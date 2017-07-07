<?php
/*
 * -------------------------------------------------------------------------
ARSurveys plugin
Monitors via notifications the results of surveys
Provides bad result notification as well as good result notifications

Copyright (C) 2016 by Raynet SAS a company of A.Raymond Network.

http://www.araymond.com
-------------------------------------------------------------------------

LICENSE

This file is part of ARSurveys plugin for GLPI.

This file is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */


// ----------------------------------------------------------------------
// Original Author of file: Olivier Moron
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

// No autoload when plugin is not activated
require_once('../inc/config.class.php');

$config = new PluginArsurveysConfig();
if (isset($_POST["update"])) {
   if(TableExists('glpi_profilerights')) {
      $config->check($_POST['id'], UPDATE);
   } else {
      $config->check($_POST['id'],'w');
   }

    $config->update($_POST);

    Html::back();
} 
Html::redirect($CFG_GLPI["root_doc"]."/front/config.form.php?forcetab=".
             urlencode('PluginArsurveysConfig$1'));
