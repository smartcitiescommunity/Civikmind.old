<?php


/**
 * Summary of plugin_timezones_install
 * @return true or die!
 */
function plugin_timezones_install() {
    global $DB;

   if (!TableExists("glpi_plugin_timezones_users")) {
      $query = "  CREATE TABLE `glpi_plugin_timezones_users` (
	                    `id` INT(11) NOT NULL AUTO_INCREMENT,
	                    `users_id` INT(11) NOT NULL,
	                    `timezone` VARCHAR(50) NOT NULL,
	                    PRIMARY KEY (`id`),
	                    UNIQUE INDEX `users_id` (`users_id`),
	                    INDEX `timezone` (`timezone`)
                    )
                    COLLATE='utf8_general_ci'
                    ENGINE=InnoDB
                    ;
			";

      $DB->query($query) or die("error creating glpi_plugin_timezones_users" . $DB->error());

   } else if (!FieldExists("glpi_plugin_timezones_users", "users_id")) {
       $query = "  ALTER TABLE `glpi_plugin_timezones_users`
	                    ADD COLUMN `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	                    CHANGE COLUMN `id` `users_id` INT(11) NOT NULL AFTER `id`,
	                    DROP PRIMARY KEY,
	                    ADD PRIMARY KEY (`id`),
	                    ADD UNIQUE INDEX `users_id` (`users_id`);
                ";

      $DB->query($query) or die("error altering glpi_plugin_timezones_users" . $DB->error());

   }

   if (!TableExists("glpi_plugin_timezones_dbbackups")) {
        $query = "  CREATE TABLE `glpi_plugin_timezones_dbbackups` (
	                `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	                `table_name` VARCHAR(255) NULL ,
	                `alter_table` TEXT NULL
                )
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;
			";

        $DB->query($query) or die("error creating glpi_plugin_timezones_dbbackups" . $DB->error());
   }

   if (!TableExists("glpi_plugin_timezones_tasks_localtimes")) {
        $query = " CREATE TABLE `glpi_plugin_timezones_tasks_localtimes` (
	                    `id` INT(11) NOT NULL AUTO_INCREMENT,
	                    `items_type` VARCHAR(50) NOT NULL,
	                    `items_id` INT(11) NOT NULL,
	                    `begin` VARCHAR(20) NULL DEFAULT NULL COMMENT 'In order to keep local time',
	                    `end` VARCHAR(20) NULL DEFAULT NULL COMMENT 'In order to keep local time',
	                    `tz_name` VARCHAR(64) NULL,
                       PRIMARY KEY (`id`),
	                    UNIQUE INDEX `items_type_items_id` (`items_type`, `items_id`)
                    )
                    COLLATE='utf8_general_ci'
                    ENGINE=InnoDB
                    ;
			";

        $DB->query($query) or die("error creating glpi_plugin_timezones_tasks_localtimes" . $DB->error());

   } else if (!FieldExists("glpi_plugin_timezones_tasks_localtimes", "tz_name")) {
      $query = "  ALTER TABLE `glpi_plugin_timezones_tasks_localtimes`
	                  ADD COLUMN `tz_name` VARCHAR(64) NULL AFTER `end`;
                ";

      $DB->query($query) or die("error adding 'tz_name' into glpi_plugin_timezones_tasks_localtimes" . $DB->error());

   }

   // last but not least, must convert any DATETIME field into TIMESTAMP
   convertDB();

   return true;
}

/**
 * Summary of convertDB
 * @param mixed $echo
 * @return boolean true if success, else will die
 */
