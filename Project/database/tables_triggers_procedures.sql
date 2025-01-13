DROP DATABASE IF EXISTS diplomatiki_support;
CREATE DATABASE diplomatiki_support;

USE diplomatiki_support;


CREATE TABLE professor (
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  email_professor VARCHAR(255) PRIMARY KEY NOT NULL,
  topic VARCHAR(255) DEFAULT NULL,
  landline VARCHAR(13) DEFAULT NULL,
  mobile VARCHAR(13) NOT NULL,
  department VARCHAR(255) NOT NULL,
  university VARCHAR(255) NOT NULL
  );		


CREATE TABLE student (
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  student_number INT NOT NULL,
  street VARCHAR(255) NOT NULL,
  number VARCHAR(10) NOT NULL,
  city VARCHAR(255) NOT NULL, 
  postcode VARCHAR(5) NOT NULL,
  father_name VARCHAR(255) NOT NULL,
  landline_telephone VARCHAR(13) DEFAULT NULL,
  mobile_telephone VARCHAR(13) NOT NULL, 
  email_student VARCHAR(255) PRIMARY KEY NOT NULL
);


CREATE TABLE secretary (
	email_sec VARCHAR(255) PRIMARY KEY NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE diplomatiki (
  id_diplomatiki INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  email_prof VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,    
  description TEXT NOT NULL,                            
  pdf_link_topic VARCHAR(255) DEFAULT NULL,
  status ENUM('available', 'given') NOT NULL
)AUTO_INCREMENT = 1;



CREATE TABLE eksetasi_diplomatikis (
  id_diplomatikis INT NOT NULL,
  email_st VARCHAR(255) NOT NULL,
  exam_date DATETIME DEFAULT NULL,                    
  exam_room VARCHAR(255) DEFAULT NULL,
  grade1 DECIMAL(4, 2) DEFAULT NULL,               
  grade2 DECIMAL(4, 2) DEFAULT NULL,               
  grade3 DECIMAL(4, 2) DEFAULT NULL,               
  final_grade DECIMAL(4, 2) DEFAULT NULL,          
  praktiko_bathmologisis VARCHAR(255) DEFAULT NULL
);


CREATE TABLE anathesi_diplomatikis (
  email_stud VARCHAR(255) NOT NULL,                     
  id_diploma INT NOT NULL,                     
  status ENUM('pending', 'active', 'canceled_by_student', 'canceled_by_professor', 'recalled', 'under examination', 'finished') NOT NULL DEFAULT 'pending',  
  start_date DATE,                        
  end_date DATE DEFAULT NULL,                                   
  Nemertes_link VARCHAR(255) DEFAULT NULL,
  pdf_main_diploma VARCHAR(255) DEFAULT NULL,
  external_links TEXT DEFAULT NULL, 
  protocol_number INT DEFAULT NULL
);


CREATE TABLE professor_notes (
  professor_email VARCHAR(255) NOT NULL,
  id_diplom INT NOT NULL,
  notes TEXT NOT NULL
);


CREATE TABLE trimelis_epitropi_diplomatikis (                 
  id_dipl INT NOT NULL,    
  supervisor VARCHAR(255) NOT NULL,               
  member1 VARCHAR(255) DEFAULT NULL,                  
  member2 VARCHAR(255) DEFAULT NULL
);


CREATE TABLE prosklisi_se_trimeli (
  student_email VARCHAR(255) NOT NULL,                             
  prof_email VARCHAR(255) NOT NULL,                   
  id_dip INT NOT NULL,                 
  status ENUM('pending', 'accepted', 'declined') NOT NULL DEFAULT 'pending', 
  reply_date DATE DEFAULT NULL, 
  invitation_date DATE DEFAULT NULL
);

CREATE TABLE log (
  id_di INT NOT NULL, 
  record TEXT NOT NULL
);

CREATE TABLE cancellations (
	id_d INT NOT NULL, 
    meeting_number INT NOT NULL, 
    meeting_year INT NOT NULL
);

-- -- STORED PROCEDURE FOR USER LOGIN

DELIMITER $
DROP PROCEDURE IF EXISTS login$
CREATE PROCEDURE login(IN pname VARCHAR(255), IN pcode VARCHAR(255), OUT ptype VARCHAR(255))
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
	END IF;
    
	IF (sec_email > 0) THEN
		SET ptype = 'GRAM';
	END IF;
    
	IF (student_email = 0 AND prof_email = 0 AND sec_email = 0) THEN
		SET ptype = 'NONE';       #  WRONG COMBINATION OF USERNAME AND PASSWORD. PLEASE TRY AGAIN.
	END IF;
END$
DELIMITER ;






-- STORED PROCEDURES FOR PROFESSOR PAGE



DELIMITER $
DROP PROCEDURE IF EXISTS upload_pdf$
CREATE PROCEDURE upload_pdf(IN d_id VARCHAR(255))
BEGIN 
	DECLARE link VARCHAR(50);
	
    SET link = CONCAT('../uploads/pdf_link_topic/',d_id,'.pdf');
    
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
        SET dip_cod = 0;		# ALREADY EXISTS
        
	ELSE 
		INSERT INTO diplomatiki (email_prof, title, description, status) 
        VALUES (pr_email, dip_title, dip_descr, dip_stat);
        SET dip_cod = 1;		# SUCCESS
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
        SET dip_cod = 0;		# ALREADY EXISTS
        
	ELSE 
		UPDATE diplomatiki 
        SET title = new_title, description = new_descr
        WHERE id_diplomatiki = id_dip;
        
        SET dip_cod = 1;		# SUCCESS
    END IF;
END$
DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS assign_thesis$
CREATE PROCEDURE assign_thesis(IN dip_id VARCHAR(255), IN am VARCHAR(255), IN stud_email VARCHAR(255), IN prof_email VARCHAR(255), OUT error_code INT)
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
		SET error_code = 1;		# STUDENT ALREADY HAS A THESIS
	END IF;
    
    IF (stud_exist = 0) THEN
		SET error_code = 2;		# WRONG STUDENT INFO
	END IF;
    
	IF (id_count = 0) THEN
		SET error_code = 0;		# WRONG THESIS ID
	END IF;
    
	IF (stud_count = 0 AND id_count = 1 AND stud_exist = 1) THEN
		SET error_code = 3;		# CORRECT
        
        INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links) 
		VALUES(stud_email, dip_id, 'pending', NULL, NULL, NULL, NULL, NULL);
        
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
    IN meet_number VARCHAR(255),  -- Αριθμός της Γενικής Συνέλευσης
    IN meet_year VARCHAR(255),    -- Έτος της Γενικής Συνέλευσης
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
    
    SELECT COUNT(*)
    INTO stud_count
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND id_diploma = dip_id AND status = 'pending';

    -- Ανάκτηση κατάστασης και ημερομηνίας ανάθεσης
    SELECT status, start_date
    INTO assignment_status, assign_date
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND id_diploma = dip_id AND status = 'active';

    IF (stud_count = 1 AND id_count = 1) THEN
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
        WHERE id_diploma = dip_id AND status != 'canceled_by_professor';

        SET error_code = 1; -- Επιτυχής ανάκληση
        
	END IF;
        
    IF (assignment_status = 'active' AND id_count = 1) THEN
    
		IF (meet_number < 0 OR meet_number > 10000 OR meet_number IS NULL OR meet_year < 2000 OR meet_year > 2030 OR meet_year IS NULL) THEN
			SET error_code = -4;
        -- Έλεγχος αν έχουν περάσει 2 έτη από την ανάθεση

		ELSEIF DATEDIFF(CURDATE(), assign_date) >= 730 THEN
			
				-- Ενημέρωση κατάστασης ανάθεσης σε 'canceled_by_professor'
				UPDATE anathesi_diplomatikis
				SET status = 'canceled_by_professor'
				WHERE id_diploma = dip_id AND status != 'recalled';
                
				UPDATE diplomatiki
                SET status = 'available'
                WHERE id_diplomatiki = dip_id;
				
				DELETE FROM trimelis_epitropi_diplomatikis
				WHERE id_dipl = dip_id;  
				
				INSERT INTO cancellations(id_d, meeting_number, meeting_year)
				VALUES (dip_id, meet_number, meet_year);

				SET error_code = 2; -- Επιτυχής ακύρωση ανάθεσης
			ELSE
        
            SET error_code = -2; -- Σφάλμα: Δεν έχουν παρέλθει 2 έτη
        END IF;
    END IF;
    
   IF id_count = 0 THEN
        -- Σφάλμα: Διπλωματική δεν ανήκει στον καθηγητή ή δεν είναι διαθέσιμη
        SET error_code = -1;
   END IF;
   
   IF (stud_count = 0 AND assignment_status IS NULL) THEN
		SET error_code = -3;
   END IF;
    
    
