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

DROP TABLE diplomatiki;

CREATE TABLE diplomatiki (
  id_diplomatiki INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  email_prof VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,    
  description TEXT NOT NULL,                            
  pdf_link_topic VARCHAR(255),
  status ENUM('available', 'given') NOT NULL
);

-- Εισαγωγή δεδομένων στον πίνακα professor
INSERT INTO professor (password, name, surname, email_professor, topic, landline, mobile, department, university)
VALUES
  ('p1', 'Δημητρης', 'Παπαδοπουλος', 'dimitris.papa@university.edu', 'Physics', '2101234567', '6901234567', 'Physics', 'National University'),
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
  ('s1', 'Γιαννης', 'Γεωργιου', 1001, 'Main St', '52', 'Athens', '10432', 'Nikolaos', '2106543210', '6906543210', 'giannis.georgiou@student.edu'),
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
  ('dimitris.papa@university.edu', 'Τεχνητή νοημοσύνη και αγροκαλιέργειες', 'Description 1', '../uploads/pdf_link_topic/link1.pdf', 'available'),
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
  (1, 'giannis.georgiou@student.edu', '2024-12-15 10:00:00', 'Αιθουσα Γ', NULL, NULL, NULL, NULL),
  (2, 'eleni.papadaki@student.edu', '2024-12-16 10:00:00', 'Room 102', NULL, NULL, NULL, NULL),
  (3, 'kostas.lazaridis@student.edu', '2024-12-17 10:00:00', 'Room 103', NULL, NULL, NULL, NULL),
  (4, 'maria.nikolaidou@student.edu', '2024-12-18 10:00:00', 'Room 104', NULL, NULL, NULL, NULL),
  (5, 'nikos.vasilakis@student.edu', '2024-12-19 10:00:00', 'Room 105', NULL, NULL, NULL, NULL),
  (6, 'ioannis.dimitriou@student.edu', '2024-12-20 10:00:00', 'Room 106', NULL, NULL, NULL, NULL),
  (7, 'katerina.kouli@student.edu', '2024-12-21 10:00:00', 'Room 107', NULL, NULL, NULL, NULL),
  (8, 'eleni.kiriakidou@student.edu', '2024-12-22 10:00:00', 'Room 108', NULL, NULL, NULL, NULL),
  (9, 'antonis.papageorgiou@student.edu', '2024-12-23 10:00:00', 'Room 109', 5.0, 5.0, 5.0, 5.0),
  (10, 'vasilis.manousakis@student.edu', '2024-12-24 10:00:00', 'Room 110', NULL, NULL, NULL, NULL),
  (12, 'nikos.manolopoulos@student.edu', '2024-12-24 10:00:00', 'Room 111', NULL, NULL, NULL, NULL);
  

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
  
  
 -- theo part --
 
 -- part 2 -- 
 
 INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Artifical INtelligence and future', 'Description 13', '../uploads/pdf_link_topic/link13.pdf', 'available'),
  ('dimitris.papa@university.edu', 'Artifical INtelligence and future 2', 'Description 14', '../uploads/pdf_link_topic/link14.pdf', 'given'),
  ('dimitris.papa@university.edu', 'Artifical INtelligence and future 3', 'Description 15', '../uploads/pdf_link_topic/link15.pdf', 'given'),
  ('dimitris.papa@university.edu', 'Artifical INtelligence and future 4', 'Description 16', '../uploads/pdf_link_topic/link16.pdf', 'given');
  
  
  INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('s14', 'Alexandros', 'Papadopoulos', 1014, 'Hill St', '25', 'Athens', '10562', 'Dimitris', '2101234567', '6901234567', 'alexandros.papadopoulos@student.edu'),
  ('s15', 'Georgia', 'Mavrou', 1015, 'Forest Rd', '18A', 'Thessaloniki', '54645', 'Antonis', '2310456789', '6902345679', 'georgia.mavrou@student.edu'),
  ('s16', 'Sofia', 'Karagiorgi', 1016, 'Olive St', '5', 'Patras', '26224', 'Kostas', '2610234567', '6903456780', 'sofia.karagiorgi@student.edu'),
  ('s17', 'Petros', 'Tsakalos', 1017, 'Sunrise Ave', '10', 'Heraklion', '71325', 'Vasilis', '2810456789', '6904567891', 'petros.tsakalos@student.edu'),
  ('s18', 'Ioanna', 'Makri', 1018, 'Lake St', '12B', 'Larissa', '41335', 'Giannis', '2410234567', '6905678912', 'ioanna.makri@student.edu'),
  ('s19', 'Dimitra', 'Zafeiriou', 1019, 'Skyline Rd', '9', 'Chania', '73222', 'Manolis', '2820345678', '6906789123', 'dimitra.zafeiriou@student.edu'),
  ('s20', 'Theodoros', 'Stefanidis', 1020, 'Garden St', '14', 'Volos', '38322', 'Nikos', '2420345678', '6907891234', 'theodoros.stefanidis@student.edu'),
  ('s21', 'Eirini', 'Spanou', 1021, 'Pearl St', '17A', 'Athens', '10462', 'Michalis', '2103456789', '6908912345', 'eirini.spanou@student.edu'),
  ('s22', 'Christos', 'Lykourgos', 1022, 'Bridge Rd', '8C', 'Thessaloniki', '54655', 'Kostas', '2310567890', '6909123456', 'christos.lykourgos@student.edu'),
  ('s23', 'Anastasia', 'Koutra', 1023, 'Ocean Blvd', '30', 'Rhodes', '85200', 'Vasilis', '2241034567', '6901234568', 'anastasia.koutra@student.edu');
  
  INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('alexandros.papadopoulos@student.edu', 14, 'pending', NULL, NULL, NULL, NULL, NULL),
  ('georgia.mavrou@student.edu', 15, 'active', '2022-02-10', NULL, NULL, NULL, NULL),
  ('sofia.karagiorgi@student.edu', 16, 'active', '2024-02-10', NULL, NULL, NULL, NULL);
  
  
  INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (14, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', NULL),
  (15, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', 'anna.nikolaou@university.edu'),
  (16, 'dimitris.papa@university.edu', 'anna.nikolaou@university.edu', 'george.papadakis@university.edu');
  
  INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES
  ('alexandros.papadopoulos@student.edu', 'maria.ioannou@university.edu', 14, 'pending', NULL, '2024-01-10'),
  ('alexandros.papadopoulos@student.edu', 'nikos.katsaros@university.edu', 14, 'accepted', NULL, '2024-01-10');
  
  -- part 3 --

 -- pending -- 
 
INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Artifical INtelligence and future 17', 'Description 17', '../uploads/pdf_link_topic/link13.pdf', 'given');
  
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (17, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', NULL);
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('petros.tsakalos@student.edu', 17, 'pending', NULL, NULL, NULL, NULL, NULL);
  
    INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES
  ('petros.tsakalos@student.edu', 'maria.ioannou@university.edu', 17, 'pending', NULL, '2024-01-10'),
  ('petros.tsakalos@student.edu', 'nikos.katsaros@university.edu', 17, 'accepted', '2024-11-11', '2024-01-10');
  
  -- active --
  
  
  INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Artifical INtelligence and future 18', 'Description 18', '../uploads/pdf_link_topic/link13.pdf', 'given');
  
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (18, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', 'kostas.nikolopoulos@university.edu');
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('ioanna.makri@student.edu', 18, 'active', '2024-01-10', NULL, NULL, NULL, NULL);
  
  -- active 2 --
  
    INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Artifical INtelligence and future 19', 'Description 19', '../uploads/pdf_link_topic/link13.pdf', 'given');
  
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (19, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', 'kostas.nikolopoulos@university.edu');
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('dimitra.zafeiriou@student.edu', 19, 'active', '2024-01-10', NULL, NULL, NULL, NULL);
  
UPDATE anathesi_diplomatikis
SET protocol_number = 50 
WHERE id_diploma = 19;
  
  
-- active 3 --

    INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 20', 'Description 20', '../uploads/pdf_link_topic/link13.pdf', 'given');
  
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (20, 'nikos.katsaros@university.edu', 'dimitris.papa@university.edu', 'kostas.nikolopoulos@university.edu');
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('theodoros.stefanidis@student.edu', 20, 'active', '2024-01-10', NULL, NULL, NULL, NULL);  
  
-- under exam --

    INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 21', 'Description 21', '../uploads/pdf_link_topic/link13.pdf', 'given');
  
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (21, 'nikos.katsaros@university.edu', 'dimitris.papa@university.edu', 'kostas.nikolopoulos@university.edu');
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('eirini.spanou@student.edu', 21, 'under examination', '2024-01-10', NULL, 'link', '../uploads/students/21.pdf', 'links');  
  
  INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade)
VALUES
  (21, 'eirini.spanou@student.edu', '2024-12-15 10:00:00', 'Αιθουσα Γ', 8, NULL, 5, NULL);
  
  -- under exam  2 --
  
      INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 22', 'Description 22', '../uploads/pdf_link_topic/link13.pdf', 'given');
  
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (22, 'nikos.katsaros@university.edu', 'dimitris.papa@university.edu', 'kostas.nikolopoulos@university.edu');
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('anastasia.koutra@student.edu', 22, 'under examination', '2024-01-10', NULL, 'link', '../uploads/students/22.pdf', 'links');  
  
  INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade)
VALUES
  (22, 'anastasia.koutra@student.edu', '2024-12-15 10:00:00', 'Αιθουσα Γ', NULL, NULL, NULL, NULL);
  
  
  -- finished -- 
  
      INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 23', 'Description 23', '../uploads/pdf_link_topic/link13.pdf', 'given');
  
INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (23, 'nikos.katsaros@university.edu', 'dimitris.papa@university.edu', 'kostas.nikolopoulos@university.edu');
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('christos.lykourgos@student.edu', 23, 'finished', '2024-01-10', '2025-01-10', 'link', '../uploads/students/22.pdf', 'links');  
  
  INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade)
VALUES
  (23, 'christos.lykourgos@student.edu', '2024-12-15 10:00:00', 'Αιθουσα Γ', 8, 7, 5, 6.33);
  
  UPDATE eksetasi_diplomatikis SET praktiko_bathmologisis = '../uploads/praktiko/22_praktiko_simplified.html' WHERE id_diplomatikis = 23;
  
  -- PART 4 invites --
  
  INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('s24', 'Panagiotis', 'Georgiou', 1024, 'River Ave', '5B', 'Athens', '10572', 'Andreas', '2105678910', '6905678910', 'panagiotis.georgiou@student.edu'),
  ('s25', 'Stella', 'Papadimitriou', 1025, 'Valley Rd', '22', 'Thessaloniki', '54735', 'Dimitris', '2310678901', '6906789011', 'stella.papadimitriou@student.edu'),
  ('s26', 'Michalis', 'Konstantinou', 1026, 'Pine St', '13A', 'Patras', '26322', 'Kostas', '2610456789', '6907890123', 'michalis.konstantinou@student.edu'),
  ('s27', 'Eirini', 'Tsakiri', 1027, 'Horizon Blvd', '9', 'Heraklion', '71425', 'Vasilis', '2810567890', '6908912345', 'eirini.tsakiri@student.edu'),
  ('s28', 'Fotis', 'Vlachos', 1028, 'Cedar St', '4', 'Larissa', '41425', 'Yiannis', '2410456789', '6909123456', 'fotis.vlachos@student.edu');
  
   INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 24', 'Description 24', '../uploads/pdf_link_topic/link13.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 25', 'Description 25', '../uploads/pdf_link_topic/link14.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 26', 'Description 26', '../uploads/pdf_link_topic/link15.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 27', 'Description 27', '../uploads/pdf_link_topic/link16.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Artifical INtelligence and future 28', 'Description 28', '../uploads/pdf_link_topic/link16.pdf', 'given');
  
  
INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links)
VALUES
  ('panagiotis.georgiou@student.edu', 24, 'pending', NULL, NULL, NULL, NULL, NULL),
  ('stella.papadimitriou@student.edu', 25, 'pending', NULL, NULL, NULL, NULL, NULL),
  ('michalis.konstantinou@student.edu', 26, 'pending', NULL, NULL, NULL, NULL, NULL),
  ('eirini.tsakiri@student.edu', 27, 'pending', NULL, NULL, NULL, NULL, NULL),
  ('fotis.vlachos@student.edu', 28, 'active', NULL, NULL, NULL, NULL, NULL);


INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES
  (24, 'nikos.katsaros@university.edu', 'maria.ioannou@university.edu', NULL),
  (25, 'nikos.katsaros@university.edu', 'anna.nikolaou@university.edu', NULL),
  (26, 'nikos.katsaros@university.edu', NULL, NULL),
  (27, 'nikos.katsaros@university.edu', NULL, NULL),
  (28, 'nikos.katsaros@university.edu', 'anna.nikolaou@university.edu', 'maria.ioannou@university.edu');
  
INSERT INTO prosklisi_se_trimeli (student_email, prof_email, id_dip, status, reply_date, invitation_date)
VALUES
  ('panagiotis.georgiou@student.edu', 'dimitris.papa@university.edu', 24, 'pending', NULL, '2024-01-10'),
  ('panagiotis.georgiou@student.edu', 'nikos.katsaros@university.edu', 24, 'pending', NULL, '2024-01-10'),
  ('stella.papadimitriou@student.edu', 'dimitris.papa@university.edu', 25, 'pending', NULL, '2024-01-10'),
  ('stella.papadimitriou@student.edu', 'nikos.katsaros@university.edu', 25, 'pending', NULL, '2024-01-10'),
  ('michalis.konstantinou@student.edu', 'dimitris.papa@university.edu', 26, 'pending', NULL, '2024-01-10'),
  ('michalis.konstantinou@student.edu', 'nikos.katsaros@university.edu', 26, 'pending', NULL, '2024-01-10'),
  ('eirini.tsakiri@student.edu', 'dimitris.papa@university.edu', 27, 'pending', NULL, '2024-01-10'),
  ('eirini.tsakiri@student.edu', 'nikos.katsaros@university.edu', 27, 'pending', NULL, '2024-01-10'),
  ('fotis.vlachos@student.edu', 'dimitris.papa@university.edu', 28, 'pending', NULL, '2024-01-10');
  
  
 -- PART 5 -- 
  
  
INSERT INTO diplomatiki (email_prof, title, description, pdf_link_topic, status)
VALUES
  ('dimitris.papa@university.edu', 'Sample Thesis Title 4', 'Description 4', 'link4.pdf', 'given'),
  ('dimitris.papa@university.edu', 'Sample Thesis Title 5', 'Description 5', 'link5.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Sample Thesis Title 6', 'Description 6', 'link6.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Sample Thesis Title 7', 'Description 7', 'link7.pdf', 'given'),
  ('nikos.katsaros@university.edu', 'Sample Thesis Title 8', 'Description 8', 'link8.pdf', 'given'),
  ('dimitra.kondylia@university.edu', 'Sample Thesis Title 9', 'Description 9', 'link9.pdf', 'given');  
  
  
  INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2) 
