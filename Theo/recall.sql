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
    WHERE email_stud = stud_email AND status = 'pending';
        
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

-- DROP PROCEDURE recall_thesis;

CALL recall_thesis('90','mike.brown@example.com','dimitris.papa@university.edu',@x);
SELECT @x;

SELECT * FROM diplomatiki;
SELECT * FROM anathesi_diplomatikis;
SELECT * FROM prosklisi_se_trimeli;
SELECT * FROM trimelis_epitropi_diplomatikis;
SELECT * FROM student;