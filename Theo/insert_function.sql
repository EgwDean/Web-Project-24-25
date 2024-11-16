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

-- DROP PROCEDURE insert_to_dip;

CALL insert_to_dip('dimitris.papa@university.edu','Something biggerr than smaller', '222', 'available', @x);
SELECT @x;

SELECT * FROM diplomatiki;