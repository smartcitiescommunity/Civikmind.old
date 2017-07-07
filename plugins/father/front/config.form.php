<?php

include('../../../inc/includes.php');

$plugin = new Plugin();
if ($plugin->isActivated("father")) {

   $config = new PluginFatherConfig();

   if (isset($_POST["update"])) {

      if (isset($_POST['father_ids'])) {
         $_POST['father_ids'] = exportArrayToDB($_POST['father_ids']);
      } else {
         $_POST['father_ids'] = exportArrayToDB(array());
      }
      if (isset($_POST['statut_impacted'])) {
         $_POST['statut_impacted'] = exportArrayToDB($_POST['statut_impacted']);
      } else {
         $_POST['statut_impacted'] = exportArrayToDB(array());
      }
      $config->update($_POST);
      //Update singelton
      PluginFatherConfig::getConfig(true);
      Html::redirect($_SERVER['HTTP_REFERER']);

   } else {
      Html::header(PluginFatherConfig::getTypeName(), '', "plugins", "father");
      $config->showForm();
      Html::footer();
   }

} else {
   Html::header(__('Setup'), '', "config", "plugins");
   echo "<div align='center'><br><br>";
   echo "<img src=\"" . $CFG_GLPI["root_doc"] . "/pics/warning.png\" alt='warning'><br><br>";
   echo "<b>" . __('Please activate the plugin', 'father') . "</b></div>";
   Html::footer();
}
