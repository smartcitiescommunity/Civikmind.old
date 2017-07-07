<?php

/*
------------------------------------------------------------------------
GLPI Plugin renamer
Copyright (C) 2014 by the GLPI Plugin renamer Development Team.

https://forge.indepnet.net/projects/renamer
------------------------------------------------------------------------

LICENSE

This file is part of GLPI Plugin renamer project.

GLPI Plugin renamer is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

GLPI Plugin renamer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI Plugin renamer. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------------

@package   GLPI Plugin renamer
@author    Stanislas Kita (teclib')
@copyright Copyright (c) 2014 GLPI Plugin renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://forge.indepnet.net/projects/renamer
@since     2014

------------------------------------------------------------------------
*/

include('../../../inc/includes.php');

$plugin  = new Plugin();
$renamer = new PluginRenamerRenamer();

if ($plugin->isActivated('renamer')) {
   
   if (isset($_GET['action']) && $_GET['action'] == 'overloadlanguage') {
      
      Html::popHeader('Renamer', $_SERVER['PHP_SELF']);
      $renamer->showFormToOverloadLanguage();
      Html::popFooter();
      
   } else if (isset($_GET['action']) && $_GET['action'] == 'updateWord') {
      
      Html::popHeader('Renamer', $_SERVER['PHP_SELF']);
      $renamer->showFormToUpdateOverloadLanguage($_GET['id']);
      Html::popFooter();
      
   } else {
      Html::header(__("Setup - Renamer", "renamer"), $_SERVER['PHP_SELF'], 'plugins', 'Renamer', 'configuration');
      $renamer->showForm();
   }
   
} else {
   global $CFG_GLPI;
   echo '<div class=\'center\'><br><br><img src=\'' . $CFG_GLPI['root_doc'] . '/pics/warning.png\' alt=\'warning\'><br><br>';
   echo '<b>' . __("Thank you to activate plugin", "renamer") . '</b></div>';
}

Html::footer();