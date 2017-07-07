<?php

include ("../../../inc/includes.php");
Html::header(__('Iframe', 'iframe'), $_SERVER['PHP_SELF'],
   "tools", "PluginIframeConfig", "defaulttab");

Search::Show('PluginIframeDefaulttab');

Html::footer();
