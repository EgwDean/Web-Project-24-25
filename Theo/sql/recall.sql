DROP PROCEDURE IF EXISTS recall_thesis;

DELIMITER $

CREATE PROCEDURE recall_thesis(
    IN dip_id INT, 
    IN stud_email VARCHAR(255), 
    IN prof_email VARCHAR(255), 
    IN meet_number VARCHAR(255),  -- Αριθμός της Γενικής Συνέλευσης
    IN meet_year VARCHAR(255),    -- Έτος της Γενικής Συνέλευσης
    OUT error_code INT
)
BEGIN 
    DECLARE id_count INT;
    DECLARE stud_count INT;
    DECLARE assign_date DATE;
    DECLARE assignment_status ENUM('pending', 'active', 'canceled_by_student', 'canceled_by_professor', 'recalled', 'under examination', 'finished');

    -- Προεπιλογή error_code = 0 (γενικό σφάλμα)
    SET error_code = 0;

    -- Έλεγχος αν η διπλωματική ανήκει στον καθηγητή και είναι 'given'
    SELECT COUNT(*)
    INTO id_count
    FROM diplomatiki
    WHERE email_prof = prof_email 
	AND id_diplomatiki = dip_id 
	AND status = 'given';
    
    SELECT COUNT(*)
    INTO stud_count
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND id_diploma = dip_id AND status = 'pending';

    -- Ανάκτηση κατάστασης και ημερομηνίας ανάθεσης
    SELECT status, start_date
    INTO assignment_status, assign_date
    FROM anathesi_diplomatikis
    WHERE email_stud = stud_email AND id_diploma = dip_id AND status = 'active';

    IF (stud_count = 1 AND id_count = 1) THEN
        -- Διαγραφή από σχετικoύς πίνακες
        DELETE FROM trimelis_epitropi_diplomatikis
        WHERE id_dipl = dip_id;  

        DELETE FROM prosklisi_se_trimeli
        WHERE id_dip = dip_id;

        -- Ενημέρωση κατάστασης διπλωματικής
        UPDATE diplomatiki
        SET status = 'available'
        WHERE id_diplomatiki = dip_id;

        -- Ενημέρωση κατάστασης ανάθεσης
        UPDATE anathesi_diplomatikis
        SET status = 'recalled'
        WHERE id_diploma = dip_id;

        SET error_code = 1; -- Επιτυχής ανάκληση
        
	END IF;
        
    IF (assignment_status = 'active' AND id_count = 1) THEN
    
		IF (meet_number < 0 OR meet_number > 10000 OR meet_number IS NULL OR meet_year < 2000 OR meet_year > 2030 OR meet_year IS NULL) THEN
			SET error_code = -4;
        -- Έλεγχος αν έχουν περάσει 2 έτη από την ανάθεση

		ELSEIF DATEDIFF(CURDATE(), assign_date) >= 730 THEN
			
				-- Ενημέρωση κατάστασης ανάθεσης σε 'canceled_by_professor'
				UPDATE anathesi_diplomatikis
				SET status = 'canceled_by_professor'
				WHERE id_diploma = dip_id;
				
				DELETE FROM trimelis_epitropi_diplomatikis
				WHERE id_dipl = dip_id;  
				
				INSERT INTO cancellations(id_d, meeting_number, meeting_year)
				VALUES (dip_id, meet_number, meet_year);

				SET error_code = 2; -- Επιτυχής ακύρωση ανάθεσης
			ELSE
        
            SET error_code = -2; -- Σφάλμα: Δεν έχουν παρέλθει 2 έτη
        END IF;
    END IF;
    
   IF id_count = 0 THEN
        -- Σφάλμα: Διπλωματική δεν ανήκει στον καθηγητή ή δεν είναι διαθέσιμη
        SET error_code = -1;
   END IF;
   
   IF (stud_count = 0 AND assignment_status IS NULL) THEN
		SET error_code = -3;
   END IF;
    
    
END$
DELIMITER ;




-- testfile -- 

DELETE FROM anathesi_diplomatikis;
DELETE FROM prosklisi_se_trimeli;
DELETE FROM trimelis_epitropi_diplomatikis;

