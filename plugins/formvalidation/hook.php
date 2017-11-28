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

if (method_exists( $DB, 'tableExists')) {
   function arTableExists($table) {
      global $DB;
      return $DB->tableExists($table);
   }
} else {
   function arTableExists($table) {
      return TableExists($table);
   }
}

if (method_exists( $DB, 'fieldExists')) {
   function arFieldExists($table, $field, $usecache = true) {
      global $DB;
      return $DB->fieldExists($table, $field, $usecache);
   }
} else {
   function arFieldExists($table, $field, $usecache = true) {
      return FieldExists($table, $field, $usecache);
   }
}

/**
 * Summary of plugin_formvalidation_install
 * @return boolean
 */
function plugin_formvalidation_install() {
   global $DB;

   if (!arTableExists("glpi_plugin_formvalidation_configs")) {
        $query = "CREATE TABLE `glpi_plugin_formvalidation_configs` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `css_mandatory` VARCHAR(200) NOT NULL DEFAULT '{\"background-color\":\"lightgrey\", \"font-weight\":\"bold\"}',
                     `css_error` VARCHAR(200) NOT NULL DEFAULT '{\"background-color\": \"red\"}',
                  PRIMARY KEY (`id`)
                  )
                  ENGINE=InnoDB
                  ;
			";

        $DB->query($query) or die("Error creating glpi_plugin_formvalidation_configs " . $DB->error());

      $query = "INSERT INTO `glpi_plugin_formvalidation_configs` (`id`) VALUES (1);";
      $DB->query($query) or die("Error inserting default config into glpi_plugin_formvalidation_configs " . $DB->error());
   }

   if (!arTableExists("glpi_plugin_formvalidation_itemtypes")) {
        $query = "CREATE TABLE `glpi_plugin_formvalidation_itemtypes` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `name` VARCHAR(255) NOT NULL,
	                  `itemtype` VARCHAR(100) NOT NULL,
	                  `URL_path_part` VARCHAR(255) NOT NULL,
	                  PRIMARY KEY (`id`),
	                  UNIQUE INDEX `itemtype` (`itemtype`),
	                  UNIQUE INDEX `name` (`name`)
                  )
                  COLLATE='utf8_general_ci'
                  ENGINE=InnoDB
                  ;
			";

        $DB->query($query) or die("Error creating glpi_plugin_formvalidation_itemtypes " . $DB->error());

      // init data
      $query = "INSERT INTO `glpi_plugin_formvalidation_itemtypes` (`id`, `name`, `itemtype`, `URL_path_part`)
               VALUES (1, 'Computer', 'Computer', '/front/computer.form.php'),
                      (2, 'Monitor', 'Monitor', '/front/monitor.form.php'),
                      (3, 'Software', 'Software', '/front/software.form.php'),
                      (4, 'NetworkEquipment', 'NetworkEquipment', '/front/networkequipment.form.php'),
                      (5, 'Peripheral', 'Peripheral', '/front/peripheral.form.php'),
                      (6, 'Printer', 'Printer', '/front/printer.form.php'),
                      (7, 'CartridgeItem', 'CartridgeItem', '/front/cartridgeitem.form.php'),
                      (8, 'ConsumableItem', 'ConsumableItem', '/front/consumableitem.form.php'),
                      (9, 'Phone', 'Phone', '/front/phone.form.php'),
                      (10, 'Ticket', 'Ticket', '/front/ticket.form.php,'),
                      (11, 'Problem', 'Problem', '/front/problem.form.php'),
                      (12, 'TicketRecurrent', 'TicketRecurrent', '/front/ticketrecurrent.form.php'),
                      (13, 'Budget', 'Budget', '/front/budget.form.php'),
                      (14, 'Supplier', 'Supplier', '/front/supplier.form.php'),
                      (15, 'Contact', 'Contact', '/front/contact.form.php'),
                      (16, 'Contract', 'Contract', '/front/contract.form.php'),
                      (17, 'Document', 'Document', '/front/document.form.php'),
                      (18, 'Notes', 'Notes', '/front/notes.form.php'),
                      (19, 'RSSFeed', 'RSSFeed', '/front/rssfeed.form.php'),
                      (20, 'User', 'User', '/front/user.form.php'),
                      (21, 'Group', 'Group', '/front/group.form.php'),
                      (22, 'Entity', 'Entity', '/front/entity.form.php'),
                      (23, 'Profile', 'Profile', '/front/profile.form.php'),
                      (24, 'PluginFormcreatorForm', 'PluginFormcreatorForm', '/plugins/formcreator/formdisplay.php'),
                      (25, 'PluginFormvalidationPage', 'PluginFormvalidationPage', '/plugins/formvalidation/front/page.form.php'),
                      (26, 'PluginFormvalidationForm', 'PluginFormvalidationForm', '/plugins/formvalidation/front/form.form.php'),
                      (27, 'PluginFormvalidationField', 'PluginFormvalidationField', '/plugins/formvalidation/front/field.form.php'),
                      (28, 'PluginRayusermanagementticketRayusermanagementticket', 'PluginRayusermanagementticketRayusermanagementticket', '/plugins/rayusermanagementticket/front/rayusermanagementticket.helpdesk.public.php');";
      $DB->query($query) or die("Error inserting default item types into glpi_plugin_formvalidation_itemtypes " . $DB->error());

   }

   if (!arTableExists("glpi_plugin_formvalidation_pages")) {
        $query = "CREATE TABLE `glpi_plugin_formvalidation_pages` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `name` VARCHAR(200) NULL DEFAULT NULL,
	                  `entities_id` INT(11) NOT NULL DEFAULT '0',
	                  `itemtypes_id` INT(11) NOT NULL DEFAULT '0',
	                  `is_recursive` TINYINT(1) NOT NULL DEFAULT '0',
	                  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
	                  `comment` TEXT NULL,
	                  `date_mod` TIMESTAMP NULL DEFAULT NULL,
	                  PRIMARY KEY (`id`),
	                  INDEX `itemtypes_id` (`itemtypes_id`),
                     INDEX `entities_id` (`entities_id`)
                  )
                  COLLATE='utf8_general_ci'
                  ENGINE=InnoDB
                  ;
			";

        $DB->query($query) or die("Error creating glpi_plugin_formvalidation_pages " . $DB->error());

      // init data
      $query = "INSERT INTO `glpi_plugin_formvalidation_pages` (`id`, `name`, `entities_id`, `itemtypes_id`, `is_recursive`, `is_active`, `comment`, `date_mod`)
               VALUES (1, 'Form Validation Page', 0, 25, 1, 1, NULL, NULL),
                      (2, 'Form Validation Form', 0, 26, 1, 1, NULL, NULL),
                      (3, 'Form Validation Field', 0, 27, 1, 1, NULL, NULL),
                      (4, 'Ticket Validations', 0, 10, 1, 1, NULL, NULL);";
      $DB->query($query) or die("Error inserting default pages into glpi_plugin_formvalidation_pages " . $DB->error());

   } else {
      if (!arFieldExists('glpi_plugin_formvalidation_pages', 'itemtypes_id')) {
         $query = "ALTER TABLE `glpi_plugin_formvalidation_pages`
	                  ADD COLUMN `itemtypes_id` INT(11) NOT NULL DEFAULT '0' AFTER `itemtype`,
                     ADD INDEX `itemtypes_id` (`itemtypes_id`);
                  ";
         $DB->query($query) or die("Error inserting itemtypes_id field into glpi_plugin_formvalidation_pages " . $DB->error());
      }

      // check if migration is neccessary
      $pages = getAllDatasFromTable('glpi_plugin_formvalidation_pages', 'itemtypes_id = 0');
      if (count($pages)) {
         // migration of itemtype into itemtypes_id
         $query = "UPDATE glpi_plugin_formvalidation_pages AS gpfp, glpi_plugin_formvalidation_itemtypes AS gpfi
                   SET gpfp.itemtypes_id = gpfi.id
                   WHERE gpfi.itemtype = gpfp.itemtype;";
         $DB->query($query) or die("Error migrating itemtype into itemtypes_id field in glpi_plugin_formvalidation_pages " . $DB->error());

         // check if all pages have been migrated
         $pages = getAllDatasFromTable('glpi_plugin_formvalidation_pages', 'itemtypes_id = 0');
         if (count($pages)) {
            die("Error some itemtype can't be migrated into itemtypes_id field from glpi_plugin_formvalidation_pages, </br>
                 please check the list of itemtype in glpi_plugin_formvalidation_pages and in glpi_plugin_formvalidation_itemtypes,</br>
                 fix the issue and restart install of the plugin.");
         }
      }

      if (arFieldExists('glpi_plugin_formvalidation_pages', 'itemtype') && !count($pages)) {
         // delete itemtype field after migration is done
         $query = "ALTER TABLE `glpi_plugin_formvalidation_pages`
                   DROP COLUMN `itemtype`,
	                DROP INDEX `itemtype`;";
         $DB->query($query) or die("Error deleting itemtypes field and index from glpi_plugin_formvalidation_pages " . $DB->error());

         // delete the itemtype field from glpi_displaypreferences
         $query = "UPDATE `glpi_displaypreferences`
                   SET num = 803
                   WHERE itemtype = 'PluginFormvalidationPage' AND num = 3;";
         $DB->query($query) or die("Error updating num in glpi_displaypreferences " . $DB->error());

      }
   }

   if (!arTableExists("glpi_plugin_formvalidation_forms")) {
        $query = "CREATE TABLE `glpi_plugin_formvalidation_forms` (
	                  `id` INT(11) NOT NULL AUTO_INCREMENT,
	                  `name` VARCHAR(200) NULL DEFAULT NULL,
	                  `pages_id` INT(11) NOT NULL,
	                  `css_selector` VARCHAR(255) NOT NULL,
                     `is_createitem` TINYINT(1) NOT NULL DEFAULT '0',
	                  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
	                  `use_for_massiveaction` TINYINT(1) NOT NULL DEFAULT '0',
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

        $DB->query($query) or die("Error creating glpi_plugin_formvalidation_forms" . $DB->error());

      $query = "INSERT INTO `glpi_plugin_formvalidation_forms` (`id`, `name`, `pages_id`, `css_selector`, `is_createitem`, `is_active`, `use_for_massiveaction`, `formula`, `comment`, `date_mod`)
               VALUES (1, 'form(/plugins/formvalidation/front/page.form.php)', 1, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/page.form.php\\\"]', 0, 1, 0, NULL, NULL, NULL),
                      (2, 'form(/plugins/formvalidation/front/form.form.php)', 2, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/form.form.php\\\"]', 0, 1, 0, NULL, NULL, NULL),
                      (3, 'form(/plugins/formvalidation/front/field.form.php)', 3, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/field.form.php\\\"]', 0, 1, 0, NULL, NULL, NULL),
                      (4, 'Simplified interface Creation', 4, 'form[name=\\\"helpdeskform\\\"][action=\\\"/front/tracking.injector.php\\\"]', 1, 1, 0, NULL, NULL, NULL),
                      (5, 'Followup Validations', 4, 'form[name=\\\"form\\\"][action=\\\"/front/ticketfollowup.form.php\\\"]', 0, 1, 0, NULL, NULL, NULL),
                      (6, 'Central Interface Edit', 4, 'form[name=\\\"form_ticket\\\"][action=\\\"/front/ticket.form.php\\\"]', 0, 1, 1, NULL, NULL,NULL),
                      (7, 'Central Interface Creation', 4, 'form[name=\\\"form_ticket\\\"][action=\\\"/front/ticket.form.php\\\"]', 1, 1, 0, NULL, NULL, NULL),
                      (8, 'form(/plugins/formvalidation/front/page.form.php)', 1, 'form[name=\\\"form\\\"][action=\\\"/plugins/formvalidation/front/page.form.php\\\"]', 1, 1, 0, NULL, NULL, NULL);";

      $DB->query($query) or die("Error inserting default data into glpi_plugin_formvalidation_forms " . $DB->error());

   } else {
      if (!arFieldExists('glpi_plugin_formvalidation_forms', 'use_for_massiveaction')) {
         $query = "ALTER TABLE `glpi_plugin_formvalidation_forms`
	                  ADD COLUMN `use_for_massiveaction` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_active`;
                  ";
         $DB->query($query) or die("Error inserting use_for_massiveaction field into glpi_plugin_formvalidation_forms " . $DB->error());
      }
   }

   if (!arTableExists("glpi_plugin_formvalidation_fields")) {
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

        $DB->query($query) or die("Error creating glpi_plugin_formvalidation_fields " . $DB->error());

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
                       (18, 'Name', 8, 'div>table>tbody>tr:eq(1)>td:eq(1) input[name=\\\"name\\\"]', NULL, 'div>table>tbody>tr:eq(1)>td:eq(1)', 'div>table>tbody>tr:eq(1)>td:eq(0)', 1, 1, NULL, NULL, NULL, NULL); ";

      $DB->query($query) or die("Error inserting default data into glpi_plugin_formvalidation_fields " . $DB->error());

   }

   return true;
}


/**
 * Summary of plugin_formvalidation_uninstall
 * Uninstall process for plugin : need to return true if succeeded
 * @return boolean
 */
function plugin_formvalidation_uninstall() {
   // will not drop tables
   return true;
}


