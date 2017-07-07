<?php

// Install process for plugin : need to return true if succeeded
function plugin_iframe_install() {
   global $DB;

   // $config = new Config();
   // $config->setConfigurationValues('plugin:IframePlugin', array('configuration' => false));

   // ProfileRight::addProfileRights(array('iframeplugin:read'));

  if (!TableExists("glpi_plugin_iframeplugin_links")) {
      
      $DB->runFile(GLPI_ROOT ."/plugins/iframeplugin/sql/empty-1.0.0.sql");

   }

  
 
   return true;
}


// Uninstall process for plugin : need to return true if succeeded
function plugin_iframe_uninstall() {
   global $DB;

   if (TableExists("glpi_plugin_iframeplugin_links")) {
       
      $query = "DROP TABLE `glpi_plugin_iframeplugin_links`";
      $DB->query($query) or die("error deleting glpi_plugin_iframeplugin_links");

    }

  
   return true;
}

?>
