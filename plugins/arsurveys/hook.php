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

// Install process for plugin : need to return true if succeeded
function plugin_arsurveys_install() {
   global $DB ;

   if( !TableExists('glpi_plugin_arsurveys_configs') ) {
      $query = "CREATE TABLE `glpi_plugin_arsurveys_configs` (
	         `id` INT(11) NOT NULL AUTO_INCREMENT,
	         `bad_threshold` INT(11) NOT NULL ,
	         `good_threshold` INT(11) NOT NULL ,
	         `force_positive_notif` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'to send positive notification even if user comment is empty',
            `date_mod` TIMESTAMP NULL DEFAULT NULL,
	         `comment` TEXT NULL,
	         PRIMARY KEY (`id`)
         )
         ENGINE=InnoDB         
         ;

         ";

      $DB->query( $query ) or die("Can't create 'glpi_plugin_arsurveys_configs' table") ;

      // insert default configuration
      $query = "INSERT INTO `glpi_plugin_arsurveys_configs` 
            (`bad_threshold`, `good_threshold`, `date_mod`, `comment`) 
            VALUES 
            (2, 4, NOW(), 'These are by default for all ARSurvey notifications, but may be redefined on a per Notification basis.'); " ;      
      $DB->query( $query ) or die("Can't insert configuration in 'glpi_plugin_arsurveys_configs' table") ;

      // notification template
      $query = "SELECT *
             FROM `glpi_notificationtemplates`
             WHERE `itemtype` = 'PluginArsurveysTicketSatisfaction'";

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) == 0) {
            // insert default notification template
            $query = "INSERT INTO `glpi_notificationtemplates` 
                  (`name`, `itemtype`, `date_mod`) 
                  VALUES 
                  ('Ticket Survey Monitor', 'PluginArsurveysTicketSatisfaction', NOW());" ;

            $DB->query( $query ) or die("Can't insert 'Ticket Survey Monitor' notification template in 'glpi_notificationtemplates' table") ;
            
            $notiftemplateid = $DB->insert_id() ;
            
            // insert default notififaction template translation
            $query = "INSERT INTO `glpi_notificationtemplatetranslations` 
                  (`notificationtemplates_id`, `language`, `subject`, `content_text`, `content_html`) 
                  VALUES
                  ($notiftemplateid,
                     '', 
                     'Ticket ###ticketsatisfaction.ticket## - ##ticketsatisfaction.action##', 
                     '##lang.ticketsatisfaction.action##: ##ticketsatisfaction.action##

##lang.ticketsatisfaction.user##: ##ticketsatisfaction.user##

##lang.ticketsatisfaction.ticketentity##: ##ticketsatisfaction.ticketentity##

##lang.ticketsatisfaction.ticket##: ##ticketsatisfaction.ticket##

##lang.ticketsatisfaction.requesters##: ##ticketsatisfaction.requesters##

##lang.ticketsatisfaction.ticketname##: ##ticketsatisfaction.ticketname##

##lang.ticketsatisfaction.url##: ##ticketsatisfaction.url##

##lang.ticketsatisfaction.date_begin##: ##ticketsatisfaction.date_begin##

##lang.ticketsatisfaction.date_answer##: ##ticketsatisfaction.date_answer##

##lang.ticketsatisfaction.satisfaction##: ##ticketsatisfaction.satisfaction##

##lang.ticketsatisfaction.comment##: ##ticketsatisfaction.comment##

##lang.ticketsatisfaction.assigntousers##: ##ticketsatisfaction.assigntousers##

##lang.ticketsatisfaction.assigntogroups##: ##ticketsatisfaction.assigntogroups##',
                     '&lt;table&gt;
                     &lt;tbody&gt;
                     &lt;tr&gt;&lt;th colspan=\"2\"&gt;##lang.ticketsatisfaction.action##: ##ticketsatisfaction.action##&lt;/th&gt;&lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.user##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.user##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.ticket##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.ticket##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.ticketentity##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.ticketentity##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.requesters##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.requesters##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.ticketname##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.ticketname##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.url##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.url##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.date_begin##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.date_begin##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.date_answer##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.date_answer##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.satisfaction##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.satisfaction##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.comment##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.comment##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.assigntousers##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.assigntousers##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;tr&gt;
                     &lt;td&gt;##lang.ticketsatisfaction.assigntogroups##&lt;/td&gt;
                     &lt;td&gt;##ticketsatisfaction.assigntogroups##&lt;/td&gt;
                     &lt;/tr&gt;
                     &lt;/tbody&gt;
                     &lt;/table&gt;'
                  );";

            $DB->query( $query ) or die( "Add notification template translation in 'glpi_notificationtemplatetranslations' table");

            // add notifications one for negative survey results and one for positive survey results
            $query = "INSERT INTO `glpi_notifications` 
                     (`name`, `entities_id`, `itemtype`, `event`, `mode`, `notificationtemplates_id`, `comment`, `is_recursive`, `is_active`, `date_mod`) 
                     VALUES ('Negative Survey Results', 0, 'PluginArsurveysTicketSatisfaction', 'bad_survey', 'mail', $notiftemplateid, '', 1, 1, NOW());";
            $DB->query( $query ) or die("Add Negative Survey Result notification in 'glpi_notifications' table");

            $notifid = $DB->insert_id() ;
            $query = "INSERT INTO `glpi_notificationtargets` (`items_id`, `type`, `notifications_id`) VALUES (10, 1, $notifid);";
            $DB->query( $query ) or die("Add Negative Survey Result notification target in 'glpi_notificationtargets' table");

            $query = "INSERT INTO `glpi_notifications`
                     (`name`, `entities_id`, `itemtype`, `event`, `mode`, `notificationtemplates_id`, `comment`, `is_recursive`, `is_active`, `date_mod`)
                     VALUES ('Positive Survey Results', 0, 'PluginArsurveysTicketSatisfaction', 'good_survey', 'mail', $notiftemplateid, '', 1, 1, NOW());";
            $DB->query( $query ) or die("Add Positive Survey Result notification in 'glpi_notifications' table");

            $notifid = $DB->insert_id() ;
            $query = "INSERT INTO `glpi_notificationtargets` (`items_id`, `type`, `notifications_id`) VALUES (10, 1, $notifid);";
            $DB->query( $query ) or die("Add Positive Survey Result notification target in 'glpi_notificationtargets' table");

         }
      }

   } else {
      // table is already existing
      // must test for missing fields
      if( !FieldExists('glpi_plugin_arsurveys_configs', 'force_positive_notif')){
         $query = "ALTER TABLE `glpi_plugin_arsurveys_configs`
                  	ADD COLUMN `force_positive_notif` TINYINT(1) NOT NULL DEFAULT '1' COMMENT 'to force positive notification even if user comment is empty' 
                     AFTER `good_threshold`;";
         $DB->query( $query ) or die("Add 'force_positive_notif' field in 'glpi_plugin_arsurveys_configs' table");

      }
   }

   if( !TableExists('glpi_plugin_arsurveys_notifications') ) {
      $query = "CREATE TABLE `glpi_plugin_arsurveys_notifications` (
	         `id` INT(11) NOT NULL AUTO_INCREMENT,
	         `notifications_id` INT(11) NOT NULL,
	         `threshold` INT(11) NULL DEFAULT NULL,
	         `force_positive_notif` TINYINT(1) NULL DEFAULT NULL COMMENT 'to send positive notification even if user comment is empty',
            PRIMARY KEY (`id`),
	         UNIQUE INDEX `notifications_id` (`notifications_id`)
         )
         ENGINE=InnoDB
         ;

         ";
      $DB->query( $query ) or die("Can't insert configuration in 'glpi_plugin_arsurveys_notifications' table") ;

   } else {
      // table is already existing
      // must test for missing fields
      if( !FieldExists('glpi_plugin_arsurveys_notifications', 'force_positive_notif')){
         $query = "ALTER TABLE `glpi_plugin_arsurveys_notifications`
	                  ADD COLUMN `force_positive_notif` TINYINT(1) NULL DEFAULT NULL COMMENT 'to send positive notification even if user comment is empty' 
                     AFTER `threshold`;
                  ";
         $DB->query( $query ) or die("Add 'force_positive_notif' field in 'glpi_plugin_arsurveys_notifications' table");         
      }
   }

   return true;
}


// Uninstall process for plugin : need to return true if succeeded
function plugin_arsurveys_uninstall() {

    return true;
}


