-- 1 ----
DELETE FROM diplomatiki;

INSERT INTO professor (username, password, name, surname, email_professor, topic, landline, mobile, department, university)
VALUES
  ('p1', 'p1', 'Dimitris', 'Papadopoulos', 'dimitris.papa@university.edu', 'Physics', '2101234567', '6901234567', 'Physics', 'National University'),
  ('p2', 'p2', 'Maria', 'Ioannou', 'maria.ioannou@university.edu', 'Mathematics', '2101234568', '6901234568', 'Mathematics', 'National University'),
  ('p3', 'p3', 'Nikos', 'Katsaros', 'nikos.katsaros@university.edu', 'Computer Science', '2101234569', '6901234569', 'Computer Science', 'National University');
  
  INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Quantum Physics in Computation', 'This thesis explores the applications of quantum mechanics in modern computational theory.', 'uploads/1.pdf', 'available'),
  ('dimitris.papa@university.edu', 'Advanced Topics in Algebra', 'A detailed analysis of modern algebraic structures and their applications in cryptography.', 'uploads/2.pdf', 'available'),
  ('dimitris.papa@university.edu', 'Artificial Intelligence Ethics', 'An in-depth study on the ethical implications of artificial intelligence and machine learning.', 'uploads/3.pdf', 'available'),
  ('dimitris.papa@university.edu', 'Artificial Intelligence Ethics1', 'An in-depth study on the ethical implications of artificial intelligence and machine learning.', 'https://university.edu/papers/ai_ethics.pdf', 'given'),
  ('nikos.katsaos@university.edu', 'Artificial Intelligence Ethics2', 'An in-depth study on the ethical implications of artificial intelligence and machine learning.', 'https://university.edu/papers/ai_ethics.pdf', 'available');

-- 2 ------


INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('s1', 'Giannis', 'Georgiou', 1001, 'Main St', '5A', 'Athens', '10432', 'Nikolaos', '2106543210', '6906543210', 'giannis.georgiou@student.edu'),
  ('s2', 'Eleni', 'Papadaki', 1002, 'Oak St', '12B', 'Thessaloniki', '54635', 'Manolis', '2310123456', '6907654321', 'eleni.papadaki@student.edu'),
  ('s3', 'Kostas', 'Lazaridis', 1003, 'Elm St', '7', 'Patras', '26223', 'Andreas', '2610123456', '6908765432', 'kostas.lazaridis@student.edu'),
  ('pass1234', 'John', 'Doe', 1001, 'Main Street', '10', 'New York', '10001', 'Robert Doe', '1234567890', '9876543210', 'john.doe@example.com'),
  ('secure567', 'Jane', 'Smith', 1002, 'Elm Avenue', '20', 'Los Angeles', '90001', 'William Smith', NULL, '8765432109', 'jane.smith@example.com'),
  ('mypassword789', 'Mike', 'Brown', 1003, 'Oak Street', '30', 'Chicago', '60601', 'James Brown', '0987654321', '7654321098', 'mike.brown@example.com');

SELECT * FROM anathesi_diplomatikis;
SELECT * FROM trimelis_epitropi_diplomatikis;

-- 4 ----
DELETE FROM trimelis_epitropi_diplomatikis;
DELETE FROM anathesi_diplomatikis;
DELETE FROM prosklisi_se_trimeli;

SELECT * FROM trimelis_epitropi_diplomatikis;
SELECT * FROM anathesi_diplomatikis;
SELECT * FROM prosklisi_se_trimeli;

INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES 
(1, 'Dr. Ioannis Papadopoulos', 'maria.ioannou@university.edu', NULL),
(2, 'Dr. Eleni Georgiou', NULL, NULL),
(3, 'Dr. Nikolaos Pappas', NULL, NULL);

INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links) 
VALUES 
('john.doe@example.com', 1, 'pending', '2024-01-15', '2024-12-15', 'http://example.com/nemertes/101', 'path/to/diploma1.pdf', 'http://example.com/external_link1'),
('mary.smith@example.com', 2, 'pending', '2024-03-01', '2025-1-1', 'http://example.com/nemertes/102', 'path/to/diploma2.pdf', 'http://example.com/external_link2');

INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES 
('mike.brown@example.com', 'dimitris.papa@university.edu', 1, 'pending', NULL, '2024-11-01'),
('mike.brown@example.com', 'dimitris.papa@university.edu', 2, 'pending', '2024-11-10', '2024-11-05');


--  5 -----

DELETE FROM trimelis_epitropi_diplomatikis;
DELETE FROM anathesi_diplomatikis;
DELETE FROM eksetasi_diplomatikis;

SELECT * FROM trimelis_epitropi_diplomatikis;
SELECT * FROM anathesi_diplomatikis;
SELECT * FROM eksetasi_diplomatikis;

INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2) 
VALUES 
(1, 'dimitris.papa@university.edu', 'Nikos Papadopoulos', 'Maria Vassilaki'),
(2, 'dimitris.papa@university.edu', 'Elena Theodorou', 'Giannis Nikas'),
(3, 'Professor Dimitrios Xenos', 'dimitris.papa@university.edu', 'Sofia Liasidou'),
(4, 'Minister Ioannis Papanikolaou', 'Vasilis Georgiou', 'dimitris.papa@university.edu'),
(5, 'dimitris.papa@university.edu', 'dimitris.papa@university.edu', 'Sofia Liasidou'),
(6, 'Minister Ioannis Papanikolaou', 'dimitris.papa@university.edu', 'dimitris.papa@university.edu');


INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links) 
VALUES 
('john.doe@example.com', 1, 'finished', '2024-01-15', '2024-12-15', 'http://example.com/nemertes/101', 'path/to/diploma1.pdf', 'http://example.com/external_link1'),
('mary.smith@example.com', 2, 'finished', '2024-03-01', '2025-1-1', 'http://example.com/nemertes/102', 'path/to/diploma2.pdf', 'http://example.com/external_link2'),
('peter.jones@example.com', 3, 'finished', '2024-02-20', '2026-1-1', 'http://example.com/nemertes/103', 'path/to/diploma3.pdf', 'http://example.com/external_link3'),
('lucy.brown@example.com', 4, 'finished', '2024-04-10', '2024-10-10', 'http://example.com/nemertes/104', 'path/to/diploma4.pdf', 'http://example.com/external_link4'),
('lucy.brown@example.com', 5, 'active', '2024-04-10', NULL, 'http://example.com/nemertes/104', 'path/to/diploma4.pdf', 'http://example.com/external_link4'),
('lucy.brown@example.com', 6, 'active', '2024-04-10', NULL, 'http://example.com/nemertes/104', 'path/to/diploma4.pdf', 'http://example.com/external_link4');

INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade, praktiko_bathmologisis) 
VALUES 
(1, 'john.doe@example.com', '2024-06-15 09:00:00', 'Room A101', 8.50, 7.75, 9.00, 8.42, 'Pass'),
(2, 'mary.smith@example.com', '2024-06-16 11:00:00', 'Room B202', 6.80, 7.90, 8.20, 7.30, 'Pass'),
(3, 'peter.jones@example.com', '2024-06-17 14:00:00', 'Room C303', 7.50, 8.00, 8.75, 8.08, 'Pass'),
(4, 'lucy.brown@example.com', '2024-06-18 10:00:00', 'Room D404', 9.00, 9.50, 9.30, 9.27, 'Pass');
