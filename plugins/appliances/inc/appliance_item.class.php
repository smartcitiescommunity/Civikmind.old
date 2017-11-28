<?php
/*
 * @version $Id: appliance_item.class.php 258 2017-10-10 13:21:54Z yllen $
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


/**
 * Class PluginAppliancesAppliance_Item
**/
class PluginAppliancesAppliance_Item extends CommonDBRelation {

   // From CommonDBRelation
   static public $itemtype_1     = 'PluginAppliancesAppliance';
   static public $items_id_1     = 'plugin_appliances_appliances_id';
   static public $take_entity_1  = false ;

   static public $itemtype_2     = 'itemtype';
   static public $items_id_2     = 'items_id';
   static public $take_entity_2  = true ;

   static public $checkItem_2_Rights  = self::HAVE_VIEW_RIGHT_ON_ITEM;



    /**
     * Return the localized name of the current Type
     *
     * @see CommonGLPI::getTypeName()
    **/
    static function getTypeName($nb=0) {
      return _n('Appliance item', 'Appliances items', $nb, 'appliances');
   }


    /**
     * Actions done when item is deleted from the database
     */
    function cleanDBonPurge() {

      $temp = new PluginAppliancesOptvalue_Item();
      $temp->deleteByCriteria(['itemtype' => $this->fields['itemtype'],
                               'items_id' => $this->fields['items_id']]);

      $temp = new PluginAppliancesRelation();
      $temp->deleteByCriteria(['plugin_appliances_appliances_items_id' => $this->fields['id']]);
   }


    /**
     * Hook called After an item is uninstall or purge
     *
     * @param $item      string      CommonDBTM object
     */
   static function cleanForItem(CommonDBTM $item) {

      $temp = new self();
      $temp->deleteByCriteria(['itemtype' => $item->getType(),
                               'items_id' => $item->getField('id')]);
   }


