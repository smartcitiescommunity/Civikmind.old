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


class PluginArsurveysConfig extends Config {

    static private $_instance = NULL;

    function getName($with_comment=0) {
        global $LANG;

        return $LANG['plugin_arsurveys']["name"];  
    }

    /**
     * Singleton for the unique config record
     */
    static function getInstance() {

        if (!isset(self::$_instance)) {
            self::$_instance = new self();
            if (!self::$_instance->getFromDB(1)) {
                self::$_instance->getEmpty();
            }
        }
        return self::$_instance;
    }


    static function showConfigForm($item) {
        global $LANG, $DB;

        $config = self::getInstance();

        $config->showFormHeader();

        echo "<tr class='tab_bg_1'>";
        echo "<td colspan=2>".$LANG['plugin_arsurveys']['config']['bad_threshold']."&nbsp;:</td><td colspan=2>";
        echo "<input type='text' name='bad_threshold' value='".$config->fields['bad_threshold']."'>" ;
        echo "</td></tr>\n";

        echo "<tr class='tab_bg_1'>";
        echo "<td colspan=2>".$LANG['plugin_arsurveys']['config']['good_threshold']."&nbsp;:</td><td colspan=2>";
        echo "<input type='text' name='good_threshold' value='".$config->fields['good_threshold']."'>" ;
        echo "</td></tr>\n";

        echo "<tr class='tab_bg_1'>";
        echo "<td colspan=2>".$LANG['plugin_arsurveys']['config']['force_positive_notif']."&nbsp;:</td><td colspan=2>";
        Dropdown::showYesNo("force_positive_notif",$config->fields["force_positive_notif"]);
        echo "</td></tr>\n";

        echo "<tr class='tab_bg_1'>";
        echo "<td colspan=2>".$LANG['plugin_arsurveys']['config']['comments']."&nbsp;:";
        echo "</td><td colspan=2 class='center'>";
        echo "<textarea cols='60' rows='5' name='comment' >".$config->fields['comment']."</textarea>";
        echo "</td></tr>\n";

        echo "<tr class='tab_bg_1'>";
        echo "<td colspan=2>".$LANG['plugin_arsurveys']['config']['datemod']."&nbsp;: </td><td colspan=2>";
        echo Html::convDateTime($config->fields["date_mod"]);
        echo "</td></tr>\n";

        $config->showFormButtons(array('candel'=>false));

        return false;
    }


    function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
        global $LANG;

        if ($item->getType()=='Config') {
           return $LANG['plugin_arsurveys']["name"];
        }
        return '';
    }


    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

        if ($item->getType()=='Config') {
            self::showConfigForm($item);
        }
        return true;
    }

    function prepareInputForUpdate($input){
       return $input ;
    }

}
