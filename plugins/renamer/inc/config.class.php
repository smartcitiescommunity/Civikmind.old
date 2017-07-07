<?php

/*
------------------------------------------------------------------------
GLPI Plugin Renamer
Copyright (C) 2014 by the GLPI Plugin Renamer Development Team.

https://forge.indepnet.net/projects/mantis
------------------------------------------------------------------------

LICENSE

This file is part of GLPI Plugin Renamer project.

GLPI Plugin Renamer is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

GLPI Plugin Renamer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI Plugin Renamer. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------------

@package   GLPI Plugin Renamer
@author    Stanislas Kita (teclib')
@co-author François Legastelois (teclib')
@co-author Le Conseil d'Etat
@copyright Copyright (c) 2014 GLPI Plugin Renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://forge.indepnet.net/projects/mantis
@since     2014

------------------------------------------------------------------------
*/

/**
 * Class PluginMantisConfig pour la partie gestion de la configuration
 */
class PluginRenamerConfig extends CommonDBTM {
   
   /**
    * Function to define if the user have right to create
    * @return bool
    */
   static function canCreate() {
      return Session::haveRight('config', UPDATE);
   }
   
   
   /**
    * Function to define if the user have right to view
    * @return bool
    */
   static function canView() {
      return Session::haveRight('config', READ);
   }
   
   
   /**
    * Function to show form to configure the plugin
    */
   function showConfigForm() {
      global $CFG_GLPI;
      //we recover the first and only record
      $this->getFromDB(1);
      
      $langSlected = unserialize(stripslashes($this->fields['lang_selected']));

      $target = $this->getFormURL();
      if (isset($options['target'])) {
         $target = $options['target'];
      }
      
      $content = "<form method='post' action='" . $target . "' method='post'>";
      $content .= "<table class='tab_cadre' >";
      
      $content .= "<tr>";
      $content .= "<th colspan='2'>" . __("Setup - Renamer", "renamer") . "</th>";
      $content .= "</tr>";
      
      
      $content .= "<tr class='tab_bg_1'>";
      $content .= "<th>" . __("Choose languages to display", "renamer") . "</th>";
      
      $content .= '<td>';
      $content .= '<select id="pick_list_lang" name="pick_list_lang[]" multiple="multiple">';
      foreach ($CFG_GLPI["languages"] as $lang) {
         if (is_array($langSlected) && in_array($lang, $langSlected)) {
            $content .= ' <option value="' . $lang[0] . '" selected="selected">' . $lang[0] . '</option>';
         } else {
            $content .= ' <option value="' . $lang[0] . '">' . $lang[0] . '</option>';
         }
      }
      $content .= '</select>';
      $content .= '</td>';
      
      $content .= "<input type='hidden' name='id' value=" . $this->fields['id'] . ">";
      $content .= "</table>";
      
      $content .= "<br><center><input id='update' class='submit center-h' type='submit' name='update' value='" . __("Update", "renamer") . "'></center>";
      $content .= Html::closeForm(false);
      
      echo $content;
   }

   public function getelectedLanguage() {    
      $this->getFromDB(1);
      $langSelected = unserialize(stripslashes($this->fields['lang_selected']));
      $lang         = array();
      
      if ($langSelected === false) {
         return false;
      }
      
      foreach ($langSelected as $l) {
         $lang[] = $l[0];
      }
      
      return $lang;
   }
}