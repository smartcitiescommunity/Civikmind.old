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

include ('../inc/includes.php');

Session::checkCentralAccess();

$iapp = new \Appliance_Item();
$app = new Appliance();

if (isset($_POST['update'])) {
   $iapp->check($_POST['id'], UPDATE);
   //update existing relation
   if ($iapp->update($_POST)) {
      $url = $app->getFormURLWithID($_POST['appliances_id']);
   } else {
      $url = $iapp->getFormURLWithID($_POST['id']);
   }
   Html::redirect($url);
} else if (isset($_POST['add'])) {
   $iapp->check(-1, CREATE, $_POST);
   $iapp->add($_POST);
   Html::back();
} else if (isset($_POST['purge'])) {
   $iapp->check($_POST['id'], PURGE);
   $iapp->delete($_POST, 1);
   $url = $app->getFormURLWithID($_POST['appliances_id']);
   Html::redirect($url);
}

Html::displayErrorAndDie("lost");
