-- ΤΡΕΞΕ ΠΡΩΤΑ ΤΟ test_project ΤΟΥ ΘΕΟΦΡΑΣΤΟΥ ΚΑΙ ΜΕΤΑ ΤΟ ΔΙΚΟ ΜΟΥ


-- ΕΡΩΤΗΜΑ 6 ΔΙΔΑΣΚΟΝΤΑ

-- ΥΠΟ ΑΝΑΘΕΣΗ ΔΙΠΛΩΜΑΤΙΚΗ
-- ΠΡΟΒΟΛΗ ΑΛΛΩΝ ΜΕΛΩΝ ΚΑΙ ΑΠΑΝΤΗΣΗ ΤΟΥΣ
UPDATE anathesi_diplomatikis
SET status='pending' 
WHERE id_diploma = 1;

INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES 
('mike.brown@example.com', 'maria.ioannou@university.edu', 1, 'accepted', '2024-11-11', '2024-11-06');

-- Η ΑΚΥΡΩΣΗ ΑΝΑΘΕΣΗΣ ΘΕΜΑΤΟΣ ΕΧΕΙ ΗΔΗ ΥΛΟΠΟΙΗΘΕΙ ΑΠΟ ΘΕΟΦΡΑΣΤΟ
-- ΤΕΛΟΣ ΓΙΑ ΤΗΝ ΥΠΟ ΑΝΑΘΕΣΗ ΔΙΠΛΩΜΑΤΙΚΗ 


-- ΕΝΕΡΓΗ ΔΙΠΛΩΜΑΤΙΚΗ
-- ΥΠΟΕΡΩΤΗΜΑ α)

-- ΥΠΟΕΡΩΤΗΜΑ β)
UPDATE anathesi_diplomatikis
SET start_date = '2020-04-10'
WHERE id_diploma=5;

-- ΥΠΟΕΡΩΤΗΜΑ γ)
-- ΥΠΟΕΡΩΤΗΜΑ δ)
-- ΤΕΛΟΣ ΓΙΑ ΤΗΝ ΕΝΕΡΓΟ ΔΙΠΛΩΜΑΤΙΚΗ 



-- ΥΠΟ ΕΞΕΤΑΣΗ ΔΙΠΛΩΜΑΤΙΚΗ
-- ΥΠΟΕΡΩΤΗΜΑ α)
-- ΥΠΟΕΡΩΤΗΜΑΤΑ β), γ)
INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade, praktiko_bathmologisis) 
VALUES 
(5, 'lucy.brown@example.com', '2024-06-18 10:00:00', 'Room D404', NULL, 9.50, 9.30, 9.27, 'Pass');

-- ΤΕΛΟΣ ΓΙΑ ΤΗΝ ΥΠΟ ΕΞΕΤΑΣΗ ΔΙΠΛΩΜΑΤΙΚΗ 