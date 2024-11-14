-- Καθαρισμός πινάκων
DELETE FROM professor;
DELETE FROM student;
DELETE FROM secretary;


-- Εισαγωγή δεδομένων στον πίνακα professor
INSERT INTO professor (username, password, name, surname, email_professor, topic, landline, mobile, department, university)
VALUES
  ('p1', 'p1', 'Dimitris', 'Papadopoulos', 'dimitris.papa@university.edu', 'Physics', '2101234567', '6901234567', 'Physics', 'National University'),
  ('p2', 'p2', 'Maria', 'Ioannou', 'maria.ioannou@university.edu', 'Mathematics', '2101234568', '6901234568', 'Mathematics', 'National University'),
  ('p3', 'p3', 'Nikos', 'Katsaros', 'nikos.katsaros@university.edu', 'Computer Science', '2101234569', '6901234569', 'Computer Science', 'National University');

-- Εισαγωγή δεδομένων στον πίνακα student
INSERT INTO student (username, password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('s1', 's1', 'Giannis', 'Georgiou', 1001, 'Main St', '5A', 'Athens', '10432', 'Nikolaos', '2106543210', '6906543210', 'giannis.georgiou@student.edu'),
  ('s2', 's2', 'Eleni', 'Papadaki', 1002, 'Oak St', '12B', 'Thessaloniki', '54635', 'Manolis', '2310123456', '6907654321', 'eleni.papadaki@student.edu'),
  ('s3', 's3', 'Kostas', 'Lazaridis', 1003, 'Elm St', '7', 'Patras', '26223', 'Andreas', '2610123456', '6908765432', 'kostas.lazaridis@student.edu');

-- Εισαγωγή δεδομένων στον πίνακα secretary
INSERT INTO secretary (email_sec, password)
VALUES
  ('sec1@gmail.edu', 'p7'),
  ('sec2@university.edu', 'sec2pass'),
  ('sec3@university.edu', 'sec3pass');
  
  
  
  INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES (
  'dimitris.papa@university.edu', 
  'Sample Thesis Title', 
  'This is a sample description for the thesis topic.', 
  'link_to_pdf_topic.pdf', 
  'available'
);

# Δουλειά του Θεόφραστου την οποία έκανα χειροκίνητα
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES (
  'giannis.georgiou@student.edu', 
  1, 
  'pending', 
  '2022-01-15', 
  '2024-06-15', 
  'link_to_nemertes.pdf', 
  'link_to_main_diploma.pdf', 
  'link_to_external_resources'
);

# Δουλειά του Dean την οποία έκανα χειροκίνητα
INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES 
('giannis.georgiou@student.edu', 'maria.ioannou@university.edu', 1, 'pending', NULL, '2024-01-10'),
('giannis.georgiou@student.edu', 'nikos.katsaros@university.edu', 1, 'pending', NULL, '2024-01-10');


# Δουλειά του Θεόφραστου την οποία έκανα χειροκίνητα
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES (1, 'dimitris.papa@university.edu', 'maria.ioannou@university.edu', 'nikos.katsaros@university.edu');


INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade)
VALUES (1, 'giannis.georgiou@student.edu', '2024-12-15 10:00:00', 'Room 101', NULL, NULL, NULL, NULL);