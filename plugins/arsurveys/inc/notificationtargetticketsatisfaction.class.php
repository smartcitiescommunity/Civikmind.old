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

// Class NotificationTarget
class PluginArsurveysNotificationTargetTicketSatisfaction extends NotificationTargetCommonITILObject {

   //Notification to the group of technician in charge of the item
   const ARSURVEY_ITEM_TECH_IN_CHARGE_IN_GROUP = -10000;
   const ARSURVEY_MANAGER_TECH_IN_CHARGE_IN_GROUP = -10001;

   private static $ASSIGN;
   private static $OBSERVER;
   private static $REQUESTER;

   private $tags = array('ticketsatisfaction.action'       => 'Survey answer type',
                      'ticketsatisfaction.user'            => 'User name',
                      'ticketsatisfaction.ticket'          => 'Ticket number',
                      'ticketsatisfaction.ticketentity'    => 'Ticket entity',
                      'ticketsatisfaction.ticketname'      => 'Ticket Title',
                      'ticketsatisfaction.requesters'      => 'Ticket Requesters',
                      'ticketsatisfaction.url'             => 'Satisfaction URL',
                      'ticketsatisfaction.date_begin'      => 'Start date',
                      'ticketsatisfaction.date_answer'     => 'Answer date',
                      'ticketsatisfaction.satisfaction'    => 'Quality satisfaction',
                      'ticketsatisfaction.comment'         => 'Survey comment',
                      'ticketsatisfaction.friendliness'    => 'Friendliness satisfaction',
                      'ticketsatisfaction.responsetime'    => 'Responsetime satisfaction',
                      'ticketsatisfaction.assigntousers'   => 'Assigned To Technicians',
                      'ticketsatisfaction.assigntogroups'  => 'Assigned To Groups'
                      );

   function __construct($entity='', $event='', $object=null, $options=array()){
   
      parent::__construct($entity, $event, null, $options); // passes null to prevent $this->obj and $this->target_object to be assigned with wrong values

      // defines static variables
      if( defined('CommonITILActor::ASSIGN') ) {
         self::$ASSIGN = constant('CommonITILActor::ASSIGN');
      } else {
         self::$ASSIGN = constant('CommonITILObject::ASSIGN');
      }
      if( defined('CommonITILActor::OBSERVER') ) {
         self::$OBSERVER = constant('CommonITILActor::OBSERVER');
      } else {
         self::$OBSERVER = constant('CommonITILObject::OBSERVER');
      }
      if( defined('CommonITILActor::REQUESTER') ) {
         self::$REQUESTER = constant('CommonITILActor::REQUESTER');
      } else {
         self::$REQUESTER = constant('CommonITILObject::REQUESTER');
      }

   // needs to define the $this->obj to point to associated Ticket, and $this->target_object
      if( isset($options['item']) ) {
         $ticket = new Ticket ;
         $ticket->getFromDB( $options['item']->fields['tickets_id'] ) ;
         $this->obj = $ticket;
         $this->getObjectItem($this->raiseevent);
      }
   }

    function getEvents() {
        global $LANG ;
        return array('bad_survey' => $LANG['plugin_arsurveys']['bad_survey'], 
                     'good_survey' => $LANG['plugin_arsurveys']['good_survey']);
    }

