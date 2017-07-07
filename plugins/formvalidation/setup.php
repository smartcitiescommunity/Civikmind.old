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


// ----------------------------------------------------------------------
// Original Author of file: Olivier Moron
// ----------------------------------------------------------------------

function plugin_init_formvalidation() {
   global $PLUGIN_HOOKS,$LANG,$CFG_GLPI;

   if( (!isset($_SESSION["glpicronuserrunning"]) || (Session::getLoginUserID() != $_SESSION["glpicronuserrunning"])) && !isset($_SESSION['glpiformvalidationeditmode'])){
      $_SESSION['glpiformvalidationeditmode'] = 0 ;
   }
   $PLUGIN_HOOKS['csrf_compliant']['formvalidation'] = true;

   Plugin::registerClass('PluginFormvalidationPage');

   if (Config::canUpdate()) {
      Plugin::registerClass('PluginFormvalidationUser',
                         array('addtabon'                    => array('Preference', 'User')));

      // Display a menu entry
      $PLUGIN_HOOKS['menu_toadd']['formvalidation'] = array('config' => 'PluginFormvalidationMenu');
   }

   // Add specific files to add to the header : javascript or css
   $PLUGIN_HOOKS['add_javascript']['formvalidation'] = array('js/formvalidation.js');
}


// Get the name and the version of the plugin
function plugin_version_formvalidation() {

   return array('name'           => 'Form Validation',
                'version'        => '0.1.7',
                'author'         => 'Olivier Moron',
                'license'        => 'GPLv2+',
                'homepage'       => 'https://github.com/tomolimo/formvalidation',
                'minGlpiVersion' => '0.85');
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_formvalidation_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.85','lt') ) {
      echo "This plugin requires GLPI >= 0.85";
      return false;
   }
   return true;
}


// Check configuration process for plugin : need to return true if succeeded
// Can display a message only if failure and $verbose is true
function plugin_formvalidation_check_config($verbose=false) {

   return true;
}


?>