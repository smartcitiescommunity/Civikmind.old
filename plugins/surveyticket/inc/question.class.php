<?php

/*
  ------------------------------------------------------------------------
  Surveyticket
  Copyright (C) 2012-2016 by the Surveyticket plugin Development Team.

  ------------------------------------------------------------------------

  LICENSE

  This file is part of Surveyticket plugin project.

  Surveyticket plugin is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Surveyticket plugin is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with Surveyticket plugin. If not, see <http://www.gnu.org/licenses/>.

  ------------------------------------------------------------------------

  @package   Surveyticket plugin
  @author    David Durieux
  @author    Infotel
  @copyright Copyright (c) 2012-2016 Surveyticket plugin team
  @license   AGPL License 3.0 or (at your option) any later version
  http://www.gnu.org/licenses/agpl-3.0-standalone.html
  @link      https://github.com/pluginsGLPI/surveyticket
  @since     2012

  ------------------------------------------------------------------------
 */


if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginSurveyticketQuestion
 */
class PluginSurveyticketQuestion extends CommonDBTM {

   public $dohistory = true;
   static $rightname = "plugin_surveyticket";
   var $can_be_translated  = true;
   CONST YESNO = 'yesno';
   CONST DROPDOWN = 'dropdown';
   CONST CHECKBOX = 'checkbox';
   CONST RADIO = 'radio';
   CONST DATE = 'date';
   CONST INPUT = 'input';
   CONST TEXTAREA = 'textarea';

   /**
    * Get name of this type
    *
    * @param int $nb
    * @return text name of this type by language of the user connected
    *
    */
   static function getTypeName($nb = 0) {
      return _n('Question', 'Questions', $nb, 'surveyticket');
   }

   /**
    *  Define tabs to display
    *
    * @param array $options
    * @return array
    */
   function defineTabs($options = array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginSurveyticketAnswer', $ong, $options);
      $this->addStandardTab('PluginSurveyticketQuestionTranslation', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }

   /**
    * Get the Search options for the given Type
    *
    * @return array
    */
   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Characteristics');

      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name'] = __('Name');
      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = $this->getTable();
      $tab[2]['field'] = 'type';
      $tab[2]['name'] = __('Type');
      $tab[2]['searchtype'] = 'equals';
      $tab[2]['datatype'] = 'specific';
      
      
      $tab[3]['table'] = $this->getTable();
      $tab[3]['field'] = 'comment';
      $tab[3]['name'] = __('Comments');
      $tab[3]['searchtype'] = 'equals';
      $tab[3]['datatype'] = 'text';

      return $tab;
   }

   /**
    * @param String $field
    * @param String $values
    * @param array $options
    * @return
    */
   static function getSpecificValueToDisplay($field, $values, array $options = array()) {
      if (!is_array($values)) {
         $values = array($field => $values);
      }
      switch ($field) {
         case 'type':
            return self::getQuestionTypeName($values[$field]);
            break;
      }
      return parent::getSpecificValueToDisplay($field, $values, $options);
   }

   /**
    * Returns the type of the question
    *
    * @param $value
    * @return mixed
    */
   static function getQuestionTypeName($value) {
      $elements = PluginSurveyticketQuestion::getQuestionTypeList();
      if (isset($elements[$value])) {
         return $elements[$value];
      }
      return $value;
   }

   /**
    * Return true if $type is (date, input, textarea)
    *
    * @param type $type
    * @return boolean
    */
   static function isQuestionTypeText($type){
      switch ($type){
         case self::YESNO : 
            return false;
         case self::DROPDOWN :
            return false;
         case self::CHECKBOX :
            return false;
         case self::RADIO :
            return false;
         case self::DATE :
            return TRUE;
         case self::INPUT :
            return TRUE;
         case self::TEXTAREA :
            return TRUE;
         default : return TRUE;
      }
   }

