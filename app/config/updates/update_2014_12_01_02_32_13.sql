
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'front_car_interested_in', 'frontend', 'Label / The car that client is interested in', 'script', '2014-12-01 02:16:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'The car that client is interested in:', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_label_title', 'frontend', 'Label / Title', 'script', '2014-12-01 02:17:52');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_label_year', 'frontend', 'Label / Year', 'script', '2014-12-01 02:31:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Year', 'script');

COMMIT;