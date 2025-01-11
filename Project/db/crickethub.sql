CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    city VARCHAR(100),
    approval_status VARCHAR(50) DEFAULT 'pending'
);

CREATE TABLE player_registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(255),
    player_role ENUM('Batsman', 'Bowler', 'All-rounder', 'Wicketkeeper') NOT NULL,
    batting_style ENUM('Right-hand bat', 'Left-hand bat') NOT NULL,
    bowling_style ENUM('Right-arm fast', 'Left-arm fast', 'Right-arm spin','Right-arm spin', 'Left-arm spin', 'None') NOT NULL,
    experience_level ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    team_name VARCHAR(255),
    achievements TEXT,
    photo_url VARCHAR(255), -- New column for photo URL
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

CREATE TABLE match_performance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL, -- Foreign key to link with the players table
    match_id INT NOT NULL, -- Foreign key to link with the matches table
    runs_scored INT,
    balls_faced INT,
    fours INT,
    sixes INT,
    strike_rate DECIMAL(5, 2),
    wickets_taken INT,
    overs_bowled DECIMAL(3, 1),
    runs_conceded INT,
    economy_rate DECIMAL(4, 2),
    catches INT,
    stumpings INT,
    run_outs INT,
    match_date DATE,
    VARCHAR(50) -- e.g., Test, ODI, T20
);

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$abcdef1234567890abcdef1234567890abcdef1234567890');
CREATE TABLE match_details (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    team1 VARCHAR(255) NOT NULL,
    team2 VARCHAR(255) NOT NULL,
    match_date DATE NOT NULL,
    match_type ENUM('20 Over', '50 Over', '90 Over') NOT NULL,
    match_result VARCHAR(255) NOT NULL
);

CREATE TABLE trainees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    email VARCHAR(255),
    phone_number VARCHAR(20) NOT NULL,
    role ENUM('Head Coach', 'Assistant Coach', 'Batting Coach', 'Bowling Coach') NOT NULL,
    batting_style ENUM('Right-hand bat', 'Left-hand bat') NOT NULL,
    bowling_style ENUM('Right-arm fast', 'Left-arm fast', 'Right-arm spin', 'Left-arm spin', 'None') NOT NULL,
    total_matches_played INT,
    total_runs INT,
    total_wickets INT,
    photo VARCHAR(255),
    experience_level ENUM('Beginner', 'Intermediate', 'Advanced') NOT NULL,
    achievements TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE match_details (
    match_id INT AUTO_INCREMENT PRIMARY KEY,
    team1 VARCHAR(255) NOT NULL,
    team2 VARCHAR(255) NOT NULL,
    match_date DATE NOT NULL,
    match_type ENUM('20 Over', '50 Over', '90 Over') NOT NULL,
    match_result VARCHAR(255) NOT NULL,
    team1_score VARCHAR(255),
    team2_score VARCHAR(255)
);