    /**
     * Get all data needed for template processing
     **/
    function getDatasForTemplate($event, $options=array()) {
        global $CFG_GLPI;

        $events = $this->getAllEvents();

        $this->datas['##ticketsatisfaction.action##'] = $events[$event];

        $locTicket = $this->obj ;

        $locTicketSatisfaction = $options['ticketsatisfaction']; //$options['item']->fields['ticketsatisfaction'] ;
        $user = new User();
        $user->getFromDB(Session::getLoginUserID());

        $this->datas['##ticketsatisfaction.user##'] = $user->getName( ) ; //$this->getUserFullName( Session::getLoginUserID() );
        $this->datas['##ticketsatisfaction.ticket##'] = $locTicket->getID() ;
        $this->datas['##ticketsatisfaction.ticketname##'] = $locTicket->fields['name'];
        $this->datas['##ticketsatisfaction.url##'] = urldecode($CFG_GLPI["url_base"]."/index.php?redirect=ticket_".
                                       $locTicket->getID().'_Ticket$3');
        $this->datas['##ticketsatisfaction.date_begin##'] = $locTicketSatisfaction->fields['date_begin'];
        $this->datas['##ticketsatisfaction.date_answer##'] = $locTicketSatisfaction->fields['date_answered'];
        $this->datas['##ticketsatisfaction.satisfaction##'] = $locTicketSatisfaction->fields['satisfaction'];
        $this->datas['##ticketsatisfaction.comment##'] = $locTicketSatisfaction->fields['comment'];
        if(isset($options['item']->fields['friendliness'])) {
           $this->datas['##ticketsatisfaction.friendliness##'] = $locTicketSatisfaction->fields['friendliness'];
        }
        if( isset($options['item']->fields['responsetime'])) {
           $this->datas['##ticketsatisfaction.responsetime##'] = $locTicketSatisfaction->fields['responsetime'];
        }
        
        $this->datas["##ticketsatisfaction.assigntousers##"] = '';
        if ($locTicket->countUsers(self::$ASSIGN)) {
           $users = array();
           foreach ($locTicket->getUsers(self::$ASSIGN) as $tmp) {
              $uid = $tmp['users_id'];
              $user_tmp = new User();
              if ($user_tmp->getFromDB($uid)) {
                 $users[$uid] = $user_tmp->getName();
              }
           }
           $this->datas["##ticketsatisfaction.assigntousers##"] = implode(', ',$users);
        }

        $this->datas["##ticketsatisfaction.assigntogroups##"] = '';
        if ($locTicket->countGroups(self::$ASSIGN)) {
           $groups = array();
           foreach ($locTicket->getGroups(self::$ASSIGN) as $tmp) {
              $gid = $tmp['groups_id'];
              $groups[$gid] = Dropdown::getDropdownName('glpi_groups', $gid);
           }
           $this->datas["##ticketsatisfaction.assigntogroups##"] = implode(', ',$groups);
        }

        $entity = new Entity();
        if ($entity->getFromDB($locTicket->getField('entities_id'))) {
           $this->datas["##ticketsatisfaction.ticketentity##"] = $entity->getField('completename');
        }

        $this->datas["##ticketsatisfaction.requesters##"] = '';
        if ($locTicket->countUsers(self::$REQUESTER)) {
           $users = array();
           foreach ($locTicket->getUsers(self::$REQUESTER) as $tmpusr) {
              $uid = $tmpusr['users_id'];
              $user_tmp = new User();
              if ($uid && $user_tmp->getFromDB($uid)) {
                 $users[] = $user_tmp->getName();                 
              } else {
                 // Anonymous users only in xxx.authors, not in authors
                 $users[] = $tmpusr['alternative_email'];
              }
           }
           $this->datas["##ticketsatisfaction.requesters##"] = implode(', ',$users);
        }


        $this->getTags();
        foreach ($this->tag_descriptions[NotificationTarget::TAG_LANGUAGE] as $tag => $values) {
            if (!isset($this->datas[$tag])) {
                $this->datas[$tag] = $values['label'];
            }
        }
    }


    function getTags() {
        global $LANG;
        foreach ($this->tags as $tag => $label) {
           if( ($tag != 'ticketsatisfaction.friendliness' && $tag != 'ticketsatisfaction.responsetime') || 
              (FieldExists( 'glpi_ticketsatisfactions', 'friendliness' ) && FieldExists('glpi_ticketsatisfactions', 'responsetime' )) ) {
               $this->addTagToList(array('tag'   => $tag,
                                      'label' => $LANG['plugin_arsurveys']["$tag"],
                                      'value' => true));
           }
        }

        asort($this->tag_descriptions);
    }


