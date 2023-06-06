
START TRANSACTION;

UPDATE `options` SET `is_visible`='0' WHERE `key`='o_layout';
UPDATE `options` SET `value`='preview.php' WHERE `key`='o_listing_page';

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_fields_index', 99, 'd874fcc5fe73b90d770a544664a3775d', NULL, 'string', NULL, 0, NULL);

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_theme', 1, 'theme1|theme2|theme3|theme4|theme5|theme6|theme7|theme8|theme9|theme10::theme1', 'Theme 1|Theme 2|Theme 3|Theme 4|Theme 5|Theme 6|Theme 7|Theme 8|Theme 9|Theme 10', 'enum', 5, 0, NULL);

INSERT INTO `fields` VALUES (NULL, 'infoThemeTitle', 'backend', 'Infobox / Preview front end', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Preview front end', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoThemeDesc', 'backend', 'Infobox / Preview front end', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'There are multiple color schemes available for the front end. Click on each of the thumbnails below to preview it. Click on "Use this theme" button for the theme you want to use.', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblInstallTheme', 'backend', 'Label / Choose theme', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Choose theme', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_1', 'arrays', 'option_themes_ARRAY_1', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 1', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_2', 'arrays', 'option_themes_ARRAY_2', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 2', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_3', 'arrays', 'option_themes_ARRAY_3', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 3', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_4', 'arrays', 'option_themes_ARRAY_4', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 4', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_5', 'arrays', 'option_themes_ARRAY_5', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 5', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_6', 'arrays', 'option_themes_ARRAY_6', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 6', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_7', 'arrays', 'option_themes_ARRAY_7', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 7', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_8', 'arrays', 'option_themes_ARRAY_8', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 8', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_9', 'arrays', 'option_themes_ARRAY_9', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 9', 'script');

INSERT INTO `fields` VALUES (NULL, 'option_themes_ARRAY_10', 'arrays', 'option_themes_ARRAY_10', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Theme 10', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblCurrentlyInUse', 'backend', 'Label / Currently in use', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Currently in use', 'script');

INSERT INTO `fields` VALUES (NULL, 'btnUseThisTheme', 'backend', 'Label / Use this theme', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use this theme', 'script');


INSERT INTO `fields` VALUES (NULL, 'front_highest_to_lowest', 'frontend', 'Label / Highest to Lowest', 'script', '2015-08-11 02:52:33');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Highest to Lowest', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_lowest_to_highest', 'frontend', 'Label / Lowest to Highest', 'script', '2015-08-11 02:53:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Lowest to Highest', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_field_required', 'frontend', 'Label / This field is required.', 'script', '2015-08-11 09:36:32');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This field is required.', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_email_invalid', 'frontend', 'Label / Email is invalid.', 'script', '2015-08-11 09:37:58');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Email is invalid.', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_captcha_incorrect', 'frontend', 'Label / Captcha is incorrect.', 'script', '2015-08-11 09:52:07');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Captcha is incorrect.', 'script');

INSERT INTO `fields` VALUES (NULL, 'front_password_not_match', 'frontend', 'Label / Password does not match.', 'script', '2015-08-11 11:43:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Password does not match.', 'script');

COMMIT;