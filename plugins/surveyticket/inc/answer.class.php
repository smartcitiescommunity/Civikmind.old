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
 * Class PluginSurveyticketAnswer
 */
class PluginSurveyticketAnswer extends CommonDBTM {
   
   public $dohistory = true;
   static $rightname = "plugin_surveyticket";

   /**
    * Get name of this type
    *
    * @param int $nb
    * @return text name of this type by language of the user connected
    *
    */
   static function getTypeName($nb = 0) {
      return _n('Answer', 'Answers', $nb, 'surveyticket');
   }

   /**
    * Get the Search options for the given Type
    *
    * @return array
    */
   function getSearchOptions() {

      $tab = array();
    
      $tab['common'] = __('Characteristics');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = __('Label');
      $tab[1]['datatype']  = 'itemlink';
      
      $tab[2]['table']      = $this->getTable();
      $tab[2]['field']      = 'order';
      $tab[2]['name']       = __('Position');
      $tab[2]['datatype']   = 'number';

      return $tab;
   }

   /**
    * Define tabs to display
    *
    * @param array $options
    * @return array
    */
   function defineTabs($options = array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginSurveyticketAnswerTranslation', $ong, $options);

      return $ong;
   }

   /**
    * @see CommonGLPI::getTabNameForItem()
    * @param CommonGLPI $item
    * @param int $withtemplate
    * @return array|string
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      if ($item->getType() == "PluginSurveyticketQuestion") {
         if ($item->fields['type'] == PluginSurveyticketQuestion::RADIO || $item->fields['type'] == PluginSurveyticketQuestion::YESNO ||
            $item->fields['type'] == PluginSurveyticketQuestion::DROPDOWN || $item->fields['type'] == PluginSurveyticketQuestion::CHECKBOX) {
            $answer  = new self();
            $answers = $answer->find('`plugin_surveyticket_questions_id` = ' . $item->fields['id']);
            return self::createTabEntry(self::getTypeName(Session::getPluralNumber()), count($answers));
         }
      }
      return '';
   }

   /**
    * @param $item            CommonGLPI object
    * @param $tabnum (default 1)
    * @param $withtemplate (default 0)
    *
    * @return bool|true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
       if ($item->getType() == "PluginSurveyticketQuestion") {
         self::listAnswers($item->fields['id'], $withtemplate);
      }
      return true;
   }

   /**
    * Get the answer
    * 
    * @param type $data
    * 
    * @return type
    */
   function getAnswer($data) {
      
      if (!empty($data['name'])) {
         return $data['name'];
      } else {
         $itemtype = $data['itemtype'];
         $item = new $itemtype();
         $item->getFromDB($data['items_id']);
         return $item->getName();
      }      
   }


