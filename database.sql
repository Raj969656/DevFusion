CREATE DATABASE auth_system;

USE auth_system;

CREATE TABLE users(

    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(100),

    email VARCHAR(100) UNIQUE,

    password VARCHAR(255),

    profile_image VARCHAR(255),

    bio TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE posts(

    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT,

    title VARCHAR(255),

    content TEXT,

    image VARCHAR(255),

    views INT DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE

);