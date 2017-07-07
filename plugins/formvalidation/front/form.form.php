<?php
/*
 * @version $Id$
-------------------------------------------------------------------------
GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2015 Teclib'.

http://glpi-project.org

based on GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2003-2014 by the INDEPNET Development Team.

-------------------------------------------------------------------------

LICENSE

This file is part of GLPI.

GLPI is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */

/** @file
 * @brief
 */

include ('../../../inc/includes.php');

if (empty($_GET["id"])) {
   $_GET["id"] = '';
}

Session::checkLoginUser();

$form = new PluginFormvalidationForm();
if (isset($_POST["purge"])) {
   $form->check($_POST["id"], PURGE);
   $form->delete($_POST,1);

   //Event::log($_POST["id"], "change", 4, "maintain",
   //           //TRANS: %s is the user login
   //           sprintf(__('%s purges an item'), $_SESSION["glpiname"]));
   $form->redirectToList();

} else if (isset($_POST["update"])) {
   $form->check($_POST["id"], UPDATE);
   
   $_POST["formula"] = Html::entity_decode_deep( $_POST["formula"] ) ;

   $form->update($_POST);
   //Event::log($_POST["id"], "change", 4, "maintain",
   //           //TRANS: %s is the user login
   //           sprintf(__('%s updates an item'), $_SESSION["glpiname"]));

   Html::back();

} else {
   //   Html::header(Change::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "helpdesk", "change");
   Html::header(__('Form Validation - Form','formvalidation'), $_SERVER['PHP_SELF'] , "config", "PluginFormvalidationMenu", "formvalidationform");
   $form->display($_GET);
   Html::footer();
}
?>
