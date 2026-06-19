-- =============================================================
--  Company - E-Commerce Database Schema
--  Engine: MySQL / MariaDB
--
--  Usage:
--    mysql -u root -p < company_shop.sql
--
--  Default admin account (change the password after first login):
--    username: admin
--    password: admin123
-- =============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `company_shop`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `company_shop`;

-- -------------------------------------------------------------
--  Admins
-- -------------------------------------------------------------
CREATE TABLE `tb_admin` (
  `admin_id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `admin_name`    VARCHAR(50)  NOT NULL,
  `username`      VARCHAR(50)  NOT NULL,
  `password`      VARCHAR(255) NOT NULL,
  `admin_telp`    VARCHAR(20)  NOT NULL DEFAULT '',
  `admin_email`   VARCHAR(50)  NOT NULL DEFAULT '',
  `admin_address` TEXT         NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
--  Categories
-- -------------------------------------------------------------
CREATE TABLE `tb_category` (
  `category_id`   INT(11)     NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(25) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
--  Products
-- -------------------------------------------------------------
CREATE TABLE `tb_product` (
  `product_id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `category_id`         INT(11)      NOT NULL,
  `product_name`        VARCHAR(100) NOT NULL,
  `product_price`       INT(11)      NOT NULL,
  `product_description` TEXT         NOT NULL,
  `product_image`       VARCHAR(100) NOT NULL,
  `product_status`      TINYINT(1)   NOT NULL DEFAULT 1,
  `date_created`        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  KEY `idx_product_category` (`category_id`),
  CONSTRAINT `fk_product_category`
    FOREIGN KEY (`category_id`) REFERENCES `tb_category` (`category_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
--  Cart items (session/user cart persistence)
-- -------------------------------------------------------------
CREATE TABLE `tb_cart_items` (
  `cart_item_id` INT(11)   NOT NULL AUTO_INCREMENT,
  `user_id`      INT(11)   NOT NULL,
  `product_id`   INT(11)   NOT NULL,
  `quantity`     INT(11)   NOT NULL DEFAULT 1,
  `date_added`   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_item_id`),
  KEY `idx_cart_user_id` (`user_id`),
  KEY `idx_cart_product_id` (`product_id`),
  CONSTRAINT `fk_cart_product`
    FOREIGN KEY (`product_id`) REFERENCES `tb_product` (`product_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------------
--  Seed data
-- -------------------------------------------------------------

-- Default admin. Password is "admin123" hashed with bcrypt (password_hash).
INSERT INTO `tb_admin` (`admin_name`, `username`, `password`, `admin_telp`, `admin_email`, `admin_address`)
VALUES ('Administrator', 'admin', '$2y$12$vIN7PQn8LnajlKZPcCxjvuLCOuK4giV1W3aESpZ23tZ/K5CgJrBhu', '6281234567890', 'admin@company.test', '');

-- Example categories.
INSERT INTO `tb_category` (`category_name`) VALUES
('Electronics'),
('Accessories'),
('Fashion');
