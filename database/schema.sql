CREATE DATABASE IF NOT EXISTS `flowercafe_db`;
USE `flowercafe_db`;

-- --------------------------------------------------------
-- Table structure for `customer_details`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer_details` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('customer','staff','admin') DEFAULT 'customer',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `staff_details`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `staff_details` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `date_joined` date DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_staff_user` FOREIGN KEY (`user_id`) REFERENCES `customer_details` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `menu_details`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `menu_details` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for `order_details`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_details` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '1',
  `subtotal` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `order_time` datetime DEFAULT CURRENT_TIMESTAMP,
  KEY `order_id` (`order_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `customer_details` (`user_id`),
  CONSTRAINT `fk_order_item` FOREIGN KEY (`item_id`) REFERENCES `menu_details` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Sample Admin Account (Password: admin123)
-- Note: Replace the hash below with an actual password_hash() output if you modify it.
-- --------------------------------------------------------
INSERT INTO `customer_details` (`name`, `email`, `password`, `phone`, `role`) VALUES
('Administrator', 'admin@flowercafe.com', '$2y$10$39koOKeCq8QlEaqgkNo5He1cpiZnW9P.D.vB661srzg59VisbkKI.', '0123456789', 'admin');
