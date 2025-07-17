-- Create databases for QuickCart microservices
CREATE DATABASE IF NOT EXISTS quickcart_products;
CREATE DATABASE IF NOT EXISTS quickcart_orders;

-- Create users and grant permissions
CREATE USER IF NOT EXISTS 'quickcart'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON quickcart_products.* TO 'quickcart'@'%';
GRANT ALL PRIVILEGES ON quickcart_orders.* TO 'quickcart'@'%';

-- Flush privileges
FLUSH PRIVILEGES;

-- Use the products database
USE quickcart_products;

-- Show databases
SHOW DATABASES;
