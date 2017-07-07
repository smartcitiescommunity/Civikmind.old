<?php
/*
 * @version $Id: appliancetype.class.php 246 2016-12-05 17:14:42Z yllen $
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


/**
 * Class PluginAppliancesApplianceType
**/
class PluginAppliancesApplianceType extends CommonDropdown {

   static $rightname = "plugin_appliances";


   /**
    * Return the localized name of the current Type
    *
    *  @see CommonGLPI::getTypeName()
    **/
    static function getTypeName($nb=0) {
      return __('Type of appliance', 'appliances');
   }


    /**
     * Prepare input datas for adding the item
     *
     * @see CommonDBTM prapareInputForAdd()
    **/
    function prepareInputForAdd($input) {

      if (array_key_exists('externalid',$input) && !$input['externalid']) {
         // INSERT NULL as this value is an UNIQUE index
         unset($input['externalid']);
      }
      return $input;
   }


    /**
     * @param $ID
     * @param $entity
     *
     * @return integer or boolean
    **/
    static function transfer($ID, $entity) {
      global $DB;

      $temp = new self();
      if (($ID <= 0) || !$temp->getFromDB($ID)) {
         return 0;
      }

      $query = "SELECT `id`
                FROM `".$temp->getTable()."`
                WHERE `entities_id` = '".$entity."'
                      AND `name` = '".addslashes($temp->fields['name'])."'";

      foreach ($DB->request($query) as $data) {
         return $data['id'];
      }
      $input                = $temp->fields;
      $input['entities_id'] = $entity;
      unset($input['id']);
      return $temp->add($input);
   }
}
