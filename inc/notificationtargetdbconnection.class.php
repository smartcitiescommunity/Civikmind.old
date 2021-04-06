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

// Class NotificationTarget
class NotificationTargetDBConnection extends NotificationTarget {


   /**
    * Overwrite the function in NotificationTarget because there's only one target to be notified
    *
    * @see NotificationTarget::addNotificationTargets()
   **/
   function addNotificationTargets($entity) {

      $this->addProfilesToTargets();
      $this->addGroupsToTargets($entity);
      $this->addTarget(Notification::GLOBAL_ADMINISTRATOR, __('Administrator'));
   }


   function getEvents() {
      return ['desynchronization' => __('Desynchronization SQL replica')];
   }


   function addDataForTemplate($event, $options = []) {

      if ($options['diff'] > 1000000000) {
         $tmp = __("Can't connect to the database.");
      } else {
         $tmp = Html::timestampToString($options['diff'], true);
      }
      $this->data['##dbconnection.delay##'] = $tmp." (".$options['name'].")";

      $this->getTags();
      foreach ($this->tag_descriptions[NotificationTarget::TAG_LANGUAGE] as $tag => $values) {
         if (!isset($this->data[$tag])) {
            $this->data[$tag] = $values['label'];
         }
      }

   }


   function getTags() {

      $tags = ['dbconnection.delay' => __('Difference between master and slave')];

      foreach ($tags as $tag => $label) {
         $this->addTagToList(['tag'   => $tag,
                                   'label' => $label,
                                   'value' => true,
                                   'lang'  => true]);
      }

      //Tags with just lang
      $tags = ['dbconnection.title'
                                 => __('Slave database out of sync!'),
                    'dbconnection.delay'
                                 => __('The slave database is desynchronized. The difference is of:')];

      foreach ($tags as $tag => $label) {
         $this->addTagToList(['tag'   => $tag,
                                   'label' => $label,
                                   'value' => false,
                                   'lang'  => true]);
      }

      asort($this->tag_descriptions);
   }

}
