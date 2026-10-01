CREATE DATABASE IF NOT EXISTS blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE blog;

CREATE TABLE IF NOT EXISTS categories (
    id INT auto_increment PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS posts (
    id INT auto_increment PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    text TEXT NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS post_categories (
    post_id INT,
    category_id INT,
    PRIMARY KEY (post_id, category_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS post_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    hash VARCHAR(64) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_view (post_id, hash),
    INDEX idx_post_id (post_id)
);

CREATE TABLE IF NOT EXISTS post_views_total (
    post_id INT PRIMARY KEY,
    value INT DEFAULT 0,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

DELIMITER //

CREATE TRIGGER trigger_update_views_total
    AFTER INSERT ON post_views
    FOR EACH ROW
BEGIN
    INSERT INTO post_views_total (post_id, value)
    VALUES (NEW.post_id, 1)
        ON DUPLICATE KEY UPDATE value = value + 1;
END//

DELIMITER ;

CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT,
    tag_id INT,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);