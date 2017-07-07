<?php
define("CUSTOM_FILES_DIR", GLPI_ROOT."/files/_plugins/iframe/");
define("CUSTOM_CSS_PATH", CUSTOM_FILES_DIR."glpi_style.css");

// Init the hooks of the plugins -Needed
function plugin_init_iframe() {
   global $PLUGIN_HOOKS, $CFG_GLPI;

   $PLUGIN_HOOKS['config_page']['iframe']  = "front/config.php";
   $PLUGIN_HOOKS['add_javascript']['iframe'][]    = 'selector.js.php';

   $PLUGIN_HOOKS['add_css']['iframe'][]           = 'style.css';

   $PLUGIN_HOOKS['csrf_compliant']['iframe']      = true;

   if (Session::haveRight('config', UPDATE)) {
      $PLUGIN_HOOKS['menu_toadd']['iframe'] = array('tools' => 'PluginIframeConfig');
   }

   // exclude some pages from splitted layout
   if (isset($CFG_GLPI['layout_excluded_pages'])) {
      array_push($CFG_GLPI['layout_excluded_pages'], "tab.form.php",
                                                     "defaulttab.form.php");
   }
}


// Get the name and the version of the plugin - Needed
function plugin_version_iframe() {
   return array('name'           => "Iframe",
                'version'        => "0.90-1.0.0",
                'author'         => "<a href='http://www.trulysystems.com'>Truly Systems</a>",
                'homepage'       => "https://github.com/truly-systems/iframeplugin",
                'minGlpiVersion' => "0.90");
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_iframe_check_prerequisites() {
   if (version_compare(GLPI_VERSION,'0.90','lt')) {
      echo "This plugin requires GLPI >= 0.90";
      return false;
   } elseif (!extension_loaded("gd")) {
      echo "php-gd is required";
   }
   if (version_compare(PHP_VERSION, '5.3.0', 'lt')) {
      echo "PHP 5.3.0 or higher is required";
      return false;
   }

   return true;
}


// Check configuration process for plugin : need to return true if succeeded
// Can display a message only if failure and $verbose is true
function plugin_iframe_check_config($verbose=false) {
   if (true) { // Your configuration check
      return true;
   }
   if ($verbose) {
      echo __('Installed / not configured');
   }
   return false;
}
