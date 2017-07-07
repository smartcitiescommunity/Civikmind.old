<?php
/*
 * @version $Id: profile.class.php 246 2016-12-05 17:14:42Z yllen $
 -------------------------------------------------------------------------
   LICENSE

 This file is part of Appliances plugin for GLPI.

 Appliances is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Appliances is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with Appliances. If not, see <http://www.gnu.org/licenses/>.

 @package   appliances
 @author    Xavier CAILLAUD, Remi Collet, Nelly Mahu-Lasson
 @copyright Copyright (c) 2009-2016 Appliances plugin team
 @license   AGPL License 3.0 or (at your option) any later version
            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 @link      https://forge.glpi-project.org/projects/appliances
 @since     version 2.0
 --------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


/**
 * Class PluginAppliancesProfile
**/
class PluginAppliancesProfile extends Profile {

   static $rightname = "profile";


   /**
    * Get Tab Name used for itemtype
    *
    *  @see CommonGLPI getTabNameForItem()
    **/
    function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType() == 'Profile') {
         return PluginAppliancesAppliance::getTypeName(2);
      }
      return '';
   }


   /**
    * show Tab content
    *
    * @see CommonGLPI::displayTabContentForItem()
    **/
    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='Profile') {
         $ID = $item->getID();
         $prof = new self();

         self::addDefaultProfileInfos($ID, array('plugin_appliances'               => 0,
                                                 'plugin_appliances_open_ticket'   => 0));
         $prof->showForm($ID);
      }
      return true;
   }


   static function createFirstAccess($ID) {

      self::addDefaultProfileInfos($ID, array('plugin_appliances'             => 127,
                                              'plugin_appliances_open_ticket' => 1), true);
   }


   /**
    * @param $profiles_id         integer
    * @param $rights              array
    * @param $drop_existing       boolean (faulse by default)
    *
    **/
   static function addDefaultProfileInfos($profiles_id, $rights, $drop_existing=false) {

      $profileRight = new ProfileRight();
      foreach ($rights as $right => $value) {
         if (countElementsInTable('glpi_profilerights',
                                   "`profiles_id`='$profiles_id' AND `name`='$right'")
             && $drop_existing) {

            $profileRight->deleteByCriteria(array('profiles_id' => $profiles_id,
                                                  'name' => $right));
         }

         if (!countElementsInTable('glpi_profilerights',
                                   "`profiles_id`='$profiles_id' AND `name`='$right'")) {
            $myright['profiles_id'] = $profiles_id;
            $myright['name']        = $right;
            $myright['rights']      = $value;
            $profileRight->add($myright);

            //Add right to the current session
            $_SESSION['glpiactiveprofile'][$right] = $value;
         }
      }
   }


   /**
    * Show profile form
    *
    * @param $profiles_id         integer
    * @param $openform            boolean (true by default)
    * @param $closeform           boolean (true by default)
    *
    **/
   function showForm($profiles_id=0, $openform=TRUE, $closeform=TRUE) {

      echo "<div class='firstbloc'>";
      if (($canedit = Session::haveRightsOr(self::$rightname, array(CREATE, UPDATE, PURGE)))
          && $openform) {
         $profile = new Profile();
         echo "<form method='post' action='".$profile->getFormURL()."'>";
      }

      $profile = new Profile();
      $profile->getFromDB($profiles_id);
      if ($profile->getField('interface') == 'central') {
         $rights = $this->getAllRights();
         $profile->displayRightsChoiceMatrix($rights, array('canedit'       => $canedit,
                                                            'default_class' => 'tab_bg_2',
                                                            'title'         => __('General')));
      }
      echo "<table class='tab_cadre_fixehov'>";
      echo "<tr class='tab_bg_1'><th colspan='4'>".__('Helpdesk')."</th></tr>\n";

      $effective_rights = ProfileRight::getProfileRights($profiles_id,
                                                         array('plugin_appliances_open_ticket'));
      echo "<tr class='tab_bg_2'>";
      echo "<td width='20%'>".__('Associable items to a ticket')."</td>";
      echo "<td colspan='5'>";
      Html::showCheckbox(array('name'    => '_plugin_appliances_open_ticket',
                               'checked' => $effective_rights['plugin_appliances_open_ticket']));
      echo "</td></tr>\n";
      echo "</table>";

      if ($canedit
          && $closeform) {
         echo "<div class='center'>";
         echo Html::hidden('id', array('value' => $profiles_id));
         echo Html::submit(_sx('button', 'Save'), array('name' => 'update'));
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";
   }


   static function getAllRights($all=false) {

      $rights = array(array('itemtype'  => 'PluginAppliancesAppliance',
                            'label'     => _n('Appliance', 'Appliances', 2, 'appliances'),
                            'field'     => 'plugin_appliances'));

      if ($all) {
         $rights[] = array('itemtype' => 'PluginAppliancesAppliance',
                           'label'    =>  __('Associable items to a ticket'),
                           'field'    => 'plugin_appliances_open_ticket');
      }
      return $rights;
   }


    /**
     * Init profiles
     *
     * @param $old_right
     *
     * @return integer
     */
   static function translateARight($old_right) {

      switch ($old_right) {
         case '':
            return 0;

         case 'r' :
            return READ;

         case 'w':
            return ALLSTANDARDRIGHT + READNOTE + UPDATENOTE;

         case '0':
         case '1':
            return $old_right;

         default :
            return 0;
      }
   }


   /**
    * @since 0.85
    *
    * Migration rights from old system to the new one for one profile
    * @param $profiles_id the profile ID
    *
    * @return bool
   **/
   static function migrateOneProfile($profiles_id) {
      global $DB;

      //Cannot launch migration if there's nothing to migrate...
      if (!TableExists('glpi_plugin_appliances_profiles')) {
      return true;
      }

      foreach ($DB->request('glpi_plugin_appliances_profiles',
                            "`profiles_id`='$profiles_id'") as $profile_data) {

         $matching = array('appliances'  => 'plugin_appliances',
                           'open_ticket' => 'plugin_appliances_open_ticket');
         $current_rights = ProfileRight::getProfileRights($profiles_id, array_values($matching));
         foreach ($matching as $old => $new) {
            if (!isset($current_rights[$old])) {
               $query = "UPDATE `glpi_profilerights`
                         SET `rights`='".self::translateARight($profile_data[$old])."'
                         WHERE `name`='$new' AND `profiles_id`='$profiles_id'";
               $DB->query($query);
            }
         }
      }
   }


   /**
    * Initialize profiles, and migrate it necessary
   **/
   static function initProfile() {
      global $DB;

      $profile = new self();

      //Add new rights in glpi_profilerights table
      foreach ($profile->getAllRights(true) as $data) {
         if (countElementsInTable("glpi_profilerights",
                                  "`name` = '".$data['field']."'") == 0) {
            ProfileRight::addProfileRights(array($data['field']));
         }
      }

      //Migration old rights in new ones
      foreach ($DB->request("SELECT `id` FROM `glpi_profiles`") as $prof) {
         self::migrateOneProfile($prof['id']);
      }
      foreach ($DB->request("SELECT *
                             FROM `glpi_profilerights`
                             WHERE `profiles_id`='".$_SESSION['glpiactiveprofile']['id']."'
                                   AND `name` LIKE '%plugin_appliances%'") as $prof) {
         $_SESSION['glpiactiveprofile'][$prof['name']] = $prof['rights'];
      }
   }


   static function removeRightsFromSession() {

      foreach (self::getAllRights(true) as $right) {
         if (isset($_SESSION['glpiactiveprofile'][$right['field']])) {
            unset($_SESSION['glpiactiveprofile'][$right['field']]);
         }
      }
   }

}
