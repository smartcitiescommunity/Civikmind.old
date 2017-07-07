<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 seasonality plugin for GLPI
 Copyright (C) 2009-2016 by the seasonality Development Team.

 https://github.com/InfotelGLPI/seasonality
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of seasonality.

 seasonality is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 seasonality is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with seasonality. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

class PluginSeasonalityItemInjection extends PluginSeasonalityItem
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

      //$blacklist = PluginDatainjectionCommonInjectionLib::getBlacklistedOptions();
      //Remove some options because some fields cannot be imported
      $notimportable = array();
      $options['ignore_fields'] = $notimportable;
      $options['displaytype'] = array("relation" => array(180));

      $tab = PluginDatainjectionCommonInjectionLib::addToSearchOptions($tab, $options, $this);

      return $tab;
   }

   /**
    * Standard method to add an object into glpi
    * WILL BE INTEGRATED INTO THE CORE IN 0.80
    * @param values fields to add into glpi
    * @param options options used during creation
    * @return an array of IDs of newly created objects : for example array(Computer=>1, Networkport=>10)
    */
   function addOrUpdateObject($values=array(), $options=array()) {

      $lib = new PluginDatainjectionCommonInjectionLib($this,$values,$options);
      $lib->processAddOrUpdate();
      return $lib->getInjectionResults();
   }
   
   function customDataAlreadyInDB($injectionClass, $values, $options){
      
      if (isset($values['PluginSeasonalityItem']['plugin_seasonality_seasonalities_id']) && $values['PluginSeasonalityItem']['plugin_seasonality_seasonalities_id']) {
         return true;
      }
      
      return false;
   }
   
   function customimport($toinject, $add, $rights){

      $item = new PluginSeasonalityItem();
      return $item->add(array('itilcategories_id'                   => $toinject['items_id'], 
                              'plugin_seasonality_seasonalities_id' => $toinject['plugin_seasonality_seasonalities_id']));
   }
}

?>