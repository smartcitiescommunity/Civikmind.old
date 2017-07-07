<?php
/*
 * @version $Id: appliance.php 246 2016-12-05 17:14:42Z yllen $
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

include ("../../../inc/includes.php");

$plugin = new Plugin();

if ($plugin->isActivated("environment")) {
   Html::header(PluginAppliancesAppliance::getTypeName(2)
                  ,'',"assets","pluginenvironmentdisplay","appliances");
} else {
   Html::header(PluginAppliancesAppliance::getTypeName(2), '', "assets","pluginappliancesmenu");

}

if (Session::haveRight("plugin_appliances", READ)
    || Session::haveRight("config", UPDATE)) {
   Search::show('PluginAppliancesAppliance');

} else {
   Html::displayRightError();
}
Html::footer();
