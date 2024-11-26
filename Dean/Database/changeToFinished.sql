USE diplomatiki_support;

DELIMITER $

-- Έλεγχος αν η διπλωματική είναι υπό εξέταση με προβιβάσιμο βαθμό και έχει αναρτηθεί το link στη Νημερτής
DROP PROCEDURE IF EXISTS changeToFinished$
CREATE PROCEDURE changeToFinished(IN diplomatiki_id INT, OUT status INT)
BEGIN
    DECLARE nemertes_status INT;
    DECLARE final_grade_status INT;
    DECLARE under_exam_status INT;

    SELECT COUNT(*) 
    INTO nemertes_status
    FROM anathesi_diplomatikis
    WHERE id_diploma = diplomatiki_id AND Nemertes_link IS NOT NULL;

    IF nemertes_status > 0 THEN 
        SET nemertes_status = 1;
    ELSE 
        SET nemertes_status = 0;
    END IF;

    SELECT COUNT(*) 
    INTO final_grade_status
    FROM eksetasi_diplomatikis
    WHERE id_diplomatikis = diplomatiki_id AND final_grade >= 5;

    IF final_grade_status > 0 THEN 
        SET final_grade_status = 1;
    ELSE 
        SET final_grade_status = 0;
    END IF;

    SELECT COUNT(*) 
    INTO under_exam_status
    FROM anathesi_diplomatikis
    WHERE id_diploma = diplomatiki_id AND anathesi_diplomatikis.status = 'under examination';

    IF under_exam_status > 0 THEN 
        SET under_exam_status = 1;
    ELSE 
        SET under_exam_status = 0;
    END IF;

    IF (nemertes_status + final_grade_status + under_exam_status = 3) THEN
        SET status = 1;
    ELSE
        SET status = 0;
    END IF;
END$

DELIMITER ;
