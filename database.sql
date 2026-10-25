CREATE DATABASE IF NOT EXISTS online_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE online_shop;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  category VARCHAR(80) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_url VARCHAR(500),
  stock INT NOT NULL DEFAULT 20,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  customer_name VARCHAR(100) NOT NULL,
  address TEXT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  product_name VARCHAR(150) NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO products (name, description, category, price, image_url, stock) VALUES
('Everyday Backpack', 'Lightweight backpack for college, work, and travel.', 'Accessories', 899.00, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80', 20),
('Wireless Headphones', 'Comfortable headphones for music and online classes.', 'Electronics', 1499.00, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80', 15),
('Classic Sneakers', 'Everyday sneakers with a clean, versatile design.', 'Fashion', 1899.00, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', 12),
('Smart Watch', 'Track your daily activity and stay connected.', 'Electronics', 2299.00, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80', 10),
('Ceramic Coffee Mug', 'A simple ceramic mug for your desk or kitchen.', 'Home', 299.00, 'https://images.unsplash.com/photo-1514228742587-6b1558fcca4?auto=format&fit=crop&w=900&q=80', 30),
('Desk Lamp', 'Minimal desk lamp for study and reading.', 'Home', 799.00, 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80', 18)
ON DUPLICATE KEY UPDATE name = VALUES(name);