END$
DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS accept$
CREATE PROCEDURE accept(IN pname VARCHAR(255), IN pcode VARCHAR(255), OUT error_code INT)
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
        SET status = 'active' ,start_date = CURDATE()
        WHERE id_diploma = pcode AND status = 'pending';
            
	END IF;
    
	IF (count = 0) THEN
		-- Case 1: Diploma thesis does not exist (invalid pcode)
		SET error_code = 2; 

		UPDATE prosklisi_se_trimeli
		SET status = 'declined', reply_date = CURDATE()
		WHERE prof_email = pname AND id_dip = pcode;

	ELSEIF (y = 0) THEN
		-- Case 2: Neither member1 nor member2 was updated (no slot available)
		SELECT 'error: no available slot';
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
	SET error_code = 1;      # You need to be a supervisor or member of this diploma to add notes.
END IF;
END$
DELIMITER ;



DELIMITER $
DROP PROCEDURE IF EXISTS setUnderExam$
CREATE PROCEDURE setUnderExam(IN dip_id INT,  OUT error_code INT)
BEGIN 
DECLARE protocol INT;
DECLARE email_student VARCHAR(255);

SELECT anathesi_diplomatikis.protocol_number, email_stud INTO protocol, email_student FROM anathesi_diplomatikis 
WHERE id_diploma = dip_id AND status = 'active';

