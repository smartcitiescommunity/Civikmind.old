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
 * @copyright Copyright © 2011 - 2020 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

namespace GlpiPlugin\Formcreator\Field;

use Html;
use Session;
use Dropdown;
use User;
use Group;
use Ticket;
use Computer;
use Monitor;
use Appliance;
use Software;
use NetworkEquipment;
use Peripheral;
use Printer;
use CartridgeItem;
use ConsumableItem;
use Phone;
use Line;
use Problem;
use TicketRecurrent;
use Budget;
use Supplier;
use Contact;
use Contract;
use Document;
use Project;
use Entity;
use Profile;
use PassiveDCEquipment;
use PluginAppliancesAppliance;
use Plugin;

use GlpiPlugin\Formcreator\Exception\ComparisonException;

class GlpiselectField extends DropdownField
{
   public function getDesignSpecializationField(): array {
      $rand = mt_rand();

      $label = '<label for="dropdown_glpi_objects' . $rand . '" id="label_dropdown_values">';
      $label .= _n('GLPI object', 'GLPI objects', 1, 'formcreator');
      $label .= '</label>';

      $optgroup = [
         __("Assets") => [
            Computer::class         => Computer::getTypeName(2),
            Monitor::class          => Monitor::getTypeName(2),
            Software::class         => Software::getTypeName(2),
            NetworkEquipment::class => Networkequipment::getTypeName(2),
            Peripheral::class       => Peripheral::getTypeName(2),
            Printer::class          => Printer::getTypeName(2),
            CartridgeItem::class    => CartridgeItem::getTypeName(2),
            ConsumableItem::class   => ConsumableItem::getTypeName(2),
            Phone::class            => Phone::getTypeName(2),
            Line::class             => Line::getTypeName(2),
            PassiveDCEquipment::class => PassiveDCEquipment::getTypeName(2),
            Appliance::class          => Appliance::getTypeName(2),
         ],
         __("Assistance") => [
            Ticket::class           => Ticket::getTypeName(2),
            Problem::class          => Problem::getTypeName(2),
            TicketRecurrent::class  => TicketRecurrent::getTypeName(2)
         ],
         __("Management") => [
            Budget::class           => Budget::getTypeName(2),
            Supplier::class         => Supplier::getTypeName(2),
            Contact::class          => Contact::getTypeName(2),
            Contract::class         => Contract::getTypeName(2),
            Document::class         => Document::getTypeName(2),
            Project::class          => Project::getTypeName(2)
         ],
         __("Tools") => [
            Reminder::class         => __("Notes"),
            RSSFeed::class          => __("RSS feed")
         ],
         __("Administration") => [
            User::class             => User::getTypeName(2),
            Group::class            => Group::getTypeName(2),
            Entity::class           => Entity::getTypeName(2),
            Profile::class          => Profile::getTypeName(2)
         ],
      ];
      if ((new Plugin())->isActivated('appliances')) {
         $optgroup[__("Assets")][PluginAppliancesAppliance::class] = PluginAppliancesAppliance::getTypeName(2) . ' (' . _n('Plugin', 'Plugins', 1) . ')';
      }
      array_unshift($optgroup, '---');
      $field = Dropdown::showFromArray('glpi_objects', $optgroup, [
         'value'     => $this->question->fields['values'],
         'rand'      => $rand,
         'on_change' => 'plugin_formcreator_changeGlpiObjectItemType();',
         'display'   => false,
      ]);

      $additions = '<tr class="plugin_formcreator_question_specific">';
      $additions .= '<td>';
      $additions .= '<label for="dropdown_default_values' . $rand . '">';
      $additions .= __('Default values');
      $additions .= '</label>';
      $additions .= '</td>';
      $additions .= '<td id="dropdown_default_value_field">';
      $additions .= '</td>';
      $additions .= '<td></td>';
      $additions .= '<td></td>';
      $additions .= '</tr>';
      $additions .= Html::scriptBlock("plugin_formcreator_changeGlpiObjectItemType($rand);");
      return [
         'label' => $label,
         'field' => $field,
         'additions' => $additions,
         'may_be_empty' => true,
         'may_be_required' => true,
      ];
   }

   public static function getName(): string {
      return _n('GLPI object', 'GLPI objects', 1, 'formcreator');
   }

   public function isValidValue($value): bool {
      $itemtype = $this->getSubItemtype();
      if ($itemtype == Entity::getType() && $value == '-1') {
         return true;
      }

      return parent::isValidValue($value);
   }

   public function prepareQuestionInputForSave($input) {
      if (!isset($input['glpi_objects']) || empty($input['glpi_objects'])) {
         Session::addMessageAfterRedirect(
            __('The field value is required:', 'formcreator') . ' ' . $input['name'],
            false,
            ERROR
         );
         return [];
      }

      $input['values']         = $input['glpi_objects'];
      $input['default_values'] = isset($input['dropdown_default_value']) ? $input['dropdown_default_value'] : '';
      unset($input['dropdown_default_value']);

      return $input;
   }

   public static function canRequire(): bool {
      return true;
   }

   public function equals($value): bool {
      $value = html_entity_decode($value);
      $itemtype = $this->question->fields['values'];
      $item = new $itemtype();
      if ($item->isNewId($this->value)) {
         return ($value === '');
      }
      if (!$item->getFromDB($this->value)) {
         throw new ComparisonException('Item not found for comparison');
      }
      return $item->getField($item->getNameField()) == $value;
   }

   public function notEquals($value): bool {
      return !$this->equals($value);
   }

   public function greaterThan($value): bool {
      $value = html_entity_decode($value);
      $itemtype = $this->question->fields['values'];
      $item = new $itemtype();
      if (!$item->getFromDB($this->value)) {
         throw new ComparisonException('Item not found for comparison');
      }
      return $item->getField($item->getNameField()) > $value;
   }

   public function lessThan($value): bool {
      return !$this->greaterThan($value) && !$this->equals($value);
   }

   public function regex($value): bool {
      return (preg_grep($value, $this->value)) ? true : false;
   }

   public function isAnonymousFormCompatible(): bool {
      return false;
   }
}
