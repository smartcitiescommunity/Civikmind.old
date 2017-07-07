DROP TABLE IF EXISTS `glpi_plugin_surveyticket_questions`;

CREATE TABLE `glpi_plugin_surveyticket_questions` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `type` varchar(255) DEFAULT NULL,
   `is_start` tinyint(1) NOT NULL DEFAULT '0',
   `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_surveyticket_answers`;

CREATE TABLE `glpi_plugin_surveyticket_answers` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `items_id` int(11) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) DEFAULT NULL,
   `answertype` varchar(100) DEFAULT NULL,
   `is_yes` tinyint(1) NOT NULL DEFAULT '0',
   `is_no` tinyint(1) NOT NULL DEFAULT '0',
   `plugin_surveyticket_questions_id` int(11) NOT NULL DEFAULT '0',
   `link` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_surveyticket_surveys`;

CREATE TABLE `glpi_plugin_surveyticket_surveys` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(255) DEFAULT NULL,
   `entities_id` int(11) NOT NULL DEFAULT '0',
   `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
   `is_active` tinyint(1) NOT NULL DEFAULT '0',
   `comment` text COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_surveyticket_surveyquestions`;

CREATE TABLE `glpi_plugin_surveyticket_surveyquestions` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_surveyticket_surveys_id` int(11) NOT NULL DEFAULT '0',
   `plugin_surveyticket_questions_id` int(11) NOT NULL DEFAULT '0',
   `order` tinyint(2) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_surveyticket_tickettemplates`;

CREATE TABLE `glpi_plugin_surveyticket_tickettemplates` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `plugin_surveyticket_surveys_id` tinyint(1) NOT NULL DEFAULT '0',
   `tickettemplates_id` int(11) NOT NULL DEFAULT '0',
   `type` tinyint(1) NOT NULL DEFAULT '0',
   `is_helpdesk` tinyint(1) NOT NULL DEFAULT '0',
   `is_central` tinyint(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;



DROP TABLE IF EXISTS `glpi_plugin_surveyticket_profiles`;

CREATE TABLE `glpi_plugin_surveyticket_profiles` (
  `profiles_id` int(11) NOT NULL DEFAULT '0',
  `config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`)
   VALUES (NULL,'PluginSurveyticketQuestion', '2', '1', '0');
