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

class PluginSeasonalityItem extends CommonDBTM {

   static $rightname = 'plugin_seasonality';

   /**
    * functions mandatory
    * getTypeName(), canCreate(), canView()
    * */
   static function getTypeName($nb=0) {
      return _n('Seasonality item', 'Seasonality items', $nb, 'seasonality');
   }

  /** 
   * Purge item
   * 
   * @param type $item
   */
   static function purgeItem($item) {
      switch ($item->getType()) {
         case 'PluginSeasonalitySeasonality':
            $temp = new self();
            $temp->deleteByCriteria(array(
                'plugin_seasonality_seasonalities_id' => $item->getField("id")
            ), 1);
      }
   }
   
   /**
    * Display tab for each tickets
    *
    * @param CommonGLPI $item
    * @param int $withtemplate
    * @return array|string
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if (!$withtemplate) {
         switch ($item->getType()) {
            case 'ITILCategory':
               return PluginSeasonalitySeasonality::getTypeName(2);
            case 'PluginSeasonalitySeasonality':
               return _n('Ticket category', 'Ticket categories', 2);
         }
      }

      return '';
   }

   /**
    * Display content for each users
    *
    * @static
    * @param CommonGLPI $item
    * @param int $tabnum
    * @param int $withtemplate
    * @return bool|true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      $seasonality = new self();

      switch ($item->getType()) {
         case 'ITILCategory':
            $seasonality->showForItem($item);
            break;
         case 'PluginSeasonalitySeasonality':
            $seasonality->showForSeasonality($item);
            break;
      }

      return true;
   }

   /**
    * Show for item
    *
    * @param $ID        integer  ID of the item
    * @param $options   array    options used
    */
   function showForItem(ITILCategory $item) {
      
      $canedit = $item->can($item->fields['id'], UPDATE) && $this->canCreate();
      
      $plugin_seasonality_seasonalities_id = 0;
      
      $data = $this->getItemsForCategory($item->fields['id']);
      $used = array();
      foreach ($data as $val) {
         $used[] = $val['plugin_seasonality_seasonalities_id'];
      }

      echo "<form name='form' method='post' action='".Toolbox::getItemTypeFormURL(__CLASS__)."'>";
      echo "<div align='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='2'>".PluginSeasonalitySeasonality::getTypeName(1)."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td class='center'>".PluginSeasonalitySeasonality::getTypeName(1)." <span class='red'>*</span></td>";
      echo "<td class='center'>";
      Dropdown::show('PluginSeasonalitySeasonality', array('used' => $used, 'entity' => $item->getField('entities_id'), 'entity_sons' => $item->getField('is_recursive')));
      echo "</td>";
      echo "</tr>";
      
      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='2'>";
         echo "<input type='submit' name='add' class='submit' value='"._sx('button', 'Add')."' >";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table></div>";
      
      echo "<input type='hidden' name='itilcategories_id' class='submit' value='".$item->fields['id']."' >";
      
      Html::closeForm();
      
      // Show seasonality list
      $this->showSeasonalityList($item);

      return true;
   }
   
   /**
    * Show for category add for seasonaltity
    *
    * @param $ID        integer  ID of the item
    * @param $options   array    options used
    */
   function showForSeasonality(PluginSeasonalitySeasonality $seasonality) {
      
      $canedit = $seasonality->can($seasonality->fields['id'], UPDATE) && $this->canCreate();
      
      $data = $this->getItems($seasonality->fields['id']);
      $used = array();
      foreach ($data as $val) {
         $used[] = $val['itilcategories_id'];
      }

      echo "<form name='form' method='post' action='".Toolbox::getItemTypeFormURL(__CLASS__)."'>";
      echo "<div align='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='2'>".__('Add a category', 'seasonality')."</th></tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td class='center'>".__('Category')." <span class='red'>*</span></td>";
      echo "<td class='center'>";
      Dropdown::show('ITILCategory', array('entity' => $seasonality->getField('entities_id'), 'entity_sons' => $seasonality->getField('is_recursive'), 'used' => $used));
      echo "</td>";
      echo "</tr>";
      
      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='2'>";
         echo "<input type='submit' name='add' class='submit' value='"._sx('button', 'Add')."' >";
         echo "<input type='hidden' name='plugin_seasonality_seasonalities_id' class='submit' value='".$seasonality->fields['id']."' >";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table></div>";
      
      Html::closeForm();
      
       // Show category list
      $this->showCategoryList($seasonality);

      return true;
   }

