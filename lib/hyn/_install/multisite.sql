CREATE TABLE IF NOT EXISTS `site_routes` (
  `route` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `function` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`route`),
  UNIQUE KEY `route` (`route`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL COMMENT 'Setting specific for a module',
  `value` text NOT NULL,
  PRIMARY KEY (`name`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;