-- -- STORED PROCEDURE FOR USER LOGIN


DELIMITER $
DROP PROCEDURE IF EXISTS login$
CREATE PROCEDURE login(IN pname VARCHAR(30), IN pcode VARCHAR(30), OUT ptype VARCHAR(30))
BEGIN 
	DECLARE student_email INT;
	DECLARE prof_email INT;
    DECLARE sec_email INT;
    
    
    SELECT COUNT(*)
    INTO student_email
    FROM student
    WHERE email_student = pname AND password = pcode;
    
	SELECT COUNT(*)
    INTO prof_email
    FROM professor
    WHERE email_professor = pname AND password = pcode;
    
    SELECT COUNT(*)
    INTO sec_email
    FROM secretary
    WHERE email_sec = pname AND password = pcode;

	IF (student_email > 0) THEN
		SET ptype = 'STUDENT';
		#SELECT 'STUDENT FOUND.';
	END IF;
    
	IF (prof_email > 0) THEN
		SET ptype = 'PROF';
		#SELECT 'PROFESSOR FOUND.';
	END IF;
    
	IF (sec_email > 0) THEN
		SET ptype = 'GRAM';
		#SELECT 'GRAMATEIA FOUND.';
	END IF;
    
	IF (student_email = 0 AND prof_email = 0 AND sec_email = 0) THEN
		SET ptype = 'NONE';
		#SELECT 'WRONG COMBINATION OF USERNAME AND PASSWORD. PLEASE TRY AGAIN.';
	END IF;

END$
DELIMITER ;






-- STORED PROCEDURES FOR PROFESSOR PAGE



DELIMITER $
DROP PROCEDURE IF EXISTS upload_pdf$
CREATE PROCEDURE upload_pdf(IN d_id VARCHAR(30))
BEGIN 
	DECLARE link VARCHAR(50);
	
    SET link = CONCAT('uploads/',d_id,'.pdf');
    
    #SELECT link;    
    
    UPDATE diplomatiki
    SET pdf_link_topic = link
    WHERE id_diplomatiki = CAST(d_id AS SIGNED);

END$
DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS insert_to_dip$
CREATE PROCEDURE insert_to_dip(IN pr_email VARCHAR(100), IN dip_title VARCHAR(100), IN dip_descr VARCHAR(100), IN dip_stat VARCHAR(100), OUT dip_cod INT)
BEGIN 
	DECLARE title_count INT;
   
    
    SELECT COUNT(*)
    INTO title_count
    FROM diplomatiki
    WHERE title = dip_title;
    
    IF (title_count > 0) THEN
		#SELECT 'YPARXEI HDH';
        SET dip_cod = 0;
        
	ELSE 
		INSERT INTO diplomatiki (email_prof, title, description, status) 
        VALUES (pr_email, dip_title, dip_descr, dip_stat);
        #SELECT 'OK';
        SET dip_cod = 1;
    END IF;
END$
DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS update_dip$
CREATE PROCEDURE update_dip(IN id_dip INT, IN new_title VARCHAR(100), IN new_descr text, OUT dip_cod INT)
BEGIN 

	DECLARE title_count INT;
   
    SELECT COUNT(*)
    INTO title_count
    FROM diplomatiki
    WHERE title = new_title AND id_diplomatiki != id_dip;
    
    IF (title_count > 0) THEN
		#SELECT 'YPARXEI HDH';
        SET dip_cod = 0;
        
	ELSE 
		UPDATE diplomatiki 
        SET title = new_title, description = new_descr
        WHERE id_diplomatiki = id_dip;
        
        #SELECT 'OK';
        
        SET dip_cod = 1;
    END IF;
END$
DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS assign_thesis$
CREATE PROCEDURE assign_thesis(IN dip_id VARCHAR(30), IN am VARCHAR(30), IN stud_email VARCHAR(30), IN prof_email VARCHAR(30), OUT error_code INT)
BEGIN 
	DECLARE id_count INT;
	DECLARE stud_count INT;
    DECLARE stud_exist INT;
    
    SELECT COUNT(*)
    INTO id_count
    FROM diplomatiki
    WHERE email_prof = prof_email AND id_diplomatiki = CAST(dip_id AS SIGNED) AND status != 'given';
    
    SELECT COUNT(*)
    INTO stud_exist
    FROM student
    WHERE  email_student = stud_email AND student_number = am;
    
    SELECT COUNT(*)
    INTO stud_count
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND (status = 'pending' OR status = 'active' OR status = 'under examination' OR status = 'finished');
        
	IF (stud_count > 0) THEN
		SET error_code = 1;
		#SELECT 'exei hdh.';
	END IF;
    
    IF (stud_exist = 0) THEN
		SET error_code = 2;
		#SELECT 'lathos info.';
	END IF;
    
	IF (id_count = 0) THEN
		SET error_code = 0;
		#SELECT 'lathos kodikos.';
	END IF;
    
	IF (stud_count = 0 AND id_count = 1 AND stud_exist = 1) THEN
		SET error_code = 3;
		#SELECT 'YESIR';
        
        INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links) 
		VALUES(stud_email, dip_id, 'pending', current_date(), NULL, NULL, NULL, NULL);
        
        INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1 , member2 )
        VALUES(dip_id, prof_email, NULL, NULL);
        
        UPDATE diplomatiki 
		SET status = 'given'
        WHERE id_diplomatiki = dip_id;
        
    END IF;

