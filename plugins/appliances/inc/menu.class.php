<?php
/*
 * @version $Id: optvalue.class.php 206 2013-06-13 10:29:37Z tsmr $
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
 @copyright Copyright (c) 2009-2016 Appliances plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/appliances
 @since     version 2.0
 --------------------------------------------------------------------------
 */

/**
 * Class PluginAppliancesMenu
**/
class PluginAppliancesMenu extends CommonGLPI {
   static $rightname = 'plugin_appliances';


   /**
    * Return the localized name of the current Type
    *
    * @return translated
   **/
    static function getMenuName() {
      return _n('Appliance', 'Appliances', 2, 'appliances');
   }

   /**
    * @return array
   **/
   static function getMenuContent() {

      $menu                                           = array();
      $menu['title']                                  = self::getMenuName();
      $menu['page']                                   = "/plugins/appliances/front/appliance.php";
      $menu['links']['search']                        = PluginAppliancesAppliance::getSearchURL(false);
      if (PluginAppliancesAppliance::canCreate()) {
         $menu['links']['add']                        = PluginAppliancesAppliance::getFormURL(false);
      }

      return $menu;
   }


   static function removeRightsFromSession() {

      if (isset($_SESSION['glpimenu']['tools']['types']['PluginAppliancesMenu'])) {
         unset($_SESSION['glpimenu']['tools']['types']['PluginAppliancesMenu']);
      }
      if (isset($_SESSION['glpimenu']['tools']['content']['pluginappliancesmenu'])) {
         unset($_SESSION['glpimenu']['tools']['content']['pluginappliancesmenu']);
      }
   }
}