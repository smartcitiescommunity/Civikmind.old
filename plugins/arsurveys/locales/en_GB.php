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

$LANG['plugin_arsurveys']["name"] = "AR Surveys";
$LANG['plugin_arsurveys']["notifconfig"] = "Set threshold";

$LANG['plugin_arsurveys']["ticketsatisfactiontype"] = "Ticket surveys";

$LANG['plugin_arsurveys']['config']['bad_threshold'] = "Negative Threshold: if satisfaction survey result is less than or equal (<=) to this value a notification will be triggered as a 'negative survey result'";
$LANG['plugin_arsurveys']['config']['good_threshold'] = "Positive Threshold: if satisfaction survey result is greater than or equal (=>) to this value a notification will be triggered as a 'positive survey result'";
$LANG['plugin_arsurveys']['config']['force_positive_notif'] = "'Positive Survey Result' notifications are not sent when user's comments to satisfaction survey is empty. Send them anyway?";
$LANG['plugin_arsurveys']['config']['comments'] = "Comments";
$LANG['plugin_arsurveys']['config']['datemod'] = "Last update" ;
$LANG['plugin_arsurveys']['config']['save'] = "Save" ;

$LANG['plugin_arsurveys']['bad_survey'] = 'Negative survey result' ;
$LANG['plugin_arsurveys']['good_survey'] = 'Positive survey result' ;

$LANG['plugin_arsurveys']['targets']['tech_assigned_in_group'] = 'Technician in charge of this ticket within Group' ;
$LANG['plugin_arsurveys']['targets']['manager_tech_assigned_in_group'] = 'Manager of Group for Technician in charge of this ticket within Group' ;


$LANG['plugin_arsurveys']['ticketsatisfaction.action'] = 'Survey answer' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.user'] = 'Survey author' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.ticket'] = 'Ticket number' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.ticketentity'] = 'Ticket entity' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.ticketname'] = 'Ticket Title' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.requesters'] = 'Ticket Requesters' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.url'] = 'Satisfaction URL' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.date_begin'] = 'Start date' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.date_answer'] = 'Answer date' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.satisfaction'] = 'Quality satisfaction' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.comment'] = 'Survey comment' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.friendliness'] = 'Friendliness satisfaction' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.responsetime'] = 'Responsetime satisfaction' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.assigntousers'] = 'Assigned To Technicians' ;
$LANG['plugin_arsurveys']['ticketsatisfaction.assigntogroups'] = 'Assigned To Groups' ;
