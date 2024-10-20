DROP DATABASE IF EXISTS html;
CREATE DATABASE html;

USE html;

CREATE TABLE users (
  username VARCHAR(100),
  password VARCHAR(100),
  firstname VARCHAR(100),
  lastname VARCHAR(100),
  email VARCHAR(100));
  
CREATE TABLE admins (
  username VARCHAR(100),
  password VARCHAR(100),
  firstname VARCHAR(100),
  lastname VARCHAR(100),
  email VARCHAR(100));

INSERT INTO users (username, password, firstname, lastname, email)
VALUES 
('john_doe', 'password123', 'John', 'Doe', 'john.doe@example.com'),
('jane_smith', 'qwerty456', 'Jane', 'Smith', 'jane.smith@example.net'),
('mike_brown', 'abcde789', 'Mike', 'Brown', 'mike.brown@example.org'),
('emily_white', 'zxcvb321', 'Emily', 'White', 'emily.white@example.com'),
('alex_jones', 'passAlex01', 'Alex', 'Jones', 'alex.jones@example.net'),
('sarah_taylor', 'taylorPass22', 'Sarah', 'Taylor', 'sarah.taylor@example.org'),
('david_wilson', 'wilsonSecure99', 'David', 'Wilson', 'david.wilson@example.com'),
('lisa_clark', 'lisaClark1995', 'Lisa', 'Clark', 'lisa.clark@example.net'),
('tom_harris', 'tharris2020', 'Tom', 'Harris', 'tom.harris@example.org'),
('nancy_kim', 'kimNancy007', 'Nancy', 'Kim', 'nancy.kim@example.com'),
('joshua_lee', 'leeStrongPass', 'Joshua', 'Lee', 'joshua.lee@example.net'),
('anna_martin', 'annaSecure321', 'Anna', 'Martin', 'anna.martin@example.com'),
('chris_jackson', 'cjacksonPass01', 'Chris', 'Jackson', 'chris.jackson@example.org'),
('olivia_thomas', 'livThomas678', 'Olivia', 'Thomas', 'olivia.thomas@example.com'),
('ethan_wright', 'wrightEth123', 'Ethan', 'Wright', 'ethan.wright@example.net'),
('victoria_evans', 'vickyEvans245', 'Victoria', 'Evans', 'victoria.evans@example.com'),
('daniel_roberts', 'robDanielXY', 'Daniel', 'Roberts', 'daniel.roberts@example.org'),
('karen_lewis', 'lewisKarenPass', 'Karen', 'Lewis', 'karen.lewis@example.com'),
('matthew_turner', 'turnerMatt909', 'Matthew', 'Turner', 'matthew.turner@example.net'),
('sophia_carter', 'sophiaCarter555', 'Sophia', 'Carter', 'sophia.carter@example.com');

INSERT INTO admins (username, password, firstname, lastname, email) VALUES 
('theo', 'theo123', 'Theofrastos', 'Paximadis', 'theo@example.com'),
('babis', 'babis123', 'Charalampos', 'Anastasiou', 'babis@example.com'),
('dean', 'dean123', 'Konstantinos', 'Anastasopoulos', 'dean@example.com');