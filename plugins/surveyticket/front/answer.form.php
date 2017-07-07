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


include ("../../../inc/includes.php");

Html::header(PluginSurveyticketAnswer::getTypeName(2),'',"helpdesk","pluginsurveyticketmenu","question");

if (!isset($_GET["id"]))
   $_GET["id"] = "";
if (!isset($_GET["withtemplate"]))
   $_GET["withtemplate"] = "";

$psAnswer = new PluginSurveyticketAnswer();
$survey = new PluginSurveyticketSurvey();

if (isset ($_POST["add"])) {
   $survey->check(-1, CREATE, $_POST);
   $psAnswer->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   $survey->checkGlobal(UPDATE);
   $psAnswer->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   $survey->checkGlobal(PURGE);
   $psAnswer->delete($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginSurveyticketQuestion')."?id=".$_POST['plugin_surveyticket_questions_id']);
} else if (isset ($_POST["purge"])) {
   $survey->checkGlobal(PURGE);
   $psAnswer->delete($_POST);
   Html::redirect(Toolbox::getItemTypeFormURL('PluginSurveyticketQuestion')."?id=".$_POST['plugin_surveyticket_questions_id']);
}
   $survey->checkGlobal(READ);
   $psAnswer->display($_GET);

Html::footer();