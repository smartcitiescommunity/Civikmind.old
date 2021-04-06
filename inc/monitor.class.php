<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2021 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Monitor Class
**/
class Monitor extends CommonDBTM {
   use Glpi\Features\DCBreadcrumb;
   use Glpi\Features\Clonable;

   // From CommonDBTM
   public $dohistory                   = true;
   static protected $forward_entity_to = ['Infocom', 'ReservationItem', 'Item_OperatingSystem', 'NetworkPort',
                                          'Item_SoftwareVersion'];

   static $rightname                   = 'monitor';
   protected $usenotepad               = true;

   public function getCloneRelations() :array {
      return [
         Item_OperatingSystem::class,
         Item_Devices::class,
         Infocom::class,
         Contract_Item::class,
         Document_Item::class,
         Computer_Item::class,
         KnowbaseItem_Item::class
      ];
   }

   /**
    * Name of the type
    *
    * @param $nb  string   number of item in the type
   **/
   static function getTypeName($nb = 0) {
      return _n('Monitor', 'Monitors', $nb);
   }


   /**
    * @see CommonDBTM::useDeletedToLockIfDynamic()
    *
    * @since 0.84
   **/
   function useDeletedToLockIfDynamic() {
      return false;
   }


   function defineTabs($options = []) {

      $ong = [];
      $this->addDefaultFormTab($ong);
      $this->addImpactTab($ong, $options);
      $this->addStandardTab('Item_OperatingSystem', $ong, $options);
      $this->addStandardTab('Item_SoftwareVersion', $ong, $options);
      $this->addStandardTab('Item_Devices', $ong, $options);
      $this->addStandardTab('Computer_Item', $ong, $options);
      $this->addStandardTab('NetworkPort', $ong, $options);
      $this->addStandardTab('Infocom', $ong, $options);
      $this->addStandardTab('Contract_Item', $ong, $options);
      $this->addStandardTab('Document_Item', $ong, $options);
      $this->addStandardTab('KnowbaseItem_Item', $ong, $options);
      $this->addStandardTab('Ticket', $ong, $options);
      $this->addStandardTab('Item_Problem', $ong, $options);
      $this->addStandardTab('Change_Item', $ong, $options);
      $this->addStandardTab('Link', $ong, $options);
      $this->addStandardTab('Notepad', $ong, $options);
      $this->addStandardTab('Reservation', $ong, $options);
      $this->addStandardTab('Domain_Item', $ong, $options);
      $this->addStandardTab('Appliance_Item', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }

   function prepareInputForAdd($input) {

      if (isset($input["id"]) && ($input["id"] > 0)) {
         $input["_oldID"] = $input["id"];
      }
      if (isset($input["size"]) && ($input["size"] == '')) {
         unset($input["size"]);
      }
      unset($input['id']);
      unset($input['withtemplate']);

      return $input;
   }


   function cleanDBonPurge() {

      $this->deleteChildrenAndRelationsFromDb(
         [
            Computer_Item::class,
            Item_Project::class,
         ]
      );

      Item_Devices::cleanItemDeviceDBOnItemDelete($this->getType(), $this->fields['id'],
                                                  (!empty($this->input['keep_devices'])));

   }


   /**
    * Print the monitor form
    *
    * @param $ID        integer  ID of the item
    * @param $options   array
    *     - target filename : where to go when done.
    *     - withtemplate boolean : template or basic item
    *
    * @return boolean item found
    **/
   function showForm($ID, $options = []) {
      global $CFG_GLPI;

      $target       = $this->getFormURL();
      $withtemplate = $this->initForm($ID, $options);
      $this->showFormHeader($options);

      $tplmark = $this->getAutofillMark('name', $options);
      echo "<tr class='tab_bg_1'>";
      //TRANS: %1$s is a string, %2$s a second one without spaces between them : to change for RTL
      echo "<td>".sprintf(__('%1$s%2$s'), __('Name'), $tplmark);
      echo "</td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name",
                             (isset($options['withtemplate']) && ($options['withtemplate'] == 2)),
                             $this->getType(), $this->fields["entities_id"]);
      Html::autocompletionTextField($this, "name", ['value' => $objectName]);
      echo "</td>";
      echo "<td>".__('Status')."</td>";
      echo "<td>";
      State::dropdown([
         'value'     => $this->fields["states_id"],
         'entity'    => $this->fields["entities_id"],
         'condition' => ['is_visible_monitor' => 1]
      ]);
      echo "</td></tr>";

      $this->showDcBreadcrumb();

      echo "<tr class='tab_bg_1'>";
      echo "<td>".Location::getTypeName(1)."</td>";
      echo "<td>";
      Location::dropdown(['value'  => $this->fields["locations_id"],
                               'entity' => $this->fields["entities_id"]]);
      echo "</td>";
      echo "<td>"._n('Type', 'Types', 1)."</td>";
      echo "<td>";
      MonitorType::dropdown(['value' => $this->fields["monitortypes_id"]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Technician in charge of the hardware')."</td>";
      echo "<td>";
      User::dropdown(['name'   => 'users_id_tech',
                           'value'  => $this->fields["users_id_tech"],
                           'right'  => 'own_ticket',
                           'entity' => $this->fields["entities_id"]]);
      echo "</td>";
      echo "<td>".Manufacturer::getTypeName(1)."</td>";
      echo "<td>";
      Manufacturer::dropdown(['value' => $this->fields["manufacturers_id"]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Group in charge of the hardware')."</td>";
      echo "<td>";
      Group::dropdown([
         'name'      => 'groups_id_tech',
         'value'     => $this->fields['groups_id_tech'],
         'entity'    => $this->fields['entities_id'],
         'condition' => ['is_assign' => 1]
      ]);
      echo "</td>";
      echo "<td>"._n('Model', 'Models', 1)."</td>";
      echo "<td>";
      MonitorModel::dropdown(['value' => $this->fields["monitormodels_id"]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Alternate username number')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "contact_num");
      echo "</td>";
      echo "<td>".__('Serial number')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "serial");
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Alternate username')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "contact");
      echo "</td>";

      $tplmark = $this->getAutofillMark('otherserial', $options);
      echo "<td>".sprintf(__('%1$s%2$s'), __('Inventory number'), $tplmark).
           "</td>";
      echo "<td>";
      $objectName = autoName($this->fields["otherserial"], "otherserial",
                             (isset($options['withtemplate']) && ($options['withtemplate'] == 2)),
                             $this->getType(), $this->fields["entities_id"]);
      Html::autocompletionTextField($this, "otherserial", ['value' => $objectName]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".User::getTypeName(1)."</td>";
      echo "<td>";
      User::dropdown(['value'  => $this->fields["users_id"],
                           'entity' => $this->fields["entities_id"],
                           'right'  => 'all']);
      echo "</td>";
      echo "<td>".__('Management type')."</td>";
      echo "<td>";
      Dropdown::showGlobalSwitch($this->fields["id"],
                                 ['withtemplate' => $withtemplate,
                                       'value'        => $this->fields["is_global"],
                                       'management_restrict'
                                                      => $CFG_GLPI["monitors_management_restrict"],
                                       'target'       => $target]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".Group::getTypeName(1)."</td>";
      echo "<td>";
      Group::dropdown([
         'value'     => $this->fields["groups_id"],
         'entity'    => $this->fields["entities_id"],
         'condition' => ['is_itemgroup' => 1]
      ]);
      echo "</td>";
      echo "<td rowspan='3'>" . __('Comments')."</td>";
      echo "<td rowspan='3'>
            <textarea cols='45' rows='10' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Size')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "size");
      echo "\"</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".('Flags')."</td>";
      echo "<td><table>";
      // micro?
      echo "<tr><td width='20%'>".__('Microphone')."</td><td width='30%'>";
      Dropdown::showYesNo("have_micro", $this->fields["have_micro"]);
      // speakers?
      echo "</td><td width='20%'>".__('Speakers')."</td><td width='30%'>";
      Dropdown::showYesNo("have_speaker", $this->fields["have_speaker"]);
      echo "</td></tr>";

      // sub-d?
      echo "<tr><td width='20%'>".__('Sub-D')."</td><td width='30%'>";
      Dropdown::showYesNo("have_subd", $this->fields["have_subd"]);
      // bnc?
      echo "</td><td width='20%'>".__('BNC')."</td><td width='30%'>";
      Dropdown::showYesNo("have_bnc", $this->fields["have_bnc"]);
      echo "</td></tr>";

      // dvi?
      echo "<tr><td>".__('DVI')."</td><td>";
      Dropdown::showYesNo("have_dvi", $this->fields["have_dvi"]);
      // pivot ?
      echo "</td><td>".__('Pivot')."</td><td>";
      Dropdown::showYesNo("have_pivot", $this->fields["have_pivot"]);
      echo "</td></tr>";
      // hdmi?
      echo "<tr><td>".__('HDMI')."</td><td>";
      Dropdown::showYesNo("have_hdmi", $this->fields["have_hdmi"]);
      //Displayport
      echo "</td><td>".__('DisplayPort')."</td><td>";
      Dropdown::showYesNo("have_displayport", $this->fields["have_displayport"]);
      echo "</td></tr>";
      echo "</table></td></tr>";

      $this->showFormButtons($options);

      return true;
   }


   /**
    * Return the linked items (in computers_items)
    *
    * @return array of linked items  like array('Computer' => array(1,2), 'Printer' => array(5,6))
    * @since 0.84.4
   **/
   function getLinkedItems() {
      global $DB;

      $iterator = $DB->request([
         'SELECT' => 'computers_id',
         'FROM'   => 'glpi_computers_items',
         'WHERE'  => [
            'itemtype'  => $this->getType(),
            'items_id'  => $this->fields['id']
         ]
      ]);
      $tab = [];
      while ($data = $iterator->next()) {
         $tab['Computer'][$data['computers_id']] = $data['computers_id'];
      }
      return $tab;
   }


   function getSpecificMassiveActions($checkitem = null) {

      $actions = parent::getSpecificMassiveActions($checkitem);
      if (static::canUpdate()) {
         Computer_Item::getMassiveActionsForItemtype($actions, __CLASS__, 0, $checkitem);
         KnowbaseItem_Item::getMassiveActionsForItemtype($actions, __CLASS__, 0, $checkitem);
      }

      return $actions;
   }


   function rawSearchOptions() {
      $tab = parent::rawSearchOptions();

      $tab[] = [
         'id'                 => '2',
         'table'              => $this->getTable(),
         'field'              => 'id',
         'name'               => __('ID'),
         'massiveaction'      => false,
         'datatype'           => 'number'
      ];

      $tab = array_merge($tab, Location::rawSearchOptionsToAdd());

      $tab[] = [
         'id'                 => '4',
         'table'              => 'glpi_monitortypes',
         'field'              => 'name',
         'name'               => _n('Type', 'Types', 1),
         'datatype'           => 'dropdown'
      ];

      $tab[] = [
         'id'                 => '40',
         'table'              => 'glpi_monitormodels',
         'field'              => 'name',
         'name'               => _n('Model', 'Models', 1),
         'datatype'           => 'dropdown'
      ];

      $tab[] = [
         'id'                 => '31',
         'table'              => 'glpi_states',
         'field'              => 'completename',
         'name'               => __('Status'),
         'datatype'           => 'dropdown',
         'condition'          => ['is_visible_monitor' => 1]
      ];

      $tab[] = [
         'id'                 => '5',
         'table'              => $this->getTable(),
         'field'              => 'serial',
         'name'               => __('Serial number'),
         'datatype'           => 'string',
         'autocomplete'       => true,
      ];

      $tab[] = [
         'id'                 => '6',
         'table'              => $this->getTable(),
         'field'              => 'otherserial',
         'name'               => __('Inventory number'),
         'datatype'           => 'string',
         'autocomplete'       => true,
      ];

      $tab[] = [
         'id'                 => '7',
         'table'              => $this->getTable(),
         'field'              => 'contact',
         'name'               => __('Alternate username'),
         'datatype'           => 'string',
         'autocomplete'       => true,
      ];

      $tab[] = [
         'id'                 => '8',
         'table'              => $this->getTable(),
         'field'              => 'contact_num',
         'name'               => __('Alternate username number'),
         'datatype'           => 'string',
         'autocomplete'       => true,
      ];

      $tab[] = [
         'id'                 => '70',
         'table'              => 'glpi_users',
         'field'              => 'name',
         'name'               => User::getTypeName(1),
         'datatype'           => 'dropdown',
         'right'              => 'all'
      ];

      $tab[] = [
         'id'                 => '71',
         'table'              => 'glpi_groups',
         'field'              => 'completename',
         'name'               => Group::getTypeName(1),
         'condition'          => ['is_itemgroup' => 1],
         'datatype'           => 'dropdown'
      ];

      $tab[] = [
         'id'                 => '19',
         'table'              => $this->getTable(),
         'field'              => 'date_mod',
         'name'               => __('Last update'),
         'datatype'           => 'datetime',
         'massiveaction'      => false
      ];

      $tab[] = [
         'id'                 => '121',
         'table'              => $this->getTable(),
         'field'              => 'date_creation',
         'name'               => __('Creation date'),
         'datatype'           => 'datetime',
         'massiveaction'      => false
      ];

      $tab[] = [
         'id'                 => '16',
         'table'              => $this->getTable(),
         'field'              => 'comment',
         'name'               => __('Comments'),
         'datatype'           => 'text'
      ];

      $tab[] = [
         'id'                 => '11',
         'table'              => $this->getTable(),
         'field'              => 'size',
         'name'               => __('Size'),
         'datatype'           => 'decimal',
         'autocomplete'       => true,
      ];

      $tab[] = [
         'id'                 => '41',
         'table'              => $this->getTable(),
         'field'              => 'have_micro',
         'name'               => __('Microphone'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '42',
         'table'              => $this->getTable(),
         'field'              => 'have_speaker',
         'name'               => __('Speakers'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '43',
         'table'              => $this->getTable(),
         'field'              => 'have_subd',
         'name'               => __('Sub-D'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '44',
         'table'              => $this->getTable(),
         'field'              => 'have_bnc',
         'name'               => __('BNC'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '45',
         'table'              => $this->getTable(),
         'field'              => 'have_dvi',
         'name'               => __('DVI'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '46',
         'table'              => $this->getTable(),
         'field'              => 'have_pivot',
         'name'               => __('Pivot'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '47',
         'table'              => $this->getTable(),
         'field'              => 'have_hdmi',
         'name'               => __('HDMI'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '48',
         'table'              => $this->getTable(),
         'field'              => 'have_displayport',
         'name'               => __('DisplayPort'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '23',
         'table'              => 'glpi_manufacturers',
         'field'              => 'name',
         'name'               => Manufacturer::getTypeName(1),
         'datatype'           => 'dropdown'
      ];

      $tab[] = [
         'id'                 => '24',
         'table'              => 'glpi_users',
         'field'              => 'name',
         'linkfield'          => 'users_id_tech',
         'name'               => __('Technician in charge of the hardware'),
         'datatype'           => 'dropdown',
         'right'              => 'own_ticket'
      ];

      $tab[] = [
         'id'                 => '49',
         'table'              => 'glpi_groups',
         'field'              => 'completename',
         'linkfield'          => 'groups_id_tech',
         'name'               => __('Group in charge of the hardware'),
         'condition'          => ['is_assign' => 1],
         'datatype'           => 'dropdown'
      ];

      $tab[] = [
         'id'                 => '61',
         'table'              => $this->getTable(),
         'field'              => 'template_name',
         'name'               => __('Template name'),
         'datatype'           => 'text',
         'massiveaction'      => false,
         'nosearch'           => true,
         'nodisplay'          => true,
         'autocomplete'       => true,
      ];

      $tab[] = [
         'id'                 => '80',
         'table'              => 'glpi_entities',
         'field'              => 'completename',
         'name'               => Entity::getTypeName(1),
         'massiveaction'      => false,
         'datatype'           => 'dropdown'
      ];

      $tab[] = [
         'id'                 => '82',
         'table'              => $this->getTable(),
         'field'              => 'is_global',
         'name'               => __('Global management'),
         'datatype'           => 'bool',
         'massiveaction'      => false
      ];

      // add objectlock search options
      $tab = array_merge($tab, ObjectLock::rawSearchOptionsToAdd(get_class($this)));

      $tab = array_merge($tab, Notepad::rawSearchOptionsToAdd());

      $tab = array_merge($tab, Datacenter::rawSearchOptionsToAdd(get_class($this)));

      return $tab;
   }


   static function getIcon() {
      return "fas fa-desktop";
   }

}
