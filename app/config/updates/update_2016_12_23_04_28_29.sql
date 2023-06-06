
START TRANSACTION;

INSERT INTO `notifications` (`user_id`) VALUES (1);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjNotification', '::LOCALE::', 'new_account_subject', 'New vendor account', 'data');
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjNotification', '::LOCALE::', 'new_account_message', 'New account has been created\r\n\r\n{Email} - email\r\n{Name} - full name', 'data');
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjNotification', '::LOCALE::', 'predefined_subject', '{RefID}', 'data');
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjNotification', '::LOCALE::', 'predefined_message', 'Send more details about {RefID}', 'data');
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjNotification', '::LOCALE::', 'active_account_subject', 'Account confirmed', 'data');
INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjNotification', '::LOCALE::', 'active_account_message', 'Dear {Name},\r\n\r\nyour account has been confirmed\r\n\r\nEmail: {Email}\r\nPassword: {Password}\r\n\r\nRegards,\r\nWebmaster', 'data');

COMMIT;