VALUES 
(29, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', 'maria.ioannou@university.edu'),
(30, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', 'maria.ioannou@university.edu'),
(31, 'nikos.katsaros@university.edu', 'dimitris.papa@university.edu', 'maria.ioannou@university.edu'),
(32, 'nikos.katsaros@university.edu', 'maria.ioannou@university.edu', 'dimitris.papa@university.edu'),
(33, 'dimitris.papa@university.edu', 'nikos.katsaros@university.edu', 'maria.ioannou@university.edu'),
(34, 'nikos.katsaros@university.edu', 'maria.ioannou@university.edu', 'dimitris.papa@university.edu');


  INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('s14', 'Alex', 'Papa', 1014, 'Hill St', '25', 'Athens', '10562', 'Dimitris', '2101234567', '6901234567', 'alexpoulos@student.edu'),
  ('s15', 'Georg', 'Mav', 1015, 'Forest Rd', '18A', 'Thessaloniki', '54645', 'Antonis', '2310456789', '6902345679', 'georvrou@student.edu'),
  ('s16', 'Sof', 'Karagi', 1016, 'Olive St', '5', 'Patras', '26224', 'Kostas', '2610234567', '6903456780', 'sofigiorgi@student.edu'),
  ('s17', 'Pets', 'Tsaos', 1017, 'Sunrise Ave', '10', 'Heraklion', '71325', 'Vasilis', '2810456789', '6904567891', 'petrlos@student.edu'),
  ('s18', 'Ioaa', 'Mai', 1018, 'Lake St', '12B', 'Larissa', '41335', 'Giannis', '2410234567', '6905678912', 'ioanna.ri@student.edu'),
  ('s19', 'Dima', 'Zafou', 1019, 'Skyline Rd', '9', 'Chania', '73222', 'Manolis', '2820345678', '6906789123', 'dimiiriou@student.edu');


INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links) 
VALUES 
('alexpoulos@student.edu', 29, 'finished', '2024-01-15', '2024-12-15', 'http://example.com/nemertes/101', 'path/to/diploma1.pdf', 'http://example.com/external_link1'),
('georvrou@student.edu', 30, 'finished', '2024-03-01', '2025-1-1', 'http://example.com/nemertes/102', 'path/to/diploma2.pdf', 'http://example.com/external_link2'),
('sofigiorgi@student.edu', 31, 'finished', '2024-02-20', '2026-1-1', 'http://example.com/nemertes/103', 'path/to/diploma3.pdf', 'http://example.com/external_link3'),
('petrlos@student.edu', 32, 'finished', '2024-04-10', '2024-10-10', 'http://example.com/nemertes/104', 'path/to/diploma4.pdf', 'http://example.com/external_link4'),
('ioanna.ri@student.edu', 33, 'active', '2024-04-10', NULL, 'http://example.com/nemertes/104', 'path/to/diploma4.pdf', 'http://example.com/external_link4'),
('dimiiriou@student.edu', 34, 'active', '2024-04-10', NULL, 'http://example.com/nemertes/104', 'path/to/diploma4.pdf', 'http://example.com/external_link4');

INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade, praktiko_bathmologisis) 
VALUES 
(29, 'alexpoulos@student.edu', '2024-06-15 09:00:00', 'Room A101', 8.50, 7.75, 9.00, 8.42, 'Pass'),
(30, 'georvrou@student.edu', '2024-06-16 11:00:00', 'Room B202', 6.80, 7.90, 8.20, 7.30, 'Pass'),
(31, 'sofigiorgi@student.edu', '2024-06-17 14:00:00', 'Room C303', 7.50, 8.00, 8.75, 8.08, 'Pass'),
(32, 'petrlos@student.edu', '2024-06-18 10:00:00', 'Room D404', 9.00, 9.50, 9.30, 9.27, 'Pass');
  

  
  


  

  
