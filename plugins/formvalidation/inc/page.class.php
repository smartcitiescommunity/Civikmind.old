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
class PluginFormvalidationPage extends CommonDBTM {

   static $rightname = 'entity';

    /**
     * Summary of getSearchOptions
     * @return mixed
     */
    function getSearchOptions() {
       // global $LANG;

        $tab = array();

        $tab['common'] = __('Page', 'formvalidation');

        $tab[1]['table']         = $this->getTable();
        $tab[1]['field']         = 'name';
        $tab[1]['name']          = __('Title');
        $tab[1]['datatype']      = 'itemlink';
        $tab[1]['searchtype']           = 'contains';
        $tab[1]['massiveaction']        = false;
        $tab[1]['itemlink_type'] = $this->getType();

        $tab[3]['table']         = $this->getTable();
        $tab[3]['field']         = 'itemtype';
        $tab[3]['name']          = __("Associated item type");
        $tab[3]['datatype']       = 'itemtypename';

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

        $tab[80]['table']               = 'glpi_entities';
        $tab[80]['field']               = 'completename';
        $tab[80]['name']                = __('Entity');
        $tab[80]['massiveaction']       = false;
        $tab[80]['datatype']            = 'dropdown';

        $tab[86]['table']          = $this->getTable();
        $tab[86]['field']          = 'is_recursive';
        $tab[86]['name']           = __('Child entities');
        $tab[86]['datatype']       = 'bool';

        return $tab;
    }


    static function getTypeName($nb=0) {
        global $LANG;

        if ($nb>1) {
           return __('Pages','formvalidation');
        }
        return __('Page','formvalidation');
    }

    function defineTabs($options=array()) {

//        $ong = array('empty' => $this->getTypeName(1));
        $ong = array();
        $this->addDefaultFormTab($ong);
        //$this->addStandardTab(__CLASS__, $ong, $options);

        $this->addStandardTab('PluginFormvalidationForm', $ong, $options);
        //$this->addStandardTab('PluginProcessmakerProcess_Profile', $ong, $options);

        return $ong;
    }

    function showForm ($ID, $options=array('candel'=>false)) {
      //global $DB;

      if ($ID > 0) {
         $this->check($ID,READ);
      }

      $canedit = $this->can($ID,UPDATE);
      $options['canedit'] = $canedit ;

      $this->initForm($ID, $options);

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__("Name")."&nbsp;:</td><td>";
      //Html::autocompletionTextField($this, "name");
      echo "<input type='text' size='50' maxlength=250 name='name' ".
                  " value=\"".htmlentities($this->fields["name"], ENT_QUOTES)."\">";
      echo "</td>";
      echo "<td rowspan='3' class='middle right'>".__("Comments")."&nbsp;:</td>";
      echo "<td class='center middle' rowspan='3'><textarea cols='45' rows='5' name='comment' >".
      htmlentities($this->fields["comment"], ENT_QUOTES)."</textarea></td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td >".__("Active")."&nbsp;:</td><td>";
      Html::showCheckbox(array('name'           => 'is_active',
                                    'checked'        => $this->fields["is_active"]
                                    ));
      echo "</td></tr>";

      if( version_compare(GLPI_VERSION,'9.1','lt') ) {
         echo "<tr class='tab_bg_1'>";
         echo "<td >".__("Child entities")."&nbsp;:</td><td>";
         Html::showCheckbox(array('name'           => 'is_recursive',
                                       'checked'        => $this->fields["is_recursive"]
                                       ));
         echo "</td></tr>";
      }

      echo "<tr>";
      echo "<td>".__("Associated item type")." : </td>";
      echo "<td>";
      if($ID > 0) {
         $obj = getItemForItemtype($this->fields["itemtype"]);
         echo $obj->getTypeName(1);
      } else {
         Dropdown::showFromArray('itemtype',
         	                     self::getItemtypes(),
            							array('value' => $this->fields["itemtype"],
            								   'rand'  => $rand));
      }
      echo "</td>";
      echo "</tr>";



      $this->showFormButtons($options );
      //$this->addDivForTabs();

    }

    static function getItemtypes() {
       echo '';
       return array(
          __("Assets") => array(
             'Computer'           => _n("Computer", "Computers", 2),
             'Monitor'            => _n("Monitor", "Monitors", 2),
             'Software'           => _n("Software", "Software", 2),
             'NetworkEquipment'   => _n("Network", "Networks", 2),
             'Peripheral'         => _n("Device", "Devices", 2),
             'Printer'            => _n("Printer", "Printers", 2),
             'CartridgeItem'      => _n("Cartridge", "Cartridges", 2),
             'ConsumableItem'     => _n("Consumable", "Consumables", 2),
             'Phone'              => _n("Phone", "Phones", 2)),
          __("Assistance") => array(
             'Ticket'             => _n("Ticket", "Tickets", 2),
             'Problem'            => _n("Problem", "Problems", 2),
             'TicketRecurrent'    => __("Recurrent tickets")),
          __("Management") => array(
             'Budget'             => _n("Budget", "Budgets", 2),
             'Supplier'           => _n("Supplier", "Suppliers", 2),
             'Contact'            => _n("Contact", "Contacts", 2),
             'Contract'           => _n("Contract", "Contracts", 2),
             'Document'           => _n("Document", "Documents", 2)),
          __("Tools") => array(
             'Notes'              => __("Notes"),
             'RSSFeed'            => __("RSS feed")),
          __("Administration") => array(
             'User'               => _n("User", "Users", 2),
             'Group'              => _n("Group", "Groups", 2),
             'Entity'             => _n("Entity", "Entities", 2),
             'Profile'            => _n("Profile", "Profiles", 2)),
          __("Plugins") => array(
             'PluginFormcreatorForm' => "Form Creator"
             )
         );
   }


    /**
     * Actions done after the PURGE of the item in the database
     *
     * @return nothing
     **/
    function post_purgeItem() {
       global $DB;
       // as it is purged, then need to purge the associated forms
       // get list of form to purge them
       $frm = new PluginFormvalidationForm ;
       $query = "SELECT * FROM ".$frm->getTable()." WHERE pages_id=".$this->getID();
       foreach( $DB->request($query) as $frmkey => $row ) {
          $frm->delete($row, 1);
       }

    }


}

