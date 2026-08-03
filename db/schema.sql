-- Création de la base
CREATE DATABASE IF NOT EXISTS university_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE university_db;

-- =========================
-- Table des utilisateurs
-- =========================
CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) UNIQUE,
  user_firstname VARCHAR(100) NOT NULL,
  user_lastname VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL, -- stocker un hash (bcrypt/argon2)
  profile ENUM('admin','invite') NOT NULL DEFAULT 'invite',
  user_profile_image VARCHAR(255) DEFAULT NULL, -- chemin vers l'image (ex: assets/img/user1.png)
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- Table des filières 
-- =========================
CREATE TABLE streams (
  stream_code VARCHAR(20) PRIMARY KEY,
  stream_name VARCHAR(100) NOT NULL UNIQUE
);

-- =========================
-- Table des étudiants
-- =========================
CREATE TABLE students (
  stu_id INT AUTO_INCREMENT PRIMARY KEY,
  stu_firstname VARCHAR(100) NOT NULL,
  stu_lastname VARCHAR(100) NOT NULL,
  stu_birthday DATE NOT NULL,
  stu_birthplace VARCHAR(100),
  stu_gender ENUM('M','F') NOT NULL,
  stu_address VARCHAR(255),
  stu_city VARCHAR(100),
  stu_country VARCHAR(100),
  stu_phone_number VARCHAR(20),
  stu_email VARCHAR(150) UNIQUE,
  stu_degree VARCHAR(100),   -- Licence, Master, etc.
  stu_level VARCHAR(50),     -- L1, L2, M1...
  stream_code VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (stream_code) REFERENCES streams(stream_code)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);

-- =========================
-- Index pour optimiser les recherches
-- =========================
CREATE INDEX idx_students_email ON students(stu_email);
CREATE INDEX idx_students_stream ON students(stream_code);