END$
DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS recall_thesis$
CREATE PROCEDURE recall_thesis(
    IN dip_id INT, 
    IN stud_email VARCHAR(255), 
    IN prof_email VARCHAR(255), 
    IN meet_number INT,  -- Αριθμός της Γενικής Συνέλευσης
    IN meet_year YEAR,    -- Έτος της Γενικής Συνέλευσης
    OUT error_code INT
)
BEGIN 
    DECLARE id_count INT;
    DECLARE stud_count INT;
    DECLARE assign_date DATE;
    DECLARE assignment_status ENUM('pending', 'active', 'canceled_by_student', 'canceled_by_professor', 'recalled', 'under examination', 'finished');

    -- Προεπιλογή error_code = 0 (γενικό σφάλμα)
    SET error_code = 0;

    -- Έλεγχος αν η διπλωματική ανήκει στον καθηγητή και είναι 'given'
    SELECT COUNT(*)
    INTO id_count
    FROM diplomatiki
    WHERE email_prof = prof_email 
	AND id_diplomatiki = dip_id 
	AND status = 'given';

    IF id_count = 0 THEN
        -- Σφάλμα: Διπλωματική δεν ανήκει στον καθηγητή ή δεν είναι διαθέσιμη
        SET error_code = -1;
    END IF;

    -- Ανάκτηση κατάστασης και ημερομηνίας ανάθεσης
    SELECT status, start_date
    INTO assignment_status, assign_date
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email 
	AND id_diploma = dip_id;

    IF assignment_status = 'pending' THEN
        -- Διαγραφή από σχετικoύς πίνακες
        DELETE FROM trimelis_epitropi_diplomatikis
        WHERE id_dipl = dip_id;  

        DELETE FROM prosklisi_se_trimeli
        WHERE id_dip = dip_id;

        -- Ενημέρωση κατάστασης διπλωματικής
        UPDATE diplomatiki
        SET status = 'available'
        WHERE id_diplomatiki = dip_id;

        -- Ενημέρωση κατάστασης ανάθεσης
        UPDATE anathesi_diplomatikis
        SET status = 'recalled'
        WHERE id_diploma = dip_id;

        SET error_code = 1; -- Επιτυχής ανάκληση
    ELSEIF assignment_status = 'active' THEN
        -- Έλεγχος αν έχουν περάσει 2 έτη από την ανάθεση
        IF DATEDIFF(CURDATE(), assign_date) >= 730 THEN
        
            -- Ενημέρωση κατάστασης ανάθεσης σε 'canceled_by_professor'
            UPDATE anathesi_diplomatikis
            SET status = 'canceled_by_professor'
            WHERE id_diploma = dip_id;
            
            
             INSERT INTO cancellations(id_d, meeting_number, meeting_year)
			 VALUES (dip_id, meet_number, meet_year);

            SET error_code = 2; -- Επιτυχής ακύρωση ανάθεσης
        ELSE
            SET error_code = -2; -- Σφάλμα: Δεν έχουν παρέλθει 2 έτη
        END IF;
    ELSE
        SET error_code = -3; -- Άγνωστη κατάσταση ανάθεσης
    END IF;
END$
DELIMITER ;



DELIMITER $
DROP PROCEDURE IF EXISTS accept$
CREATE PROCEDURE accept(IN pname VARCHAR(30), IN pcode VARCHAR(30), OUT error_code INT)
BEGIN 
    
    DECLARE prof1 VARCHAR(30);
	DECLARE prof2 VARCHAR(30);
    DECLARE y INT;
    DECLARE count INT;
    
    SET error_code = 0;
    SET y = 0;    
    
    SELECT COUNT(*)
    INTO count
    FROM trimelis_epitropi_diplomatikis
    WHERE id_dipl = pcode;
    
    SELECT member1, member2
    INTO prof1, prof2
    FROM trimelis_epitropi_diplomatikis
    WHERE id_dipl = pcode;
    
    SELECT prof1, prof2;
    
    IF (prof1 IS NULL) THEN
		UPDATE trimelis_epitropi_diplomatikis
		SET member1 = pname
		WHERE id_dipl = pcode;
        SET y = 1;
        
        	UPDATE prosklisi_se_trimeli
			SET status = 'accepted', reply_date = CURDATE()
			WHERE prof_email = pname AND id_dip = pcode;
        
	END IF;
    
    IF (y = 0 AND prof2 IS NULL) THEN
    
    	UPDATE trimelis_epitropi_diplomatikis
		SET member2 = pname
		WHERE id_dipl = pcode;
        
        SET y = 2;
	
        UPDATE prosklisi_se_trimeli
		SET status = 'accepted', reply_date = CURDATE()
		WHERE prof_email = pname AND id_dip = pcode;
        
        
		UPDATE prosklisi_se_trimeli
        SET status = 'declined', reply_date = CURDATE()
        WHERE status = 'pending' AND id_dip = pcode;
        
        UPDATE anathesi_diplomatikis
        SET status = 'active'
        WHERE id_diploma = pcode AND status = 'pending';
            
	END IF;
    
	IF (y = 0 OR count = 0) THEN
		SELECT 'error';
        SET error_code = 1;
        
		    UPDATE prosklisi_se_trimeli
			SET status = 'declined', reply_date = CURDATE()
			WHERE prof_email = pname AND id_dip = pcode;
	END IF;
    
