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

@package   GLPI Plugin Renamer
@author    Stanislas Kita (teclib)
@copyright Copyright (c) 2014 GLPI Plugin renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://forge.indepnet.net/projects/renamer
@since     2014

------------------------------------------------------------------------
*/

/**
 * Class PluginRenamerProfile pour la gestion des profiles
 */
class PluginRenamerProfile extends CommonDBTM {
   static function canCreate() {
      
      if (isset($_SESSION["glpi_plugin_renamer_profile"])) {
         return ($_SESSION["glpi_plugin_renamer_profile"]['renamer'] == 'w');
      }
      return false;
   }
   
   static function canView() {
      
      if (isset($_SESSION["glpi_plugin_renamer_profile"])) {
         return ($_SESSION["glpi_plugin_renamer_profile"]['renamer'] == 'w' || $_SESSION["glpi_plugin_renamer_profile"]['renamer'] == 'r');
      }
      return false;
   }
   
   static function createAdminAccess($ID) {
      
      $myProf = new self();
      // si le profile n'existe pas déjà dans la table profile de mon plugin
      if (!$myProf->getFromDB($ID)) {
         // ajouter un champ dans la table comprenant l'ID du profil d la personne connecté et le droit d'écriture
         $myProf->add(array(
            'id' => $ID,
            'right' => 'w'
         ));
      }
      
   }
   
   function showForm($id, $options = array()) {
      
      if (!Session::haveRight("profile", READ)) {
         return false;
      }
      
      $target = $this->getFormURL();
      if (isset($options['target'])) {
         $target = $options['target'];
      }
      
      $canedit = Session::haveRight("profile", UPDATE);
      $prof    = new Profile();
      if ($id) {
         $this->getFromDB($id);
         $prof->getFromDB($id);
      }
      
      echo "<form action='" . $target . "' method='post'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='2' class='center b'>" . sprintf(__('%1$s %2$s'), ('gestion des droits :'), Dropdown::getDropdownName("glpi_profiles", $this->fields["id"]));
      echo "</th></tr>";
      
      echo "<tr class='tab_bg_2'>";
      echo "<td>Utiliser Mon Plugin</td><td>";
      Profile::dropdownNoneReadWrite("right", $this->fields["right"], 1, 1, 1);
      echo "</td></tr>";
      
      if ($canedit) {
         echo "<tr class='tab_bg_1'>";
         echo "<td class='center' colspan='2'>";
         echo "<input type='hidden' name='id' value=$id>";
         echo "<input type='submit' name='update_user_profile' value='Mettre à jour' class='submit'>";
         echo "</td></tr>";
      }
      echo "</table>";
      Html::closeForm();
   }
   
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      
      if ($item->getType() == 'Profile') {
         return "Renamer";
      }
      return '';
   }
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      
      if ($item->getType() == 'Profile') {
         $prof = new self();
         $ID   = $item->getField('id');
         
         if (!$prof->GetfromDB($ID)) {
            $prof->createAccess($ID);
         }
         
         $prof->showForm($ID);
      }
      return true;
   }
   
   function createAccess($ID) {
      $this->add(array(
         'id' => $ID
      ));
   }
   
   static function changeProfile() {
      
      $prof = new self();
      if ($prof->getFromDB($_SESSION['glpiactiveprofile']['id'])) {
         $_SESSION["glpi_plugin_renamer_profile"] = $prof->fields;
      } else {
         unset($_SESSION["glpi_plugin_renamer_profile"]);
      }
   }
}
