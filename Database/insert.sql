INSERT INTO student (am, username, name, streetnumber, city, postcode, father, landline, mobile, email, password)
VALUES 
(1001, 'jdoe01', 'John Doe', '12 Elm Street', 'Athens', '11523', 'Richard Doe', '2101234567', '6981234567', 'jdoe01@example.com', 'p1'),
(1002, 'asmith02', 'Alice Smith', '45 Maple Avenue', 'Thessaloniki', '54622', 'Robert Smith', '2310123456', '6990123456', 'asmith02@example.com', 'p2'),
(1003, 'bwilson03', 'Bob Wilson', '78 Oak Road', 'Patras', '26222', 'George Wilson', '2610123456', '6978123456', 'bwilson03@example.com', 'p3');

INSERT INTO professor (email, username, name, topic, landline, mobile, department, university, password)
VALUES 
('gpapadopoulos@example.com', 'gpapadopoulos', 'George Papadopoulos', 'Computer Science', '2107654321', '6987654321', 'Informatics', 'National University', 'p4'),
('emantzouranis@example.com', 'emantzouranis', 'Eleni Mantzouranis', 'Physics', '2310765432', '6998765432', 'Physics Department', 'University of Thessaloniki', 'p5'),
('kgeorgiou@example.com', 'kgeorgiou', 'Kostas Georgiou', 'Mathematics', '2610765432', '6979765432', 'Math Department', 'University of Patras', 'p6');

INSERT INTO secretary (email, password)
VALUES 
('admin1@university.com', 'p7'),
('admin2@university.com', 'p8'),
('admin3@university.com', 'p9');

DELETE FROM student;
DELETE FROM professor;
DELETE FROM administration;