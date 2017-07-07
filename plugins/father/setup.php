<?php
/**
 * Check plugin's config before activation
 */
function plugin_father_check_config($verbose=false) {
   return true;
}



function plugin_init_father() {
   global $PLUGIN_HOOKS;
   $PLUGIN_HOOKS['csrf_compliant']['father'] = true;

  if (class_exists('PluginFatherFather')) {
   $config = new PluginFatherConfig();
   $PLUGIN_HOOKS['csrf_compliant']['father'] = true;
   $PLUGIN_HOOKS['config_page']['father'] = 'front/config.form.php';

   Plugin::registerClass('PluginFatherFatherItem',
            array('addtabon' => array('PluginFatherFather')));

    if (
	   ((strpos($_SERVER['REQUEST_URI'], "/ticket.form.php") !== false)&& $config->isOk(0)) ||
	   ((strpos($_SERVER['REQUEST_URI'], "/problem.form.php") !== false)&& $config->isOk(1)) ||
	   ((strpos($_SERVER['REQUEST_URI'], "/change.form.php") !== false)&& $config->isOK(2))
    ) {
        $PLUGIN_HOOKS['add_javascript']['father'][] = 'js/show_father.js';
    }

    if ($config->isOk(0)){
      $PLUGIN_HOOKS['pre_item_update']['father']['Ticket'] = array('PluginFatherFather', 'beforeUpdate');;
    }
    if ($config->isOk(1)){
      $PLUGIN_HOOKS['pre_item_update']['father']['Problem'] = array('PluginFatherFather', 'beforeUpdate');;
    }

    if ($config->isOk(2)){
      $PLUGIN_HOOKS['pre_item_update']['father']['Change'] = array('PluginFatherFather', 'beforeUpdate');;
    }
  }
}

function plugin_version_father() {
   return array('name'       => __('Father&Sons', 'father'),
            'version'        => '1.0.1',
            'author'         => 'zorm (<a href="http://www.probesys.com">Probesys</a>)',
            'homepage'       => 'https://www.probesys.com',
            'license'        => '<a href="../plugins/father/LICENSE" target="_blank">AGPLv3</a>',
            'minGlpiVersion' => "0.90");
}

/**
 * Check plugin's prerequisites before installation
 */
function plugin_father_check_prerequisites() {
   if (version_compare(GLPI_VERSION,'0.90','lt')) {
      echo __('This plugin requires GLPI >= 0.90');
   } else {
      return true;
   }
   return false;
}
