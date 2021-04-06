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

use CommonGLPI;
use DBConnection;
use QueryExpression;
use Change;
use CommonITILActor;
use CommonITILValidation;
use CommonTreeDropdown;
use CommonDBTM;
use CommonITILObject;
use Group;
use Group_Ticket;
use Problem;
use QuerySubQuery;
use Session;
use Search;
use Stat;
use Ticket;
use Ticket_User;
use Toolbox;
use User;

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Provider class
**/
class Provider extends CommonGLPI {


   /**
    * Retrieve the number of element for a given item
    *
    * @param CommonDBTM|null object to count
    *
    * @param array $params default values for
    * - 'apply_filters' values from dashboard filters
    *
    * @return array :
    * - 'number'
    * - 'url'
    * - 'label'
    * - 'icon'
    */
   static function bigNumberItem(CommonDBTM $item = null, array $params = []): array {
      $DB = DBConnection::getReadConnection();

      $default_params = [
         'apply_filters'  => [],
      ];
      $params = array_merge($default_params, $params);

      $i_table = $item::getTable();

      $where = [];
      if (isset($item->fields['is_deleted'])) {
         $where['is_deleted'] = 0;
      }

      if (isset($item->fields['is_template'])) {
         $where['is_template'] = 0;
      }

      if ($item->isEntityAssign()) {
         $where += getEntitiesRestrictCriteria($item::getTable());
      }

      $criteria = array_merge_recursive(
         [
            'COUNT'  => 'cpt',
            'FROM'   => $i_table,
            'WHERE'  => $where
         ],
         self::getFiltersCriteria($i_table, $params['apply_filters']),
         $item instanceof Ticket ? Ticket::getCriteriaFromProfile() : []
      );
      $iterator = $DB->request($criteria);

      $result   = $iterator->next();
      $nb_items = $result['cpt'];

      $search_criteria = [
         [
            self::getSearchFiltersCriteria($i_table, $params['apply_filters'])
         ]
      ];

      $url = $item::getSearchURL()."?".Toolbox::append_params([
         $search_criteria,
         'reset'
      ]);

      return [
         'number' => $nb_items,
         'url'    => $url,
         'label'  => $item::getTypeName($nb_items),
         'icon'   => $item::getIcon(),
      ];
   }


   /**
    * @method self::bigNumberItem
    * @method self::nbItemByFk
    */
   public static function __callStatic(string $name = "", array $arguments = []) {
      if (strpos($name, 'bigNumber') !== false) {
         $itemtype = str_replace('bigNumber', '', $name);
         if (is_subclass_of($itemtype, 'CommonDBTM')) {
            $item = new $itemtype;
            $item->getEmpty();
            return self::bigNumberItem($item, $arguments[0] ?? []);
         }
      }

      if (strpos($name, 'multipleNumber') !== false
          && strpos($name, 'By') !== false) {
         $tmp = str_replace('multipleNumber', '', $name);
         $tmp = explode('By', $tmp);

         if (count($tmp) === 2) {
            $itemtype    = $tmp[0];
            $fk_itemtype = $tmp[1];

            return self::nbItemByFk(
               new $itemtype,
               new $fk_itemtype,
               $arguments[0] ?? []
            );
         }
      }

      if (strpos($name, 'getArticleList') !== false) {
         $itemtype = str_replace('getArticleList', '', $name);
         if (is_subclass_of($itemtype, 'CommonDBTM')) {
            $item = new $itemtype;
            $item->getEmpty();
            return self::articleListItem($item, $arguments[0] ?? []);
         }
      }
   }


