<?php

class PluginShowloadingConfig extends CommonDBTM {

   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      switch (get_class($item)) {
         case 'Config':
            return array(1 => __sl('Show Loading'));
         default:
            return '';
      }
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      switch (get_class($item)) {
         case 'Config':
            $config = new self();
            $config->showFormDisplay();
            break;
      }
      return true;
   }

   static function configUpdate($input) {
      unset($input['_no_history']);
      return $input;
   }

   /**
    * Print the config form for display
    *
    * @return Nothing (display)
    * */
   function showFormDisplay() {
      global $CFG_GLPI;
      if (!Config::canView()) {
         return false;
      }

      $CFG_SHOW_LOADING = Config::getConfigurationValues('showloading');

      $canedit = Session::haveRight(Config::$rightname, UPDATE);
      if ($canedit) {
         echo "<form name='form' action=\"" . Toolbox::getItemTypeFormURL('Config') . "\" method='post'>";
      }
      echo Html::hidden('config_context', ['value' => 'showloading']);
      echo Html::hidden('config_class', ['value' => __CLASS__]);

      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr><th colspan='4'>" . __sl('Show Loading') . " - " . __('General setup') . "</th></tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td width='30%'> " . __sl('Color') . "</td><td  width='20%'>";

      $colors = [
         'black'  => __sl('Black'),
         'blue'   => __sl('Blue'),
         'green'  => __sl('Green'),
         'orange' => __sl('Orange'),
         'pink'   => __sl('Pink'),
         'purple' => __sl('Purple'),
         'red'    => __sl('Red'),
         'silver' => __sl('Silver'),
         'white'  => __sl('White'),
         'yellow' => __sl('Yellow'),
      ];

      Dropdown::showFromArray('color', $colors, [
         'value'               => $CFG_SHOW_LOADING['color'],
         'display_emptychoice' => true,
         'emptylabel'          => __sl('Auto'),
      ]);

      echo "</td><td width='30%'>" . __sl('Theme') . "</td>";
      echo "<td width='20%'>";

      $themes = [
         'pace-theme-barber-shop'      => __sl('Barber Shop'),
         'pace-theme-big-counter'      => __sl('Big Counter'),
         'pace-theme-bounce'           => __sl('Bounce'),
         'pace-theme-center-atom'      => __sl('Center Atom'),
         'pace-theme-center-circle'    => __sl('Center Circle'),
         'pace-theme-center-radar'     => __sl('Center Radar'),
         'pace-theme-center-simple'    => __sl('Center Simple'),
         'pace-theme-corner-indicator' => __sl('Corner Indicator'),
         'pace-theme-fill-left'        => __sl('Fill Left'),
         'pace-theme-flash'            => __sl('Flash'),
         'pace-theme-flat-top'         => __sl('Flat Top'),
         'pace-theme-loading-bar'      => __sl('Loading Bar'),
         'pace-theme-mac-osx'          => __sl('Mac OSX'),
         'pace-theme-material'         => __sl('Material'),
         'pace-theme-minimal'          => __sl('Minimal'),
      ];

      Dropdown::showFromArray('theme', $themes, [
         'value'               => $CFG_SHOW_LOADING['theme'],
         'display_emptychoice' => false,
      ]);

      echo "</td></tr>";

      if ($canedit) {
         echo "<tr class='tab_bg_2'>";
         echo "<td colspan='4' class='center'>";
         echo "<input type='submit' name='update' class='submit' value=\"" . _sx('button', 'Save') . "\">";
         echo "</td></tr>";
      }

      echo "</table></div>";
      Html::closeForm();
   }

}
