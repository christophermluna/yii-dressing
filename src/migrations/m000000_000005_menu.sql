CREATE TABLE IF NOT EXISTS `site_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `url_params` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `access_role` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_menu_item_menu_item1` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `site_menu` (`id`, `parent_id`, `label`, `icon`, `url`, `url_params`, `target`, `access_role`, `sort_order`, `enabled`, `created`, `deleted`) VALUES
(1, 0, 'Main', '', '', '', '', NULL, 0, 1, NOW(), NULL),
(2, 0, 'User', '', '', '', '', '@', 0, 1, NOW(), NULL),
(3, 0, 'Admin', '', '', '', '', 'admin', 0, 1, NOW(), NULL),
(4, 3, 'Manage', '', '', '', '', 'admin', 0, 1, NOW(), NULL),
(5, 3, 'Settings', '', '', '', '', 'admin', 1, 1, NOW(), NULL),
(6, 3, 'Logs', '', '', '', '', 'admin', 2, 1, NOW(), NULL),
(7, 3, 'Tools', '', '', '', '', 'admin', 3, 1, NOW(), NULL),
(8, 4, 'Users', '', '/user/index', '', '', 'admin', 0, 1, NOW(), NULL),
(9, 5, 'Settings', '', '/setting/index', '', '', 'admin', 0, 1, NOW(), NULL),
(10, 5, 'Menus', '', '/menu/index', '', '', 'admin', 0, 1, NOW(), NULL),
(11, 5, 'Roles', '', '/role/index', '', '', 'admin', 0, 1, NOW(), NULL),
(12, 5, 'Email Templates', '', '/emailTemplate/index', '', '', 'admin', 0, 1, NOW(), NULL),
(13, 6, 'Audits', '', '/audit/index', '', '', 'admin', 0, 1, NOW(), NULL),
(14, 6, 'Audit Trails', '', '/auditTrail/index', '', '', 'admin', 0, 1, NOW(), NULL),
(15, 6, 'Email Spools', '', '/emailSpool/index', '', '', 'admin', 0, 1, NOW(), NULL),
(16, 6, 'Errors', '', '/error/index', '', '', 'admin', 0, 1, NOW(), NULL),
(17, 6, 'Contact Us', '', '/contactUs/index', '', '', 'admin', 0, 1, NOW(), NULL),
(18, 7, 'Clear Cache', '', '/tool/clearCache', 'returnUrl={returnUrl}', '', 'admin', 0, 1, NOW(), NULL),
(19, 7, 'Generate Properties', '', '/tool/generateProperties', '', '', 'admin', 0, 1, NOW(), NULL),
(20, 2, 'Account', '', '/account/index', '', '', '@', 0, 1, NOW(), NULL),
(21, 2, 'Update', '', '/account/update', '', '', '@', 1, 1, NOW(), NULL),
(22, 2, 'Password', '', '/account/password', '', '', '@', 2, 1, NOW(), NULL),
(23, 2, 'Logout', '', '/account/logout', '', '', '@', 50, 1, NOW(), NULL),
(24, 2, 'Login', '', '/account/login', '', '', '?', 4, 1, NOW(), NULL),
(25, 2, 'Signup', '', '/account/signup', '', '', '?', 5, 1, NOW(), NULL),
(26, 2, 'Recover', '', '/account/recover', '', '', '?', 6, 1, NOW(), NULL),
(27, 7, 'Generate Code', '', '/gii', '', '', 'admin', 0, 1, NOW(), NULL),
(28, 1, 'Help', '', '/site/page', 'view=help', '', NULL, 0, 1, NOW(), NULL),
(29, 2, 'Tools', '', '/tool/index', '', '', 'admin', 20, 1, NOW(), NULL);
