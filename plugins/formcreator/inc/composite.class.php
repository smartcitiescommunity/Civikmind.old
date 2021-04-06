<?php
/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @copyright Copyright © 2011 - 2021 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginFormcreatorComposite
{
   private $item_targetTicket;

   private $targets = [];

   private $ticket_ticket;

   public function __construct(PluginFormcreatorItem_TargetTicket $item_targetTicket, Ticket_Ticket $ticket_ticket) {
      $this->item_targetTicket = $item_targetTicket;
      $this->ticket_ticket = $ticket_ticket;
   }

   /**
    * Add a target and generated target
    *
    * @param PluginFormcreatorAbstractTarget $target
    * @param CommonDBTM $generatedTarget
    */
   public function addTarget(PluginFormcreatorAbstractTarget $target, CommonDBTM $generatedTarget) {
      $itemtype = get_class($target);
      $this->targets[$itemtype][$target->getID()] = $generatedTarget;
   }

   /**
    * Undocumented function
    *
    * @return void
    */
   public function buildCompositeRelations() {
      global $DB;

      if (isset($this->targets['PluginFormcreatorTargetTicket'])) {
         foreach ($this->targets['PluginFormcreatorTargetTicket'] as $targetId => $generatedObject) {
            $rows = $DB->request([
               'SELECT' => [
                  'itemtype',
                  'items_id',
                  'link'
               ],
               'FROM'   => $this->item_targetTicket->getTable(),
               'WHERE'  => [
                  'plugin_formcreator_targettickets_id' => $targetId
               ]
            ]);
            foreach ($rows as $row) {
               switch ($row['itemtype']) {
                  case 'Ticket':
                     $this->ticket_ticket->add([
                        'link' => $row['link'],
                        'tickets_id_1' => $generatedObject->getID(),
                        'tickets_id_2' => $row['items_id'],
                     ]);
                     break;

                  case 'PluginFormcreatorTargetTicket':
                     $ticket = $this->targets['PluginFormcreatorTargetTicket'][$row['items_id']];
                     $this->ticket_ticket->add([
                        'link' => $row['link'],
                        'tickets_id_1' => $generatedObject->getID(),
                        'tickets_id_2' => $ticket->getID(),
                     ]);
                     break;
               }
            }
         }
      }
   }
}
