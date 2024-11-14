USE diplomatiki_support;


-- Υπό Ανάθεση Διπλωματική

DELIMITER $
DROP PROCEDURE IF EXISTS checkInvitations$
CREATE PROCEDURE checkInvitations(IN id_diploma INT)
BEGIN
DECLARE state ENUM('pending', 'active', 'canceled', 'under examination', 'finished');

SELECT anathesi_diplomatikis.status INTO state FROM anathesi_diplomatikis 
WHERE  anathesi_diplomatikis.id_diploma = id_diploma;

IF (state = 'pending') THEN
SELECT prosklisi_se_trimeli.prof_email, prosklisi_se_trimeli.status, prosklisi_se_trimeli.reply_date FROM prosklisi_se_trimeli
INNER JOIN anathesi_diplomatikis ON prosklisi_se_trimeli.id_dip = anathesi_diplomatikis.id_diploma;
END IF;
END$
DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS cancelAssignmentPending$
CREATE PROCEDURE cancelAssignmentPending(IN professor_email VARCHAR(255), IN id_diploma INT)
BEGIN

DECLARE state ENUM('pending', 'active', 'canceled', 'under examination', 'finished');
DECLARE supervisor VARCHAR(255);

SELECT anathesi_diplomatikis.status INTO state FROM anathesi_diplomatikis 
WHERE  anathesi_diplomatikis.id_diploma = id_diploma;

SELECT trimelis_epitropi_diplomatikis.supervisor INTO supervisor FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;


IF (state = 'pending') THEN
	IF(supervisor = professor_email) THEN
		UPDATE anathesi_diplomatikis
		SET status = 'canceled'
		WHERE anathesi_diplomatikis.id_diploma = id_diploma;

		DELETE FROM trimelis_epitropi_diplomatikis
		WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

		DELETE FROM prosklisi_se_trimeli
		WHERE prosklisi_se_trimeli.id_dip = id_diploma;
	ELSE 
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'You need to be a supervisor of this diploma to cancel its assignment.';
	END IF;
ELSE 
	SIGNAL SQLSTATE '45000'
	SET MESSAGE_TEXT = 'Diploma needs to be pending to cancel its assignment.';
END IF;

END$
DELIMITER ;




-- Ενεργή Διπλωματική

DELIMITER $
DROP PROCEDURE IF EXISTS createNotes$
CREATE PROCEDURE createNotes(IN professor_email VARCHAR(255), IN id_diploma INT, IN notes TEXT)
BEGIN

  DECLARE state ENUM('pending', 'active', 'canceled', 'under examination', 'finished');
  
  DECLARE supervisor_email VARCHAR(255);
  DECLARE member1_email VARCHAR(255);
  DECLARE member2_email VARCHAR(255);
  
  SELECT anathesi_diplomatikis.status INTO state FROM anathesi_diplomatikis 
  WHERE  anathesi_diplomatikis.id_diploma = id_diploma;
  
  
  IF (state = 'active') THEN

	SELECT supervisor, member1, member2
	INTO supervisor_email, member1_email, member2_email
	FROM trimelis_epitropi_diplomatikis
	WHERE id_dipl = id_diploma;


	IF (professor_email = supervisor_email OR professor_email = member1_email OR professor_email = member2_email) THEN
		IF NOT EXISTS (SELECT * FROM professor_notes WHERE professor_notes.professor_email = professor_email AND professor_notes.id_diplom = id_diploma) THEN
			INSERT INTO professor_notes (professor_email, id_diplom, notes)
			VALUES (professor_email, id_diploma, notes);
		ELSE
			UPDATE professor_notes
			SET professor_notes.notes = notes
			WHERE professor_notes.professor_email = professor_email AND professor_notes.id_diplom = id_diploma;
		END IF;
	ELSE
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'You need to be a supervisor or member of this diploma to add notes.';
	END IF;
  ELSE
	SIGNAL SQLSTATE '45000'
	SET MESSAGE_TEXT = 'Diploma needs to be active in order to add notes.';

  END IF;

END$
DELIMITER ;



DELIMITER $
DROP PROCEDURE IF EXISTS cancelAssignmentActive$
CREATE PROCEDURE cancelAssignmentActive(IN professor_email VARCHAR(255), IN id_diploma INT)
BEGIN

DECLARE state ENUM('pending', 'active', 'canceled', 'under examination', 'finished');
DECLARE supervisor VARCHAR(255);
DECLARE startDate DATE;
DECLARE currentDate DATE;

SET currentDate = CURDATE();

