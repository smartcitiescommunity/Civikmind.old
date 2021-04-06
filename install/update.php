<?php
/**
 * --------------------------------------------------------------------- Civikmind
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2021 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', realpath('..'));
}

include_once (GLPI_ROOT . "/inc/based_config.php");
include_once (GLPI_ROOT . "/inc/db.function.php");
include_once (GLPI_CONFIG_DIR . "/config_db.php");

global $DB, $GLPI, $GLPI_CACHE;

$GLPI = new GLPI();
$GLPI->initLogger();
$GLPI->initErrorHandler();

$GLPI_CACHE = Config::getCache('cache_db');
$GLPI_CACHE->clear(); // Force cache cleaning to prevent usage of outdated cache data

$translation_cache = Config::getCache('cache_trans');
$translation_cache->clear(); // Force cache cleaning to prevent usage of outdated cache data

Config::detectRootDoc();

$DB = new DB();
$DB->disableTableCaching(); //prevents issues on fieldExists upgrading from old versions

Config::loadLegacyConfiguration();

$update = new Update($DB);
$update->initSession();

if (isset($_POST['update_end'])) {
   if (isset($_POST['send_stats'])) {
      Telemetry::enable();
   }
   header('Location: ../index.php');
}

/* ----------------------------------------------------------------- */

/*---------------------------------------------------------------------*/
/**
 * To be conserved to migrations before 0.80
 * since 0.80, migration is a new class
**/
function displayMigrationMessage ($id, $msg = "") {
   static $created = 0;
   static $deb;

   if ($created != $id) {
      if (empty($msg)) {
         $msg = __('Trabajo en proceso...');
      }
      echo "<div id='migration_message_$id'><p class='center'>$msg</p></div>";
      $created = $id;
      $deb     = time();

   } else {
      if (empty($msg)) {
         $msg = __('Tarea Completada.');
      }
      $fin = time();
      $tps = Html::timestampToString($fin-$deb);
      echo "<script type='text/javascript'>document.getElementById('migration_message_$id').innerHTML =
             '<p class=\"center\" >$msg ($tps)</p>';</script>\n";
   }
   Html::glpi_flush();
}


/**
 * Add a dropdown if not exists (used by pre 0.78 update script)
 * Only use for simple dropdown (no entity and not tree)
 *
 * @param $table string table name
 * @param $name string name of the imported dropdown
 *
 * @return integer (ID of the existing/new dropdown)
**/
function update_importDropdown ($table, $name) {
   global $DB;

   $query = "SELECT `ID`
             FROM `".$table."`
             WHERE `name` = '".addslashes($name)."'";

   if ($result = $DB->query($query)) {
      if ($DB->numrows($result) > 0) {
         return $DB->result($result, 0, "ID");
      }
   }
   $query = "INSERT INTO `".$table."`
             (`name`)
             VALUES ('".addslashes($name)."')";
   if ($result = $DB->query($query)) {
      return $DB->insertId();
   }
   return 0;
}


/**
 * Display the form of content update (addslashes compatibility (V0.4))
 *
 * @return void
 */
function showContentUpdateForm() {
   $_SESSION['do_content_update'] = true;
   echo "<form action='update_content.php' method='post'>";
   echo "<div class='center'>";
   echo "<h3>".__('Actualización exitosa, su base de datos está actualizada')."</h3>";
   echo "<p>".__('Ahora debe proceder a actualizar el contenido de su base de datos.')."</p></div>";
   echo "<p>";
   echo "<input type='submit' class='vsubmit' value='.__('Continuar?').'/>";
   echo "</form>";
}


///// FONCTION POUR UPDATE LOCATION

function validate_new_location() {
   global $DB;

   $query = " DROP TABLE `glpi_dropdown_locations`";
   $DB->query($query);

   $query = " ALTER TABLE `glpi_dropdown_locations_new`
              RENAME `glpi_dropdown_locations`";
   $DB->query($query);
}


