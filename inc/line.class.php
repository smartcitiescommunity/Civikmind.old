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

/**
 * @since 9.2
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class Line extends CommonDBTM {
   // From CommonDBTM
   public $dohistory                   = true;

   static $rightname                   = 'line';
   protected $usenotepad               = true;


   static function getTypeName($nb = 0) {
      return _n('Line', 'Lines', $nb);
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
      $this->addStandardTab('Infocom', $ong, $options);
      $this->addStandardTab('Contract_Item', $ong, $options);
      $this->addStandardTab('Document_Item', $ong, $options);
      $this->addStandardTab('Notepad', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }

   /**
    * Print the contact form
    *
    * @param $ID        integer ID of the item
    * @param $options   array of possible options:
    *     - target for the Form
    *     - withtemplate : template or basic item
    *
    * @return void
    **/
   function showForm($ID, $options = []) {

      $rowspan = 3;
      if ($ID > 0) {
         $rowspan++;
      }

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "name");
      echo "</td>";

      echo "<td>".__('Status')."</td>";
      echo "<td>";
      State::dropdown(['value'     => $this->fields["states_id"],
            'entity'    => $this->fields["entities_id"],
            'condition' => ['is_visible_line' => 1]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".Location::getTypeName(1)."</td>";
      echo "<td>";
      Location::dropdown(['value'  => $this->fields["locations_id"],
            'entity' => $this->fields["entities_id"]]);
      echo "</td>";

      echo "<td>".LineType::getTypeName(1)."</td>";
      echo "<td>";
      LineType::dropdown(['value'  => $this->fields["linetypes_id"],
            'entity' => $this->fields["entities_id"]]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Caller number')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "caller_num");
      echo "</td>";

      echo "<td>".__('Caller name')."</td>";
      echo "<td>";
      Html::autocompletionTextField($this, "caller_name");
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      $randDropdown = mt_rand();
      echo "<td><label for='dropdown_users_id$randDropdown'>".User::getTypeName(1)."</label></td>";
      echo "<td>";
      User::dropdown(['value'  => $this->fields["users_id"],
            'entity' => $this->fields["entities_id"],
            'right'  => 'all',
            'rand'   => $randDropdown]);
      echo "</td>";

      $rowspan = 3;

      echo "<td rowspan='$rowspan'>" . __('Comments')."</td>";
      echo "<td rowspan='$rowspan'>
      <textarea cols='45' rows='10' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      $randDropdown = mt_rand();
      echo "<td><label for='dropdown_users_id$randDropdown'>".Group::getTypeName(1)."</label></td>";
      echo "<td>";
      Group::dropdown(['value'  => $this->fields["groups_id"],
            'entity' => $this->fields["entities_id"],
            'right'  => 'all',
            'rand'   => $randDropdown]);
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      $randDropdown = mt_rand();
      echo "<td><label for='dropdown_users_id$randDropdown'>".LineOperator::getTypeName(1)."</label></td>";
      echo "<td>";
      LineOperator::dropdown(['value'  => $this->fields["lineoperators_id"],
            'entity' => $this->fields["entities_id"],
            'right'  => 'all',
            'rand'   => $randDropdown]);
      echo "</td></tr>";

      $this->showFormButtons($options);
      return true;
   }


   function rawSearchOptions() {
      $tab = parent::rawSearchOptions();

      $tab = array_merge($tab, Location::rawSearchOptionsToAdd());

      $tab[] = [
            'id'                 => '2',
            'table'              => $this->getTable(),
            'field'              => 'id',
            'name'               => __('ID'),
            'massiveaction'      => false,
            'datatype'           => 'number'
      ];

      $tab[] = [
            'id'                 => '4',
            'table'              => 'glpi_linetypes',
            'field'              => 'name',
            'name'               => LineType::getTypeName(1),
            'datatype'           => 'dropdown',
      ];

      $tab[] = [
            'id'                 => '16',
            'table'              => $this->getTable(),
            'field'              => 'comment',
            'name'               => __('Comments'),
            'datatype'           => 'text'
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
            'id'                 => '31',
            'table'              => 'glpi_states',
            'field'              => 'completename',
            'name'               => __('Status'),
            'datatype'           => 'dropdown',
            'condition'          => ['is_visible_line' => 1]
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
            'id'                 => '80',
            'table'              => 'glpi_entities',
            'field'              => 'completename',
            'name'               => Entity::getTypeName(1),
            'massiveaction'      => false,
            'datatype'           => 'dropdown'
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
            'id'                 => '184',
            'table'              => 'glpi_lineoperators',
            'field'              => 'name',
            'name'               => LineOperator::getTypeName(1),
            'massiveaction'      => true,
            'datatype'           => 'dropdown'
      ];

      $tab[] = [
            'id'                 => '185',
            'table'              => $this->getTable(),
            'field'              => 'caller_num',
            'name'               => __('Caller number'),
            'datatype'           => 'string',
            'autocomplete'       => true,
      ];

      $tab[] = [
            'id'                 => '186',
            'table'              => $this->getTable(),
            'field'              => 'caller_name',
            'name'               => __('Caller name'),
            'datatype'           => 'string',
            'autocomplete'       => true,
      ];

      return $tab;
   }


   static function getIcon() {
      return "fas fa-phone";
   }
}
