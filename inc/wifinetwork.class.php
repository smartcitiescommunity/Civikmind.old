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

/// Class WifiNetwork
/// since version 0.84
class WifiNetwork extends CommonDropdown {

   public $dohistory          = true;

   static $rightname          = 'internet';

   public $can_be_translated  = false;


   static function getTypeName($nb = 0) {
      return _n('Wifi network', 'Wifi networks', $nb);
   }

   static function getWifiCardVersion() {

      return [''          => '',
                   'a'         => 'a',
                   'a/b'       => 'a/b',
                   'a/b/g'     => 'a/b/g',
                   'a/b/g/n'   => 'a/b/g/n',
                   'a/b/g/n/y' => 'a/b/g/n/y'];
   }


   static function getWifiCardModes() {

      return [''          => Dropdown::EMPTY_VALUE,
                   'ad-hoc'    => __('Ad-hoc'),
                   'managed'   => __('Managed'),
                   'master'    => __('Master'),
                   'repeater'  => __('Repeater'),
                   'secondary' => __('Secondary'),
                   'monitor'   => Monitor::getTypeName(1),
                   'auto'      => __('Automatic')];
   }


   static function getWifiNetworkModes() {

      return [''               => Dropdown::EMPTY_VALUE,
                   'infrastructure' => __('Infrastructure (with access point)'),
                   'ad-hoc'         => __('Ad-hoc (without access point)')];
   }


   function defineTabs($options = []) {

      $ong  = [];
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('NetworkPort', $ong, $options);

      return $ong;
   }


   function getAdditionalFields() {

      return [['name'  => 'essid',
                         'label' => __('ESSID'),
                         'type'  => 'text',
                         'list'  => true],
                   ['name'  => 'mode',
                         'label' => __('Wifi network type'),
                         'type'  => 'wifi_mode',
                         'list'  => true]];
   }


   function displaySpecificTypeField($ID, $field = []) {

      if ($field['type'] == 'wifi_mode') {
         Dropdown::showFromArray($field['name'], self::getWifiNetworkModes(),
                                 ['value' => $this->fields[$field['name']]]);

      }
   }


   function rawSearchOptions() {
      $tab = parent::rawSearchOptions();

      $tab[] = [
         'id'                 => '10',
         'table'              => $this->getTable(),
         'field'              => 'essid',
         'name'               => __('ESSID'),
         'datatype'           => 'string',
         'autocomplete'       => true,
      ];

      return $tab;
   }
}
