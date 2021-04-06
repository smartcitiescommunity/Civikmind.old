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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * @since 0.84
**/
class SsoVariable extends CommonDropdown {

   static $rightname = 'config';

   public $can_be_translated = false;


   static function getTypeName($nb = 0) {

      return _n('Field storage of the login in the HTTP request',
                'Fields storage of the login in the HTTP request', $nb);
   }


   static function canCreate() {
      return static::canUpdate();
   }


   /**
    * @since 0.85
   **/
   static function canPurge() {
      return static::canUpdate();
   }


   function cleanRelationData() {

      parent::cleanRelationData();

      if ($this->isUsedInAuth()) {
         $newval = (isset($this->input['_replace_by']) ? $this->input['_replace_by'] : 0);

         Config::setConfigurationValues(
            'core',
            [
               'ssovariables_id' => $newval,
            ]
         );
      }
   }


   function isUsed() {

      if (parent::isUsed()) {
         return true;
      }

      return $this->isUsedInAuth();
   }


   /**
    * Check if variable is used in auth process.
    *
    * @return boolean
    */
   private function isUsedInAuth() {

      $config_values = Config::getConfigurationValues('core', ['ssovariables_id']);

      return array_key_exists('ssovariables_id', $config_values)
         && $config_values['ssovariables_id'] == $this->fields['id'];
   }
}