   /**
    * Function show categories for item
    * 
    * @param type $item
    * @return boolean
    */
   function showCategoryList(PluginSeasonalitySeasonality $item) {
      
      $canedit = ($item->can($item->fields['id'], UPDATE) && $this->canCreate());
      
      $rand = mt_rand();
      
      if (isset($_GET["start"])) {
         $start = $_GET["start"];
      } else {
         $start = 0;
      }
      $data = $this->getItems($item->fields['id'], $start);
      
      if (!empty($data)) {
         echo "<div class='center'>";
         if ($canedit) {
            Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
            $massiveactionparams = array('item' => __CLASS__, 'container' => 'mass'.__CLASS__.$rand);
            Html::showMassiveActions($massiveactionparams);
         }

         Html::printAjaxPager(__('Category'), $start, countElementsInTable($this->getTable(), "`".$this->getTable()."`.`plugin_seasonality_seasonalities_id` = '".$item->fields['id']."'"));
         echo "<table class='tab_cadre_fixehov'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th width='10'>";
         if ($canedit) {
            echo Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         }
         echo "</th>";
         echo "<th>".__('Name')."</th>";
         echo "</tr>";

         foreach ($data as $field) {
            echo "<tr class='tab_bg_2'>";
            echo "<td width='10'>";
            if ($canedit) {
               Html::showMassiveActionCheckBox(__CLASS__, $field['id']);
            }
            echo "</td>";
            // Data
            $item = new ITILCategory();
            $item->getFromDB($field['itilcategories_id']);
            echo "<td>".$item->getLink()."</td>";
            echo "</tr>";
         }
         echo "</table>";
         if ($canedit) {
            $massiveactionparams['ontop'] = false;
            Html::showMassiveActions($massiveactionparams);
            Html::closeForm(); 
         }
         echo "</div>";
      }
   }
   
   /**
    * Function show seasonality for item
    * 
    * @param type $item
    * @return boolean
    */
   function showSeasonalityList(ITILCategory $item) {
      
      $canedit = ($item->can($item->fields['id'], UPDATE) && $this->canCreate());
      
      $rand = mt_rand();
      
      if (isset($_GET["start"])) {
         $start = $_GET["start"];
      } else {
         $start = 0;
      }
      $data = $this->getItemsForCategory($item->fields['id'], $start);
      
      if (!empty($data)) {
         echo "<div class='center'>";
         if ($canedit) {
            Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
            $massiveactionparams = array('item' => __CLASS__, 'container' => 'mass'.__CLASS__.$rand);
            Html::showMassiveActions($massiveactionparams);
         }

         Html::printAjaxPager(PluginSeasonalitySeasonality::getTypeName(2), $start, countElementsInTable($this->getTable(), "`".$this->getTable()."`.`itilcategories_id` = '".$item->fields['id']."'"));
         echo "<table class='tab_cadre_fixehov'>";
         echo "<tr class='tab_bg_1'>";
         echo "<th width='10'>";
         if ($canedit) {
            echo Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         }
         echo "</th>";
         echo "<th>".__('Name')."</th>";
         echo "<th>".__('Urgency')."</th>";
         echo "<th>".__('Begin date')."</th>";
         echo "<th>".__('End date')."</th>";
         echo "<th>".__('Recurrent')."</th>";
         echo "</tr>";

         foreach ($data as $field) {
            echo "<tr class='tab_bg_2'>";
            echo "<td width='10'>";
            if ($canedit) {
               Html::showMassiveActionCheckBox(__CLASS__, $field['assocID']);
            }
            echo "</td>";
            // Data
            $item = new PluginSeasonalitySeasonality();
            $item->getFromDB($field['plugin_seasonality_seasonalities_id']);
            echo "<td>".$item->getLink()."</td>";
            echo "<td>".Ticket::getUrgencyName($field["urgency"])."</td>";
            echo "<td>".Html::convDate($field['begin_date'])."</td>";
            echo "<td>".Html::convDate($field['end_date'])."</td>";
            echo "<td>".Dropdown::getYesNo($field['periodicity'])."</td>";
            echo "</tr>";
         }
         echo "</table>";
         if ($canedit) {
            $massiveactionparams['ontop'] = false;
            Html::showMassiveActions($massiveactionparams);
            Html::closeForm(); 
         }
         echo "</div>";
      }
   }
   
