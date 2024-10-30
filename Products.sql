CREATE TABLE Products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    quantity_in_stock INT DEFAULT 0
);

INSERT INTO Products (product_name, description, price, quantity_in_stock) VALUES
('Laptop', '15-inch screen, 8GB RAM, 256GB SSD', 799.99, 50),
('Smartphone', '6.5-inch display, 128GB storage, 12MP camera', 599.99, 100),
('Headphones', 'Wireless, noise-canceling over-ear headphones', 149.99, 200),
('Coffee Maker', '12-cup programmable coffee maker', 89.99, 80),
('Electric Toothbrush', 'Rechargeable, with 5 brushing modes', 49.99, 150),
('Gaming Console', 'Latest generation console with 1TB storage', 499.99, 30),
('Smartwatch', 'Fitness tracking, GPS, heart rate monitor', 199.99, 75),
('Desk Lamp', 'LED desk lamp with adjustable brightness', 29.99, 120),
('Bluetooth Speaker', 'Portable speaker with 10-hour battery life', 69.99, 90),
('Backpack', 'Water-resistant backpack with multiple compartments', 39.99, 200);
