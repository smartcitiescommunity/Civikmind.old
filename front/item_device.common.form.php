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
 * @since 0.85
 */

/**
 * Following variables have to be defined before inclusion of this file:
 * @var Item_Devices $item_device
 */

use Glpi\Event;

if (!($item_device instanceof Item_Devices)) {
   Html::displayErrorAndDie('');
}
if (!$item_device->canView()) {
   // Gestion timeout session
   Session::redirectIfNotLoggedIn();
   Html::displayRightError();
}


if (isset($_POST["id"])) {
   $_GET["id"] = $_POST["id"];
} else if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

if (isset($_POST["add"])) {
   $item_device->check(-1, CREATE, $_POST);
   if ($newID = $item_device->add($_POST)) {
      Event::log($newID, get_class($item_device), 4, "setup",
                 sprintf(__('%1$s adds an item'), $_SESSION["glpiname"]));

      if ($_SESSION['glpibackcreated']) {
         Html::redirect($item_device->getLinkURL());
      }
   }
   Html::back();
} else if (isset($_POST["purge"])) {
   $item_device->check($_POST["id"], PURGE);
   $item_device->delete($_POST, 1);

   Event::log($_POST["id"], get_class($item_device), 4, "setup",
              //TRANS: %s is the user login
              sprintf(__('%s purges an item'), $_SESSION["glpiname"]));

   $device = $item_device->getOnePeer(1);
   Html::redirect($device->getLinkURL());

} else if (isset($_POST["update"])) {
   $item_device->check($_POST["id"], UPDATE);
   $item_device->update($_POST);

   Event::log($_POST["id"], get_class($item_device), 4, "setup",
              //TRANS: %s is the user login
              sprintf(__('%s updates an item'), $_SESSION["glpiname"]));
   Html::back();

} else {

   if (in_array($item_device->getType(), $CFG_GLPI['devices_in_menu'])) {
      Html::header($item_device->getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", strtolower($item_device->getType()));
   } else {
      Html::header($item_device->getTypeName(Session::getPluralNumber()), '', "config", "commondevice", $item_device->getDeviceType());
   }

   if (!isset($options)) {
      $options = [];
   }
   $options['id'] = $_GET["id"];
   $item_device->display($options);
   Html::footer();
}
