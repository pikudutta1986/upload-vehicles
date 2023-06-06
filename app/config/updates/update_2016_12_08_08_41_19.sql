
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblVendorAccountConfirmation', 'backend', 'Labe / Vendor accounts confirmation', 'script', '2016-12-08 08:15:13');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Vendor accounts confirmation', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblVendorAccountConfirmationTip', 'backend', 'Labe / Vendor accounts confirmation', 'script', '2016-12-08 08:16:33');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This email message will be sent to vendors when their accounts is confirmed manually by administrator. There are 3 available tokens Email, Password, and Full Name.', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblActiveAccountTokens', 'backend', 'Label / Register tokens', 'script', '2016-12-08 08:25:07');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Available tokens: \r\n\r\n{Email} - email \r\n{Password} - password\r\n{Name} - full name', 'script');

COMMIT;