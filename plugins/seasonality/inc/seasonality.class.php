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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginSeasonalitySeasonality extends CommonDBTM {

   static $rightname = 'plugin_seasonality';

   /**
    * functions mandatory
    * getTypeName(), canCreate(), canView()
    * */
   static function getTypeName($nb=0) {
      return _n('Seasonality', 'Seasonalities', $nb, 'seasonality');
   }
   
   /**
    * Show form
    *
    * @param $ID        integer  ID of the item
    * @param $options   array    options used
    */
   function showForm($ID, $options=array()) {
   
      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;<span class='red'>*</span></td>";
      echo "<td>";
      Html::autocompletionTextField($this, "name", array('value' => $this->fields['name']));
      echo "</td>";
      echo "<td>";
      echo __('Urgency')."&nbsp;<span class='red'>*</span>";
      echo "</td>";
      echo "<td>";
      Ticket::dropdownUrgency(array('value' => $this->fields["urgency"]));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Date range', 'seasonality')."&nbsp;<span class='red'>*</span>";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='date_range' id='seasonality_date_range'>";
      // Init date range
      $JS =  "$(function() { 
                  $('#seasonality_date_range').daterangepicker({   
                     dateFormat      : '".$this->getDateFormat()."',
                     applyButtonText : '"._sx('button', 'Post')."',
                     clearButtonText : '".__('Clear', 'seasonality')."', 
                     cancelButtonText: '"._sx('button', 'Cancel')."', 
                     initialText     : '".__('Select date range...', 'seasonality')."',
                     datepickerOptions: {
                        minDate: new Date(".strtotime($this->fields['begin_date']."-12 MONTH")."*1000),
                        maxDate: new Date(".strtotime($this->fields['begin_date']."+12 MONTH")."*1000)
                     },
                     presetRanges: [{
                        text: '".addslashes(__('Today', 'seasonality'))."',
                        dateStart: function() { return moment() },
                        dateEnd: function() { return moment() }
                     }, {
                        text: '".addslashes(__('Tomorrow', 'seasonality'))."',
                        dateStart: function() { return moment().add('days', 1) },
                        dateEnd: function() { return moment().add('days', 1) }
                     }, {
                        text: '".addslashes(__('Next 7 Days', 'seasonality'))."',
                        dateStart: function() { return moment() },
                        dateEnd: function() { return moment().add('days', 6) }
                     }, {
                        text: '".addslashes(__('Next Week', 'seasonality'))."',
                        dateStart: function() { return moment().add('weeks', 1).startOf('week') },
                        dateEnd: function() { return moment().add('weeks', 1).endOf('week') }
                     }],
                  });";
      
      // Predefined dates
      if (!empty($this->fields['begin_date']) && !empty($this->fields['end_date'])) {
         $JS .= " var start = new Date(".strtotime($this->fields['begin_date'])."*1000);
                  var end   = new Date(".strtotime($this->fields['end_date'])."*1000);
                  $('#seasonality_date_range').daterangepicker('setRange', {start: start, end: end});";
      }

      $JS .= "});";
      echo Html::scriptBlock($JS);
      echo "</td>";
      echo "<td>";
      echo __('Recurrent');
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo('periodicity', $this->fields['periodicity']);
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      
      return true;
   }
   
   /**
    * Compute next creation date of a ticket
    *
    * New parameter in  version 0.84 : $calendars_id
    *
    * @param $begin_date      datetime    Begin date of the recurrent ticket
    * @param $end_date        datetime    End date of the recurrent ticket
    * @param $periodicity     timestamp   Periodicity of creation
    * @param $date_ticket     datetime    Date of opening the ticket 
    *
    * @return datetime next creation date
   **/
   function computeNextCreationDate($begin_date, $end_date, $periodicity, $date_ticket) {

      if (empty($begin_date) || ($begin_date == 'NULL')) {
         return 'NULL';
      }
      
      $dates = array($begin_date, $end_date);
      
      if ($periodicity > 0) {
         $yearDiff = date('Y', strtotime($end_date)) - date('Y', strtotime($begin_date));
         $ticketYear = date('Y', strtotime($date_ticket));
         $begin_date = ($ticketYear - $yearDiff) . "-" . date('m-d', strtotime($begin_date));
         $end_date = ($ticketYear) . "-" . date('m-d', strtotime($end_date));

         $begin_date_begin = ($ticketYear) . "-" . date('m-d', strtotime($begin_date));
         $end_date_begin = ($ticketYear + $yearDiff) . "-" . date('m-d', strtotime($end_date));

         if (($begin_date < $date_ticket && $date_ticket < $end_date) || ($begin_date_begin < $date_ticket && $date_ticket < $end_date_begin)) {
            return true;
         } else {
            return false;
         }
      }else{
         if ($begin_date <= $date_ticket && $end_date >= $date_ticket) {
            return true;
         }else{
            return false;
         }
      }
   }
   
  /** 
   * Get date format
   * 
   * @return string
   */
   function getDateFormat(){
      switch ($_SESSION['glpidate_format']) {
         case 1 :
            return 'dd-mm-yy';
         case 2 :
            return 'mm-dd-yy';
         default : 
            return 'yy-mm-dd';
      }
   }
   
  /** 
   * Actions done before add
   * 
   * @param type $input
   * @return type
   */
   function prepareInputForAdd($input) {
      if (isset($input['date_range'])) {
         $dates = json_decode(stripslashes($input['date_range']), true);

         $input['begin_date'] = $dates['start'];
         $input['end_date']   = $dates['end'];
      }
      if (!$this->checkMandatoryFields($input)) {
         return false;
      }
      
      return $input;
   }
   
  /** 
   * Actions done before update
   * 
   * @param type $input
   * @return type
   */
   function prepareInputForUpdate($input) {
      if (isset($input['date_range'])) {
         $dates = json_decode(stripslashes($input['date_range']), true);

         $input['begin_date'] = $dates['start'];
         $input['end_date']   = $dates['end'];
      }
      if (!$this->checkMandatoryFields($input)) {
         return false;
      }

      return $input;
   }
   
  /** 
   * Add search options for an item
   * 
   * @return array
   */
   function getSearchOptions(){
      $tab = parent::getSearchOptions();
      
      $tab[3]['table']          = 'glpi_entities';
      $tab[3]['field']          = 'name';
      $tab[3]['name']           = __('Entity');
      $tab[3]['datatype']       = 'dropdown';
      
      $tab[4]['table']          = $this->getTable();
      $tab[4]['field']          = 'is_recursive';
      $tab[4]['name']           = __('Recursive');
      $tab[4]['datatype']       = 'bool';
 
      $tab[5]['table']          = $this->getTable();
      $tab[5]['field']          = 'begin_date';
      $tab[5]['name']           = __('Begin date');
      $tab[5]['datatype']       = 'datetime';
            
      $tab[6]['table']          = $this->getTable();
      $tab[6]['field']          = 'end_date';
      $tab[6]['name']           = __('End date');
      $tab[6]['datatype']       = 'datetime';
      
      $tab[7]['table']          = 'glpi_itilcategories';
      $tab[7]['field']          = 'name';
      $tab[7]['name']           = __('Category');
      $tab[7]['datatype']       = 'dropdown';
      $tab[7]['forcegroupby']   = true;
      $tab[7]['massiveaction']  = false;
      $tab[7]['joinparams']     = array('beforejoin'
                                         => array('table'      => 'glpi_plugin_seasonality_items',
                                                  'joinparams' => array('jointype'   => 'child')));
      
      $tab[8]['table']          = $this->getTable();
      $tab[8]['field']          = 'periodicity';
      $tab[8]['name']           = __('Recurrent');
      $tab[8]['datatype']       = 'bool';
      
      $tab[9]['table']          = $this->getTable();
      $tab[9]['field']          = 'urgency';
      $tab[9]['name']           = __('Urgency');
      $tab[9]['datatype']       = 'specific';
      $tab[9]['searchtype']     = 'equals';
      $tab[9]['massiveaction']  = true;
      
      return $tab;
   }
   
      /**
    * @param $field
    * @param $name              (default '')
    * @param $values            (default '')
    * @param $options   array
    * */
   static function getSpecificValueToSelect($field, $name = '', $values = '', array $options = array()) {

      if (!is_array($values)) {
         $values = array($field => $values);
      }
      $options['display'] = false;

      switch ($field) {
         case 'urgency' :
            $options['value'] = $values[$field];
            return Ticket::dropdownUrgency(array('name' => $name,'value' => $values[$field], 'display' => false));
      }
      return parent::getSpecificValueToSelect($field, $name, $values, $options);
   }

   /**
    * @param $field
    * @param $values
    * @param $options   array
    * */
   static function getSpecificValueToDisplay($field, $values, array $options = array()) {
      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'urgency':
            return Ticket::getUrgencyName($values[$field]);
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }

   /** 
   * checkMandatoryFields 
   * 
   * @param type $input
   * @return boolean
   */
   function checkMandatoryFields($input){
      $msg     = array();
      $checkKo = false;
      
      $mandatory_fields = array('end_date'          => __('End date'),
                                'begin_date'        => __('Begin date'),
                                'name'              => __('Name'),
                                'urgency'           => __('Urgency'));

      foreach ($input as $key => $value) {
         if (array_key_exists($key, $mandatory_fields)) {
            if (empty($value)) {
               $msg[] = $mandatory_fields[$key];
               $checkKo = true;
            }
         }
      }
      
      if ($checkKo) {
         Session::addMessageAfterRedirect(sprintf(__("Mandatory fields are not filled. Please correct: %s"), implode(', ', $msg)), true, ERROR);
         return false;
      }
      return true;
   }
   
   /**
    * Menu content for headers
    */
   static function getMenuContent() {
      $plugin_page =  PluginSeasonalitySeasonality::getSearchURL(false);
      $menu = array();
      //Menu entry in helpdesk
      $menu['title']                          = PluginSeasonalitySeasonality::getTypeName(2);
      $menu['page']                           = $plugin_page;
      $menu['links']['search']                = $plugin_page;
      
      // Main
      $menu['options']['seasonality']['title']            = PluginSeasonalitySeasonality::getTypeName(1);
      $menu['options']['seasonality']['page']             = PluginSeasonalitySeasonality::getSearchURL(false);
      $menu['options']['seasonality']['links']['add']     = PluginSeasonalitySeasonality::getFormURL(false);
      $menu['options']['seasonality']['links']['search']  = PluginSeasonalitySeasonality::getSearchURL(false);

      return $menu;
   }
   
   
}