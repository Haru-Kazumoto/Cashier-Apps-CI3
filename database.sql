CREATE DATABASE IF NOT EXISTS kasirdb_ci3;

USE kasirdb_ci3;

CREATE TABLE ci_sessions (
    id varchar(128) NOT NULL,
    ip_address varchar(45) NOT NULL,
    timestamp int(10) unsigned DEFAULT 0 NOT NULL,
    data blob NOT NULL,
    PRIMARY KEY (id),
    KEY ci_sessions_timestamp (timestamp)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(100) UNIQUE NOT NULL,
    fullname VARCHAR(200) NOT NULL,
    password VARCHAR(255) NOT NULL,

    is_admin BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(255) NOT NULL,
    description TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,

    category_id INT,

    code VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,

    price DECIMAL(15,2) NOT NULL DEFAULT 0,
    stock INT NOT NULL DEFAULT 0,

    unit VARCHAR(50),

    created_by INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    invoice_number VARCHAR(100) UNIQUE NOT NULL,

    total_price DECIMAL(15,2) NOT NULL DEFAULT 0,
    total_pay DECIMAL(15,2) NOT NULL DEFAULT 0,
    total_return DECIMAL(15,2) NOT NULL DEFAULT 0,

    payment_method ENUM('CASH', 'QRIS', 'TRANSFER', 'DEBIT') DEFAULT 'CASH',

    created_by INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE transaction_details (
    id INT AUTO_INCREMENT PRIMARY KEY,

    transaction_id INT NOT NULL,
    product_id INT NOT NULL,

    quantity INT NOT NULL DEFAULT 1,

    price DECIMAL(15,2) NOT NULL DEFAULT 0,
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- SEEDING DATA USER AJAH
INSERT INTO users(username, PASSWORD, fullname, is_admin, created_at, updated_at) VALUES 
(
	'super_admin',
	'$2y$10$1uAIxwYhr2CorYcyADZYFO.AwRyu.IYVRJLprAlE5kzAY7eb6qwQi', -- ini passwordnya superadmin
	'Haru Kazumoto',
	FALSE,
	NOW(),
	NOW()
),
(
	'admin_kasir',
	'$2y$10$BAnnOpYB9W2qLZNADcKFCOMjffXlUJPxmV7QcJ6P1S93x.ztZXzdi', -- ini password nya kasir
	'Ryumi Nagato',
	TRUE,
	NOW(),
	NOW()
);