   /**
   * Summary of checkNotificationTarget
   * @param mixed $data 
   * @param mixed $options 
   */
   function checkNotificationTarget( $data, &$options ) {
      // get ticket      
      $tick = $this->obj ;
      $members = array( ) ;
      $ids = array() ;
      $ret = false ; // no users
      $options['arsurvey']['users_id']=array(); // empty array

      $grp = new Group ;
      $grp->getFromDB( $data['items_id'] ) ;
      Group_User::getDataForGroup( $grp, $members, $ids ) ;

      // search for all ticket tech belonging to this group
      // and store them into options
      // will be used later
      
      foreach( $tick->getUsers( self::$ASSIGN ) as $tech ) {
         if( in_array( $tech['users_id'], $ids )  ) {
            // then send notification
            $options['arsurvey']['users_id'][]=$tech['users_id'];
            $ret = true; // at least one user
         }

      }
      return $ret ;
   }

   /**
    * Summary of checkNotificationThreshold
    * @param mixed $data 
    * @param mixed $options 
    * @return bool
    */
   function checkNotificationThreshold( $data, $options ) {
      $config = PluginArsurveysConfig::getInstance() ;
      $notif = new PluginArsurveysNotification ;
      if( !$notif->getFromDBByNotification( $data['notifications_id'] ) ){
         $notif->fields['threshold'] = null ;
         $notif->fields['force_positive_notif'] = null ;
      }
      switch( $this->raiseevent ) {
         case 'bad_survey' :
            $threshold = ($notif->fields['threshold']!=null ? $notif->fields['threshold'] : $config->fields['bad_threshold']);
            if( (in_array('satisfaction', $options['item']->updates) || in_array( 'friendliness', $options['item']->updates ) || in_array( 'responsetime', $options['item']->updates ))
               && ($options['item']->input['satisfaction'] <= $threshold
                  || (isset($options['item']->input['friendliness']) && $options['item']->input['friendliness'] <= $threshold)
                  || (isset($options['item']->input['responsetime']) && $options['item']->input['responsetime'] <= $threshold)
                  )
               ) {
               return true ;
            }
            break;
         case 'good_survey' :
            $threshold = ($notif->fields['threshold']!=null ? $notif->fields['threshold'] : $config->fields['good_threshold']);
            $force_positive_notif=($notif->fields['force_positive_notif']!=null ? $notif->fields['force_positive_notif'] : $config->fields['force_positive_notif']);
            if( (in_array('satisfaction', $options['item']->updates) || 
               in_array('friendliness', $options['item']->updates) || 
               in_array('responsetime', $options['item']->updates) ||
               in_array('comment', $options['item']->updates)) &&
               ($options['item']->input['satisfaction'] >= $threshold) &&
               (!isset( $options['item']->input['friendliness'] ) || $options['item']->input['friendliness'] >= $threshold) &&
               (!isset( $options['item']->input['responsetime'] ) || $options['item']->input['responsetime'] >= $threshold) &&
               ( $force_positive_notif || !empty($options['item']->input['comment']) )) {

               return true ;
            }
            break;
      }
      return false ;
   }

   /**
    * Summary of getAddressesByTarget
    * @param mixed $data 
    * @param mixed $options 
    */
   function getAddressesByTarget($data, $options = array()) {     
      $exec = true ;
      if( ($data['type'] == self::ARSURVEY_MANAGER_TECH_IN_CHARGE_IN_GROUP
            || $data['type'] == self::ARSURVEY_ITEM_TECH_IN_CHARGE_IN_GROUP) 
          && !$this->checkNotificationTarget( $data, $options ) ) {
         $exec = false ;
      }
      if( $exec && $this->checkNotificationThreshold( $data, $options) ) {
         parent::getAddressesByTarget( $data, $options ) ;
      }
   }

