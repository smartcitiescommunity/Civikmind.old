<?php
/*
 * @version $Id: HEADER 15930 2011-10-30 15:47:55Z tsmr $
 -------------------------------------------------------------------------
 seasonality plugin for GLPI
 Copyright (C) 2009-2016 by the seasonality Development Team.

 https://github.com/InfotelGLPI/seasonality
 -------------------------------------------------------------------------

 LICENSE
      
 This file is part of seasonality.

 seasonality is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 seasonality is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with seasonality. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

include ('../../../inc/includes.php');

Html::header_nocache();
Session::checkLoginUser();
header("Content-Type: text/html; charset=UTF-8");

if (isset($_POST['action'])) {
   switch ($_POST['action']) {
      case "load" :
         if (strpos($_SERVER['HTTP_REFERER'], "ticket.form.php") !== false 
               || strpos($_SERVER['HTTP_REFERER'], "helpdesk.public.php") !== false
               || strpos($_SERVER['HTTP_REFERER'], "tracking.injector.php") !== false) {

            $rand = mt_rand();

            $params = array('root_doc' => $CFG_GLPI['root_doc']);
                        
            echo "<script type='text/javascript'>";
            echo "var seasonality = $(document).seasonality(".json_encode($params).");";
            echo "seasonality.addelements();";
            echo "</script>";
         }
         break;
   }
}
?>