SELECT email_student;

IF (protocol IS NOT NULL) THEN
	UPDATE anathesi_diplomatikis 
	SET status = 'under examination' 
	WHERE id_diploma = dip_id AND status = 'active';
       
    INSERT INTO eksetasi_diplomatikis(id_diplomatikis, email_st)
    VALUES (dip_id, email_student);
    
    SET error_code = 0;
ELSE 
	SET error_code = 1;
END IF;
END$
DELIMITER ;





-- Υπό Εξέταση Διπλωματική


DELIMITER $
DROP PROCEDURE IF EXISTS gradeSubmit$
CREATE PROCEDURE gradeSubmit(IN professor_email VARCHAR(255), IN id_diploma INT, IN grade DECIMAL(4, 2))
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
    WHERE anathesi_diplomatikis.email_stud = student_email AND (anathesi_diplomatikis.status = 'active' 
        OR anathesi_diplomatikis.status = 'under examination' 
        OR anathesi_diplomatikis.status = 'pending' 
        OR anathesi_diplomatikis.status = 'finished');

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
  
  IF (OLD.status != NEW.status ) THEN
  INSERT INTO log (id_di, record)
  VALUES (NEW.id_diploma, CONCAT(
    curr_date, ' - UPDATE: ',
    'Old status: ', IFNULL(OLD.status, 'NULL'), ', ', 'New status: ', IFNULL(NEW.status, 'NULL'), ', '
  ));
  END IF;
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
  -- Recalculate the final grade, treating NULL as 0
  SET NEW.final_grade = (IFNULL(NEW.grade1, 0) + IFNULL(NEW.grade2, 0) + IFNULL(NEW.grade3, 0)) / 3;
END //
DELIMITER ;

DELIMITER $

