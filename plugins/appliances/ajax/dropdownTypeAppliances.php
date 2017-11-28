<?php
/*
 * @version $Id: dropdownTypeAppliances.php 258 2017-10-10 13:21:54Z yllen $
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
 @copyright Copyright (c) 2009-2017 Appliances plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/appliances
 @since     version 2.0
 --------------------------------------------------------------------------
 */

if (strpos($_SERVER['PHP_SELF'],"dropdownTypeAppliances.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

Session::checkCentralAccess();

// Make a select box
if (isset($_POST["appliancetype"])) {
   $used = [];

   // Clean used array
   if (isset($_POST['used']) && is_array($_POST['used']) && (count($_POST['used']) > 0)) {
      foreach ($DB->request(['SELECT' => 'id',
                             'FROM'   => 'glpi_plugin_appliances_appliances',
                             'WHERE'  => ['id' => $_POST['used'],
                                          'plugin_appliances_appliancetypes_id'
                                               => $_POST["appliancetype"]]]) AS $data) {
         $used[$data['id']] = $data['id'];
      }
   }

   Dropdown::show('PluginAppliancesAppliance',
                  ['name'      => $_POST['myname'],
                   'used'      => $used,
                   'width'     => '50%',
                   'entity'    => $_POST['entity'],
                   'rand'      => $_POST['rand'],
                   'condition' => "glpi_plugin_appliances_appliances.plugin_appliances_appliancetypes_id='".$_POST["appliancetype"]."'"]);

}