function display_new_locations() {
   global $DB;

   $MAX_LEVEL  = 10;
   $SELECT_ALL = "";
   $FROM_ALL   = "";
   $ORDER_ALL  = "";

   for ($i=1; $i<=$MAX_LEVEL; $i++) {
      $SELECT_ALL .= " , location$i.`name` AS NAME$i
                       , location$i.`parentID` AS PARENT$i ";
      $FROM_ALL   .= " LEFT JOIN `glpi_dropdown_locations_new` AS location$i
                           ON location".($i-1).".`ID` = location$i.`parentID` ";
      $ORDER_ALL  .= " , NAME$i";
   }

   $query = "SELECT location0.`name` AS NAME0,
                    location0.`parentID` AS PARENT0
                    $SELECT_ALL
             FROM `glpi_dropdown_locations_new` AS location0
             $FROM_ALL
             WHERE location0.`parentID` = 0
             ORDER BY NAME0 $ORDER_ALL";
   $result = $DB->query($query);

   $data_old = [];
   echo "<table><tr>";

   for ($i=0; $i<=$MAX_LEVEL; $i++) {
      echo "<th>$i</th><th>&nbsp;</th>";
   }
   echo "</tr>";

   while ($data = $DB->fetchAssoc($result)) {
      echo "<tr class=tab_bg_1>";
      for ($i=0; $i<=$MAX_LEVEL; $i++) {

         if (!isset($data_old["NAME$i"])
             || ($data_old["PARENT$i"] != $data["PARENT$i"])
             || ($data_old["NAME$i"] != $data["NAME$i"])) {

            $name = $data["NAME$i"];

            if (isset($data["NAME".($i+1)]) && !empty($data["NAME".($i+1)])) {
               $arrow = "--->";
            } else {
               $arrow = "";
            }

         } else {
            $name  = "";
            $arrow = "";
         }

         echo "<td>".$name."</td>";
         echo "<td>$arrow</td>";
      }

      echo "</tr>";
      $data_old=$data;
   }

   $DB->freeResult($result);
   echo "</table>";
}


function display_old_locations() {
   global $DB;

   $query = "SELECT *
             FROM `glpi_dropdown_locations`
             ORDER BY `name`";
   $result = $DB->query($query);

   while ($data = $DB->fetchAssoc($result)) {
      echo "<span class='b'>".$data['name']."</span> - ";
   }

   $DB->freeResult($result);
}


function location_create_new($split_char, $add_first) {
   global $DB;

   $query_auto_inc = "ALTER TABLE `glpi_dropdown_locations_new`
                      CHANGE `ID` `ID` INT(11) NOT NULL";
   $result_auto_inc = $DB->query($query_auto_inc);

   $query = "SELECT MAX(`ID`) AS MAX
             FROM `glpi_dropdown_locations`";

   $result = $DB->query($query);
   $new_ID = $DB->result($result, 0, "MAX");
   $new_ID++;

   $query = "SELECT *
             FROM `glpi_dropdown_locations`";
   $result = $DB->query($query);

   $query_clear_new = "TRUNCATE TABLE `glpi_dropdown_locations_new`";
   $result_clear_new = $DB->query($query_clear_new);

   if (!empty($add_first)) {
      $root_ID = $new_ID;
      $new_ID++;
      $query_insert = "INSERT INTO `glpi_dropdown_locations_new`
                       VALUES ('$root_ID', '".addslashes($add_first)."', 0, '')";

      $result_insert = $DB->query($query_insert);

   } else {
      $root_ID = 0;
   }

   while ($data =  $DB->fetchAssoc($result)) {

      if (!empty($split_char)) {
         $splitter = explode($split_char, $data['name']);
      } else {
         $splitter = [$data['name']];
      }

      $up_ID = $root_ID;

      for ($i=0; $i<count($splitter)-1; $i++) {
         // Entree existe deja ??
         $query_search = "SELECT `ID`
                          FROM `glpi_dropdown_locations_new`
                          WHERE `name` = '".addslashes($splitter[$i])."'
                               AND `parentID` = '".$up_ID."'";
         $result_search = $DB->query($query_search);

         if ($DB->numrows($result_search)==1) { // Found
            $up_ID = $DB->result($result_search, 0, "ID");
         } else { // Not FOUND -> INSERT
            $query_insert = "INSERT INTO `glpi_dropdown_locations_new`
                             VALUES ('$new_ID', '".addslashes($splitter[$i])."', '$up_ID', '')";
            $result_insert = $DB->query($query_insert);

            $up_ID = $new_ID++;
         }

      }

      // Ajout du dernier
      $query_insert = "INSERT INTO `glpi_dropdown_locations_new`
                       VALUES ('".$data["ID"]."', '".addslashes($splitter[count($splitter)-1])."',
                               '$up_ID', '')";
      $result_insert=$DB->query($query_insert);
   }

   $DB->freeResult($result);
   $query_auto_inc = "ALTER TABLE `glpi_dropdown_locations_new`
                      CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT";
   $result_auto_inc = $DB->query($query_auto_inc);

}


