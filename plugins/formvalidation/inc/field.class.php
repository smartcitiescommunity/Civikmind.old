<?php

if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access directly to this file");
}

/**
 * process short summary.
 *
 * process description.
 *
 * @version 1.0
 * @author MoronO
 */
class PluginFormvalidationField extends CommonDBTM {

   static $rightname = 'entity';

   static function getTypeName($nb=0) {
        global $LANG;

        if ($nb>1) {
           return __('Fields','formvalidation');
        }
        return __('Field','formvalidation');
    }

   /**
    * Summary of getSearchOptions
    * @return mixed
    */
   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = __('Field','formvalidation');

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = __('Name');
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['searchtype']           = 'contains';
      $tab[1]['massiveaction']        = false;
      $tab[1]['itemlink_type'] = $this->getType();

      $tab[8]['table']         = $this->getTable();
      $tab[8]['field']         = 'is_active';
      $tab[8]['name']          = __('Active');
      $tab[8]['massiveaction'] = true;
      $tab[8]['datatype']      = 'bool';

      $tab[4]['table']        = $this->getTable();
      $tab[4]['field']        =  'comment';
      $tab[4]['name']         =  __('Comments');
      $tab[4]['massiveaction'] = true;
      $tab[4]['datatype']     =  'text';

      $tab[19]['table']               = $this->getTable();
      $tab[19]['field']               = 'date_mod';
      $tab[19]['name']                = __('Last update');
      $tab[19]['datatype']            = 'datetime';
      $tab[19]['massiveaction']       = false;

      //$tab[800]['table']               = 'glpi_plugin_formvalidation_pages';
      //$tab[800]['field']               = 'name';
      //$tab[800]['linkfield']           = 'pages_id';
      //$tab[800]['name']                = __('Page', 'formvalidation');
      //$tab[800]['massiveaction']       = false;
      //$tab[800]['datatype']            = 'dropdown';

      $tab[801]['table']               = 'glpi_plugin_formvalidation_forms';
      $tab[801]['field']               = 'name';
      $tab[801]['linkfield']           = 'forms_id';
      $tab[801]['name']                = __('Form', 'formvalidation');
      $tab[801]['massiveaction']       = false;
      $tab[801]['datatype']            = 'dropdown';

      $tab[802]['table']               = $this->getTable();
      $tab[802]['field']               = 'css_selector_value';
      $tab[802]['name']                = __('Value CSS selector', 'formvalidation');
      $tab[802]['massiveaction']       = false;
      $tab[802]['datatype']            = 'dropdown';

