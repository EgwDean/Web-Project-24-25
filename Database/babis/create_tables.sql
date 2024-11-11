DROP DATABASE IF EXISTS diplomatiki_support;
CREATE DATABASE diplomatiki_support;

USE diplomatiki_support;


# Ο Ν Τ Ο Τ Η Τ Ε Σ 
CREATE TABLE professor (
  username VARCHAR(100) PRIMARY KEY NOT NULL,
  password VARCHAR(100) NOT NULL,
  name VARCHAR(100) NOT NULL,
  surname VARCHAR(100) NOT NULL,
  email_prof VARCHAR(100) UNIQUE NOT NULL,
  topic VARCHAR(100),
  landline VARCHAR(15),
  mobile VARCHAR(15),
  department VARCHAR(50) NOT NULL,
  university VARCHAR(100) NOT NULL
  );		


CREATE TABLE student (
  username VARCHAR(100) PRIMARY KEY NOT NULL,
  password CHAR(64) NOT NULL,
  name VARCHAR(100) NOT NULL,
  surname VARCHAR(100) NOT NULL,
  student_number INT UNIQUE NOT NULL,
  street VARCHAR(100) NOT NULL,
  number VARCHAR(10) NOT NULL,           # it may contain letters like 5A
  city VARCHAR(50) NOT NULL, 
  postcode VARCHAR(10) NOT NULL,         # INT is wrong because 0's get canceled in the beginning
  father_name VARCHAR(100),
  landline_telephone VARCHAR(15),
  mobile_telephone VARCHAR(15),
  email_st VARCHAR(100) UNIQUE NOT NULL
);


CREATE TABLE secretary (
	email_sec VARCHAR(100) PRIMARY KEY NOT NULL,
    password VARCHAR(255)
);


CREATE TABLE diplomatiki (
  title VARCHAR(100) PRIMARY KEY NOT NULL,    
  description TEXT,                           
  pdf_link_topic VARCHAR(255)                 
);

CREATE TABLE eksetasi_diplomatikis (
  exam_date DATE NOT NULL,                    
  exam_room VARCHAR(50),                       
  grade1 DECIMAL(3, 2),               
  grade2 DECIMAL(3, 2),               
  grade3 DECIMAL(3, 2),               
  final_grade DECIMAL(3, 2),          
  praktiko_bathmologisis VARCHAR(255)     
);





# Σ Υ Σ Χ Ε Τ Ι Σ Ε Ι Σ
CREATE TABLE anathesi_diplomatikis (
  student_num INT NOT NULL,                     
  title_dipl VARCHAR(100) NOT NULL,                     
  status ENUM('pending', 'active', 'canceled', 'under examination', 'finished') NOT NULL,  
  start_date DATE NOT NULL,                        
  end_date DATE,                                   
  Nemertes_link VARCHAR(255)
);


CREATE TABLE professor_notes (
	email_prof VARCHAR(100) NOT NULL,
    title_d VARCHAR(100) NOT NULL,
    notes TEXT
);


CREATE TABLE trimelis_epitropi_diplomatikis (
  ID_trimelous INT PRIMARY KEY,                   
  title_diplom VARCHAR(100) NOT NULL,             
  supervisor VARCHAR(100),               
  member1 VARCHAR(100),                  
  member2 VARCHAR(100)
);

CREATE TABLE prosklisi_se_trimeli (
  student_id INT NOT NULL,                             
  prof_email VARCHAR(100) NOT NULL,                   
  title_diploma VARCHAR(100) NOT NULL,                 
  status ENUM('pending', 'accepted', 'declined') NOT NULL, 
  invitation_date DATE NOT NULL,                      
  accept_date DATE,                   
  decline_date DATE
);