
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'lblMakeFeatured', 'backend', 'Label / Make featured', 'script', '2015-10-12 08:26:44');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Make featured', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblFeaturedTip', 'backend', 'Label / Featured tip', 'script', '2015-10-12 08:31:04');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Featured cars go at the top of all listed cars and are marked as such. There is also a separate Featured Cars listing formed by the cars marked as "featured" that can be integrated on website page, other than the main cars listing. See Install menu for Featured Cars integration code.', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblInstallFeatured', 'backend', 'Label / Featured cars', 'script', '2015-10-12 08:39:08');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Featured cars', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblInstallPhp3Title', 'backend', 'Label / Featured cars', 'script', '2015-10-12 08:40:43');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Follow these steps to insert the Featured Cars listing on your .php web page using PHP include code:', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblInstallFeat_1', 'backend', 'Label / Step 1', 'script', '2015-10-12 08:41:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 1. Copy and paste the code below at the very top of your .php page. It should be line 1 of your .php web page.', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblInstallFeat_2', 'backend', 'Label / Step 2', 'script', '2015-10-12 08:41:58');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 2. Copy and paste the code below into your html code, where featured cars will be displayed.', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblInstallFeat_3', 'backend', 'Label / Step 3', 'script', '2015-10-12 08:42:29');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Step 3. Copy and paste the code below at the very bottom of your .php web page after all the other code.', 'script');

COMMIT;