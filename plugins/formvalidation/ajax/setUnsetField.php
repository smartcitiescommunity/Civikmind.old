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

$ret = false ;
if( isset($_REQUEST['action']) ){
   switch( $_REQUEST['action'] ) {
      case 'set' :
         if( $_REQUEST['fieldindex'] > 0 ) {
            $query = "UPDATE glpi_plugin_formvalidation_fields 
                     SET is_active=1, 
                        show_mandatory=1, 
                        css_selector_errorsign='".$DB->escape(html_entity_decode( $_REQUEST['css_selector_errorsign']))."', 
                        css_selector_mandatorysign='".$DB->escape(html_entity_decode( $_REQUEST['css_selector_mandatorysign']))."' 
                     WHERE id=".$_REQUEST['fieldindex'];
            if($DB->query( $query ) && $DB->affected_rows() > 0 ) {
               $ret = true ;
            }
         } else {
            // we may need to add a form
            if( $_REQUEST['formindex'] == 0 ) {
               // add form
               // extract default form name from form_css_selector
               $name = '';
               $action = '' ;
               $matches = array() ;
               $regex = "/form(\\[name=\\\"(?'name'\\w*)\\\"])?\\[action=\\\"(?'action'[\\w\\/\\.]*)\\\"]/"; 
               if( preg_match( $regex, str_replace("\\", "", html_entity_decode($_REQUEST['form_css_selector'])), $matches ) ){
                  if(isset($matches['name'] )){
                     $name = $matches['name'];
                  }
                  $action =  $matches['action'];
               }
               $query = "INSERT INTO `glpi_plugin_formvalidation_forms` (`name`, `pages_id`, `css_selector`, `is_active`, `is_createitem`)
                         VALUES ('".$DB->escape("$name($action)")."', ".$_REQUEST['pages_id'].", '".$DB->escape(html_entity_decode($_REQUEST['form_css_selector']))."', 1, ".$_REQUEST['is_createitem'].")";
               if( $DB->query( $query ) )
                  $_REQUEST['formindex'] = $DB->insert_id();
               else 
                  $ret = false ;
            }
            $query = "INSERT INTO `glpi_plugin_formvalidation_fields` (`name`, `forms_id`, `css_selector_value`, `css_selector_errorsign`, `css_selector_mandatorysign`, `is_active`) VALUES (" ;
            $query .= "'".$_REQUEST['name']."', ";
            $query .= $_REQUEST['formindex'].", ";
            $query .= "'".$DB->escape(html_entity_decode( $_REQUEST['css_selector_value']))."', ";
            $query .= "'".$DB->escape(html_entity_decode( $_REQUEST['css_selector_errorsign']))."', ";
            $query .= "'".$DB->escape(html_entity_decode( $_REQUEST['css_selector_mandatorysign']))."', 1)" ;
            
            if( $DB->query( $query ) ) {
               $_REQUEST['fieldindex'] = $DB->insert_id();
               $ret = array( 'forms' => array( ) ) ; // by default
               $query = "SELECT * FROM glpi_plugin_formvalidation_forms WHERE id = ".$_REQUEST['formindex'] ;
               foreach( $DB->request( $query ) as $form ) {
                  $ret['forms_id'] = $form['id'] ;
                  $ret['forms'][$form['id']]=Toolbox::stripslashes_deep( $form );
                  $ret['forms'][$form['id']]['fields'] = array(); // needed in case this form has no fields
                  $query = "SELECT * FROM glpi_plugin_formvalidation_fields WHERE id = ".$_REQUEST['fieldindex'] ;
                  foreach( $DB->request( $query ) as $field ) {
                     $ret['fields_id']=$field['id'];
                     $ret['forms'][$form['id']]['fields'][$field['id']] = Toolbox::stripslashes_deep( $field ) ;
                  }
               }
            }
         }
         break;
      case 'unset' :
         $query = "UPDATE glpi_plugin_formvalidation_fields SET is_active=0 WHERE id=".$_REQUEST['fieldindex'];
         if($DB->query( $query ) && $DB->affected_rows() > 0 ) {
            $ret = true ;
         }
         break;
      case 'hidemandatorysign':
         $query = "UPDATE glpi_plugin_formvalidation_fields SET show_mandatory=0 WHERE id=".$_REQUEST['fieldindex'];
         if($DB->query( $query ) && $DB->affected_rows() > 0 ) {
            $ret = true ;
         }
         break;
   }
}

echo json_encode( $ret ) ;