function convertDB($echo=false){
   global $DB;

   $now = date('Y-m-d H:i:s' );

   // we are going to update datetime, date and time (?) types to timestamp type
   $query = "SELECT DISTINCT( `INFORMATION_SCHEMA`.`COLUMNS`.`TABLE_NAME` ), TABLE_TYPE from `INFORMATION_SCHEMA`.`COLUMNS`
               JOIN `INFORMATION_SCHEMA`.`TABLES` ON `INFORMATION_SCHEMA`.`TABLES`.`TABLE_NAME` = `INFORMATION_SCHEMA`.`COLUMNS`.`TABLE_NAME` AND `INFORMATION_SCHEMA`.`TABLES`.`TABLE_TYPE` = 'BASE TABLE'
               WHERE `INFORMATION_SCHEMA`.`COLUMNS`.TABLE_SCHEMA = '".$DB->dbdefault."' AND `INFORMATION_SCHEMA`.`COLUMNS`.`COLUMN_TYPE` IN ('DATETIME') ; ";

   foreach ($DB->request($query) as $table) {
      $tablealter = $tablebackup = ''; // init by default

      // get table create code to get accurate table definition for backup purpose
      $query="SHOW CREATE TABLE `".$table['TABLE_NAME']."`;";
      $res = $DB->query( $query );
      $tabledef = $DB->fetch_assoc( $res );
      $tablelines = explode("\n", $tabledef['Create Table']);
      foreach ($tablelines as $line) {
         if (stripos( $line, " datetime " ) !== false) {
            // then we must backup this line
            $tablebackup .= "\n MODIFY ".$line;
         }
      }

      // get accurate info from information_schema to perform correct alter
      $query = "SELECT * from `INFORMATION_SCHEMA`.`COLUMNS` WHERE TABLE_SCHEMA = '".$DB->dbdefault."' AND TABLE_NAME LIKE '".$table['TABLE_NAME']."' AND COLUMN_TYPE IN ('DATETIME'); ";
      foreach ($DB->request($query) as $column) {
         $defaultalter = $commentalter = '';
         // we have all columns representing temporal values that we want to change to TIMESTAMP
         if ($column['IS_NULLABLE']=='YES') {
            $nullable = "NULL";
         } else {
            $nullable = "NOT NULL";
         }
         if (is_null($column['COLUMN_DEFAULT']) && $column['IS_NULLABLE']=='NO') { // no default
            $defaultalter=" DEFAULT '0000-00-00 00:00:00'";
         } else if (is_null($column['COLUMN_DEFAULT']) && $column['IS_NULLABLE']=='YES') {
            $defaultalter = " DEFAULT NULL";
         } else if (!is_null($column['COLUMN_DEFAULT'])) {
            if ($column['COLUMN_DEFAULT'] == '0000-00-00 00:00:00') {
               $defaultalter = " DEFAULT '0000-00-00 00:00:00'";
            } else if ($column['COLUMN_DEFAULT'] < '1970-01-01 00:00:01') {
               $defaultDate = new DateTime( '1970-01-01 00:00:01', new DateTimeZone( 'UTC' ) );
               $defaultDate->setTimezone( new DateTimeZone( date_default_timezone_get() ) );
               $defaultalter = " DEFAULT '".$defaultDate->format("Y-m-d H:i:s")."'";
            } else if ($column['COLUMN_DEFAULT'] > '2038-01-19 03:14:07') {
               $defaultDate = new DateTime( '2038-01-19 03:14:07', new DateTimeZone( 'UTC' ) );
               $defaultDate->setTimezone( new DateTimeZone( date_default_timezone_get() ) );
               $defaultalter = " DEFAULT '".$defaultDate->format("Y-m-d H:i:s")."'";
            } else {
               $defaultalter = " DEFAULT '".$column['COLUMN_DEFAULT']."'";
            }
         }
         if ($column['COLUMN_COMMENT'] != '') {
            $commentalter = " COMMENT '".$column['COLUMN_COMMENT']."'";
         }
         $tablealter .= "\n MODIFY COLUMN `".$column['COLUMN_NAME']."` TIMESTAMP $nullable $defaultalter $commentalter,";
      }

      // must delete last ',' from $tablebackup and $tablealter if we have one
      // create backup of the column definitions so that we may apply them to restore database when uninstalling plugin
      $tablebackup = $DB->dbh->real_escape_string( rtrim( $tablebackup, "," ) );
      $tablealter =  rtrim( $tablealter, "," );

      // special case for glpi_*tasks tables for objects like TicketTask, ProblemTask, ChangeTask
      // we must first copy local times from 'glpi_tickettasks' and 'glpi_problemtasks' tables to glpi_plugin_timezones_tasks_localtimes
      if ($table['TABLE_NAME'] == 'glpi_tickettasks') {

         // begin drafts
         //INSERT INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'TicketTask' as items_type, id as items_id, `begin`, `end`
         //FROM glpi_tickettasks
         //WHERE glpi_tickettasks.id NOT IN (SELECT glpi_plugin_timezones_tasks_localtimes.items_id FROM glpi_plugin_timezones_tasks_localtimes WHERE glpi_plugin_timezones_tasks_localtimes.items_type='TicketTask') ;

         //SELECT NULL, tickets_id, 'TicketTask' as items_type, glpi_tickettasks.id as items_id, `begin`, `end`, CONVERT_TZ(`begin`, 'Europe/Paris', glpi_plugin_timezones_users.timezone) as 'begin-conv', glpi_plugin_timezones_users.timezone FROM glpi_tickettasks
         //left join glpi_plugin_timezones_users on glpi_plugin_timezones_users.users_id=users_id_tech;
         // end drafts

         $query = "INSERT INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'TicketTask' as items_type, id as items_id, `begin`, `end` FROM glpi_tickettasks;";
         $DB->query( $query );
      }
      if ($table['TABLE_NAME'] == 'glpi_problemtasks') {
         $query = "INSERT INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'ProblemTask' as items_type, id as items_id, `begin`, `end` FROM glpi_problemtasks;";
         $DB->query( $query );
      }
      if ($table['TABLE_NAME'] == 'glpi_changetasks') {
         $query = "INSERT INTO glpi_plugin_timezones_tasks_localtimes SELECT NULL, 'ChangeTask' as items_type, id as items_id, `begin`, `end` FROM glpi_changetasks;";
         $DB->query( $query );
      }


      // apply alter to table
      $query ="ALTER TABLE  `".$table['TABLE_NAME']."` ".$tablealter.";";
      if( $echo ) {
         echo $query;
      }
      $DB->query( $query ) or die( " --> error when applying ". $DB->error()."\n");

      $query = "INSERT INTO `glpi_plugin_timezones_dbbackups` ( `date`, `table_name`, `alter_table`) VALUES ( '$now', '".$table['TABLE_NAME']."', 'ALTER TABLE  `".$table['TABLE_NAME']."` $tablebackup' );";
      $DB->query( $query ) or die( ' --> error when backing up '.$DB->error()."\n");

      if( $echo ) {
         echo " --> done\n";
      }
   }
   return true ;
}