   /**
    * List of answers
    *
    * @param $questions_id
    * @param $withtemplate
    */
   static function listAnswers($questions_id, $withtemplate) {
      global $CFG_GLPI;
      
      $rand   = mt_rand();
      $psQuestion = new PluginSurveyticketQuestion();
      
      $_SESSION['glpi_plugins_surveyticket']['questions_id'] = $questions_id;

      if (Session::haveRight(static::$rightname, CREATE)) {
         echo "<table class='tab_cadre_fixe'>";

         echo "<tr class='tab_bg_1'>";
         echo "<th colspan='6'>";
         echo _n('Answer', 'Answers', 2, 'surveyticket')." ";
         echo "<a href='".Toolbox::getItemTypeFormURL('PluginSurveyticketAnswer')."?add=1'>
            <img src='".$CFG_GLPI["root_doc"]."/pics/add_dropdown.png'/></a>";
         echo "</th>";
         echo "</tr></table>";
      }
      
      $canedit = Session::haveRight(static::$rightname, UPDATE);
      
      $answer  = new self();
      $answers = $answer->find('`plugin_surveyticket_questions_id` = ' . $questions_id, "`order`");
      if (count($answers) > 0) {
         if ($canedit && $withtemplate != 2) {
            Html::openMassiveActionsForm('mass' . __CLASS__ . $rand);
            $massiveactionparams = array('container' => 'mass' . __CLASS__ . $rand);
            Html::showMassiveActions($massiveactionparams);
         }
         echo "<table class='tab_cadre_fixe'>";
         $header = "<tr class='tab_bg_1'>";
         $header .= "<th width='10'>";
         if ($canedit && $withtemplate != 2) {
            $header .= Html::getCheckAllAsCheckbox('mass' . __CLASS__ . $rand);
         }
         $header .= "</th>";
         $header .= "<th>";
         $header .= __('Id');
         $header .= "</th>";
         $header .= "<th>";
         $header .= __('Label');
         $header .= "</th>";
         $header .= "<th>";
         $header .= __('Position');
         $header .= "</th>";
         $header .= "<th>";
         $header .= __('+ field', 'surveyticket');
         $header .= "</th>";
         $header .= "<th>";
         $header .= __('Go to question', 'surveyticket');
         $header .= "</th>";
         $header .= "</tr>";
         echo $header;

         foreach ($answers as $data) {
            echo "<tr class='tab_bg_1'>";
            echo "<td class='center' width='10'>";
            if ($canedit && $withtemplate != 2) {
               Html::showMassiveActionCheckBox(__CLASS__, $data["id"]);
            }
            $answer->getFromDB($data['id']);
            echo "</td>";
            echo "<td>";
            echo "<a href='" . $answer->getLinkURL() . "'>" . $data['id'] . "</a>";
            echo "</td>";
            echo "<td>";
            echo $data['name'];
            echo "</td>";
            echo "<td>";
            echo $data['order'];
            echo "</td>";
            echo "<td align='center'>";
            $texttype              = array();
            $texttype['']          = "";
            $texttype['shorttext'] = __('Text') . " - court";
            $texttype['longtext']  = __('Text') . " - long";
            $texttype['date']      = __('Date');
            $texttype['number']    = _x('Quantity', 'Number');
            echo $texttype[$data['answertype']];
            echo "</td>";
            echo "<td>";
            if ($data['link'] > 0) {
               $psQuestion->getFromDB($data['link']);
               echo $psQuestion->getLink(1);
            }
            echo "</td>";
            echo "</tr>";
         }
         echo $header;
         echo "</table>";
         if ($canedit && $withtemplate != 2) {
            $massiveactionparams['ontop'] = false;
            Html::showMassiveActions($massiveactionparams);
            Html::closeForm();
         }
      }
   }


