<?php

/*
 *
  This file is part of the Modifications plugin.

 Order plugin is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 Stevenes Donato; either version 2 of the License, or
 (at your option) any later version.

 Order plugin is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; along with itilcategorygroups. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 @package   modifications
 @author    Stevenes Donato
 @copyright Copyright (c) 2020 Stevenes Donato
 @license   GPLv3
            http://www.gnu.org/licenses/gpl.txt
 @link      https://github.com/stdonato/glpi-modifications
 @link      http://www.glpi-project.org/
 @since     2018
 --------------------------------------------------------------------------
 */

/**
 * @name plugin_mod_install
 * @access public
 * @return boolean
 */


function plugin_init_mod() {
  
   global $PLUGIN_HOOKS, $LANG ;
	
	$PLUGIN_HOOKS['csrf_compliant']['mod'] = true;         
   
   $plugin = new Plugin();
   if ($plugin->isInstalled('mod') && $plugin->isActivated('mod')) {

	   Plugin::registerClass('PluginMod', [
	      'addtabon' => ['Config']
	   ]);
	             
	   $PLUGIN_HOOKS['config_page']['mod'] = 'config.php';
	   $PLUGIN_HOOKS['add_javascript']['mod'][] = "scripts/stats.js";
	   $PLUGIN_HOOKS['add_javascript']['mod'][] = "scripts/ind.js";
 	   include('install.php');                     
 	}  
 	
 	if ($plugin->isInstalled('mod') && !$plugin->isActivated('mod')) {
		 	include('uninstall.php'); 
 	}
 	
}


function plugin_version_mod(){
	global $DB, $LANG;

	return array('name'			   => __('GLPI Modifications'),
					'version' 			=> '1.5.5',
					'author'			   => '<a href="mailto:stevenesdonato@gmail.com"> Stevenes Donato </b> </a>',
					'license'		 	=> 'GPLv2+',
					'homepage'			=> 'https://github.com/stdonato/glpi-modifications',
					'minGlpiVersion'	=> '9.4.6');
}

function plugin_mod_check_prerequisites(){
     if (GLPI_VERSION >= '9.4.6'){   	
	         return true;	     	         
     } 
     else {
         echo "GLPI version not compatible need 9.4.6";
     }
}


function plugin_mod_check_config($verbose=false){
	if ($verbose) {
		echo 'Installed / not configured';
	}
	return true;
}


?>