    /**
     * @param $item      PluginAppliancesAppliance object
     *
     * @return integer
    **/
    static function countForAppliance(PluginAppliancesAppliance $item) {

      $types = implode("','", $item->getTypes());
      if (empty($types)) {
         return 0;
      }
      return countElementsInTable('glpi_plugin_appliances_appliances_items',
                                  "`itemtype` IN ('".$types."')
                                   AND `plugin_appliances_appliances_id` = '".$item->getID()."'");
   }


    /**
     * @param $item      CommonDBTM object
     *
     * @return integer
    */
    static function countForItem(CommonDBTM $item) {

      return countElementsInTable('glpi_plugin_appliances_appliances_items',
                                  "`itemtype`='".$item->getType()."'
                                   AND `items_id` = '".$item->getID()."'");
   }


   /**
    * Show the appliances associated with a device
    *
    * Called from the device form (applicatif tab)
    *
    * @param $item         CommonDBTMtype of the device
    * @param $withtemplate (default '')
   **/
   static function showForItem($item, $withtemplate='') {
      global $DB,$CFG_GLPI;

      $ID       = $item->getField('id');
      $itemtype = get_class($item);
      $canread  = $item->can($ID, READ);
      $canedit  = $item->can($ID, UPDATE);

      $query = "SELECT `glpi_plugin_appliances_appliances_items`.`id` AS entID,
                       `glpi_plugin_appliances_appliances`.*
                FROM `glpi_plugin_appliances_appliances_items`,
                     `glpi_plugin_appliances_appliances`
                LEFT JOIN `glpi_entities`
                     ON (`glpi_entities`.`id` = `glpi_plugin_appliances_appliances`.`entities_id`)
                WHERE `glpi_plugin_appliances_appliances_items`.`items_id` = '".$ID."'
                      AND `glpi_plugin_appliances_appliances_items`.`itemtype` = '".$itemtype."'
                      AND `glpi_plugin_appliances_appliances_items`.`plugin_appliances_appliances_id`
                           = `glpi_plugin_appliances_appliances`.`id`".
                      getEntitiesRestrictRequest(" AND", "glpi_plugin_appliances_appliances",
                                                 'entities_id', $item->getEntityID(), true);
      $result = $DB->request($query);

      $result_app = $DB->request(['SELECT' => 'ID',
                                  'FROM'   => 'glpi_plugin_appliances_appliances_items',
                                  'WHERE'  => ['items_id' => $ID]]);
      $number_app = $result_app->numrows();

      if ($number_app >0) {
         $colsup = 1;
      } else {
         $colsup = 0;
      }

      if (Session::isMultiEntitiesMode()) {
         $colsup += 1;
      }

      echo "<div class='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='".(5+$colsup)."'>".__('Associate', 'appliances')."</th></tr>";
      echo "<tr><th>".__('Name')."</th>";
      if (Session::isMultiEntitiesMode()) {
         echo "<th>".__('Entity')."</th>";
      }
      echo "<th>".__('Group')."</th>";
      echo "<th>".__('Type')."</th>";
      if ($number_app > 0) {
         echo "<th>".__('Item to link', 'appliances')."</th>";
      }
      echo "<th>".__('Comments')."<br>".__('User fields', 'appliances')."</th>";

      if ($canedit) {
         if ($withtemplate < 2) {
            echo "<th>&nbsp;</th>";
         }
      }
      echo "</tr>";
      $used = [];
      while ($data = $result_app->next()) {
         $appliancesID = $data["id"];
         $used[]       = $appliancesID;

         echo "<tr class='tab_bg_1".($data["is_deleted"]=='1'?"_2":"")."'>";
         $name = $data["name"];
         if (($withtemplate != 3)
             && $canread
             && (in_array($data['entities_id'], $_SESSION['glpiactiveentities'])
                 || $data["is_recursive"])) {

            echo "<td class='center'>";
            echo "<a href='".
                   $CFG_GLPI["root_doc"]."/plugins/appliances/front/appliance.form.php?id=".
                   $data["id"]."'>";
            if ($_SESSION["glpiis_ids_visible"]) {
               printf(__('%1$s (%2$s)'), $name, $data["id"]);
            } else {
               echo $name;
            }
            echo "</a></td>";
         } else {
            echo "<td class='center'>";
            if ($_SESSION["glpiis_ids_visible"]) {
               printf(__('%1$s (%2$s)'), $name, $data["id"]);
            } else {
               echo $name;
            }
            echo "</td>";
         }
         if (Session::isMultiEntitiesMode()) {
            echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",
                                                                 $data['entities_id'])."</td>";
         }
         echo "<td class='center'>".Dropdown::getDropdownName("glpi_groups", $data["groups_id"]).
              "</td>";
         echo "<td class='center'>".
                Dropdown::getDropdownName("glpi_plugin_appliances_appliancetypes",
                                          $data["plugin_appliances_appliancetypes_id"])."</td>";

         if ($number_app > 0) {
            // add or delete a relation to an applicatifs
            echo "<td class='center'>";
            $data["relationtype"] = '';
            if (isset($data["relationtype"]) && $data["relationtype"]) {
               $data["relationtype"] = $data["relationtype"];
            }
            PluginAppliancesRelation::showList($data["relationtype"], $data["entID"],
                                               $item->fields["entities_id"], $canedit);
            echo "</td>";
         }

         echo "<td class='center'>".$data["comment"];
         PluginAppliancesOptvalue_Item::showList($itemtype, $ID, $appliancesID, $canedit);
         echo "</td>";

         if ($canedit) {
            echo "<td class='center tab_bg_2'>";
            Html::showSimpleForm($CFG_GLPI['root_doc'].'/plugins/appliances/front/appliance.form.php',
                                 'deleteappliance', __('Delete permanently'),
                                 ['id' => $data['entID']]);
            echo "</td>";
         }
         echo "</tr>";
      }

      if ($canedit){
         if ($item->isRecursive()) {
            $entities = getSonsOf('glpi_entities', $item->getEntityID());
         } else {
            $entities = $item->getEntityID();
         }
         $limit = getEntitiesRestrictRequest(" AND", "glpi_plugin_appliances_appliances", '',
                                             $entities, true);

         $req = $DB->request(['FROM'  => 'glpi_plugin_appliances_appliances',
                              'COUNT' => 'cpt',
                              'WHERE' => ['is_deleted' => 0]]);
         $nb     = $req->numrows();

         if (($withtemplate < 2)
             && ($nb > count($used))) {
            echo "<tr class='tab_bg_1'>";
            echo "<td class='right' colspan=5>";

            // needed to use the button "additem"
            echo "<form method='post' action=\"".$CFG_GLPI["root_doc"].
                  "/plugins/appliances/front/appliance.form.php\">";
            echo "<input type='hidden' name='item' value='".$ID."'>".
                 "<input type='hidden' name='itemtype' value='$itemtype'>";
            Dropdown::show('PluginAppliancesAppliance', ['name'   => "conID",
                                                         'entity' => $entities,
                                                         'used'   => $used]);

            echo "<input type='submit' name='additem' value='".__('Add')."' class='submit'>";
            Html::closeForm();

            echo "</td>";
            echo "<td class='right' colspan='".($colsup)."'></td>";
            echo "</tr>";
         }
      }
      echo "</table></div>";
   }


   /**
    * show for PDF the applicatif associated with a device
    *
    * @param $pdf       instance of plugin PDF
    * @param $item      CommonGLPI object
   **/
   static function pdfForItem(PluginPdfSimplePDF $pdf, CommonGLPI $item){
      global $DB;

      $ID       = $item->getField('id');
      $itemtype = get_class($item);

      $pdf->setColumnsSize(100);
      $pdf->displayTitle("<b>".__('Associated appliances', 'appliances')."</b>");

      $query = "SELECT `glpi_plugin_appliances_appliances_items`.`id` AS entID,
                       `glpi_plugin_appliances_appliances`.*
                FROM `glpi_plugin_appliances_appliances_items`,
                     `glpi_plugin_appliances_appliances`
                LEFT JOIN `glpi_entities`
                     ON (`glpi_entities`.`id` = `glpi_plugin_appliances_appliances`.`entities_id`)
                WHERE `glpi_plugin_appliances_appliances_items`.`items_id` = '$ID'
                      AND `glpi_plugin_appliances_appliances_items`.`itemtype` = '$itemtype'
                      AND `glpi_plugin_appliances_appliances_items`.`plugin_appliances_appliances_id`
                           = `glpi_plugin_appliances_appliances`.`id`".
                      getEntitiesRestrictRequest(" AND", "glpi_plugin_appliances_appliances",
                                                 'entities_id', $item->getEntityID(), true);
      $result = $DB->request($query);
      $number = $result->numrows();

      if (!$number) {
         $pdf->displayLine(__('No item found'));
      } else {
         if (Session::isMultiEntitiesMode()) {
            $pdf->setColumnsSize(30,30,20,20);
            $pdf->displayTitle('<b><i>'.__('Name'), __('Entity'), __('Group'), __('Type').'</i></b>');
         } else {
            $pdf->setColumnsSize(50,25,25);
            $pdf->displayTitle('<b><i>'.__('Name'), __('Group'),__('Type').'</i></b>');
         }

         while ($data = $result->next()) {
            $appliancesID = $data["id"];
            if (Session::isMultiEntitiesMode()) {
               $pdf->setColumnsSize(30,30,20,20);
               $pdf->displayLine($data["name"],
                                 Html::clean(Dropdown::getDropdownName("glpi_entities",
                                                                       $data['entities_id'])),
                                 Html::clean(Dropdown::getDropdownName("glpi_groups",
                                                                       $data["groups_id"])),
                                 Html::clean(Dropdown::getDropdownName("glpi_plugin_appliances_appliancetypes",
                                                                       $data["plugin_appliances_appliancetypes_id"])));
            } else {
               $pdf->setColumnsSize(50,25,25);
               $pdf->displayLine($data["name"],
                                 Html::clean(Dropdown::getDropdownName("glpi_groups",
                                                                       $data["groups_id"])),
                                 Html::clean(Dropdown::getDropdownName("glpi_plugin_appliances_appliancetypes",
                                                                       $data["plugin_appliances_appliancetypes_id"])));
            }
            PluginAppliancesRelation::showList_PDF($pdf, $data["relationtype"], $data["entID"]);
            PluginAppliancesOptvalue_Item::showList_PDF($pdf, $ID, $appliancesID);
         }
      }
      $pdf->displaySpace();
   }


    /**
     * @param $pdf      instance of plugin PDF
     * @param $appli    PluginAppliancesAppliance object
     *
     * @return bool
    **/
    static function pdfForAppliance(PluginPdfSimplePDF $pdf, PluginAppliancesAppliance $appli) {
      global $DB;

      $instID = $appli->fields['id'];

      if (!$appli->can($instID, READ)) {
         return false;
      }
      if (!Session::haveRight("plugin_appliances", READ)) {
         return false;
      }

      $pdf->setColumnsSize(100);
      $pdf->displayTitle('<b>'._n('Associated item', 'Associated items',2).'</b>');

      $result = $DB->request(['SELECT DISTINCT' => 'itemtype',
                              'FROM'            => 'glpi_plugin_appliances_appliances_items',
                              'WHERE'           => ['plugin_appliances_appliances_id' => $instID]]);
      $number = $result->numrows();

      if (Session::isMultiEntitiesMode()) {
         $pdf->setColumnsSize(12,27,25,18,18);
         $pdf->displayTitle('<b><i>'.__('Type'), __('Name'), __('Entity'), __('Serial number'),
                                     __('Inventory number').'</i></b>');
      } else {
         $pdf->setColumnsSize(25,31,22,22);
         $pdf->displayTitle('<b><i>'.__('Type'), __('Name'), __('Serial number'),
                                     __('Inventory number').'</i></b>');
      }

      if (!$number) {
         $pdf->displayLine(__('No item found'));
      } else {
         foreach ($result as $id => $row) {
            $type = $row['itemtype'];
            if (!($item = getItemForItemtype($type))) {
               continue;
            }

            if ($item->canView()) {
               $column = "name";
               if ($type == 'Ticket') {
                  $column = "id";
               }
               if ($type == 'KnowbaseItem') {
                  $column = "question";
               }

               $query = "SELECT `".$item->getTable()."`.*,
                                `glpi_plugin_appliances_appliances_items`.`id` AS IDD,
                                `glpi_entities`.`id` AS entity
                         FROM `glpi_plugin_appliances_appliances_items`, `".$item->getTable()."`
                         LEFT JOIN `glpi_entities`
                              ON (`glpi_entities`.`id` = `".$item->getTable()."`.`entities_id`)
                         WHERE `".$item->getTable()."`.`id`
                                    = `glpi_plugin_appliances_appliances_items`.`items_id`
                               AND `glpi_plugin_appliances_appliances_items`.`itemtype` = '".$type."'
                               AND `glpi_plugin_appliances_appliances_items`.`plugin_appliances_appliances_id`
                                    = '".$instID."' ".
                               getEntitiesRestrictRequest(" AND ",$item->getTable());

               if ($item->maybeTemplate()) {
                  $query .= " AND `".$item->getTable()."`.`is_template` = '0'";
               }
               $query.=" ORDER BY `glpi_entities`.`completename`, `".$item->getTable()."`.$column";

               if ($result_linked = $DB->request($query)) {
                  if ($result_linked->numrows()) {
                     foreach ($result_linked as $id => $data) {
                        if (!$item->getFromDB($data['id'])) {
                           continue;
                        }

                        if ($type == 'Ticket') {
                           $data["name"] = sprintf(__('%1$s %2$s'), __('Ticket'), $data["id"]);
                        }
                        if ($type == 'KnowbaseItem') {
                           $data["name"] = $data["question"];
                        }
                        $name = $data["name"];
                        if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) {
                           $name = sprintf(__('%1$s (%2$s)'), $name, $data["id"]);
                        }

                        if (Session::isMultiEntitiesMode()) {
                           $pdf->setColumnsSize(12,27,25,18,18);
                           $pdf->displayLine($item->getTypeName(1), $name,
                                             Dropdown::getDropdownName("glpi_entities",
                                                                       $data['entities_id']),
                                             (isset($data["serial"])? $data["serial"] :"-"),
                                             (isset($data["otherserial"])?$data["otherserial"]:"-"));
                        } else {
                           $pdf->setColumnsSize(25,31,22,22);
                           $pdf->displayTitle($item->getTypeName(1), $name,
                                              (isset($data["serial"])?$data["serial"]:"-"),
                                              (isset($data["otherserial"])?$data["otherserial"]:"-"));
                        }

                        PluginAppliancesRelation::showList_PDF($pdf, $appli->fields["relationtype"],
                                                               $data["IDD"]);
                        PluginAppliancesOptvalue_Item::showList_PDF($pdf, $data["id"], $instID);
                     } // Each device
                  } // numrows device
               }
            } // type right
         } // each type
      } // numrows type
      $pdf->displaySpace();
   }


