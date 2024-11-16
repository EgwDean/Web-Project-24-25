SELECT * FROM trimelis_epitropi_diplomatikis;
SELECT * FROM anathesi_diplomatikis;
SELECT * FROM eksetasi_diplomatikis;

DELETE FROM trimelis_epitropi_diplomatikis;
DELETE FROM anathesi_diplomatikis;
DELETE FROM eksetasi_diplomatikis;

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


SELECT AVG(DATEDIFF(end_date, start_date)) AS avg_completion_days
FROM anathesi_diplomatikis INNER JOIN trimelis_epitropi_diplomatikis ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl
WHERE supervisor = 'dimitris.papa@university.edu' AND anathesi_diplomatikis.status = 'finished';

SELECT AVG(DATEDIFF(end_date, start_date)) AS avg_completion_days
FROM anathesi_diplomatikis INNER JOIN trimelis_epitropi_diplomatikis ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl
WHERE (member1 = 'dimitris.papa@university.edu' OR member2 = 'dimitris.papa@university.edu') AND anathesi_diplomatikis.status = 'finished';

SELECT AVG(final_grade) AS mesos_oros
FROM trimelis_epitropi_diplomatikis INNER JOIN eksetasi_diplomatikis ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE supervisor = 'dimitris.papa@university.edu' AND final_grade IS NOT NULL;

SELECT AVG(final_grade) AS mesos_oros
FROM trimelis_epitropi_diplomatikis INNER JOIN eksetasi_diplomatikis ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE (member1 = 'dimitris.papa@university.edu' OR member2 = 'dimitris.papa@university.edu') AND final_grade IS NOT NULL;

SELECT COUNT(*) AS plithos1
FROM trimelis_epitropi_diplomatikis INNER JOIN eksetasi_diplomatikis ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE supervisor = 'dimitris.papa@university.edu';

SELECT COUNT(*) AS plithos2
FROM trimelis_epitropi_diplomatikis INNER JOIN eksetasi_diplomatikis ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE member1 = 'dimitris.papa@university.edu' OR member2 = 'dimitris.papa@university.edu';

-- ----------------------------------------

SELECT ROUND(AVG(TIMESTAMPDIFF(MONTH, start_date, end_date)), 1) AS  avg_completion_days
FROM anathesi_diplomatikis 
INNER JOIN trimelis_epitropi_diplomatikis 
  ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl
WHERE supervisor = 'dimitris.papa@university.edu' 
  AND anathesi_diplomatikis.status = 'finished'

UNION

SELECT ROUND(AVG(TIMESTAMPDIFF(MONTH, start_date, end_date)), 1) AS avg_completion_days
FROM anathesi_diplomatikis 
INNER JOIN trimelis_epitropi_diplomatikis 
  ON anathesi_diplomatikis.id_diploma = trimelis_epitropi_diplomatikis.id_dipl
WHERE (member1 = 'dimitris.papa@university.edu' OR member2 = 'dimitris.papa@university.edu') 
  AND anathesi_diplomatikis.status = 'finished'

UNION

SELECT ROUND(AVG(final_grade), 1) AS mesos_oros
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE supervisor = 'dimitris.papa@university.edu' 
  AND final_grade IS NOT NULL

UNION

SELECT ROUND(AVG(final_grade), 1) AS mesos_oros
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE (member1 = 'dimitris.papa@university.edu' OR member2 = 'dimitris.papa@university.edu') 
  AND final_grade IS NOT NULL

UNION

SELECT CAST(COUNT(*) AS DECIMAL(10, 1)) AS plithos1
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE supervisor = 'dimitris.papa@university.edu'

UNION ALL

SELECT CAST(COUNT(*) AS DECIMAL(10, 1)) AS plithos2
FROM trimelis_epitropi_diplomatikis 
INNER JOIN eksetasi_diplomatikis 
  ON trimelis_epitropi_diplomatikis.id_dipl = eksetasi_diplomatikis.id_diplomatikis
WHERE member1 = 'dimitris.papa@university.edu' 
   OR member2 = 'dimitris.papa@university.edu';
