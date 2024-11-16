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

-- DROP PROCEDURE upload_pdf;

SELECT * FROM diplomatiki;

CALL upload_pdf('6');
SELECT @x;