function plugin_timezones_uninstall() {
    global $DB;

    return true;
}


function plugin_init_session_timezones() {
   if (!isset($_SESSION["glpicronuserrunning"]) || (Session::getLoginUserID() != $_SESSION["glpicronuserrunning"])) {
      $pref = new PluginTimezonesUser;
      $tzid = $pref->getIDFromUserID( Session::getLoginUserID() );

      if ($tzid && $pref->getFromDB( $tzid )) {
         setTimeZone( $pref->fields['timezone'] );
      } else {
         setTimeZone( @date_default_timezone_get() );
      }
   }
}

/**
 * Summary of setTimeZone
 * @param string $tz timezone to be set like 'Europe/Paris'
 */
function setTimeZone( $tz ) {
    global $DB;
    $_SESSION['glpitimezone'] = $tz; // could be redondant, but anyway :)
    date_default_timezone_set( $tz ) or Toolbox::logInFile("php-errors", "Can't set tz: $tz for ".Session::getLoginUserID()."\n");
    $DB->query("SET SESSION time_zone = '$tz'" ) or Toolbox::logInFile("php-errors", "Can't set tz: $tz - ". $DB->error()."\n"); //die ("Can't set tz: ". $DB->error());
    $_SESSION['glpi_currenttime'] = date("Y-m-d H:i:s");
}

