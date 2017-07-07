<?php
/*
 */

// ----------------------------------------------------------------------
// Original Author of file: Olivier Moron
// Purpose of file: Provides frame for hidefieldsing button clicks and key pressed
// ----------------------------------------------------------------------

// Init the hooks of the plugin
function plugin_init_hidefields() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['hidefields'] = true;

   if( $_SESSION['glpiactiveprofile']['interface'] == 'helpdesk' ) {
      // Add specific files to add to the header : javascript or css
      $PLUGIN_HOOKS['add_javascript']['hidefields'] = array('js/hidefields.js');
   }

}


// Get the name and the version of the plugin - Needed
function plugin_version_hidefields() {

   return array('name'           => 'Hidefields',
                'version'        => '1.0.0',
                'author'         => 'Olivier Moron',
                'license'        => 'GPLv2+',
                'homepage'       => 'https://github.com/tomolimo/hidefields/',
                'minGlpiVersion' => '0.85');
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_hidefields_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.85','lt')) {
      echo "This plugin requires GLPI >= 0.85";
      return false;
   }
   return true;
}


// Check configuration process for plugin : need to return true if succeeded
// Can display a message only if failure and $verbose is true
function plugin_hidefields_check_config($verbose=false) {
   

   return true;
   
}


?>