   /**
    * Summary of getSpecificTargets
    * @param mixed $data 
    * @param mixed $options 
    */
   function getSpecificTargets($data, $options){
      switch( $data['type'] ) {
         case self::ARSURVEY_ITEM_TECH_IN_CHARGE_IN_GROUP :            
            $this->getLinkedUserByID($options['arsurvey']['users_id']);
            break ;
         case self::ARSURVEY_MANAGER_TECH_IN_CHARGE_IN_GROUP:
            $this->getAddressesByGroup(1, $data['items_id']);
            break ;
         default :
            parent::getSpecificTargets($data, $options);
      }
   }

   /**
    * Summary of getLinkedUserByID
    * Retreive info for users in $ids of type $type
    * @param mixed $ids array of users_id
    * @param mixed $type 
    */
   function getLinkedUserByID($ids, $type=false) {
      global $DB, $CFG_GLPI;
      if( !$type ) {
         $type = self::$ASSIGN;
      }
      $userlinktable = getTableForItemType($this->obj->userlinkclass);
      $fkfield       = $this->obj->getForeignKeyField();

      //Look for the user by his id
      $query =        $this->getDistinctUserSql().",
                      `$userlinktable`.`use_notification` AS notif,
                      `$userlinktable`.`alternative_email` AS altemail
               FROM `$userlinktable`
               LEFT JOIN `glpi_users` ON (`$userlinktable`.`users_id` = `glpi_users`.`id`)".
               ($type!=self::$OBSERVER?$this->getProfileJoinSql():"")."
               WHERE `$userlinktable`.`$fkfield` = '".$this->obj->fields["id"]."'
                     AND `$userlinktable`.`type` = '$type'
                     AND `$userlinktable`.`users_id` IN (".implode(', ', $ids).")";

      foreach ($DB->request($query) as $data) {
         //Add the user email and language in the notified users list
         if ($data['notif']) {
            $author_email = UserEmail::getDefaultForUser($data['id']);
            $author_lang  = $data["language"];
            $author_id    = $data['id'];

            if (!empty($data['altemail'])
                && $data['altemail'] != $author_email
                && NotificationMail::isUserAddressValid($data['altemail'])) {
               $author_email = $data['altemail'];
            }
            if (empty($author_lang)) {
               $author_lang = $CFG_GLPI["language"];
            }
            if (empty($author_id)) {
               $author_id = -1;
            }
            $this->addToAddressesList(array('email'    => $author_email,
                                            'language' => $author_lang,
                                            'id'       => $author_id,
                                            'type'     => $type )); // $type is passed only to authorize view of tickets by watchers (or observers)
         }
      }
   }

   /**
    * Summary of addGroupsToTargets
    * @param mixed $entity 
    */
   function addGroupsToTargets($entity) {
      global $LANG, $DB;

      parent::addGroupsToTargets($entity) ;

      // Filter groups which can be notified and have members (as notifications are sent to members)
      $query = "SELECT `id`, `name`
                FROM `glpi_groups`".
                getEntitiesRestrictRequest(" WHERE", 'glpi_groups', 'entities_id', $entity, true)."
                      AND `is_usergroup`
                      AND `is_notify`
                ORDER BY `name`";

      foreach ($DB->request($query) as $data) {
         //Add group 
         $this->addTarget($data["id"], $LANG['plugin_arsurveys']['targets']['tech_assigned_in_group']. " " .$data["name"], self::ARSURVEY_ITEM_TECH_IN_CHARGE_IN_GROUP);
         $this->addTarget($data["id"], $LANG['plugin_arsurveys']['targets']['manager_tech_assigned_in_group']. " " .$data["name"], self::ARSURVEY_MANAGER_TECH_IN_CHARGE_IN_GROUP);
      }

   }