-- Έλεγχος αν η διπλωματική είναι υπό εξέταση με προβιβάσιμο βαθμό και έχει αναρτηθεί το link στη Νημερτής
DROP PROCEDURE IF EXISTS changeToFinished$
CREATE PROCEDURE changeToFinished(IN diplomatiki_id INT, OUT status INT)
BEGIN
    DECLARE nemertes_status INT;
    DECLARE final_grade_status INT;
    DECLARE under_exam_status INT;

    SELECT COUNT(*) 
    INTO nemertes_status
    FROM anathesi_diplomatikis
    WHERE id_diploma = diplomatiki_id AND Nemertes_link IS NOT NULL;

    IF nemertes_status > 0 THEN 
        SET nemertes_status = 1;
    ELSE 
        SET nemertes_status = 0;
    END IF;

    SELECT COUNT(*) 
    INTO final_grade_status
    FROM eksetasi_diplomatikis
    WHERE id_diplomatikis = diplomatiki_id AND final_grade >= 5;

    IF final_grade_status > 0 THEN 
        SET final_grade_status = 1;
    ELSE 
        SET final_grade_status = 0;
    END IF;

    SELECT COUNT(*) 
    INTO under_exam_status
    FROM anathesi_diplomatikis
    WHERE id_diploma = diplomatiki_id AND anathesi_diplomatikis.status = 'under examination';

    IF under_exam_status > 0 THEN 
        SET under_exam_status = 1;
    ELSE 
        SET under_exam_status = 0;
    END IF;

    IF (nemertes_status + final_grade_status + under_exam_status = 3) THEN
        SET status = 1;
    ELSE
        SET status = 0;
    END IF;
END$

DELIMITER ;

-- Διαγραφή στοιχείων περατωμένης διπλωματικής
DELIMITER $
DROP PROCEDURE IF EXISTS deleteById$
CREATE PROCEDURE deleteById(IN id INT)
BEGIN
    UPDATE diplomatiki SET status = 'available' WHERE id = id_diplomatiki;
    UPDATE anathesi_diplomatikis SET status = 'canceled_by_student' WHERE id = id_diploma AND status = "active";
    DELETE FROM professor_notes WHERE id = id_diplom;
    DELETE FROM trimelis_epitropi_diplomatikis WHERE id = id_dipl;
END$

DELIMITER ;


DELIMITER $
DROP PROCEDURE IF EXISTS returnId$ -- Επιστρέφει το ID διπλωματικής από ανάθεση
CREATE PROCEDURE returnID(IN email VARCHAR(255), OUT id INT)
BEGIN
	SELECT id_diploma INTO id FROM anathesi_diplomatikis WHERE email_stud = email ORDER BY start_date DESC LIMIT 1;
END$
DELIMITER ;

USE diplomatiki_support;

DELIMITER $
DROP PROCEDURE IF EXISTS updateExam$ -- Update ή Insert ανάλογα με το αν υπάρχει εγγραφή του πίνακα εξέτασης
CREATE PROCEDURE updateExam(IN s_email VARCHAR(255), IN e_date DATETIME, IN e_room VARCHAR(255))
BEGIN
	DECLARE rowCount INT;
    DECLARE d_id INT;
    
    SELECT id_diploma INTO d_id FROM anathesi_diplomatikis WHERE email_stud = s_email ORDER BY start_date DESC LIMIT 1;
    
    SELECT COUNT(*) INTO rowCount FROM eksetasi_diplomatikis WHERE email_st = s_email AND id_diplomatikis = d_id;
    
    IF rowCount = 0 THEN
		INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade) VALUES
		(d_id, s_email, e_date, e_room, NULL, NULL, NULL, NULL);
	ELSE
		UPDATE eksetasi_diplomatikis SET exam_date = e_date, exam_room = e_room WHERE email_st = s_email AND id_diplomatikis = d_id;
    END IF;
END$
DELIMITER ;

DELIMITER $
DROP PROCEDURE IF EXISTS sendRequest$ -- Στέλνει αιτήσεις για τριμελή επιτροπή
CREATE PROCEDURE sendRequest(IN s_email VARCHAR(255), IN p_email VARCHAR(255), OUT result_status VARCHAR(255))
BEGIN
	DECLARE rowCount INT;
    DECLARE id INT;
    
    SELECT COUNT(*) INTO rowCount FROM prosklisi_se_trimeli
    WHERE student_email = s_email AND prof_email = p_email AND (status = "pending" OR status = "accepted");
    
    SELECT id_diploma INTO id FROM anathesi_diplomatikis
    WHERE email_stud = s_email AND (anathesi_diplomatikis.status = 'active' 
        OR anathesi_diplomatikis.status = 'under examination' 
        OR anathesi_diplomatikis.status = 'pending' 
        OR anathesi_diplomatikis.status = 'finished');
    
    IF rowCount > 0 THEN
		SET result_status = 0;
	ELSE 
		INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
		VALUES (s_email, p_email, id, 'pending', NULL, CURDATE());
		SET result_status = 1;
	END IF;
END$

DELIMITER ;
