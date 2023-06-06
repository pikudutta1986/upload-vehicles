
START TRANSACTION;

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "infoOwnerCarDetailTitle");
UPDATE `multi_lang` SET `content` = 'Car details' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

SET @id := (SELECT `id` FROM `fields` WHERE `key` = "infoOwnerCarDetailBody");
UPDATE `multi_lang` SET `content` = 'You can edit details information of your car on the form below.' WHERE `foreign_id` = @id AND `model` = "pjField" AND `field` = "title";

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AE01', 'arrays', 'error_titles_ARRAY_AE01', 'script', '2015-11-03 05:38:07');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra updated!', 'script');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AE01', 'arrays', 'error_bodies_ARRAY_AE01', 'script', '2015-11-03 05:39:02');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra title has been updated.', 'script');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AE03', 'arrays', 'error_titles_ARRAY_AE03', 'script', '2015-11-03 05:39:27');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra added!', 'script');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AE03', 'arrays', 'error_bodies_ARRAY_AE03', 'script', '2015-11-03 05:40:00');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra has been added into the system.', 'script');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AE04', 'arrays', 'error_titles_ARRAY_AE04', 'script', '2015-11-03 05:40:32');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra not added!', 'script');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AE04', 'arrays', 'error_bodies_ARRAY_AE04', 'script', '2015-11-03 05:41:09');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra has not been added. Please try again.', 'script');

INSERT INTO `fields` VALUES (NULL, 'error_titles_ARRAY_AE08', 'arrays', 'error_titles_ARRAY_AE08', 'script', '2015-11-03 05:41:35');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra not found!', 'script');

INSERT INTO `fields` VALUES (NULL, 'error_bodies_ARRAY_AE08', 'arrays', 'error_bodies_ARRAY_AE08', 'script', '2015-11-03 05:42:06');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Extra you are looking is missing.', 'script');

COMMIT;