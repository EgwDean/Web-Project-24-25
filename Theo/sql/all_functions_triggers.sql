DELIMITER $
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

DELIMITER $
CREATE PROCEDURE upload_pdf(IN d_id VARCHAR(30))
BEGIN 
	DECLARE link VARCHAR(50);
	
    SET link = CONCAT('uploads/',d_id,'.pdf');
    
    SELECT link;    
    
    UPDATE diplomatiki
    SET pdf_link_topic = link
    WHERE id_diplomatiki = CAST(d_id AS SIGNED);

END$
DELIMITER ;

DELIMITER $
CREATE PROCEDURE insert_to_dip(IN pr_email VARCHAR(100), IN dip_title VARCHAR(100), IN dip_descr VARCHAR(100), IN dip_stat VARCHAR(100), OUT dip_cod INT)
BEGIN 
	DECLARE title_count INT;
   
    
    SELECT COUNT(*)
    INTO title_count
    FROM diplomatiki
    WHERE title = dip_title;
    
    IF (title_count > 0) THEN
		SELECT 'YPARXEI HDH';
        SET dip_cod = 0;
        
	ELSE 
		INSERT INTO diplomatiki (email_prof, title, description, status) 
        VALUES (pr_email, dip_title, dip_descr, dip_stat);
        SELECT 'OK';
        SET dip_cod = 1;
    END IF;
END$
DELIMITER ;

DELIMITER $
CREATE PROCEDURE update_dip(IN id_dip INT, IN new_title VARCHAR(100), IN new_descr text, OUT dip_cod INT)
BEGIN 

	DECLARE title_count INT;
   
    SELECT COUNT(*)
    INTO title_count
    FROM diplomatiki
    WHERE title = new_title AND id_diplomatiki != id_dip;
    
    IF (title_count > 0) THEN
		SELECT 'YPARXEI HDH';
        SET dip_cod = 0;
        
	ELSE 
		UPDATE diplomatiki 
        SET title = new_title, description = new_descr
        WHERE id_diplomatiki = id_dip;
        
        SELECT 'OK';
        
        SET dip_cod = 1;
    END IF;
END$
DELIMITER ;

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

DELIMITER $
CREATE PROCEDURE recall_thesis(IN dip_id VARCHAR(30), IN stud_email VARCHAR(30), IN prof_email VARCHAR(30), OUT error_code INT)
BEGIN 
	DECLARE id_count INT;
	DECLARE stud_count INT;
    
    SELECT COUNT(*)
    INTO id_count
    FROM diplomatiki
    WHERE email_prof = prof_email AND id_diplomatiki = CAST(dip_id AS SIGNED) AND status = 'given';
    
    SELECT COUNT(*)
    INTO stud_count
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND id_diploma = dip_id AND status = 'pending';
        
	SET error_code = 0;
    
    IF (id_count = 1 AND stud_count = 1) THEN
		SET error_code = 1;
        SELECT 'OKAY';
        
        DELETE FROM trimelis_epitropi_diplomatikis
        WHERE id_dipl = dip_id;  
        
        DELETE FROM prosklisi_se_trimeli
        WHERE id_dip = dip_id;
        
        UPDATE diplomatiki
		SET status = 'available'
        WHERE id_diplomatiki = dip_id;
        
        UPDATE anathesi_diplomatikis
		SET status = 'canceled'
        WHERE id_diploma = dip_id;
        
    END IF;

END$
DELIMITER ;

DELIMITER $
CREATE PROCEDURE accept(IN pname VARCHAR(30), IN pcode VARCHAR(30), OUT error_code INT)
BEGIN 
    
    DECLARE prof1 VARCHAR(30);
	DECLARE prof2 VARCHAR(30);
    DECLARE y INT;
    
    SET error_code = 0;
    SET y = 0;    
    
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
        WHERE id_diploma = pcode;
            
	END IF;
    
	IF (y = 0) THEN
		SELECT 'error';
        SET error_code = 1;
        
		    UPDATE prosklisi_se_trimeli
			SET status = 'declined', reply_date = CURDATE()
			WHERE prof_email = pname AND id_dip = pcode;
	END IF;
    
