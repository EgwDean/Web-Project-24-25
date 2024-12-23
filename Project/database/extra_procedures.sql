USE diplomatiki_support;

DELIMITER $
DROP PROCEDURE IF EXISTS setUnderExam$
CREATE PROCEDURE setUnderExam(IN dip_id INT,  OUT error_code INT)
BEGIN 
DECLARE protocol INT;

SELECT anathesi_diplomatikis.protocol_number INTO protocol FROM anathesi_diplomatikis 
WHERE id_diploma = dip_id
LIMIT 1;

IF (protocol IS NOT NULL) THEN
	UPDATE anathesi_diplomatikis 
	SET status = 'under examination' 
	WHERE id_diploma = dip_id AND status = 'active';
    
    SET error_code = 0;
ELSE 
	SET error_code = 1;
END IF;
END$
DELIMITER ;