<?php

// Ensure current directory as run command prompt
chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

define('DO_NOT_CHECK_HTTP_REFERER', 1);
include ( "../../../inc/includes.php");

$plug = new Plugin;
if ($plug->isInstalled('timezones')) {
   // $now = date('Y-m-d H:i:s' );
   // // we are going to update datetime, date and time (?) types to timestamp type
   // $query = "SELECT DISTINCT( `INFORMATION_SCHEMA`.`COLUMNS`.`TABLE_NAME` ), TABLE_TYPE from `INFORMATION_SCHEMA`.`COLUMNS`
   //            JOIN `INFORMATION_SCHEMA`.`TABLES` ON `INFORMATION_SCHEMA`.`TABLES`.`TABLE_NAME` = `INFORMATION_SCHEMA`.`COLUMNS`.`TABLE_NAME` AND `INFORMATION_SCHEMA`.`TABLES`.`TABLE_TYPE` = 'BASE TABLE'
   //            WHERE `INFORMATION_SCHEMA`.`COLUMNS`.TABLE_SCHEMA = '".$DB->dbdefault."' AND `INFORMATION_SCHEMA`.`COLUMNS`.`COLUMN_TYPE` IN ('DATETIME') ; ";
   //foreach ($DB->request($query) as $table) {
   //   $tablealter = $tablebackup = ''; // init by default

   //   // get table create code to get accurate table definition for backup purpose
   //   $query="SHOW CREATE TABLE `".$table['TABLE_NAME']."`;";
   //   $res = $DB->query( $query );
   //   $tabledef = $DB->fetch_assoc( $res );
   //   $tablelines = explode("\n", $tabledef['Create Table']);
   //   foreach ($tablelines as $line) {
   //      if (stripos( $line, " datetime " ) !== false) {
   //         // then we must backup this line
   //         $tablebackup .= "\n MODIFY ".$line;
   //      }
   //   }

   //   // get accurate info from information_schema to perform correct alter
   //   $query = "SELECT * from `INFORMATION_SCHEMA`.`COLUMNS` WHERE TABLE_SCHEMA = '".$DB->dbdefault."' AND TABLE_NAME LIKE '".$table['TABLE_NAME']."' AND COLUMN_TYPE IN ('DATETIME'); "; //, 'DATE');" ; //, 'TIME', 'YEAR');" ;
   //   foreach ($DB->request($query) as $column) {
   //      $defaultalter = $commentalter = '';
   //      // we have all columns representing temporal values that we want to change to TIMESTAMP
   //      if ($column['IS_NULLABLE']=='YES') {
   //         $nullable = "NULL";
   //      } else {
   //         $nullable = "NOT NULL";
   //      }
   //      if (is_null($column['COLUMN_DEFAULT']) && $column['IS_NULLABLE']=='NO') { // no default
   //         $defaultalter=" DEFAULT '0000-00-00 00:00:00'";
   //      } else if (is_null($column['COLUMN_DEFAULT']) && $column['IS_NULLABLE']=='YES') {
   //         $defaultalter = " DEFAULT NULL";
   //      } else if (!is_null($column['COLUMN_DEFAULT'])) {
   //         if ($column['COLUMN_DEFAULT'] == '0000-00-00 00:00:00') {
   //            $defaultalter = " DEFAULT '0000-00-00 00:00:00'";
   //         } else if ($column['COLUMN_DEFAULT'] < '1970-01-01 00:00:01') {
   //            $defaultDate = new DateTime( '1970-01-01 00:00:01', new DateTimeZone( 'UTC' ) );
   //            $defaultDate->setTimezone( new DateTimeZone( date_default_timezone_get() ) );
   //            $defaultalter = " DEFAULT '".$defaultDate->format("Y-m-d H:i:s")."'";
   //         } else if ($column['COLUMN_DEFAULT'] > '2038-01-19 03:14:07') {
   //            $defaultDate = new DateTime( '2038-01-19 03:14:07', new DateTimeZone( 'UTC' ) );
   //            $defaultDate->setTimezone( new DateTimeZone( date_default_timezone_get() ) );
   //            $defaultalter = " DEFAULT '".$defaultDate->format("Y-m-d H:i:s")."'";
   //         } else {
   //            $defaultalter = " DEFAULT '".$column['COLUMN_DEFAULT']."'";
   //         }
   //      }
   //      if ($column['COLUMN_COMMENT'] != '') {
   //         $commentalter = " COMMENT '".$column['COLUMN_COMMENT']."'";
   //      }
   //      $tablealter .= "\n MODIFY COLUMN `".$column['COLUMN_NAME']."` TIMESTAMP $nullable $defaultalter $commentalter,";
   //   }

   //   // must delete last ',' from $tablebackup and $tablealter if we have one
   //   // create backup of the column definitions so that we may apply them to restore database when uninstalling plugin
   //   $tablebackup = $DB->dbh->real_escape_string( rtrim( $tablebackup, "," ) );
   //   $tablealter =  rtrim( $tablealter, "," );

   //   // special case for glpi_*tasks tables for objects like TicketTask, ProblemTask, ChangeTask, ProjectTask
   //   // we must first copy local times from 'glpi_tickettasks' and 'glpi_problemtasks' tables to glpi_plugin_timezones_tasks_localtimes
   //   if ($table['TABLE_NAME'] == 'glpi_tickettasks') {
   //      $query = "REPLACE INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'TicketTask' as items_type, id as items_id, `begin`, `end` FROM glpi_tickettasks;";
   //      $DB->query( $query );
   //   }
   //   if ($table['TABLE_NAME'] == 'glpi_problemtasks') {
   //      $query = "REPLACE INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'ProblemTask' as items_type, id as items_id, `begin`, `end` FROM glpi_problemtasks;";
   //      $DB->query( $query );
   //   }
   //   if ($table['TABLE_NAME'] == 'glpi_changetasks') {
   //      $query = "REPLACE INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'ChangeTask' as items_type, id as items_id, `begin`, `end` FROM glpi_changetasks;";
   //      $DB->query( $query );
   //   }
   //   if ($table['TABLE_NAME'] == 'glpi_projecttasks') {
   //      $query = "REPLACE INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'ProjectTask' as items_type, id as items_id, `begin`, `end` FROM glpi_projecttasks;";
   //      $DB->query( $query );
   //   }

   //   // apply alter to table
   //   $query ="ALTER TABLE  `".$table['TABLE_NAME']."` ".$tablealter.";";
   //   echo $query;
   //   $DB->query( $query ) or die( " --> error when applying ". $DB->error()."\n");

   //   //echo "ALTER TABLE  `".$table['TABLE_NAME']."` $tablebackup" ;
   //   $query = "INSERT INTO `glpi_plugin_timezones_dbbackups` ( `date`, `table_name`, `alter_table`) VALUES ( '$now', '".$table['TABLE_NAME']."', 'ALTER TABLE  `".$table['TABLE_NAME']."` $tablebackup' );";
   //   $DB->query( $query ) or die( ' --> error when backing up '.$DB->error()."\n");

   //   echo " --> done\n";
   //}

   include_once( '../hook.php' ) ;
   convertDB(true) ;

} else {
   echo "Plugin 'Timezones' is not installed!\nPlease install it before applying script!\n";
}