function plugin_timezones_postinit( ) {
   if (isset($_SESSION['glpitimezone'])) {
      setTimeZone( $_SESSION['glpitimezone'] );
      $formerHandler = set_error_handler(array('PluginTimezonesToolbox', 'userErrorHandlerNormal'));
   }
}


function plugin_item_add_update_timezones_tasks(CommonDBTM $parm) {
    global $DB;
    if($parm instanceof CommonITILTask){
       $itemType = $parm->getType();
       $begin = (isset($parm->fields['begin'])?$parm->fields['begin']:'');
       $end = (isset($parm->fields['end'])?$parm->fields['end']:'');

       if( isset($_SESSION['glpitimezone']) ){
          $tz = $_SESSION['glpitimezone'] ;
       } else {
          // a cron is running
          // then use default timezone
          $tz = @date_default_timezone_get();
       }
       $query = "REPLACE INTO `glpi_plugin_timezones_tasks_localtimes` (`items_type`, `items_id`, `begin`, `end`, `tz_name`) VALUES ('$itemType', ".$parm->getID().", '$begin', '$end', '$tz');";
       $DB->query( $query );
    }

}


function plugin_item_add_update_timezones_dbconnection(Config $parm) {
    $slaveDB = DBConnection::getDBSlaveConf( );
   if ($slaveDB) {
      $host = $slaveDB->dbhost;
      $user = $slaveDB->dbuser;
      $password = $slaveDB->dbpassword;
      $DBname = $slaveDB->dbdefault;
      unset( $slaveDB  );
      timezones_createSlaveConnectionFile($host, $user, $password, $DBname) or Toolbox::logInFile('php-errors', "timezones: Can't create config_db_slave.php\n");
   }

}

/**
    * Create slave DB configuration file
    *
    * @param host the slave DB host(s)
    * @param user the slave DB user
    * @param password the slave DB password
    * @param DBname the name of the slave DB
    *
    * @return boolean for success
   **/
function timezones_createSlaveConnectionFile($host, $user, $password, $DBname) {

   $DB_str = "<?php \n class DBSlave extends DBmysql { \n var \$slave = true; \n var \$dbhost = ";
   $host   = trim($host);
   if (strpos($host, ' ')) {
      $hosts = explode(' ', $host);
      $first = true;
      foreach ($hosts as $host) {
         if (!empty($host)) {
            $DB_str .= ($first ? "array('" : ",'").$host."'";
            $first   = false;
         }
      }
      if ($first) {
         // no host configured
         return false;
      }
      $DB_str .= ");\n";

   } else {
      $DB_str .= "'$host';\n";
   }
   $DB_str .= " var \$dbuser = '" . $user . "'; \n var \$dbpassword= '" .rawurlencode($password) . "'; \n var \$dbdefault = '" . $DBname . "';
    function __construct(\$choice=NULL) {
        global \$DB;
        parent::connect(\$choice);
        if (\$this->connected && isset(\$_SESSION['glpitimezone']) ) {
            \$dbInit = isset( \$DB ) ;
            if( !\$dbInit ) {
                \$DB=\$this;
            }
            \$plug = new Plugin;
            if( \$plug->isActivated('timezones' ) ) {
                \$tz = \$_SESSION['glpitimezone'] ;
                \$this->query(\"SET SESSION time_zone = '\$tz'\" ) or Toolbox::logInFile(\"php-errors\", \"Can't set tz: \$tz - \". \$this->error().\"\\n\");
            }
            if( !\$dbInit ) {
                unset(\$DB) ;
            }
        }
    }
} \n ?>";
   $fp      = fopen(GLPI_CONFIG_DIR . "/config_db_slave.php", 'wt');
   if ($fp) {
      $fw = fwrite($fp, $DB_str);
      fclose($fp);
      return true;
   }
   return false;
}

