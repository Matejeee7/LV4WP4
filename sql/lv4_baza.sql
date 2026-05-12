CREATE DATABASE IF NOT EXISTS lv4_filmovi_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lv4_filmovi_web;

DROP TABLE IF EXISTS ratings;
DROP TABLE IF EXISTS desired_movies;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS movies;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    release_year INT NOT NULL,
    genre VARCHAR(120) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    country VARCHAR(120) NOT NULL,
    rating VARCHAR(20) NOT NULL,
    type VARCHAR(40) NOT NULL,
    average_score DECIMAL(3,1) NOT NULL DEFAULT 7.0
);

CREATE TABLE desired_movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    movie_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_movie (user_id, movie_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
);

CREATE TABLE images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    image_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment VARCHAR(500),
    rated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_image (user_id, image_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE
);

-- admin lozinka: admin123
-- korisnik lozinka: user123
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@example.com', '$2y$10$vI8aWBnSFWyS.8lVfRZj/e1gHOC02kPx5MWcG9x.Q.GTR0nsR/Jaa', 'admin'),
('korisnik', 'korisnik@example.com', '$2y$10$H4.oT8tELmD1r.3c6UJAS.cMQUW6YyaYpUpEfgpQGrhQHTmTDsT9G', 'user');

INSERT INTO movies (title, release_year, genre, duration, country, rating, type, average_score) VALUES
('At Close Range', 1986, 'Crime', '111 min', 'United States', 'R', 'Movie', 7.0),
('Diner', 1982, 'Comedy', '110 min', 'United States', 'R', 'Movie', 7.1),
('Bowery at Midnight', 1942, 'Horror', '62 min', 'United States', 'Approved', 'Movie', 5.2),
('Dead Bang', 1989, 'Action', '102 min', 'United States', 'R', 'Movie', 6.1),
('Ride a Wild Pony', 1975, 'Drama', '91 min', 'Australia', 'G', 'Movie', 4.7),
('Mr. Majestyk', 1974, 'Action', '103 min', 'United States', 'PG', 'Movie', 6.7),
('Cinema Classics', 2021, 'Documentary', '45 min', 'Croatia', 'PG', 'TV Show', 8.0);

INSERT INTO images (filename, title, alt_text) VALUES
('At_Close_Range_poster.jpg', 'At Close Range', 'Poster filma At Close Range'),
('Diner-movie-poster-1982.jpg', 'Diner', 'Poster filma Diner'),
('Boweryatmidnight.jpg', 'Bowery at Midnight', 'Poster filma Bowery at Midnight'),
('Dead_bang_poster.jpg', 'Dead Bang', 'Poster filma Dead Bang'),
('ride a wild pony poster.jpg', 'Ride a Wild Pony', 'Poster filma Ride a Wild Pony'),
('Mr_Majestyk_movie_poster.jpg', 'Mr. Majestyk', 'Poster filma Mr. Majestyk');
