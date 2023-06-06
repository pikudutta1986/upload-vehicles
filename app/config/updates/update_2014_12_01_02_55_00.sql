
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'contact_status_ARRAY_9908', 'arrays', 'contact_status_ARRAY_9908', 'script', '2014-12-01 02:49:03');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha cannot be empty.', 'script');

INSERT INTO `fields` VALUES (NULL, 'contact_status_ARRAY_9909', 'arrays', 'contact_status_ARRAY_9909', 'script', '2014-12-01 02:49:24');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha is not correct.', 'script');

COMMIT;