   /**
    * Function get items
    * 
    * @global type $DB
    * @param type $id
    * @param type $start
    * @return type
    */
   function getItems($id, $start=0){
      global $DB;
      
      $output = array();
      
      $query = "SELECT `".$this->getTable()."`.*
          FROM ".$this->getTable()."
          WHERE `".$this->getTable()."`.`plugin_seasonality_seasonalities_id` = '".Toolbox::cleanInteger($id)."'
          LIMIT ".intval($start).",".intval($_SESSION['glpilist_limit']);

      $result = $DB->query($query);
      if ($DB->numrows($result)) {
         while ($data = $DB->fetch_assoc($result)) {
            $output[$data['id']] = $data;
         }
      }
      
      return $output;
   }
   
   /**
    * Function get items
    * 
    * @global type $DB
    * @param type $id
    * @param type $start
    * @param type $condition
    * @return type
    */
   function getItemsForCategory($id, $start=0, $condition='1'){
      global $DB;
      
      $output = array();
      
      $query = "SELECT `".$this->getTable()."`.*, 
                        `".$this->getTable()."`.id as assocID,
                       `glpi_plugin_seasonality_seasonalities`.*
                FROM ".$this->getTable()."
                LEFT JOIN `glpi_plugin_seasonality_seasonalities`
                  ON (`glpi_plugin_seasonality_seasonalities`.`id` = `".$this->getTable()."`.`plugin_seasonality_seasonalities_id`)
                WHERE `".$this->getTable()."`.`itilcategories_id` = '".Toolbox::cleanInteger($id)."'
                AND $condition
                LIMIT ".intval($start).",".intval($_SESSION['glpilist_limit']);

      $result = $DB->query($query);
      if ($DB->numrows($result)) {
         while ($data = $DB->fetch_assoc($result)) {
            $output[$data['id']] = $data;
         }
      }
      
      return $output;
   }
   
      
   /**
    * Get urgency from ticket category
    * 
    * @param type $itilcategories_id
    * @param type $tickets_id
    * @param type $date
    * @param type $type
    * @param type $entities_id
    * @return type
    */
   function getUrgencyFromCategory($itilcategories_id, $tickets_id, $date, $type, $entities_id) {
      
      // Default values
      $error              = 1;
      $urgency_name       = null;
      $urgency_id         = 0;
      $seasonalities_link = null;
      $seasonality        = new PluginSeasonalitySeasonality();
      $default_urgency    = 3;
      $default_impact     = 3;
      $default_priority   = Ticket::computePriority(3, 3);

      $ticket = new Ticket();

      // If template load urgency, DO NOT load seasonality
      if ($tickets_id > 0) {
         $ticket->getFromDB($tickets_id);
         $tt = $ticket->getTicketTemplateToUse(0, $ticket->fields['type'], $ticket->fields['itilcategories_id'], $ticket->fields['entities_id']);
      } else {
         $tt = $ticket->getTicketTemplateToUse(0, $type, $itilcategories_id, $entities_id);
      }
      if (isset($tt->predefined) && count($tt->predefined)) {
         if (isset($tt->predefined['impact'])) {
            $default_impact = $tt->predefined['impact'];
         }
         if (isset($tt->predefined['urgency'])) {
            $default_urgency = $tt->predefined['urgency'];
         }
         if (isset($tt->predefined['priority'])) {
            $default_priority = $tt->predefined['priority'];
         }
      }

      if (empty($date)) {
         $date = date('Y-m-d');
      }

      // Load ticket values if possible
      if ($tickets_id > 0) {
         if ($itilcategories_id == 0) {
            $itilcategories_id = $ticket->fields['itilcategories_id'];
         }
         if (empty($date)) {
            $date = $ticket->fields['date'];
         }
         if (isset($ticket->fields['urgency'])) {
            $default_urgency = $ticket->fields['urgency'];
         }
         if (isset($ticket->fields['impact'])) {
            $default_impact   = $ticket->fields['impact'];
         }
         if (isset($ticket->fields['priority'])) {
            $default_priority = $ticket->fields['priority'];
         }
      }

      // Find correct seasonality for category
      $datas = $this->getItemsForCategory($itilcategories_id);
      if (!empty($datas)) {
         foreach($datas as $data){
            if ($seasonality->computeNextCreationDate($data['begin_date'], $data['end_date'], $data['periodicity'], $date)) {
               $urgency_name       = Ticket::getUrgencyName($data["urgency"]);
               $urgency_id         = $data["urgency"];

               $seasonality->getFromDB($data["plugin_seasonality_seasonalities_id"]);
               if ($_SESSION['glpiactiveprofile']['interface'] == 'central' && self::canUpdate()) {
                  $seasonalities_link = "<div id='seasonalities_link'>".$seasonality->getLink(array('linkoption' => 'target="_blank"'))."</div>";
               } else {
                  $seasonalities_link = "<div id='seasonalities_link'>".$seasonality->fields['name']."</div>";
               }
               $error = 0; 
               break;
            }
         }
      }
      
      return array('error'              => $error,
                   'template'           => 0,
                   'seasonalities_link' => $seasonalities_link,
                   'urgency_id'         => $urgency_id,
                   'urgency_name'       => $urgency_name,
                   'default_urgency'    => $default_urgency,
                   'default_impact'     => $default_impact,
                   'default_priority'   => $default_priority);
   }
   
