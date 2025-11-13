-- Add missing columns to tables

-- Add device column to traffics table if not exists
ALTER TABLE `traffics` 
ADD COLUMN IF NOT EXISTS `device` tinyint(4) NOT NULL DEFAULT 0 AFTER `kind`;

-- Verify the changes
SELECT 'Column added successfully' as message;
SHOW COLUMNS FROM `traffics` LIKE 'device';

