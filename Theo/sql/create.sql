DROP DATABASE IF EXISTS diplomatiki_support;
CREATE DATABASE diplomatiki_support;

USE diplomatiki_support;


CREATE TABLE professor (
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  email_professor VARCHAR(255) PRIMARY KEY NOT NULL,
  topic VARCHAR(255) DEFAULT NULL,
  landline VARCHAR(13) DEFAULT NULL,
  mobile VARCHAR(13) NOT NULL,
  department VARCHAR(255) NOT NULL,
  university VARCHAR(255) NOT NULL
  );		


CREATE TABLE student (
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  student_number INT NOT NULL,
  street VARCHAR(255) NOT NULL,
  number VARCHAR(10) NOT NULL,
  city VARCHAR(255) NOT NULL, 
  postcode VARCHAR(5) NOT NULL,
  father_name VARCHAR(255) NOT NULL,
  landline_telephone VARCHAR(13) DEFAULT NULL,
  mobile_telephone VARCHAR(13) NOT NULL, 
  email_student VARCHAR(255) PRIMARY KEY NOT NULL
);


CREATE TABLE secretary (
	email_sec VARCHAR(255) PRIMARY KEY NOT NULL,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE diplomatiki (
  id_diplomatiki INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  email_prof VARCHAR(255) NOT NULL,
  title VARCHAR(255) NOT NULL,    
  description TEXT NOT NULL,                            
  pdf_link_topic VARCHAR(255) DEFAULT NULL,
  status ENUM('available', 'given') NOT NULL
)AUTO_INCREMENT = 1;


CREATE TABLE eksetasi_diplomatikis (
  id_diplomatikis INT NOT NULL,
  email_st VARCHAR(255) NOT NULL,
  exam_date DATETIME NOT NULL,                    
  exam_room VARCHAR(255) NOT NULL,
  grade1 DECIMAL(3, 2) DEFAULT NULL,               
  grade2 DECIMAL(3, 2) DEFAULT NULL,               
  grade3 DECIMAL(3, 2) DEFAULT NULL,               
  final_grade DECIMAL(4, 2) DEFAULT NULL,          
  praktiko_bathmologisis VARCHAR(255) DEFAULT NULL
);


CREATE TABLE anathesi_diplomatikis (
  email_stud VARCHAR(255) NOT NULL,                     
  id_diploma INT NOT NULL,                     
  status ENUM('pending', 'active', 'canceled_by_student', 'canceled_by_professor', 'recalled', 'under examination', 'finished') NOT NULL DEFAULT 'pending',  
  start_date DATE NOT NULL,                        
  end_date DATE DEFAULT NULL,                                   
  Nemertes_link VARCHAR(255) DEFAULT NULL,
  pdf_main_diploma VARCHAR(255) DEFAULT NULL,
  external_links VARCHAR(255) DEFAULT NULL, 
  protocol_number INT DEFAULT NULL
);


CREATE TABLE professor_notes (
  professor_email VARCHAR(255) NOT NULL,
  id_diplom INT NOT NULL,
  notes TEXT NOT NULL
);


CREATE TABLE trimelis_epitropi_diplomatikis (                 
  id_dipl INT NOT NULL,    
  supervisor VARCHAR(255) NOT NULL,               
  member1 VARCHAR(255) DEFAULT NULL,                  
  member2 VARCHAR(255) DEFAULT NULL
);

CREATE TABLE prosklisi_se_trimeli (
  student_email VARCHAR(255) NOT NULL,                             
  prof_email VARCHAR(255) NOT NULL,                   
  id_dip INT NOT NULL,                 
  status ENUM('pending', 'accepted', 'declined') NOT NULL DEFAULT 'pending', 
  reply_date DATE DEFAULT NULL, 
  invitation_date DATE DEFAULT NULL
);

CREATE TABLE log (
  id_di INT NOT NULL, 
  record TEXT NOT NULL
);

CREATE TABLE cancellations (
	id_d INT NOT NULL, 
    meeting_number INT NOT NULL, 
    meeting_year YEAR NOT NULL
);
