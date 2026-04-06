DROP DATABASE IF EXISTS Stock_Management_System;
CREATE DATABASE Stock_Management_System;
USE Stock_Management_System;

-- =====================================
-- 1. USERS TABLE
-- =====================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================
-- 2. SUPPLIERS TABLE
-- =====================================
CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address VARCHAR(255)
);

-- =====================================
-- 3. WAREHOUSES TABLE
-- =====================================
CREATE TABLE warehouses (
    warehouse_id INT AUTO_INCREMENT PRIMARY KEY,
    warehouse_name VARCHAR(100) NOT NULL,
    location VARCHAR(150) NOT NULL
);

-- =====================================
-- 4. CATEGORIES TABLE
-- =====================================
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255)
);

-- =====================================
-- 5. PRODUCTS TABLE
-- =====================================
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT,
    warehouse_id INT,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0),
    quantity INT NOT NULL DEFAULT 0 CHECK (quantity >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES warehouses(warehouse_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =====================================
-- 6. PURCHASES TABLE
-- =====================================
CREATE TABLE purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    supplier_id INT NOT NULL,
    user_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_cost DECIMAL(10,2) NOT NULL CHECK (unit_cost >= 0),
    total_cost DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_cost) STORED,
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =====================================
-- 7. SALES TABLE
-- =====================================
CREATE TABLE sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    unit_price DECIMAL(10,2) NOT NULL CHECK (unit_price >= 0),
    total_price DECIMAL(10,2) GENERATED ALWAYS AS (quantity * unit_price) STORED,
    sale_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =====================================
-- 8. STOCK TRANSACTIONS TABLE
-- =====================================
CREATE TABLE stock_transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    transaction_type ENUM('PURCHASE', 'SALE', 'ADJUSTMENT') NOT NULL,
    quantity INT NOT NULL,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =====================================
-- INDEXES
-- =====================================
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_warehouse ON products(warehouse_id);
CREATE INDEX idx_purchases_product ON purchases(product_id);
CREATE INDEX idx_sales_product ON sales(product_id);
CREATE INDEX idx_transactions_product ON stock_transactions(product_id);

-- =====================================
-- TRIGGERS
-- =====================================

DELIMITER //

CREATE TRIGGER after_purchase_insert
AFTER INSERT ON purchases
FOR EACH ROW
BEGIN
    UPDATE products
    SET quantity = quantity + NEW.quantity
    WHERE product_id = NEW.product_id;

    INSERT INTO stock_transactions (product_id, user_id, transaction_type, quantity, notes)
    VALUES (NEW.product_id, NEW.user_id, 'PURCHASE', NEW.quantity, 'Purchase transaction recorded');
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER before_sale_insert
BEFORE INSERT ON sales
FOR EACH ROW
BEGIN
    DECLARE current_qty INT;

    SELECT quantity INTO current_qty
    FROM products
    WHERE product_id = NEW.product_id;

    IF current_qty IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Product does not exist';
    END IF;

    IF current_qty < NEW.quantity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Not enough stock';
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER after_sale_insert
AFTER INSERT ON sales
FOR EACH ROW
BEGIN
    UPDATE products
    SET quantity = quantity - NEW.quantity
    WHERE product_id = NEW.product_id;

    INSERT INTO stock_transactions (product_id, user_id, transaction_type, quantity, notes)
    VALUES (NEW.product_id, NEW.user_id, 'SALE', NEW.quantity, 'Sale transaction recorded');
END //

DELIMITER ;

-- =====================================
-- VIEWS
-- =====================================

CREATE VIEW stock_view AS
SELECT 
    p.product_id,
    p.name AS product_name,
    c.category_name,
    w.warehouse_name,
    p.price,
    p.quantity,
    (p.price * p.quantity) AS total_stock_value
FROM products p
LEFT JOIN categories c ON p.category_id = c.category_id
LEFT JOIN warehouses w ON p.warehouse_id = w.warehouse_id;

CREATE VIEW sales_report AS
SELECT 
    p.product_id,
    p.name AS product_name,
    COALESCE(SUM(s.quantity), 0) AS total_sold,
    COALESCE(SUM(s.total_price), 0) AS total_sales_amount
FROM products p
LEFT JOIN sales s ON p.product_id = s.product_id
GROUP BY p.product_id, p.name;

CREATE VIEW purchase_report AS
SELECT 
    p.product_id,
    p.name AS product_name,
    COALESCE(SUM(pc.quantity), 0) AS total_purchased,
    COALESCE(SUM(pc.total_cost), 0) AS total_purchase_amount
FROM products p
LEFT JOIN purchases pc ON p.product_id = pc.product_id
GROUP BY p.product_id, p.name;

-- =====================================
-- SAMPLE DATA
-- =====================================

INSERT INTO users (full_name, username, password, role) VALUES
('Ahmed Ali', 'ahmed', '123456', 'admin'),
('Sara Mohamed', 'sara', '123456', 'staff'),
('Omar Hassan', 'omar', '123456', 'staff');

INSERT INTO suppliers (supplier_name, phone, email, address) VALUES
('Tech Supplies Co.', '01012345678', 'tech@suppliers.com', 'Cairo'),
('Office Tools Ltd.', '01098765432', 'office@suppliers.com', 'Alexandria'),
('Food Market', '01111222333', 'food@suppliers.com', 'Giza');

INSERT INTO warehouses (warehouse_name, location) VALUES
('Main Warehouse', 'Cairo'),
('Branch Warehouse', 'Alexandria');

INSERT INTO categories (category_name, description) VALUES
('Electronics', 'Electronic devices and accessories'),
('Office Supplies', 'Office equipment and accessories'),
('Food', 'Food products');

INSERT INTO products (name, category_id, warehouse_id, price, quantity) VALUES
('Laptop', 1, 1, 14000.00, 12),
('Mouse', 1, 1, 100.00, 50),
('Keyboard', 2, 1, 300.00, 30),
('Monitor', 1, 2, 4000.00, 10),
('Cake', 3, 2, 30.00, 31),
('Meat', 3, 2, 400.00, 40);

INSERT INTO purchases (product_id, supplier_id, user_id, quantity, unit_cost) VALUES
(1, 1, 1, 10, 13000.00),
(2, 1, 2, 5, 80.00),
(4, 2, 1, 3, 3500.00),
(5, 3, 3, 20, 20.00);

INSERT INTO sales (product_id, user_id, quantity, unit_price) VALUES
(1, 2, 3, 14000.00),
(2, 3, 2, 100.00),
(4, 1, 1, 4000.00),
(5, 2, 5, 30.00);

-- =====================================
-- CRUD OPERATIONS EXAMPLES
-- =====================================

-- INSERT example
INSERT INTO products (name, category_id, warehouse_id, price, quantity)
VALUES ('Printer', 2, 1, 2500.00, 8);

-- UPDATE example
UPDATE products
SET price = 14500.00
WHERE product_id = 1;

-- DELETE example
DELETE FROM products
WHERE product_id = 7;

-- SELECT example
SELECT * FROM products;

-- =====================================
-- REPORTS AND QUERIES
-- =====================================

-- 1. Show all products with category and warehouse
SELECT 
    p.product_id,
    p.name,
    c.category_name,
    w.warehouse_name,
    p.price,
    p.quantity
FROM products p
LEFT JOIN categories c ON p.category_id = c.category_id
LEFT JOIN warehouses w ON p.warehouse_id = w.warehouse_id;

-- 2. Show all purchases with supplier and user
SELECT 
    pc.purchase_id,
    p.name AS product_name,
    s.supplier_name,
    u.full_name AS handled_by,
    pc.quantity,
    pc.unit_cost,
    pc.total_cost,
    pc.purchase_date
FROM purchases pc
JOIN products p ON pc.product_id = p.product_id
JOIN suppliers s ON pc.supplier_id = s.supplier_id
JOIN users u ON pc.user_id = u.user_id
ORDER BY pc.purchase_date DESC;

-- 3. Show all sales with user
SELECT 
    s.sale_id,
    p.name AS product_name,
    u.full_name AS handled_by,
    s.quantity,
    s.unit_price,
    s.total_price,
    s.sale_date
FROM sales s
JOIN products p ON s.product_id = p.product_id
JOIN users u ON s.user_id = u.user_id
ORDER BY s.sale_date DESC;

-- 4. Count total products
SELECT COUNT(*) AS total_products
FROM products;

-- 5. Sum total stock value
SELECT SUM(price * quantity) AS total_stock_value
FROM products;

-- 6. Average product price
SELECT AVG(price) AS average_price
FROM products;

-- 7. Products with low stock
SELECT *
FROM products
WHERE quantity < 5
ORDER BY quantity ASC;

-- 8. Top selling products
SELECT 
    p.name,
    SUM(s.quantity) AS total_sold
FROM sales s
JOIN products p ON s.product_id = p.product_id
GROUP BY p.name
ORDER BY total_sold DESC;

-- 9. Total purchases per supplier
SELECT 
    sp.supplier_name,
    COUNT(pc.purchase_id) AS total_purchase_orders,
    SUM(pc.total_cost) AS total_amount
FROM purchases pc
JOIN suppliers sp ON pc.supplier_id = sp.supplier_id
GROUP BY sp.supplier_name
ORDER BY total_amount DESC;

-- 10. Stock transaction history
SELECT 
    st.transaction_id,
    p.name AS product_name,
    u.full_name AS user_name,
    st.transaction_type,
    st.quantity,
    st.transaction_date,
    st.notes
FROM stock_transactions st
JOIN products p ON st.product_id = p.product_id
JOIN users u ON st.user_id = u.user_id
ORDER BY st.transaction_date DESC;