///// FIN FONCTIONS POUR UPDATE LOCATION

function showLocationUpdateForm() {
   global $DB, $CFG_GLPI;

   if (($DB->tableExists ("glpi_dropdown_locations")
        && $DB->fieldExists("glpi_dropdown_locations", "parentID", false))
       || ($DB->tableExists ("glpi_locations") && $DB->fieldExists("glpi_locations", "locations_id", false))) {
      updateTreeDropdown();
      return true;
   }

   if (!isset($_POST['root'])) {
      $_POST['root'] = '';
   }

   if (!isset($_POST['car_sep'])) {
      $_POST['car_sep'] = '';
   }

   if (!$DB->tableExists("glpi_dropdown_locations_new")) {
      $query = " CREATE TABLE `glpi_dropdown_locations_new` (
                  `ID` INT NOT NULL auto_increment,
                  `name` VARCHAR(255) NOT NULL ,
                  `parentID` INT NOT NULL ,
                  `comments` TEXT NULL ,
                 PRIMARY KEY (`ID`),
                 UNIQUE KEY (`name`,`parentID`),
                 KEY(`parentID`)) TYPE=MyISAM";
      $DB->queryOrDie($query, "LOCATION");
   }

   if (!isset($_POST["validate_location"])) {
      echo "<div class='center'>";
      echo "<h4>".__('Actualización de ubicaciones')."</h4>";
      echo "<p>".__('La nueva estructura es jerárquica')."</p>";
      echo "<p>".__('Proporcionar un delimitador para automatizar la nueva generación de jerarquías.')."<br>";
      echo __('También puede especificar una ubicación raíz que incluirá todas las ubicaciones generadas.');
      echo "</p>";
      echo "<form action='".$CFG_GLPI["root_doc"]."/install/update.php' method='post'>";
      echo "<p>".__('Delimitador')."&nbsp;".
            "<input type='text' name='car_sep' value='".$_POST['car_sep']."'></p>";
      echo "<p>".__('Ubicación Raíz').'&nbsp;'.
            "<input type='text' name='root' value='".$_POST['root']."'></p>";
      echo "<input type='submit' class='submit' name='new_location' value=\""._sx('button', 'Enviar')."\">";
      echo "<input type='hidden' name='from_update' value='from_update'>";
      Html::closeForm();
      echo "</div>";
   }

   if (isset($_POST["new_location"])) {
      location_create_new($_POST['car_sep'], $_POST['root']);
      echo "<h4>".__('Ubicaciones Actuales')." : </h4>";
      display_old_locations();
      echo "<h4>".__('Nueva jerarquía')." : </h4>";
      display_new_locations();
      echo "<p>".__("Ésta es la Nueva jerarquía. Si está completo apruebe.")."</p>";
      echo "<div class='center'>";
      echo "<form action='".$CFG_GLPI["root_doc"]."/install/update.php' method='post'>";
      echo "<input type='submit' class='submit' name='validate_location' value=\"".
             _sx('button', 'Enviar')."\">";
      echo "<input type='hidden' name='from_update' value='from_update'>";
      echo "</div>";
      Html::closeForm();

   } else if (isset($_POST["validate_location"])) {
      validate_new_location();
      updateTreeDropdown();
      return true;

   } else {
      display_old_locations();
   }
   exit();
}


//test la connection a la base de donn???.
function test_connect() {
   global $DB;

   if ($DB->error == 0) {
      return true;
   }
   return false;
}


