<?php
/*
 * -------------------------------------------------------------------------
ARSurveys plugin
Monitors via notifications the results of surveys
Provides bad result notification as well as good result notifications

Copyright (C) 2016 by Raynet SAS a company of A.Raymond Network.

http://www.araymond.com
-------------------------------------------------------------------------

LICENSE

This file is part of ARSurveys plugin for GLPI.

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

// Init the hooks of the plugin
function plugin_init_arsurveys() {
   global $PLUGIN_HOOKS,$LANG,$CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['arsurveys'] = true;
   
   Plugin::registerClass('PluginArsurveysTicketSatisfaction', array ('notificationtemplates_types'  => true));

   $conf=new Config ;
   if ($conf->canUpdate()) {
      Plugin::registerClass('PluginArsurveysConfig', array('addtabon' => 'Config'));
      $PLUGIN_HOOKS['config_page']['arsurveys'] = 'front/config.form.php';
   }

   Plugin::registerClass('PluginArsurveysNotification', array('addtabon' => 'Notification'));

   $PLUGIN_HOOKS['item_update']['arsurveys'] = array(
      'TicketSatisfaction' => array('PluginArsurveysTicketSatisfaction', 'plugin_item_update_arsurveys')
      );
   $PLUGIN_HOOKS['item_purge']['arsurveys'] = array(
      'Notification' => array('PluginArsurveysNotification', 'plugin_item_purge_arsurveys')
      );


}


// Get the name and the version of the plugin - Needed
function plugin_version_arsurveys() {
    global $LANG;
    return array('name'           => $LANG['plugin_arsurveys']["name"],
                 'version'        => '1.4.1',
                 'author'         => 'Olivier Moron',
                 'minGlpiVersion' => '0.83');// For compatibility / no install in version < 0.83
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_arsurveys_check_prerequisites() {

    if (version_compare(GLPI_VERSION,'0.83','lt') ) {
        echo "This plugin requires GLPI >= 0.83";
        return false;
    }
    return true;
}


// Check configuration process for plugin : need to return true if succeeded
// Can display a message only if failure and $verbose is true
function plugin_arsurveys_check_config($verbose=false) {
    global $LANG;

    if (true) { // Your configuration check
        return true;
    }
    if ($verbose) {
        
    }
    return false;
}

