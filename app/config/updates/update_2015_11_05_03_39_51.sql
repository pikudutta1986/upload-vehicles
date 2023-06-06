
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_clear_compare_list', 'frontend', 'Label / Clear Compare List', 'script', '2015-11-05 03:22:52');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Clear Compare List', 'script');

COMMIT;