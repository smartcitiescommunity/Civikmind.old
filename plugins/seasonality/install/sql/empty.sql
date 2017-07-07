--
-- Structure de la table 'glpi_plugin_seasonality_seasonalities'
-- 
--
DROP TABLE IF EXISTS `glpi_plugin_seasonality_seasonalities`;
CREATE TABLE `glpi_plugin_seasonality_seasonalities` (
  `id` int(11) NOT NULL auto_increment, -- id 
  `name` varchar(255) DEFAULT NULL,
  `entities_id` varchar(255) DEFAULT NULL,
  `is_recursive` tinyint(1) NOT NULL DEFAULT 0,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `urgency` int(11) NOT NULL DEFAULT 0,
  `periodicity` varchar(255) DEFAULT NULL,
  PRIMARY KEY  (`id`),
  KEY `entities_id` (`entities_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Structure de la table 'glpi_plugin_seasonality_items'
-- 
--
DROP TABLE IF EXISTS `glpi_plugin_seasonality_items`;
CREATE TABLE `glpi_plugin_seasonality_items` (
  `id` int(11) NOT NULL auto_increment, -- id 
  `plugin_seasonality_seasonalities_id` int(11) NOT NULL DEFAULT 0,
  `itilcategories_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unicity` (`plugin_seasonality_seasonalities_id`,`itilcategories_id`),
  KEY `itilcategories_id` (`itilcategories_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;