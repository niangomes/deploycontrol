-- Criação da tabela de usuários
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0
);

-- Criação da tabela de status de deploy
CREATE TABLE deploy_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project VARCHAR(255) NOT NULL,
    status VARCHAR(255) NOT NULL,
    user_id INT,
    last_user INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (last_user) REFERENCES users(id)
);

-- Criação da tabela de audit log
CREATE TABLE audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    project VARCHAR(255),
    datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);