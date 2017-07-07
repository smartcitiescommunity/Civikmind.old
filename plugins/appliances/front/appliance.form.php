<?php
/*
 * @version $Id: appliance.form.php 221 2016-05-30 15:25:38Z yllen $
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

include ("../../../inc/includes.php");

//Plugin::load('appliances',true);

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
   $_GET["withtemplate"] = "";
}

$PluginAppliances = new PluginAppliancesAppliance();
$PluginItem       = new PluginAppliancesAppliance_Item();

if (isset($_POST["add"])) {
   $PluginAppliances->check(-1, CREATE, $_POST);
   $newID = $PluginAppliances->add($_POST);
   if ($_SESSION['glpibackcreated']) {
      Html::redirect($PluginAppliances->getFormURL()."?id=".$newID);
   }
   Html::back();

} else if (isset($_POST["update"])) {
   $PluginAppliances->check($_POST['id'], UPDATE);
   $PluginAppliances->update($_POST);
   Html::back();

} else if (isset($_POST["delete"])) {
   $PluginAppliances->check($_POST['id'], DELETE);
   $PluginAppliances->delete($_POST);
   Html::redirect($CFG_GLPI["root_doc"]."/plugins/appliances/front/appliance.php");

} else if (isset($_POST["restore"])) {
   $PluginAppliances->check($_POST['id'], PURGE);
   $PluginAppliances->restore($_POST);
   Html::back();

} else if (isset($_POST["purge"])) {
   $PluginAppliances->check($_POST['id'], PURGE);
   $PluginAppliances->delete($_POST, 1);

   Html::redirect($CFG_GLPI["root_doc"]."/plugins/appliances/front/appliance.php");

// delete a relation
} else if (isset($_POST["dellieu"])) {
   $relation = new PluginAppliancesRelation();
   if (isset($_POST['itemrelation'])) {
      foreach($_POST["itemrelation"] as $key => $val) {
         $relation->delete(array('id' => $key));
      }
   }
   Html::back();

// add a relation
} else if (isset($_POST["addlieu"])) {
   $relation = new PluginAppliancesRelation();
   if ($_POST['tablekey'] >0) {
      foreach($_POST["tablekey"] as $key => $val) {
         if ($val > 0) {
            $relation->add(array('plugin_appliances_appliances_items_id' => $key,
                                 'relations_id'                          => $val));
         }
      }
   }
   Html::back();

} else if (isset($_POST['update_optvalues'])) {
   $PluginAppliances->check($_POST['plugin_appliances_appliances_id'], UPDATE);

   $Optvalue = new PluginAppliancesOptvalue();
   $Optvalue->updateList($_POST);
   Html::back();

} else if (isset($_POST["add_opt_val"])){
   $PluginAppliances->check($_POST['plugin_appliances_appliances_id'], READ);
   $item = new $_POST['itemtype']();
   $item->check($_POST['items_id'], UPDATE);

   $OptvalueItem = new PluginAppliancesOptvalue_Item();
   $OptvalueItem->updateList($_POST);
   Html::back();

} else if (isset($_POST["additem"])) {
   if ($_POST['itemtype']
       && ($_POST['item'] > 0)) {
      $input = array('plugin_appliances_appliances_id' => $_POST['conID'],
                     'items_id'                        => $_POST['item'],
                     'itemtype'                        => $_POST['itemtype']);

      $PluginItem->check(-1, UPDATE, $input);
      $newID = $PluginItem->add($input);
   }
   Html::back();

} else if (isset($_POST["deleteitem"])){
   foreach ($_POST["item"] as $key => $val) {
      $input = array('id' => $key);
      if ($val == 1) {
         $PluginItem->check($key, UPDATE);
         $PluginItem->delete($input);
      }
   }
   Html::back();

} else if (isset($_POST["deleteappliance"])) {
   $input = array('id' => $_POST["id"]);
   $PluginItem->check($_POST["id"], UPDATE);
   $PluginItem->delete($input);
   Html::back();

} else {
   $PluginAppliances->checkGlobal(READ);

   //check environment meta-plugin installtion for change header
   $plugin = new Plugin();
   if ($plugin->isActivated("environment")) {
      Html::header(PluginAppliancesAppliance::getTypeName(2),
                     '',"assets","pluginenvironmentdisplay","appliances");
   } else {
      Html::header(PluginAppliancesAppliance::getTypeName(2), '', "assets",
                   "pluginappliancesmenu");
   }
   $PluginAppliances->display($_GET);

   Html::footer();
}
?>