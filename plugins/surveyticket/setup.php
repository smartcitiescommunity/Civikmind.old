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

define ("PLUGIN_SURVEYTICKET_VERSION", "0.90+1.1");

// Init the hooks of surveyticket
function plugin_init_surveyticket() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['surveyticket'] = true;

   if (isset($_SESSION["glpiID"])) {

      $plugin = new Plugin();
      if ($plugin->isActivated('surveyticket')) {
         Plugin::registerClass('PluginSurveyticketProfile', array('addtabon' => array('Profile')));

         $PLUGIN_HOOKS['change_profile']['surveyticket'] = array('PluginSurveyticketProfile', 'initProfile');

         if (Session::haveRight("plugin_surveyticket", READ)) {
            $PLUGIN_HOOKS['menu_toadd']['surveyticket'] = array('helpdesk' => 'PluginSurveyticketMenu');
         }
         if (Session::haveRight("config", READ)) {
            $PLUGIN_HOOKS['config_page']['surveyticket'] = 'front/menu.php';
         }
         
         if (Session::haveRight("plugin_surveyticket_use", CREATE)) {
            if (strpos($_SERVER['REQUEST_URI'], "ticket.form.php") !== false ||
               strpos($_SERVER['REQUEST_URI'], "helpdesk.public.php") !== false ||
               strpos($_SERVER['REQUEST_URI'], "tracking.injector.php") !== false) {
               $PLUGIN_HOOKS['add_javascript']['surveyticket'][] = 'scripts/surveyticket.js';
               $PLUGIN_HOOKS['add_javascript']['surveyticket'][] = 'scripts/surveyticket_load_scripts.js';

               $PLUGIN_HOOKS['pre_item_add']['surveyticket'] = array('Ticket' => 
                                          array('PluginSurveyticketTicket', 'preAddTicket'));
               $PLUGIN_HOOKS['item_add']['surveyticket'] = array('Ticket' => 
                                          array('PluginSurveyticketTicket', 'postAddTicket'));

               $PLUGIN_HOOKS['item_empty']['surveyticket']      = array('Ticket'     => 
                                          array('PluginSurveyticketTicket', 'emptyTicket'));
            }
         }
      }
   }
}

// Name and Version of the plugin
function plugin_version_surveyticket() {
   return array('name' => __('Survey', 'surveyticket'),
      'shortname' => 'surveyticket',
      'version' => PLUGIN_SURVEYTICKET_VERSION,
      'author' => '<a href="http://infotel.com/services/expertise-technique/glpi/">Infotel</a> & 
                  <a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>',
      'homepage' => 'https://forge.glpi_project.org/projects/surveyticket/',
      'minGlpiVersion' => '0.90',
      'license' => 'AGPLv3+',
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_surveyticket_check_prerequisites() {

   if (version_compare(GLPI_VERSION, '0.90', 'lt') || version_compare(GLPI_VERSION, '9.2', 'ge')) {
      _e('Your GLPI version not compatible, require 0.90', 'surveyticket');
      return FALSE;
   }

   return TRUE;
}

function plugin_surveyticket_check_config() {
   return TRUE;
}