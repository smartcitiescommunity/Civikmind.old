<?php
/*
 * @version $Id: applianceinjection.class.php 246 2016-12-05 17:14:42Z yllen $
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

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginAppliancesApplianceInjection
**/
class PluginAppliancesApplianceInjection extends PluginAppliancesAppliance
   implements PluginDatainjectionInjectionInterface {


   static function getTable() {

      $parenttype = get_parent_class();
      return $parenttype::getTable();

   }


   function isPrimaryType() {
      return true;
   }


   function connectedTo() {
      return array();
   }


   function getOptions($primary_type = '') {

      $tab = Search::getOptions(get_parent_class($this));

      //Specific to location
      $tab[3]['linkfield'] = 'locations_id';

      //Remove some options because some fields cannot be imported
      $blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions(get_parent_class($this));
      $notimportable = array(5, 9, 31, 91, 92, 93);
      $options['ignore_fields'] = array_merge($blacklist, $notimportable);

      $key = array_search(2, $options['ignore_fields']);
      unset($options['ignore_fields'][$key]);

      $options['displaytype']   = array("dropdown"       => array(2, 3, 8, 10, 32, 49),
                                        "user"           => array(6, 24),
                                        "multiline_text" => array(4),
                                        "bool"           => array(7, 11));

      $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);

      return $tab;
   }


   /**
    * Standard method to delete an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    *
    * @param $values       array   fields to add into glpi
    * @param $options      array   options used during creation
   **/
   function deleteObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->deleteObject();
      return $lib->getInjectionResults();
   }


   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    *
    * @param $values    array   fields to add into glpi
    * @param $options   array   options used during creation
    *
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
   **/
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }

}