   /**
    * Count number of tickets for a given case
    *
    * @param string $case:
    * - 'notold': not closed or solved tickets
    * - 'late': late tickets
    * - 'waiting_validation': tickets waiting validation for connected user
    * - 'incoming': ticket with incoming status
    * - 'waiting': ticket with waiting status
    * - 'assigned': ticket with assigned status
    * - 'planned': ticket with planned status
    * - 'solved': ticket with solved status
    * - 'closed': ticket with closed status
    * @param array $params default values for
    * - 'title' of the card
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array :
    * - 'number'
    * - 'url'
    * - 'label'
    * - 'icon'
    */
   static function nbTicketsGeneric(
      string $case = "",
      array $params = []
   ):array {
      $DBread = DBConnection::getReadConnection();

      $default_params = [
         'label'         => "",
         'icon'          => Ticket::getIcon(),
         'apply_filters' => [],
      ];
      $params = array_merge($default_params, $params);

      $nb_tickets      = 0;
      $query_criteria  = [];
      $search_criteria = [];
      $skip = false;

      $notold = [
         'field'      => 12,
         'searchtype' => 'equals',
         'value'      => 'notold',
      ];

      $table = Ticket::getTable();
      $query_criteria = [
         'FROM'   => $table,
         'WHERE'  => [
            "$table.is_deleted" => 0,
         ] + getEntitiesRestrictCriteria($table),
         'GROUPBY' => "$table.id"
      ];

      $query_criteria = array_merge_recursive(
         $query_criteria,
         Ticket::getCriteriaFromProfile(),
         self::getFiltersCriteria($table, $params['apply_filters'])
      );

      switch ($case) {
         case 'notold':
            $search_criteria = [$notold];
            $query_criteria['WHERE']+= [
               "$table.status" => Ticket::getNotSolvedStatusArray(),
            ];
         break;

         case 'late':
            $params['icon']  = "far fa-clock";
            $params['label']  = __("Late tickets");
            $search_criteria = array_merge([$notold], [
               [
                  'link'       => 'AND',
                  'criteria'   => [
                     [
                        'field'      => 82,
                        'searchtype' => 'equals',
                        'value'      => 1,
                     ], [
                        'link'       => 'OR',
                        'field'      => 182,
                        'searchtype' => 'equals',
                        'value'      => 1,
                     ], [
                        'link'       => 'OR',
                        'field'      => 159,
                        'searchtype' => 'equals',
                        'value'      => 1,
                     ], [
                        'link'       => 'OR',
                        'field'      => 187,
                        'searchtype' => 'equals',
                        'value'      => 1,
                     ]
                  ]
               ]
            ]);
            $query_criteria['WHERE']+= [
               "$table.status" => Ticket::getNotSolvedStatusArray(),
               'OR' => [
                  CommonITILObject::generateSLAOLAComputation('time_to_resolve', 'glpi_tickets'),
                  CommonITILObject::generateSLAOLAComputation('internal_time_to_resolve', 'glpi_tickets'),
                  CommonITILObject::generateSLAOLAComputation('time_to_own', 'glpi_tickets'),
                  CommonITILObject::generateSLAOLAComputation('internal_time_to_own', 'glpi_tickets'),
               ]
            ];
            break;

         case 'waiting_validation':
            $params['icon']  = "far fa-eye";
            $params['label'] = __("Tickets waiting your validation");
            $search_criteria = [
               [
                  'field'      => 55,
                  'searchtype' => 'equals',
                  'value'      => CommonITILValidation::WAITING,
               ],  [
                  'link'       => 'AND',
                  'field'      => 59,
                  'searchtype' => 'equals',
                  'value'      => Session::getLoginUserID(),
               ]
            ];
            $query_criteria = array_merge_recursive($query_criteria, [
               'LEFT JOIN' => [
                  'glpi_ticketvalidations' => [
                     'ON' => [
                        'glpi_ticketvalidations' => 'tickets_id',
                        $table                   => 'id'
                     ]
                  ]
               ],
               'WHERE' => [
                  'glpi_ticketvalidations.status'            => CommonITILValidation::WAITING,
                  'glpi_ticketvalidations.users_id_validate' => Session::getLoginUserID()
               ]
            ]);
            break;

         // Statuses speciale cases (no break)
         case 'incoming':
            $status = Ticket::INCOMING;
            $params['icon']  = Ticket::getIcon();
            $params['label'] = __("Incoming tickets");
            $skip = true;
         case 'waiting':
            if (!$skip) {
               $status =Ticket::WAITING;
               $params['icon']  = "fas fa-pause-circle";
               $params['label'] = __("Pending tickets");
               $skip = true;
            }
         case 'assigned':
            if (!$skip) {
               $status = Ticket::ASSIGNED;
               $params['icon']  = "fas fa-users";
               $params['label'] = __("Assigned tickets");
               $skip = true;
            }
         case 'planned':
            if (!$skip) {
               $status = Ticket::PLANNED;
               $params['icon']  = "fas fa-calendar-check";
               $params['label'] = __("Planned tickets");
               $skip = true;
            }
         case 'solved':
            if (!$skip) {
               $status = Ticket::SOLVED;
               $params['icon']  = "far fa-check-square";
               $params['label'] = __("Solved tickets");
               $skip = true;
            }
         case 'closed':
            if (!$skip) {
               $status = Ticket::CLOSED;
               $params['icon']  = "fas fa-archive";
               $params['label'] = __("Closed tickets");
               $skip = true;
            }
         case 'status':
            if (!$skip) {
               $status = Ticket::INCOMING;
            }
            $search_criteria = [
               [
                  'field'      => 12,
                  'searchtype' => 'equals',
                  'value'      => $status,
               ]
            ];
            $query_criteria = array_merge_recursive($query_criteria, [
               'WHERE' => [
                  "$table.status" => $status,
               ]
            ]);
            break;
      }

      $search_criteria['criteria'] = self::getSearchFiltersCriteria($table, $params['apply_filters']);

      $url = Ticket::getSearchURL()."?".Toolbox::append_params([
         'criteria' => $search_criteria,
         'reset'    => 'reset'
      ]);

      $iterator   = $DBread->request($query_criteria);
      if ($nb_tickets === 0) {
         $nb_tickets = count($iterator);
      }

      return [
         'number'     => $nb_tickets,
         'url'        => $url,
         'label'      => $params['label'],
         'icon'       => $params['icon'],
         's_criteria' => $search_criteria,
         'itemtype'   => 'Ticket',
      ];
   }


