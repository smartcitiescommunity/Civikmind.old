<?php

/*
   ------------------------------------------------------------------------
   GLPI Plugin Renamer
   Copyright (C) 2014 by the GLPI Plugin Renamer Development Team.

   https://forge.indepnet.net/projects/renamer
   ------------------------------------------------------------------------

   LICENSE

   This file is part of GLPI Plugin Renamer project.

   GLPI Plugin Renamer is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 3 of the License, or
   (at your option) any later version.

   GLPI Plugin Renamer is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI Plugin Renamer. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   GLPI Plugin Renamer
   @author    Stanislas Kita (Teclib')
   @copyright Copyright (c) 2014 GLPI Plugin Renamer Development team
   @license   GPLv3 or (at your option) any later version
              http://www.gnu.org/licenses/gpl.html
   @link      https://forge.indepnet.net/projects/renamer
   @since     2014

   ------------------------------------------------------------------------
 */

/**
 * function to initialize the plugin
 * @global array $PLUGIN_HOOKS
 */
function plugin_init_renamer() {
   global $PLUGIN_HOOKS;

   $plugin = new Plugin();

   $PLUGIN_HOOKS['csrf_compliant']['renamer'] = true;
   $PLUGIN_HOOKS['change_profile']['renamer'] = array('PluginRenamerProfile', 'changeProfile');

   Plugin::registerClass('PluginRenamerInstall');
   Plugin::registerClass('PluginRenamerRenamer');
   Plugin::registerClass('PluginRenamerConfig');
   Plugin::registerClass('PluginRenamerProfile', array('addtabon' => array('Profile')));

   if (Session::getLoginUserID()) {
      if (Session::haveRight("config", UPDATE)) {

         // Add link in GLPI plugins list :
         $PLUGIN_HOOKS['config_page']['renamer'] = "front/config.form.php";

         // add to 'Admin' menu :
         $PLUGIN_HOOKS["menu_toadd"]['renamer'] = array('admin' => 'PluginRenamerMenu');
      }
   }

   if (Session::getLoginUserID() && $plugin->isActivated('renamer')) {

      $PLUGIN_HOOKS['add_javascript']['renamer'] = array('scripts/jquery-picklist.min.js',
                                                         'scripts/renamer.js.php');
      $PLUGIN_HOOKS['add_css']['renamer'] = array( 'css/renamer.css', 
                                                   'css/jquery-picklist.css',
                                                   'jquery-picklist-ie7.css');
   }
}

/**
 *function to define the version for glpi for plugin
 * @return array
 */
function plugin_version_renamer() {
   return array('name'            => __("Renamer", "renamer"),
                'version'         => '0.90-1.0',
                'author'          => 'TECLIB\'',
                'license'         => 'GPLv3',
                'homepage'        => 'https://github.com/TECLIB/renamer',
                'minGlpiVersion'  => '0.85');
}

/**
 * function to check the prerequisites
 * @return boolean
 */
function plugin_renamer_check_prerequisites() {

   if (version_compare(GLPI_VERSION, '0.85', 'lt')) {
      echo "This plugin requires GLPI >= 0.85";
      return false;
   }

   return true;
}


/**
 * function to check the initial configuration
 * @param boolean $verbose
 * @return boolean
 */
function plugin_renamer_check_config($verbose = false) {

   if (true) {
      //your configuration check
      return true;
   }

   if ($verbose) {
      echo _x('plugin', 'Installed / not configured');
   }

   return false;
}
