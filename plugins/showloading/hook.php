<?php

function plugin_showloading_install() {

   $default = [
      'color' => '', //Auto
      'theme' => 'pace-theme-flash',
   ];

   $current = Config::getConfigurationValues('showloading');

   foreach ($default as $key => $value) {
      if (!isset($current[$key])) {
         $current[$key] = $value;
      }
   }

   Config::setConfigurationValues('showloading', $current);
   return true;
}

function plugin_showloading_uninstall() {

   $config = new Config();
   $rows = $config->find("`context` LIKE 'showloading'");

   foreach ($rows as $id => $row) {
      $config->delete(['id' => $id]);
   }

   return true;
}
