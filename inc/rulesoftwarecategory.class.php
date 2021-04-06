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
* Rule class store all information about a GLPI rule :
*   - description
*   - criterias
*   - actions
*
**/
class RuleSoftwareCategory extends Rule {

   // From Rule
   static $rightname = 'rule_softwarecategories';
   public $can_sort  = true;


   function getTitle() {
      return __('Rules for assigning a category to software');
   }


   /**
    * @see Rule::maxActionsCount()
   **/
   function maxActionsCount() {
      return 1;
   }


   function getCriterias() {

      static $criterias = [];

      if (count($criterias)) {
         return $criterias;
      }

      $criterias['name']['field']         = 'name';
      $criterias['name']['name']          = _n('Software', 'Software', Session::getPluralNumber());
      $criterias['name']['table']         = 'glpi_softwares';

      $criterias['manufacturer']['field'] = 'name';
      $criterias['manufacturer']['name']  = __('Publisher');
      $criterias['manufacturer']['table'] = 'glpi_manufacturers';

      $criterias['comment']['field']      = 'comment';
      $criterias['comment']['name']       = __('Comments');
      $criterias['comment']['table']      = 'glpi_softwares';

      $criterias['_system_category']['field'] = 'name';
      $criterias['_system_category']['name']  = __('Category from inventory tool');

      return $criterias;
   }


   function getActions() {

      $actions                                   = [];

      $actions['softwarecategories_id']['name']  = __('Category');
      $actions['softwarecategories_id']['type']  = 'dropdown';
      $actions['softwarecategories_id']['table'] = 'glpi_softwarecategories';
      $actions['softwarecategories_id']['force_actions'] = ['assign','regex_result'];

      $actions['_import_category']['name'] = __('Import category from inventory tool');
      $actions['_import_category']['type'] = 'yesonly';

      $actions['_ignore_import']['name']  = __('To be unaware of import');
      $actions['_ignore_import']['type']  = 'yesonly';

      return $actions;
   }

}
