CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    role VARCHAR(20),
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL,
    num_login_attempts INT UNSIGNED DEFAULT 0,
    last_login_attempt DATETIME DEFAULT NULL
);
