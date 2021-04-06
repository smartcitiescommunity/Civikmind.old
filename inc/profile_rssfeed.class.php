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

/// Class Profile_RSSFeed
/// @since 0.84
class Profile_RSSFeed extends CommonDBRelation {

   // From CommonDBRelation
   static public $itemtype_1          = 'RSSFeed';
   static public $items_id_1          = 'rssfeeds_id';
   static public $itemtype_2          = 'Profile';
   static public $items_id_2          = 'profiles_id';

   static public $checkItem_2_Rights  = self::DONT_CHECK_ITEM_RIGHTS;
   static public $logs_for_item_2     = false;


   /**
    * Get profiles for a rssfeed
    *
    * @param $rssfeeds_id ID of the rssfeed
    *
    * @return array of profiles linked to a rssfeed
   **/
   static function getProfiles($rssfeeds_id) {
      global $DB;

      $prof  = [];
      $iterator = $DB->request([
         'FROM'   => self::getTable(),
         'WHERE'  => ['rssfeeds_id' => $rssfeeds_id]
      ]);

      while ($data = $iterator->next()) {
         $prof[$data['profiles_id']][] = $data;
      }
      return $prof;
   }

}
