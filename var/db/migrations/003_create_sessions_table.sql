CREATE TABLE sessions (
    id VARCHAR(128) PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
);
