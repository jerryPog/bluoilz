-- ============================================================================
-- Bluoilz Skincare - E-Commerce Database Schema
-- Compatible with MySQL 5.7+ / 8.0+ and MariaDB (phpMyAdmin Import Ready)
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ----------------------------------------------------------------------------
-- 1. Table: products
-- Stores skincare & therapeutic remedies
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_products_name` (`name`),
  INDEX `idx_products_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. Table: orders
-- Stores customer checkout orders & fulfillment status
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(255) NOT NULL,
  `address` TEXT NOT NULL,
  `status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
  `total` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_orders_status` (`status`),
  INDEX `idx_orders_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. Table: order_items
-- Pivot / line-items connecting orders with products
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `price` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_order_items_order_id` (`order_id`),
  INDEX `idx_order_items_product_id` (`product_id`),
  CONSTRAINT `fk_order_items_orders`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_products`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. Table: admin_users
-- Stores administrator authentication records
-- ----------------------------------------------------------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_username` (`username`),
  INDEX `idx_admin_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Optional Seed Data (Bluoilz Botanical Catalog)
-- ----------------------------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image_path`, `description`, `created_at`) VALUES
(1, 'Anti Pigmentation Cream', 599.00, 50, 'assets/anti_pigmentation.jpg', 'Clinically potent therapeutic formulation crafted using ancient botanical alchemy to fade melasma and hyperpigmentation without barrier irritation.', NOW()),
(2, 'Anti Fungal Climate Cream', 499.00, 45, 'assets/anti_fungal.png', 'Herbal microflora defense countering humidity-induced fungal irritation, sweat rashes, and chafing.', NOW()),
(3, 'Anti Allergy SOS Cream', 399.00, 60, 'assets/anti_allergy.jpg', 'Colloidal oat SOS shield providing instant comfort for reactive skin and histamine flare-ups.', NOW()),
(4, 'Psoriasis Support Cream', 599.00, 3, 'assets/psoriasis_cream.jpg', 'Deeply restorative Wrightia Tinctoria lipid emollient softening thick, flaking epidermal plaques.', NOW()),
(5, 'Migraine & Tension Roll-on Oil', 149.00, 100, 'assets/migraine_oil.jpg', 'Fast-acting pure botanical aromatherapeutic distillate easing headache tension and sinus pressure in minutes.', NOW());

-- Default Admin User:
-- Username: admin
-- Password: password
-- Generated using: password_hash('password', PASSWORD_DEFAULT)
INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());

-- ----------------------------------------------------------------------------
-- Sample Seed Data: Orders & Line Items
-- ----------------------------------------------------------------------------
INSERT INTO `orders` (`id`, `customer_name`, `address`, `status`, `total`, `created_at`) VALUES
(101, 'Aditi Sharma', 'Flat 402, Sea Breeze Apts, Bandra West, Mumbai, MH 400050', 'pending', 1198.00, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(102, 'Rahul Varma', '12/4 Indiranagar 100ft Road, Bangalore, KA 560038', 'shipped', 1098.00, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(103, 'Priya Nair', 'Panampilly Nagar, Kochi, KL 682036', 'delivered', 548.00, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(104, 'Vikram Singh', 'Pocket C, Vasant Kunj, New Delhi, DL 110070', 'processing', 149.00, DATE_SUB(NOW(), INTERVAL 4 HOUR)),
(105, 'Sneha Patel', 'Ambawadi, Ahmedabad, GJ 380015', 'cancelled', 599.00, DATE_SUB(NOW(), INTERVAL 5 DAY));

INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `price`) VALUES
(101, 1, 2, 599.00),
(102, 2, 1, 499.00),
(102, 4, 1, 599.00),
(103, 3, 1, 399.00),
(103, 5, 1, 149.00),
(104, 5, 1, 149.00),
(105, 1, 1, 599.00);

SET FOREIGN_KEY_CHECKS = 1;