   /**
    * Get multiple counts of computer by a specific foreign key
    *
    * @param CommonDBTM $item main item to count
    * @param CommonDBTM $fk_item groupby by this item (we will find the foreign key in the main item)
    * @param array $params values for:
    * - 'title' of the card
    * - 'icon' of the card
    * - 'searchoption_id' id corresponding to FK search option
    * - 'limit' max data to return
    * - 'join_key' LEFT, INNER, etc JOIN
    * - 'apply_filters' values from dashboard filters
    *
    * @return array :
    * - 'data': [
    *    'url'
    *    'number'
    *    'label'
    * ]
    * - 'label'
    * - 'icon'
    */
   public static function nbItemByFk(
      CommonDBTM $item = null,
      CommonDBTM $fk_item = null,
      array $params = []
   ): array {
      $DB = DBConnection::getReadConnection();

      $c_table     = $item::getTable();
      $fk_table    = $fk_item::getTable();
      $fk_itemtype = $fk_item::getType();

      // try to autodetect searchoption id
      $searchoptions = $item->rawSearchOptions();
      $found_so = array_filter($searchoptions, function($searchoption) use($fk_table) {
         return isset($searchoption['table']) && $searchoption['table'] === $fk_table;
      });
      $found_so = array_shift($found_so);
      $found_so_id = $found_so['id'] ?? 0;

      $default_params = [
         'label'           => "",
         'searchoption_id' => $found_so_id,
         'icon'            => $fk_item::getIcon() ?? $item::getIcon(),
         'limit'           => 50,
         'join_key'        => 'LEFT JOIN',
         'apply_filters'   => [],
      ];
      $params = array_merge($default_params, $params);

      $where = [];
      if ($item->maybeDeleted()) {
         $where["$c_table.is_deleted"] = 0;
      }
      if ($item->maybeTemplate()) {
         $where["$c_table.is_template"] = 0;
      }

      $name = 'name';
      if ($fk_item instanceof CommonTreeDropdown) {
         $name = 'completename';
      }

      if ($item->isEntityAssign()) {
         $where += getEntitiesRestrictCriteria($c_table, '', '', $item->maybeRecursive());
      }

      $criteria = array_merge_recursive(
         [
            'SELECT'    => [
               "$fk_table.$name AS fk_name",
               "$fk_table.id AS fk_id",
               'COUNT' => "$c_table.id AS cpt",
            ],
            'DISTINCT'  => true,
            'FROM'      => $c_table,
            $params['join_key'] => [
               $fk_table => [
                  'ON' => [
                     $fk_table => 'id',
                     $c_table  => getForeignKeyFieldForItemType($fk_itemtype),
                  ]
               ]
            ],
            'GROUPBY'   => "$fk_table.$name",
            'ORDERBY'   => "cpt DESC",
            'LIMIT'     => $params['limit'],
         ],
         count($where) ? ['WHERE' => $where] : [],
         self::getFiltersCriteria($c_table, $params['apply_filters']),
         $item instanceof Ticket ? Ticket::getCriteriaFromProfile() : []
      );
      $iterator = $DB->request($criteria);

      $search_criteria = [
         'criteria' => [
            [
               'field'      => $params['searchoption_id'],
               'searchtype' => 'equals',
               'value'      => 0
            ],
            self::getSearchFiltersCriteria($fk_table, $params['apply_filters'])
         ],
         'reset' => 'reset',
      ];

      $url = $item::getSearchURL();
      $url .= (strpos($url, '?') !== false ? '&' : '?') . 'reset';

      $data = [];
      foreach ($iterator as $result) {
         $search_criteria['criteria'][0]['value'] = $result['fk_id'] ?? 0;
         $data[] = [
            'number' => $result['cpt'],
            'label'  => $result['fk_name'] ?? __("without"),
            'url'    => $url . '&' . Toolbox::append_params($search_criteria),
         ];
      }

      if (count($data) === 0) {
         $data = [
            'nodata' => true
         ];
      }

      return [
         'data'  => $data,
         'label' => $params['label'],
         'icon'  => $params['icon'],
      ];
   }


   /**
    * Get a list of article for an compatible item (with date,name,text fields)
    *
    * @param CommonDBTM $item the itemtype to list
    * @param array   $params default values for
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array
    */
   public static function articleListItem(CommonDBTM $item = null, array $params = []): array {
      $DB = DBConnection::getReadConnection();

      $default_params = [
         'icon'          => $item::getIcon(),
         'apply_filters' => [],
      ];
      $params = array_merge($default_params, $params);

      $i_table           = $item::getTable();
      $criteria = array_merge_recursive(
         [
            'SELECT' => "$i_table.*",
            'FROM'   => $i_table
         ],
         self::getFiltersCriteria($i_table, $params['apply_filters'])
      );
      $iterator = $DB->request($criteria);

      $data = [];
      foreach ($iterator as $line) {
         $data[] = [
            'date'    => $line['date'] ?? '',
            'label'   => $line['name'] ?? '',
            'content' => $line['text'] ?? '',
            'author'  => User::getFriendlyNameById($line['users_id'] ?? 0),
            'url'     => $item::getFormURLWithID($line['id']),
         ];
      }

      $nb_items = count($data);
      if ($nb_items === 0) {
         $data = [
            'nodata' => true
         ];
      }

      return [
         'data'   => $data,
         'number' => $nb_items,
         'url'    => $item::getSearchURL(),
         'label'  => $item::getTypeName($nb_items),
         'icon'   => $item::getIcon(),
      ];
   }


   /**
    * get multiple count of ticket by month
    *
    * @param array $params default values for
    * - 'title' of the card
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array
    */
   public static function ticketsOpened(array $params = []): array {
      $DB = DBConnection::getReadConnection();
      $default_params = [
         'label'         => "",
         'icon'          => Ticket::getIcon(),
         'apply_filters' => [],
      ];
      $params = array_merge($default_params, $params);

      $t_table = Ticket::getTable();
      $criteria = array_merge_recursive(
         [
            'SELECT' => [
               'COUNT' => "$t_table.id as nb_tickets",
               new QueryExpression("DATE_FORMAT(".$DB->quoteName("date").", '%Y-%m') AS ticket_month")
            ],
            'FROM'    => $t_table,
            'GROUPBY' => 'ticket_month',
            'ORDER'   => 'ticket_month ASC'
         ],
         Ticket::getCriteriaFromProfile(),
         self::getFiltersCriteria($t_table, $params['apply_filters'])
      );
      $iterator = $DB->request($criteria);

      $s_criteria = [
         'criteria' => [
            [
               'link'       => 'AND',
               'field'      => 15,
               'searchtype' => 'morethan',
               'value'      => null
            ], [
               'link'       => 'AND',
               'field'      => 15,
               'searchtype' => 'lessthan',
               'value'      => null
            ],
         ],
         'reset' => 'reset'
      ];

      $data = [];
      foreach ($iterator as $result) {
         list($start_day, $end_day) = self::formatMonthyearDates($result['ticket_month']);

         $s_criteria['criteria'][0]['value'] = $start_day;
         $s_criteria['criteria'][1]['value'] = $end_day;

         $data[] = [
            'number' => $result['nb_tickets'],
            'label'  => $result['ticket_month'],
            'url'    => Ticket::getSearchURL()."?".Toolbox::append_params($s_criteria),
         ];
      }

      return [
         'data'        => $data,
         'distributed' => false,
         'label'       => $params['label'],
         'icon'        => $params['icon'],
      ];
   }


