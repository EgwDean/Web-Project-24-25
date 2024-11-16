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

-- DROP PROCEDURE accept;

CALL accept('dimitris.papa@university.edu', '93', @x);
SELECT @x;

SELECT * FROM prosklisi_se_trimeli;
SELECT * FROM anathesi_diplomatikis;
SELECT * FROM trimelis_epitropi_diplomatikis;


UPDATE prosklisi_se_trimeli
SET status = 'pending', reply_date = CURDATE()
WHERE prof_email = 'dimitris.papa@university.edu' AND id_dip = '93';


UPDATE prosklisi_se_trimeli
SET status = 'accepted', reply_date = CURDATE()
WHERE prof_email = 'dimitris.papa@university.edu' AND id_dip = '91';
    
