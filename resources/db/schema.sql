
CREATE DATABASE IF NOT EXISTS slim_skel;

USE slim_skel;

CREATE TABLE IF NOT EXISTS product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    INDEX(category_id)
);

CREATE TABLE IF NOT EXISTS category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS tag (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS product_tag (
    product_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (product_id, tag_id),
    INDEX(tag_id)
);

INSERT INTO category (name) VALUES
    (1, 'Electronics'),
    (2, 'Clothing'),
    (3, 'Home Appliances'),
    (4, 'Books'),
    (5, 'Toys');

INSERT INTO tag (name) VALUES
    (1, 'New'),
    (2, 'Sale'),
    (3, 'Popular'),
    (4, 'Featured');

INSERT INTO product (name) VALUES
    (1, 'Smartphone', 200.00, 1),
    (2, 'Laptop', 900.00, 1),
    (3, 'T-shirt', 10.00, 2),
    (4, 'Refrigerator', 200.00, 3);

INSERT INTO product_tag (product_id, tag_id) VALUES
    (1, 1),
    (1, 3),
    (2, 1),
    (3, 2),
    (4, 4);