   /**
    * Get ticket evolution by opened, solved, closed, late series and months group
    *
    * @param array $params default values for
    * - 'title' of the card
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array
    */
   public static function getTicketsEvolution(array $params = []): array {
      $default_params = [
         'label'         => "",
         'icon'          => Ticket::getIcon(),
         'apply_filters' => [],
      ];
      $params = array_merge($default_params, $params);

      $year   = date("Y")-15;
      $begin  = date("Y-m-d", mktime(1, 0, 0, (int)date("m"), (int)date("d"), $year));
      $end    = date("Y-m-d");

      if (isset($params['apply_filters']['dates'])
      && count($params['apply_filters']['dates']) == 2) {
         $begin = date("Y-m-d", strtotime($params['apply_filters']['dates'][0]));
         $end   = date("Y-m-d", strtotime($params['apply_filters']['dates'][1]));
         unset($params['apply_filters']['dates']);
      }

      $t_table   = Ticket::getTable();

      $series = [

         'inter_total' => [
            'name'   => _nx('ticket', 'Opened', 'Opened', \Session::getPluralNumber()),
            'search' => [
               'criteria' => [
                  [
                     'link'       => 'AND',
                     'field'      => 15, // creation date
                     'searchtype' => 'morethan',
                     'value'      => null
                  ], [
                     'link'       => 'AND',
                     'field'      => 15, // creation date
                     'searchtype' => 'lessthan',
                     'value'      => null
                  ],self::getSearchFiltersCriteria($t_table, $params['apply_filters']),
               ],
               'reset' => 'reset'
            ]
         ],
         'inter_solved' => [
            'name'   => _nx('ticket', 'Solved', 'Solved', \Session::getPluralNumber()),
            'search' => [
               'criteria' => [
                  [
                     'link'       => 'AND',
                     'field'      => 17, // solve date
                     'searchtype' => 'morethan',
                     'value'      => null
                  ], [
                     'link'       => 'AND',
                     'field'      => 17, // solve date
                     'searchtype' => 'lessthan',
                     'value'      => null
                  ],self::getSearchFiltersCriteria($t_table, $params['apply_filters']),
               ],
               'reset' => 'reset'
            ]
         ],
         'inter_solved_late' => [
            'name'   => __('Late'),
            'search' => [
               'criteria' => [
                  [
                     'link'       => 'AND',
                     'field'      => 17, // solve date
                     'searchtype' => 'morethan',
                     'value'      => null
                  ], [
                     'link'       => 'AND',
                     'field'      => 17, // solve date
                     'searchtype' => 'lessthan',
                     'value'      => null
                  ], [
                     'link'       => 'AND',
                     'field'      => 82, // time_to_resolve exceed solve date
                     'searchtype' => 'equals',
                     'value'      => 1
                  ],self::getSearchFiltersCriteria($t_table, $params['apply_filters']),
               ],
               'reset' => 'reset'
            ]
         ],
         'inter_closed' => [
            'name'   => __('Closed'),
            'search' => [
               'criteria' => [
                  [
                     'link'       => 'AND',
                     'field'      => 16, // close date
                     'searchtype' => 'morethan',
                     'value'      => null
                  ], [
                     'link'       => 'AND',
                     'field'      => 16, // close date
                     'searchtype' => 'lessthan',
                     'value'      => null
                  ],self::getSearchFiltersCriteria($t_table, $params['apply_filters']),
               ],
               'reset' => 'reset'
            ]
         ],
      ];

      $filters = array_merge_recursive(
         Ticket::getCriteriaFromProfile(),
         self::getFiltersCriteria($t_table, $params['apply_filters'])
      );

      $i = 0;
      $monthsyears = [];
      foreach ($series as $stat_type => &$serie) {
         $values = Stat::constructEntryValues(
            'Ticket',
            $stat_type,
            $begin,
            $end,
            "",
            "",
            "",
            $filters
         );

         if ($i === 0) {
            $monthsyears = array_keys($values);
         }
         $values = array_values($values);

         foreach ($values as $index => $number) {
            $current_monthyear = $monthsyears[$index];
            list($start_day, $end_day) = self::formatMonthyearDates($current_monthyear);
            $serie['search']['criteria'][0]['value'] = $start_day;
            $serie['search']['criteria'][1]['value'] = $end_day;

            $serie['data'][$index] = [
               'value' => $number,
               'url'   => Ticket::getSearchURL()."?".Toolbox::append_params($serie['search']),
            ];
         }

         $i++;
      }

      return [
         'data'  => [
            'labels' => $monthsyears,
            'series' => array_values($series),
         ],
         'label' => $params['label'],
         'icon'  => $params['icon'],
      ];
   }


