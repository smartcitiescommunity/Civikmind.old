<?php
include ("../../../inc/includes.php");


Html::header(__('Form Validations','formvalidation'), $_SERVER['PHP_SELF'] , "config", "PluginFormvalidationMenu", "formvalidationform");

if (Session::haveRight('entity', READ) || Session::haveRight("entity", UPDATE)) {
   //$process=new PluginFormvalidationForm();

   Search::show('PluginFormvalidationForm');

} else {
    Html::displayRightError();
}
Html::footer();

?>