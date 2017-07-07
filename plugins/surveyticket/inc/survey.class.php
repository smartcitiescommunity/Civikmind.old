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
 * Class PluginSurveyticketSurvey
 */
class PluginSurveyticketSurvey extends CommonDBTM {

    static $rightname = "plugin_surveyticket";

    /**
     * Get name of this type
     *
     * @param int $nb
     * @return text name of this type by language of the user connected
     *
     */
    static function getTypeName($nb = 0) {
        return _n('Survey', 'Surveys', $nb, 'surveyticket');
    }

   /**
    * Define tabs to display
    *
    * @param array $options
    * @return array
    */
   function defineTabs($options = array()) {
       
        $ong = array();
      if ((isset($this->fields['id'])) && ($this->fields['id'] > 0)) {
        $this->addDefaultFormTab($ong);
        $this->addStandardTab('PluginSurveyticketSurveyQuestion', $ong, array());
        $this->addStandardTab('PluginSurveyticketTicketTemplate', $ong, array());
      }
        return $ong;
    }

   /**
    * Print the survey form
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
        echo '<input type="text" name="name" value="' . $this->fields["name"] . '" size="50"/>';
        echo "</td>";
        echo "<td>" . __('Active') . "&nbsp;:</td>";
        echo "<td>";
        Dropdown::showYesNo("is_active", $this->fields['is_active']);
        echo "</td>";
        echo "</tr>";

        echo "<tr class='tab_bg_1'>";
        echo "<td>" . __('Comments') . "&nbsp;:</td>";
        echo "<td colspan='3' class='middle'>";
        echo "<textarea cols='110' rows='3' name='comment' >" . $this->fields["comment"] . "</textarea>";
        echo "</td>";
        echo "</tr>";

        $this->showFormButtons($options);

        return true;
    }

}