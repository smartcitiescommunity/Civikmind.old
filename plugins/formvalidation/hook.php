<?php
/*
 * -------------------------------------------------------------------------
Form Validation plugin
Copyright (C) 2016 by Raynet SAS a company of A.Raymond Network.

http://www.araymond.com
-------------------------------------------------------------------------

LICENSE

This file is part of Form Validation plugin for GLPI.

This file is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */

function plugin_formvalidation_install() {
   global $DB ;

   if (!TableExists("glpi_plugin_formvalidation_configs")) {
		$query = "   CREATE TABLE `glpi_plugin_formvalidation_configs` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `css_mandatory` VARCHAR(200) NOT NULL DEFAULT '{\"background-color\":\"lightgrey\", \"font-weight\":\"bold\"}',
                     `css_error` VARCHAR(200) NOT NULL DEFAULT '{\"background-color\": \"red\"}',
                  PRIMARY KEY (`id`)
                  )
                  ENGINE=InnoDB
                  ;
			";

		$DB->query($query) or die("error creating glpi_plugin_formvalidation_configs" . $DB->error());

      $query = "INSERT INTO `glpi_plugin_formvalidation_configs` (`id`) VALUES (1);" ;
      $DB->query($query) or die("error inserting default config into glpi_plugin_formvalidation_configs" . $DB->error());
	}

   if (!TableExists("glpi_plugin_formvalidation_pages")) {
		$query = "CREATE TABLE `glpi_plugin_formvalidation_pages` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `name` VARCHAR(200) NULL DEFAULT NULL,
	                  `entities_id` INT(11) NOT NULL DEFAULT '0',
	                  `itemtype` VARCHAR(100) NOT NULL,
	                  `is_recursive` TINYINT(1) NOT NULL DEFAULT '0',
	                  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
	                  `comment` TEXT NULL,
	                  `date_mod` TIMESTAMP NULL DEFAULT NULL,
	                  PRIMARY KEY (`id`),
	                  INDEX `itemtype` (`itemtype`),
                     INDEX `entities_id` (`entities_id`)
                  )
                  COLLATE='utf8_general_ci'
                  ENGINE=InnoDB
                  ;
			";

		$DB->query($query) or die("error creating glpi_plugin_formvalidation_pages" . $DB->error());

      // init data
      $query = "INSERT INTO `glpi_plugin_formvalidation_pages` (`id`, `name`, `entities_id`, `itemtype`, `is_recursive`, `is_active`, `comment`, `date_mod`)
               VALUES (1, 'Form Validation Page', 0, 'PluginFormvalidationPage', 1, 1, NULL, NULL),
                      (2, 'Form Validation Form', 0, 'PluginFormvalidationForm', 1, 1, NULL, NULL),
                      (3, 'Form Validation Field', 0, 'PluginFormvalidationField', 1, 1, NULL, NULL),
                      (4, 'Ticket Validations', 0, 'Ticket', 1, 1, NULL, NULL);";
      $DB->query($query) or die("error inserting default pages into glpi_plugin_formvalidation_pages" . $DB->error());

	}

   if (!TableExists("glpi_plugin_formvalidation_forms")) {
		$query = "CREATE TABLE `glpi_plugin_formvalidation_forms` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `name` VARCHAR(200) NULL DEFAULT NULL,
	                  `pages_id` INT(11) NOT NULL,
	                  `css_selector` VARCHAR(255) NOT NULL,
                     `is_createitem` TINYINT(1) NOT NULL DEFAULT '0',
	                  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
	                  `formula` TEXT NULL,
	                  `comment` TEXT NULL,
	                  `date_mod` TIMESTAMP NULL DEFAULT NULL,
	                  PRIMARY KEY (`id`),
	                  INDEX `pages_id` (`pages_id`)
                  )
                  COLLATE='utf8_general_ci'
                  ENGINE=InnoDB
                  ;
			";

		$DB->query($query) or die("error creating glpi_plugin_formvalidation_forms" . $DB->error());

      $query = "INSERT INTO `glpi_plugin_formvalidation_forms` (`id`, `name`, `pages_id`, `css_selector`, `is_createitem`, `is_active`, `formula`, `comment`, `date_mod`)
               VALUES (1, 'form(/plugins/formvalidation/front/page.form.php)', 1, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/page.form.php\\\"]', 0, 1, NULL, NULL, NULL),
                      (2, 'form(/plugins/formvalidation/front/form.form.php)', 2, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/form.form.php\\\"]', 0, 1, NULL, NULL, NULL),
                      (3, 'form(/plugins/formvalidation/front/field.form.php)', 3, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/field.form.php\\\"]', 0, 1, NULL, NULL, NULL),
                      (4, 'Simplified interface Creation', 4, 'form[name=\\\"helpdeskform\\\"][action=\\\"/front/tracking.injector.php\\\"]', 1, 1, NULL, NULL, NULL),
                      (5, 'Followup Validations', 4, 'form[name=\\\"form\\\"][action=\\\"/front/ticketfollowup.form.php\\\"]', 0, 1, NULL, NULL, NULL),
                      (6, 'Central Interface Edit', 4, 'form[name=\\\"form_ticket\\\"][action=\\\"/front/ticket.form.php\\\"]', 0, 1, NULL, NULL,NULL),
                      (7, 'Central Interface Creation', 4, 'form[name=\\\"form_ticket\\\"][action=\\\"/front/ticket.form.php\\\"]', 1, 1, NULL, NULL, NULL),
                      (8, 'form(/plugins/formvalidation/front/page.form.php)', 1, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/page.form.php\\\"]', 1, 1, NULL, NULL, NULL);";

      $DB->query($query) or die("error inserting default data into glpi_plugin_formvalidation_forms" . $DB->error());

	}

   if (!TableExists("glpi_plugin_formvalidation_fields")) {
		$query = "CREATE TABLE `glpi_plugin_formvalidation_fields` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `name` VARCHAR(200) NULL DEFAULT NULL,
	                  `forms_id` INT(11) NOT NULL,
	                  `css_selector_value` VARCHAR(255) NULL DEFAULT NULL,
	                  `css_selector_altvalue` VARCHAR(255) NULL DEFAULT NULL,
	                  `css_selector_errorsign` VARCHAR(255) NULL DEFAULT NULL,
	                  `css_selector_mandatorysign` VARCHAR(255) NULL DEFAULT NULL,
	                  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
	                  `show_mandatory` TINYINT(1) NOT NULL DEFAULT '1',
	                  `show_mandatory_if` TEXT NULL,
                     `formula` TEXT NULL,
	                  `comment` TEXT NULL,
	                  `date_mod` TIMESTAMP NULL DEFAULT NULL,
	                  PRIMARY KEY (`id`),
               	   UNIQUE INDEX `forms_id_css_selector_value` (`forms_id`, `css_selector_value`),
	                  INDEX `forms_id` (`forms_id`)
                  )
                  COLLATE='utf8_general_ci'
                  ENGINE=InnoDB
                  ;
			";

		$DB->query($query) or die("error creating glpi_plugin_formvalidation_fields" . $DB->error());

      $query = "INSERT INTO `glpi_plugin_formvalidation_fields` (`id`, `name`, `forms_id`, `css_selector_value`, `css_selector_altvalue`, `css_selector_errorsign`, `css_selector_mandatorysign`, `is_active`, `show_mandatory`, `show_mandatory_if`, `formula`, `comment`, `date_mod`)
                VALUES (1,  'Name', 1, 'div>table>tbody>tr:eq(1)>td:eq(1) input[name=\\\"name\\\"]', NULL, 'div>table>tbody>tr:eq(1)>td:eq(1)', 'div>table>tbody>tr:eq(1)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL),
                       (2,  'Name', 2, 'div>table>tbody>tr:eq(1)>td:eq(1) input[name=\\\"name\\\"]', NULL, 'div>table>tbody>tr:eq(1)>td:eq(1)', 'div>table>tbody>tr:eq(1)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL),
                       (3,  'CSS Selector', 2, 'div>table>tbody>tr:eq(4)>td:eq(1) input[name=\\\"css_selector\\\"]', NULL, 'div>table>tbody>tr:eq(4)>td:eq(1)', 'div>table>tbody>tr:eq(4)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL),
                       (4,  'Name', 3, 'div>table>tbody>tr:eq(1)>td:eq(1) input[name=\\\"name\\\"]', NULL, 'div>table>tbody>tr:eq(1)>td:eq(1)', 'div>table>tbody>tr:eq(1)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL),
                       (5,  'Value CSS selector', 3, 'div>table>tbody>tr:eq(3)>td:eq(1) input[name=\\\"css_selector_value\\\"]', NULL, 'div>table>tbody>tr:eq(3)>td:eq(1)', 'div>table>tbody>tr:eq(3)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL),
                       (6,  'Force mandatory sign', 3, 'div>table>tbody>tr:eq(6)>td:eq(1)>span input[name=\\\"show_mandatory\\\"]', NULL, 'div>table>tbody>tr:eq(6)>td:eq(1)', 'div>table>tbody>tr:eq(6)>td:eq(0)', 1, 0, NULL, NULL, NULL, NULL),
                       (7,  'Mandatory sign formula', 3, 'div>table>tbody>tr:eq(7)>td:eq(1) input[name=\\\"show_mandatory_if\\\"]', NULL, 'div>table>tbody>tr:eq(7)>td:eq(1)', 'div>table>tbody>tr:eq(7)>td:eq(0)', 0, 0, NULL, NULL, NULL, NULL),
                       (8,  'Mandatory sign CSS selector', 3, 'div>table>tbody>tr:eq(8)>td:eq(1) input[name=\\\"css_selector_mandatorysign\\\"]', NULL, 'div>table>tbody>tr:eq(8)>td:eq(1)', 'div>table>tbody>tr:eq(8)>td:eq(0)', 1, 0, '#6 || #7!=\'\'', NULL, NULL, NULL),
                       (9,  'Title', 4, 'div>table>tbody>tr:eq(8)>td:eq(1) input[name=\\\"name\\\"]', NULL, 'div>table>tbody>tr:eq(8)>td:eq(1)', 'div>table>tbody>tr:eq(8)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL),
                       (10, 'Description', 4, 'div>table>tbody>tr:eq(9)>td:eq(1)>div textarea[name=\\\"content\\\"]', NULL, 'div>table>tbody>tr:eq(9)>td:eq(1)', 'div>table>tbody>tr:eq(9)>td:eq(0)', 1, 0, '#11 == 2','(#11 == 1) || (#11 == 2 && #.length > 10 && countWords(#) > 5)', NULL, NULL),
                       (11, 'Type', 4, 'div>table>tbody>tr:eq(1)>td:eq(1) select[name=\\\"type\\\"]', NULL, 'div>table>tbody>tr:eq(1)>td:eq(1)', 'div>table>tbody>tr:eq(1)>td:eq(0)', 1, 0, NULL, NULL, NULL, NULL),
                       (12, 'Description', 5, 'div>table>tbody>tr:eq(1)>td:eq(1) textarea[name=\\\"content\\\"]', NULL, 'div>table>tbody>tr:eq(1)>td:eq(1)', 'div>table>tbody>tr:eq(1)>td:eq(0)', 1, 1, NULL, '#.length > 10 && countWords(#) > 3', NULL, NULL),
                       (13, 'Title', 6, 'div>table:eq(2)>tbody>tr:eq(0)>td input[name=\\\"name\\\"]', NULL, 'div>table:eq(2)>tbody>tr:eq(0)>td', 'div>table:eq(2)>tbody>tr:eq(0)>th', 1, 1, NULL, NULL, NULL, NULL),
                       (14, 'Description', 6, 'div>table:eq(2)>tbody>tr:eq(1)>td>div textarea[name=\\\"content\\\"]', NULL, 'div>table:eq(2)>tbody>tr:eq(1)>td', 'div>table:eq(2)>tbody>tr:eq(1)>th', 1, 1, NULL, NULL, NULL, NULL),
                       (15, 'Type', 6, 'div>table:eq(1)>tbody>tr:eq(0)>td:eq(0) select[name=\\\"type\\\"]', NULL, 'div>table:eq(1)>tbody>tr:eq(0)>td:eq(0)', 'div>table:eq(1)>tbody>tr:eq(0)>th:eq(0)', 1, 0, NULL, NULL, NULL, NULL),
                       (16, 'Title', 7, 'div>table:eq(2)>tbody>tr:eq(0)>td input[name=\\\"name\\\"]', NULL, 'div>table:eq(2)>tbody>tr:eq(0)>td', 'div>table:eq(2)>tbody>tr:eq(0)>th', 1, 1, NULL, NULL, NULL, NULL),
                       (17, 'Description', 7, 'div>table:eq(2)>tbody>tr:eq(1)>td>div textarea[name=\\\"content\\\"]', NULL, 'div>table:eq(2)>tbody>tr:eq(1)>td', 'div>table:eq(2)>tbody>tr:eq(1)>th', 1, 1, NULL, NULL, NULL, NULL),
                       (18, 'Name', 8, 'div>table>tbody>tr:eq(1)>td:eq(1) input[name=\\\"name\\\"]', NULL, 'div>table>tbody>tr:eq(1)>td:eq(1)', 'div>table>tbody>tr:eq(1)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL); " ;

   $DB->query($query) or die("error inserting default data into glpi_plugin_formvalidation_fields" . $DB->error());

	}

   return true;
}


// Uninstall process for plugin : need to return true if succeeded
function plugin_formvalidation_uninstall() {
   // will not drop tables
   return true;
}


?>