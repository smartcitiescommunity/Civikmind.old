<?php

define('PLUGIN_SHOWLOADING_VERSION', '1.0.0');

// Init the hooks of the plugins -Needed
function plugin_init_showloading() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['showloading'] = true;


   Plugin::registerClass('PluginShowloadingConfig', [
      'addtabon' => ['Config']
   ]);

   $CFG_SHOW_LOADING = Config::getConfigurationValues('showloading');
   $color = 'blue';
   if (isset($CFG_SHOW_LOADING['color']) && $CFG_SHOW_LOADING['color']) {
      $color = $CFG_SHOW_LOADING['color'];
   } else {
      if (isset($_SESSION['glpipalette'])) {
         switch ($_SESSION['glpipalette']) {
            case 'aerialgreen':
            case 'flood':
            case 'greenflat':
               $color = 'green';
               break;
            case 'auror':
            case 'lightblue':
               $color = 'blue';
               break;
            case 'automn':
            case 'clockworkorange':
            case 'hipster':
               $color = 'orange';
               break;
            case 'classic':
            case 'dark':
               $color = 'silver';
               break;
            case 'dark':
               $color = 'silver';
               break;
            case 'icecream':
               $color = 'pink';
               break;
            case 'premiumred':
               $color = 'red';
               break;
            case 'purplehaze':
            case 'teclib':
               $color = 'purple';
               break;
            case 'vintage':
               $color = 'yellow';
               break;
         }
      }
   }

   $theme = 'pace-theme-flash';
   if (isset($CFG_SHOW_LOADING['theme']) && $CFG_SHOW_LOADING['theme']) {
      $theme = $CFG_SHOW_LOADING['theme'];
   }

   $PLUGIN_HOOKS['add_javascript']['showloading'][] = 'js/pace.min.js';
   $PLUGIN_HOOKS['add_css']['showloading'][] = "css/$color/$theme.css";
}

// Get the name and the version of the plugin - Needed
function plugin_version_showloading() {
   return array(
      'name'           => __sl('Show Loading'),
      'version'        => PLUGIN_SHOWLOADING_VERSION,
      'author'         => 'Edgard Lorraine Messias',
      'homepage'       => 'https://github.com/edgardmessias/showloading',
      'minGlpiVersion' => '0.85'
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_showloading_check_prerequisites() {
   if (version_compare(GLPI_VERSION, '0.85', 'lt')) {
      echo __sl("This plugin requires GLPI >= 0.85");
      return false;
   } else {
      return true;
   }
}

function plugin_showloading_check_config() {
   return true;
}

function __sl($str) {
   return __($str, 'showloading');
}
