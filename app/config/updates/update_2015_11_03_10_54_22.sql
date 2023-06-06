
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblCharsLimitation', 'frontend', 'Label / Characters limitation', 'script', '2015-11-03 10:47:21');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Characters limitation', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblCharsLimitationDesc', 'frontend', 'Label / Characters limitation', 'script', '2015-11-03 10:48:50');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Number of characters in the description are more than 65535. Please check again.', 'script');

COMMIT;