   /**
    * Print the answer form
    *
    * @param $ID
    * @param array $options
    * @return bool
    */
   function showForm($ID, $options=array()) {

      if ($ID!='') {
         $this->getFromDB($ID);
      } else {
         $this->getEmpty();
         if (isset($_SESSION['glpi_plugins_surveyticket']['questions_id'])) {
            $this->fields['plugin_surveyticket_questions_id'] = 
                    $_SESSION['glpi_plugins_surveyticket']['questions_id'];            
         }
      }

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>"._n('Answer', 'Answers', 1, 'surveyticket')."&nbsp;:</td>";
      echo "<td colspan='3'>";
      $psQuestion = new PluginSurveyticketQuestion();
      $psQuestion->getFromDB($this->fields['plugin_surveyticket_questions_id']);
      echo $psQuestion->getLink();
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Label')."&nbsp;:</td>";
      echo "<td>";
      switch ($psQuestion->fields['type']){
         case PluginSurveyticketQuestion::DATE : 
      		echo '<i>'.__('date').'</i>';
      		break;
         case PluginSurveyticketQuestion::INPUT :
         	echo '<i>'.__('Short text', 'surveyticket').'</i>';
         	break;
         case PluginSurveyticketQuestion::TEXTAREA :
         	echo '<i>'.__('Long text', 'surveyticket').'</i>';
         	break;
        default :
         	echo '<textarea maxlength="255" cols="70" rows="3" name="name">'.$this->fields["name"].'</textarea>'; 
         	break;
      } 
      
      echo "</td>";
      echo "<td>";
      $psQuestion = new PluginSurveyticketQuestion();
      if($psQuestion->getFromDB($_SESSION['glpi_plugins_surveyticket']['questions_id'])){
         if ($psQuestion->fields['type'] != PluginSurveyticketQuestion::DATE
                 && $psQuestion->fields['type'] != PluginSurveyticketQuestion::INPUT && $psQuestion->fields['type'] != PluginSurveyticketQuestion::TEXTAREA) {
            echo __('+ field', 'surveyticket')."&nbsp;:";
         }
      }
      echo "</td>";
      echo "<td>";
      $texttype = array();
      $texttype[''] = Dropdown::EMPTY_VALUE;
      $texttype['shorttext'] = __('Text')." - court";
      $texttype['longtext'] = __('Text')." - long";
      $texttype['date'] = __('Date');
      $texttype['number'] = _x('Quantity', 'Number');
      
      if (!PluginSurveyticketQuestion::isQuestionTypeText($psQuestion->fields['type'])) {
         Dropdown::showFromArray("answertype", $texttype, array('value' => $this->fields['answertype']));   
      }
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Position')."&nbsp;:</td>";
      echo "<td colspan='3'>";
      Dropdown::showNumber("order", array(
          'name'=>'order',
          'value'=>$this->fields['order']
         ));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Linked to question', 'surveyticket')."&nbsp;:</td>";
      echo "<td colspan='3'>";
      Dropdown::show("PluginSurveyticketQuestion", array(
          'name'=>'plugin_surveyticket_questions_id',
          'value'=>$this->fields['plugin_surveyticket_questions_id']
         ));
      echo "</td>";
      echo "</tr>";
      
      if ($psQuestion->fields['type'] != PluginSurveyticketQuestion::CHECKBOX) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Go to question', 'surveyticket')."&nbsp;:</td>";
         echo "<td>";
         Dropdown::show("PluginSurveyticketQuestion", array(
             'name'=>'link',
             'value'=>$this->fields['link'],
             'used' => array($psQuestion->getID())
            ));
         echo "</td>";
         echo "<td>" . __('Mandatory', 'surveyticket') . "&nbsp;:</td>";
         echo "</td>";
         echo "<td>";
         Dropdown::showYesNo('mandatory', $this->fields['mandatory']);
         echo "</td>";
         echo "</tr>";
      }
      
      $this->showFormButtons($options);

