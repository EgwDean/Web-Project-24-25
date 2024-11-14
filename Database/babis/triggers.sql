USE diplomatiki_support;


DELIMITER $
CREATE TRIGGER trigger_check_grade_update
BEFORE UPDATE ON eksetasi_diplomatikis
FOR EACH ROW
BEGIN
    -- Έλεγχος της κατάστασης της διπλωματικής στον πίνακα anathesi_diplomatikis
    DECLARE diploma_status ENUM('pending', 'active', 'canceled', 'under examination', 'finished');

    SELECT anathesi_diplomatikis.status INTO diploma_status
    FROM anathesi_diplomatikis
    WHERE id_diploma = NEW.id_diplomatikis;


	-- Έλεγχος ότι ενημερώνονται οι τιμές στα πεδία των βαθμών (από NULL που ήταν αρχικά ενημερώνονται με NOT NULL τιμές)
    IF (NEW.grade1 IS NOT NULL AND NEW.grade1 != OLD.grade1) OR (NEW.grade2 IS NOT NULL AND NEW.grade2 != OLD.grade2) OR (NEW.grade3 IS NOT NULL AND NEW.grade3 != OLD.grade3) THEN
		
        -- Αν η κατάσταση δεν είναι "under examination", ακύρωση της εισαγωγής 
		IF diploma_status != 'under examination' THEN
			SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'Η καταχώρηση βαθμών επιτρέπεται μόνο όταν η διπλωματική είναι υπό εξέταση.';
		END IF;
	END IF;
END$
DELIMITER ;