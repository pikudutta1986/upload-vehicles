
START TRANSACTION;

INSERT INTO `fields` VALUES (NULL, 'infoInstallSEODesc', 'backend', 'Infobox / SEO description for 5 pages', 'script', '2016-11-21 08:52:29');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'For search engines it is important that your web pages have Meta tags. These Meta tags refer to five pages - Home page, Login page, Register page, Search page, and Compare page. They not only give relevant information to the search engines, but are usually used as snippets when the page appears in a search engine results page (as a result of a conducted search). So, use them to define before searchers that use Google, Yahoo or Bing what is this page for.', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblHomePage', 'backend', 'Labe / Home page', 'script', '2016-11-21 08:53:21');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Home page', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblLoginPage', 'backend', 'Labe / Login page', 'script', '2016-11-21 08:53:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Login page', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblRegisterPage', 'backend', 'Labe / Register page', 'script', '2016-11-21 08:53:57');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Register page', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblSearchPage', 'backend', 'Labe / Search page', 'script', '2016-11-21 08:54:15');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Search page', 'script');

INSERT INTO `fields` VALUES (NULL, 'lblComparePage', 'backend', 'Labe / Compare page', 'script', '2016-11-21 08:54:39');

SET @id := (SELECT LAST_INSERT_ID());

INSERT INTO `multi_lang` VALUES (NULL, @id, 'pjField', '::LOCALE::', 'title', 'Compare page', 'script');

COMMIT;