   static function showAddForm(PluginAppliancesAppliance $appli) {
      global $CFG_GLPI;

      $ID = $appli->getField('id');
      if (!$appli->can($ID, UPDATE)) {
         return false;
      }
      $rand = mt_rand();
      if ($ID > 0) {
         echo "<div class='firstbloc'>";
         echo "<form method='post' name='appliances_form$rand' id='appliances_form$rand' action=\"".
                $CFG_GLPI["root_doc"]."/plugins/appliances/front/appliance.form.php\">";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr><td class='center tab_bg_2' width='20%'>";
         echo "<input type='hidden' name='conID' value='$ID'>\n";
         Dropdown::showSelectItemFromItemtypes(['items_id_name'   => 'item',
                                                'itemtypes'       => $appli->getTypes(true),
                                                'entity_restrict' => ($appli->fields['is_recursive']
                                                                      ? getSonsOf('glpi_entities',
                                                                                  $appli->fields['entities_id'])
                                                                      : $appli->fields['entities_id']),
                                                'checkright'      => true]);
         echo "</td>";
         echo "<td class='center' class='tab_bg_2'>";
         echo "<input type='submit' name='additem' value='".__('Add')."' class='submit'>";
         echo "</td></tr></table>";
         Html::closeForm();
         echo "</div>";
      }
   }


