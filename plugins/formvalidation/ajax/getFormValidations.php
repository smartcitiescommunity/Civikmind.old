<?php
/*
 * -------------------------------------------------------------------------
Form Validation plugin
Copyright (C) 2016 by Raynet SAS a company of A.Raymond Network.

http://www.araymond.com
-------------------------------------------------------------------------

LICENSE

This file is part of Form Validation plugin for GLPI.

This file is free software; you can redistribute it and/or modify
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

$AJAX_INCLUDE = 1;
include ('../../../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

$config = PluginFormvalidationConfig::getInstance();

$validations = array( 'config' => $config->fields, 'pages_id' => 0, 'forms' => array( ) ) ; // by default

// from user session
$validations['config']['editmode']=$_SESSION['glpiformvalidationeditmode'];

$is_createitem = 0 ; // by default

$obj = getItemForItemtype( $_GET['itemtype'] ) ;
if( $obj ) {
   if( $_GET['id'] > 0 ) {
      $obj->getFromDB( $_GET['id'] ) ;
      $entity_restrict = 0 ; // by default if $obj doesn't have entities_id in table
      if( isset($obj->fields['entities_id']) ) {
         $entity_restrict = $obj->fields['entities_id'] ;
      }
   } else {
      $is_createitem = 1 ;
      $entity_restrict = $_SESSION['glpiactive_entity'] ;
   }

   $query = "SELECT * FROM glpi_plugin_formvalidation_pages WHERE itemtype = '".$obj->getType()."' AND is_active=1 ".
            getEntitiesRestrictRequest( 'AND', 'glpi_plugin_formvalidation_pages', '', $entity_restrict, true) ;

   foreach( $DB->request( $query ) as $page ) {
      $validations['pages_id']=$page['id'] ; // normaly there is only one page
      $validations['itemtype']=$page['itemtype'] ; // normaly there is only one page

      $query = "SELECT * FROM glpi_plugin_formvalidation_forms WHERE is_createitem=$is_createitem AND pages_id = ".$page['id'] ;

      foreach( $DB->request( $query ) as $form ) {
         $validations['forms'][$form['id']]= Toolbox::stripslashes_deep( $form );
         $validations['forms'][$form['id']]['fields'] = array(); // needed in case this form has no fields

         $query = "SELECT * FROM glpi_plugin_formvalidation_fields WHERE forms_id = ".$form['id'] ;
         foreach( $DB->request( $query ) as $field ) {
            $validations['forms'][$form['id']]['fields'][$field['id']] = Toolbox::stripslashes_deep( $field );
         }
      }
   }
}
echo json_encode( $validations, JSON_HEX_APOS | JSON_HEX_QUOT ) ;

