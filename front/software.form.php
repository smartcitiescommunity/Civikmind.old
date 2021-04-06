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

use Glpi\Event;

include ('../inc/includes.php');

Session::checkRight("software", READ);

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
   $_GET["withtemplate"] = "";
}

$soft = new Software();
if (isset($_POST["add"])) {
   $soft->check(-1, CREATE, $_POST);

   if ($newID = $soft->add($_POST)) {
      Event::log($newID, "software", 4, "inventory",
                 sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"]));
      if ($_SESSION['glpibackcreated']) {
         Html::redirect($soft->getLinkURL());
      }
   }
   Html::back();

} else if (isset($_POST["delete"])) {
   $soft->check($_POST["id"], DELETE);
   $soft->delete($_POST);

   Event::log($_POST["id"], "software", 4, "inventory",
              //TRANS: %s is the user login
              sprintf(__('%s deletes an item'), $_SESSION["glpiname"]));

   $soft->redirectToList();

} else if (isset($_POST["restore"])) {
   $soft->check($_POST["id"], DELETE);

   $soft->restore($_POST);
   Event::log($_POST["id"], "software", 4, "inventory",
              //TRANS: %s is the user login
              sprintf(__('%s restores an item'), $_SESSION["glpiname"]));
   $soft->redirectToList();

} else if (isset($_POST["purge"])) {
   $soft->check($_POST["id"], PURGE);

   $soft->delete($_POST, 1);
   Event::log($_POST["id"], "software", 4, "inventory",
              //TRANS: %s is the user login
              sprintf(__('%s purges an item'), $_SESSION["glpiname"]));
   $soft->redirectToList();

} else if (isset($_POST["update"])) {
   $soft->check($_POST["id"], UPDATE);

   $soft->update($_POST);
   Event::log($_POST["id"], "software", 4, "inventory",
              //TRANS: %s is the user login
              sprintf(__('%s updates an item'), $_SESSION["glpiname"]));
   Html::back();

} else {
   Html::header(Software::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "software");
   $soft->display([
      'id'           => $_GET["id"],
      'withtemplate' => $_GET["withtemplate"],
      'formoptions'  => "data-track-changes=true"
   ]);
   Html::footer();
}
