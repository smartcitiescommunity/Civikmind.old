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
 * Class PluginSurveyticketSurveyQuestion
 */
class PluginSurveyticketSurveyQuestion extends CommonDBTM {
   
   static $rightname = 'plugin_surveyticket';

   /**
    * Get name of this type
    *
    * @param int $nb
    * @return translated
    */
   static function getTypeName($nb = 0) {
      return _n('Question', 'Questions', $nb, 'surveyticket');
   }

   /**
    * Get Tab Name used for itemtype
    *
    * @param CommonGLPI $item
    * @param int $withtemplate
    * @return string|translated
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if ($item->getType() == 'PluginSurveyticketSurvey') {
         return _n('Question', 'Questions', 2, 'surveyticket');
      }
      return '';
   }

   /**
    * Display content of tab
    *
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param integer $withtemplate
    * @return bool TRUE
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      if ($item->getType() == 'PluginSurveyticketSurvey') {
         $pfSurveyQuestion = new self();
         $pfSurveyQuestion->showQuestions($item->getID(), $withtemplate);
      }
      return TRUE;
   }

   /**
    * Return questions of the questionnaire
    *
    * @param $plugin_surveyticket_questions_id
    * @param array $a_used
    * @return array
    */
   static function questionUsed($plugin_surveyticket_questions_id, $a_used = array()) {
      $psAnswer = new PluginSurveyticketAnswer();
      $result = $psAnswer->find("`plugin_surveyticket_questions_id` = " . $plugin_surveyticket_questions_id);
      foreach ($result as $data) {
         if ($data['link'] > 0) {
            $a_used[] = $data['link'];
            $a_used = self::questionUsed($data['link'], $a_used);
         }
      }
      return $a_used;
   }

   /**
    * Print the form questions
    *
    * @param $items_id
    * @param $withtemplate
    */
   function showQuestions($items_id, $withtemplate) {
      global $CFG_GLPI;
      
      $a_questions = $this->find("`plugin_surveyticket_surveys_id`='" . $items_id . "'", "`order`");
      $a_used      = array();
      foreach ($a_questions as $data) {
         $a_used[] = $data['plugin_surveyticket_questions_id'];
         //recovery of links to other issues
         $a_used   = self::questionUsed($data['plugin_surveyticket_questions_id'], $a_used);
      }

      if (Session::haveRight("plugin_surveyticket", CREATE)) {
         echo "<form method='post' name='form_addquestion' action='" . $CFG_GLPI['root_doc'] .
         "/plugins/surveyticket/front/surveyquestion.form.php'>";

         echo "<table class='tab_cadre' width='700'>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>" . _n('Question', 'Questions', 1, 'surveyticket') . "&nbsp;:</td>";
         echo "<td>";

         Dropdown::show("PluginSurveyticketQuestion", array("name" => "plugin_surveyticket_questions_id",
                                                            "used" => $a_used)
         );
         echo "</td>";
         echo "<td>" . __('Position') . "&nbsp;:</td>";
         echo "<td>";
         Dropdown::showInteger("order", "0", 0, 20);
         echo "</td>";

         echo "<td>" . __('Mandatory', 'surveyticket') . "&nbsp;:</td>";
         echo "<td>";
         Dropdown::showYesNo('mandatory');
         echo "</td>";
         echo "</tr>";


         echo "<tr>";
         echo "<td class='tab_bg_2 top' colspan='6'>";
         echo "<input type='hidden' name='plugin_surveyticket_surveys_id' value='" . $items_id . "'>";
         echo "<div class='center'>";
         echo "<input type='submit' name='add' value=\"" . __('Add') . "\" class='submit'>";
         echo "</div></td></tr>";

         echo "</table>";
         Html::closeForm();
      
      
         echo "<form method='post' name='add' action='" . $CFG_GLPI['root_doc'] .
         "/plugins/surveyticket/front/question.form.php'>";
         echo "<input type='submit'  value=\"" . __('Create a question', 'surveyticket') . "\" class='submit'>";
         Html::closeForm();
      }


      // list questions
      self::showListQuestions($a_questions, $withtemplate);
   }

   /**
    * Print the list
    *
    * @param $a_questions
    * @param $withtemplate
    */
   static function showListQuestions($a_questions, $withtemplate) {
      $rand = mt_rand();
      $canedit = Session::haveRight(static::$rightname, UPDATE);
      
      echo "<div class='spaced'>";
      if ($canedit && $withtemplate != 2) {
         Html::openMassiveActionsForm('mass' . __CLASS__ . $rand);
         $massiveactionparams = array();
         Html::showMassiveActions($massiveactionparams);
      }
      echo "<table class='tab_cadre_fixe'>";
      if ($canedit && $withtemplate != 2) {
         $header_top    = "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         $header_top    .= "</th>";
      }else{
         $header_top    = "<th width='10'></th>";
      }
      
      $header_begin = "<tr class='tab_bg_1'>";
      $header_end = "<th>";
      $header_end .= _n('Question', 'Questions', 1, 'surveyticket');
      $header_end .= "</th>";
      $header_end .= "<th>";
      $header_end .= __('Type');
      $header_end .= "</th>";
      $header_end .= "<th>";
      $header_end .= __('Position') . " / " . __('Link');
      $header_end .= "</th>";
      $header_end .= "<th>";
      $header_end .= __('Mandatory', 'surveyticket');
      $header_end .= "</th>";
      $header_end .= "<th>";
      $header_end .= "</th>";
      $header_end .= "</tr>";
      
      echo $header_begin.$header_top.$header_end;
      foreach ($a_questions as $data) {
         self::showQuestion($data);
      }
      if($canedit && $withtemplate != 2){
         echo "<tr class='tab_bg_1'>";
         echo "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
         echo "</th><th colspan='10'></th>";
         echo "</tr>";
      }
      echo "</table>";
      if ($canedit && $withtemplate != 2) {
         $massiveactionparams['ontop'] = false;
         Html::showMassiveActions($massiveactionparams);
         Html::closeForm();
      }
      echo "</div>";
   }