      return true;
   }


   /**
    * @param $questions_id
    */
   function addYesNo($questions_id) {
      global $DB;
      
      $query= "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_surveyticket_questions_id` = '".$questions_id."'
            AND `is_yes`='0' AND `is_no`='0'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $this->delete($data);
      }
      
      $query= "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_surveyticket_questions_id` = '".$questions_id."'
            AND `is_yes`='1'";
      $result = $DB->query($query);
      if ($DB->numrows($result) == 0) {
         $input = array();
         $input['plugin_surveyticket_questions_id'] = $questions_id;
         $input['is_yes'] = 1;
         $input['name'] = __('Yes');
         $input['mandatory'] = 0;
         $this->add($input);
      }
      $query= "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_surveyticket_questions_id` = '".$questions_id."'
            AND `is_no`='1'";
      $result = $DB->query($query);
      if ($DB->numrows($result) == 0) {
         $input = array();
         $input['plugin_surveyticket_questions_id'] = $questions_id;
         $input['is_no'] = 1;
         $input['name'] = __('No');
         $input['mandatory'] = 0;
         $this->add($input);
      }
   }


   /**
    * @param $questions_id
    */
   function removeYesNo($questions_id) {
      global $DB;
      
      $query= "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_surveyticket_questions_id` = '".$questions_id."'
            AND `is_yes`='1'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $this->delete($data);
      }
      $query= "SELECT * FROM `".$this->getTable()."`
         WHERE `plugin_surveyticket_questions_id` = '".$questions_id."'
            AND `is_no`='1'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $this->delete($data);
      }
   }

   /**
    * Prepare input datas for updating the item
    *
    * @param datas $input
    * @return array|datas
    */
   function prepareInputForUpdate($input) {
      //case action massive
      if(isset($input['link'])){
         if (($input['link']) > 0) {
            $survey = new PluginSurveyticketSurvey();
            $allsurvey = $survey->find();
            foreach($allsurvey as $data_survey){
               $surveyquestion  = new PluginSurveyticketSurveyQuestion();
               $questionsSurvey = $surveyquestion->find('`plugin_surveyticket_surveys_id` = ' . $data_survey['id']);
               $tab = array();
               foreach ($questionsSurvey as $question){
                  $tab[] = $question['plugin_surveyticket_questions_id'];
                  if($question['plugin_surveyticket_questions_id'] != $input['plugin_surveyticket_questions_id']){
                     $tab = PluginSurveyticketSurveyQuestion::questionUsed($question['plugin_surveyticket_questions_id'], $tab);
                  }
               }
               $survey->getFromDB($data_survey['id']);
               if(in_array($input['plugin_surveyticket_questions_id'], $tab) && in_array($input['link'], $tab)){
                   Session::addMessageAfterRedirect(__('The question is present in the survey', 'surveyticket') . " : " . $survey->fields['name'] . " " . __('Please delete the questionnaire if you want to add it.', 'surveyticket'), false, ERROR);
                     return array();
               }
            }
         } else {
            $input['mandatory'] = 0;
         }
      }
      return $input;
   }

   /**
    * @param string $condition
    * @param string $order
    * @return array
    */
   static function findAnswers($condition = "", $order = "") {
      global $DB;

      // Make new database object and fill variables
      $table = 'glpi_plugin_surveyticket_answers';

      $SELECTNAME = "`$table`.`name`, `namet`.`value` AS transname";
      $JOIN = " LEFT JOIN `glpi_plugin_surveyticket_answertranslations` AS namet
                           ON (`namet`.`itemtype` = '" . getItemTypeForTable($table) . "'
                               AND `namet`.`items_id` = `$table`.`id`
                               AND `namet`.`language` = '" . $_SESSION['glpilanguage'] . "'
                               AND `namet`.`field` = 'name')";

      $query = "SELECT  `$table`.`id`,
                        `$table`.`answertype`,
                        `$table`.`is_yes`,
                        `$table`.`is_no`,
                        `$table`.`link`,
                        `$table`.`mandatory`,
                        `$table`.`order`,
                        $SELECTNAME
                FROM `$table`
                $JOIN";

      if (!empty($condition)) {
         $query .= " WHERE $condition";
      }

      if (!empty($order)) {
         $query .= " ORDER BY $order";
      }
      $data   = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)) {
            while ($line = $DB->fetch_assoc($result)) {
               if (!empty($line['transname'])) {
                  $line['name'] = $line['transname'];
               }
               $data[$line['id']] = $line;
            }
         }
      }
      return $data;
   }

   /**
    * Find the answers in the questionnaire
    *
    * @param $id
    * @return bool|result
    */
   static function findAnswer($id) {
      global $DB;

      // Make new database object and fill variables
      $table = 'glpi_plugin_surveyticket_answers';

      $SELECTNAME = "`$table`.`name`, `namet`.`value` AS transname";
      $JOIN = " LEFT JOIN `glpi_plugin_surveyticket_answertranslations` AS namet
                           ON (`namet`.`itemtype` = '" . getItemTypeForTable($table) . "'
                               AND `namet`.`items_id` = `$table`.`id`
                               AND `namet`.`language` = '" . $_SESSION['glpilanguage'] . "'
                               AND `namet`.`field` = 'name')";

      $query = "SELECT  `$table`.`id`,
                        `$table`.`answertype`,
                        `$table`.`is_yes`,
                        `$table`.`is_no`,
                        `$table`.`link`,
                        `$table`.`mandatory`,
                        `$table`.`order`,
                        $SELECTNAME
                FROM `$table`
                $JOIN";

         $query .= " WHERE `$table`.`id` = ".Toolbox::cleanInteger($id);

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result)) {
            while ($line = $DB->fetch_assoc($result)) {
               if (!empty($line['transname'])) {
                  $line['name'] = $line['transname'];
               }
               return $line;
            }
         }
      }
      return false;
   }
   
}