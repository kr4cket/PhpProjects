ALTER TABLE themes CHANGE theme_id id INT;
ALTER TABLE user_reviews CHANGE message_type theme_id INT, 
CHANGE user_phoneNumber user_phone_number VARCHAR(255);
ALTER TABLE user_reviews ADD id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE user_reviews ADD FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE CASCADE;