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
  `slug` VARCHAR(100) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) DEFAULT 'therapeutic',
  `categoryLabel` VARCHAR(100) DEFAULT 'Therapeutic Care',
  `concern` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `originalPrice` DECIMAL(10, 2) DEFAULT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `rating` DECIMAL(3, 1) DEFAULT 5.0,
  `reviewCount` INT DEFAULT 0,
  `badge` VARCHAR(100) DEFAULT NULL,
  `curation` VARCHAR(255) DEFAULT NULL,
  `weight` VARCHAR(50) DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `keyBenefits` TEXT DEFAULT NULL,
  `ingredients` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_products_slug` (`slug`),
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
INSERT INTO `products` (`id`, `slug`, `name`, `category`, `categoryLabel`, `concern`, `price`, `originalPrice`, `stock`, `rating`, `reviewCount`, `badge`, `curation`, `weight`, `image_path`, `description`, `keyBenefits`, `ingredients`, `created_at`) VALUES
(1, 'anti-pigmentation-cream', 'Anti Pigmentation Cream', 'therapeutic', 'Therapeutic Care', 'pigmentation', 599.00, 749.00, 50, 4.9, 128, 'Bestseller', 'Ancient Method • Small-Batch Botanical Distillation', '50 g', 'assets/anti_pigmentation.jpg', 'A clinically potent therapeutic formulation crafted using ancient botanical alchemy for tropical and humidity-exposed skin. Prepared fresh upon booking with zero storage stabilizers to reduce hyperpigmentation and melasma patches without barrier irritation.', 'We prepare fresh as you book — zero warehouse shelf life\nFades stubborn blemishes, UV spots & melasma patches\nAncient botanical alchemy using cold-pressed herbal lipids\nFree from hydroquinone, parabens & synthetic dyes', 'Kojic Dipalmitate, Alpha Arbutin, Licorice Root Extract, Niacinamide, Cold-Pressed Jojoba Oil, Aloe Vera Leaf Juice, Vitamin E.', NOW()),
(2, 'anti-fungal-cream', 'Anti Fungal Climate Cream', 'therapeutic', 'Therapeutic Care', 'fungal', 499.00, 620.00, 45, 4.8, 94, 'Climate Shield', 'Ancient Method • Herbal Microflora Defense', '50 g', 'assets/anti_fungal.png', 'Engineered specifically to counter humidity-induced fungal irritation, sweat rashes, and chafing. Freshly prepared as you book using ancient Ayurvedic extracts like Karanja and Neem to cool inflamed, itchy skin.', 'Freshly prepared upon your booking for peak herbal potency\nRapidly alleviates sweat rash, redness & chafing\nReinforces dermal microflora in high-humidity zones\n100% breathable formulation suitable for active wear', 'Neem Seed Oil, Organic Tea Tree Leaf Extract, Karanja Oil, Turmeric Rhizome Extract, Zinc PCA, Beeswax, Calendula Infusion.', NOW()),
(3, 'anti-allergy-cream', 'Anti Allergy SOS Cream', 'therapeutic', 'Therapeutic Care', 'sensitive', 399.00, 499.00, 60, 4.9, 156, 'Barrier SOS', 'Ancient Method • Colloidal Barrier SOS', '50 g', 'assets/anti_allergy.jpg', 'An SOS therapeutic shield designed for hyper-reactive, allergic skin. Freshly compounded as you book using ancient colloidal oat distillation to soothe histamine flares, contact redness, and compromised barrier tissue.', 'Handcrafted upon booking — uncompromised therapeutic freshness\nInstant relief from allergic hives, itching & irritation\nReconstructs compromised skin lipid matrix\nSteroid-free comfort for daily preventative use', 'Colloidal Oatmeal, Centella Asiatica (Gotu Kola), Chamomile Flower Extract, Shea Butter, Evening Primrose Oil, Squalane.', NOW()),
(4, 'psoriasis-support-cream', 'Psoriasis Support Cream', 'therapeutic', 'Therapeutic Care', 'psoriasis', 599.00, 750.00, 3, 4.9, 88, 'Intensive Relief', 'Ancient Method • Wrightia Tinctoria Alchemy', '60 g', 'assets/psoriasis_cream.jpg', 'Deeply restorative lipid-replenishing emollient formulated using ancient Wrightia Tinctoria distillation. Prepared fresh as you book to soften thick, scaly plaques and relieve severe xerosis without synthetic occlusives.', 'Prepared upon booking — biologically active plant phytosterols\nSoftens tough epidermal flakes & rough patches\nSustained 24-hour barrier hydration shield\nReduces scaling, cracking, and stinging sensations', 'Mahonia Aquifolium Extract, Wrightia Tinctoria Leaf Oil, Shea Butter, Virgin Coconut Oil, Borage Seed Oil, Beeswax.', NOW()),
(5, 'migraine-relief-oil', 'Migraine & Tension Roll-on', 'therapeutic', 'Therapeutic Care', 'stress-pain', 149.00, 199.00, 100, 4.9, 312, 'Pocket Healer', 'Ancient Method • Pure Herbal Distillate', '10 ml', 'assets/migraine_oil.jpg', 'An aromatherapeutic fast-acting roll-on infused with pure therapeutic-grade wintergreen, peppermint, and lavender distillates. Hand-bottled as you book to dissolve forehead tension, sinus pressure, and headaches in minutes.', 'Freshly bottled upon booking — active volatile aromatherapeutics\nInstant cooling pressure release upon temple application\nEases stress-induced neck tension & migraine throbbing\nPortable spill-proof roll-on applicator', 'Mentha Piperita (Peppermint) Oil, Gaultheria Procumbens (Wintergreen) Oil, Lavandula Angustifolia Oil, Eucalyptus Globulus, Sweet Almond Carrier Oil.', NOW());

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
