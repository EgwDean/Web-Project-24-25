DELIMITER $
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
    WHERE email_stud = stud_email AND status != 'canceled';
        
	IF (stud_count > 0) THEN
		SET error_code = 1;
		SELECT 'exei hdh.';
	END IF;
    
    IF (stud_exist = 0) THEN
		SET error_code = 2;
		SELECT 'lathos info.';
	END IF;
    
	IF (id_count = 0) THEN
		SET error_code = 0;
		SELECT 'lathos kodikos.';
	END IF;
    
	IF (stud_count = 0 AND id_count = 1 AND stud_exist = 1) THEN
		SET error_code = 3;
		SELECT 'YESIR';
        
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

-- DROP PROCEDURE assign_thesis;

SELECT * FROM diplomatiki;
SELECT * FROM anathesi_diplomatikis;
SELECT * FROM trimelis_epitropi_diplomatikis;
SELECT * FROM student;
DELETE FROM anathesi_diplomatikis;


CALL assign_thesis('90','1003','mike.brown@example.com','dimitris.papa@university.edu',@x);
SELECT @X;