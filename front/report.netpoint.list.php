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

Session::checkRight("reports", READ);

if (isset($_POST["prise"]) && $_POST["prise"]) {
   Html::header(Report::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "report");

   Report::title();

   $name = Dropdown::getDropdownName("glpi_netpoints", $_POST["prise"]);

   // Titre
   echo "<div class='center spaced'><h2>".sprintf(__('Network report by outlet: %s'), $name).
        "</h2></div>";

   Report::reportForNetworkInformations(
      'glpi_netpoints', //from
      ['PORT_1' => 'id', 'glpi_networkportethernets' => 'networkports_id'], //joincrit
      ['glpi_netpoints.id' => (int) $_POST["prise"]], //where
      ['glpi_locations.completename AS extra'], //select
      [
         'glpi_locations'  => [
            'ON'  => [
               'glpi_locations'  => 'id',
               'glpi_netpoints'  => 'locations_id'
            ]
         ]
      ], //left join
      [
         'glpi_networkportethernets'   => [
            'ON'  => [
               'glpi_networkportethernets'   => 'netpoints_id',
               'glpi_netpoints'              => 'id'
            ]
         ]
      ], //inner join
      [], //order
      Location::getTypeName()
   );

   Html::footer();

} else {
   Html::redirect($CFG_GLPI['root_doc']."/front/report.networking.php");
}
