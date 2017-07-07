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
class PluginFormvalidationForm extends CommonDBTM {

   static $rightname = 'entity';

    /**
     * Summary of getSearchOptions
     * @return mixed
     */
    function getSearchOptions() {
        global $LANG;

        $tab = array();

        $tab['common'] = __('Form','formvalidation');

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

        $tab[800]['table']               = 'glpi_plugin_formvalidation_pages';
        $tab[800]['field']               = 'name';
        $tab[800]['linkfield']           = 'pages_id';
        $tab[800]['name']                = __('Page', 'formvalidation');
        $tab[800]['massiveaction']       = false;
        $tab[800]['datatype']            = 'dropdown';

        return $tab;
    }



    static function getTypeName($nb=0) {
        global $LANG;

        if ($nb>1) {
           return __('Forms','formvalidation');
        }
        return __('Form','formvalidation');
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
             case 'PluginFormvalidationPage' :
                if ($_SESSION['glpishow_count_on_tabs']) {
                   $nb = countElementsInTable('glpi_plugin_formvalidation_forms',
                                              "`pages_id` = '".$item->getID()."'");
                }
                return self::createTabEntry(PluginFormvalidationForm::getTypeName(Session::getPluralNumber()), $nb);

             case 'PluginFormvalidationForm' :
                //if ($_SESSION['glpishow_count_on_tabs']) {
                //   $nb = countElementsInTable('glpi_plugin_formvalidation_forms',
                //                              "`pages_id` = '".$item->getID()."'");
                //}
                return PluginFormvalidationForm::getTypeName(Session::getPluralNumber());
          }
       }
       return '';
    }


    /**
     * @since version 0.85
     **/
    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

        self::showForPage($item);

        return true;
        }


    /**
     * Summary of getDataForPage
     * @param PluginFormvalidationPage $page
     * @param mixed $members
     * @param mixed $ids
     * @param mixed $crit
     * @param mixed $tree
     * @return mixed
     */
    static function getDataForPage(PluginFormvalidationPage $page, &$members, &$ids, $crit='', $tree=0) {
       global $DB;

       // Entity restriction for this group, according to user allowed entities
       //if ($page->fields['is_recursive']) {
       //   $entityrestrict = getSonsOf('glpi_entities', $page->fields['entities_id']);

       //   // active entity could be a child of object entity
       //   if (($_SESSION['glpiactive_entity'] != $page->fields['entities_id'])
       //       && in_array($_SESSION['glpiactive_entity'], $entityrestrict)) {
       //      $entityrestrict = getSonsOf('glpi_entities', $_SESSION['glpiactive_entity']);
       //   }
       //} else {
          $entityrestrict = $page->fields['entities_id'];
       //}

       //if ($tree) {
       //   $restrict = "IN (".implode(',', getSonsOf('glpi_groups', $page->getID())).")";
       //} else {
          $restrict = "='".$page->getID()."'";
       //}

       // All group members
       $query = "SELECT DISTINCT `glpi_plugin_formvalidation_forms`.`id`,
                       `glpi_plugin_formvalidation_forms`.`id` AS linkID,
                       `glpi_plugin_formvalidation_forms`.`name`,
                       `glpi_plugin_formvalidation_forms`.`pages_id`,
                       `glpi_plugin_formvalidation_forms`.`css_selector`,
                       `glpi_plugin_formvalidation_forms`.`is_active`,
                       `glpi_plugin_formvalidation_forms`.`is_createitem`,
                       `glpi_plugin_formvalidation_forms`.`name`
                FROM `glpi_plugin_formvalidation_forms`
                WHERE `glpi_plugin_formvalidation_forms`.`pages_id` $restrict
                ORDER BY `glpi_plugin_formvalidation_forms`.`id`";

       $result = $DB->query($query);

       if ($DB->numrows($result) > 0) {
          while ($data=$DB->fetch_assoc($result)) {
             // Add to display list, according to criterion
             if (empty($crit) || $data[$crit]) {
                $members[] = $data;
             }
             // Add to member list (member of sub-group are not member)
             if ($data['pages_id'] == $page->getID()) {
                $ids[]  = $data['id'];
             }
          }
       }

       return $entityrestrict;
    }

    /**
     * Show forms of a page
     *
    * @param $page  PluginFormvalidationPage object: the page
     **/
    static function showForPage(PluginFormvalidationPage $page) {
       global $DB, $LANG, $CFG_GLPI;

       $ID = $page->getID();
       if (!PluginFormvalidationForm::canView()
           || !$page->can($ID, READ)) {
          return false;
       }

       // Have right to manage members
       $canedit = self::canUpdate();
       $rand    = mt_rand();
       $form    = new PluginFormvalidationForm();
       $crit    = Session::getSavedOption(__CLASS__, 'criterion', '');
       $tree    = Session::getSavedOption(__CLASS__, 'tree', 0);
       $used    = array();
       $ids     = array();

       // Retrieve member list
       $entityrestrict = self::getDataForPage($page, $used, $ids, $crit, $tree);

       //if ($canedit) {
       //   self::showAddUserForm($page, $ids, $entityrestrict, $crit);
       //}

       // Mini Search engine
       //echo "<table class='tab_cadre_fixe'>";
       //echo "<tr class='tab_bg_1'><th colspan='2'>".PluginFormvalidationForm::getTypeName(Session::getPluralNumber())."</th></tr>";
       //echo "<tr class='tab_bg_1'><td class='center'>";
       //echo _n('Criterion', 'Criteria', 1)."&nbsp;";
       //$crits = array('name'      => __('Name'),
       //               'is_active' => __('Active'));
       //Dropdown::showFromArray('crit', $crits,
       //                        array('value'               => $crit,
       //                              'on_change'           => 'reloadTab("start=0&criterion="+this.value)',
       //                              'display_emptychoice' => true));
       ////if ($page->haveChildren()) {
       ////   echo "</td><td class='center'>".__('Child groups');
       ////   Dropdown::showYesNo('tree', $tree, -1,
       ////                       array('on_change' => 'reloadTab("start=0&tree="+this.value)'));
       ////} else {
       //   $tree = 0;
       ////}
       //echo "</td></tr></table>";

       $number = count($used);
       //$start  = (isset($_GET['start']) ? intval($_GET['start']) : 0);
       //if ($start >= $number) {
          $start = 0;
       //}

       // Display results
       if ($number) {
          echo "<div class='spaced'>";
          //Html::printAjaxPager(sprintf(__('%1$s'), PluginFormvalidationForm::getTypeName(Session::getPluralNumber())),
          //                     $start, $number);

          Session::initNavigateListItems('PluginFormvalidationForm',
                               //TRANS : %1$s is the itemtype name,
                               //        %2$s is the name of the item (used for headings of a list)
                                         sprintf(__('%1$s = %2$s'),
                                                 PluginFormvalidationPage::getTypeName(1), $page->getName()));

          if ($canedit) {
             Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
             $massiveactionparams = array('num_displayed'    => min($number-$start,
                                                                    $_SESSION['glpilist_limit']),
                                          'container'        => 'mass'.__CLASS__.$rand);
             Html::showMassiveActions($massiveactionparams);
          }

          echo "<table class='tab_cadre_fixehov'>";

          $header_begin  = "<tr>";
          $header_top    = '';
          $header_bottom = '';
          $header_end    = '';

          if ($canedit) {
             $header_begin  .= "<th width='10'>";
             $header_top    .= Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
             $header_bottom .= Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand);
             $header_end    .= "</th>";
          }
          $header_end .= "<th>".__('ID')."</th>";
          $header_end .= "<th>".PluginFormvalidationForm::getTypeName(1)."</th>";
          //if ($tree) {
          //   $header_end .= "<th>".PluginFormvalidationPage::getTypeName(1)."</th>";
          //}
          $header_end .= "<th>".__('CSS selector', 'formvalidation')."</th>";
          $header_end .= "<th>".__('Active')."</th>";
          $header_end .= "<th>".__('Item creation', 'formvalidation')."</th></tr>";
          echo $header_begin.$header_top.$header_end;

          //$tmpgrp = new PluginFormvalidationPage();

          for ($i=$start, $j=0 ; ($i < $number) && ($j < $_SESSION['glpilist_limit']) ; $i++, $j++) {
             $data = $used[$i];
             $form->getFromDB($data["id"]);
             Session::addToNavigateListItems('PluginFormvalidationForm', $data["id"]);

             echo "\n<tr class='tab_bg_1'>";
             if ($canedit) {
                echo "<td width='10'>";
                Html::showMassiveActionCheckBox(__CLASS__, $data["linkID"]);
                echo "</td>";
             }

             echo "<td class='center'>";
             echo $data['id'] ;

             echo "</td><td>".$form->getLink();

            echo "</td><td >";
            echo $data['css_selector'] ;
            echo "</td><td class='center'>";
             Html::showCheckbox(array( 'id'        => 'isformactive',
                                    'name'           => 'is_active',
                                    'checked'        => $data["is_active"],
                                    'readonly' => true

                                    ));

             echo "</td><td class='center'>";
             Html::showCheckbox(array( 'id'        => 'isformitemcreation',
                                    'name'           => 'is_createitem',
                                    'checked'        => $data["is_createitem"],
                                    'readonly' => true

                                    ));

             echo "</td></tr>";
          }
          echo $header_begin.$header_bottom.$header_end;
          echo "</table>";
          if ($canedit) {
             $massiveactionparams['ontop'] = false;
             Html::showMassiveActions($massiveactionparams);
             Html::closeForm();
          }
          //Html::printAjaxPager(sprintf(__('%1$s'), PluginFormvalidationForm::getTypeName(Session::getPluralNumber())),
          //                     $start, $number);

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

        $this->addStandardTab('PluginFormvalidationField', $ong, $options);
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

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__("Name")."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=250 name='name' value='".htmlentities($this->fields["name"], ENT_QUOTES)."'></td>";
      echo "<td rowspan='5' class='middle'>".__("Comments")."&nbsp;:</td>";
      echo "<td class='center middle' rowspan='5'><textarea cols='40' rows='5' name='comment' >".htmlentities($this->fields["comment"], ENT_QUOTES)."</textarea></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("Active")."&nbsp;:</td>";
      echo "<td>" ;
      Html::showCheckbox(array('name'           => 'is_active',
                                 'checked'        => $this->fields["is_active"]
                                 ));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("For item creation")."&nbsp;:</td>";
      echo "<td>" ;
      Html::showCheckbox(array('name'           => 'is_createitem',
                                 'checked'        => $this->fields["is_createitem"]
                                 ));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("CSS selector", 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=200 name='css_selector' value='".htmlentities($this->fields["css_selector"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("Formula (left empty to default to <b><i>true</i></b>)", 'formvalidation')."&nbsp;:</td>";
      echo "<td><input type='text' size='50' maxlength=1000 name='formula' value='".htmlentities($this->fields["formula"], ENT_QUOTES)."'></td>";
      echo "</tr>";

      echo "<tr><td>&nbsp;";
      echo "</td></tr>";

      echo "<tr>";
      echo "</tr>";

      $this->showFormButtons($options );
      //$this->addDivForTabs();

    }


    /**
     * Actions done after the PURGE of the item in the database
     *
     * @return nothing
     **/
    function post_purgeItem() {
       global $DB;
       // as it is purged, then need to purge the associated fields
       // get list of fields to purge them
       $fld = new PluginFormvalidationField ;
       $query = "SELECT * FROM ".$fld->getTable()." WHERE forms_id=".$this->getID();
       foreach( $DB->request($query) as $fldkey => $row ) {
          $fld->delete($row, 1);
       }
    }


}