   /**
    * get ticket by their curent status and their opening date
    *
    * @param array $params default values for
    * - 'title' of the card
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array
    */
   public static function getTicketsStatus(array $params = []): array {
      $DB = DBConnection::getReadConnection();

      $default_params = [
         'label'          => "",
         'icon'           => Ticket::getIcon(),
         'apply_filters'  => [],
      ];
      $params = array_merge($default_params, $params);

      $statuses = Ticket::getAllStatusArray();
      $t_table  = Ticket::getTable();

      $sub_query = array_merge_recursive(
         [
            'DISTINCT' => true,
            'SELECT'   => ["$t_table.*"],
            'FROM'     => $t_table,
            'WHERE'    => [
               "$t_table.is_deleted" => 0,
            ] + getEntitiesRestrictCriteria($t_table),
         ],
         // limit count for profiles with limited rights
         Ticket::getCriteriaFromProfile(),
         self::getFiltersCriteria($t_table, $params['apply_filters'])
      );

      $criteria = [
         'SELECT'   => [
            new QueryExpression(
               "FROM_UNIXTIME(UNIX_TIMESTAMP(".$DB->quoteName("{$t_table}_distinct.date")."),'%Y-%m') AS period"
            ),
            new QueryExpression(
               "SUM(IF({$t_table}_distinct.status = ".Ticket::INCOMING.", 1, 0))
                  as ".$DB->quoteValue(_x('status', 'New'))
            ),
            new QueryExpression(
               "SUM(IF({$t_table}_distinct.status = ".Ticket::ASSIGNED.", 1, 0))
                  as ".$DB->quoteValue(_x('status', 'Processing (assigned)'))
            ),
            new QueryExpression(
               "SUM(IF({$t_table}_distinct.status = ".Ticket::PLANNED.", 1, 0))
                  as ".$DB->quoteValue(_x('status', 'Processing (planned)'))
            ),
            new QueryExpression(
               "SUM(IF({$t_table}_distinct.status = ".Ticket::WAITING.", 1, 0))
                  as ".$DB->quoteValue(__('Pending'))
            ),
            new QueryExpression(
               "SUM(IF({$t_table}_distinct.status = ".Ticket::SOLVED.", 1, 0))
                  as ".$DB->quoteValue(_x('status', 'Solved'))
            ),
            new QueryExpression(
               "SUM(IF({$t_table}_distinct.status = ".Ticket::CLOSED.", 1, 0))
                  as ".$DB->quoteValue(_x('status', 'Closed'))
            ),
         ],
         'FROM' => new QuerySubQuery($sub_query, "{$t_table}_distinct"),
         'ORDER'   => 'period ASC',
         'GROUP'    => ['period']
      ];

      $iterator = $DB->request($criteria);

      $s_criteria = [
         'criteria' => [
            [
               'link'       => 'AND',
               'field'      => 12, // status
               'searchtype' => 'equals',
               'value'      => null
            ], [
               'link'       => 'AND',
               'field'      => 15, // creation date
               'searchtype' => 'morethan',
               'value'      => null
            ], [
               'link'       => 'AND',
               'field'      => 15, // creation date
               'searchtype' => 'lessthan',
               'value'      => null
            ],
            self::getSearchFiltersCriteria($t_table, $params['apply_filters'])
         ],
         'reset' => 'reset'
      ];

      $data = [
         'labels' => [],
         'series' => []
      ];
      foreach ($iterator as $result) {
         list($start_day, $end_day) = self::formatMonthyearDates($result['period']);
         $s_criteria['criteria'][1]['value'] = $start_day;
         $s_criteria['criteria'][2]['value'] = $end_day;

         $data['labels'][] = $result['period'];
         $tmp = $result;
         unset($tmp['period'], $tmp['nb_tickets']);

         $i = 0;
         foreach ($tmp as $label2 => $value) {
            $status_key = array_search($label2, $statuses);
            $s_criteria['criteria'][0]['value'] = $status_key;

            $data['series'][$i]['name'] = $label2;
            $data['series'][$i]['data'][] = [
               'value' => (int) $value,
               'url'   => Ticket::getSearchURL()."?".Toolbox::append_params($s_criteria),
            ];
            $i++;
         }
      }

      return [
         'data'  => $data,
         'label' => $params['label'],
         'icon'  => $params['icon'],
      ];
   }


   /**
    * Get numbers of tickets grouped by actors
    *
    * @param string $case cound be:
    * - user_requester
    * - group_requester
    * - user_observer
    * - group_observer
    * - user_assign
    * - group_assign
    * @param array $params default values for
    * - 'title' of the card
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array
    */
   public static function nbTicketsActor(
      string $case = "",
      array $params = []
   ):array {
      $DBread = DBConnection::getReadConnection();
      $default_params = [
         'label'         => "",
         'icon'          => null,
         'apply_filters' => [],
      ];
      $params = array_merge($default_params, $params);

      $t_table  = Ticket::getTable();
      $li_table = Ticket_User::getTable();
      $ug_table = User::getTable();
      $n_fields = [
         "$ug_table.firstname as first",
         "$ug_table.realname as second",
      ];

      $where = [
         "$t_table.is_deleted" => 0,
      ];

      $case_array = explode('_', $case);
      if ($case_array[0] == 'user') {
         $where["$ug_table.is_deleted"]  = 0;
         $params['icon'] = $params['icon'] ?? User::getIcon();
      } else if ($case_array[0] == 'group') {
         $li_table = Group_Ticket::getTable();
         $ug_table = Group::getTable();
         $n_fields = [
            "$ug_table.completename as first"
         ];
         $params['icon'] = $params['icon'] ?? Group::getIcon();
      }

      $type = 0;
      switch ($case) {
         case "user_requester":
            $type     = CommonITILActor::REQUESTER;
            $soption  = 4;
            break;
         case "group_requester":
            $type     = CommonITILActor::REQUESTER;
            $soption  = 71;
            break;
         case "user_observer":
            $type     = CommonITILActor::OBSERVER;
            $soption  = 66;
            break;
         case "group_observer":
            $type     = CommonITILActor::OBSERVER;
            $soption  = 65;
            break;
         case "user_assign":
            $type     = CommonITILActor::ASSIGN;
            $soption  = 5;
            break;
         case "group_assign":
            $type     = CommonITILActor::ASSIGN;
            $soption  = 8;
            break;
      }

      $criteria = array_merge_recursive(
         [
            'SELECT' => array_merge([
               'COUNT' => "$t_table.id AS nb_tickets",
               "$ug_table.id as actor_id",
            ], $n_fields),
            'FROM' => $t_table,
            'INNER JOIN' => [
               $li_table => [
                  'ON' => [
                     $li_table => getForeignKeyFieldForItemType("Ticket"),
                     $t_table  => 'id',
                     [
                        'AND' => [
                           "$li_table.type" => $type
                        ]
                     ]
                  ]
               ],
               $ug_table => [
                  'ON' => [
                     $li_table => getForeignKeyFieldForTable($ug_table),
                     $ug_table  => 'id'
                  ]
               ]
            ],
            'GROUPBY' => "$ug_table.id",
            'ORDER'   => 'nb_tickets DESC',
            'WHERE'   => $where + getEntitiesRestrictCriteria($t_table),
         ],
         Ticket::getCriteriaFromProfile(),
         self::getFiltersCriteria($t_table, $params['apply_filters'])
      );
      $iterator = $DBread->request($criteria);

      $s_criteria = [
         'criteria' => [
            [
               'link'       => 'AND',
               'field'      => $soption,
               'searchtype' => 'equals',
               'value'      => null
            ],
            self::getSearchFiltersCriteria($t_table, $params['apply_filters']),
         ],
         'reset' => 'reset'
      ];
      $data = [];
      foreach ($iterator as $result) {
         $s_criteria['criteria'][0]['value'] = $result['actor_id'];
         $data[] = [
            'number' => $result['nb_tickets'],
            'label'  => $result['first']." ".($result['second'] ?? ""),
            'url'    => Ticket::getSearchURL()."?".Toolbox::append_params($s_criteria),
         ];
      }
      return [
         'data'  => $data,
         'label' => $params['label'],
         'icon'  => $params['icon'],
      ];
   }


