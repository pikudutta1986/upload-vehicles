
START TRANSACTION;

DROP TABLE IF EXISTS `passwords`;
CREATE TABLE IF NOT EXISTS `passwords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(1, 'o_week_start', 1, '0|1|2|3|4|5|6::1', 'Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday', 'enum', 4, 0, NULL);

INSERT INTO `fields` VALUES (NULL, 'lblNoAccessToFeed', 'backend', 'Label / No access to feed.', 'script', '2015-08-06 10:04:04');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'No access to feed.', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoExportCarsTitle', 'backend', 'Infobox / Export cars', 'script', '2015-08-06 10:06:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Export cars', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoExportCarsDesc', 'backend', 'Infobox / Export cars', 'script', '2015-08-06 10:07:18');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You can export cars'' details in different formats. You can either download a file with cars'' details or use a link for a feed which load all the cars'' details.', 'script');

INSERT INTO `fields` VALUES (NULL, 'export_formats_ARRAY_csv', 'arrays', 'export_formats_ARRAY_csv', 'script', '2015-08-06 10:07:54');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'CSV', 'script');

INSERT INTO `fields` VALUES (NULL, 'export_formats_ARRAY_xml', 'arrays', 'export_formats_ARRAY_xml', 'script', '2015-08-06 10:08:17');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'XML', 'script');

INSERT INTO `fields` VALUES (NULL, 'export_types_ARRAY_file', 'arrays', 'export_types_ARRAY_file', 'script', '2015-08-06 10:09:16');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'File', 'script');

INSERT INTO `fields` VALUES (NULL, 'export_types_ARRAY_feed', 'arrays', 'export_types_ARRAY_feed', 'script', '2015-08-06 10:09:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Feed', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblFormat', 'backend', 'Labe / Format', 'script', '2015-08-06 10:10:01');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Format', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblEnterPassword', 'backend', 'Label / Enter password', 'script', '2015-08-06 10:11:05');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Enter password', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblCarCreated', 'backend', 'Label / Car created', 'script', '2015-08-06 10:11:48');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Car created', 'script');

INSERT INTO `fields` VALUES (NULL, 'made_arr_ARRAY_1', 'arrays', 'made_arr_ARRAY_1', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Today', 'script');

INSERT INTO `fields` VALUES (NULL, 'made_arr_ARRAY_2', 'arrays', 'made_arr_ARRAY_2', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Yesterday', 'script');

INSERT INTO `fields` VALUES (NULL, 'made_arr_ARRAY_3', 'arrays', 'made_arr_ARRAY_3', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This week', 'script');

INSERT INTO `fields` VALUES (NULL, 'made_arr_ARRAY_4', 'arrays', 'made_arr_ARRAY_4', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last week', 'script');

INSERT INTO `fields` VALUES (NULL, 'made_arr_ARRAY_5', 'arrays', 'made_arr_ARRAY_5', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'This month', 'script');

INSERT INTO `fields` VALUES (NULL, 'made_arr_ARRAY_6', 'arrays', 'made_arr_ARRAY_6', 'script', NULL);

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Last month', 'script');

INSERT INTO `fields` VALUES (NULL, 'btnGetFeedURL', 'backend', 'Button / Get Feed URL', 'script', '2015-08-06 10:14:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Get Feed URL', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoCarsFeedTitle', 'backend', 'Infobox / Cars feed URL', 'script', '2015-08-06 10:15:25');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Cars feed URL', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoCarsFeedDesc', 'backend', 'Infobox / Cars feed URL', 'script', '2015-08-06 10:16:34');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Use the URL below to have access to all cars. Please, note that if you change the password the URL will change too as password is used in the URL itself so no one else can open it.', 'script');

INSERT INTO `fields` VALUES (NULL, 'btnAddUser', 'backend', 'Button / + Add user', 'script', '2015-08-13 04:51:59');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '+ Add user', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoUsersTitle', 'backend', 'Infobox / List of users', 'script', '2015-08-13 04:52:33');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'List of users', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoUsersDesc', 'backend', 'Infobox / List of users', 'script', '2015-08-13 04:53:16');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You can see below the list of users. If you want to add new user, click on the button "+ Add user".', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoCarsTitle', 'backend', 'Infobox / List of cars', 'script', '2015-08-13 04:57:26');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'List of cars', 'script');

INSERT INTO `fields` VALUES (NULL, 'infoCarsDesc', 'backend', 'Infobox / List of cars', 'script', '2015-08-13 04:58:07');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'You can see below the list of cars. If you want to add new car, click on the button "+ Add car".', 'script');

INSERT INTO `fields` VALUES (NULL, 'btnAddCar', 'backend', 'Button / + Add car', 'script', '2015-08-13 04:58:56');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', '+ Add car', 'script');

COMMIT;