USE html;

DELIMITER $
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
	END IF;
    
	IF (admin_num > 0) THEN
		SET ptype = 'ADMIN';
	END IF;
    
	IF (user_num = 0 AND admin_num = 0) THEN
		SET ptype = 'NONE';
	END IF;

END$
DELIMITER ;

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
        SET yes_no = 1;
	ELSE 
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
        SET email_found = 1;
	ELSE 
		SET email_found = 0;
	END IF;

END$
DELIMITER ;
