<?php
/*
 * @version $Id: appliancepdf.class.php 246 2016-12-05 17:14:42Z yllen $
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
 * Class PluginAppliancesAppliancePDF
**/
class PluginAppliancesAppliancePDF extends PluginPdfCommon {


    /**
     * PluginAppliancesAppliancePDF constructor.
     *
     * @param $obj   CommonGLPI $object (Default NULL)
    **/
    function __construct(CommonGLPI $obj=NULL) {
      $this->obj = ($obj ? $obj : new PluginAppliancesAppliance());
   }


    /**
     * Define tabs to display
     *
     * @see CommonGLPI final defineAllTabs()
    **/
    function defineAllTabs($options=array()) {

      $onglets = parent::defineAllTabs($options);
      unset($onglets['Item_Problem$1']); // TODO add method to print linked Problems
      return $onglets;
   }


    /**
     * show Tab content
     *
     * @param $pdf                  instance of plugin PDF
     * @param $item        string   CommonGLPI object
     * @param $tab         string   CommonGLPI
     *
     * @return bool
    **/
    static function displayTabContentForPDF(PluginPdfSimplePDF $pdf, CommonGLPI $item, $tab) {

      switch ($tab) {
         case 'PluginAppliancesAppliance$main' :
            $item->show_PDF($pdf);
            break;

         case 'PluginAppliancesAppliance_item$1' :
            PluginAppliancesAppliance_Item::pdfForAppliance($pdf);
            break;

         default :
            return false;
      }
      return true;
   }
}