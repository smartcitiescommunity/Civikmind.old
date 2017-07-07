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
@author    Stanislas Kita (teclib)
@copyright Copyright (c) 2014 GLPI Plugin renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://forge.indepnet.net/projects/renamer
@since     2014

------------------------------------------------------------------------
*/

class PluginRenamerRenamer extends CommonDBTM {
   
   static function canCreate() {
      return Session::haveRight('config', UPDATE);
   }
   
   static function canView() {
      return Session::haveRight('config', READ);
   }
   
   function showForm() {
      
      echo "<div>";
      $this->showBtnToRestoreLanguage();
      echo "<br>";
      $this->showHistory();
      echo "<br>";
      $this->showFormToOverload();
      echo "</div>";
      
      /*require_once('../lib/PoParser.php');
      global $CFG_GLPI;
      $file = $this->getLanguageFile('Français');
      $poParser = new PoParser();
      $entries = $poParser->parse($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/'.$file);
      
      foreach($entries as $entry){
      var_dump($entry);
      }*/
      
   }
   
   
   function showFormToOverload() {
      global $CFG_GLPI;
      
      $conf = new PluginRenamerConfig();
      $conf->getFromDB(1);
      $lang      = $conf->getelectedLanguage();
      $lang_user = $this->getLanguage($_SESSION['glpilanguage'], $lang);
      
      
      $content = "<table class='tab_cadre'cellpadding='5'>";
      $content .= "<th colspan='2'>" . __('Overload Language', 'renamer') . "</th>";
      
      $content .= "<tr class='headerRow'>";
      $content .= "<th>" . __('Language', 'renamer') . "</th>";
      $content .= "<th>" . __('Search Word', 'renamer') . "</th>";
      $content .= "</tr>";
      
      $content .= "<tr class='tab_bg_1'>";
      $content .= "<td class='center'>";
      
      if ($conf->fields['lang_selected'] == null) {
         
         $content .= Dropdown::showLanguages("language", array(
            'display' => false,
            'value' => $_SESSION['glpilanguage'],
            'rand' => ''
         ));
         
      } else {
         
         $content .= '<select id="dropdown_language" name="dropdown_language" selected="selected"  >';
         foreach ($lang as $l) {
            if ($l == $lang_user) {
               $content .= ' <option value="' . $l . '" selected="selected">' . $l . '</option>';
            } else {
               $content .= ' <option value="' . $l . '">' . $l . '</option>';
            }
         }
         $content .= '</select>';
      }
      
      $content .= "</td>";
      
      $content .= "<td class='center'>";
      $content .= "<input type='text' id='searchword' />";
      $content .= "<div style='width:24px; float:right; padding-left:10px;' id='infoSearchWord'><img id='waitLoading' style='width:24px; display:none;' src='../pics/loading.gif'></div>";
      $content .= "</td>";
      $content .= "</tr>";
      
      $content .= "<table class='tab_cadre'cellpadding='5' id='tableOverloadWord'>";
      $content .= "<th colspan='6'>" . __("List of words found", "renamer") . "</th>";
      $content .= "<tr class='headerRow'>";
      $content .= "<th>" . __("ID", "renamer") . "</th>";
      $content .= "<th>" . __("msgctx", "renamer") . "</th>";
      $content .= "<th>" . __("plural", "renamer") . "</th>";
      $content .= "<th>" . __("String", "renamer") . "</th>";
      $content .= "<th colspan='2'>" . __("Overload", "renamer") . "</th>";
      //$content .= "<th></th>";
      $content .= "</tr>";
      
      $content .= "<br>";
      $content .= "<tbody id='tbody'>";
      
      $content .= "</td>";
      $content .= "</tbody>";
      $content .= "</table>";
      
      $content .= "<input type='hidden' name='users_id'      id='users_id'      value=" . Session::getLoginUserID() . ">";
      $content .= "<input type='hidden' name='date_overload' id='date_overload' value=" . date("Y-m-d") . ">";
      
      $content .= "</table>";
      
      echo $content;
   }
   
   
   
   
   function showHistory() {
      global $CFG_GLPI;
      $res = $this->getHistory();
      
      if ($res->num_rows > 0) {
         
         $content = "<table id='table2' class='tab_cadre_fixe'>";
         $content .= "<th colspan='10'>" . __("History of overload", "renamer") . "</th>";
         
         $content .= "<tr class='headerRow'>";
         $content .= "<th>" . __("ID", "renamer") . "</th>";
         $content .= "<th>" . __("msgid", "renamer") . "</th>";
         $content .= "<th>" . __("msgctxt", "renamer") . "</th>";
         $content .= "<th>" . __("Date", "renamer") . "</th>";
         $content .= "<th>" . __("Language", "renamer") . "</th>";
         $content .= "<th>" . __("Original", "renamer") . "</th>";
         $content .= "<th>" . __("Overload", "renamer") . "</th>";
         $content .= "<th>" . __("User") . "</th>";
         $content .= "<th>" . __("Restore") . "</th>";
         $content .= "<th>" . __("Update", "renamer") . "</th>";
         $content .= "</tr>";
         
         $user = new User();
         
         while ($row = $res->fetch_assoc()) {
            
            $user->getFromDB($row["users_id"]);
            
            $content .= "<tr class='center'>";
            $content .= "<td>" . $row["id"] . "</td>";
            $content .= "<td lang='en' dir='ltr'>";
            $content .= implode('<br>', unserialize(stripslashes(stripslashes($row['msgid']))));
            $content .= "</td>";
            
            $content .= "<td>";
            if ($row['context'] == null) {
               $content .= __('No', 'renamer');
            } else {
               $content .= implode('<br>', unserialize(stripslashes(stripslashes(str_replace("]", "'", $row['context'])))));
            }
            $content .= "</td>";
            
            $content .= "<td>" . Html::convDate($row["date_overload"]) . "</td>";
            $content .= "<td>" . $row["lang"] . "</td>";
            $content .= "<td>";
            $original = unserialize(stripslashes(stripslashes(str_replace("]", "'", $row['original']))));
            $content .= $original;
            $content .= "</td>";
            $content .= "<td>" . $row["overload"] . "</td>";
            $content .= "<td>" . $user->getName() . "</td>";
            
            $content .= "<td><img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/bin16.png' onclick='restoreWord(" . $row['id'] . ")'" . "style='cursor: pointer;' title='" . __("Delete overload", "renamer") . "'/></td>";
            
            $content .= "<td><input type='text' id='updateWord" . $row["id"] . "' value='$original' /> ";
            $content .= "<img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/update16.png' onclick='updateOverload(" . $row['id'] . ")'" . "style='cursor: pointer;' title='" . __("Update overload", "renamer") . "'/>
                    <img id='waitLoadingOnUpdate' style='min-width:24px; display:none;' class='center' src='../pics/please_wait.gif'></td>";
            
         }
         $content .= "</table>";
         
      } else {
         $content = "<table id='table1' class='tab_cadre_fixe'>";
         $content .= "<th colspan='10'>" . __("History of overload", "renamer") . "</th>";
         
         $content .= "<tr class='tab_bg_1'>";
         
         $content .= "<td class='center'>";
         $content .= __("No history to show for the moment", "renamer");
         $content .= "</td>";
         
         $content .= "</tr>";
         
         $content .= "</table>";
      }
      
      echo $content;
   }
   