   /**
    * Display notification targets
    *
    * @param $notification the Notification object
   **/
   function showNotificationTargets(Notification $notification) {
      global $LANG, $DB;

      if ($notification->getField('itemtype') != '') {
         $notifications_id = $notification->fields['id'];
         $this->getNotificationTargets($_SESSION['glpiactive_entity']);

         $canedit = $notification->can($notifications_id,'w');

         $options = "";
         // Get User mailing
         $query = "SELECT `glpi_notificationtargets`.`items_id`,
                          `glpi_notificationtargets`.`id`
                   FROM `glpi_notificationtargets`
                   WHERE `glpi_notificationtargets`.`notifications_id` = '$notifications_id'
                         AND `glpi_notificationtargets`.`type` = '" . Notification::USER_TYPE . "'
                   ORDER BY `glpi_notificationtargets`.`items_id`";

         foreach ($DB->request($query) as $data) {
            if (isset($this->notification_targets[Notification::USER_TYPE."_".$data["items_id"]])) {
               unset($this->notification_targets[Notification::USER_TYPE."_".$data["items_id"]]);
            }

            if (isset($this->notification_targets_labels[Notification::USER_TYPE]
                                                        [$data["items_id"]])) {
               $name = $this->notification_targets_labels[Notification::USER_TYPE][$data["items_id"]];
            } else {
               $name = "&nbsp;";
            }
            $options .= "<option value='" . $data["id"] . "'>" . $name . "</option>";
         }

         // Get Profile mailing
         $query = "SELECT `glpi_notificationtargets`.`items_id`,
                          `glpi_notificationtargets`.`id`,
                          `glpi_profiles`.`name` AS `prof`
                   FROM `glpi_notificationtargets`
                   LEFT JOIN `glpi_profiles`
                        ON (`glpi_notificationtargets`.`items_id` = `glpi_profiles`.`id`)
                   WHERE `glpi_notificationtargets`.`notifications_id` = '$notifications_id'
                         AND `glpi_notificationtargets`.`type` = '" . Notification::PROFILE_TYPE . "'
                   ORDER BY `prof`";

         foreach ($DB->request($query) as $data) {
            $options .= "<option value='" . $data["id"] . "'>" . $LANG['profiles'][22] . " " .
                        $data["prof"] . "</option>";

            if (isset($this->notification_targets[Notification::PROFILE_TYPE."_".$data["items_id"]])) {
               unset($this->notification_targets[Notification::PROFILE_TYPE."_".$data["items_id"]]);
            }
         }

         // Get Group mailing
         $query = "SELECT `glpi_notificationtargets`.`items_id`,
                          `glpi_notificationtargets`.`id`,
                          `glpi_groups`.`name` AS `name`
                   FROM `glpi_notificationtargets`
                   LEFT JOIN `glpi_groups`
                        ON (`glpi_notificationtargets`.`items_id` = `glpi_groups`.`id`)
                   WHERE `glpi_notificationtargets`.`notifications_id`='$notifications_id'
                         AND `glpi_notificationtargets`.`type` = '" . Notification::GROUP_TYPE . "'
                   ORDER BY `name`;";

         foreach ($DB->request($query) as $data) {
            $options .= "<option value='" . $data["id"] . "'>" . $LANG['common'][35] . " " .
                        $data["name"] . "</option>";

            if (isset($this->notification_targets[Notification::GROUP_TYPE."_".$data["items_id"]])) {
               unset($this->notification_targets[Notification::GROUP_TYPE."_".$data["items_id"]]);
            }
         }

         // Get Group mailing
         $query = "SELECT `glpi_notificationtargets`.`items_id`,
                          `glpi_notificationtargets`.`id`,
                          `glpi_groups`.`name` AS `name`
                   FROM `glpi_notificationtargets`
                   LEFT JOIN `glpi_groups`
                        ON (`glpi_notificationtargets`.`items_id` = `glpi_groups`.`id`)
                   WHERE `glpi_notificationtargets`.`notifications_id`='$notifications_id'
                         AND `glpi_notificationtargets`.`type`
                                                         = '".Notification::SUPERVISOR_GROUP_TYPE."'
                   ORDER BY `name`;";

         foreach ($DB->request($query) as $data) {
            $options .= "<option value='" . $data["id"] . "'>" . $LANG['common'][64].' '.
                        $LANG['common'][35] . " " .$data["name"] . "</option>";

            if (isset($this->notification_targets[Notification::SUPERVISOR_GROUP_TYPE."_".
                                                  $data["items_id"]])) {

               unset($this->notification_targets[Notification::SUPERVISOR_GROUP_TYPE."_".
               $data["items_id"]]);
            }
         }

         // Get Special ARSurvey Group mailing
         $query = "SELECT `glpi_notificationtargets`.`items_id`,
                          `glpi_notificationtargets`.`id`,
                          `glpi_groups`.`name` AS `name`
                   FROM `glpi_notificationtargets`
                   LEFT JOIN `glpi_groups`
                        ON (`glpi_notificationtargets`.`items_id` = `glpi_groups`.`id`)
                   WHERE `glpi_notificationtargets`.`notifications_id`='$notifications_id'
                         AND `glpi_notificationtargets`.`type`
                                                         = '".self::ARSURVEY_ITEM_TECH_IN_CHARGE_IN_GROUP."'
                   ORDER BY `name`;";

         foreach ($DB->request($query) as $data) {
            $options .= "<option value='" . $data["id"] . "'>" . $LANG['plugin_arsurveys']['targets']['tech_assigned_in_group'] . " " .$data["name"] . "</option>";

            if (isset($this->notification_targets[self::ARSURVEY_ITEM_TECH_IN_CHARGE_IN_GROUP."_".
                                                  $data["items_id"]])) {

               unset($this->notification_targets[self::ARSURVEY_ITEM_TECH_IN_CHARGE_IN_GROUP."_".
               $data["items_id"]]);
            }
         }

         // Get Special ARSurvey Group mailing for group managers
         $query = "SELECT `glpi_notificationtargets`.`items_id`,
                          `glpi_notificationtargets`.`id`,
                          `glpi_groups`.`name` AS `name`
                   FROM `glpi_notificationtargets`
                   LEFT JOIN `glpi_groups`
                        ON (`glpi_notificationtargets`.`items_id` = `glpi_groups`.`id`)
                   WHERE `glpi_notificationtargets`.`notifications_id`='$notifications_id'
                         AND `glpi_notificationtargets`.`type`
                                                         = '".self::ARSURVEY_MANAGER_TECH_IN_CHARGE_IN_GROUP."'
                   ORDER BY `name`;";

         foreach ($DB->request($query) as $data) {
            $options .= "<option value='" . $data["id"] . "'>" . $LANG['plugin_arsurveys']['targets']['manager_tech_assigned_in_group'] . " " .$data["name"] . "</option>";

            if (isset($this->notification_targets[self::ARSURVEY_MANAGER_TECH_IN_CHARGE_IN_GROUP."_".
                                                  $data["items_id"]])) {

               unset($this->notification_targets[self::ARSURVEY_MANAGER_TECH_IN_CHARGE_IN_GROUP."_".
               $data["items_id"]]);
            }
         }

         if ($canedit) {
            echo "<td class='right'>";

            if (count($this->notification_targets)) {
               echo "<select name='mailing_to_add[]' multiple size='5'>";

               foreach ($this->notification_targets as $key => $val) {
                  list ($type, $items_id) = explode("_", $key);
                  echo "<option value='$key'>".$this->notification_targets_labels[$type][$items_id].
                  "</option>";
               }

               echo "</select>";
            }

            echo "</td><td class='center'>";

            if (count($this->notification_targets)) {
               echo "<input type='submit' class='submit' name='mailing_add' value='".
               $LANG['buttons'][8]." >>'>";
            }
            echo "<br><br>";

            if (!empty($options)) {
               echo "<input type='submit' class='submit' name='mailing_delete' value='<< ".
               $LANG['buttons'][6]."'>";
            }
            echo "</td><td>";

         } else {
            echo "<td class='center'>";
         }

         if (!empty($options)) {
            echo "<select name='mailing_to_delete[]' multiple size='5'>";
            echo $options ."</select>";
         } else {
            echo "&nbsp;";
         }
         echo "</td>";
      }
   }
}
