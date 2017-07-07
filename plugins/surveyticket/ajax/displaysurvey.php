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

Session::checkLoginUser();

header("Content-Type: text/html; charset=UTF-8");
$psSurvey = new PluginSurveyticketTicket();
$psAnswer = new PluginSurveyticketAnswer();
if ($psAnswer->getFromDB($_POST[$_POST['myname']])) {
   if ($psAnswer->fields['link'] > 0) {
      if(!empty($_POST['session'])){
         $session = $_POST['session'];
         if(isset($session[$psAnswer->fields['link']])){
            $session[$psAnswer->fields['link']] = Html::cleanPostForTextArea($session[$psAnswer->fields['link']]);
         }
         echo $psSurvey->displaySurvey($psAnswer->fields['link'], -1, $session, $_POST['answer_id']);
      }else{
         echo $psSurvey->displaySurvey($psAnswer->fields['link'], -1, array(), $_POST['answer_id']);
      }
   }
}