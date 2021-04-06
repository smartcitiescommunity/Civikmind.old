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
   die("Sorry. You can't access directly to this file");
}

/**
 * Item_Ticket Class
 *
 *  Relation between Tickets and Items
**/
class Item_Ticket extends CommonItilObject_Item {


   // From CommonDBRelation
   static public $itemtype_1          = 'Ticket';
   static public $items_id_1          = 'tickets_id';

   static public $itemtype_2          = 'itemtype';
   static public $items_id_2          = 'items_id';
   static public $checkItem_2_Rights  = self::HAVE_VIEW_RIGHT_ON_ITEM;



   /**
    * @since 0.84
   **/
   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }


   /**
    * @since 0.85.5
    * @see CommonDBRelation::canCreateItem()
   **/
   function canCreateItem() {

      $ticket = new Ticket();
      // Not item linked for closed tickets
      if ($ticket->getFromDB($this->fields['tickets_id'])
          && in_array($ticket->fields['status'], $ticket->getClosedStatusArray())) {
         return false;
      }

      if ($ticket->canUpdateItem()) {
         return true;
      }

      return parent::canCreateItem();
   }


   function post_addItem() {

      $ticket = new Ticket();
      $input  = ['id'            => $this->fields['tickets_id'],
                      'date_mod'      => $_SESSION["glpi_currenttime"],
                      '_donotadddocs' => true];

      if (!isset($this->input['_do_notif']) || $this->input['_do_notif']) {
         $input['_forcenotif'] = true;
      }
      if (isset($this->input['_disablenotif']) && $this->input['_disablenotif']) {
         $input['_disablenotif'] = true;
      }

      $ticket->update($input);
      parent::post_addItem();
   }


   function post_purgeItem() {

      $ticket = new Ticket();
      $input = ['id'            => $this->fields['tickets_id'],
                     'date_mod'      => $_SESSION["glpi_currenttime"],
                     '_donotadddocs' => true];

      if (!isset($this->input['_do_notif']) || $this->input['_do_notif']) {
         $input['_forcenotif'] = true;
      }
      $ticket->update($input);

      parent::post_purgeItem();
   }


   function prepareInputForAdd($input) {

      // Avoid duplicate entry
      if (countElementsInTable($this->getTable(), ['tickets_id' => $input['tickets_id'],
                                                   'itemtype'   => $input['itemtype'],
                                                   'items_id'   => $input['items_id']]) > 0) {
         return false;
      }

      $ticket = new Ticket();
      $ticket->getFromDB($input['tickets_id']);

      // Get item location if location is not already set in ticket
      if (empty($ticket->fields['locations_id'])) {
         if (($input["items_id"] > 0) && !empty($input["itemtype"])) {
            if ($item = getItemForItemtype($input["itemtype"])) {
               if ($item->getFromDB($input["items_id"])) {
                  if ($item->isField('locations_id')) {
                     $ticket->fields['_locations_id_of_item'] = $item->fields['locations_id'];

                     // Process Business Rules
                     $rules = new RuleTicketCollection($ticket->fields['entities_id']);

                     $ticket->fields = $rules->processAllRules(Toolbox::stripslashes_deep($ticket->fields),
                                                Toolbox::stripslashes_deep($ticket->fields),
                                                ['recursive' => true]);

                     unset($ticket->fields['_locations_id_of_item']);
                     $ticket->updateInDB(['locations_id']);
                  }
               }
            }
         }
      }

      return parent::prepareInputForAdd($input);
   }


   /**
    * Print the HTML ajax associated item add
    *
    * @param $ticket Ticket object
    * @param $options   array of possible options:
    *    - id                  : ID of the ticket
    *    - _users_id_requester : ID of the requester user
    *    - items_id            : array of elements (itemtype => array(id1, id2, id3, ...))
    *
    * @return void
   **/
   static function itemAddForm(Ticket $ticket, $options = []) {
      global $CFG_GLPI;

      $params = ['id'                  => (isset($ticket->fields['id'])
                                                && $ticket->fields['id'] != '')
                                                   ? $ticket->fields['id']
                                                   : 0,
                      '_users_id_requester' => 0,
                      'items_id'            => [],
                      'itemtype'            => '',
                      '_canupdate'          => false];

      $opt = [];

      foreach ($options as $key => $val) {
         if (!empty($val)) {
            $params[$key] = $val;
         }
      }

      if (!$ticket->can($params['id'], READ)) {
         return false;
      }

      $canedit = ($ticket->can($params['id'], UPDATE)
                  && $params['_canupdate']);

      // Ticket update case
      if ($params['id'] > 0) {
         // Get requester
         $class        = new $ticket->userlinkclass();
         $tickets_user = $class->getActors($params['id']);
         if (isset($tickets_user[CommonITILActor::REQUESTER])
             && (count($tickets_user[CommonITILActor::REQUESTER]) == 1)) {
            foreach ($tickets_user[CommonITILActor::REQUESTER] as $user_id_single) {
               $params['_users_id_requester'] = $user_id_single['users_id'];
            }
         }

         // Get associated elements for ticket
         $used = self::getUsedItems($params['id']);
         $usedcount = 0;
         foreach ($used as $itemtype => $items) {
            foreach ($items as $items_id) {
               if (!isset($params['items_id'][$itemtype])
                   || !in_array($items_id, $params['items_id'][$itemtype])) {
                  $params['items_id'][$itemtype][] = $items_id;
               }
               ++$usedcount;
            }
         }
      }

      // Get ticket template
      $tt = new TicketTemplate();
      if (isset($options['_tickettemplate'])) {
         $tt                  = $options['_tickettemplate'];
         if (isset($tt->fields['id'])) {
            $opt['templates_id'] = $tt->fields['id'];
         }
      } else if (isset($options['templates_id'])) {
         $tt->getFromDBWithData($options['templates_id']);
         if (isset($tt->fields['id'])) {
            $opt['templates_id'] = $tt->fields['id'];
         }
      }

      $rand  = mt_rand();
      $count = 0;

      echo "<div id='itemAddForm$rand'>";

      // Show associated item dropdowns
      if ($canedit) {
         echo "<div style='float:left'>";
         $p = ['used'       => $params['items_id'],
                    'rand'       => $rand,
                    'tickets_id' => $params['id']];
         // My items
         if ($params['_users_id_requester'] > 0) {
            Item_Ticket::dropdownMyDevices($params['_users_id_requester'], $ticket->fields["entities_id"], $params['itemtype'], 0, $p);
         }
         // Global search
         Item_Ticket::dropdownAllDevices("itemtype", $params['itemtype'], 0, 1, $params['_users_id_requester'], $ticket->fields["entities_id"], $p);
         echo "<span id='item_ticket_selection_information$rand'></span>";
         echo "</div>";

         // Add button
         echo "<a href='javascript:itemAction$rand(\"add\");' class='vsubmit' style='float:left'>"._sx('button', 'Add')."</a>";
      }

      // Display list
      echo "<div style='clear:both;'>";

      if (!empty($params['items_id'])) {
         // No delete if mandatory and only one item
         $delete = $ticket->canAddItem(__CLASS__);
         $cpt = 0;
         foreach ($params['items_id'] as $itemtype => $items) {
            $cpt += count($items);
         }

         if ($cpt == 1 && isset($tt->mandatory['items_id'])) {
            $delete = false;
         }
         foreach ($params['items_id'] as $itemtype => $items) {
            foreach ($items as $items_id) {
               $count++;
               echo self::showItemToAdd(
                  $params['id'],
                  $itemtype,
                  $items_id,
                  [
                     'rand'      => $rand,
                     'delete'    => $delete,
                     'visible'   => ($count <= 5)
                  ]
               );
            }
         }
      }

      if ($count == 0) {
         echo "<input type='hidden' value='0' name='items_id'>";
      }

      if ($params['id'] > 0 && $usedcount != $count) {
         $count_notsaved = $count - $usedcount;
         echo "<i>" . sprintf(_n('%1$s item not saved', '%1$s items not saved', $count_notsaved), $count_notsaved)  . "</i>";
      }
      if ($params['id'] > 0 && $usedcount > 5) {
         echo "<i><a href='".$ticket->getFormURLWithID($params['id'])."&amp;forcetab=Item_Ticket$1'>"
                  .__('Display all items')." (".$usedcount.")</a></i>";
      }
      echo "</div>";

      foreach (['id', '_users_id_requester', 'items_id', 'itemtype', '_canupdate'] as $key) {
         $opt[$key] = $params[$key];
      }

      $js  = " function itemAction$rand(action, itemtype, items_id) {";
      $js .= "    $.ajax({
                     url: '".$CFG_GLPI['root_doc']."/ajax/itemTicket.php',
                     dataType: 'html',
                     data: {'action'     : action,
                            'rand'       : $rand,
                            'params'     : ".json_encode($opt).",
                            'my_items'   : $('#dropdown_my_items$rand').val(),
                            'itemtype'   : (itemtype === undefined) ? $('#dropdown_itemtype$rand').val() : itemtype,
                            'items_id'   : (items_id === undefined) ? $('#dropdown_add_items_id$rand').val() : items_id},
                     success: function(response) {";
      $js .= "          $(\"#itemAddForm$rand\").replaceWith(response);";
      $js .= "       }";
      $js .= "    });";
      $js .= " }";
      echo Html::scriptBlock($js);
      echo "</div>";
   }


   static function showItemToAdd($tickets_id, $itemtype, $items_id, $options) {
      $params = [
         'rand'      => mt_rand(),
         'delete'    => true,
         'visible'   => true
      ];

      foreach ($options as $key => $val) {
         $params[$key] = $val;
      }

      $result = "";

      if ($item = getItemForItemtype($itemtype)) {
         if ($params['visible']) {
            $item->getFromDB($items_id);
            $result =  "<div id='{$itemtype}_$items_id'>";
            $result .= $item->getTypeName(1)." : ".$item->getLink(['comments' => true]);
            $result .= Html::hidden("items_id[$itemtype][$items_id]", ['value' => $items_id]);
            if ($params['delete']) {
               $result .= " <span class='fa fa-times-circle pointer' onclick=\"itemAction".$params['rand']."('delete', '$itemtype', '$items_id');\"></span>";
            }
            $result .= "</div>";
         } else {
            $result .= Html::hidden("items_id[$itemtype][$items_id]", ['value' => $items_id]);
         }
      }

      return $result;
   }

   /**
    * Print the HTML array for Items linked to a ticket
    *
    * @param $ticket Ticket object
    *
    * @return void
   **/
   static function showForTicket(Ticket $ticket) {
      $instID = $ticket->fields['id'];

      if (!$ticket->can($instID, READ)) {
         return false;
      }

      $canedit = $ticket->canAddItem($instID);
      $rand    = mt_rand();

      $types_iterator = self::getDistinctTypes($instID);
      $number = count($types_iterator);

      if ($canedit
          && !in_array($ticket->fields['status'], array_merge($ticket->getClosedStatusArray(),
                                                              $ticket->getSolvedStatusArray()))) {
         echo "<div class='firstbloc'>";
         echo "<form name='ticketitem_form$rand' id='ticketitem_form$rand' method='post'
                action='".Toolbox::getItemTypeFormURL(__CLASS__)."'>";

         echo "<table class='tab_cadre_fixe'>";
         echo "<tr class='tab_bg_2'><th colspan='2'>".__('Add an item')."</th></tr>";

         echo "<tr class='tab_bg_1'><td>";
         // Select hardware on creation or if have update right
         $class        = new $ticket->userlinkclass();
         $tickets_user = $class->getActors($instID);
         $dev_user_id = 0;
         if (isset($tickets_user[CommonITILActor::REQUESTER])
                 && (count($tickets_user[CommonITILActor::REQUESTER]) == 1)) {
            foreach ($tickets_user[CommonITILActor::REQUESTER] as $user_id_single) {
               $dev_user_id = $user_id_single['users_id'];
            }
         }

         if ($dev_user_id > 0) {
            self::dropdownMyDevices($dev_user_id, $ticket->fields["entities_id"], null, 0, ['tickets_id' => $instID]);
         }

         $used = self::getUsedItems($instID);
         self::dropdownAllDevices("itemtype", null, 0, 1, $dev_user_id, $ticket->fields["entities_id"], ['tickets_id' => $instID, 'used' => $used, 'rand' => $rand]);
         echo "<span id='item_ticket_selection_information$rand'></span>";
         echo "</td><td class='center' width='30%'>";
         echo "<input type='submit' name='add' value=\""._sx('button', 'Add')."\" class='submit'>";
         echo "<input type='hidden' name='tickets_id' value='$instID'>";
         echo "</td></tr>";
         echo "</table>";
         Html::closeForm();
         echo "</div>";
      }

      echo "<div class='spaced'>";
      if ($canedit && $number) {
         Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
         $massiveactionparams = ['container' => 'mass'.__CLASS__.$rand];
         Html::showMassiveActions($massiveactionparams);
      }
      echo "<table class='tab_cadre_fixehov'>";
      $header_begin  = "<tr>";
      $header_top    = '';
      $header_bottom = '';
      $header_end    = '';
      if ($canedit && $number) {
         $header_top    .= "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         $header_top    .= "</th>";
         $header_bottom .= "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         $header_bottom .= "</th>";
      }
      $header_end .= "<th>"._n('Type', 'Types', 1)."</th>";
      $header_end .= "<th>".Entity::getTypeName(1)."</th>";
      $header_end .= "<th>".__('Name')."</th>";
      $header_end .= "<th>".__('Serial number')."</th>";
      $header_end .= "<th>".__('Inventory number')."</th>";
      echo "<tr>";
      echo $header_begin.$header_top.$header_end;

      $totalnb = 0;
      while ($row = $types_iterator->next()) {
         $itemtype = $row['itemtype'];
         if (!($item = getItemForItemtype($itemtype))) {
            continue;
         }

         if (in_array($itemtype, $_SESSION["glpiactiveprofile"]["helpdesk_item_type"])) {
            $iterator = self::getTypeItems($instID, $itemtype);
            $nb = count($iterator);

            $prem = true;
            while ($data = $iterator->next()) {
               $name = $data["name"];
               if ($_SESSION["glpiis_ids_visible"]
                   || empty($data["name"])) {
                  $name = sprintf(__('%1$s (%2$s)'), $name, $data["id"]);
               }
               if ((Session::getCurrentInterface() != 'helpdesk') && $item::canView()) {
                  $link     = $itemtype::getFormURLWithID($data['id']);
                  $namelink = "<a href=\"".$link."\">".$name."</a>";
               } else {
                  $namelink = $name;
               }

               echo "<tr class='tab_bg_1'>";
               if ($canedit) {
                  echo "<td width='10'>";
                  Html::showMassiveActionCheckBox(__CLASS__, $data["linkid"]);
                  echo "</td>";
               }
               if ($prem) {
                  $typename = $item->getTypeName($nb);
                  echo "<td class='center top' rowspan='$nb'>".
                         (($nb > 1) ? sprintf(__('%1$s: %2$s'), $typename, $nb) : $typename)."</td>";
                  $prem = false;
               }
               echo "<td class='center'>";
               echo Dropdown::getDropdownName("glpi_entities", $data['entity'])."</td>";
               echo "<td class='center".
                        (isset($data['is_deleted']) && $data['is_deleted'] ? " tab_bg_2_2'" : "'");
               echo ">".$namelink."</td>";
               echo "<td class='center'>".(isset($data["serial"])? "".$data["serial"]."" :"-").
                    "</td>";
               echo "<td class='center'>".
                      (isset($data["otherserial"])? "".$data["otherserial"]."" :"-")."</td>";
               echo "</tr>";
            }
            $totalnb += $nb;
         }
      }

      if ($number) {
         echo $header_begin.$header_bottom.$header_end;
      }

      echo "</table>";
      if ($canedit && $number) {
         $massiveactionparams['ontop'] = false;
         Html::showMassiveActions($massiveactionparams);
         Html::closeForm();
      }
      echo "</div>";
   }


   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if (!$withtemplate) {
         $nb = 0;
         switch ($item->getType()) {
            case 'Ticket' :
               if (($_SESSION["glpiactiveprofile"]["helpdesk_hardware"] != 0)
                   && (count($_SESSION["glpiactiveprofile"]["helpdesk_item_type"]) > 0)) {
                  if ($_SESSION['glpishow_count_on_tabs']) {
                     //$nb = self::countForMainItem($item);
                     $nb = countElementsInTable('glpi_items_tickets',
                                                ['tickets_id' => $item->getID(),
                                                 'itemtype' => $_SESSION["glpiactiveprofile"]["helpdesk_item_type"]]);
                  }
                  return self::createTabEntry(_n('Item', 'Items', Session::getPluralNumber()), $nb);
               }
         }
      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {

      switch ($item->getType()) {
         case 'Ticket' :
            self::showForTicket($item);
            break;
      }
      return true;
   }

   /**
    * Make a select box for Ticket my devices
    *
    * @param integer $userID           User ID for my device section (default 0)
    * @param integer $entity_restrict  restrict to a specific entity (default -1)
    * @param string  $itemtype         of selected item (default 0)
    * @param integer $items_id         of selected item (default 0)
    * @param array   $options          array of possible options:
    *    - used     : ID of the requester user
    *    - multiple : allow multiple choice
    *
    * @return void
   **/
   static function dropdownMyDevices($userID = 0, $entity_restrict = -1, $itemtype = 0, $items_id = 0, $options = []) {
      global $DB, $CFG_GLPI;

      $params = ['tickets_id' => 0,
                      'used'       => [],
                      'multiple'   => false,
                      'rand'       => mt_rand()];

      foreach ($options as $key => $val) {
         $params[$key] = $val;
      }

      if ($userID == 0) {
         $userID = Session::getLoginUserID();
      }

      $rand        = $params['rand'];
      $already_add = $params['used'];

      if ($_SESSION["glpiactiveprofile"]["helpdesk_hardware"]&pow(2, Ticket::HELPDESK_MY_HARDWARE)) {
         $my_devices = ['' => Dropdown::EMPTY_VALUE];
         $devices    = [];

         // My items
         foreach ($CFG_GLPI["linkuser_types"] as $itemtype) {
            if (($item = getItemForItemtype($itemtype))
                && Ticket::isPossibleToAssignType($itemtype)) {
               $itemtable = getTableForItemType($itemtype);

               $criteria = [
                  'FROM'   => $itemtable,
                  'WHERE'  => [
                     'users_id' => $userID
                  ] + getEntitiesRestrictCriteria($itemtable, '', $entity_restrict, $item->maybeRecursive()),
                  'ORDER'  => $item->getNameField()
               ];

               if ($item->maybeDeleted()) {
                  $criteria['WHERE']['is_deleted'] = 0;
               }
               if ($item->maybeTemplate()) {
                  $criteria['WHERE']['is_template'] = 0;
               }
               if (in_array($itemtype, $CFG_GLPI["helpdesk_visible_types"])) {
                  $criteria['WHERE']['is_helpdesk_visible'] = 1;
               }

               $iterator = $DB->request($criteria);
               $nb = count($iterator);
               if ($nb > 0) {
                  $type_name = $item->getTypeName($nb);

                  while ($data = $iterator->next()) {
                     if (!isset($already_add[$itemtype]) || !in_array($data["id"], $already_add[$itemtype])) {
                        $output = $data[$item->getNameField()];
                        if (empty($output) || $_SESSION["glpiis_ids_visible"]) {
                           $output = sprintf(__('%1$s (%2$s)'), $output, $data['id']);
                        }
                        $output = sprintf(__('%1$s - %2$s'), $type_name, $output);
                        if ($itemtype != 'Software') {
                           if (!empty($data['serial'])) {
                              $output = sprintf(__('%1$s - %2$s'), $output, $data['serial']);
                           }
                           if (!empty($data['otherserial'])) {
                              $output = sprintf(__('%1$s - %2$s'), $output, $data['otherserial']);
                           }
                        }
                        $devices[$itemtype."_".$data["id"]] = $output;

                        $already_add[$itemtype][] = $data["id"];
                     }
                  }
               }
            }
         }

         if (count($devices)) {
            $my_devices[__('My devices')] = $devices;
         }
         // My group items
         if (Session::haveRight("show_group_hardware", "1")) {
            $iterator = $DB->request([
               'SELECT'    => [
                  'glpi_groups_users.groups_id',
                  'glpi_groups.name'
               ],
               'FROM'      => 'glpi_groups_users',
               'LEFT JOIN' => [
                  'glpi_groups'  => [
                     'ON' => [
                        'glpi_groups_users'  => 'groups_id',
                        'glpi_groups'        => 'id'
                     ]
                  ]
               ],
               'WHERE'     => [
                  'glpi_groups_users.users_id'  => $userID
               ] + getEntitiesRestrictCriteria('glpi_groups', '', $entity_restrict, true)
            ]);

            $devices = [];
            $groups  = [];
            if (count($iterator)) {
               while ($data = $iterator->next()) {
                  $a_groups                     = getAncestorsOf("glpi_groups", $data["groups_id"]);
                  $a_groups[$data["groups_id"]] = $data["groups_id"];
                  $groups = array_merge($groups, $a_groups);
               }

               foreach ($CFG_GLPI["linkgroup_types"] as $itemtype) {
                  if (($item = getItemForItemtype($itemtype))
                      && Ticket::isPossibleToAssignType($itemtype)) {
                     $itemtable  = getTableForItemType($itemtype);
                     $criteria = [
                        'FROM'   => $itemtable,
                        'WHERE'  => [
                           'groups_id' => $groups
                        ] + getEntitiesRestrictCriteria($itemtable, '', $entity_restrict, $item->maybeRecursive()),
                        'ORDER'  => $item->getNameField()
                     ];

                     if ($item->maybeDeleted()) {
                        $criteria['WHERE']['is_deleted'] = 0;
                     }
                     if ($item->maybeTemplate()) {
                        $criteria['WHERE']['is_template'] = 0;
                     }

                     $iterator = $DB->request($criteria);
                     if (count($iterator)) {
                        $type_name = $item->getTypeName();
                        if (!isset($already_add[$itemtype])) {
                           $already_add[$itemtype] = [];
                        }
                        while ($data = $iterator->next()) {
                           if (!in_array($data["id"], $already_add[$itemtype])) {
                              $output = '';
                              if (isset($data["name"])) {
                                 $output = $data["name"];
                              }
                              if (empty($output) || $_SESSION["glpiis_ids_visible"]) {
                                 $output = sprintf(__('%1$s (%2$s)'), $output, $data['id']);
                              }
                              $output = sprintf(__('%1$s - %2$s'), $type_name, $output);
                              if (isset($data['serial'])) {
                                 $output = sprintf(__('%1$s - %2$s'), $output, $data['serial']);
                              }
                              if (isset($data['otherserial'])) {
                                 $output = sprintf(__('%1$s - %2$s'), $output, $data['otherserial']);
                              }
                              $devices[$itemtype."_".$data["id"]] = $output;

                              $already_add[$itemtype][] = $data["id"];
                           }
                        }
                     }
                  }
               }
               if (count($devices)) {
                  $my_devices[__('Devices own by my groups')] = $devices;
               }
            }
         }
         // Get software linked to all owned items
         if (in_array('Software', $_SESSION["glpiactiveprofile"]["helpdesk_item_type"])) {
            $software_helpdesk_types = array_intersect($CFG_GLPI['software_types'], $_SESSION["glpiactiveprofile"]["helpdesk_item_type"]);
            foreach ($software_helpdesk_types as $itemtype) {
               if (isset($already_add[$itemtype]) && count($already_add[$itemtype])) {
                  $iterator = $DB->request([
                     'SELECT'          => [
                        'glpi_softwareversions.name AS version',
                        'glpi_softwares.name AS name',
                        'glpi_softwares.id'
                     ],
                     'DISTINCT'        => true,
                     'FROM'            => 'glpi_items_softwareversions',
                     'LEFT JOIN'       => [
                        'glpi_softwareversions'  => [
                           'ON' => [
                              'glpi_items_softwareversions' => 'softwareversions_id',
                              'glpi_softwareversions'       => 'id'
                           ]
                        ],
                        'glpi_softwares'        => [
                           'ON' => [
                              'glpi_softwareversions' => 'softwares_id',
                              'glpi_softwares'        => 'id'
                           ]
                        ]
                     ],
                     'WHERE'        => [
                           'glpi_items_softwareversions.items_id' => $already_add[$itemtype],
                           'glpi_items_softwareversions.itemtype' => $itemtype,
                           'glpi_softwares.is_helpdesk_visible'   => 1
                        ] + getEntitiesRestrictCriteria('glpi_softwares', '', $entity_restrict),
                     'ORDERBY'      => 'glpi_softwares.name'
                  ]);

                  $devices = [];
                  if (count($iterator)) {
                     $item       = new Software();
                     $type_name  = $item->getTypeName();
                     if (!isset($already_add['Software'])) {
                        $already_add['Software'] = [];
                     }
                     while ($data = $iterator->next()) {
                        if (!in_array($data["id"], $already_add['Software'])) {
                           $output = sprintf(__('%1$s - %2$s'), $type_name, $data["name"]);
                           $output = sprintf(__('%1$s (%2$s)'), $output,
                              sprintf(__('%1$s: %2$s'), __('version'),
                                 $data["version"]));
                           if ($_SESSION["glpiis_ids_visible"]) {
                              $output = sprintf(__('%1$s (%2$s)'), $output, $data["id"]);
                           }
                           $devices["Software_".$data["id"]] = $output;

                           $already_add['Software'][] = $data["id"];
                        }
                     }
                     if (count($devices)) {
                        $my_devices[__('Installed software')] = $devices;
                     }
                  }
               }
            }
         }
         // Get linked items to computers
         if (isset($already_add['Computer']) && count($already_add['Computer'])) {
            $devices = [];

            // Direct Connection
            $types = ['Monitor', 'Peripheral', 'Phone', 'Printer'];
            foreach ($types as $itemtype) {
               if (in_array($itemtype, $_SESSION["glpiactiveprofile"]["helpdesk_item_type"])
                   && ($item = getItemForItemtype($itemtype))) {
                  $itemtable = getTableForItemType($itemtype);
                  if (!isset($already_add[$itemtype])) {
                     $already_add[$itemtype] = [];
                  }
                  $criteria = [
                     'SELECT'          => "$itemtable.*",
                     'DISTINCT'        => true,
                     'FROM'            => 'glpi_computers_items',
                     'LEFT JOIN'       => [
                        $itemtable  => [
                           'ON' => [
                              'glpi_computers_items'  => 'items_id',
                              $itemtable              => 'id'
                           ]
                        ]
                     ],
                     'WHERE'           => [
                        'glpi_computers_items.itemtype'     => $itemtype,
                        'glpi_computers_items.computers_id' => $already_add['Computer']
                     ] + getEntitiesRestrictCriteria($itemtable, '', $entity_restrict),
                     'ORDERBY'         => "$itemtable.name"
                  ];

                  if ($item->maybeDeleted()) {
                     $criteria['WHERE']["$itemtable.is_deleted"] = 0;
                  }
                  if ($item->maybeTemplate()) {
                     $criteria['WHERE']["$itemtable.is_template"] = 0;
                  }

                  $iterator = $DB->request($criteria);
                  if (count($iterator)) {
                     $type_name = $item->getTypeName();
                     while ($data = $iterator->next()) {
                        if (!in_array($data["id"], $already_add[$itemtype])) {
                           $output = $data["name"];
                           if (empty($output) || $_SESSION["glpiis_ids_visible"]) {
                              $output = sprintf(__('%1$s (%2$s)'), $output, $data['id']);
                           }
                           $output = sprintf(__('%1$s - %2$s'), $type_name, $output);
                           if ($itemtype != 'Software') {
                              $output = sprintf(__('%1$s - %2$s'), $output, $data['otherserial']);
                           }
                           $devices[$itemtype."_".$data["id"]] = $output;

                           $already_add[$itemtype][] = $data["id"];
                        }
                     }
                  }
               }
            }
            if (count($devices)) {
               $my_devices[__('Connected devices')] = $devices;
            }
         }
         echo "<div id='tracking_my_devices'>";
         echo __('My devices')."&nbsp;";
         Dropdown::showFromArray('my_items', $my_devices, ['rand' => $rand]);
         echo "</div>";

         // Auto update summary of active or just solved tickets
         $params = ['my_items' => '__VALUE__'];

         Ajax::updateItemOnSelectEvent("dropdown_my_items$rand", "item_ticket_selection_information$rand",
                                       $CFG_GLPI["root_doc"]."/ajax/ticketiteminformation.php",
                                       $params);
      }
   }

   /**
    * Make a select box with all glpi items
    *
    * @param $options array of possible options:
    *    - name         : string / name of the select (default is users_id)
    *    - value
    *    - comments     : boolean / is the comments displayed near the dropdown (default true)
    *    - entity       : integer or array / restrict to a defined entity or array of entities
    *                      (default -1 : no restriction)
    *    - entity_sons  : boolean / if entity restrict specified auto select its sons
    *                      only available if entity is a single value not an array(default false)
    *    - rand         : integer / already computed rand value
    *    - toupdate     : array / Update a specific item on select change on dropdown
    *                      (need value_fieldname, to_update, url
    *                      (see Ajax::updateItemOnSelectEvent for information)
    *                      and may have moreparams)
    *    - used         : array / Already used items ID: not to display in dropdown (default empty)
    *    - on_change    : string / value to transmit to "onChange"
    *    - display      : boolean / display or get string (default true)
    *    - width        : specific width needed (default 80%)
    *
   **/
   static function dropdown($options = []) {
      global $DB;

      // Default values
      $p['name']           = 'items';
      $p['value']          = '';
      $p['all']            = 0;
      $p['on_change']      = '';
      $p['comments']       = 1;
      $p['width']          = '80%';
      $p['entity']         = -1;
      $p['entity_sons']    = false;
      $p['used']           = [];
      $p['toupdate']       = '';
      $p['rand']           = mt_rand();
      $p['display']        = true;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $itemtypes = ['Computer', 'Monitor', 'NetworkEquipment', 'Peripheral', 'Phone', 'Printer'];

      $union = new \QueryUnion();
      foreach ($itemtypes as $type) {
         $table = getTableForItemType($type);
         $union->addQuery([
            'SELECT' => [
               'id',
               new \QueryExpression("$type AS " . $DB->quoteName('itemtype')),
               "name"
            ],
            'FROM'   => $table,
            'WHERE'  => [
               'NOT'          => ['id' => null],
               'is_deleted'   => 0,
               'is_template'  => 0
            ]
         ]);
      }

      $iterator = $DB->request(['FROM' => $union]);
      $output = [];
      while ($data = $iterator->next()) {
         $item = getItemForItemtype($data['itemtype']);
         $output[$data['itemtype']."_".$data['id']] = $item->getTypeName()." - ".$data['name'];
      }

      return Dropdown::showFromArray($p['name'], $output, $p);
   }

   /**
    * Return used items for a ticket
    *
    * @param integer type $tickets_id
    *
    * @return array
    */
   static function getUsedItems($tickets_id) {

      $data = getAllDataFromTable('glpi_items_tickets', ['tickets_id' => $tickets_id]);
      $used = [];
      if (!empty($data)) {
         foreach ($data as $val) {
            $used[$val['itemtype']][] = $val['items_id'];
         }
      }

      return $used;
   }

   /**
    * Form for Followup on Massive action
   **/
   static function showFormMassiveAction($ma) {
      global $CFG_GLPI;

      switch ($ma->getAction()) {
         case 'add_item' :
            Dropdown::showSelectItemFromItemtypes(['items_id_name'   => 'items_id',
                                                   'itemtype_name'   => 'item_itemtype',
                                                   'itemtypes'       => $CFG_GLPI['ticket_types'],
                                                   'checkright'      => true,
                                                   'entity_restrict' => $_SESSION['glpiactive_entity']
                                                  ]);
            echo "<br><input type='submit' name='add' value=\""._sx('button', 'Add')."\" class='submit'>";
            break;

         case 'delete_item' :
            Dropdown::showSelectItemFromItemtypes(['items_id_name'   => 'items_id',
                                                   'itemtype_name'   => 'item_itemtype',
                                                   'itemtypes'       => $CFG_GLPI['ticket_types'],
                                                   'checkright'      => true,
                                                   'entity_restrict' => $_SESSION['glpiactive_entity']
                                                  ]);

            echo "<br><input type='submit' name='delete' value=\"".__('Delete permanently')."\" class='submit'>";
            break;
      }

   }

   /**
    * @since 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {

      switch ($ma->getAction()) {
         case 'add_item' :
            static::showFormMassiveAction($ma);
            return true;

         case 'delete_item' :
            static::showFormMassiveAction($ma);
            return true;
      }

      return parent::showMassiveActionsSubForm($ma);
   }


   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      switch ($ma->getAction()) {
         case 'add_item' :
            $input = $ma->getInput();

            $item_ticket = new static();
            foreach ($ids as $id) {
               if ($item->getFromDB($id) && !empty($input['items_id'])) {
                  $input['tickets_id'] = $id;
                  $input['itemtype'] = $input['item_itemtype'];

                  if ($item_ticket->can(-1, CREATE, $input)) {
                     $ok = true;
                     if (!$item_ticket->add($input)) {
                        $ok = false;
                     }

                     if ($ok) {
                        $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                     } else {
                        $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                        $ma->addMessage($item->getErrorMessage(ERROR_ON_ACTION));
                     }

                  } else {
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_NORIGHT);
                     $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
                  }

               } else {
                  $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                  $ma->addMessage($item->getErrorMessage(ERROR_NOT_FOUND));
               }
            }
            return;

         case 'delete_item' :
            $input = $ma->getInput();
            $item_ticket = new static();
            foreach ($ids as $id) {
               if ($item->getFromDB($id) && !empty($input['items_id'])) {
                  $item_found = $item_ticket->find([
                     'tickets_id'   => $id,
                     'itemtype'     => $input['item_itemtype'],
                     'items_id'     => $input['items_id']
                  ]);
                  if (!empty($item_found)) {
                     $item_founds_id = array_keys($item_found);
                     $input['id'] = $item_founds_id[0];

                     if ($item_ticket->can($input['id'], DELETE, $input)) {
                        $ok = true;
                        if (!$item_ticket->delete($input)) {
                           $ok = false;
                        }

                        if ($ok) {
                           $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_OK);
                        } else {
                           $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                           $ma->addMessage($item->getErrorMessage(ERROR_ON_ACTION));
                        }

                     } else {
                        $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_NORIGHT);
                        $ma->addMessage($item->getErrorMessage(ERROR_RIGHT));
                     }

                  } else {
                     $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                     $ma->addMessage($item->getErrorMessage(ERROR_NOT_FOUND));
                  }

               } else {
                  $ma->itemDone($item->getType(), $id, MassiveAction::ACTION_KO);
                  $ma->addMessage($item->getErrorMessage(ERROR_NOT_FOUND));
               }
            }
            return;
      }
      parent::processMassiveActionsForOneItemtype($ma, $item, $ids);
   }

   function rawSearchOptions() {
      $tab = [];

      $tab[] = [
         'id'                 => '3',
         'table'              => $this->getTable(),
         'field'              => 'tickets_id',
         'name'               => Ticket::getTypeName(1),
         'datatype'           => 'dropdown',
      ];

      $tab[] = [
         'id'                 => '13',
         'table'              => $this->getTable(),
         'field'              => 'items_id',
         'name'               => _n('Associated element', 'Associated elements', Session::getPluralNumber()),
         'datatype'           => 'specific',
         'comments'           => true,
         'nosort'             => true,
         'additionalfields'   => ['itemtype']
      ];

      $tab[] = [
         'id'                 => '131',
         'table'              => $this->getTable(),
         'field'              => 'itemtype',
         'name'               => _n('Associated item type', 'Associated item types', Session::getPluralNumber()),
         'datatype'           => 'itemtypename',
         'itemtype_list'      => 'ticket_types',
         'nosort'             => true
      ];

      return $tab;
   }

   /**
    * Add a message on add action
   **/
   function addMessageOnAddAction() {
      $addMessAfterRedirect = false;
      if (isset($this->input['_add'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message'])
          || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {
         $item = getItemForItemtype($this->fields['itemtype']);
         $item->getFromDB($this->fields['items_id']);

         $link = $item->getFormURL();
         if (!isset($link)) {
            return;
         }
         if (($name = $item->getName()) == NOT_AVAILABLE) {
            //TRANS: %1$s is the itemtype, %2$d is the id of the item
            $item->fields['name'] = sprintf(__('%1$s - ID %2$d'),
                                            $item->getTypeName(1), $item->fields['id']);
         }

         $display = (isset($this->input['_no_message_link'])?$item->getNameID()
                                                            :$item->getLink());

         // Do not display quotes
         //TRANS : %s is the description of the added item
         Session::addMessageAfterRedirect(sprintf(__('%1$s: %2$s'), __('Item successfully added'),
                                                  stripslashes($display)));

      }
   }

   /**
    * Add a message on delete action
   **/
   function addMessageOnPurgeAction() {

      if (!$this->maybeDeleted()) {
         return;
      }

      $addMessAfterRedirect = false;
      if (isset($this->input['_delete'])) {
         $addMessAfterRedirect = true;
      }

      if (isset($this->input['_no_message'])
          || !$this->auto_message_on_action) {
         $addMessAfterRedirect = false;
      }

      if ($addMessAfterRedirect) {
         $item = getItemForItemtype($this->fields['itemtype']);
         $item->getFromDB($this->fields['items_id']);

         $link = $item->getFormURL();
         if (!isset($link)) {
            return;
         }
         if (isset($this->input['_no_message_link'])) {
            $display = $item->getNameID();
         } else {
            $display = $item->getLink();
         }
         //TRANS : %s is the description of the updated item
         Session::addMessageAfterRedirect(sprintf(__('%1$s: %2$s'), __('Item successfully deleted'), $display));

      }
   }
}
