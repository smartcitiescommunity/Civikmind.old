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
 * @since 0.85 (before migration_cleaner)
**/
class MigrationCleaner extends CommonGLPI {

   static $rightname = 'networking';


   static function getTypeName($nb = 0) {
      return __('Migration cleaner');
   }


   /**
    * @see CommonGLPI::getAdditionalMenuOptions()
   **/
   static function getAdditionalMenuOptions() {

      if (static::canView()) {
         $options['networkportmigration']['title']  = NetworkPortMigration::getTypeName(Session::getPluralNumber());
         $options['networkportmigration']['page']   = NetworkPortMigration::getSearchURL(false);
         $options['networkportmigration']['search'] = NetworkPortMigration::getSearchURL(false);

         return $options;
      }
      return false;
   }


   static function canView() {
      global $DB;

      if (!isset($_SESSION['glpishowmigrationcleaner'])) {

         if ($DB->tableExists('glpi_networkportmigrations')
             && (countElementsInTable('glpi_networkportmigrations') > 0)) {
            $_SESSION['glpishowmigrationcleaner'] = true;
         } else {
            $_SESSION['glpishowmigrationcleaner'] = false;
         }
      }

      if ($_SESSION['glpishowmigrationcleaner']
          && (Session::haveRight("networking", UPDATE)
              || Session::haveRight("internet", UPDATE))) {
         return true;
      }

      return false;
   }

   static function getIcon() {
      return "fas fa-broom";
   }

}