   /**
    * function to show boutton to overload Language or restore
    */
   function showBtnToRestoreLanguage() {
      
      global $CFG_GLPI;
      
      $content = "<table id='table1'  class='tab_cadre_fixe'>";
      $content .= "<th colspan='3'>" . __("Restore") . "</th>";
      
      $content .= "<tr class='headerRow'>";
      $content .= "<th>" . __('Restore all languages', 'renamer') . "</th>";
      $content .= "<th colspan='2'>" . __("Restore a language ", "renamer") . "</th>";
      $content .= "</tr>";
      
      $content .= "<tr class='tab_bg_1'>";
      
      $content .= "<td style='text-align: center;'>";
      $content .= "<input  onclick='restoreAllLocaleFiles();'  value='" . __('Restore') . "' class='submit'    style='width : 200px;'>";
      $content .= "</td>";
      
      $content .= "<td style='text-align: center;'>";
      $content .= Dropdown::showLanguages("languageToRestore", array(
         'display' => false,
         'value' => $_SESSION['glpilanguage'],
         'rand' => ''
      ));
      $content .= "</td>";
      $content .= "<td style='text-align: center;'>";
      $content .= "<input onclick='restoreLocaleFiles();' value='" . __('Restore') . "' class='submit' style='width: 200px;'>";
      $content .= "</td>";
      
      $content .= "</tr>";
      $content .= "</table>";
      
      echo $content;
   }
   
   
   
