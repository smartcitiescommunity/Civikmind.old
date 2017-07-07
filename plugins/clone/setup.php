<?php

class PluginCloneConfig extends CommonDBTM {

   static protected $notable = true;
   
   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return __('Clone Ticket','clone');
   }
   
   /**
    *  @see CommonGLPI::getMenuContent()
    *
    *  @since version 0.5.6
   **/
   static function getMenuContent() {
   	global $CFG_GLPI;
   
   	$menu = array();

      $menu['title']   = __('Clone Ticket','clone');
     // $menu['page']    = '/plugins/clone/front/index.php';
   	return $menu;
   }
}



function plugin_init_clone() {
  
   global $PLUGIN_HOOKS, $LANG ;
   

  $plugin = new Plugin();
   if (isset($_SESSION['glpiID']) && $plugin->isInstalled('clone') && $plugin->isActivated('clone')) {
   
               //clone ticket feature
               //if ($_SESSION['plugins']['clone']['config']['clone_ticket'] == true) {
                  $PLUGIN_HOOKS['add_javascript']['clone'][] = 'inc/clone.js.php';
               //}                        
         }   
         
    $PLUGIN_HOOKS['csrf_compliant']['clone'] = true;   
    $PLUGIN_HOOKS["menu_toadd"]['clone'] = array('plugins'  => 'PluginCloneConfig');
    $PLUGIN_HOOKS['config_page']['clone'] = 'front/config.php';
                
}


function plugin_version_clone(){
	global $DB, $LANG;

	return array('name'			=> __('Clone Ticket','clone'),
					'version' 			=> '1.0.0',
					'author'			   => '<a href="mailto:stevenesdonato@gmail.com"> Stevenes Donato </b> </a>',
					'license'		 	=> 'GPLv2+',
					'homepage'			=> 'https://forge.indepnet.net/projects/clone',
					'minGlpiVersion'	=> '0.85'
					);
}

function plugin_clone_check_prerequisites(){
        if (GLPI_VERSION>=0.85){
                return true;
        } else {
                echo "GLPI version NOT compatible. Requires GLPI 0.85";
        }
}


function plugin_clone_check_config($verbose=false){
	if ($verbose) {
		echo 'Installed / not configured';
	}
	return true;
}


?>
