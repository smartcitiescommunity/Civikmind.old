<?php

/**
 * hook short summary.
 *
 * hook description.
 *
 * @version 1.0
 * @author MoronO
 */
class PluginFormvalidationHook extends CommonDBTM {

   /**
    * Summary of plugin_post_item_form_formvalidation
    * @param mixed $parm mixed
    * @return mixed
    */
   static public function plugin_post_item_form_formvalidation($parm) {
      if ($parm['item']->getType() == 'TicketSatisfaction') {
         echo "<script type='text/javascript'>
                     $('#stars').on('rated', function() { $('#satisfaction_data').change(); });
                </script>";
         echo "</td></tr>";
      }
   }

   const HELPER_FUNCTIONS = <<<'EOT'
    //------------------------------------------
    // helper function to verify a if a string
    // is really a date
    // uses the datapicker JQuery plugin
    //------------------------------------------
    function isValidDate(d) {
        try {
            $.datepicker.parseDate($(".hasDatepicker`").datepicker("option", "dateFormat"), d)
            return true;
        } catch (e) {
            return false;
        }
    }

    //------------------------------------------
    // helper function to verify a if a string
    // is really a time from 00:00[:00] to 23:59[:59]
    //------------------------------------------
    function isValidTime(str) {
        return /^(?:[0-1]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/.test(str);
    }

    //------------------------------------------
    // helper function to verify a if a string
    // is really an integer
    //------------------------------------------
    function isValidInteger(str) {
        return /^\d+$/.test(str);
    }

    //------------------------------------------
    // helper function to count words in a given string
    // returns quantity of words
    //------------------------------------------
    function countWords(str) {
        return str.split(/\W+/).length;
    }

    //------------------------------------------
    // helper function to verify a if a string
    // is really an IPV4 address
    // uses the datapicker JQuery plugin
    //------------------------------------------
    function isValidIPv4(ipaddress) {
        return /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress) ;
    }


    //------------------------------------------
    // helper function to verify a if a string
    // is really an IPV6 address
    // uses the datapicker JQuery plugin
    //------------------------------------------
    function isValidIPv6(ipaddress) {
        return /^((?:[0-9A-Fa-f]{1,4}))((?::[0-9A-Fa-f]{1,4}))*::((?:[0-9A-Fa-f]{1,4}))((?::[0-9A-Fa-f]{1,4}))*|((?:[0-9A-Fa-f]{1,4}))((?::[0-9A-Fa-f]{1,4})){7}$/.test(ipaddress);
    }

    //------------------------------------------
    // helper function to verify a if a string
    // is really an email address
    // will use the input type=email if it exists (HTML5)
    // otherwise will use a basic verification.
    //------------------------------------------
    function isValidEmail(value) {
        var input = document.createElement("input");

        input.type = "email";
        input.value = value;

        return typeof input.checkValidity == "function" ? input.checkValidity() : /^\S+@\S+\.\S+$/.test(value);
    }

    //------------------------------------------
    // helper function to verify a if a string
    // is really a MAC address
    //------------------------------------------
    function isValidMacAddress(str) {
        return /^[\da-f]{2}([:-])(?:[\da-f]{2}\1){4}[\da-f]{2}$/i.test(str);
    }

EOT;

   /**
    * Summary of plugin_pre_item_update_formvalidation
    * @param mixed $parm the object that is going to be updated
    * @return mixed
    */
   static public function plugin_pre_item_update_formvalidation($parm) {
      global $DB;

      // to be executed only for massive actions
      if (strstr($_SERVER['PHP_SELF'], "/front/massiveaction.php")) {
         $ret=array();

         //return;
         //clean input values
         $input = $parm->input;
         unset( $input['id'] );
         foreach ($input as $key => $val) {
            if (preg_match("/^_/", $key )) {
               unset( $input[$key] );
            }
         }
         $itemvalues = array_merge( $parm->fields, $input );
         $formulas = array();
         $fieldnames = array();
         $fieldtitles = array();

         $query = "SELECT DISTINCT glpi_plugin_formvalidation_forms.* FROM glpi_plugin_formvalidation_forms
               LEFT JOIN glpi_plugin_formvalidation_fields ON glpi_plugin_formvalidation_forms.id=glpi_plugin_formvalidation_fields.forms_id
               LEFT JOIN glpi_plugin_formvalidation_pages ON glpi_plugin_formvalidation_pages.id=glpi_plugin_formvalidation_forms.pages_id
               LEFT JOIN glpi_plugin_formvalidation_itemtypes ON glpi_plugin_formvalidation_itemtypes.id=glpi_plugin_formvalidation_pages.itemtypes_id
               WHERE glpi_plugin_formvalidation_itemtypes.itemtype ='".$parm->getType()."'
				      AND glpi_plugin_formvalidation_forms.use_for_massiveaction=1
               "; // css_selector like '%\"".$_SERVER['PHP_SELF']."%'
         $where = "";
         foreach (array_keys($input) as $val) {
            if ($where != "") {
               $where .= "\nOR ";
            }
            $where .= "glpi_plugin_formvalidation_fields.css_selector_value like '%\"$val%'";
         }

         foreach ($DB->request( $query." AND ( $where )" ) as $form) {
            foreach ($DB->request("SELECT * from glpi_plugin_formvalidation_fields WHERE forms_id = ".$form['id'])  as $field) {
               $matches = array();
               if (preg_match('/\[(name|id\^)=\\\\{0,1}"(?<name>[a-z_\-0-9]+)\\\\{0,1}"\]/i', $field['css_selector_value'], $matches)) { 
                  $fieldnames[$field['id']]=$matches['name'];
                  $formulas[$field['id']]=($field['formula'] ? $field['formula'] : '#>0 || #!=""');
                  $fieldtitles[$field['id']]=$field['name'];
               }
            }

            $values=array();
            foreach ($formulas as $fieldnum => $formula) {
               $values[$fieldnum] = ($itemvalues[$fieldnames[$fieldnum]] ? $itemvalues[$fieldnames[$fieldnum]] : "" );
            }
            $formulaJS=array();
            foreach ($formulas as $fieldnum => $formula) {
               $formulaJS[$fieldnum] = $formula;
               foreach ($values as $valnum => $val) {
                  if ($fieldnum == $valnum) {
                     $regex = '/#\B/i';
                  } else {
                     $regex = '/#'.$valnum.'\b/i';
                  }
                  //$formulaJS[$fieldnum] = preg_replace( $regex, '"'.Toolbox::addslashes_deep( $val ).'"', $formulaJS[$fieldnum] ) ;
                  $formulaJS[$fieldnum] = preg_replace( $regex, "PHP.val[$valnum]", $formulaJS[$fieldnum] );
               }
            }

            $v8 = new V8Js();
            $v8->val = $values;
            $ret=array();
            foreach ($formulaJS as $index => $formula) {
               try {
                  if (!$v8->executeString(self::HELPER_FUNCTIONS."
                                       exec = $formula;
                                       " ) ) {
                     Session::addMessageAfterRedirect( __('Mandatory fields or wrong value: ').__($fieldtitles[$index]), true, ERROR );
                     $ret[]=$fieldnames[$index];
                  }
               } catch (Exception $ex) {
                  Session::addMessageAfterRedirect( __('Error: ').__($ex->message), false, ERROR );
                  $ret[]=$fieldnames[$index];
               }
            }
         }

         if (count($ret) > 0) {
            $parm->input = array(); //to prevent update of unconsistant data
         }

      }
   }

   static public function plugin_pre_show_tab_formvalidation($parm ) {
      echo '';
   }

}