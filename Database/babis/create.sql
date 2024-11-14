DROP DATABASE IF EXISTS diplomatiki_support;
CREATE DATABASE diplomatiki_support;

USE diplomatiki_support;


CREATE TABLE professor (
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  email_professor VARCHAR(255) PRIMARY KEY NOT NULL,
  topic VARCHAR(255),
  landline VARCHAR(13),
  mobile VARCHAR(13) NOT NULL,
  department VARCHAR(255) NOT NULL,
  university VARCHAR(255) NOT NULL
  );		


CREATE TABLE student (
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  student_number INT NOT NULL,
  street VARCHAR(255) NOT NULL,
  number VARCHAR(10) NOT NULL,
  city VARCHAR(255) NOT NULL, 
  postcode VARCHAR(5) NOT NULL,
  father_name VARCHAR(255) NOT NULL,
  landline_telephone VARCHAR(13),
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
  pdf_link_topic VARCHAR(255),
  status ENUM('available', 'given') NOT NULL,
  CONSTRAINT DIPLPROF FOREIGN KEY (email_prof) REFERENCES professor(email_professor) 
  ON DELETE CASCADE ON UPDATE CASCADE
)AUTO_INCREMENT = 1;


CREATE TABLE eksetasi_diplomatikis (
  id_diplomatikis INT NOT NULL,
  email_st VARCHAR(255) NOT NULL,
  exam_date DATETIME NOT NULL,                    
  exam_room VARCHAR(255) NOT NULL,
  grade1 DECIMAL(3, 2),               
  grade2 DECIMAL(3, 2),               
  grade3 DECIMAL(3, 2),               
  final_grade DECIMAL(3, 2),          
  praktiko_bathmologisis VARCHAR(255),
  CONSTRAINT EXAMDIPL FOREIGN KEY (id_diplomatikis) REFERENCES diplomatiki(id_diplomatiki) 
  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT EXAMSTUD FOREIGN KEY (email_st) REFERENCES student(email_student) 
  ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE anathesi_diplomatikis (
  email_stud VARCHAR(255) NOT NULL,                     
  id_diploma INT NOT NULL,                     
  status ENUM('pending', 'active', 'canceled', 'under examination', 'finished') NOT NULL DEFAULT 'pending',  
  start_date DATE NOT NULL,                        
  end_date DATE,                                   
  Nemertes_link VARCHAR(255),
  pdf_main_diploma VARCHAR(255),
  external_links VARCHAR(255),
  CONSTRAINT ANASTUD FOREIGN KEY (email_stud) REFERENCES student(email_student) 
  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT ANADIPL FOREIGN KEY (id_diploma) REFERENCES diplomatiki(id_diplomatiki) 
  ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE professor_notes (
  professor_email VARCHAR(255) NOT NULL,
  id_diplom INT NOT NULL,
  notes TEXT NOT NULL,
  CONSTRAINT NOTEPROF FOREIGN KEY (professor_email) REFERENCES professor(email_professor) 
  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT NOTEDIPL FOREIGN KEY (id_diplom) REFERENCES diplomatiki(id_diplomatiki) 
  ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE trimelis_epitropi_diplomatikis (                 
  id_dipl INT NOT NULL,    
  supervisor VARCHAR(255),               
  member1 VARCHAR(255),                  
  member2 VARCHAR(255),
  FOREIGN KEY (id_dipl) REFERENCES diplomatiki(id_diplomatiki) 
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (supervisor) REFERENCES professor(email_professor) 
  ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (member1) REFERENCES professor(email_professor) 
  ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (member2) REFERENCES professor(email_professor) 
  ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE prosklisi_se_trimeli (
  student_email VARCHAR(255) NOT NULL,                             
  prof_email VARCHAR(255) NOT NULL,                   
  id_dip INT NOT NULL,                 
  status ENUM('pending', 'accepted', 'declined') NOT NULL DEFAULT 'pending', 
  reply_date DATE, 
  invitation_date DATE,
  FOREIGN KEY (student_email) REFERENCES student(email_student) 
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (prof_email) REFERENCES professor(email_professor) 
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_dip) REFERENCES diplomatiki(id_diplomatiki) 
  ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE log (
  id_di INT NOT NULL, 
  record TEXT NOT NULL
);
