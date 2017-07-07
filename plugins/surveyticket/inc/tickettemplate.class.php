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
 * Class PluginSurveyticketTicketTemplate
 */
class PluginSurveyticketTicketTemplate extends CommonDBTM {

   static $rightname = "plugin_surveyticket";
   
   /**
    * Get name of this type
    *
    * @param int $nb
    * @return translated
    */
   static function getTypeName($nb = 0) {
      return __('Template');
   }

   /**
    * Get Tab Name used for itemtype
    *
    * @param CommonGLPI $item
    * @param int $withtemplate
    * @return string|translated
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->getType()=='PluginSurveyticketSurvey') {
         return _n('Ticket template', 'Ticket templates', 1);
      }
      return '';
   }


   /**
    * Display content of tab
    *
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param int|integer $withtemplate
    * @return bool TRUE
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      if ($item->getType() == 'PluginSurveyticketSurvey') {
         $psTicketTemplate = new self();
         $psTicketTemplate->showTicketTemplate($item->getID());
      }
      return TRUE;
   }


   /**
    * Display form
    * @param $items_id
    */
   function showTicketTemplate($items_id) {
      global $CFG_GLPI;
      
      $ticketTemplate = new TicketTemplate();
      
      if (Session::haveRight(static::$rightname, CREATE)) {
         echo "<form method='post' name='form_addquestion' action='".$CFG_GLPI['root_doc'].
                "/plugins/surveyticket/front/tickettemplate.form.php'>";

         echo "<table class='tab_cadre' width='700'>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Ticket template')."&nbsp;:</td>";
         echo "<td>";
         $a_used = array();


         Dropdown::show("TicketTemplate", 
                        array("name" => "tickettemplates_id",
                              "used" => $a_used)
                       );
         echo "</td>";
         echo "<td>".__('Type')."&nbsp;:</td>";
         echo "<td>";
         Ticket::dropdownType("type");
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Simplified interface')."&nbsp;:</td>";
         echo "<td>";
         Dropdown::showYesNo("is_helpdesk");
         echo "</td>";

         echo "<td>".__('Standard interface')."&nbsp;:</td>";
         echo "<td>";
         Dropdown::showYesNo("is_central");
         echo "</td>";
         echo "</tr>";

         echo "<tr>";
         echo "<td class='tab_bg_2 top' colspan='4'>";
         echo "<input type='hidden' name='plugin_surveyticket_surveys_id' value='".$items_id."'>";
         echo "<div class='center'>";
         echo "<input type='submit' name='add' value=\"".__('Add')."\" class='submit'>";
         echo "</div></td></tr>";

         echo "</table>";
         Html::closeForm();
      }
      
      // list templates
      echo "<table class='tab_cadre_fixe'>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo __('Ticket template');
      echo "</th>";
      echo "<th>";
      echo __('Type');
      echo "</th>";
      echo "<th>";
      echo __('Simplified interface');
      echo "</th>";
      echo "<th>";
      echo __('Standard interface');
      echo "</th>";
      echo "<th>";
      echo "</th>";
      echo "</tr>";    
      
      $_tickettempaltes = $this->find("`plugin_surveyticket_surveys_id`='".$items_id."'");
      foreach ($_tickettempaltes as $data) {
         echo "<tr class='tab_bg_1'>";
         echo "<td class='center'>";
         $ticketTemplate->getFromDB($data['tickettemplates_id']);
         echo $ticketTemplate->getLink(1);
         echo "</td>";
         echo "<td class='center'>";
         echo Ticket::getTicketTypeName($data['type']);
         echo "</td>";
         echo "<td class='center'>";
         echo Dropdown::getYesNo($data['is_helpdesk']);
         echo "</td>";   
         echo "<td class='center'>";
         echo Dropdown::getYesNo($data['is_central']);
         echo "</td>";
         
         echo "<td align='center'>";
         if (Session::haveRight(static::$rightname, PURGE)) {
            echo "<form method='post' name='form_delettickettemplate' action='".$CFG_GLPI['root_doc'].
                "/plugins/surveyticket/front/tickettemplate.form.php'>";
            echo "<input type='hidden' name='id' value='".$data['id']."'>";
            echo "<input type='submit' name='delete' value=\""._sx('button', 'Delete permanently')."\" class='submit'>";
            Html::closeForm();
         }
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
   }
}