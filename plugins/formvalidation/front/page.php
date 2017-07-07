<?php
include ("../../../inc/includes.php");


Html::header(__('Form Validations','formvalidation'), $_SERVER['PHP_SELF'] , "config", "PluginFormvalidationMenu", "formvalidationpage");

if (Session::haveRight('entity', READ) || Session::haveRight("entity", UPDATE)) {
   //$process=new PluginFormvalidationPage();

   Search::show('PluginFormvalidationPage');

} else {
    Html::displayRightError();
}
Html::footer();

?>