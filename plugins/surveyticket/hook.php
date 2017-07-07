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

  ------------------------------------------------------------------------

  @package   Surveyticket plugin
  @author    David Durieux
  @author    Infotel
  @copyright Copyright (c) 2012-2016 Surveyticket plugin team
  @license   AGPL License 3.0 or (at your option) any later version
  http://www.gnu.org/licenses/agpl-3.0-standalone.html
  @link      https://github.com/pluginsGLPI/surveyticket
  @since     2012

  ------------------------------------------------------------------------
 */


/**
 * @return bool
 */
function plugin_surveyticket_install() {
   global $DB;

   include_once (GLPI_ROOT . "/plugins/surveyticket/inc/profile.class.php");

   if (!TableExists('glpi_plugin_surveyticket_questions')) {
      $DB->runFile(GLPI_ROOT . "/plugins/surveyticket/install/mysql/empty-1.0.7.sql");
   }
   if (!FieldExists("glpi_plugin_surveyticket_surveyquestions", "mandatory")) {
      include(GLPI_ROOT . "/plugins/surveyticket/install/update13_14.php");
      update13to14();
   }
   if (!FieldExists("glpi_plugin_surveyticket_answers", "mandatory")) {
      include(GLPI_ROOT . "/plugins/surveyticket/install/update14_15.php");
      update14to15();
   }
   if (!FieldExists("glpi_plugin_surveyticket_answers", "order")) {
      include(GLPI_ROOT . "/plugins/surveyticket/install/update15_16.php");
      update15to16();
   }
   PluginSurveyticketProfile::initProfile();
   PluginSurveyticketProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   $migration = new Migration("1.0.5");
   $migration->dropTable('glpi_plugin_surveyticket_profiles');

   return true;
}

// Uninstall process for plugin : need to return true if succeeded
/**
 * @return bool
 */
function plugin_surveyticket_uninstall() {
   global $DB;

   include_once (GLPI_ROOT . "/plugins/surveyticket/inc/profile.class.php");
   include_once (GLPI_ROOT . "/plugins/surveyticket/inc/menu.class.php");

   $query = "SHOW TABLES;";
   $result = $DB->query($query);
   while ($data = $DB->fetch_array($result)) {
      if (strstr($data[0], "glpi_plugin_surveyticket_")) {

         $query_delete = "DROP TABLE `" . $data[0] . "`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

   $query = "DELETE FROM `glpi_displaypreferences`
           WHERE `itemtype` LIKE 'PluginSurveyticket%';";
   $DB->query($query) or die($DB->error());

   //Delete rights associated with the plugin
   $profileRight = new ProfileRight();
   foreach (PluginSurveyticketProfile::getAllRights() as $right) {
      $profileRight->deleteByCriteria(array('name' => $right['field']));
   }
   PluginSurveyticketMenu::removeRightsFromSession();
   PluginSurveyticketProfile::removeRightsFromSession();

   return true;
}