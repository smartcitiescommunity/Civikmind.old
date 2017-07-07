<?php
/*
 * -------------------------------------------------------------------------
Form Validation plugin
Copyright (C) 2016 by Raynet SAS a company of A.Raymond Network.

http://www.araymond.com
-------------------------------------------------------------------------

LICENSE

This file is part of Form Validation plugin for GLPI.

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

$AJAX_INCLUDE = 1;
include ('../../../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if( !isset($LANG['plugin_formvalidation'])){
   $dir = GLPI_ROOT . "/plugins/formvalidation/locales/";
   // try to load en_GB default language
   // to be sure that it will default to english if user's language
   // can't be found
   if (file_exists($dir . "en_GB.php")) {
      include ($dir . "en_GB.php");
   }
   // and then try to load user's own language   
   if (file_exists($dir.$_SESSION["glpilanguage"].'.php')) {
      include ($dir.$_SESSION["glpilanguage"].'.php');
   }
}

// JSON encode all strings that are needed in formvalidation.js
$localization = array(
//   'job' => array( 44 => "LANG['job'][44]" ),
//                        'common' => array( 17 => "LANG['common'][17]",
//                                           36 => "LANG['common'][36]")
                     );

// add plugin own language strings to $localization
$localization['plugin_formvalidation'] = $LANG['plugin_formvalidation'] ;

echo json_encode( $localization, JSON_HEX_APOS | JSON_HEX_QUOT ) ;

