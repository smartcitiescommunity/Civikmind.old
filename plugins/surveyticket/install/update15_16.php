<?php

/*
  ------------------------------------------------------------------------
  Surveyticket
  Copyright (C) 2012-2016 by the Surveyticket plugin Development Team.

  ------------------------------------------------------------------------

  LICENSE

  This file is part of Surveyticket plugin project.

  Surveyticket plugin is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Surveyticket plugin is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with Surveyticket plugin. If not, see <http://www.gnu.org/licenses/>.

 */


/**
 * Update from 1.4 to 1.5
 *
 * @return bool for success (will die for most error)
 * */
function update15to16() {
   global $DB;

   $migration = new Migration(15);

   // add field mandatory
   $migration->addField('glpi_plugin_surveyticket_answers', 'order', "tinyint(2) NOT NULL DEFAULT '0'", array('value' => 0));

   $query = "CREATE TABLE `glpi_plugin_surveyticket_answertranslations` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `items_id` int(11) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `language` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
   `field` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `value` text COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

   $DB->queryOrDie($query);

   $query = "CREATE TABLE `glpi_plugin_surveyticket_questiontranslations` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `items_id` int(11) NOT NULL DEFAULT '0',
   `itemtype` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `language` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
   `field` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
   `value` text COLLATE utf8_unicode_ci DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
   $DB->queryOrDie($query);

   $migration->executeMigration();

   return true;
}