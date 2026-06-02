CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

INSERT INTO users (name, email, password, role_id) VALUES ('Admin', 'admin@hz.nl', '$2y$10$msmuywebfnQ6UfpEqOgzXuw5DE2GkiR21gF.rrFKrAWHS.oWczJ1C', 1);
