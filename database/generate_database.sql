CREATE TABLE IF NOT EXISTS themes
( 
    id INT PRIMARY KEY AUTO_INCREMENT,
    theme_type  VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS user_reviews 
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    theme_id INT NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    user_surname VARCHAR(255), 
    user_phone_number VARCHAR(255) NOT NULL,
    user_message TEXT
    FOREIGN KEY (theme_id) REFERENCES themes (id) ON DELETE CASCADE
);

INSERT INTO themes ('theme_type') VALUES ('Жалоба');
INSERT INTO themes ('theme_type') VALUES ('Предложение');
INSERT INTO themes ('theme_type') VALUES ('Задать вопрос');