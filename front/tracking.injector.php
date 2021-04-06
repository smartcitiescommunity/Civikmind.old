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

if (empty($_POST["_type"])
    || ($_POST["_type"] != "Helpdesk")
    || !$CFG_GLPI["use_anonymous_helpdesk"]) {
   Session::checkRight("ticket", CREATE);
}

$track = new Ticket();

// Security check
if (empty($_POST) || (count($_POST) == 0)) {
   Html::redirect($CFG_GLPI["root_doc"]."/front/helpdesk.public.php");
}

if (isset($_POST["_type"]) && ($_POST["_type"] == "Helpdesk")) {
   Html::nullHeader(Ticket::getTypeName(Session::getPluralNumber()));
} else if ($_POST["_from_helpdesk"]) {
   Html::helpHeader(__('Simplified interface'), '', $_SESSION["glpiname"]);
} else {
   Html::header(__('Simplified interface'), '', $_SESSION["glpiname"], "helpdesk", "tracking");
}

if (isset($_POST['add'])) {
   if (!$CFG_GLPI["use_anonymous_helpdesk"]) {
      $track->check(-1, CREATE, $_POST);
   } else {
      $track->getEmpty();
   }
   $_POST['check_delegatee'] = true;
   if ($track->add($_POST)) {
      if ($_SESSION['glpibackcreated']) {
         Html::redirect($track->getLinkURL());
      }
      if (isset($_POST["_type"]) && ($_POST["_type"] == "Helpdesk")) {
         echo "<div class='center spaced'>".
                __('Your ticket has been registered, its treatment is in progress.');
         Html::displayBackLink();
         echo "</div>";
      } else {
         echo "<div class='center b spaced'>";
         echo "<img src='".$CFG_GLPI["root_doc"]."/pics/ok.png' alt='".__s('OK')."'>";
         Session::addMessageAfterRedirect(__('Thank you for using our automatic helpdesk system.'));
         Html::displayMessageAfterRedirect();
         echo "</div>";
      }

   } else {
      echo "<div class='center'>";
      echo "<img src='".$CFG_GLPI["root_doc"]."/pics/warning.png' alt='".__s('Warning')."'><br>";
      Html::displayMessageAfterRedirect();
      echo "<a href='".$CFG_GLPI["root_doc"]."/front/helpdesk.public.php?create_ticket=1'>".
            __('Back')."</a></div>";

   }
   Html::nullFooter();

} else { // reload display form
   $track->showFormHelpdesk(Session::getLoginUserID());
   Html::helpFooter();
}
