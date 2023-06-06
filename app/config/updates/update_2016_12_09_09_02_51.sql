
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblCarCreatedModified', 'backend', 'Label / Car created or modified', 'script', '2016-12-09 09:02:24');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Car created or modified', 'script');

COMMIT;