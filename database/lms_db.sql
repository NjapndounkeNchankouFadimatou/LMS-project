-- ============================================
-- File: lms_db.sql
-- Purpose: Create the database and all tables for the LMS project
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS lms_db;
USE lms_db;

-- ============================================
-- Table: users
-- Stores all users (student, teacher, admin)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') NOT NULL,
    sex ENUM('M', 'F') NOT NULL,
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Table: modules
-- Created by admin or teacher, groups several courses
-- ============================================
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    description TEXT NOT NULL,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- Table: courses
-- Each course belongs to a module and has a teacher
-- ============================================
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    user_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    type ENUM('pdf', 'video') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    description TEXT,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- Table: quiz
-- Each row is ONE question belonging to a course's quiz
-- Multiple rows can share the same course_id (multiple questions)
-- ============================================
CREATE TABLE quiz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    question_text TEXT NOT NULL,
    answer_options TEXT NOT NULL,      -- e.g. "Option A, Option B, Option C, Option D"
    correct_answer VARCHAR(255) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- ============================================
-- Table: score
-- Stores the score a student got for a course's quiz
-- ============================================
CREATE TABLE score (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    user_id INT NOT NULL,
    score INT NOT NULL,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- Table: certification
-- Issued to a student when they validate a module
-- ============================================
CREATE TABLE certification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    module_id INT NOT NULL,
    user_id INT NOT NULL,
    description TEXT,
    issued_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);