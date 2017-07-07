<?php

/*
  ------------------------------------------------------------------------
  Surveyticket
  Copyright (C) 2012-2016 by the Surveyticket plugin Development Team.

  ------------------------------------------------------------------------

  LICENSE

  This file is part of Surveyticket plugin project.

  Surveyticket plugin is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Surveyticket plugin is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with Surveyticket plugin. If not, see <http://www.gnu.org/licenses/>.

  ------------------------------------------------------------------------

  @package   Surveyticket plugin
  @author    David Durieux
  @author    Infotel
  @copyright Copyright (c) 2012-2016 Surveyticket plugin team
  @license   AGPL License 3.0 or (at your option) any later version
  http://www.gnu.org/licenses/agpl-3.0-standalone.html
  @link      https://github.com/pluginsGLPI/surveyticket
  @since     2012

  ------------------------------------------------------------------------
 */


if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginSurveyticketProfile
 */
class PluginSurveyticketProfile extends CommonDBTM {

   static $rightname = "profile";

   /**
    * Get Tab Name used for itemtype
    *
    * @param CommonGLPI $item
    * @param int $withtemplate
    * @return string|text
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      if ($item->getType() == 'Profile') {
         return PluginSurveyticketSurvey::getTypeName(2);
      }
      return '';
   }

   /**
    * show Tab content
    *
    * @param CommonGLPI $item
    * @param int $tabnum
    * @param int $withtemplate
    * @return bool
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {

      if ($item->getType() == 'Profile') {
         $ID = $item->getID();
         $prof = new self();

         self::addDefaultProfileInfos($ID, 
            array('plugin_surveyticket'     => 0,
                  'plugin_surveyticket_use' => 0,));
         $prof->showForm($ID);
      }
      return true;
   }

   /**
    * @param $ID
    */
   static function createFirstAccess($ID) {
      //85
      $rights = array('plugin_surveyticket' => 127,
                      'plugin_surveyticket_use' => CREATE);
      
      self::addDefaultProfileInfos($ID, $rights, true);
   }

   /**
    * @param $profiles_id
    * @param $rights
    * @param bool $drop_existing
    * @internal param $profile
    */
   static function addDefaultProfileInfos($profiles_id, $rights, $drop_existing = false) {

      $profileRight = new ProfileRight();
      foreach ($rights as $right => $value) {
         if (countElementsInTable('glpi_profilerights', "`profiles_id`='$profiles_id' AND `name`='$right'") && $drop_existing) {
            $profileRight->deleteByCriteria(array('profiles_id' => $profiles_id, 'name' => $right));
         }
         if (!countElementsInTable('glpi_profilerights', "`profiles_id`='$profiles_id' AND `name`='$right'")) {
            $myright['profiles_id'] = $profiles_id;
            $myright['name'] = $right;
            $myright['rights'] = $value;
            $profileRight->add($myright);

            //Add right to the current session
            $_SESSION['glpiactiveprofile'][$right] = $value;
         }
      }
   }

   /**
    * Show profile form
    *
    * @param int $profiles_id
    * @param bool $openform
    * @param bool $closeform
    * @return nothing
    * @internal param int $items_id id of the profile
    * @internal param value $target url of target
    */
   function showForm($profiles_id = 0, $openform = TRUE, $closeform = TRUE) {

      echo "<div class='firstbloc'>";
      if (($canedit = Session::haveRightsOr(self::$rightname, array(CREATE, UPDATE, PURGE))) && $openform) {
         $profile = new Profile();
         echo "<form method='post' action='" . $profile->getFormURL() . "'>";
      }

      $profile = new Profile();
      $profile->getFromDB($profiles_id);
      
      $rights = $this->getAllRights();
      $profile->displayRightsChoiceMatrix($rights, array('canedit'       => $canedit,
                                                         'default_class' => 'tab_bg_2',
                                                         'title'         => __('General')));

      if ($canedit && $closeform) {
         echo "<div class='center'>";
         echo Html::hidden('id', array('value' => $profiles_id));
         echo Html::submit(_sx('button', 'Save'), array('name' => 'update'));
         echo "</div>\n";
         Html::closeForm();
      }
      echo "</div>";
   }

   /**
    * @param bool $all
    * @return array
    */
   static function getAllRights($all = false) {
      $rights = array(
         array('itemtype' => 'PluginSurveyticketSurvey',
            'label' => _n('Survey', 'Surveys', 2, 'surveyticket'),
            'field' => 'plugin_surveyticket'
         ),
      );
      
      $rights[] = array('itemtype' => 'PluginSurveyticketTicket',
                        'label'    => __('Use the questionnaire when creating a ticket', 'surveyticket'),
                        'field'    => 'plugin_surveyticket_use',
                        'rights' => array(CREATE => __('Create')));
      return $rights;
   }

   /**
    * Init profiles
    *
    * @param $old_right
    * @return int
    */
   static function translateARight($old_right) {
      switch ($old_right) {
         case '':
            return 0;
         case 'r' :
            return READ;
         case 'w':
            return ALLSTANDARDRIGHT;
         case '0':
         case '1':
            return $old_right;

         default :
            return 0;
      }
   }

   /**
    * @since 0.85
    * Migration rights from old system to the new one for one profile
    * @param $profiles_id the profile ID
    */
   static function migrateOneProfile($profiles_id) {
      global $DB;
      //Cannot launch migration if there's nothing to migrate...
      if (!TableExists('glpi_plugin_surveyticket_profiles')) {
         return true;
      }

      foreach ($DB->request('glpi_plugin_surveyticket_profiles', "`profiles_id`='$profiles_id'") as $profile_data) {

         $matching = array('config' => 'plugin_surveyticket');
         $current_rights = ProfileRight::getProfileRights($profiles_id, array_values($matching));
         foreach ($matching as $old => $new) {
            if (!isset($current_rights[$old])) {
               $query = "UPDATE `glpi_profilerights` 
                         SET `rights`='" . self::translateARight($profile_data[$old]) . "' 
                         WHERE `name`='$new' AND `profiles_id`='$profiles_id'";
               $DB->query($query);
            }
         }
      }
   }

   /**
    * Initialize profiles, and migrate it necessary
    */
   static function initProfile() {
      global $DB;
      $profile = new self();

      //Add new rights in glpi_profilerights table
      foreach ($profile->getAllRights(true) as $data) {
         if (countElementsInTable("glpi_profilerights", "`name` = '" . $data['field'] . "'") == 0) {
            ProfileRight::addProfileRights(array($data['field']));
         }
      }

      //Migration old rights in new ones
      foreach ($DB->request("SELECT `id` FROM `glpi_profiles`") as $prof) {
         self::migrateOneProfile($prof['id']);
      }
      foreach ($DB->request("SELECT *
                           FROM `glpi_profilerights` 
                           WHERE `profiles_id`='" . $_SESSION['glpiactiveprofile']['id'] . "' 
                              AND `name` LIKE '%plugin_surveyticket%'") as $prof) {
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