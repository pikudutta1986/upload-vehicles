
START TRANSACTION;

ALTER TABLE `passwords` ADD COLUMN `format` enum('csv','xml') NOT NULL DEFAULT 'csv' AFTER `password`;
ALTER TABLE `passwords` ADD COLUMN `period` enum('1','2','3','4','5','6') NOT NULL DEFAULT '1' AFTER `format`;

COMMIT;