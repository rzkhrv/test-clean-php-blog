CREATE TABLE categories (
    id INT auto_increment PRIMARY KEY,
    name VARCHAR(255) NOT NULL ,
    description VARCHAR(255)
)

CREATE TABLE posts (
    id INT auto_increment PRIMARY KEY,
    name VARCHAR(255) NOT NULL ,
    description VARCHAR(255),
    text TEXT NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE post_categories (
    post_id INT,
    category_id INT,
    PRIMARY KEY (post_id, category_id)
)

CREATE TABLE post_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    hash VARCHAR(64) NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_view (post_id, hash),
    INDEX idx_post_id (post_id)
)

