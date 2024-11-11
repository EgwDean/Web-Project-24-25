DELIMITER $
CREATE PROCEDURE login(IN pname VARCHAR(30), IN pcode VARCHAR(30), OUT ptype VARCHAR(30))
BEGIN 
	DECLARE student_email INT;
	DECLARE prof_email INT;
    DECLARE gram_email INT;
    
    
    SELECT COUNT(*)
    INTO student_email
    FROM student
    WHERE email = pname AND password = pcode;
    
	SELECT COUNT(*)
    INTO prof_email
    FROM professor
    WHERE email = pname AND password = pcode;
    
    SELECT COUNT(*)
    INTO gram_email
    FROM administration
    WHERE email = pname AND password = pcode;

	IF (student_email > 0) THEN
		SET ptype = 'STUDENT';
		SELECT 'STUDENT FOUND.';
	END IF;
    
	IF (prof_email > 0) THEN
		SET ptype = 'PROF';
		SELECT 'PROFESSOR FOUND.';
	END IF;
    
	IF (gram_email > 0) THEN
		SET ptype = 'GRAM';
		SELECT 'GRAMATEIA FOUND.';
	END IF;
    
	IF (student_email = 0 AND prof_email = 0 AND gram_email = 0) THEN
		SET ptype = 'NONE';
		SELECT 'WRONG COMBINATION OF USERNAME AND PASSWORD. PLEASE TRY AGAIN.';
	END IF;

END$
DELIMITER ;

-- DROP PROCEDURE login;

CALL login('admin1@university.com', 'p7', @x);
SELECT @x;