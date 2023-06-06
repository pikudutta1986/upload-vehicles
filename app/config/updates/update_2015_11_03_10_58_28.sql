
START TRANSACTION;

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "infoOwnerCarExtraTitle");
UPDATE `multi_lang` SET `content` = 'Set vehicle extras' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "infoOwnerCarExtraBody");
UPDATE `multi_lang` SET `content` = 'Assign extra items below to your vehicle.' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

COMMIT;