   /**
    * Show form
    *
    * @param $ID        integer  ID of the item
    * @param $options   array    options used
    */
   function showForm($ID=0, $options=array()){
      $this->getFromDB($ID);
      
      $seasonality = new PluginSeasonalitySeasonality();
      $seasonality->showForm($this->getID(), $options);
   }

   /** 
   * Actions done before add
   * 
   * @param type $input
   * @return type
   */
   function prepareInputForAdd($input) {
      if (!$this->checkMandatoryFields($input) || !$this->checkItemDate($input)) {
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
   function getAddSearchOptions(){
      $tab = array();
      
      $tab[180]['table']          = 'glpi_plugin_seasonality_seasonalities';
      $tab[180]['field']          = 'name';
      $tab[180]['name']           = self::getTypeName(2);
      $tab[180]['datatype']       = 'dropdown';
      $tab[180]['forcegroupby']   = true;
      $tab[180]['massiveaction']  = false;
      $tab[180]['displaytype']    = 'relation';
      $tab[180]['relationclass']  = 'PluginSeasonalityItem';
      $tab[180]['joinparams']     = array('beforejoin'
                                            => array('table'      => 'glpi_plugin_seasonality_items',
                                                     'joinparams' => array('jointype'   => 'child')));
            
      return $tab;
   }
   
  /** 
   * Add search options for an item
   * 
   * @return array
   */
   function getSearchOptions(){
      $tab = array();
      
      $tab[3]['table']          = $this->getTable();
      $tab[3]['field']          = 'itilcategories_id';
      $tab[3]['name']           = 'Itilcategories id';
      $tab[3]['datatype']       = 'dropdown';
      $tab[3]['massiveaction']  = false;
      $tab[3]['search']         = false;
      
      $tab[4]['table']          = $this->getTable();
      $tab[4]['field']          = 'plugin_seasonality_seasonalities_id';
      $tab[4]['name']           = 'Seasonalities id';
      $tab[4]['datatype']       = 'dropdown';
      $tab[4]['massiveaction']  = false;
      $tab[4]['search']         = false;
      
      return $tab;
   }
   
    /**
    * Massive actions to be added
    * 
    * @param $input array of input datas
    *
    * @return array of results (nbok, nbko, nbnoright counts)
    **/
   function massiveActions($type){
      
      $prefix = $this->getType().MassiveAction::CLASS_ACTION_SEPARATOR;
      
      switch ($type) {
         case "ITILCategory":
            $output = array();
            if ($this->canCreate()) {
               $output = array (
                  $prefix."add_seasonality"    => __('Add seasonality', 'seasonality'),
                  $prefix."delete_seasonality" => __('Delete seasonality', 'seasonality')
               );
            }
            return $output;
      }
   }
   
   /**
    * Massive actions display
    * 
    * @param $input array of input datas
    *
    * @return array of results (nbok, nbko, nbnoright counts)
    * */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      
      $itemtype = $ma->getItemtype(false);
      $seasonality = new PluginSeasonalitySeasonality();

      switch ($itemtype) {
         case 'ITILCategory':
            switch ($ma->getAction()) {
               case "add_seasonality":
                  if ($seasonality->canUpdate()){
                     Dropdown::show('PluginSeasonalitySeasonality', array('entity' => $_SESSION['glpiactiveentities']));
                     echo "<br><br>";
                  }
                  break;
                  
               case "delete_seasonality":
                  if ($seasonality->canUpdate()){
                     Dropdown::show('PluginSeasonalitySeasonality', array('entity' => $_SESSION['glpiactiveentities']));
                     echo "<br><br>";
                  }
                  break;
            }
            return parent::showMassiveActionsSubForm($ma);
      }
   }
   
   /**
    * @since version 0.85
    *
    * @see CommonDBTM::processMassiveActionsForOneItemtype()
   **/
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {
      $input = $ma->getInput();
      $seasonalityItem = new self();
      
      foreach ($ids as $key => $val) {
         if ($item->can($key, UPDATE) && $seasonalityItem->canUpdate()) {
            $result = false;
            switch ($ma->getAction()) {
               case "add_seasonality":
                  if ($key) {
                     $result = $seasonalityItem->add(array('plugin_seasonality_seasonalities_id' => $input['plugin_seasonality_seasonalities_id'], 
                                                           'itilcategories_id'                   => $val));
                  }
                  break;
                  
               case "delete_seasonality":
                  if ($key) {
                     $result = $seasonalityItem->deleteByCriteria(array('plugin_seasonality_seasonalities_id' => $input['plugin_seasonality_seasonalities_id'], 
                                                                        'itilcategories_id'                   => $val));
                  }
                  break;

               default :
                  return parent::doSpecificMassiveActions($ma->POST);
            }

            if ($result) {
               $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
            } else {
               $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
               $ma->addMessage($item->getErrorMessage(ERROR_ON_ACTION));
            }

         } else {
            $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_NORIGHT);
            $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
         }
      }
   }

      
  /** 
   * Forbidden massive actions
   * 
   * @return array
   */
   function getForbiddenStandardMassiveAction() {

      $forbidden = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      
      return $forbidden;
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
      
      $mandatory_fields = array('itilcategories_id'                    => __('Category'), 
                                'plugin_seasonality_seasonalities_id'  => _n('Seasonality', 'Seasonalities', 1, 'seasonality'));

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
   * Check if items have not same dates
   * 
   * @param type $input
   * @return type
   */
   function checkItemDate($input) {
      $datas = $this->getItemsForCategory($input['itilcategories_id']);

      $seasonality = new PluginSeasonalitySeasonality();
      $seasonality->getFromDB($input['plugin_seasonality_seasonalities_id']);
      
      foreach ($datas as $data) {
         if($seasonality->fields['begin_date'] >= $data['begin_date'] && $seasonality->fields['end_date'] <= $data['end_date']){
            Session::addMessageAfterRedirect(__("Cannot add two seasonalities with same date interval", "seasonality"), true, ERROR);
            return false;
         }
      }
      
      return true;
   }
   
}