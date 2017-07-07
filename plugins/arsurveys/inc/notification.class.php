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


if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}


class PluginArsurveysNotification extends CommonDBTM {


    //profiles modification
    function showForm($item, $options=array()) {
        global $LANG;

        $target = $this->getFormURL();
        if (isset($options['target'])) {
            $target = $options['target'];
        }


        if ((TableExists('glpi_profilerights') && !Notification::canView()) || (!TableExists('glpi_profilerights') && !Session::haveRight("notification","r"))) {
           return false;
        }

        $canedit = (TableExists('glpi_profilerights') && Notification::canUpdate()) || Session::haveRight("notification", "w");

        $bad_survey = false ;
        if( $item->fields['event'] == 'bad_survey' ) {
           $bad_survey = true ;
        }

        $config = PluginArsurveysConfig::getInstance() ;
        $threshold = ($bad_survey?$config->fields['bad_threshold']:$config->fields['good_threshold']); // by default
        if(!$this->getFromDBByNotification($item->getID())){
           // must create it with default values
           $this->add( array( 'notifications_id' => $item->getID())) ;
        }
        if( isset($this->fields['threshold'])) {           
           $threshold = $this->fields['threshold'] ;
        }
        
        $force_positive_notif = $config->fields['force_positive_notif'] ;
        if( isset($this->fields['force_positive_notif'])) {
           $force_positive_notif = $this->fields['force_positive_notif'] ;
        }


        echo "<form action='".$target."' method='post'>";
        echo "<table class='tab_cadre_fixe'>";

        echo "<tr><th colspan='2'>".$LANG['plugin_arsurveys']["name"]." : ".$LANG['plugin_arsurveys']["notifconfig"] ."</th></tr>";

        echo "<tr class='tab_bg_2'>";
        echo "<td >".($bad_survey?$LANG['plugin_arsurveys']['config']['bad_threshold']:$LANG['plugin_arsurveys']['config']['good_threshold'])."&nbsp;:</td><td >";
        echo "<input type='text' name='threshold' value='".$threshold."'>" ;
        echo "</td></tr>";

        if(!$bad_survey){
           // then show the setting to force positive notifications even if user's comment to satisfaction survey is empty
           echo "<tr class='tab_bg_2'>";
           echo "<td >".$LANG['plugin_arsurveys']['config']['force_positive_notif']."&nbsp;:</td><td >";
           Dropdown::showYesNo("force_positive_notif",$force_positive_notif);
           echo "</td></tr>";
        }
        if ($canedit) {
            echo "<tr class='tab_bg_1'>";
            echo "<td class='center' colspan='2'>";
            echo "<input type='hidden' name='id' value=".$this->getID().">";
            echo "<input type='hidden' name='notifications_id' value=".$item->getID().">";
            echo "<input type='submit' name='update_notification_config' value=\"".$LANG['plugin_arsurveys']['config']['save']."\"
               class='submit'>";
            echo "</td></tr>";
        }
        echo "</table>";
        Html::closeForm();
    }

    function getFromDBByNotification($notifications_id) {
      global $DB;
		
      $query = "SELECT * FROM `".$this->getTable()."`
               WHERE `notifications_id` = '" . $notifications_id . "' ";
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 1) {
            return false;
         }
         $this->fields = $DB->fetch_assoc($result);
         if (is_array($this->fields) && count($this->fields)) {
            return true;
         } else {
            return false;
         }
      }
      return false;
   }
    
  
    function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
        global $LANG;

        if ($item->getType()=='Notification' && $item->fields['itemtype'] == "PluginArsurveysTicketSatisfaction" && $item->getID() > 0) {
          
           return $LANG['plugin_arsurveys']["name"];
        }
        return '';
    }


    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
        
        if ($item->getType()=='Notification' && $item->fields['itemtype'] == "PluginArsurveysTicketSatisfaction" && $item->getID() > 0) {
            $notif = new self();
            $notif->showForm($item);
        }
        return true;
    }


    static function plugin_item_purge_arsurveys($item) {
       // just delete the record linked to current TicketValidation item
       $me = new self();
       $me->deleteByCriteria(array('notifications_id' => $item->getID()));
    }

}

?>