   /**
    * get average stats (takeintoaccoutn, solve/close delay, waiting) of ticket by month
    *
    * @param array $params default values for
    * - 'title' of the card
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array
    */
   public static function averageTicketTimes(array $params = []) {
      $DBread = DBConnection::getReadConnection();
      $default_params = [
         'label'         => "",
         'icon'          => "fas fa-stopwatch",
         'apply_filters' => [],
      ];
      $params = array_merge($default_params, $params);

      $t_table  = Ticket::getTable();
      $criteria = array_merge_recursive(
         [
            'SELECT' => [
               new QueryExpression("DATE_FORMAT(".$DBread->quoteName("date").", '%Y-%m') AS period"),
               new QueryExpression("AVG(".$DBread->quoteName("takeintoaccount_delay_stat").") AS avg_takeintoaccount_delay_stat"),
               new QueryExpression("AVG(".$DBread->quoteName("waiting_duration").") AS avg_waiting_duration"),
               new QueryExpression("AVG(".$DBread->quoteName("solve_delay_stat").") AS avg_solve_delay_stat"),
               new QueryExpression("AVG(".$DBread->quoteName("close_delay_stat").") AS close_delay_stat"),
            ],
            'FROM' => $t_table,
            'WHERE' => [
               'is_deleted' => 0,
            ] + getEntitiesRestrictCriteria($t_table),
            'ORDER' => 'period ASC',
            'GROUP' => ['period']
         ],
         Ticket::getCriteriaFromProfile(),
         self::getFiltersCriteria($t_table, $params['apply_filters'])
      );
      $iterator = $DBread->request($criteria);

      $data = [
         'labels' => [],
         'series' => [
            [
               'name' => __("Time to own"),
               'data' => []
            ], [
               'name' => __("Waiting time"),
               'data' => []
            ], [
               'name' => __("Time to resolve"),
               'data' => []
            ], [
               'name' => __("Time to close"),
               'data' => []
            ]
         ]
      ];
      foreach ($iterator as $r) {
         $data['labels'][] = $r['period'];
         $tmp = $r;
         unset($tmp['period']);

         $data['series'][0]['data'][] = round($r['avg_takeintoaccount_delay_stat'] / HOUR_TIMESTAMP, 1);
         $data['series'][1]['data'][] = round($r['avg_waiting_duration'] / HOUR_TIMESTAMP, 1);
         $data['series'][2]['data'][] = round($r['avg_solve_delay_stat'] / HOUR_TIMESTAMP, 1);
         $data['series'][3]['data'][] = round($r['close_delay_stat'] / HOUR_TIMESTAMP, 1);
      }

      return [
         'data'  => $data,
         'label' => $params['label'],
         'icon'  => $params['icon'],
      ];
   }


   /**
    * get multiple count of ticket by status and month
    *
    * @param array $params default values for
    * - 'title' of the card
    * - 'icon' of the card
    * - 'apply_filters' values from dashboard filters
    *
    * @return array
    */
   public static function getTicketSummary(array $params = []) {
      $default_params = [
         'label'         => "",
         'icon'          => "",
         'apply_filters' => [],
      ];
      $params = array_merge($default_params, $params);

      $incoming   = self::nbTicketsGeneric('incoming', $params);
      $assigned   = self::nbTicketsGeneric('assigned', $params);
      $waiting    = self::nbTicketsGeneric('waiting', $params);
      $tovalidate = self::nbTicketsGeneric('waiting_validation', $params);
      $closed     = self::nbTicketsGeneric('closed', $params);

      return [
         'data'  => [
            [
               'number' => $incoming['number'],
               'label'  => __("New"),
               'url'    => $incoming['url'],
               'color'  => '#3bc519',
            ], [
               'number' => $assigned['number'],
               'label'  => __("Assigned"),
               'url'    => $assigned['url'],
               'color'  => '#f1cd29',
            ], [
               'number' => $waiting['number'],
               'label'  => __("Pending"),
               'url'    => $waiting['url'],
               'color'  => '#f1a129',
            ], [
               'number' => $tovalidate['number'],
               'label'  => __("To validate"),
               'url'    => $tovalidate['url'],
               'color'  => '#266ae9',
            ], [
               'number' => $closed['number'],
               'label'  => __("Closed"),
               'url'    => $closed['url'],
               'color'  => '#555555',
            ]
         ],
         'label' => $params['label'],
         'icon'  => $params['icon'],
      ];
   }


   public static function formatMonthyearDates(string $monthyear): array {
      $rawdate = explode('-', $monthyear);
      $year    = $rawdate[0];
      $month   = $rawdate[1];
      $monthtime = mktime(0, 0, 0, $month, 1, $year);

      $start_day = date("Y-m-d H:i:s", strtotime("first day of this month", $monthtime));
      $end_day   = date("Y-m-d H:i:s", strtotime("first day of next month", $monthtime));

      return [$start_day, $end_day];
   }

