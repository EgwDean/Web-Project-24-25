DROP DATABASE IF EXISTS html;
CREATE DATABASE html;
USE html;

CREATE TABLE users (
  username VARCHAR(30) BINARY,
  password VARCHAR(20),
  firstname VARCHAR(25),
  lastname VARCHAR(45),
  email VARCHAR(30));
  
CREATE TABLE admins (
  username VARCHAR(30) BINARY,
  password VARCHAR(20),
  firstname VARCHAR(25),
  lastname VARCHAR(45),
  email VARCHAR(30));

-- see tables created
SELECT * 
FROM users;
SELECT * 
FROM admins;
--

-- Insert users and admins
INSERT INTO users (username, password, firstname, lastname, email)
VALUES 
('john_doe', 'password123', 'John', 'Doe', 'john.doe@example.com'),
('jane_smith', 'qwerty456', 'Jane', 'Smith', 'jane.smith@example.net'),
('mike_brown', 'abcde789', 'Mike', 'Brown', 'mike.brown@example.org'),
('emily_white', 'zxcvb321', 'Emily', 'White', 'emily.white@example.com');

INSERT INTO admins (username, password, firstname, lastname, email) VALUES 
('admin1', 'password123', 'Alice', 'Johnson', 'alice.johnson@example.com'),
('admin2', 'password456', 'Bob', 'Smith', 'bob.smith@example.com'),
('admin3', 'password789', 'Charlie', 'Brown', 'charlie.brown@example.com'),
('admin4', 'password101', 'David', 'Williams', 'david.williams@example.com'),
('admin5', 'password202', 'Eva', 'Jones', 'eva.jones@example.com'),
('admin6', 'password303', 'Frank', 'Garcia', 'frank.garcia@example.com');
--
