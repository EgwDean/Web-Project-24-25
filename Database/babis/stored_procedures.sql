USE diplomatiki_support;

DELIMITER $
DROP PROCEDURE IF EXISTS checkInvitations$
CREATE PROCEDURE checkInvitations(IN id_diploma INT)
BEGIN
DECLARE state ENUM('pending', 'active', 'canceled', 'under examination', 'finished');

SELECT anathesi_diplomatikis.status INTO state FROM anathesi_diplomatikis 
WHERE  anathesi_diplomatikis.id_diploma = id_diploma;

IF (state = 'pending') THEN
SELECT prosklisi_se_trimeli.prof_email, prosklisi_se_trimeli.status, prosklisi_se_trimeli.reply_date FROM prosklisi_se_trimeli
INNER JOIN anathesi_diplomatikis ON prosklisi_se_trimeli.id_dip = anathesi_diplomatikis.id_diploma;
END IF;
END$
DELIMITER ;




DELIMITER $
DROP PROCEDURE IF EXISTS cancelAssignment$
CREATE PROCEDURE cancelAssignment(IN professor_email VARCHAR(255), IN id_diploma INT)
BEGIN
DECLARE supervisor VARCHAR(255);

SELECT trimelis_epitropi_diplomatikis.supervisor INTO supervisor FROM trimelis_epitropi_diplomatikis 
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

IF(supervisor = professor_email) THEN
UPDATE anathesi_diplomatikis
SET status = 'canceled'
WHERE anathesi_diplomatikis.id_diploma = id_diploma;

DELETE FROM trimelis_epitropi_diplomatikis
WHERE trimelis_epitropi_diplomatikis.id_dipl = id_diploma;

DELETE FROM prosklisi_se_trimeli
WHERE prosklisi_se_trimeli.id_dip = id_diploma;
ELSE 
SIGNAL SQLSTATE '45000'
	  SET MESSAGE_TEXT = 'You need to be a supervisor of this diploma to cancel its assignment';
END IF;


END$
DELIMITER ;



