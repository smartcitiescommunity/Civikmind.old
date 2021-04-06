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

namespace Glpi\Dashboard;

use Ramsey\Uuid\Uuid;

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class Dashboard extends \CommonDBTM {
   protected $id      = 0;
   protected $key     = "";
   protected $title   = "";
   protected $embed   = false;
   protected $items   = null;
   protected $rights  = null;

   static $all_dashboards = [];
   static $rightname = 'dashboard';


   public function __construct(string $dashboard_key = "") {
      $this->key = $dashboard_key;
   }


   static function getIndexName() {
      return "key";
   }


   /**
    * Retrieve the current dashboard from the DB (or from cache)
    * with its rights and items
    *
    * @param bool $force if true, don't use cache
    *
    * @return int
    */
   public function load(bool $force = false): int {
      $loaded = true;
      if ($force
          || count($this->fields) == 0
          || $this->fields['id'] == 0
          || strlen($this->fields['name']) == 0) {
         $loaded = $this->getFromDB($this->key);
      }

      if ($loaded) {
         if ($force || $this->items === null) {
            $this->items = Item::getForDashboard($this->fields['id']);
         }

         if ($force || $this->rights === null) {
            $this->rights = Right::getForDashboard($this->fields['id']);
         }
      }

      return $this->fields['id'] ?? false;
   }


   public function getFromDB($ID) {
      global $DB;

      $iterator = $DB->request([
         'FROM'  => self::getTable(),
         'WHERE' => [
            'key' => $ID
         ],
         'LIMIT' => 1
      ]);
      if (count($iterator) == 1) {
         $this->fields = $iterator->next();
         $this->key    = $ID;
         $this->post_getFromDB();
         return true;
      } else if (count($iterator) > 1) {
         \Toolbox::logWarning(
            sprintf(
               'getFromDB expects to get one result, %1$s found!',
               count($iterator)
            )
         );
      }

      return false;
   }


   /**
    * Return the title of the current dasbhoard
    *
    * @return string
    */
   public function getTitle(): string {
      $this->load();
      return $this->fields['name'] ?? "";
   }

   /**
    * Do we have the right to view the current dashboard
    *
    * @return bool
    */
   public function canViewCurrent(): bool {
      // check global (admin) right
      if (self::canView()) {
         return true;
      }

      $this->load();

      //check shared rights
      $rights = self::convertRights($this->rights ?? []);
      return self::checkRights($rights);
   }


   /**
    * Save the current dashboard instance to DB
    *
    * @param string $title label of the dasbhoard, will be suglified to have a corresponding key
    * @param string $context of the dasbhoard, filter the dasboard collection by a key
    * @param array $items cards for the dashboard
    * @param array $rights for the dasbhoard
    *
    * @return string
    */
   public function saveNew(
      string $title = "",
      string $context = "core",
      array $items = [],
      array $rights = []
   ): string {
      $this->fields['name']   = $title;
      $this->fields['context'] = $context;
      $this->key    = \Toolbox::slugify($title);
      $this->items  = $items;
      $this->rights = $rights;

      $this->save();

      return $this->key;
   }


   /**
    * Save current dashboard
    *
    * @param bool $skip_child skip saving rights and items
    *
    * @return void
    */
   public function save(bool $skip_child = false) {
      global $DB, $GLPI_CACHE;

      $DB->updateOrInsert(self::getTable(), [
         'key'     => $this->key,
         'name'    => $this->fields['name'],
         'context' => $this->fields['context']
      ], [
         'key'  => $this->key
      ]);

      // reload dashboard
      $this->getFromDB($this->key);

      //save items
      if (!$skip_child && count($this->items) > 0) {
         $this->saveItems($this->items);
      }

      //save rights
      if (!$skip_child && count($this->rights) > 0) {
         $this->saveRights($this->rights);
      }

      // invalidate dashboard cache
      $cache_key = "dashboard_card_".$this->key;
      $GLPI_CACHE->delete($cache_key);
   }


   function cleanDBonPurge() {
      $this->deleteChildrenAndRelationsFromDb([
         Item::class,
         Right::class,
      ]);
   }

   /**
    * Save items in DB for the current dashboard
    *
    * @param array $items cards of the dashboard, contains:
    *    - gridstack_id: unique id of the card in the grid, usually build like card_id.uuidv4
    *    - card_id: key of array return by getAllDasboardCards
    *    - x: position in grid
    *    - y: position in grid
    *    - width: size in grid
    *    - height: size in grid
    *    - card_options, sub array, depends on the card, contains at least a key color
    *
    * @return void
    */
   public function saveItems(array $items = []) {
      $this->load();
      $this->items   = $items;

      $this->deleteChildrenAndRelationsFromDb([
         Item::class,
      ]);

      Item::addForDashboard($this->fields['id'], $items);
   }

   /**
    * Save title DB for the current dashboard
    *
    * @param string $title of the current dashboard
    *
    * @return void
    */
   public function saveTitle(string $title = "") {
      if (!strlen($title)) {
         return;
      }

      $this->load();
      $this->fields['name'] = $title;
      $this->save(true);
   }


   /**
    * Save rights (share) in DB for the current dashboard
    *
    * @param array $rights contains these data:
    * - 'users_id'    => [items_id]
    * - 'groups_id'   => [items_id]
    * - 'entities_id' => [items_id]
    * - 'profiles_id' => [items_id]
    *
    * @return void
    */
   public function saveRights(array $rights = []) {
      $this->load();
      $this->rights = $rights;

      $this->deleteChildrenAndRelationsFromDb([
         Right::class,
      ]);

      Right::addForDashboard($this->fields['id'], $rights);
   }


   /**
    * Clone current Dashboard.
    * (Clean gridstack_id-id in new one)
    *
    * @return array with [title, key]
    */
   function cloneCurrent(): array {
      $this->load();

      $this->fields['name'] = sprintf(__('Copy of %s'), $this->fields['name']);
      $this->key = \Toolbox::slugify($this->fields['name']);

      // replace gridstack_id (with uuid V4) in the copy, to avoid cache issue
      $this->items = array_map(function(array $item) {
         $item['gridstack_id'] = $item['card_id'].Uuid::uuid4();

         return $item;
      }, $this->items);

      // convert right to the good format
      $this->rights = self::convertRights($this->rights);

      $this->save();

      return [
         'title' => $this->fields['name'],
         'key'   => $this->key
      ];
   }


   /**
    * Retrieve all dashboards and store them into a static var
    *
    * @param bool   $force don't check dashboard are already loaded and force their load
    * @param bool   $check_rights use to remove rights checking (use in embed)
    * @param string $context only dashboard for given context
    *
    * @return array dasboards
    */
   static function getAll(
      bool $force = false,
      bool $check_rights = true,
      string $context = 'core'
   ): array {
      global $DB;

      if (!$force && count(self::$all_dashboards) > 0) {
         return self::$all_dashboards;
      }

      // empty previous data
      self::$all_dashboards = [];

      $dashboard_criteria = [];
      if (strlen($context)) {
         $dashboard_criteria['context'] = $context;
      }

      $dashboards = iterator_to_array($DB->request(self::getTable(), ['WHERE' => $dashboard_criteria]));
      $items      = iterator_to_array($DB->request(Item::getTable()));
      $rights     = iterator_to_array($DB->request(Right::getTable()));

      foreach ($dashboards as $dashboard) {
         $key = $dashboard['key'];
         $id  = $dashboard['id'];

         $d_rights = array_filter($rights, function($right_line) use($id) {
            return $right_line['dashboards_dashboards_id'] == $id;
         });
         if ($check_rights && !self::checkRights(self::convertRights($d_rights))) {
            continue;
         }
         $dashboard['rights'] = self::convertRights($d_rights);

         $d_items = array_filter($items, function($item) use($id) {
            return $item['dashboards_dashboards_id'] == $id;
         });
         $d_items = array_map(function($item) {
            $item['card_options'] = importArrayFromDB($item['card_options']);
            return $item;
         }, $d_items);
         $dashboard['items'] = $d_items;

         self::$all_dashboards[$key] = $dashboard;
      }

      return self::$all_dashboards;
   }


   /**
    * Convert right from DB entries to a array with type foreign keys.
    * Ex:
    * IN
    * [
    *    [
    *       'itemtype' => 'Entity'
    *       'items_id' => yyy
    *    ], [
    *       ...
    *    ],
    * ]
    *
    * OUT
    * [
    *   'entities_id' => [...]
    *   'profiles_id' => [...]
    *   'users_id'    => [...]
    *   'groups_id'   => [...]
    * ]
    *
    * @param array $raw_rights right from DB
    *
    * @return array converter rights
    */
   static function convertRights(array $raw_rights = []): array {
      $rights = [
         'entities_id' => [],
         'profiles_id' => [],
         'users_id'    => [],
         'groups_id'   => [],
      ];
      foreach ($raw_rights as $right_line) {
         $fk = getForeignKeyFieldForItemType($right_line['itemtype']);
         $rights[$fk][] = $right_line['items_id'];
      }

      return $rights;
   }


   /**
    * Check a current set of rights
    *
    * @param array $rights
    *
    * @return bool
    */
   static function checkRights(array $rights = []): bool {
      // check global (admin) right
      if (self::canView()) {
         return true;
      }

      $default_rights = [
         'entities_id' => [],
         'profiles_id' => [],
         'users_id'    => [],
         'groups_id'   => [],
      ];
      $rights = array_merge_recursive($default_rights, $rights);

      // check specific rights
      if (count(array_intersect($rights['entities_id'], $_SESSION['glpiactiveentities']))
          || count(array_intersect($rights['profiles_id'], array_keys($_SESSION['glpiprofiles'])))
          || in_array($_SESSION['glpiID'], $rights['users_id'])
          || count(array_intersect($rights['groups_id'], $_SESSION['glpigroups']))) {
         return true;
      }

      return false;
   }


   /**
    * Import dashboards from a variable

    * @param string|array $import json or php array representing the dashboards collection
    * [
    *    dashboard_key => [
    *       'title'  => '...',
    *       'items'  => [...],
    *       'rights' => [...],
    *    ], [
    *       ...
    *    ]
    * ]
    *
    * @return bool
    */
   static function importFromJson($import = null) {
      if (!is_array($import)) {
         $import = json_decode($import, true);
         if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
         }
      }

      foreach ($import as $key => $dashboard) {
         $dash_object = new self($key);
         $dash_object->saveNew(
            $dashboard['title']  ?? $key,
            $dashboard['context']  ?? "core",
            $dashboard['items']  ?? [],
            $dashboard['rights'] ?? []
         );
      }

      return true;
   }
}
