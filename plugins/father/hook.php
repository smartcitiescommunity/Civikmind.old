<?php

// Plugin hook after *Uninstall*
function plugin_uninstall_after_father($item) {
   $fatheritem = new PluginFatherFatherItem();
   $fatheritem->deleteByCriteria(array('itemtype' => $item->getType(),
                                    'items_id' => $item->getID()
                                    )
   );
}
function plugin_father_install() {
   $version   = plugin_version_father();
   $migration = new Migration($version['version']);

   // Parse inc directory
   foreach (glob(dirname(__FILE__).'/inc/*') as $filepath) {
      // Load *.class.php files and get the class name
      if (preg_match("/inc.(.+)\.class.php/", $filepath, $matches)) {
         $classname = 'PluginFather' . ucfirst($matches[1]);
         include_once($filepath);
         // If the install method exists, load it
         if (method_exists($classname, 'install')) {
            $classname::install($migration);
         }
      }
   }

   return True;
}

function plugin_father_uninstall() {
   // Parse inc directory
   foreach (glob(dirname(__FILE__).'/inc/*') as $filepath) {
      // Load *.class.php files and get the class name
      if (preg_match("/inc.(.+)\.class.php/", $filepath, $matches)) {
         $classname = 'PluginFather' . ucfirst($matches[1]);
         include_once($filepath);
         // If the install method exists, load it
         if (method_exists($classname, 'uninstall')) {
            $classname::uninstall();
         }
      }
   }
   return true;
}




////// SEARCH FUNCTIONS ///////() {

// Define search option for types of the plugins
/**
 * @param $itemtype
 * @return array
 */ 
function plugin_father_getAddSearchOptions($itemtype)
{

   $sopt = array();

   if ($itemtype == "Ticket") {

         $rng1 = PluginFatherFather::TAG_SEARCH_NUM;
         $sopt[$rng1]['table'] = 'glpi_plugin_father_fathers';
         $sopt[$rng1]['field'] = 'isfather';
         $sopt[$rng1]['name'] = __('Father type', 'father');
	 $sopt[$rng1]['datatype'] = "bool";
	 $sopt[$rng1]['joinparams']    = array('jointype' => "itemtype_item"); 
         $sopt[$rng1]['massiveaction'] = false;
   return $sopt;

   }
}
