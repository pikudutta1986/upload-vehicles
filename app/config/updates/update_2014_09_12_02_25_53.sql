
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_compare_list_empty', 'frontend', 'Label / The compare list is empty.', 'script', '2014-09-12 02:22:10');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The compare list is empty.', 'script');

COMMIT;