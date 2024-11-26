USE diplomatiki_support;

-- Διαγραφή στοιχείων περατωμένης διπλωματικής
DELIMITER $
DROP PROCEDURE IF EXISTS deleteById$
CREATE PROCEDURE deleteById(IN id INT)
BEGIN
    UPDATE diplomatiki SET status = 'available' WHERE id = id_diplomatiki;
    UPDATE anathesi_diplomatikis SET status = 'canceled_by_student' WHERE id = id_diploma;
    DELETE FROM professor_notes WHERE id = id_diplom;
    DELETE FROM trimelis_epitropi_diplomatikis WHERE id = id_dipl;
END$

DELIMITER ;