USE diplomatiki_support;

-- Καθαρισμός πινάκων
DELETE FROM professor;
DELETE FROM student;
DELETE FROM secretary;
DELETE FROM diplomatiki;
DELETE FROM eksetasi_diplomatikis;
DELETE FROM anathesi_diplomatikis;
DELETE FROM professor_notes;
DELETE FROM trimelis_epitropi_diplomatikis;
DELETE FROM prosklisi_se_trimeli;
DELETE FROM log;

-- Εισαγωγή δεδομένων στον πίνακα professor
INSERT INTO professor (password, name, surname, email_professor, topic, landline, mobile, department, university)
VALUES
  ('p1', 'Dimitris', 'Papadopoulos', 'dimitris.papa@university.edu', 'Physics', '2101234567', '6901234567', 'Physics', 'National University'),
  ('p2', 'Maria', 'Ioannou', 'maria.ioannou@university.edu', 'Mathematics', '2101234568', '6901234568', 'Mathematics', 'National University'),
  ('p3', 'Nikos', 'Katsaros', 'nikos.katsaros@university.edu', 'Computer Science', '2101234569', '6901234569', 'Computer Science', 'National University'),
  ('p4', 'Anna', 'Nikolaou', 'anna.nikolaou@university.edu', 'Chemistry', '2101234570', '6901234570', 'Chemistry', 'National University'),
  ('p5', 'George', 'Papadakis', 'george.papadakis@university.edu', 'Engineering', '2101234571', '6901234571', 'Engineering', 'National University'),
  ('p6', 'Sophia', 'Pavlou', 'sophia.pavlou@university.edu', 'Biology', '2101234572', '6901234572', 'Biology', 'National University'),
  ('p7', 'Vasilis', 'Zafiropoulos', 'vasilis.zafiropoulos@university.edu', 'Mathematics', '2101234573', '6901234573', 'Mathematics', 'National University'),
  ('p8', 'Eleni', 'Chrysafides', 'eleni.chrysafides@university.edu', 'Literature', '2101234574', '6901234574', 'Literature', 'National University'),
  ('p9', 'Dimitra', 'Kondylia', 'dimitra.kondylia@university.edu', 'Philosophy', '2101234575', '6901234575', 'Philosophy', 'National University'),
  ('p10', 'Kostas', 'Theodoridis', 'kostas.theodoridis@university.edu', 'Economics', '2101234576', '6901234576', 'Economics', 'National University'),
  ('p11', 'Kostas', 'Nikolopoulos', 'kostas.nikolopoulos@university.edu', 'Economics', '2101234577', '6901234577', 'Economics', 'National University'),
  ('p12', 'Giorgos', 'Andreou', 'giorgos.andreou@university.edu', 'Economics', '2101234578', '6901234578', 'Economics', 'National University');

-- Εισαγωγή δεδομένων στον πίνακα student
INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('s1', 'Giannis', 'Georgiou', 1001, 'Main St', '5A', 'Athens', '10432', 'Nikolaos', '2106543210', '6906543210', 'giannis.georgiou@student.edu'),
  ('s2', 'Eleni', 'Papadaki', 1002, 'Oak St', '12B', 'Thessaloniki', '54635', 'Manolis', '2310123456', '6907654321', 'eleni.papadaki@student.edu'),
  ('s3', 'Kostas', 'Lazaridis', 1003, 'Elm St', '7', 'Patras', '26223', 'Andreas', '2610123456', '6908765432', 'kostas.lazaridis@student.edu'),
  ('s4', 'Maria', 'Nikolaidou', 1004, 'King St', '22', 'Heraklion', '71235', 'Vasilis', '2810123456', '6909876543', 'maria.nikolaidou@student.edu'),
  ('s5', 'Nikos', 'Vasilakis', 1005, 'Greece St', '17B', 'Larissa', '41235', 'Andreas', '2410123456', '6902345678', 'nikos.vasilakis@student.edu'),
  ('s6', 'Ioannis', 'Dimitriou', 1006, 'Sunset Blvd', '3C', 'Chania', '73122', 'Giannis', '2820123456', '6903456789', 'ioannis.dimitriou@student.edu'),
  ('s7', 'Katerina', 'Kouli', 1007, 'Park Rd', '8A', 'Volos', '38222', 'Lefteris', '2420123456', '6904567890', 'katerina.kouli@student.edu'),
  ('s8', 'Eleni', 'Kiriakidou', 1008, 'Mountain St', '15', 'Athens', '10422', 'Michalis', '2104321234', '6905678901', 'eleni.kiriakidou@student.edu'),
  ('s9', 'Antonis', 'Papageorgiou', 1009, 'River Rd', '10B', 'Thessaloniki', '54625', 'Nikolaos', '2310123498', '6906789012', 'antonis.papageorgiou@student.edu'),
  ('s10', 'Vasilis', 'Manousakis', 1010, 'Sea St', '21', 'Rhodes', '85100', 'Yiannis', '2241023456', '6907890123', 'vasilis.manousakis@student.edu'),
  ('s11', 'Kostas', 'Manolopoulos', 1011, 'Sea St', '21', 'Rhodes', '85101', 'Yiannis', '2241023457', '6907890124', 'kostas.manolopoulos@student.edu'),
  ('s12', 'Nikos', 'Manolopoulos', 1012, 'Sea St', '21', 'Rhodes', '85102', 'Yiannis', '2241023458', '6907890125', 'nikos.manolopoulos@student.edu'),
  ('s13', 'Nikos', 'Kostantis', 1013, 'Sea St', '21', 'Rhodes', '85103', 'Yiannis', '2241023459', '6907890126', 'nikos.kostantis@student.edu');

