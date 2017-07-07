<?php
/*
 * @version $Id$
-------------------------------------------------------------------------
GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2015 Teclib'.

http://glpi-project.org

based on GLPI - Gestionnaire Libre de Parc Informatique
Copyright (C) 2003-2014 by the INDEPNET Development Team.

-------------------------------------------------------------------------

LICENSE

This file is part of GLPI.

GLPI is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

GLPI is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI. If not, see <http://www.gnu.org/licenses/>.
--------------------------------------------------------------------------
 */

/** @file
 * @brief
 */

include ('../../../inc/includes.php');

if (empty($_GET["id"])) {
   $_GET["id"] = '';
}

Session::checkLoginUser();

$field = new PluginFormvalidationField();
if (isset($_POST["purge"])) {
   $field->check($_POST["id"], PURGE);
   $field->delete($_POST,1);

   $field->redirectToList();

} else if (isset($_POST["update"])) {

   if( !isset( $_POST['id'] ) ) {
      // then we have an array of input to update
      foreach( $_POST as $key => $val ) {
         $match = array();
         if( preg_match( "/^formula_(\\d+)$/", $key, $match ) ) {
            $ID = $match[1] ;
            $field->check($ID, UPDATE);

            $formula = Html::entity_decode_deep( $val ) ;
            $formula = ($formula===''?'NULL':$formula);

            $_POST["show_mandatory_if_$ID"] = Html::entity_decode_deep( $_POST["show_mandatory_if_$ID"] ) ;
            $post = array( 'id' => $ID, 'formula' => $formula, 'is_active' => $_POST["is_active_$ID"], 'show_mandatory' => $_POST["show_mandatory_$ID"], 'show_mandatory_if' => $_POST["show_mandatory_if_$ID"] );
            $field->update($post);
         }
      }

   } else {
      // then we have only one field
      $field->check($_POST["id"], UPDATE);

      $_POST["formula"] = Html::entity_decode_deep( $_POST["formula"] ) ;
      $_POST["show_mandatory_if"] = Html::entity_decode_deep( $_POST["show_mandatory_if"] ) ;
      $field->update($_POST);
   }

   Html::back();

} else {
   Html::header(__('Form Validation - Field','formvalidation'), $_SERVER['PHP_SELF'] , "config", "PluginFormvalidationMenu", "formvalidationfield");
   $field->display($_GET);
   Html::footer();
}
?>
