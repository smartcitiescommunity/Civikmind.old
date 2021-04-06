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

/// Class DeviceDrive
class DeviceDrive extends CommonDevice {

   static protected $forward_entity_to = ['Item_DeviceDrive', 'Infocom'];

   static function getTypeName($nb = 0) {
      return _n('Drive', 'Drives', $nb);
   }


   function getAdditionalFields() {

      return array_merge(parent::getAdditionalFields(),
                         [['name'  => 'is_writer',
                                     'label' => __('Writing ability'),
                                     'type'  => 'bool'],
                               ['name'  => 'speed',
                                     'label' => __('Speed'),
                                     'type'  => 'text'],
                               ['name'  => 'interfacetypes_id',
                                     'label' => __('Interface'),
                                     'type'  => 'dropdownValue'],
                               ['name'  => 'devicedrivemodels_id',
                                     'label' => _n('Model', 'Models', 1),
                                     'type'  => 'dropdownValue']]);
   }


   function rawSearchOptions() {
      $tab = parent::rawSearchOptions();

      $tab[] = [
         'id'                 => '12',
         'table'              => $this->getTable(),
         'field'              => 'is_writer',
         'name'               => __('Writing ability'),
         'datatype'           => 'bool'
      ];

      $tab[] = [
         'id'                 => '13',
         'table'              => $this->getTable(),
         'field'              => 'speed',
         'name'               => __('Speed'),
         'datatype'           => 'string',
         'autocomplete'       => true,
      ];

      $tab[] = [
         'id'                 => '14',
         'table'              => 'glpi_interfacetypes',
         'field'              => 'name',
         'name'               => __('Interface'),
         'datatype'           => 'dropdown'
      ];

      $tab[] = [
         'id'                 => '15',
         'table'              => 'glpi_devicedrivemodels',
         'field'              => 'name',
         'name'               => _n('Model', 'Models', 1),
         'datatype'           => 'dropdown'
      ];

      return $tab;
   }


   static function getHTMLTableHeader($itemtype, HTMLTableBase $base,
                                      HTMLTableSuperHeader $super = null,
                                      HTMLTableHeader $father = null, array $options = []) {

      $column = parent::getHTMLTableHeader($itemtype, $base, $super, $father, $options);

      if ($column == $father) {
         return $father;
      }

      switch ($itemtype) {
         case 'Computer' :
            Manufacturer::getHTMLTableHeader(__CLASS__, $base, $super, $father, $options);
            $base->addHeader('devicedrive_writer', __('Writing ability'), $super, $father);
            $base->addHeader('devicedrive_speed', __('Speed'), $super, $father);
            InterfaceType::getHTMLTableHeader(__CLASS__, $base, $super, $father, $options);
            break;
      }

   }


   function getHTMLTableCellForItem(HTMLTableRow $row = null, CommonDBTM $item = null,
                                    HTMLTableCell $father = null, array $options = []) {

      $column = parent::getHTMLTableCellForItem($row, $item, $father, $options);

      if ($column == $father) {
         return $father;
      }

      switch ($item->getType()) {
         case 'Computer' :
            Manufacturer::getHTMLTableCellsForItem($row, $this, null, $options);
            if ($this->fields["is_writer"]) {
               $row->addCell($row->getHeaderByName('devicedrive_writer'),
                             Dropdown::getYesNo($this->fields["is_writer"]), $father);
            }

            if ($this->fields["speed"]) {
               $row->addCell($row->getHeaderByName('devicedrive_speed'),
                             $this->fields["speed"], $father);
            }

            InterfaceType::getHTMLTableCellsForItem($row, $this, null, $options);
      }
   }


   /**
    * Criteria used for import function
    *
    * @see CommonDevice::getImportCriteria()
    *
    * @since 0.84
   **/
   function getImportCriteria() {

      return ['designation'       => 'equal',
                   'manufacturers_id'  => 'equal',
                   'interfacetypes_id' => 'equal'];
   }


   static function getIcon() {
      return "fas fa-hdd";
   }
}