   /**
    * Print the question form
    *
    * @param $data
    */
   static function showQuestion($data) {
      global $CFG_GLPI;
      $psQuestion = new PluginSurveyticketQuestion();
      $psQuestion->getFromDB($data['plugin_surveyticket_questions_id']);
      echo "<tr class='tab_bg_1'>";
      if (isset($data['id'])) {
         echo "<td width='10'>";
         Html::showMassiveActionCheckBox(__CLASS__, $data['id']);
         echo "</td>";
      } else {
         echo "<td width='10'></td>";
      }
      echo "<td>";

      echo $psQuestion->getLink(1);
      echo "</td>";
      echo "<td>";
      echo PluginSurveyticketQuestion::getQuestionTypeName($psQuestion->fields['type']);
      echo "</td>";
      if (isset($data['id'])) {
         echo "<td>";
         echo $data['order'];
         echo "</td>";
         echo "<td>";
         if ($data['mandatory']) {
            echo __('Yes');
         } else {
            //no
            echo __('No');
         }
         echo "</td>";
         echo "<td align='center'>";
         echo "<form method='post' name='form_addquestion' action='" . $CFG_GLPI['root_doc'] .
         "/plugins/surveyticket/front/surveyquestion.form.php'>";
         echo "<input type='hidden' name='id' value='" . $data['id'] . "'>";
         echo "<input type='submit' name='delete' value=\"" . _sx('button', 'Delete permanently') . "\" class='submit'>";
         Html::closeForm();
         echo "</td>";
      } else {
         echo "<td>";
         echo '0';
         echo "</td>";
         echo "<td>";
         if ($data['mandatory']) {
            echo __('Yes');
         } else {
            //no
            echo __('No');
         }
         echo "</td><td align='center'></td>";
      }

      echo "</tr>";
      self::showLinkQuestion($data['plugin_surveyticket_questions_id']);
   }

   /**
    * @param $plugin_surveyticket_questions_id
    */
   static function showLinkQuestion($plugin_surveyticket_questions_id) {
      $psAnswer = new PluginSurveyticketAnswer();
      $result = $psAnswer->find("`plugin_surveyticket_questions_id` = " . $plugin_surveyticket_questions_id);
      $psQuestionName = new PluginSurveyticketQuestion();
      $psQuestion = new PluginSurveyticketQuestion();
      foreach ($result as $data) {
         if ($data['link'] > 0) {
            $psQuestion->getFromDB($data['link']);
            $psQuestionName->getFromDB($data['plugin_surveyticket_questions_id']);
            echo "<tr class='tab_bg_2'><td></td>";
            echo "<td>".__('Answer', 'surveyticket')." : ".$psQuestionName->fields['name']."</td><td>";
            echo $data['name'];
            echo "</td><td>".$psQuestion->getLink(1)."</td><td colspan='2'></td>";
            echo "</tr>";
            self::showQuestion(array('plugin_surveyticket_questions_id' => $data['link'], 'old_plugin_surveyticket_questions_id' => $plugin_surveyticket_questions_id, 'mandatory' => $data['mandatory']));
         }
      }
   }

   /**
    * Actions done before update
    * 
    * @param type $input
    * @return type
    */
   function prepareInputForAdd($input) {
      $msg = array();
      $psAnswer = new PluginSurveyticketAnswer();
      $result = $psAnswer->find("`plugin_surveyticket_questions_id` = " . $input['plugin_surveyticket_questions_id']);
      $bool = false;
      $survey = new PluginSurveyticketSurveyQuestion();
      $a_questions = $survey->find("`plugin_surveyticket_surveys_id`='" . $input['plugin_surveyticket_surveys_id'] . "'", "`order`");
      $psQuestion = new PluginSurveyticketQuestion();
      $a_used = array();
       foreach ($a_questions as $data) {
         $a_used[$data['plugin_surveyticket_questions_id']] = $data['plugin_surveyticket_questions_id'];
       }

      foreach ($result as $data) {
         if ($data['link'] > 0) {
            if (array_key_exists($data['link'], $a_used)) {
               $bool = true;
               $psQuestion->getFromDB($data['link']);
               $msg[] = $psQuestion->fields['name'];
            }
         }
      }
      if ($bool) {
         Session::addMessageAfterRedirect(sprintf(__("The question can not be added because it has links to other questions : %s. Please delete the questionnaire if you want to add it.", 'surveyticket'), implode(', ', $msg)), false, ERROR);
      } else {
         return $input;
      }
   }

   /**
    * Delete survey
    *
    * @param $id
    */
   static function deleteSurveyQuestion($id){
      $temp = new self();
      $temp->deleteByCriteria(array('plugin_surveyticket_surveys_id' => $id));
   }
   
   /**
    * 
    * @return string
    */
   function getSearchOptions() {

      $tab = array();

      $tab['common'] = self::getTypeName(2);

      $tab[30]['table']    = $this->getTable();
      $tab[30]['field']    = 'id';
      $tab[30]['name']     = __('ID');
      $tab[30]['datatype'] = 'number';

      $tab[3]['table']    = $this->getTable();
      $tab[3]['field']    = 'mandatory';
      $tab[3]['name']     = __('Mandatory', 'surveyticket');
      $tab[3]['datatype'] = 'bool';

      $tab[2]['table']      = $this->getTable();
      $tab[2]['field']      = 'order';
      $tab[2]['name']       = __('Position');
      $tab[2]['datatype']   = 'number';

      return $tab;
   }
}