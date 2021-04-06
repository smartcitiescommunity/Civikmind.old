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

use PluginFormcreatorAbstractField;
use Html;
use Toolbox;
use Session;
use PluginFormcreatorQuestionRange;

class CheckboxesField extends PluginFormcreatorAbstractField
{
   public function isPrerequisites(): bool {
      return true;
   }

   public function getDesignSpecializationField(): array {
      $rand = mt_rand();

      $label = '';
      $field = '';

      $additions = '<tr class="plugin_formcreator_question_specific">';
      $additions .= '<td>';
      $additions .= '<label for="default_values' . $rand . '">';
      $additions .= __('Default values');
      $additions .= '<small>(' . __('One per line', 'formcreator') . ')</small>';
      $additions .= '</label>';
      $additions .= '</td>';
      $additions .= '<td>';
      $additions .= Html::textarea([
         'name'             => 'default_values',
         'id'               => 'default_values',
         'value'            => Html::entities_deep($this->getValueForDesign()),
         'cols'             => '50',
         'display'          => false,
      ]);
      $additions .= '</td>';
      $additions .= '<td>';
      $additions .= '<label for="values' . $rand . '">';
      $additions .= __('Values');
      $additions .= '<small>(' . __('One per line', 'formcreator') . ')</small>';
      $additions .= '</label>';
      $additions .= '</td>';
      $additions .= '<td>';
      $value = json_decode($this->question->fields['values']);
      if ($value === null) {
         $value = [];
      }
      $additions .= Html::textarea([
         'name'             => 'values',
         'id'               => 'values',
         'value'            => implode("\r\n", $value),
         'cols'             => '50',
         'display'          => false,
      ]);
      $additions .= '</td>';
      $additions .= '</tr>';

      $common = parent::getDesignSpecializationField();
      $additions .= $common['additions'];

      return [
         'label' => $label,
         'field' => $field,
         'additions' => $additions,
         'may_be_empty' => false,
         'may_be_required' => true,
      ];
   }

   public function getRenderedHtml($canEdit = true): string {
      $html = '';
      if (!$canEdit) {
         if (count($this->value)) {
            $html .= implode('<br />', $this->value);
         }
         return $html;
      }

      $id        = $this->question->getID();
      $rand      = mt_rand();
      $fieldName = 'formcreator_field_' . $id;
      $domId     = $fieldName . '_' . $rand;

      $values = $this->getAvailableValues();
      if (!empty($values)) {
         $html .= '<div class="checkboxes">';
         $i = 0;
         foreach ($values as $value) {
            if ((trim($value) != '')) {
               $i++;
               $html .= "<div class='checkbox'>";
               $html .= Html::getCheckbox([
                  'title'         => htmlentities($value, ENT_QUOTES),
                  'id'            => $domId . '_' . $i,
                  'name'          => htmlentities($fieldName, ENT_QUOTES) . '[]',
                  'value'         => htmlentities($value, ENT_QUOTES),
                  'zero_on_empty' => false,
                  'checked'       => in_array($value, $this->value)
               ]);
               $html .= '<label for="' . $domId . '_' . $i . '">';
               $html .= '&nbsp;' . $value;
               $html .= '</label>';
               $html .= "</div>";
            }
         }
         $html .= '</div>';
      }
      $html .= Html::scriptBlock("$(function() {
         pluginFormcreatorInitializeCheckboxes('$fieldName', '$rand');
      });");

