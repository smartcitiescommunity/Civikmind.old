<?php

include ("../../../inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();
Session::checkLoginUser();

if (!isset($_REQUEST['name'])) {
   exit;
}

echo PluginTagTag::getTagForEntityName($_REQUEST['name']);