      return $tab;
   }



    /**
     * @since version 0.85
     *
     * @see CommonGLPI::getTabNameForItem()
     **/
    function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

       if (static::canView()) {
          $nb = 0;
          switch ($item->getType()) {
             case 'PluginFormvalidationForm' :
                if ($_SESSION['glpishow_count_on_tabs']) {
                   $nb = countElementsInTable('glpi_plugin_formvalidation_fields',
                                              "`forms_id` = '".$item->getID()."'");
                }
                return self::createTabEntry(PluginFormvalidationField::getTypeName(Session::getPluralNumber()), $nb);

             case 'PluginFormvalidationField' :
                //if ($_SESSION['glpishow_count_on_tabs']) {
                //   $nb = countElementsInTable('glpi_plugin_formvalidation_forms',
                //                              "`pages_id` = '".$item->getID()."'");
                //}
                return PluginFormvalidationField::getTypeName(Session::getPluralNumber());
          }
       }
       return '';
    }

    /**
     * @since version 0.85
     **/
    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      self::showForForm($item);

      return true;
   }

   /**
    * Summary of getDataForPage
    * @param PluginFormvalidationForm $form
    * @param mixed $members
    * @param mixed $ids
    * @param mixed $crit
    * @param mixed $tree
    * @return mixed
    */
   static function getDataForForm(PluginFormvalidationForm $form, &$members, &$ids, $crit='', $tree=0) {
      global $DB;

      $entityrestrict = 0 ;

      $restrict = "='".$form->getID()."'";

      // All group members
      $query = "SELECT DISTINCT `glpi_plugin_formvalidation_fields`.`id`,
                       `glpi_plugin_formvalidation_fields`.`id` AS linkID,
                       `glpi_plugin_formvalidation_fields`.`name`,
                       `glpi_plugin_formvalidation_fields`.`css_selector_value`,
                       `glpi_plugin_formvalidation_fields`.`formula`,
                       `glpi_plugin_formvalidation_fields`.`is_active`,
                       `glpi_plugin_formvalidation_fields`.`show_mandatory`,
                       `glpi_plugin_formvalidation_fields`.`show_mandatory_if`,
                       `glpi_plugin_formvalidation_fields`.`forms_id`
                FROM `glpi_plugin_formvalidation_fields`
                WHERE `glpi_plugin_formvalidation_fields`.`forms_id` $restrict
                ORDER BY `glpi_plugin_formvalidation_fields`.`id`";

      $result = $DB->query($query);

      if ($DB->numrows($result) > 0) {
         while ($data=$DB->fetch_assoc($result)) {
            // Add to display list, according to criterion
            if (empty($crit) || $data[$crit]) {
               $members[] = $data;
            }
            // Add to member list (member of sub-group are not member)
            if ($data['forms_id'] == $form->getID()) {
               $ids[]  = $data['id'];
            }
         }
      }

      return $entityrestrict;
   }

   /**
    * Show forms of a page
    *
    * @param $form  PluginFormvalidationForm object: the page
    **/
   static function showForForm(PluginFormvalidationForm $form) {
      global $DB, $LANG, $CFG_GLPI;

      $ID = $form->getID();
      if (!PluginFormvalidationField::canView()
          || !$form->can($ID, READ)) {
         return false;
      }

      // Have right to manage members
      $canedit = self::canUpdate();
      $rand    = mt_rand();
      $field    = new PluginFormvalidationField();
      $used    = array();
      $ids     = array();

      // Retrieve member list
      $entityrestrict = self::getDataForForm($form, $used, $ids);

      $number = count($used);

      $start = 0;


      // Display results
      if ($number) {
         echo "<form name='formfield_form$rand' id='formfield_form$rand' method='post'
                action='".Toolbox::getItemTypeFormURL(__CLASS__)."'>";

         echo "<div class='spaced'>";

         Session::initNavigateListItems('PluginFormvalidationField',
                              //TRANS : %1$s is the itemtype name,
                              //        %2$s is the name of the item (used for headings of a list)
                                        sprintf(__('%1$s = %2$s'),
                                                PluginFormvalidationForm::getTypeName(1), $form->getName()));



         echo "<table class='tab_cadre_fixehov'>";

         $header_begin  = "<tr>";
         $header_top    = '';
         $header_bottom = '';
         $header_end    = '';

         $header_end .= "<th>".__('ID')."</th>";
         $header_end .= "<th>".PluginFormvalidationField::getTypeName(1).__(' / CSS selector', 'formvalidation')."</th>";
         $header_end .= "<th>".__('Active')."</th>";
         $header_end .= "<th>".__("Validation formula", 'formvalidation')."</th>";
         $header_end .= "<th>".__('Force mand. sign', 'formvalidation')."</th>";
         $header_end .= "<th>".__("Mandatory sign formula", 'formvalidation')."</th></tr>";
         echo $header_begin.$header_top.$header_end;

         for ($i=$start, $j=0 ; ($i < $number)  ; $i++, $j++) {
            $data = $used[$i];
            $field->getFromDB($data["id"]);
            if( !isset($field->fields['name']) || $field->fields['name'] == "") {
               $field->fields['name'] = $field->fields['css_selector_value'] ;
            }
            Session::addToNavigateListItems('PluginFormvalidationField', $data["id"]);

            echo "\n<tr class='tab_bg_1'>";

            echo "<td class='center'>";
            echo $data['id'] ;

            echo "</td><td width='10%'>".$field->getLink();

            echo "</td><td class='center'>";
            Html::showCheckbox(array('name'           => 'is_active_'.$data['id'],
                                     'checked'        => $data['is_active']
                                     ));

            echo "</td><td class='center' width='40%'>";
            echo "<input type='text' size='60' maxlength=1000 name='formula_".$data['id']."' value='".htmlentities($data['formula'], ENT_QUOTES)."'>"  ;

            echo "</td><td class='center'>";
            Html::showCheckbox(array('name'           => 'show_mandatory_'.$data['id'],
                                    'checked'        => $data['show_mandatory']
                                    ));
            echo "</td><td class='center' width='40%'>";
            echo "<input type='text' size='40' maxlength=1000 name='show_mandatory_if_".$data['id']."' value='".htmlentities($data['show_mandatory_if'], ENT_QUOTES)."'>"  ;
            echo "</td></tr>";
         }
         echo $header_begin.$header_bottom.$header_end;
         echo "</table>";

         if ($canedit) {
            echo "<br>";
            echo Html::submit(_x('button','Save'), array('name' => 'update'));
         }

         echo "</div>";
         Html::closeForm();
         echo "<div>";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<th>".__('Formula guidelines','formvalidation')."</th>";
         echo "</tr>";
         echo "<tr><td>";
         echo "<a href='https://github.com/tomolimo/formvalidation/wiki/Formulas' target='_new'>formvalidation/wiki/Formulas</a>" ;
         echo "</td></tr>" ;
         echo "</table>";
         echo "</div>";
      } else {
         echo "<p class='center b'>".__('No item found')."</p>";
      }
   }


   function defineTabs($options=array()) {

//        $ong = array('empty' => $this->getTypeName(1));
        $ong = array();
        $this->addDefaultFormTab($ong);
        //$this->addStandardTab(__CLASS__, $ong, $options);

        //$this->addStandardTab('PluginFormvalidationField', $ong, $options);
        //$this->addStandardTab('PluginProcessmakerProcess_Profile', $ong, $options);

        return $ong;
    }

    function showForm ($ID, $options=array('candel'=>false)) {
      global $DB, $CFG_GLPI, $LANG;

      if ($ID > 0) {
         $this->check($ID,READ);
      }

      $canedit = $this->can($ID,UPDATE);
      $options['canedit'] = $canedit ;

      $this->initForm($ID, $options);

      // TODO
      //$this->showTabs($options);

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__("Name")."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=250 name='name' value='".htmlentities($this->fields["name"], ENT_QUOTES)."'></td>";
      echo "<td rowspan='4' class='middle'>".__("Comments")."&nbsp;:</td>";
      echo "<td class='center middle' rowspan='4'><textarea cols='40' rows='4' name='comment' >".htmlentities($this->fields["comment"], ENT_QUOTES)."</textarea></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("Active")."&nbsp;:</td>";
      echo "<td>" ;
      Html::showCheckbox(array('name'           => 'is_active',
                                     'checked'        => $this->fields['is_active']
                                     ));
      echo "</td></tr>";

      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__('Value CSS selector', 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=200 name='css_selector_value' value='".htmlentities($this->fields["css_selector_value"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__('Alternative value CSS selector', 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=200 name='css_selector_altvalue' value='".htmlentities($this->fields["css_selector_altvalue"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__('Error sign CSS selector', 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=200 name='css_selector_errorsign' value='".htmlentities($this->fields["css_selector_errorsign"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("Force mandatory sign", 'formvalidation')."&nbsp;:</td>";
      echo "<td>" ;
      Html::showCheckbox(array('name'           => 'show_mandatory',
                                    'checked'        => $this->fields["show_mandatory"]
                                    ));

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("Mandatory sign formula", 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=1000 name='show_mandatory_if' value='". htmlentities($this->fields["show_mandatory_if"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__('Mandatory sign CSS selector', 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=200 name='css_selector_mandatorysign' value='".htmlentities($this->fields["css_selector_mandatorysign"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("Validation formula", 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=1000 name='formula' value='".htmlentities($this->fields["formula"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr><td>&nbsp;";
      echo "</td></tr>";

      echo "<tr>";
      echo "</tr>";

      $this->showFormButtons($options );
      //$this->addDivForTabs();

    }

    function prepareInputForUpdate($input) {
       if(isset( $input['css_selector_value'] ) ) {
          $input['css_selector_value'] = html_entity_decode( $input['css_selector_value']) ;
       }
       if(isset( $input['css_selector_altvalue'] ) ) {
          $input['css_selector_altvalue'] = html_entity_decode( $input['css_selector_altvalue']) ;
       }
       if(isset( $input['css_selector_errorsign'] ) ) {
          $input['css_selector_errorsign'] = html_entity_decode( $input['css_selector_errorsign']) ;
       }
       if(isset( $input['css_selector_mandatorysign'] ) ) {
          $input['css_selector_mandatorysign'] = html_entity_decode( $input['css_selector_mandatorysign']) ;
       }
       return $input ;
    }


}