   /**
    * Show the Device associated with an applicatif
    *
    * Called from the applicatif form
    *
    * @param $appli   PluginAppliancesAppliance object
    *
    * @return bool
   **/
   static function showForAppliance(PluginAppliancesAppliance $appli) {
      global $DB,$CFG_GLPI;

      $instID = $appli->fields['id'];

      if (!$appli->can($instID, READ)) {
         return false;
      }

      $canedit = $appli->can($instID, UPDATE);

      $result = $DB->request(['SELECT DISTINCT' => 'itemtype',
                              'FROM'            => 'glpi_plugin_appliances_appliances_items',
                              'WHERE'           => ['plugin_appliances_appliances_id' => $instID]]);
      $number = $result->numrows();

      if (Session::isMultiEntitiesMode()) {
         $colsup = 1;
      } else {
         $colsup = 0;
      }
      $rand = mt_rand();

      echo "<div class='spaced'>";
      if ($canedit) {
         Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
         $massiveactionparams = ['num_displayed'    => $number,
                                 'container'        => 'mass'.__CLASS__.$rand];
         Html::showMassiveActions($massiveactionparams);
         echo "<input type='hidden' name='conID' value='$instID'>\n";
      }

      echo "<table class='tab_cadre_fixehov'>";
      echo "<tr class='tab_bg_1'>";
      if ($canedit) {
          echo "<th width='10'>";
          Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
          echo "</th>";
      }
      echo "<th>".__('Type')."</th>";
      echo "<th>".__('Name')."</th>";
      if (Session::isMultiEntitiesMode()) {
         echo "<th>".__('Entity')."</th>";
      }
      if (isset($appli->fields["relationtype"])) {
         echo "<th>".__('Item to link', 'appliances')."<br>".__('User fields', 'appliances')."</th>";
      }
      echo "<th>".__('Serial number')."</th>";
      echo "<th>".__('Inventory number')."</th>";
      echo "</tr>";

      foreach ($result as $id => $row) {
         $type = $row['itemtype'];

         if (!($item = getItemForItemtype($type))) {
            continue;
         }
         if ($item->canView()) {
            // Ticket and knowbaseitem can't be associated to an appliance
            $column = "name";

            $query = "SELECT `".$item->getTable()."`.*,
                             `glpi_plugin_appliances_appliances_items`.`id` AS IDD,
                             `glpi_entities`.`id` AS entity
                      FROM `glpi_plugin_appliances_appliances_items`, `".getTableForItemType($type)."`
                      LEFT JOIN `glpi_entities`
                           ON (`glpi_entities`.`id` = `".$item->getTable()."`.`entities_id`)
                      WHERE `".$item->getTable()."`.`id`
                                 = `glpi_plugin_appliances_appliances_items`.`items_id`
                            AND `glpi_plugin_appliances_appliances_items`.`itemtype` = '".$type."'
                            AND `glpi_plugin_appliances_appliances_items`.`plugin_appliances_appliances_id`
                                 = '".$instID."' ".
                            getEntitiesRestrictRequest(" AND ", $item->getTable());

            if ($item->maybeTemplate()) {
               $query .= " AND `".$item->getTable()."`.`is_template` = '0'";
            }
            $query.=" ORDER BY `glpi_entities`.`completename`, `".$item->getTable()."`.$column";

            if ($result_linked = $DB->request($query)) {
               if ($result_linked->numrows()) {
                  Session::initNavigateListItems($type,
                                                 _n('Appliance', 'Appliances', 2, 'appliances')."
                                                   = ".$appli->getNameID());

                   foreach ($result_linked as $id => $data) {
                     $item->getFromDB($data["id"]);
                     Session::addToNavigateListItems($type, $data["id"]);
                     $name = $item->getLink();

                     echo "<tr class='tab_bg_1'>";
                     echo "<td width='10'>";
                     if ($canedit) {
                        Html::showMassiveActionCheckBox(__CLASS__, $data["IDD"]);
                     } else {
                        echo "$nbsp;";
                     }
                     echo "</td>";
                     echo "<td class='center'>".$item->getTypeName(1)."</td>";
                     echo "<td class='center' ".
                           (isset($data['deleted']) && $data['deleted']?"class='tab_bg_2_2'":"").">".
                           $name."</td>";
                     if (Session::isMultiEntitiesMode()) {
                        echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",
                                                                             $data['entity']).
                              "</td>";
                     }

                     if (isset($appli->fields["relationtype"]) && $appli->fields["relationtype"]) {
                        echo "<td class='center'>".
                               PluginAppliancesRelation::getTypeName($appli->fields["relationtype"]).
                               "&nbsp;:&nbsp;";
                               PluginAppliancesRelation::showList($appli->fields["relationtype"],
                                                                  $data["IDD"],
                                                                  $item->fields["entities_id"],
                                                                  false);
                               PluginAppliancesOptvalue_Item::showList($type, $data["id"], $instID,
                                                                       false);
                        echo "</td>";
                     }

                     echo "<td class='center'>".(isset($data["serial"])? "".$data["serial"].""
                                                                       :"-")."</td>";
                     echo "<td class='center'>".
                           (isset($data["otherserial"])? "".$data["otherserial"]."" :"-")."</td>";
                     echo "</tr>";
                  }
               }
            }
         }
      }
      echo "</table>";
      if ($canedit && $number) {
         $massiveactionparams['ontop'] = false;
         Html::showMassiveActions($massiveactionparams);
         Html::closeForm();
      }
      echo "</div>";
   }