function plugin_timezones_getAddSearchOptions( $itemtype ) {
   global $LANG;

   $sopt = array();
   if ($itemtype == 'User') {
       $sopt[11001]['table']     = 'glpi_plugin_timezones_users';
       $sopt[11001]['field']     = 'timezone';
       $sopt[11001]['linkfield'] = 'plugin_timezones_users_timezone';
       $sopt[11001]['massiveaction'] = true;
       $sopt[11001]['name']      = $LANG['timezones']['item']['tab'];
       $sopt[11001]['datatype']       = 'dropdown';
       $sopt[11001]['forcegroupby'] = true;
       $sopt[11001]['joinparams'] = array('jointype' => 'child');
       $sopt[11001]['searchtype']    = 'contains';

   }
   return $sopt;
}

   //function plugin_timezones_addLeftJoin($type,$ref_table,$new_table,$linkfield,&$already_link_tables) {

   //    switch ($type){

   //        case 'User':
   //            switch ($new_table){

   //                case "glpi_plugin_timezones_users" :
   //                    $out= " LEFT JOIN `glpi_plugin_timezones_users`
   //                     ON (`$ref_table`.`id` = `glpi_plugin_timezones_users`.`id` ) ";
   //                    return $out;
   //            }

   //            return "";
   //    }

   //    return "";
   //}

   //function plugin_pre_item_update_timezones_user(CommonDBTM $parm){
   //    global $DB;

   //    if($parm->getType() == 'User' && isset( $parm->input['plugin_timezones_users_timezone']) ) {
   //        $query = "REPLACE INTO `glpi_plugin_timezones_users` (`users_id`, `timezone`) VALUES (".$parm->getID().", '".$parm->input['plugin_timezones_users_timezone']."');";
   //        $DB->query( $query ) ;
   //    }
   //}

function plugin_timezones_MassiveActionsFieldsDisplay($options=array()) {
   //$type,$table,$field,$linkfield

   $table     = $options['options']['table'];
   $field     = $options['options']['field'];
   $linkfield = $options['options']['linkfield'];

   if ($options['itemtype'] == 'User') {
       // Table fields
      switch ($table.".".$field) {
         case 'glpi_plugin_timezones_users.timezone' :
            $timezones = PluginTimezonesUser::getTimezones( );
             // default timezone is the one of PHP
            Dropdown::showFromArray('plugin_timezones_users_timezone', $timezones, array('value' => ini_get('date.timezone') ));
            // Need to return true if specific display
             return true;
      }

   }

   // Need to return false on non display item
   return false;
}

    //function plugin_timezones_MassiveActionsProcess($data) {
    //    global $LANG, $DB;
    //    switch ($data['action']) {

    //        case "plugin_timezones_users_timezone" :
    //            if ($data['itemtype'] == 'User') {
    //                foreach ($data["item"] as $key => $val) {
    //                    if ($val == 1) {
    //                        $tzUser = new PluginTimezonesUser ;
    //                        $tzUser->getFromDB( $key ) ;




    //                    }
    //                }
    //            }
    //            break;
    //    }
    //    return ;

    //}

    //function plugin_timezones_MassiveActions($type) {
    //    global $LANG;

    //    switch ($type) {
    //        case 'User' :
    //            return array('plugin_timezones_users_timezone' => 'Update Time Zone');
    //    }

    //    return array();
    //}

    //function plugin_timezones_MassiveActionsDisplay($options) {
    //    global $LANG;

    //    switch ($options['itemtype']) {
    //        case 'User' :
    //            switch ($options['action']) {

    //                case "plugin_timezones_users_timezone" :
    //                    $timezones = PluginTimezonesUser::getTimezones( ) ;
    //                    // default timezone is the one of PHP
    //                    Dropdown::showFromArray('timezone', $timezones, array('value' => ini_get('date.timezone')
    //                                                                             ));
    //                    echo "&nbsp;<input type='submit' name='massiveaction' class='submit' ".
    //                    "value='".$LANG['buttons'][2]."'>";
    //                    break;

    //            }
    //            break;

    //    }

    //    return "";
    //}

