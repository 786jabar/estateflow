-- EstateFlow: run this ONCE in cPanel -> phpMyAdmin -> the database -> SQL tab
-- It creates the two new tables needed by the latest features.

-- 1) Contact form inbox (for contact.php)
CREATE TABLE IF NOT EXISTS `messages` (
   `id`      VARCHAR(20)  NOT NULL,
   `name`    VARCHAR(50)  NOT NULL,
   `email`   VARCHAR(80)  NOT NULL,
   `number`  VARCHAR(20)  DEFAULT NULL,
   `message` TEXT         NOT NULL,
   `date`    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Forgot-password reset tokens (for forgot_password.php / reset_password.php)
CREATE TABLE IF NOT EXISTS `password_resets` (
   `id`         VARCHAR(20)  NOT NULL,
   `email`      VARCHAR(80)  NOT NULL,
   `token_hash` VARCHAR(64)  NOT NULL,
   `expires_at` DATETIME     NOT NULL,
   `used`       TINYINT(1)   NOT NULL DEFAULT 0,
   `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