    /**
     * Get Tab Name used for itemtype
     *
     * @see CommonGLPI::getTabNameForItem()
    **/
    function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if (!$withtemplate) {
         if (($item->getType() == 'PluginAppliancesAppliance')
             && count(PluginAppliancesAppliance::getTypes(false))) {
            if ($_SESSION['glpishow_count_on_tabs']) {
               return self::createTabEntry(_n('Associated item', 'Associated items', 2),
                                           self::countForAppliance($item));
            }
            return _n('Associated item', 'Associated items', 2);

         } else if (in_array($item->getType(), PluginAppliancesAppliance::getTypes(true))
                    && Session::haveRight('plugin_appliances', READ)) {
            if ($_SESSION['glpishow_count_on_tabs']) {
               return self::createTabEntry(PluginAppliancesAppliance::getTypeName(2),
                                           self::countForItem($item));
            }
            return PluginAppliancesAppliance::getTypeName(2);
         }
      }
      return '';
   }


    /**
     * show Tab content
     *
     * @see CommonGLPIgetTabNameForItem()
    **/
    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginAppliancesAppliance') {
         self::showAddForm($item);
         self::showForAppliance($item);

      } else if (in_array($item->getType(), PluginAppliancesAppliance::getTypes(true))) {
         self::showForItem($item, $withtemplate);

      }
      return true;
   }


    /**
     * show Tab content for PDF
     *
     * @param $pdf                  instance of plugin PDF
     * @param $item                 CommonGLPI object
     * @param $tab
     *
     * @return bool
    **/
    static function displayTabContentForPDF(PluginPdfSimplePDF $pdf, CommonGLPI $item, $tab) {

      if ($item->getType()=='PluginAppliancesAppliance') {
         self::pdfForAppliance($pdf, $item);

      } else if (in_array($item->getType(), PluginAppliancesAppliance::getTypes(true))) {
         self::pdfForItem($pdf, $item);

      } else {
         return false;
      }
      return true;
   }


    /**
     * @param $plugin_appliances_appliances_id   integer
     * @param $items_id                          integer
     * @param $itemtype                          string
     *
     * @return bool
    **/
    function getFromDBbyAppliancesAndItem($plugin_appliances_appliances_id, $items_id, $itemtype) {
      global $DB;

      if ($result = $DB->request(['FROM'  => $this->getTable(),
                                  'WHERE' => ['plugin_appliances_appliances_id'
                                                         => $plugin_appliances_appliances_id,
                                              'itemtype' => $items_id,
                                              'items_id' => $itemtype]])) {
         if ($result->numrows() != 1) {
            return false;
         }
         foreach ($result as $id => $row) {
            $this->fields[$id] = $row;
         }
         if (is_array($this->fields) && count($this->fields)) {
            return true;
         }
         return false;
      }
      return false;
   }


    /**
     * @param $plugin_appliances_appliances_id    integer
     * @param $items_id                           integer
     * @param $itemtype                           string
    **/
    function deleteItemByAppliancesAndItem($plugin_appliances_appliances_id, $items_id, $itemtype) {

      if ($this->getFromDBbyAppliancesAndItem($plugin_appliances_appliances_id,$items_id,$itemtype)) {
         $this->delete(['id'=>$this->fields["id"]]);
      }
   }


   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }

}