END$
DELIMITER ;



-- Ενεργή Διπλωματική

DELIMITER $
DROP PROCEDURE IF EXISTS createNotes$
CREATE PROCEDURE createNotes(IN professor_email VARCHAR(255), IN id_diploma INT, IN notes TEXT, OUT error_code INT)
BEGIN

DECLARE state ENUM('pending', 'active', 'canceled_by_student', 'canceled_by_professor', 'recalled', 'under examination', 'finished');
  
DECLARE supervisor_email VARCHAR(255);
DECLARE member1_email VARCHAR(255);
DECLARE member2_email VARCHAR(255);
SET error_code=0;

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
	SET error_code = 1;
	#SELECT 'You need to be a supervisor or member of this diploma to add notes.';	
END IF;
END$
DELIMITER ;



-- Υπό Εξέταση Διπλωματική

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







-- STORED PROCEDURES FOR STUDENT PAGE



DELIMITER $
DROP PROCEDURE IF EXISTS get_date_diff$
CREATE PROCEDURE get_date_diff(IN student_email VARCHAR(255))
BEGIN
    DECLARE startDate DATE;
    DECLARE currentDate DATE;
    DECLARE dateDiff INT;

    -- Set the current date
    SET currentDate = CURDATE();

    -- Fetch the start date for the given student email
    SELECT anathesi_diplomatikis.start_date INTO startDate
    FROM anathesi_diplomatikis
    WHERE anathesi_diplomatikis.email_stud = student_email;

    -- Calculate the date difference
    SELECT DATEDIFF(currentDate, startDate) AS dateDiff;

END$
DELIMITER ;






-- TRIGGERS



DELIMITER //
DROP TRIGGER IF EXISTS log_insert_anathesi //
CREATE TRIGGER log_insert_anathesi
AFTER INSERT ON anathesi_diplomatikis
FOR EACH ROW
BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (NEW.id_diploma, CONCAT(
    curr_date, ' - New thesis assigned. Status : pending.'
  ));
END //

DELIMITER ;

DELIMITER //
DROP TRIGGER IF EXISTS log_update_anathesi //
CREATE TRIGGER log_update_anathesi
AFTER UPDATE ON anathesi_diplomatikis
FOR EACH ROW
BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (NEW.id_diploma, CONCAT(
    curr_date, ' - UPDATE: ',
    'Old status: ', IFNULL(OLD.status, 'NULL'), ', ', 'New status: ', IFNULL(NEW.status, 'NULL'), ', '
  ));
END //
DELIMITER ;


DELIMITER //
DROP TRIGGER IF EXISTS log_delete_anathesi //
CREATE TRIGGER log_delete_anathesi
AFTER DELETE ON anathesi_diplomatikis
FOR EACH ROW
BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (OLD.id_diploma, CONCAT(
    curr_date, ' - DELETED: '
  ));
END //
DELIMITER ;



DELIMITER //
DROP TRIGGER IF EXISTS update_final_grade //
CREATE TRIGGER update_final_grade
BEFORE UPDATE ON eksetasi_diplomatikis
FOR EACH ROW
BEGIN
  -- Ensure that only one grade is updated at a time
  IF ( (OLD.grade1 != NEW.grade1) + (OLD.grade2 != NEW.grade2) + (OLD.grade3 != NEW.grade3) ) > 1 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Only one grade column can be updated at a time.';
  ELSE
    -- Recalculate the final grade, treating NULL as 0
    SET NEW.final_grade = (IFNULL(NEW.grade1, 0) + IFNULL(NEW.grade2, 0) + IFNULL(NEW.grade3, 0)) / 3;
  END IF;
END //
DELIMITER ;


DELIMITER //
DROP TRIGGER IF EXISTS set_status_finished //
CREATE TRIGGER set_status_finished
AFTER UPDATE ON eksetasi_diplomatikis
FOR EACH ROW
BEGIN
    IF (NEW.grade1 IS NOT NULL AND NEW.grade2 IS NOT NULL AND NEW.grade3 IS NOT NULL AND NEW.final_grade IS NOT NULL) THEN
        UPDATE anathesi_diplomatikis
        SET status = 'finished'
        WHERE anathesi_diplomatikis.id_diploma = NEW.id_diplomatikis;
    END IF;
END //
DELIMITER ;
