<?php
include ("../../../inc/includes.php");

Html::header(__('Iframe', 'iframe'), $_SERVER['PHP_SELF'] ,"tools", "PluginIframeConfig");

PluginIframeConfig::showConfigPage2($_GET["link"]);

Html::footer();
