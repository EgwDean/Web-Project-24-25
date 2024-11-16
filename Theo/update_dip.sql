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

-- DROP PROCEDURE update_dip;

CALL update_dip(32,'χα', 'new descr', @x);
SELECT @x;

SELECT * FROM diplomatiki;