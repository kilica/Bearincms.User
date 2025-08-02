INSERT INTO users (email, password, status, created_at, updated_at) 
VALUES ('admin@bearin.cms', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active', NOW(), NOW());

INSERT INTO roles (user_id, role) 
VALUES (1, 'admin');

INSERT INTO profiles (user_id, name, nickname) 
VALUES (1, 'Administrator', 'admin');