SELECT anathesi_diplomatikis.status INTO state FROM anathesi_diplomatikis 
WHERE  anathesi_diplomatikis.id_diploma = id_diploma;

SELECT trimelis_epitropi_diplomatikis.supervisor INTO supervisor FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

SELECT anathesi_diplomatikis.start_date INTO startDate FROM anathesi_diplomatikis 
WHERE  anathesi_diplomatikis.id_diploma = id_diploma;


IF (state = 'active') THEN
	IF (supervisor = professor_email) THEN
		IF (DATEDIFF(currentDate, startDate) >= 730) THEN
			UPDATE anathesi_diplomatikis
			SET status = 'canceled'
			WHERE anathesi_diplomatikis.id_diploma = id_diploma;

			DELETE FROM trimelis_epitropi_diplomatikis
			WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

			DELETE FROM prosklisi_se_trimeli
			WHERE prosklisi_se_trimeli.id_dip = id_diploma;
        ELSE
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'A minimum of 2 years since assignment is required to cancel this assignment.';
        END IF;
	ELSE 
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'You need to be a supervisor of this diploma to cancel its assignment.';
	END IF;
END IF;
END$
DELIMITER ;




DELIMITER $
DROP PROCEDURE IF EXISTS setUnderExam$
CREATE PROCEDURE setUnderExam(IN professor_email VARCHAR(255), IN id_diploma INT)
BEGIN

DECLARE state ENUM('pending', 'active', 'canceled', 'under examination', 'finished');
DECLARE supervisor VARCHAR(255);

SELECT anathesi_diplomatikis.status INTO state FROM anathesi_diplomatikis 
WHERE  anathesi_diplomatikis.id_diploma = id_diploma;

SELECT trimelis_epitropi_diplomatikis.supervisor INTO supervisor FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;


IF (state = 'active') THEN
	IF(supervisor = professor_email) THEN
		UPDATE anathesi_diplomatikis
		SET status = 'under examination'
		WHERE anathesi_diplomatikis.id_diploma = id_diploma;
	ELSE 
		SIGNAL SQLSTATE '45000'
		SET MESSAGE_TEXT = 'You need to be a supervisor of this diploma to set it under examination.';
	END IF;
ELSE 
	SIGNAL SQLSTATE '45000'
	SET MESSAGE_TEXT = 'Diploma needs to be active to be examined.';
END IF;

END$
DELIMITER ;






-- Υπό Εξέταση Διπλωματική

DELIMITER $
DROP PROCEDURE IF EXISTS seeDiploma$
CREATE PROCEDURE seeDiploma(IN id_diploma INT)
BEGIN
SELECT anathesi_diplomatikis.pdf_main_diploma FROM anathesi_diplomatikis 
WHERE anathesi_diplomatikis.id_diploma = id_diploma;
END$
DELIMITER ;


# Το β) δεν απαντήθηκε


DELIMITER $
DROP PROCEDURE IF EXISTS gradeSubmit$
CREATE PROCEDURE gradeSubmit(IN professor_email VARCHAR(255), IN id_diploma INT, IN grade DECIMAL(3, 2))
BEGIN

DECLARE supervisor VARCHAR(255);
DECLARE member1 VARCHAR(255);
DECLARE member2 VARCHAR(255);

SELECT trimelis_epitropi_diplomatikis.supervisor INTO supervisor FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

SELECT trimelis_epitropi_diplomatikis.member1 INTO member1 FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

SELECT trimelis_epitropi_diplomatikis.member2 INTO member2 FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;


IF (supervisor = professor_email) THEN
	UPDATE eksetasi_diplomatikis
	SET grade1 = grade
    WHERE eksetasi_diplomatikis.id_diplomatikis = id_diploma;
ELSEIF (member1 = professor_email) THEN
	UPDATE eksetasi_diplomatikis
	SET grade2 = grade
    WHERE eksetasi_diplomatikis.id_diplomatikis = id_diploma;
ELSEIF (member2 = professor_email) THEN
	UPDATE eksetasi_diplomatikis
	SET grade3 = grade
    WHERE eksetasi_diplomatikis.id_diplomatikis = id_diploma;
END IF;
END$
DELIMITER ;



DELIMITER $
DROP PROCEDURE IF EXISTS seeGrades$
CREATE PROCEDURE seeGrades(IN id_diploma INT)
BEGIN
SELECT eksetasi_diplomatikis.grade1, eksetasi_diplomatikis.grade2, eksetasi_diplomatikis.grade3 FROM eksetasi_diplomatikis
WHERE eksetasi_diplomatikis.id_diplomatikis = id_diploma;
END$
DELIMITER ;