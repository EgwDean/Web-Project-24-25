USE html;
DELIMITER $

-- Procedure login that checks if login credentials exist
DROP PROCEDURE IF EXISTS gradeCorrection$
CREATE PROCEDURE login(IN pname VARCHAR(30), IN pcode VARCHAR(30), OUT ptype VARCHAR(30))
BEGIN 
	DECLARE user_num INT;
	DECLARE admin_num INT;
    
    SELECT COUNT(*)
    INTO user_num
    FROM users 
    WHERE username = pname AND password = pcode;
    
	SELECT COUNT(*)
    INTO admin_num
    FROM admins
    WHERE username = pname AND password = pcode;

	IF (user_num > 0) THEN
		SET ptype = 'USER';
		SELECT 'USER FOUND.';
	END IF;
    
	IF (admin_num > 0) THEN
		SET ptype = 'ADMIN';
		SELECT 'ADMIN FOUND.';
	END IF;
    
	IF (user_num = 0 AND admin_num = 0) THEN
		SET ptype = 'NONE';
		SELECT 'WRONG COMBINATION OF USERNAME AND PASSWORD. PLEASE TRY AGAIN.';
	END IF;

END$
DELIMITER ;

# Procedure name_taken
DELIMITER $
CREATE PROCEDURE name_taken(IN tname VARCHAR(30), OUT yes_no INT)
BEGIN 
	DECLARE num INT;
    DECLARE result INT;
    DECLARE user_num INT;
	DECLARE admin_num INT;
    
    SELECT COUNT(*)
    INTO user_num
    FROM users 
    WHERE username = tname;
    
	SELECT COUNT(*)
    INTO admin_num
    FROM admins
    WHERE username = tname;
    
    IF (user_num > 0 OR admin_num > 0) THEN
		SELECT "NAME TAKEN!.";
        SET yes_no = 1;
	ELSE 
		SELECT "NAME NOT FOUND.";
		SET yes_no = 0;
	END IF;

END$
DELIMITER ;


DELIMITER $
CREATE PROCEDURE email_taken(IN temail VARCHAR(30), OUT email_found INT)
BEGIN 
	DECLARE num INT;
    DECLARE result INT;
    DECLARE user_mail INT;
	DECLARE admin_mail INT;
    
    SELECT COUNT(*)
    INTO user_mail
    FROM users 
    WHERE email = temail;
    
	SELECT COUNT(*)
    INTO admin_mail
    FROM admins
    WHERE email = temail;
    
    IF (admin_mail > 0 OR user_mail > 0) THEN
		SELECT "EMAIL TAKEN!.";
        SET email_found = 1;
	ELSE 
		SELECT "EMAIL NOT FOUND.";
		SET email_found = 0;
	END IF;

END$
DELIMITER ;
