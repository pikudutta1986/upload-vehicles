
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'pj_number_validation', 'backend', 'Labe / Please enter a valid number.', 'script', '2016-11-25 07:54:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Please enter a valid number.', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblDetails', 'backend', 'Labe / Details', 'script', '2016-11-25 08:43:19');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Details', 'script');

COMMIT;