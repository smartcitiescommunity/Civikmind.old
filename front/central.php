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

if (!(isset($_GET["embed"])
      && isset($_GET["dashboard"]))) {
   Session::checkCentralAccess();
}

// embed (anonymous) dashboard
if (isset($_GET["embed"]) && isset($_GET["dashboard"])) {
   $grid      = new Glpi\Dashboard\Grid($_GET["dashboard"]);
   $dashboard = $grid->getDashboard();
   Html::popHeader($dashboard->getTitle(), $_SERVER['PHP_SELF'], false, 'central', 'central');
   echo $grid->embed($_REQUEST);
   Html::popFooter();
   exit;
}

// Change profile system
if (isset($_POST['newprofile'])) {
   if (isset($_SESSION["glpiprofiles"][$_POST['newprofile']])) {
      Session::changeProfile($_POST['newprofile']);
      if (Session::getCurrentInterface() == "helpdesk") {
         if ($_SESSION['glpiactiveprofile']['create_ticket_on_login']) {
            Html::redirect($CFG_GLPI['root_doc'] . "/front/helpdesk.public.php?create_ticket=1");
         } else {
            Html::redirect($CFG_GLPI['root_doc']."/front/helpdesk.public.php");
         }
      }
      $_SESSION['_redirected_from_profile_selector'] = true;
      Html::redirect($_SERVER['HTTP_REFERER']);
   }
   Html::redirect(preg_replace("/entities_id.*/", "", $_SERVER['HTTP_REFERER']));
}

// Manage entity change
if (isset($_GET["active_entity"])) {
   $_GET["active_entity"] = rtrim($_GET["active_entity"], 'r');
   if (!isset($_GET["is_recursive"])) {
      $_GET["is_recursive"] = 0;
   }
   if (Session::changeActiveEntities($_GET["active_entity"], $_GET["is_recursive"])) {
      if (($_GET["active_entity"] == $_SESSION["glpiactive_entity"])
          && isset($_SERVER['HTTP_REFERER'])) {
         Html::redirect(preg_replace("/(\?|&|".urlencode('?')."|".urlencode('&').")?(entities_id|active_entity).*/", "", $_SERVER['HTTP_REFERER']));
      }
   }
}

Html::header(Central::getTypeName(1), $_SERVER['PHP_SELF'], 'central', 'central');

// Redirect management
if (isset($_GET["redirect"])) {
   Toolbox::manageRedirect($_GET["redirect"]);
}

$central = new Central();
$central->display();

Html::footer();

