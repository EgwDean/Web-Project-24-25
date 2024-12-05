DROP TABLE diplomatiki;
DELETE FROM trimelis_epitropi_diplomatikis;
DELETE FROM anathesi_diplomatikis;
DELETE FROM eksetasi_diplomatikis;
DELETE FROM professor;
DELETE FROM student;


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
  ('dimitris.papa@university.edu', 'Quantum Physics in Computation', 'This thesis explores the applications of quantum mechanics in modern computational theory.', 'uploads/1.pdf', 'available');

INSERT INTO professor (password, name, surname, email_professor, topic, landline, mobile, department, university)
VALUES
  ('p1', 'Dimitris', 'Papadopoulos', 'dimitris.papa@university.edu', 'Physics', '2101234567', '6901234567', 'Physics', 'National University'),
  ('p2', 'Maria', 'Ioannou', 'maria.ioannou@university.edu', 'Mathematics', '2101234568', '6901234568', 'Mathematics', 'National University'),
  ('p3', 'Nikos', 'Katsaros', 'nikos.katsaros@university.edu', 'Computer Science', '2101234569', '6901234569', 'Computer Science', 'National University');
  
  INSERT INTO student (password, name, surname, student_number, street, number, city, postcode, father_name, landline_telephone, mobile_telephone, email_student)
VALUES
  ('pass1234', 'Γιώργος', 'Doe', 1001, 'Main Street', '10', 'New York', '10001', 'Robert Doe', '1234567890', '9876543210', 'john.doe@example.com');

INSERT INTO eksetasi_diplomatikis (id_diplomatikis, email_st, exam_date, exam_room, grade1, grade2, grade3, final_grade, praktiko_bathmologisis) 
VALUES 
(1, 'john.doe@example.com', '2024-06-15 09:00:00', 'Room A101', 8.50, 7.75, 9.00, 8.42, 'Pass');

INSERT INTO trimelis_epitropi_diplomatikis (id_dipl, supervisor, member1, member2)
VALUES (1, 'dimitris.papa@university.edu', 'maria.ioannou@university.edu', 'nikos.katsaros@university.edu');

INSERT INTO anathesi_diplomatikis (email_stud, id_diploma, status, start_date, end_date, Nemertes_link, pdf_main_diploma, external_links, protocol_number) 
VALUES ('john.doe@example.com', 1, 'finished', '2024-01-15', '2024-12-15', 'https://nemertes.example.com/diploma1', 'path/to/diploma.pdf', 'https://external-link.com', 20),
 ('john.doe@example.com', 1, 'canceled_by_student', '2024-01-15', '2024-12-15', 'https://nemertes.example.com/diploma1', 'path/to/diploma.pdf', 'https://external-link.com', 20);


SELECT 
    t.id_dipl AS diploma_id,
    CONCAT(p1.name, ' ', p1.surname) AS supervisor_full_name,
    CONCAT(p2.name, ' ', p2.surname) AS member1_full_name,
    CONCAT(p3.name, ' ', p3.surname) AS member2_full_name,
    CONCAT(s.name, ' ', s.surname) AS student_full_name,  
    e.exam_date AS examination_date,
    e.exam_room AS examination_room,
    e.grade1 AS grade_member1,
    e.grade2 AS grade_member2,
    e.grade3 AS grade_member3,
    e.final_grade AS final_grade,
    a.start_date AS assignment_start_date,
    a.end_date AS assignment_end_date,
    d.title AS diploma_title  
FROM 
    trimelis_epitropi_diplomatikis t
INNER JOIN 
    professor p1 ON t.supervisor = p1.email_professor
INNER JOIN 
    professor p2 ON t.member1 = p2.email_professor
INNER JOIN 
    professor p3 ON t.member2 = p3.email_professor
INNER JOIN 
    eksetasi_diplomatikis e ON t.id_dipl = e.id_diplomatikis
INNER JOIN 
    anathesi_diplomatikis a ON t.id_dipl = a.id_diploma
INNER JOIN 
    diplomatiki d ON t.supervisor = d.email_prof
INNER JOIN 
    student s ON a.email_stud = s.email_student
WHERE t.id_dipl = 1 and a.status = 'finished'; 