      return $html;
   }

   public function serializeValue(): string {
      if ($this->value === null || $this->value === '') {
         return '';
      }

      return Toolbox::addslashes_deep(json_encode($this->value, JSON_OBJECT_AS_ARRAY + JSON_UNESCAPED_UNICODE));
   }

   public function deserializeValue($value) {
      $this->value = ($value !== null && $value !== '')
         ? json_decode($value)
         : [];
   }

   public function getValueForDesign(): string {
      if ($this->value === null) {
         return '';
      }

      $value = [];
      foreach ($this->value as $item) {
         if (trim($item) !== '') {
            $value[] = $item;
         }
      }
      return implode("\r\n", $value);
   }

   public function parseAnswerValues($input, $nonDestructive = false): bool {
      $key = 'formcreator_field_' . $this->question->getID();
      if (!isset($input[$key])) {
         $input[$key] = [];
      } else {
         if (!is_array($input[$key])) {
            $input[$key] = [$input[$key]];
         }
      }

      $this->value = Toolbox::stripslashes_deep($input[$key]);
      return true;
   }

   public function isValid(): bool {
      $value = $this->value;
      if (is_null($value)) {
         $value = [];
      }

      // If the field is required it can't be empty
      if ($this->isRequired() && count($value) <= 0) {
         Session::addMessageAfterRedirect(
            sprintf(__('A required field is empty: %s', 'formcreator'), $this->getLabel()),
            false,
            ERROR
         );
         return false;
      }

      return $this->isValidValue($value);
   }

   public function isValidValue($value): bool {
      if ($value === '') {
         return true;
      }

      $value = Toolbox::stripslashes_deep($value);
      foreach ($value as $item) {
         if (trim($item) == '') {
            return false;
         }
         if (!in_array($item, $this->getAvailableValues())) {
            return false;
         }
      }

      // Not required and no answer -> valid
      if (!$this->isRequired() && count($value) == 0) {
         return true;
      }

      $parameters = $this->getParameters();

      // Check the field matches the format regex
      if (!$parameters['range']->isNewItem()) {
         $rangeMin = $parameters['range']->fields['range_min'];
         $rangeMax = $parameters['range']->fields['range_max'];
         if ($rangeMin > 0 && count($value) < $rangeMin) {
            $message = sprintf(__('The following question needs at least %d answers', 'formcreator'), $rangeMin);
            Session::addMessageAfterRedirect($message . ' ' . $this->getLabel(), false, ERROR);
            return false;
         }

         if ($rangeMax > 0 && count($value) > $rangeMax) {
            $message = sprintf(__('The following question does not accept more than %d answers', 'formcreator'), $rangeMax);
            Session::addMessageAfterRedirect($message . ' ' . $this->getLabel(), false, ERROR);
            return false;
         }
      }

      return true;
   }

   public static function getName(): string {
      return __('Checkboxes', 'formcreator');
   }

   public function prepareQuestionInputForSave($input) {
      if (!isset($input['values']) || empty($input['values'])) {
         Session::addMessageAfterRedirect(
            __('The field value is required:', 'formcreator') . ' ' . $input['name'],
            false,
            ERROR
         );
         return [];
      }

      // trim values
      $input['values'] = $this->trimValue($input['values']);

      if (isset($input['default_values'])) {
         // trim values
         $input['default_values'] = $this->trimValue($input['default_values']);
      }

      return $input;
   }

   public function hasInput($input): bool {
      return isset($input['formcreator_field_' . $this->question->getID()]);
   }

   public function getValueForTargetText($richText): ?string {
      $value = [];
      $values = $this->getAvailableValues();

      if ($values === null || count($this->value) === 0) {
         return '';
      }

      foreach ($this->value as $input) {
         if (in_array($input, $values)) {
            $value[] = $input;
         }
      }

      if ($richText) {
         $value = '<br />' . implode('<br />', $value);
      } else {
         $value = implode(', ', $value);
      }
      return $value;
   }

   public function moveUploads() {
   }

   public function getDocumentsForTarget(): array {
      return [];
   }

   public static function canRequire(): bool {
      return true;
   }

   public function getEmptyParameters(): array {
      return [
         'range' => new PluginFormcreatorQuestionRange(
            $this,
            [
               'fieldName' => 'range',
               'label'     => __('Range', 'formcreator'),
               'fieldType' => ['text'],
            ]
         ),
      ];
   }

   public function equals($value): bool {
      if (!is_array($this->value)) {
         // No selection
         return ($value === '');
      }
      return in_array($value, $this->value);
   }

   public function notEquals($value): bool {
      return !$this->equals($value);
   }

   public function greaterThan($value): bool {
      if (count($this->value) < 1) {
         return false;
      }
      foreach ($this->value as $answer) {
         if ($answer <= $value) {
            return false;
         }
      }
      return true;
   }

   public function lessThan($value): bool {
      if (count($this->value) < 1) {
         return false;
      }
      foreach ($this->value as $answer) {
         if ($answer >= $value) {
            return false;
         }
      }
      return true;
   }

   public function regex($value): bool {
      return (preg_grep($value, $this->value)) ? true : false;
   }

   public function isAnonymousFormCompatible(): bool {
      return true;
   }

   public function getHtmlIcon(): string {
      return '<i class="fa fa-check-square" aria-hidden="true"></i>';
   }

   public function isVisibleField(): bool {
      return true;
   }

   public function isEditableField(): bool {
      return true;
   }
}