   private static function getSearchOptionID(string $table, string $name, string $tableToSearch): int {
      $data = Search::getOptions(getItemTypeForTable($table), true);
      $sort = [];
      foreach ($data as $ref => $opt) {
         if (isset($opt['field'])) {
            $sort[$ref] = $opt['linkfield']."-".$opt['table'];
         }
      }
      return array_search($name."-".$tableToSearch, $sort);
   }

   private static function getSearchFiltersCriteria(string $table = "", array $apply_filters = []) {
      $DB = DBConnection::getReadConnection();
      $s_criteria = [];

      if ($DB->fieldExists($table, 'date')
         && isset($apply_filters['dates'])
         && count($apply_filters['dates']) == 2) {
         $s_criteria['criteria'][] = self::getDatesSearchCriteria(self::getSearchOptionID($table, "date", $table), $apply_filters['dates'], 'begin');
         $s_criteria['criteria'][] = self::getDatesSearchCriteria(self::getSearchOptionID($table, "date", $table), $apply_filters['dates'], 'end');
      }

      //exclude itilobject already processed with 'date'
      if (!in_array($table, [
         Ticket::getTable(),
         Change::getTable(),
         Problem::getTable(),
      ]) && $DB->fieldExists($table, 'date_creation')
      && isset($apply_filters['dates'])
      && count($apply_filters['dates']) == 2) {
         $s_criteria['criteria'][] = self::getDatesSearchCriteria(self::getSearchOptionID($table, "date_creation", $table), $apply_filters['dates'], 'begin');
         $s_criteria['criteria'][] = self::getDatesSearchCriteria(self::getSearchOptionID($table, "date_creation", $table), $apply_filters['dates'], 'end');
      }

      if ($DB->fieldExists($table, 'date_mod')
         && isset($apply_filters['dates_mod'])
         && count($apply_filters['dates_mod']) == 2) {
         $s_criteria['criteria'][] = self::getDatesSearchCriteria(self::getSearchOptionID($table, "date_mod", $table), $apply_filters['dates_mod'], 'begin');
         $s_criteria['criteria'][] = self::getDatesSearchCriteria(self::getSearchOptionID($table, "date_mod", $table), $apply_filters['dates_mod'], 'end');
      }

      if ($DB->fieldExists($table, 'itilcategories_id')
         && isset($apply_filters['itilcategory'])
         && (int) $apply_filters['itilcategory'] > 0) {
         $s_criteria['criteria'][] = [
            'link'       => 'AND',
            'field'      => self::getSearchOptionID($table, 'itilcategories_id', 'glpi_itilcategories'), // itilcategory
            'searchtype' => 'equals',
            'value'      => (int) $apply_filters['itilcategory']
         ];
      }

      if ($DB->fieldExists($table, 'requesttypes_id')
         && isset($apply_filters['requesttype'])
         && (int) $apply_filters['requesttype'] > 0) {
         $s_criteria['criteria'][] = [
            'link'       => 'AND',
            'field'      => self::getSearchOptionID($table, 'requesttypes_id', 'glpi_requesttypes'), // request type
            'searchtype' => 'equals',
            'value'      => (int) $apply_filters['requesttype']
         ];
      }

      if ($DB->fieldExists($table, 'locations_id')
         && isset($apply_filters['location'])
         && (int) $apply_filters['location'] > 0) {
         $s_criteria['criteria'][] = [
            'link'       => 'AND',
            'field'      => self::getSearchOptionID($table, 'locations_id', 'glpi_locations'), // location
            'searchtype' => 'equals',
            'value'      => (int) $apply_filters['location']
         ];
      }

      if ($DB->fieldExists($table, 'manufacturers_id')
         && isset($apply_filters['manufacturer'])
         && (int) $apply_filters['manufacturer'] > 0) {
         $s_criteria['criteria'][] = [
            'link'       => 'AND',
            'field'      => self::getSearchOptionID($table, 'manufacturers_id', 'glpi_manufacturers'), // manufacturer
            'searchtype' => 'equals',
            'value'      => (int) $apply_filters['manufacturer']
         ];
      }

      if (isset($apply_filters['group_tech'])) {

         $groups_id = null;
         if ((int) $apply_filters['group_tech'] > 0) {
            $groups_id =  (int) $apply_filters['group_tech'];
         } else if ((int) $apply_filters['group_tech'] == -1) {
            $groups_id =  'mygroups';
         }

         if ($groups_id != null) {
            if ($DB->fieldExists($table, 'groups_id_tech')) {
               $s_criteria['criteria'][] = [
                  'link'       => 'AND',
                  'field'      => self::getSearchOptionID($table, 'groups_id_tech', 'glpi_groups'), // group tech
                  'searchtype' => 'equals',
                  'value'      => $groups_id
               ];
            } else if (in_array($table, [
               Ticket::getTable(),
               Change::getTable(),
               Problem::getTable(),
            ])) {
               $s_criteria['criteria'][] = [
                  'link'       => 'AND',
                  'field'      => 8, // group tech
                  'searchtype' => 'equals',
                  'value'      => $groups_id
               ];
            }
         }
      }

      if (isset($apply_filters['user_tech'])
          && (int) $apply_filters['user_tech'] > 0) {
         if ($DB->fieldExists($table, 'users_id_tech')) {
            $s_criteria['criteria'][] = [
               'link'       => 'AND',
               'field'      => self::getSearchOptionID($table, 'users_id_tech', 'glpi_users'),// tech
               'searchtype' => 'equals',
               'value'      =>  (int) $apply_filters['user_tech']
            ];
         } else if (in_array($table, [
            Ticket::getTable(),
            Change::getTable(),
            Problem::getTable(),
         ])) {
            $s_criteria['criteria'][] = [
               'link'       => 'AND',
               'field'      => 5,// tech
               'searchtype' => 'equals',
               'value'      =>  (int) $apply_filters['user_tech']
            ];
         }
      }

      return $s_criteria;
   }

