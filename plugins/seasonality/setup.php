<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 seasonality plugin for GLPI
 Copyright (C) 2009-2016 by the seasonality Development Team.

 https://github.com/InfotelGLPI/seasonality
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of seasonality.

 seasonality is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 seasonality is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with seasonality. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// Init the hooks of the plugins -Needed
function plugin_init_seasonality() {
   global $PLUGIN_HOOKS, $CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['seasonality'] = true;
   $PLUGIN_HOOKS['change_profile']['seasonality'] = array('PluginSeasonalityProfile', 'changeProfile');

   if (Session::getLoginUserID()) {
      Plugin::registerClass('PluginSeasonalityProfile', array('addtabon' => 'Profile'));

      // Display a menu entry
      if (Session::haveRight("plugin_seasonality", READ)) {
         $PLUGIN_HOOKS['use_massive_action']['seasonality']  = 1;
         $PLUGIN_HOOKS['menu_toadd']['seasonality']          = array('helpdesk' => 'PluginSeasonalitySeasonality');
         Plugin::registerClass('PluginSeasonalityItem', array('addtabon' => 'ITILCategory'));
         Plugin::registerClass('PluginSeasonalityItem', array('addtabon' => 'PluginSeasonalitySeasonality'));
      }
      
      // Add specific files to add to the header : javascript or css
      $PLUGIN_HOOKS['add_javascript']['seasonality'] = array("lib/daterangepicker/jquery.comiseo.daterangepicker.min.js", 
                                                             "lib/daterangepicker/moment.min.js");
      
      if (strpos($_SERVER['REQUEST_URI'], "ticket.form.php") !== false
            || strpos($_SERVER['REQUEST_URI'], "helpdesk.public.php") !== false
            || strpos($_SERVER['REQUEST_URI'], "tracking.injector.php") !== false) {
         $PLUGIN_HOOKS['add_javascript']['seasonality'][] = 'scripts/seasonality.js';
         $PLUGIN_HOOKS['add_javascript']['seasonality'][] = 'scripts/seasonality_load_scripts.js';
      }
      
      // Css 
      $PLUGIN_HOOKS['add_css']['seasonality'] = array("lib/daterangepicker/jquery.comiseo.daterangepicker.css");

      // Purge
      $PLUGIN_HOOKS['pre_item_purge']['seasonality'] = array(
         'PluginSeasonalitySeasonality' => array('PluginSeasonalityItem', 'purgeItem'),
         'Profile'                      => array('PluginSeasonalityProfile', 'purgeProfiles')
      );
      
      $PLUGIN_HOOKS['plugin_datainjection_populate']['seasonality'] = 'plugin_datainjection_populate_seasonality';
   }
   // End init, when all types are registered
   $PLUGIN_HOOKS['post_init']['seasonality'] = 'plugin_seasonality_postinit';
}

// Get the name and the version of the plugin - Needed
function plugin_version_seasonality() {

   return array(
      'name'           => _n('Seasonality', 'Seasonalities', 2, 'seasonality'),
      'version'        => '1.2.0',
      'license'        => 'GPLv2+',
      'author'         => "<a href='http://infotel.com/services/expertise-technique/glpi/'>Infotel</a> & Ludovic Dupont",
      'homepage'       => 'https://github.com/InfotelGLPI/seasonality',
      'minGlpiVersion' => '0.90',
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_seasonality_check_prerequisites() {
   if (version_compare(GLPI_VERSION, '0.90', 'lt') || version_compare(GLPI_VERSION, '9.2', 'ge')) {
      echo __('This plugin requires GLPI >= 0.90', 'seasonality');
      return false;
   }
   return true;
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_seasonality_check_config() {
   return true;
}

?>