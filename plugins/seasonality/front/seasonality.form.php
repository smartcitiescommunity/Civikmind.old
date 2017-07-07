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
 
include ('../../../inc/includes.php');

if (empty($_GET["id"])) {
   $_GET["id"] = "";
}

$item = new PluginSeasonalitySeasonality();

if (isset($_POST["add"])) {
   // Check add rights for fields
   $item->check(-1, CREATE, $_POST);
   $newID = $item->add($_POST);

   if ($_SESSION['glpibackcreated']) {
      Html::redirect($item->getFormURL()."?id=".$newID);
   } else {
      Html::back();
   }

} elseif (isset($_POST["update"])) {
   // Check update rights for fields
   $item->check($_POST['id'], UPDATE, $_POST);
   $item->update($_POST);
   Html::back();

} elseif (isset($_POST["purge"])) {
   // Check delete rights for fields
   $item->check($_POST['id'], PURGE, $_POST);
   $item->delete($_POST, 1);
   $item->redirectToList();
   
} else {
   $item->checkGlobal(READ);
   Html::header(PluginSeasonalitySeasonality::getTypeName(1), '', "helpdesk", "pluginseasonalityseasonality", "seasonality");
   $item->display($_GET);
   Html::footer();
}
?>