-- Εισαγωγή δεδομένων στον πίνακα secretary
INSERT INTO secretary (email_sec, password)
VALUES
  ('sec1@gmail.edu', 'p7'),
  ('sec2@university.edu', 'sec2pass'),
  ('sec3@university.edu', 'sec3pass'),
  ('sec4@university.edu', 'sec4pass'),
  ('sec5@university.edu', 'sec5pass'),
  ('sec6@university.edu', 'sec6pass'),
  ('sec7@university.edu', 'sec7pass'),
  ('sec8@university.edu', 'sec8pass'),
  ('sec9@university.edu', 'sec9pass'),
  ('sec10@university.edu', 'sec10pass');

-- Εισαγωγή δεδομένων στον πίνακα diplomatiki
INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Sample Thesis Title 1', 'Description 1', 'link1.pdf', 'available'),
  ('maria.ioannou@university.edu', 'Sample Thesis Title 2', "Science fiction promised us robot butlers, but it seems they rather fancy themselves as artists instead. And who can blame them? On November 7, a painting of the mathematician Alan Turing by an AI-powered robot called Ai-Da sold at auction for a cool $1,084,000 (around £865,000). That's a more appealing lifestyle than having to sprint around a Boston Dynamics assault course.
The Sotheby's auction house said Ai-Da is 'the first humanoid robot artist to have an artwork sold at auction.' It probably also set the record the most online grumbling about a painting, which is understandable – after all, shouldn't robots be sweeping up and making the tea, while we artfully dab at the canvases?", 'link2.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Sample Thesis Title 3', 'Description 3', 'link3.pdf', 'available'),
  ('anna.nikolaou@university.edu', 'Sample Thesis Title 4', 'Description 4', 'link4.pdf', 'given'),
  ('george.papadakis@university.edu', 'Sample Thesis Title 5', 'Description 5', 'link5.pdf', 'available'),
  ('sophia.pavlou@university.edu', 'Sample Thesis Title 6', 'Description 6', 'link6.pdf', 'given'),
  ('vasilis.zafiropoulos@university.edu', 'Sample Thesis Title 7', 'Description 7', 'link7.pdf', 'available'),
  ('eleni.chrysafides@university.edu', 'Sample Thesis Title 8', 'Description 8', 'link8.pdf', 'given'),
  ('dimitra.kondylia@university.edu', 'Sample Thesis Title 9', 'Description 9', 'link9.pdf', 'available'),
  ('kostas.theodoridis@university.edu', 'Sample Thesis Title 10', 'Description 10', 'link10.pdf', 'given'),
  ('kostas.theodoridis@university.edu', 'Sample Thesis Title 11', 'Description 11', 'link11.pdf', 'given'),
  ('dimitris.papa@university.edu', 'Sample Thesis Title 12', 'Description 12', '../uploads/pdf_link_topic/link12.pdf', 'given');
  
  

-- Εισαγωγή δεδομένων στον πίνακα eksetasi_diplomatikis
INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade)
VALUES
  (1, 'giannis.georgiou@student.edu', '2024-12-15 10:00:00', 'Room 101', NULL, NULL, NULL, NULL),
  (2, 'eleni.papadaki@student.edu', '2024-12-16 10:00:00', 'Room 102', NULL, NULL, NULL, NULL),
  (3, 'kostas.lazaridis@student.edu', '2024-12-17 10:00:00', 'Room 103', NULL, NULL, NULL, NULL),
  (4, 'maria.nikolaidou@student.edu', '2024-12-18 10:00:00', 'Room 104', NULL, NULL, NULL, NULL),
  (5, 'nikos.vasilakis@student.edu', '2024-12-19 10:00:00', 'Room 105', NULL, NULL, NULL, NULL),
  (6, 'ioannis.dimitriou@student.edu', '2024-12-20 10:00:00', 'Room 106', NULL, NULL, NULL, NULL),
  (7, 'katerina.kouli@student.edu', '2024-12-21 10:00:00', 'Room 107', NULL, NULL, NULL, NULL),
  (8, 'eleni.kiriakidou@student.edu', '2024-12-22 10:00:00', 'Room 108', NULL, NULL, NULL, NULL),
  (9, 'antonis.papageorgiou@student.edu', '2024-12-23 10:00:00', 'Room 109', 5.0, 5.0, 5.0, 5.0),
  (10, 'vasilis.manousakis@student.edu', '2024-12-24 10:00:00', 'Room 110', NULL, NULL, NULL, NULL),
  (12, 'nikos.manolopoulos@student.edu', '2024-12-24 10:00:00', 'Room 110', NULL, NULL, NULL, NULL);
  