//Change table2 from varchar to ID+varchar and update table1.chps with depends
function changeVarcharToID($table1, $table2, $chps) {
   global $DB;

   if (!$DB->fieldExists($table2, "ID", false)) {
      $query = " ALTER TABLE `$table2`
                 ADD `ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
      $DB->queryOrDie($query);
   }

   $query = "ALTER TABLE `$table1`
             ADD `temp` INT";
   $DB->queryOrDie($query);

   $iterator = $DB->request([
      'SELECT' => [
         "$table1.ID AS row1",
         "$table2.ID AS row2",
      ],
      'FROM'   => [$table1, $table2],
      'WHERE'  => [
         "$table2.name" => new \QueryExpression(DBmysql::quoteName("$table1.$chps"))
      ]
   ]);

   while ($line = $iterator->next()) {
      $DB->updateOrDie(
         $table1,
         ['temp' => $line['row2']],
         ['ID' => $line['row1']]
      );
   }
   $DB->freeResult($result);

   $query = "ALTER TABLE `$table1`
             DROP `$chps`";
   $DB->queryOrDie($query);

   $query = "ALTER TABLE `$table1`
             CHANGE `temp` `$chps` INT";
   $DB->queryOrDie($query);
}



//update database
function doUpdateDb() {
   global $GLPI_CACHE, $migration, $update;

   $currents            = $update->getCurrents();
   $current_version     = $currents['version'];
   $current_db_version  = $currents['dbversion'];
   $glpilanguage        = $currents['language'];

   $migration = new Migration(GLPI_SCHEMA_VERSION);
   $update->setMigration($migration);

   if (defined('GLPI_PREVER')) {
      if ($current_db_version != GLPI_SCHEMA_VERSION && !isset($_POST['agree_dev'])) {
         return;
      }
   }

   $update->doUpdates($current_version);
   $GLPI_CACHE->clear();
}


function updateTreeDropdown() {
   global $DB;

   // Update Tree dropdown
   if ($DB->tableExists("glpi_dropdown_locations")
       && !$DB->fieldExists("glpi_dropdown_locations", "completename", false)) {
      $query = "ALTER TABLE `glpi_dropdown_locations`
                ADD `completename` TEXT NOT NULL ";
      $DB->queryOrDie($query, "0.6 add completename in dropdown_locations");
   }

   if ($DB->tableExists("glpi_dropdown_kbcategories")
       && !$DB->fieldExists("glpi_dropdown_kbcategories", "completename", false)) {
      $query = "ALTER TABLE `glpi_dropdown_kbcategories`
                ADD `completename` TEXT NOT NULL ";
      $DB->queryOrDie($query, "0.6 add completename in dropdown_kbcategories");
   }

   if ($DB->tableExists("glpi_locations") && !$DB->fieldExists("glpi_locations", "completename", false)) {
      $query = "ALTER TABLE `glpi_locations`
                ADD `completename` TEXT NOT NULL ";
      $DB->queryOrDie($query, "0.6 add completename in glpi_locations");
   }

   if ($DB->tableExists("glpi_knowbaseitemcategories")
       && !$DB->fieldExists("glpi_knowbaseitemcategories", "completename", false)) {
      $query = "ALTER TABLE `glpi_knowbaseitemcategories`
                ADD `completename` TEXT NOT NULL ";
      $DB->queryOrDie($query, "0.6 add completename in glpi_knowbaseitemcategories");
   }
}

/**
 * Display security key check form.
 *
 * @return void
 */
function showSecurityKeyCheckForm() {
   global $CFG_GLPI, $update;

   echo '<form action="update.php" method="post">';
   echo '<input type="hidden" name="continuer" value="1" />';
   echo '<input type="hidden" name="missing_key_warning_shown" value="1" />';
   echo '<div class="center">';
   echo '<h3>' . __('Falta el archivo de clave de seguridad') . '</h3>';
   echo '<p>';
   echo '<img src="' . $CFG_GLPI['root_doc'] . '/pics/ko_min.png" />';
   echo sprintf(
      __('Falta el archivo de claves "% s" utilizado para cifrar / descifrar datos confidenciales. Debe recuperarlo de su instalación anterior o los datos cifrados serán ilegibles.'),
      $update->getExpectedSecurityKeyFilePath()
   );
   echo '</p>';
   echo '<input type="submit" name="ignore" class="submit" value="' . __('Ignorar advertencia') . '" />';
   echo '&nbsp;&nbsp;';
   echo '<input type="submit" name="retry" class="submit" value="' . __('Intentar otra vez') . '" />';
   echo '</form>';
}

//Debut du script
$HEADER_LOADED = true;

Session::start();

Session::loadLanguage('', false);

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='utf-8'>";
echo "<meta http-equiv='Content-Script-Type' content='text/javascript'>";
echo "<meta http-equiv='Content-Style-Type' content='text/css'>";
echo "<title>Configuración de Civikmind</title>";
//JS
echo Html::script("public/lib/base.js");
// CSS
echo Html::css('public/lib/base.css');
echo Html::css('css/style_install.css');
echo "</head>";
echo "<body>";
echo "<div id='principal'>";
echo "<div id='bloc'>";
echo "<div id='logo_bloc'></div>";
echo "<h2>Configuración de Civikmind</h2>";
echo "<br><h3>".__('Actualizar')."</h3>";

// step 1    avec bouton de confirmation

if (empty($_POST["continuer"]) && empty($_POST["from_update"])) {

   if (empty($from_install) && !isset($_POST["from_update"])) {
      echo "<div class='center'>";
      echo "<h3><span class='migred'>".__('¡Imposible realizar una actualización de esta manera!')."</span>";
      echo "<p>";
      echo "<a class='vsubmit' href='../index.php'>".__('Volver a Civikmind')."</a></p>";
      echo "</div>";

   } else {
      echo "<div class='center'>";
      echo "<h3><span class='migred'>".sprintf(__('¡Precaución! Actualizará la base de datos Civikmind llamada: %s'), $DB->dbdefault) ."</h3>";

      echo "<form action='update.php' method='post'>";
      if (strlen(GLPI_SCHEMA_VERSION) > 40) {
         echo Config::agreeDevMessage();
      }
      echo "<input type='submit' class='submit' name='continuer' value=\"".__('Continuar')."\">";
      Html::closeForm();
      echo "</div>";
   }

} else {
   // Step 2
   if (test_connect()) {
      echo "<h3>".__('Conexión a la base de datos exitosa')."</h3>";
      echo "<p class='center'>";
      $result = Config::displayCheckDbEngine(true);
      echo "</p>";
      if ($result > 0) {
         die(1);
      }
      if ($update->isExpectedSecurityKeyFileMissing()
          && (!isset($_POST['missing_key_warning_shown']) || !isset($_POST['ignore']))) {
         // Display missing security key file form if key file is missing
         // unless it has already been displayed and user clicks on "ignore" button.
         showSecurityKeyCheckForm();
      } else if (!isset($_POST["update_location"])) {
         $current_version = "0.31";
         $config_table    = "glpi_config";

         if ($DB->tableExists("glpi_configs")) {
            $config_table = "glpi_configs";
         }

         if ($DB->tableExists($config_table)) {
            $current_version = Config::getCurrentDBVersion();
         }
         echo "<div class='center'>";
         doUpdateDb();

         if (showLocationUpdateForm()) {
            switch ($current_version) {
               case "0.31":
               case "0.4":
               case "0.41":
               case "0.42":
               case "0.5":
               case "0.51":
               case "0.51a":
               case "0.6":
               case "0.65":
               case "0.68":
               case "0.68.1":
               case "0.68.2":
               case "0.68.3":
                  showContentUpdateForm();
                  break;

               default:
                  echo "<form action='".$CFG_GLPI["root_doc"]."/install/update.php' method='post'>";
                  echo "<input type='hidden' name='update_end' value='1'/>";

                  echo "<hr />";
                  echo "<h2>".__('Una última cosa antes de empezar')."</h2>";
                  echo "<p>";
                  echo GLPINetwork::showInstallMessage();
                  echo "</p>";
                  echo "<a href='".GLPI_NETWORK_SERVICES."' target='_blank' class='vsubmit'>".
                     __('Donativo')."</a><br /><br />";

                  if (!Telemetry::isEnabled()) {
                     echo "<hr />";
                     echo Telemetry::showTelemetry();
                  }
                  echo Telemetry::showReference();

                  echo "<p class='submit'><input type='submit' name='submit' class='submit' value='".
                           __('Usar Civikmind')."'></p>";
                  Html::closeForm();
            }
         }
         echo "</div>";
      }

   } else {
      echo "<h3>";
      echo __("Connection to database failed, verify the connection parameters included in config_db.php file")."</h3>";
   }

}

echo "</div></div></body></html>";
