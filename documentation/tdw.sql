-- Elimino il database se già esistente
DROP DATABASE IF EXISTS tdw; 

-- Creo il database e lo seleziono come default
CREATE DATABASE tdw;
USE tdw;

-- Creo un utente
CREATE USER IF NOT EXISTS 'tdwUser'@'localhost' IDENTIFIED BY 'tdwUserPwd';
GRANT select, insert, update, delete, execute ON tdw.* TO 'tdwUser'@'localhost';

CREATE TABLE `user` (
    user_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(25) UNIQUE NOT NULL,
    `name` VARCHAR(25) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    email_address VARCHAR(25) NOT NULL
);

CREATE TABLE `order` (
    order_code INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    total VARCHAR(50) NOT NULL,
    progress_status ENUM('placed', 'processing', 'shipped') NOT NULL,
    user_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES `user` (user_id)
);

CREATE TABLE product (
    product_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(50) UNIQUE NOT NULL,
    price FLOAT NOT NULL,
    quantity_available INTEGER NOT NULL,
    product_description TEXT NOT NULL,
    brand_code INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (brand_code) REFERENCES brand (brand_code)
);

CREATE TABLE brand (
    brand_code INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(25) UNIQUE NOT NULL,
    website VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    email_address VARCHAR(25) NOT NULL,
    address VARCHAR(50) NOT NULL
);

CREATE TABLE product_variant (
	variant_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    variant_name VARCHAR(30) UNIQUE NOT NULL,
    `description` TEXT,
    product_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (product_id) REFERENCES product (product_id)
);

CREATE TABLE category (
	category_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(20) UNIQUE NOT NULL,
    category_description TEXT NOT NULL
);

CREATE TABLE discount (
	discount_code INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `description` TEXT NULL,
    percentage SMALLINT NOT NULL,
    start_date TIMESTAMP NOT NULL,
    expiration_date TIMESTAMP NOT NULL
);

CREATE TABLE payment_method (
	payment_code INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `type` ENUM('debit', 'credit', 'paypal') NOT NULL,
    credentials VARCHAR(100) NOT NULL,
    validity TIMESTAMP NOT NULL,
    user_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES `user` (user_id)
);

CREATE TABLE product_review (
	review_code INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `text` TEXT NOT NULL,
    rating SMALLINT NOT NULL,
    user_id INTEGER UNSIGNED NOT NULL,
    product_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (user_id),
    FOREIGN KEY (product_id) REFERENCES product (product_id)
);

CREATE TABLE shipment_address (
	address_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(30) NOT NULL,
    address VARCHAR(50) NOT NULL,
    province VARCHAR(30) NOT NULL,
    country VARCHAR(30) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    user_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES `user` (user_id),
    UNIQUE(city, address, country)
);

CREATE TABLE customization (
	customization_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    personal_image BLOB NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    email_address VARCHAR(15) NOT NULL,
    about_info TEXT NOT NULL,
    personal_address VARCHAR(50) NOT NULL
);

CREATE TABLE `group` (
	group_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(20) NOT NULL,
    group_description TEXT NOT NULL
);

CREATE TABLE user_has_group (
	user_id INTEGER UNSIGNED,
    group_id INTEGER UNSIGNED,
    PRIMARY KEY(user_id, group_id),
    FOREIGN KEY (user_id) REFERENCES `user` (user_id),
    FOREIGN KEY (group_id) REFERENCES `group` (group_id)
);

CREATE TABLE service (
	service_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tag VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    script VARCHAR(255) NOT NULL,
    callback VARCHAR(255) NOT NULL,
    service_description TEXT NOT NULL
);

CREATE TABLE user_has_service (
	user_id INTEGER UNSIGNED,
    service_id INTEGER UNSIGNED,
    PRIMARY KEY(user_id, service_id),
    FOREIGN KEY (user_id) REFERENCES `user` (user_id),
    FOREIGN KEY (service_id) REFERENCES service (service_id)
);

CREATE TABLE `contains` (
	order_code INTEGER UNSIGNED,
    product_id INTEGER UNSIGNED,
    quantity SMALLINT NOT NULL,
    price FLOAT NOT NULL,
    PRIMARY KEY(order_code, product_id),
    FOREIGN KEY (order_code) REFERENCES `order` (order_code),
    FOREIGN KEY (product_id) REFERENCES product (product_id)
    
);

CREATE TABLE belongs_to (
	product_id INTEGER UNSIGNED,
    category_id INTEGER UNSIGNED,
    PRIMARY KEY(product_id, category_id),
    FOREIGN KEY (product_id) REFERENCES product (product_id),
    FOREIGN KEY (category_id) REFERENCES category (category_id)
);

CREATE TABLE discounted_by (
	product_id INTEGER UNSIGNED,
    discount_code INTEGER UNSIGNED,
    PRIMARY KEY(product_id, discount_code),
    FOREIGN KEY (product_id) REFERENCES product (product_id),
    FOREIGN KEY (discount_code) REFERENCES discount (discount_code)
);