DELETE FROM student;

  DROP TABLE diplomatiki;

CREATE TABLE diplomatiki (
  id_diplomatiki INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  email_prof VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,    
  description TEXT NOT NULL,                            
  pdf_link_topic VARCHAR(255),
  status ENUM('available', 'given') NOT NULL
);
  
  INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Quantum Physics in Computation', 'This thesis explores the applications of quantum mechanics in modern computational theory.', 'uploads/1.pdf', 'available'),
  ('dimitris.papa@university.edu', 'Advanced Topics in Algebra', 'A detailed analysis of modern algebraic structures and their applications in cryptography.', 'uploads/2.pdf', 'available'),
  ('dimitris.papa@university.edu', 'Artificial Intelligence Ethics', 'An in-depth study on the ethical implications of artificial intelligence and machine learning.', 'uploads/3.pdf', 'available'),
  ('dimitris.papa@university.edu', 'Artificial Intelligence Ethics1', 'An in-depth study on the ethical implications of artificial intelligence and machine learning.', 'https://university.edu/papers/ai_ethics.pdf', 'given'),
  ('nikos.katsaos@university.edu', 'Artificial Intelligence Ethics2', 'An in-depth study on the ethical implications of artificial intelligence and machine learning.', 'https://university.edu/papers/ai_ethics.pdf', 'available');


INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('s1', 'Giannis', 'Georgiou', 1001, 'Main St', '5A', 'Athens', '10432', 'Nikolaos', '2106543210', '6906543210', 'giannis.georgiou@student.edu'),
  ('s2', 'Eleni', 'Papadaki', 1002, 'Oak St', '12B', 'Thessaloniki', '54635', 'Manolis', '2310123456', '6907654321', 'eleni.papadaki@student.edu'),
  ('s3', 'Kostas', 'Lazaridis', 1003, 'Elm St', '7', 'Patras', '26223', 'Andreas', '2610123456', '6908765432', 'kostas.lazaridis@student.edu'),
  ('pass1234', 'John', 'Doe', 1001, 'Main Street', '10', 'New York', '10001', 'Robert Doe', '1234567890', '9876543210', 'john.doe@example.com'),
  ('secure567', 'Jane', 'Smith', 1002, 'Elm Avenue', '20', 'Los Angeles', '90001', 'William Smith', NULL, '8765432109', 'jane.smith@example.com'),
  ('mypassword789', 'Mike', 'Brown', 1003, 'Oak Street', '30', 'Chicago', '60601', 'James Brown', '0987654321', '7654321098', 'mike.brown@example.com');
  
  INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links) 
VALUES 
  ('giannis.georgiou@student.edu', 101, 'canceled_by_student', '2024-09-01', '2024-12-01', 'https://link1.com', 'pdf1.pdf', 'link1.com'),
  ('john.doe@example.com', 102, 'active', '2024-07-15', '2025-07-15', 'https://link2.com', 'pdf2.pdf', 'link2.com'),
  ('kostas.lazaridis@student.edu', 103, 'canceled_by_professor', '2024-05-01', NULL, 'https://link3.com', 'pdf3.pdf', 'link3.com'),
  ('eleni.papadaki@student.edu', 104, 'finished', '2023-06-01', '2024-06-01', 'https://link4.com', 'pdf4.pdf', 'link4.com');
  
  INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES 
('mike.brown@example.com', 'dimitris.papa@university.edu', 1, 'pending', NULL, '2024-11-01'),
('mike.brown@example.com', 'dimitris.papa@university.edu', 1, 'pending', '2024-11-10', '2024-11-05');

SELECT * FROM diplomatiki;
SELECT * FROM anathesi_diplomatikis;
SELECT * FROM trimelis_epitropi_diplomatikis;
SELECT * FROM prosklisi_se_trimeli;
SELECT * FROM cancellations;

-- KANE ANATHESI APO THN SELIDA THN DIPLOMATIKI ME ID 1.

UPDATE anathesi_diplomatikis 
SET status = 'active'
WHERE id_diploma = 1 AND status = 'pending';

UPDATE anathesi_diplomatikis
SET start_date = '2022-1-1'
WHERE id_diploma = 1;



