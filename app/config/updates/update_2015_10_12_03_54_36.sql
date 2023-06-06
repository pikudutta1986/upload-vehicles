
START TRANSACTION;

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_website_seo', 1, 'Yes|No::Yes', NULL, 'enum', 17, 1, NULL);

ALTER TABLE `listings` ADD COLUMN `is_featured` enum('F','T') NOT NULL DEFAULT 'F' AFTER `last_extend`;

COMMIT;