-- Εισαγωγή δεδομένων στον πίνακα anathesi_diplomatikis
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('giannis.georgiou@student.edu', 1, 'pending', '2022-01-15', '2024-06-15', 'link_to_nemertes1.pdf', 'link_to_main_diploma1.pdf', 'link_to_external_resources1'),
  ('eleni.papadaki@student.edu', 2, 'active', '2022-02-10', '2024-07-10', 'link_to_nemertes2.pdf', 'link_to_main_diploma2.pdf', 'link_to_external_resources2'),
  ('kostas.lazaridis@student.edu', 3, 'canceled_by_student', '2022-03-12', '2024-08-12', 'link_to_nemertes3.pdf', NULL, 'link_to_external_resources3'),
  ('maria.nikolaidou@student.edu', 4, 'under examination', '2022-04-15', '2024-09-15', 'link_to_nemertes4.pdf', 'link_to_main_diploma4.pdf', 'link_to_external_resources4'),
  ('nikos.vasilakis@student.edu', 5, 'finished', '2022-05-20', '2024-10-20', 'link_to_nemertes5.pdf', 'link_to_main_diploma5.pdf', 'link_to_external_resources5'),
  ('ioannis.dimitriou@student.edu', 6, 'pending', '2022-06-25', '2024-11-25', 'link_to_nemertes6.pdf', 'link_to_main_diploma6.pdf', 'link_to_external_resources6'),
  ('katerina.kouli@student.edu', 7, 'active', '2022-07-30', '2024-12-30', 'link_to_nemertes7.pdf', 'link_to_main_diploma7.pdf', 'link_to_external_resources7'),
  ('eleni.kiriakidou@student.edu', 8, 'canceled_by_professor', '2022-08-10', '2024-01-10', 'link_to_nemertes8.pdf', 'link_to_main_diploma8.pdf', 'link_to_external_resources8'),
  ('antonis.papageorgiou@student.edu', 9, 'under examination', '2022-09-20', NULL, 'link_to_nemertes9.pdf', 'link_to_main_diploma9.pdf', 'link_to_external_resources9'),
  ('vasilis.manousakis@student.edu', 10, 'finished', '2022-10-15', '2024-03-15', 'link_to_nemertes10.pdf', 'link_to_main_diploma10.pdf', 'link_to_external_resources10'),
  ('kostas.manolopoulos@student.edu', 11, 'finished', '2022-10-15', '2024-03-15', 'link_to_nemertes11.pdf', 'link_to_main_diploma11.pdf', 'link_to_external_resources11'),
  ('nikos.manolopoulos@student.edu', 12, 'under examination', '2022-10-15', '2024-03-15', 'link_to_nemertes12.pdf', 'link_to_main_diploma12.pdf', 'link_to_external_resources12');
  

