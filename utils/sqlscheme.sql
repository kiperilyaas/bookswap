--Comandi usati per la realizzazione delle tabelle del DB

--Books:
CREATE TABLE IF NOT EXISTS books (  
id_book INT PRIMARY KEY,  
title VARCHAR(255),  
isbn VARCHAR(13),  
vol VARCHAR(10),  
author VARCHAR(255),  
school_year VARCHAR(9),  
id_class INT,  
id_subject INT,  
id_publish_house INT,  
id_faculty INT,  
price DECIMAL(10,2),  
FOREIGN KEY (id_subject) REFERENCES materie(id),  
FOREIGN KEY (id_publish_house) REFERENCES editori(id), 
FOREIGN KEY (id_faculty) REFERENCES facolta(id) ); 

--Class:
CREATE TABLE IF NOT EXISTS class ( 
    id_class INT PRIMARY KEY, 
    class VARCHAR(50) NOT NULL, 
    description TEXT NULL 
);

--Subjects:
CREATE TABLE IF NOT EXISTS subjects ( 
    id_subject INT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    description TEXT NULL);

--Faculty: 
CREATE TABLE IF NOT EXISTS faculty ( 
    id_faculty INT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    description TEXT NULL 
); 
 
--Listings:
CREATE TABLE IF NOT EXISTS listings ( 
    id_listing INT PRIMARY KEY, 
    id_book INT NOT NULL, 
    id_seller INT NOT NULL, 
    price DECIMAL(10,2) NOT NULL, 
    book_condition VARCHAR(50), 
    description TEXT NULL, 
    is_available TINYINT(1) DEFAULT 1, 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_book) REFERENCES books(id_book) 
); 
 
--Orders: 
CREATE TABLE IF NOT EXISTS orders ( 
    id_order INT PRIMARY KEY, 
    id_listing INT NOT NULL, 
    id_customer INT NOT NULL, 
    id_seller INT NOT NULL, 
    final_price DECIMAL(10,2) NOT NULL, 
    date_order DATE NOT NULL, 
    state VARCHAR(50), 
    time_meet TIME, 
    place_meet VARCHAR(255), 
    description_meet TEXT, 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (id_listing) REFERENCES listings(id_listing) 
); 

--Users: 
CREATE TABLE IF NOT EXISTS users ( 
    id_user INT PRIMARY KEY AUTO_INCREMENT, 
    name VARCHAR(100) NOT NULL, 
    surname VARCHAR(100) NOT NULL, 
    class VARCHAR(10), 
    email VARCHAR(255) UNIQUE NOT NULL, 
    password VARCHAR(255) NOT NULL 
); 

--Publishing_house: 
CREATE TABLE IF NOT EXISTS publishing_house ( 
    id_publish_house INT PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    description TEXT NULL 
); 
 