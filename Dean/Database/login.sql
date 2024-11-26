USE diplomatiki_support;

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
		SELECT 'STUDENT FOUND.';
	END IF;
    
	IF (prof_email > 0) THEN
		SET ptype = 'PROF';
		SELECT 'PROFESSOR FOUND.';
	END IF;
    
	IF (sec_email > 0) THEN
		SET ptype = 'GRAM';
		SELECT 'GRAMATEIA FOUND.';
	END IF;
    
	IF (student_email = 0 AND prof_email = 0 AND sec_email = 0) THEN
		SET ptype = 'NONE';
		SELECT 'WRONG COMBINATION OF USERNAME AND PASSWORD. PLEASE TRY AGAIN.';
	END IF;

END$
DELIMITER ;