   private static function getFiltersCriteria(string $table = "", array $apply_filters = []) {
      $DB = DBConnection::getReadConnection();

      $where = [];
      $join  = [];

      if (($DB->fieldExists($table, 'date'))
          && isset($apply_filters['dates'])
          && count($apply_filters['dates']) == 2) {
         $where += self::getDatesCriteria("$table.date", $apply_filters['dates']);
      }

      //exclude itilobject already processed with 'date'
      if ((!in_array($table, [
         Ticket::getTable(),
         Change::getTable(),
         Problem::getTable(),
      ]) && $DB->fieldExists($table, 'date_creation'))
          && isset($apply_filters['dates'])
          && count($apply_filters['dates']) == 2) {
         $where += self::getDatesCriteria("$table.date_creation", $apply_filters['dates']);
      }

      if ($DB->fieldExists($table, 'date_mod')
          && isset($apply_filters['dates_mod'])
          && count($apply_filters['dates_mod']) == 2) {
         $where += self::getDatesCriteria("$table.date_mod", $apply_filters['dates_mod']);
      }

      if ($DB->fieldExists($table, 'itilcategories_id')
          && isset($apply_filters['itilcategory'])
          && (int) $apply_filters['itilcategory'] > 0) {
         $where += [
            "$table.itilcategories_id" => (int) $apply_filters['itilcategory']
         ];
      }

      if ($DB->fieldExists($table, 'requesttypes_id')
          && isset($apply_filters['requesttype'])
          && (int) $apply_filters['requesttype'] > 0) {
         $where += [
            "$table.requesttypes_id" => (int) $apply_filters['requesttype']
         ];
      }

      if ($DB->fieldExists($table, 'locations_id')
          && isset($apply_filters['location'])
          && (int) $apply_filters['location'] > 0) {
         $where += [
            "$table.locations_id" => (int) $apply_filters['location']
         ];
      }

      if ($DB->fieldExists($table, 'manufacturers_id')
          && isset($apply_filters['manufacturer'])
          && (int) $apply_filters['manufacturer'] > 0) {
         $where += [
            "$table.manufacturers_id" => (int) $apply_filters['manufacturer']
         ];
      }

      if (isset($apply_filters['group_tech'])) {

         $groups_id = null;
         if ((int) $apply_filters['group_tech'] > 0) {
            $groups_id =  (int) $apply_filters['group_tech'];
         } else if ((int) $apply_filters['group_tech'] == -1) {
            $groups_id =  $_SESSION['glpigroups'];
         }

         if ($groups_id != null) {
            if ($DB->fieldExists($table, 'groups_id_tech')) {
               $where += [
                  "$table.groups_id_tech" => $groups_id
               ];
            } else if (in_array($table, [
               Ticket::getTable(),
               Change::getTable(),
               Problem::getTable(),
            ])) {
               $itemtype  = getItemTypeForTable($table);
               $main_item = getItemForItemtype($itemtype);
               $grouplink = $main_item->grouplinkclass;
               $gl_table  = $grouplink::getTable();
               $fk        = $main_item->getForeignKeyField();

               $join += [
                  "$gl_table as gl" => [
                     'ON' => [
                        'gl'   => $fk,
                        $table => 'id',
                     ]
                  ]
               ];
               $where += [
                  "gl.type"      => \CommonITILActor::ASSIGN,
                  "gl.groups_id" => $groups_id
               ];
            }
         }
      }

      if (isset($apply_filters['user_tech'])
          && (int) $apply_filters['user_tech'] > 0) {

         if ($DB->fieldExists($table, 'users_id_tech')) {
            $where += [
               "$table.users_id_tech" => (int) $apply_filters['user_tech']
            ];
         } else if (in_array($table, [
            Ticket::getTable(),
            Change::getTable(),
            Problem::getTable(),
         ])) {
            $itemtype  = getItemTypeForTable($table);
            $main_item = getItemForItemtype($itemtype);
            $userlink  = $main_item->userlinkclass;
            $gl_table  = $userlink::getTable();
            $fk        = $main_item->getForeignKeyField();

            $join += [
               "$gl_table as gl" => [
                  'ON' => [
                     'gl'   => $fk,
                     $table => 'id',
                  ]
               ]
            ];
            $where += [
               "gl.type"     => \CommonITILActor::ASSIGN,
               "gl.users_id" => (int) $apply_filters['user_tech']
            ];
         }
      }

      $criteria = [];
      if (count($where)) {
         $criteria['WHERE'] = $where;
      }
      if (count($join)) {
         $criteria['LEFT JOIN'] = $join;
      }

      return $criteria;
   }

   private static function getDatesCriteria(string $field = "", array $dates = []): array {
      $begin = strtotime($dates[0]);
      $end   = strtotime($dates[1]);

      return [
         [$field => ['>=', date('Y-m-d', $begin)]],
         [$field => ['<=', date('Y-m-d', $end)]],
      ];
   }

   private static function getDatesSearchCriteria(int $searchoption_id, array $dates = [], $when = 'begin'): array {

      if ($when == "begin") {
         $begin = strtotime($dates[0]);
         return [
            'link'       => 'AND',
            'field'      => $searchoption_id, // creation date
            'searchtype' => 'morethan',
            'value'      => date('Y-m-d 00:00:00', $begin)
         ];
      } else {
         $end   = strtotime($dates[1]);
         return [
            'link'       => 'AND',
            'field'      => $searchoption_id, // creation date
            'searchtype' => 'lessthan',
            'value'      => date('Y-m-d 00:00:00', $end)
         ];
      }
   }

}
