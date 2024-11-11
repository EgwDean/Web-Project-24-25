-- CREATE schema html;

CREATE TABLE student (
    am INT PRIMARY KEY,
    username VARCHAR(50) UNIQUE, 
    name VARCHAR(100), 
    streetnumber VARCHAR(50),
    city VARCHAR(50), 
    postcode VARCHAR(10), 
    father VARCHAR(100),
    landline VARCHAR(15), 
    mobile VARCHAR(15), 
    email VARCHAR(100) UNIQUE, 
    password VARCHAR(255)
);

CREATE TABLE professor (
    email VARCHAR(50) PRIMARY KEY,
    username VARCHAR(50) UNIQUE, 
    name VARCHAR(100), 
    topic VARCHAR(50),
    landline VARCHAR(15), 
    mobile VARCHAR(15), 
    department VARCHAR(100),
    university VARCHAR(100), 
    password VARCHAR(255)  
);


CREATE TABLE secretary (
	email VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255)
);
