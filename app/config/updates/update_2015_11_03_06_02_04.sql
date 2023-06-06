
START TRANSACTION;

ALTER TABLE `listings` MODIFY `listing_power` int(10) unsigned DEFAULT NULL;

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "infoOwnerListingAddTitle");
UPDATE `multi_lang` SET `content` = 'Add new car' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "infoOwnerListingAddBody");
UPDATE `multi_lang` SET `content` = 'Start adding a vehicle with choosing the main details about it - type, make, model. On the next step you will be able to enter additional information about the vehicle.' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

COMMIT;