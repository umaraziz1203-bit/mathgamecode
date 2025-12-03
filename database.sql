-- Le Math Game Database Schema
-- Run this file in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS le_math_game;
USE le_math_game;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Logic puzzles table
CREATE TABLE IF NOT EXISTS logic_puzzles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    puzzle_number INT NOT NULL,
    question TEXT NOT NULL,
    answer VARCHAR(255) NOT NULL,
    hint TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Mathematics puzzles table
CREATE TABLE IF NOT EXISTS mathematics_puzzles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    puzzle_number INT NOT NULL,
    question TEXT NOT NULL,
    answer INT NOT NULL,
    options TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Memory game animals table
CREATE TABLE IF NOT EXISTS memory_animals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level INT NOT NULL,
    animal_name VARCHAR(50) NOT NULL,
    animal_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Game progress table
CREATE TABLE IF NOT EXISTS game_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_type VARCHAR(50) NOT NULL,
    level INT DEFAULT 1,
    completed BOOLEAN DEFAULT FALSE,
    score INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample users
INSERT INTO users (username, email, password) VALUES
('admin', 'admin@lemathgame.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'), -- password: password
('testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- Insert 10 logic puzzles
INSERT INTO logic_puzzles (puzzle_number, question, answer, hint) VALUES
(1, 'If all roses are flowers and some flowers are red, can we conclude that all roses are red?', 'No', 'Think about the logical relationship'),
(2, 'A man has 3 sons. Each son has 2 sisters. How many children does the man have?', '5', 'Count all children including daughters'),
(3, 'If today is Monday, what day will it be in 100 days?', 'Wednesday', 'Calculate: 100 mod 7 = 2 days ahead'),
(4, 'You have 8 balls. One is heavier. Using a balance scale, what is the minimum number of weighings to find the heavy ball?', '2', 'Divide and conquer strategy'),
(5, 'A clock shows 3:15. What is the angle between the hour and minute hands?', '7.5', 'Hour hand moves 0.5 degrees per minute'),
(6, 'If 5 cats catch 5 mice in 5 minutes, how long will it take 100 cats to catch 100 mice?', '5', 'Each cat catches 1 mouse in 5 minutes'),
(7, 'A train leaves Station A at 60 mph. Another leaves Station B at 40 mph. They are 200 miles apart. When do they meet?', '2 hours', 'Relative speed is 100 mph'),
(8, 'How many squares are on a chessboard?', '204', 'Count all sizes: 1x1, 2x2, 3x3, etc.'),
(9, 'If you flip a coin 3 times, what is the probability of getting exactly 2 heads?', '3/8', 'Use combinations: C(3,2) = 3, total outcomes = 8'),
(10, 'A number is increased by 20% and then decreased by 20%. What is the net change?', '-4%', 'Calculate: 1.2 * 0.8 = 0.96');

-- Insert 10 mathematics puzzles
INSERT INTO mathematics_puzzles (puzzle_number, question, answer, options) VALUES
(1, 'What is 15 + 27?', 42, '40,41,42,43'),
(2, 'What is 8 × 6?', 48, '46,47,48,49'),
(3, 'What is 144 ÷ 12?', 12, '10,11,12,13'),
(4, 'What is 25 - 13?', 12, '10,11,12,13'),
(5, 'What is 7²?', 49, '47,48,49,50'),
(6, 'What is √64?', 8, '6,7,8,9'),
(7, 'What is 3³?', 27, '25,26,27,28'),
(8, 'What is 18 + 24?', 42, '40,41,42,43'),
(9, 'What is 56 ÷ 7?', 8, '6,7,8,9'),
(10, 'What is 9 × 5?', 45, '43,44,45,46');

-- Insert 10 memory game animals (10 levels)
INSERT INTO memory_animals (level, animal_name, animal_image) VALUES
(1, 'Lion', '🦁'),
(2, 'Tiger', '🐯'),
(3, 'Elephant', '🐘'),
(4, 'Giraffe', '🦒'),
(5, 'Monkey', '🐵'),
(6, 'Panda', '🐼'),
(7, 'Zebra', '🦓'),
(8, 'Bear', '🐻'),
(9, 'Fox', '🦊'),
(10, 'Wolf', '🐺');

