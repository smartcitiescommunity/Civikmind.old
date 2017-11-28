<?php
/*
 * @version $Id: hook.php 258 2017-10-10 13:21:54Z yllen $
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
 @copyright Copyright (c) 2009-2017 Appliances plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/appliances
 @since     version 2.0
 --------------------------------------------------------------------------
 */

define("PLUGIN_APPLIANCES_RELATION_LOCATION", 1);

function plugin_appliances_postinit() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['plugin_uninstall_after']['appliances'] = [];
   $PLUGIN_HOOKS['item_purge']['appliances']             = [];

   foreach (PluginAppliancesAppliance::getTypes(true) as $type) {
      $PLUGIN_HOOKS['plugin_uninstall_after']['appliances'][$type]
                                    = ['PluginAppliancesAppliance_Item','cleanForItem'];

      $PLUGIN_HOOKS['item_purge']['appliances'][$type]
                                    = ['PluginAppliancesAppliance_Item','cleanForItem'];

      CommonGLPI::registerStandardTab($type, 'PluginAppliancesAppliance_Item');
   }
}

function plugin_appliances_registerMethods() {
   global $WEBSERVICES_METHOD;

   // Not authenticated method
   $WEBSERVICES_METHOD['appliances.testAppliances']  = ['PluginAppliancesAppliance',
                                                        'methodTestAppliance'];
   // Authenticated method
   $WEBSERVICES_METHOD['appliances.listAppliances']  = ['PluginAppliancesAppliance',
                                                        'methodListAppliances'];

   $WEBSERVICES_METHOD['appliances.addAppliance']    = ['PluginAppliancesAppliance',
                                                        'methodAddAppliance'];

   $WEBSERVICES_METHOD['appliances.deleteAppliance'] = ['PluginAppliancesAppliance',
                                                        'methodDeleteAppliance'];

   $WEBSERVICES_METHOD['appliances.updateAppliance'] = ['PluginAppliancesAppliance',
                                                        'methodUpdateAppliance'];

   $WEBSERVICES_METHOD['appliances.getAppliance']    = ['PluginAppliancesAppliance',
                                                        'methodGetAppliance'];
}


/**
 * @param $types
 *
 * @return mixed
**/
function plugin_appliances_AssignToTicket($types) {

   if (Session::haveRight("plugin_appliances_open_ticket", "1")) {
      $types['PluginAppliancesAppliance'] = _n('Appliance', 'Appliances', 2, 'appliances');
   }
   return $types;
}


