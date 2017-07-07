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

class PluginArsurveysTicketSatisfaction extends CommonDropdown 
{
   
   static function getTypeName($nb = 0) {
      global $LANG;

      return $LANG['plugin_arsurveys']["ticketsatisfactiontype"] ; 
   }

   static function plugin_item_update_arsurveys($item) {
      // just push notifications
      $me = new PluginArsurveysTicketSatisfaction ;
      NotificationEvent::raiseEvent('bad_survey', $me, array('item' => $item, 'ticketsatisfaction' => $item)) ;
      NotificationEvent::raiseEvent('good_survey', $me, array('item' => $item, 'ticketsatisfaction' => $item)) ;

   }

   function getEntityID(){
      return -1 ;
   }
   
}
