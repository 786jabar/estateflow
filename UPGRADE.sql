-- ============================================================
-- EstateFlow — one-time database upgrade
-- Run this ONCE in phpMyAdmin (SQL tab) on the home_db database.
-- It enlarges the password columns so the new bcrypt hashes fit.
-- Safe to re-run; existing data is preserved.
-- ============================================================

ALTER TABLE `users`  MODIFY COLUMN `password` VARCHAR(255) NOT NULL;
ALTER TABLE `admins` MODIFY COLUMN `password` VARCHAR(255) NOT NULL;

-- Optional but recommended: allow longer phone numbers (international).
ALTER TABLE `users`  MODIFY COLUMN `number` VARCHAR(20) NOT NULL;

-- Done. You should see "MySQL returned an empty result set" — that's success.