   /**
    * Function to retrieve language file with name of language
    * @param $lang
    * @return mixed|string
    */
   public function getLanguageFile($lang) {
      global $CFG_GLPI;
      $file = "";
      
      foreach ($CFG_GLPI["languages"] as &$local) {
         if ($lang == $local[0])
            $file = $local[1];
      }
      $file = str_replace('mo', 'po', $file);
      
      return $file;
   }
   
   
   /**
    * Function to find all links record for an item
    * @param $item
    * @return Query
    */
   public function getHistory() {
      global $DB;
      return $DB->query("SELECT `glpi_plugin_renamer_renamers`.*
                        FROM `glpi_plugin_renamer_renamers`");
   }
   
   /**
    * Function to check if the word to overload is an overload of another word
    * @param $_post
    * @return true
    */
   public function isAlreadyOverload($id, $wordToOverload, $context) {
      
      //$id = stripslashes($id);
      // $wordToOverload = stripslashes($wordToOverload);
      
      if ($context != 'null') {
         // $context = stripslashes($context);
         
         
         return $this->getFromDBByQuery(" WHERE `msgid` = '" . $id . "'
        and `original` = '" . $wordToOverload . "' and `context` = '" . $context . "' ");
      } else {
         return $this->getFromDBByQuery(" WHERE `msgid` = '" . $id . "'
        and `original` = '" . $wordToOverload . "'");
      } 
   }
   
   
   /**
    * function to update translation
    * $file_patch -> file to update
    * @param $file_patch
    */
   public function updateTranslation($file_patch) {
      
      $file_patch_mo = substr($file_patch, 0, -3) . ".mo";
      
      exec("msgcat " . $file_patch . " | msgfmt -o " . $file_patch_mo . " - ");
      
      // Convert XXX.po to XXX.mo
      //global $CFG_GLPI;
      //require($_SERVER['DOCUMENT_ROOT'].$CFG_GLPI["root_doc"].'/plugins/renamer/lib/php-mo.php');
      //@phpmo_convert($file_patch,substr($file_patch,0,-3).".mo");
   }
   
   
   /**
    * save file into tmp directorie
    * @param $file
    * @return bool
    */
   public function saveFileIntoTmp($file) {
      
      global $CFG_GLPI;
      if (!copy($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file, $_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/plugins/renamer/tmp/' . $file)) {
         Toolbox::logInFile('renamer', sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file) . "\n");
         return false;
      } 
      return true;
   }
   
   /**
    * remove file in tmp directories
    * @param $file
    * @return bool
    */
   public function removeFileIntoTmp($file) {
      global $CFG_GLPI;
      if (!unlink($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/plugins/renamer/tmp/' . $file)) {
         Toolbox::logInFile('renamer', sprintf(__('Can\'t remove file  \'%1$s\' in tmp folder', 'renamer'), $file) . "\n");
         return false;
      } 
      return true;
   }
   
   /**
    * restore a file in tmp directorie to locale directories of glpi
    * @param $file
    * @return bool
    */
   public function restoreFileFromTmp($file) {
      global $CFG_GLPI;
      
      if ($this->removeFileFromLocaleOfGlpi($file)) {
         if (!copy($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/plugins/renamer/tmp/' . $file, $_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file)) {
            Toolbox::logInFile('renamer', sprintf(__('Can\'t restore file  \'%1$s\' in locale folder of Glpi', 'renamer'), $file) . "\n");
            return false;
         } else {
            return true;
         }
      }
      return false;
   }
   
   /**
    * Remove file in local directorie of glpi
    * @param $file
    * @return bool
    */
   public function removeFileFromLocaleOfGlpi($file) {
      global $CFG_GLPI;
      if (!unlink($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file)) {
         Toolbox::logInFile('renamer', sprintf(__('Can\'t remove file  \'%1$s\' in locale folder of Glpi', 'renamer'), $file) . "\n");
         return false;
      } 
      return true;
   }
   
   private function getLanguage($glpilanguage, $langSelected) {
      global $CFG_GLPI;
      $lang = '';
      
      foreach ($CFG_GLPI["languages"] as $key => $value) {
         if ($glpilanguage == $key) {
            $lang = $value[0];
            break;
         }
      }
      return $lang; 
   }
   
}