END$
DELIMITER ;

DELIMITER //

CREATE TRIGGER log_insert_anathesi
AFTER INSERT ON anathesi_diplomatikis
FOR EACH ROW
BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (NEW.id_diploma, CONCAT(
    curr_date, ' - INSERT: ',
    'email_stud: ', IFNULL(NEW.email_stud, 'NULL'), ', ',
    'id_diploma: ', NEW.id_diploma, ', ',
    'status: ', IFNULL(NEW.status, 'NULL'), ', ',
    'start_date: ', IFNULL(NEW.start_date, 'NULL'), ', ',
    'end_date: ', IFNULL(NEW.end_date, 'NULL'), ', ',
    'Nemertes_link: ', IFNULL(NEW.Nemertes_link, 'NULL'), ', ',
    'pdf_main_diploma: ', IFNULL(NEW.pdf_main_diploma, 'NULL'), ', ',
    'external_links: ', IFNULL(NEW.external_links, 'NULL')
  ));
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER log_update_anathesi
AFTER UPDATE ON anathesi_diplomatikis
FOR EACH ROW
BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (NEW.id_diploma, CONCAT(
    curr_date, ' - UPDATE: ',
    'email_stud (old): ', IFNULL(OLD.email_stud, 'NULL'), ', ', 'email_stud (new): ', IFNULL(NEW.email_stud, 'NULL'), ', ',
    'id_diploma (old): ', OLD.id_diploma, ', ', 'id_diploma (new): ', NEW.id_diploma, ', ',
    'status (old): ', IFNULL(OLD.status, 'NULL'), ', ', 'status (new): ', IFNULL(NEW.status, 'NULL'), ', ',
    'start_date (old): ', IFNULL(OLD.start_date, 'NULL'), ', ', 'start_date (new): ', IFNULL(NEW.start_date, 'NULL'), ', ',
    'end_date (old): ', IFNULL(OLD.end_date, 'NULL'), ', ', 'end_date (new): ', IFNULL(NEW.end_date, 'NULL'), ', ',
    'Nemertes_link (old): ', IFNULL(OLD.Nemertes_link, 'NULL'), ', ', 'Nemertes_link (new): ', IFNULL(NEW.Nemertes_link, 'NULL'), ', ',
    'pdf_main_diploma (old): ', IFNULL(OLD.pdf_main_diploma, 'NULL'), ', ', 'pdf_main_diploma (new): ', IFNULL(NEW.pdf_main_diploma, 'NULL'), ', ',
    'external_links (old): ', IFNULL(OLD.external_links, 'NULL'), ', ', 'external_links (new): ', IFNULL(NEW.external_links, 'NULL')
  ));
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER log_delete_anathesi
AFTER DELETE ON anathesi_diplomatikis
FOR EACH ROW
BEGIN
  DECLARE curr_date DATETIME;
  SET curr_date = NOW();
  
  INSERT INTO log (id_di, record)
  VALUES (OLD.id_diploma, CONCAT(
    curr_date, ' - DELETE: ',
    'email_stud: ', IFNULL(OLD.email_stud, 'NULL'), ', ',
    'id_diploma: ', OLD.id_diploma, ', ',
    'status: ', IFNULL(OLD.status, 'NULL'), ', ',
    'start_date: ', IFNULL(OLD.start_date, 'NULL'), ', ',
    'end_date: ', IFNULL(OLD.end_date, 'NULL'), ', ',
    'Nemertes_link: ', IFNULL(OLD.Nemertes_link, 'NULL'), ', ',
    'pdf_main_diploma: ', IFNULL(OLD.pdf_main_diploma, 'NULL'), ', ',
    'external_links: ', IFNULL(OLD.external_links, 'NULL')
  ));
END //

DELIMITER ;