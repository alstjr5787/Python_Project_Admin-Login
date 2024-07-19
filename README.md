# Python_Project_Admin-Login Sql

![asdasd](https://github.com/user-attachments/assets/d43792bd-c382-4520-a7b8-833303ff6e1f)


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `License` (
  `code` varchar(16) NOT NULL,
  `code_date` varchar(20) NOT NULL,
  `used` tinyint(1) DEFAULT '0',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `expired_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `License` (`code`, `code_date`, `used`, `created_date`, `expired_date`) VALUES
('ZlzoHAcAlgfRvfbY', '30일', 1, '2024-07-19 03:07:27', '2024-08-18'),
('ws7ipP0VfalHcBWD', '7일', 1, '2024-07-19 02:51:05', '2024-07-26');

DELIMITER $$
CREATE TRIGGER `update_expired_date` BEFORE UPDATE ON `License` FOR EACH ROW BEGIN
    DECLARE days INT;

    SET days = CAST(SUBSTRING_INDEX(NEW.code_date, '일', 1) AS UNSIGNED);

    IF NEW.used = 1 AND OLD.used = 0 THEN
        SET NEW.expired_date = DATE_ADD(NOW(), INTERVAL days DAY);
    END IF;
END
$$
DELIMITER ;

CREATE TABLE `License_log` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `login_time` datetime NOT NULL,
  `ip_address` varchar(45) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `License_log` (`id`, `user_id`, `username`, `login_time`, `ip_address`) VALUES
(6, 22, 'test', '2024-07-19 12:16:30', '123.38.20.12');

CREATE TABLE `License_user` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `use_code` varchar(16) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `License_user` (`id`, `username`, `password`, `use_code`, `last_login`, `ip_address`) VALUES
(22, 'test', '$2y$10$erE6irRGxzF0zBnOXt4npucfz.lN54TpG1LkbrH2z3.RBDHUhgoPm', 'ZlzoHAcAlgfRvfbY', NULL, NULL);

ALTER TABLE `License`
  ADD PRIMARY KEY (`code`);

ALTER TABLE `License_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `License_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `use_code` (`use_code`);

ALTER TABLE `License_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `License_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

COMMIT;
