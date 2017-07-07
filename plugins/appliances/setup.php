<?php
/*
 * @version $Id: setup.php 246 2016-12-05 17:14:42Z yllen $
 -------------------------------------------------------------------------
 LICENSE

 This file is part of Appliances plugin for GLPI.

 Appliances is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Appliances is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Appliances. If not, see <http://www.gnu.org/licenses/>.

 @package   appliances
 @author    Xavier CAILLAUD, Remi Collet, Nelly Mahu-Lasson
 @copyright Copyright (c) 2009-2016 Appliances plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/appliances
 @since     version 2.0
 --------------------------------------------------------------------------
 */

// Init the hooks of the plugins -Needed
function plugin_init_appliances() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['appliances'] = true;

   // Params : plugin name - string type - number - attributes
   Plugin::registerClass('PluginAppliancesAppliance', array('linkuser_types'         => true,
                                                            'linkuser_tech_types'    => true,
                                                            'linkgroup_types'        => true,
                                                            'linkgroup_tech_types'   => true,
                                                            'infocom_types'          => true,
                                                            'document_types'         => true,
                                                            'contract_types'         => true,
                                                            'ticket_types'           => true,
                                                            'helpdesk_visible_types' => true));

   Plugin::registerClass('PluginAppliancesProfile', array('addtabon' => 'Profile'));
   Plugin::registerClass('PluginAppliancesEnvironment');
   Plugin::registerClass('PluginAppliancesApplianceType');
   Plugin::registerClass('PluginAppliancesOptvalue');
   Plugin::registerClass('PluginAppliancesOptvalue_Item');
   Plugin::registerClass('PluginAppliancesRelation');

   if (class_exists('PluginAccountsAccount')) {
      PluginAccountsAccount::registerType('PluginAppliancesAppliance');
   }

   if (class_exists('PluginCertificatesCertificate')) {
      PluginCertificatesCertificate::registerType('PluginAppliancesAppliance');
   }

   if (class_exists('PluginDatabasesDatabase')) {
      PluginDatabasesDatabase::registerType('PluginAppliancesAppliance');
   }

   if (class_exists('PluginDomainsDomain')) {
      PluginDomainsDomain::registerType('PluginAppliancesAppliance');
   }

   if (class_exists('PluginWebapplicationsWebapplication')) {
      PluginWebapplicationsWebapplication::registerType('PluginAppliancesAppliance');
   }

   // Define the type for which we know how to generate PDF, need :
   $PLUGIN_HOOKS['plugin_pdf']['PluginAppliancesAppliance'] = 'PluginAppliancesAppliancePDF';

   $PLUGIN_HOOKS['migratetypes']['appliances'] = 'plugin_datainjection_migratetypes_appliances';

   $PLUGIN_HOOKS['change_profile']['appliances']   = array('PluginAppliancesProfile','initProfile');
   $PLUGIN_HOOKS['assign_to_ticket']['appliances'] = true;
   $PLUGIN_HOOKS['assign_to_ticket_dropdown']['appliances'] = true;

   if (class_exists('PluginAppliancesAppliance')) { // only if plugin activated
      $PLUGIN_HOOKS['item_clone']['appliances']
                                       = array('Profile' => array('PluginAppliancesProfile',
                                                                  'cloneProfile'));
      $PLUGIN_HOOKS['plugin_datainjection_populate']['appliances']
                                       = 'plugin_datainjection_populate_appliances';
   }

   //if glpi is loaded
   if (Session::getLoginUserID()) {

      //if environment plugin is not installed
      $plugin = new Plugin();
      if (!$plugin->isActivated('environment')
         && Session::haveRight("plugin_appliances", READ)) {

         $PLUGIN_HOOKS['menu_toadd']['appliances'] = array('assets' => 'PluginAppliancesMenu');
      }
      $PLUGIN_HOOKS['use_massive_action']['appliances'] = 1;
   }

   // Import from Data_Injection plugin
   $PLUGIN_HOOKS['data_injection']['appliances'] = "plugin_appliances_data_injection_variables";

   // Import webservice
   $PLUGIN_HOOKS['webservices']['appliances'] = 'plugin_appliances_registerMethods';

   // End init, when all types are registered
   $PLUGIN_HOOKS['post_init']['appliances'] = 'plugin_appliances_postinit';
}


// Get the name and the version of the plugin - Needed
function plugin_version_appliances() {

   return array('name'           => __('Appliances', 'appliances'),
                'version'        => '2.2.1',
                'author'         => 'Remi Collet, Xavier Caillaud, Nelly Mahu-Lasson',
                'license'        => 'GPLv3+',
                'homepage'       => 'https://forge.glpi-project.org/projects/appliances',
                'minGlpiVersion' => '0.90');
}


function plugin_appliances_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.90','lt') || version_compare(GLPI_VERSION,'9.2','ge')) {
      echo "This plugin requires GLPI >= 0.90 and GLPI < 9.2";
      return false;
   }
   return true;
}


function plugin_appliances_check_config() {
   return true;
}


function plugin_datainjection_migratetypes_appliances($types) {

   $types[1200] = 'PluginAppliancesAppliance';
   return $types;
}
