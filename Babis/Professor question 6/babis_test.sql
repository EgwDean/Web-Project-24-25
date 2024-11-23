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
-- ΥΠΟΕΡΩΤΗΜΑ β)
-- ΥΠΟΕΡΩΤΗΜΑ γ)
-- ΤΕΛΟΣ ΓΙΑ ΤΗΝ ΥΠΟ ΕΞΕΤΑΣΗ ΔΙΠΛΩΜΑΤΙΚΗ 