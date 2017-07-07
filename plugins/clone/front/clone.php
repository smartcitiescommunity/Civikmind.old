<?php
//include ("../../../inc/includes.php");
define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/config/config.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

//if (!isset($_REQUEST['tickets_id'])) exit;

if ($_SESSION['glpiactiveprofile']['interface'] == "central") {

	if (isset($_REQUEST['tickets_id'])) {
	
	PluginCloneTicket::cloneTicket($_REQUEST['tickets_id']);
	
	}

}
?>