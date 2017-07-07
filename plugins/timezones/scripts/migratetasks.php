<?php


// Ensure current directory as run command prompt
chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

define('DO_NOT_CHECK_HTTP_REFERER', 1);
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

$plug = new Plugin;
if ($plug->isActivated('timezones')) {
    // as task dates (begin and end) have been input with local time zone
    // we must check for all TicketTasks if begin and end match the copy in glpi_plugin_timezones_tasks taking into account the local time zone
    // the local time zone is defined as the timezone of the writer (as the writer is also the last modifier of the task)
    // it has to be done only for 'on call' tasks

    // first we need to get the sons of Task Category 'on call' which has ID == 2 in order to build SQL query
    $query = "SELECT sons_cache from glpi_taskcategories where id=2;";
    $res = $DB->query( $query );
   if ($res && $DB->numrows( $res ) > 0) {
      $row = $DB->fetch_assoc( $res );
      $cat = importArrayFromDB($row['sons_cache']);

      // then build query to get 'On call' tasks
      $query = "SELECT * FROM glpi_tickettasks WHERE taskcategories_id IN ( ".implode(',', $cat)." );";
      //TODO
   }

    //$lcdate = new DateTime('2015-07-01 06:20:00', new DateTimeZone('America/Detroit'));
    //echo $lcdate->format("Y-m-d H:i:s")."<br>" ;
    //$lcdate->SetTimezone( new DateTimeZone('Europe/Paris' ) );
    //echo $lcdate->format("Y-m-d H:i:s") ;


}