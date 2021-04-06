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

if (isset($_GET["itemtype"])) {
   $itemtype = $_GET['itemtype'];
   $link     = $itemtype::getFormURL();

   // Get right sector
   $sector   = 'assets';

   //Get sectors from the menu
   $menu     = Html::getMenuInfos();

   //Try to find to which sector the itemtype belongs
   foreach ($menu as $menusector => $infos) {
      if (isset($infos['types']) && in_array($itemtype, $infos['types'])) {
         $sector = $menusector;
         break;
      }
   }

   Html::header(__('Manage templates...'), $_SERVER['PHP_SELF'], $sector, $itemtype);

   CommonDBTM::listTemplates($itemtype, $link, $_GET["add"]);

   Html::footer();
}