-- Εισαγωγή δεδομένων στον πίνακα professor_notes
INSERT INTO professor_notes (professor_email, id_diplom, notes)
VALUES
  ('dimitris.papa@university.edu', 1, 'Note 1 for thesis'),
  ('maria.ioannou@university.edu', 2, 'Note 2 for thesis'),
  ('nikos.katsaros@university.edu', 3, 'Note 3 for thesis'),
  ('anna.nikolaou@university.edu', 4, 'Note 4 for thesis'),
  ('george.papadakis@university.edu', 5, 'Note 5 for thesis'),
  ('sophia.pavlou@university.edu', 6, 'Note 6 for thesis'),
  ('vasilis.zafiropoulos@university.edu', 7, 'Note 7 for thesis'),
  ('eleni.chrysafides@university.edu', 8, 'Note 8 for thesis'),
  ('dimitra.kondylia@university.edu', 9, 'Note 9 for thesis'),
  ('kostas.theodoridis@university.edu', 10, 'Note 10 for thesis');

-- Εισαγωγή δεδομένων στον πίνακα trimelis_epitropi_diplomatikis
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (1, 'dimitris.papa@university.edu', 'maria.ioannou@university.edu', NULL),
  (2, 'maria.ioannou@university.edu', 'nikos.katsaros@university.edu', 'anna.nikolaou@university.edu'),
  (3, 'nikos.katsaros@university.edu', 'anna.nikolaou@university.edu', 'george.papadakis@university.edu'),
  (4, 'anna.nikolaou@university.edu', 'george.papadakis@university.edu', 'sophia.pavlou@university.edu'),
  (5, 'george.papadakis@university.edu', 'sophia.pavlou@university.edu', 'vasilis.zafiropoulos@university.edu'),
  (6, 'sophia.pavlou@university.edu', 'vasilis.zafiropoulos@university.edu', 'eleni.chrysafides@university.edu'),
  (7, 'vasilis.zafiropoulos@university.edu', 'eleni.chrysafides@university.edu', 'dimitra.kondylia@university.edu'),
  (8, 'eleni.chrysafides@university.edu', 'dimitra.kondylia@university.edu', 'kostas.theodoridis@university.edu'),
  (9, 'dimitra.kondylia@university.edu', 'kostas.theodoridis@university.edu', 'dimitris.papa@university.edu'),
  (10, 'kostas.theodoridis@university.edu', 'dimitris.papa@university.edu', 'maria.ioannou@university.edu'),
  (11, 'kostas.theodoridis@university.edu', 'dimitris.papa@university.edu', 'maria.ioannou@university.edu'),
  (12, 'kostas.theodoridis@university.edu', 'dimitris.papa@university.edu', 'maria.ioannou@university.edu');

-- Εισαγωγή δεδομένων στον πίνακα prosklisi_se_trimeli
INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES
  ('giannis.georgiou@student.edu', 'maria.ioannou@university.edu', 1, 'pending', NULL, '2024-01-10'),
  ('eleni.papadaki@student.edu', 'dimitris.papa@university.edu', 2, 'accepted', '2024-01-12', '2024-01-10'),
  ('kostas.lazaridis@student.edu', 'maria.ioannou@university.edu', 3, 'declined', '2024-01-13', '2024-01-10'),
  ('maria.nikolaidou@student.edu', 'nikos.katsaros@university.edu', 4, 'accepted', '2024-01-14', '2024-01-10'),
  ('nikos.vasilakis@student.edu', 'anna.nikolaou@university.edu', 5, 'pending', NULL, '2024-01-10'),
  ('ioannis.dimitriou@student.edu', 'george.papadakis@university.edu', 6, 'pending', NULL, '2024-01-10'),
  ('katerina.kouli@student.edu', 'sophia.pavlou@university.edu', 7, 'accepted', '2024-01-16', '2024-01-10'),
  ('eleni.kiriakidou@student.edu', 'vasilis.zafiropoulos@university.edu', 8, 'declined', '2024-01-17', '2024-01-10'),
  ('antonis.papageorgiou@student.edu', 'eleni.chrysafides@university.edu', 9, 'pending', NULL, '2024-01-10');