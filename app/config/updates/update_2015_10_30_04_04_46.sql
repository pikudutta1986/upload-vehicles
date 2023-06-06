
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_non_existing_car', 'frontend', 'Label / Car with such ID does not exist.', 'script', '2015-10-30 04:03:34');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Car with such ID does not exist.', 'script');

COMMIT;