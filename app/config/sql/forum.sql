-- Create Database forum
CREATE DATABASE IF NOT EXISTS forum;
GRANT SELECT, INSERT, UPDATE, DELETE ON forum.* TO root@localhost IDENTIFIED BY 'root';
FLUSH PRIVILEGES;


--Create Tables
USE forum;

--user table
CREATE TABLE IF NOT EXISTS user (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
firstname VARCHAR(50) NOT NULL,
lastname VARCHAR(50) NOT NULL,
username VARCHAR(30) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
usertype ENUM('superuser', 'admin', 'user') NOT NULL,
status ENUM('active', 'banned') NOT NULL,
registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id)
)ENGINE=InnoDB;

--comment table
CREATE TABLE IF NOT EXISTS comment (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
thread_id INT UNSIGNED NOT NULL,
user_id INT UNSIGNED NOT NULL,
body TEXT NOT NULL,
created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX (thread_id, created)
)ENGINE=InnoDB;


--thread table
CREATE TABLE IF NOT EXISTS thread (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id INT UNSIGNED NOT NULL,
title VARCHAR(50) NOT NULL,
created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
last_modified DATETIME NOT NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB;


--like_monitor
CREATE TABLE IF NOT EXISTS like_monitor (
id INT UNSIGNED NOT NULL AUTO_INCREMENT,
comment_id INT UNSIGNED NOT NULL,
user_id INT UNSIGNED NOT NULL,
liked TINYINT(1) UNSIGNED NOT NULL,
disliked TINYINT(1) UNSIGNED NOT NULL,
PRIMARY KEY (id)
)ENGINE=InnoDB;