   /**
    * List of question types
    *
    * @return array
    */
   static function getQuestionTypeList() {
      $array = array();
      $array[self::YESNO] = __('Yes') . '/' . __('No');
      $array[self::DROPDOWN] = __('dropdown', 'surveyticket');
      $array[self::CHECKBOX] = __('checkbox', 'surveyticket');
      $array[self::RADIO] = __('radio', 'surveyticket');
      $array[self::DATE] = __('date');
      $array[self::INPUT] = __('Short text', 'surveyticket');
      $array[self::TEXTAREA] = __('Long text', 'surveyticket');
      return $array;
   }

   /**
    * Print the question form
    *
    * @param $items_id
    * @param array $options
    * @return bool
    */
   function showForm($items_id, $options = array()) {

      if ($items_id != '') {
         $this->getFromDB($items_id);
      } else {
         $this->getEmpty();
      }

      $this->initForm($items_id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . __('Name') . "&nbsp;:</td>";
      echo "<td>";
      //echo '<input type="text" name="name" value="'.$this->fields["name"].'" size="50"/>';
      echo '<textarea maxlength="255" cols="70" rows="3"
         name="name">' . $this->fields["name"] . '</textarea>';
      echo "</td>";
      echo "<td>" . __('Type') . "&nbsp;:</td>";
      echo "<td>";
      $array = PluginSurveyticketQuestion::getQuestionTypeList();
      Dropdown::showFromArray('type', $array, array('value' => $this->fields['type']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>" . __('Comments') . "&nbsp;:</td>";
      echo "<td colspan='3' class='middle'>";
      echo "<textarea cols='100' rows='3' name='comment' >" . $this->fields["comment"] . "</textarea>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }

   /**
    * Delete question

    * @param $id
    */
   function deleteItem($id) {
      $answer = new PluginSurveyticketAnswer();
      $answer->deleteByCriteria(array('plugin_surveyticket_questions_id' => $id));
      
      $surveyquestions = new PluginSurveyticketSurveyQuestion();
      $surveyquestions->deleteByCriteria(array('plugin_surveyticket_questions_id' => $id));
      
   }

   /**
    * Returns the name of the question or the translation
    *
    * @param $questions_id
    * @return bool|result
    */
   static function findQuestion($questions_id) {
      global $DB;

      // Make new database object and fill variables
      $table = 'glpi_plugin_surveyticket_questions';

      $SELECTNAME    = "`$table`.`name`, `namet`.`value` AS transname";
      $JOIN          = " LEFT JOIN `glpi_plugin_surveyticket_questiontranslations` AS namet
                           ON (`namet`.`itemtype` = '" . getItemTypeForTable($table) . "'
                               AND `namet`.`items_id` = `$table`.`id`
                               AND `namet`.`language` = '" . $_SESSION['glpilanguage'] . "'
                               AND `namet`.`field` = 'name')";
      $SELECTCOMMENT = "`$table`.`comment`, `namec`.`value` AS transcomment";
      $JOIN .= " LEFT JOIN `glpi_plugin_surveyticket_questiontranslations` AS namec
                           ON (`namec`.`itemtype` = '" . getItemTypeForTable($table) . "'
                               AND `namec`.`items_id` = `$table`.`id`
                               AND `namec`.`language` = '" . $_SESSION['glpilanguage'] . "'
                               AND `namec`.`field` = 'comment')";

      $query = "SELECT  `$table`.`id`,
                        `$table`.`type`,
                        `$table`.`is_start`,
                        $SELECTNAME, $SELECTCOMMENT
                FROM `$table`
                $JOIN";

         $query .= " WHERE `$table`.`id` = ".$questions_id;

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)) {
            while ($line = $DB->fetch_assoc($result)) {
               if (!empty($line['transname'])) {
                  $line['name'] = $line['transname'];
               }
               if (!empty($line['transcomment'])) {
                  $line['comment'] = $line['transcomment'];
               }
               return $line;
            }
         }
      }
      return false;
      
   }

}