-- Create missing tables for mneylink

-- Create buyer_reports table
CREATE TABLE IF NOT EXISTS `buyer_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `traffic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_traffic_id` (`traffic_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create member_reports table
CREATE TABLE IF NOT EXISTS `member_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `traffic_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_traffic_id` (`traffic_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Add device column to traffics table if not exists
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE table_name = 'traffics'
    AND table_schema = DATABASE()
    AND column_name = 'device'
  ) > 0,
  "SELECT 1",
  "ALTER TABLE `traffics` ADD `device` tinyint(4) NOT NULL DEFAULT 0"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Verify tables and columns
SELECT 'Tables created successfully' as message;
SHOW TABLES LIKE '%_reports';
SHOW COLUMNS FROM `traffics` LIKE 'device';

