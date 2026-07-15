-- ============================================================
  -- EstateFlow — Fresh-install database schema
  -- Run ONCE in phpMyAdmin → Import on an EMPTY database.
  -- Creates all tables needed by the website.
  -- ============================================================

  SET FOREIGN_KEY_CHECKS = 0;
  SET NAMES utf8mb4;

  -- --------- USERS (buyers, sellers, agents, tenants) ----------
  CREATE TABLE IF NOT EXISTS `users` (
     `id`       VARCHAR(20)  NOT NULL,
     `name`     VARCHAR(100) NOT NULL,
     `number`   VARCHAR(20)  NOT NULL,
     `email`    VARCHAR(150) NOT NULL,
     `password` VARCHAR(255) NOT NULL,
     `date`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     UNIQUE KEY `email` (`email`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- --------- ADMINS (backoffice panel users) -------------------
  CREATE TABLE IF NOT EXISTS `admins` (
     `id`       VARCHAR(20)  NOT NULL,
     `name`     VARCHAR(50)  NOT NULL,
     `password` VARCHAR(255) NOT NULL,
     PRIMARY KEY (`id`),
     UNIQUE KEY `name` (`name`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- --------- PROPERTY (listings) -------------------------------
  CREATE TABLE IF NOT EXISTS `property` (
     `id`              VARCHAR(20)  NOT NULL,
     `user_id`         VARCHAR(20)  NOT NULL,
     `property_name`   VARCHAR(150) NOT NULL,
     `address`         VARCHAR(255) NOT NULL,
     `price`           BIGINT       NOT NULL,
     `type`            VARCHAR(30)  NOT NULL,
     `offer`           VARCHAR(30)  NOT NULL,
     `status`          VARCHAR(30)  NOT NULL,
     `furnished`       VARCHAR(30)  NOT NULL,
     `bhk`             VARCHAR(10)  NOT NULL,
     `deposite`        BIGINT       NOT NULL DEFAULT 0,
     `bedroom`         INT          NOT NULL DEFAULT 0,
     `bathroom`        INT          NOT NULL DEFAULT 0,
     `balcony`         INT          NOT NULL DEFAULT 0,
     `carpet`          INT          NOT NULL DEFAULT 0,
     `age`             VARCHAR(30)  NOT NULL,
     `total_floors`    INT          NOT NULL DEFAULT 0,
     `room_floor`      INT          NOT NULL DEFAULT 0,
     `loan`            VARCHAR(10)  NOT NULL,
     `lift`            VARCHAR(10)  NOT NULL DEFAULT 'no',
     `security_guard`  VARCHAR(10)  NOT NULL DEFAULT 'no',
     `play_ground`     VARCHAR(10)  NOT NULL DEFAULT 'no',
     `garden`          VARCHAR(10)  NOT NULL DEFAULT 'no',
     `water_supply`    VARCHAR(10)  NOT NULL DEFAULT 'no',
     `power_backup`    VARCHAR(10)  NOT NULL DEFAULT 'no',
     `parking_area`    VARCHAR(10)  NOT NULL DEFAULT 'no',
     `gym`             VARCHAR(10)  NOT NULL DEFAULT 'no',
     `shopping_mall`   VARCHAR(10)  NOT NULL DEFAULT 'no',
     `hospital`        VARCHAR(10)  NOT NULL DEFAULT 'no',
     `school`          VARCHAR(10)  NOT NULL DEFAULT 'no',
     `market_area`     VARCHAR(10)  NOT NULL DEFAULT 'no',
     `image_01`        VARCHAR(255) DEFAULT NULL,
     `image_02`        VARCHAR(255) DEFAULT NULL,
     `image_03`        VARCHAR(255) DEFAULT NULL,
     `image_04`        VARCHAR(255) DEFAULT NULL,
     `image_05`        VARCHAR(255) DEFAULT NULL,
     `description`     TEXT,
     `date`            TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     KEY `user_id` (`user_id`),
     KEY `offer`   (`offer`),
     KEY `type`    (`type`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- --------- SAVED (favourites) --------------------------------
  CREATE TABLE IF NOT EXISTS `saved` (
     `id`          VARCHAR(20) NOT NULL,
     `property_id` VARCHAR(20) NOT NULL,
     `user_id`     VARCHAR(20) NOT NULL,
     PRIMARY KEY (`id`),
     KEY `property_id` (`property_id`),
     KEY `user_id`     (`user_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- --------- REQUESTS (enquiry messages) -----------------------
  CREATE TABLE IF NOT EXISTS `requests` (
     `id`          VARCHAR(20) NOT NULL,
     `property_id` VARCHAR(20) NOT NULL,
     `sender`      VARCHAR(20) NOT NULL,
     `receiver`    VARCHAR(20) NOT NULL,
     `date`        TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY (`id`),
     KEY `property_id` (`property_id`),
     KEY `sender`      (`sender`),
     KEY `receiver`    (`receiver`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  SET FOREIGN_KEY_CHECKS = 1;
  -- ============================================================
  -- Done. Now open https://estateflow.it.com/project_realstate/admin/add_user.php
  -- to create the first admin account.
  -- ============================================================
  