/**
 * @return bool
**/
function plugin_appliances_install() {
   global $DB;

   if ($DB->tableExists("glpi_plugin_applicatifs_profiles")) {
      if ($DB->fieldExists("glpi_plugin_applicatifs_profiles","create_applicatifs")) { // version <1.3
         $DB->runFile(GLPI_ROOT ."/plugins/appliances/sql/update-1.3.sql");
      }
   }

   if ($DB->tableExists("glpi_plugin_applicatifs")) {
      if (!$DB->fieldExists("glpi_plugin_applicatifs","recursive")) { // version 1.3
         $DB->runFile(GLPI_ROOT ."/plugins/appliances/sql/update-1.4.sql");
      }
      if (!$DB->fieldExists("glpi_plugin_applicatifs","FK_groups")) { // version 1.4
         $DB->runFile(GLPI_ROOT ."/plugins/appliances/sql/update-1.5.0.sql");
      }
      if (!$DB->fieldExists("glpi_plugin_applicatifs","helpdesk_visible")) { // version 1.5.0
         $DB->runFile(GLPI_ROOT ."/plugins/appliances/sql/update-1.5.1.sql");
      }
      if ($DB->fieldExists("glpi_plugin_applicatifs","state")) { // empty 1.5.0 not in update 1.5.0
         $DB->query("ALTER TABLE `glpi_plugin_applicatifs` DROP `state`");
      }
      if (DbUtils::isIndex("glpi_plugin_applicatifs_optvalues_machines", "optvalue_ID")) { // in empty 1.5.0 not in update 1.5.0
         $DB->query("ALTER TABLE `glpi_plugin_applicatifs_optvalues_machines`
                     DROP KEY `optvalue_ID`");
      }
      $DB->runFile(GLPI_ROOT ."/plugins/appliances/sql/update-1.6.0.sql");

      Plugin::migrateItemType([1200 => 'PluginAppliancesAppliance'],
                              ["glpi_bookmarks", "glpi_bookmarks_users", "glpi_displaypreferences",
                               "glpi_documents_items", "glpi_infocoms", "glpi_logs",
                               "glpi_items_tickets"],
                              ["glpi_plugin_appliances_appliances_items",
                               "glpi_plugin_appliances_optvalues_items"]);

      Plugin::migrateItemType([4450 => "PluginRacksRack"],
                              ["glpi_plugin_appliances_appliances_items"]);
   }

   if (!$DB->tableExists("glpi_plugin_appliances_appliances")) { // not installed
      $DB->runFile(GLPI_ROOT . '/plugins/appliances/sql/empty-2.0.0.sql');

   } else {
      $migration = new Migration(200);

      include_once(GLPI_ROOT."/plugins/appliances/inc/appliance.class.php");
      PluginAppliancesAppliance::updateSchema($migration);

      $migration->executeMigration();

   }
   // required cause autoload don't work for unactive plugin'
   include_once(GLPI_ROOT."/plugins/appliances/inc/profile.class.php");

   PluginAppliancesProfile::initProfile();
   PluginAppliancesProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   $migration = new Migration("2.0.0");
   $migration->dropTable('glpi_plugin_appliances_profiles');

   return true;
}


/**
 * @return bool
**/
function plugin_appliances_uninstall() {
   global $DB;

   $tables = ['glpi_plugin_appliances_appliances',
              'glpi_plugin_appliances_appliances_items',
              'glpi_plugin_appliances_appliancetypes',
              'glpi_plugin_appliances_environments',
              'glpi_plugin_appliances_relations',
              'glpi_plugin_appliances_optvalues',
              'glpi_plugin_appliances_optvalues_items'];

   foreach($tables as $table) {
      $DB->query("DROP TABLE `$table`");
   }

   $itemtypes = ['Document_Item', 'DisplayPreference', 'Bookmark', 'Log', 'Notepad', 'Item_Ticket',
                 'Contract_Item', 'Item_Problem'];
   foreach ($itemtypes as $itemtype) {
      $item = new $itemtype;
      $item->deleteByCriteria(['itemtype' => 'PluginAppliancesAppliance']);
   }

   $query = "DELETE
             FROM `glpi_dropdowntranslations`
             WHERE `itemtype` IN ('PluginAppliancesApplianceType', 'PluginAppliancesEnvironment')";
   $DB->query($query);

   $query = "DELETE
             FROM `glpi_profilerights`
             WHERE `name` IN ('plugin_appliances', 'plugin_appliances_open_ticket')";
   $DB->query($query);

   if ($temp = getItemForItemtype('PluginDatainjectionModel')) {
      $temp->deleteByCriteria(['itemtype'=>'PluginAppliancesAppliance']);
   }
   include_once(GLPI_ROOT."/plugins/appliances/inc/profile.class.php");
   include_once(GLPI_ROOT."/plugins/appliances/inc/menu.class.php");
   //Delete rights associated with the plugin
   $profileRight = new ProfileRight();
   foreach (PluginAppliancesProfile::getAllRights() as $right) {
      $profileRight->deleteByCriteria(['name' => $right['field']]);
   }
   PluginAppliancesMenu::removeRightsFromSession();
   PluginAppliancesProfile::removeRightsFromSession();

   return true;
}


/**
 * Define Dropdown tables to be manage in GLPI :
**/
function plugin_appliances_getDropdown(){

   return ['PluginAppliancesApplianceType'  => __('Type of appliance', 'appliances'),
           'PluginAppliancesEnvironment'    => __('Environment', 'appliances')];
}


/**
 * Define dropdown relations
**/
function plugin_appliances_getDatabaseRelations() {

   $plugin = new Plugin();
   if ($plugin->isActivated("appliances")) {
      return ['glpi_plugin_appliances_appliancetypes' => ['glpi_plugin_appliances_appliances'
                                                            => 'plugin_appliances_appliancetypes_id'],

              'glpi_plugin_appliances_environments'   => ['glpi_plugin_appliances_appliances'
                                                            => 'plugin_appliances_environments_id'],

              'glpi_entities'                         => ['glpi_plugin_appliances_appliances'
                                                            => 'entities_id',
                                                          'glpi_plugin_appliances_appliancetypes'
                                                            => 'entities_id'],

              'glpi_plugin_appliances_appliances'     => ['glpi_plugin_appliances_appliances_items'
                                                             => 'plugin_appliances_appliances_id'],

              '_virtual_device'                       => ['glpi_plugin_appliances_appliances_items'
                                                            => ['items_id', 'itemtype']]];
   }
   return [];
}


////// SEARCH FUNCTIONS ///////(){

/**
 * Define search option for types of the plugins
 *
 * @see Plugin: getAddSearchOptions()
 *
 * @param $itemtype
 *
 * @return array
**/
function plugin_appliances_getAddSearchOptions($itemtype) {

   $sopt = [];
   if (Session::haveRight("plugin_appliances", READ)) {
      if (in_array($itemtype, PluginAppliancesAppliance::getTypes(true))) {
         $sopt[] = ['id'             => '1210',
                    'table'         => 'glpi_plugin_appliances_appliances',
                    'field'         => 'name',
                    'massiveaction' => false,
                    'name'          => sprintf(__('%1$s - %2$s'), __('Appliance', 'appliances'),
                                               __('Name')),
                    'forcegroupby'  => true,
                    'datatype'      => 'itemlink',
                    'itemlink_type' => 'PluginAppliancesAppliance',
                    'joinparams'    => ['beforejoin'
                                         => ['table'      => 'glpi_plugin_appliances_appliances_items',
                                             'joinparams' => ['jointype' => 'itemtype_item']]]];

         $sopt[] = ['id'            => '1211',
                    'table'         => 'glpi_plugin_appliances_appliancetypes',
                    'field'         => 'name',
                    'massiveaction' => false,
                    'name'          => sprintf(__('%1$s - %2$s'), __('Appliance', 'appliances'),
                                                   __('Type')),
                    'forcegroupby'  =>  true,
                    'joinparams'    => ['beforejoin'
                                        => [['table'      => 'glpi_plugin_appliances_appliances',
                                             'joinparams' => ['beforejoin'
                                                               => ['table'
                                                                    => 'glpi_plugin_appliances_appliances_items',
                                                                   'joinparams'
                                                                    => ['jointype' => 'itemtype_item']]]]]]];
      }
      /* TODO: need fix to link relation table
      if ($itemtype == 'Ticket') {
         $sopt[1212]['table']         = 'glpi_plugin_appliances_appliances';
         $sopt[1212]['field']         = 'name';
         $sopt[1212]['linkfield']     = 'items_id';
         $sopt[1212]['datatype']      = 'itemlink';
         $sopt[1212]['massiveaction'] = false;
         $sopt[1212]['name']          = __('Appliance', 'appliances')." - ".
                                        __('Name');
      }
      */
   }
   return $sopt;
}


/**
 * @param $type
 *
 * @return bool
**/
function plugin_appliances_forceGroupBy($type) {

   switch ($type) {
      case 'PluginAppliancesAppliance' :
         return true;
   }
   return false;
}


/**
 * @see Search::giveItem()
 *
 * @param $type
 * @param $ID
 * @param $data      array
 * @param $num
 *
 * @return string
*/
function plugin_appliances_giveItem($type, $ID, array $data, $num) {
   global $DB;

   $searchopt = &Search::getOptions($type);
   $table     = $searchopt[$ID]["table"];
   $field     = $searchopt[$ID]["field"];

   switch ($table.'.'.$field) {
      case "glpi_plugin_appliances_appliances_items.items_id" :
         $appliances_id = $data['id'];
         $query_device  = $DB->request(['SELECT DISTINCT' => 'itemtype',
                                        'FROM'            => 'glpi_plugin_appliances_appliances_items',
                                        'WHERE'           => ['plugin_appliances_appliances_id'
                                                               => $appliances_id],
                                        'ORDER'           => 'itemtype']);
         $number_device  = $query_device->numrows();
         $out            = '';
         if ($number_device > 0) {
            for ($y=0 ; $y < $number_device ; $y++) {
               $column = "name";
               if ($type == 'Ticket') {
                  $column = "id";
               }
               foreach ($query_device as $id => $row) {
                  $type = $row['itemtype'];
               }
               if (!($item = getItemForItemtype($type))) {
                     continue;
               }
               $table = $item->getTable();
               if (!empty($table)) {
                  $query = "SELECT `".$table."`.`id`
                            FROM `glpi_plugin_appliances_appliances_items`, `".$table."`
                            LEFT JOIN `glpi_entities`
                              ON (`glpi_entities`.`id` = `".$table."`.`entities_id`)
                            WHERE `".$table."`.`id`
                                       = `glpi_plugin_appliances_appliances_items`.`items_id`
                                  AND `glpi_plugin_appliances_appliances_items`.`itemtype`
                                       = '".$type."'
                                  AND `glpi_plugin_appliances_appliances_items`.`plugin_appliances_appliances_id`
                                       = '".$appliances_id."'".
                                 getEntitiesRestrictRequest(" AND ", $table, '', '',
                                                            $item->maybeRecursive());

                  if ($item->maybeTemplate()) {
                     $query .= " AND `".$table."`.`is_template` = '0'";
                  }
                  $query .= " ORDER BY `glpi_entities`.`completename`,
                             `$table`.`$column`";

                  if ($result_linked = $DB->request($query)) {
                     if ($result_linked->numrows()) {
                        foreach ($result_linked as $id => $row) {
                           if ($item->getFromDB($row['id'])) {
                              $out .= $item->getTypeName(1)." - ".$item->getLink()."<br>";
                           }
                        }
                     }
                  }
               }
            }
         }
         return $out;

      case 'glpi_plugin_appliances_appliances.name':
         if ($type == 'Ticket') {
            $appliances_id = [];
            if ($data['raw']["ITEM_$num"] != '') {
               $appliances_id = explode('$$$$', $data['raw']["ITEM_$num"]);
            } else {
               $appliances_id = explode('$$$$', $data['raw']["ITEM_".$num."_2"]);
            }
            $ret = [];
            $paAppliance = new PluginAppliancesAppliance();
            foreach ($appliances_id as $ap_id) {
               $paAppliance->getFromDB($ap_id);
               $ret[] = $paAppliance->getLink();
            }
            return implode('<br>', $ret);
         }
         break;

   }
   return "";
}


////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

/**
 * @param $type
 *
 * @return array
**/
function plugin_appliances_MassiveActions($type) {

   if (in_array($type,PluginAppliancesAppliance::getTypes(true))) {
      return ['PluginAppliancesAppliance'.MassiveAction::CLASS_ACTION_SEPARATOR.'plugin_appliances_add_item'
                  => __('Associate to appliance', 'appliances')];
   }
   return array();
}


function plugin_datainjection_populate_appliances() {
   global $INJECTABLE_TYPES;

   $INJECTABLE_TYPES['PluginAppliancesApplianceInjection'] = 'appliances';
}
