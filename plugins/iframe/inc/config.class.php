<?php
class PluginIframeConfig extends CommonGLPI {
   public static function getTypeName($nb = 0) {
      return __("Iframe", 'Iframe');
   }

   static function getMenuContent() {
      global $CFG_GLPI;

      $menu['page'] = "/plugins/iframe/front/config.php";
      $menu['title'] = self::getTypeName();

      $menu['options']['tab']['page']                      = "/plugins/iframe/front/tab.php";
      $menu['options']['tab']['title']                     = __("Edição Links Cadastrados", 'iframe');
      $menu['options']['tab']['links']['add']              = PluginIframeTab::getFormURL(false);
      if (PluginIframeTab::canCreate()) {
         $menu['options']['tab']['links']['search']        = PluginIframeTab::getSearchURL(false);
      }

      $menu['options']['defaulttab']['page']               = "/plugins/iframe/front/defaulttab.php";
      $menu['options']['defaulttab']['title']              = __("Defaults", 'iframe');
      $menu['options']['defaulttab']['links']['add']       = PluginIframeDefaulttab::getFormURL(false);
      if (PluginIframeDefaulttab::canCreate()) {
         $menu['options']['defaulttab']['links']['search'] = PluginIframeDefaulttab::getSearchURL(false);
      }

      return $menu;
   }

   static function showConfigPage() {
      global $DB;

      $query = "SELECT nome, link FROM glpi_plugin_iframeplugin_links";

     

      echo "<div class='custom_center'><ul class='custom_config'>";
      
      echo "<li onclick='location.href=\"tab.php\"'>
         <img src='../pics/add.png' width='32' style='margin-top:9px; margin-left: 4px;' />
         <p><a>".__('Cadastrar Links', 'iframe')."</a></p></li>";
      
      echo "</ul><div class='custom_clear'></div></div>";


      echo "<div class='custom_center frames' style='margin-top:10px;'>";


      if ($result = $DB->query($query)) {
         echo "<table width='100%'>";
         //if ($DB->numrows($result) > 0) {
              while ($data = $DB->fetch_assoc($result)) {
                 
                 echo "<tr style='border-bottom: 1px solid #888;'>
                     <td>$data[nome]</td>
                     <td><a href='fram.php?link=$data[link]'>$data[link]</a></td>
                     </tr>";


              }  
        //}
        echo "</table>";
      }
      else{
         echo "<center>".__("Nenhum link cadastrado...", 'iframe')."</center>";
      }

    
      echo "<div class='custom_clear'></div></div>";



   }

   static function showConfigPage2($link) {


      //echo "<div class='custom_center'>";
      
      echo "<iframe src='".$link."' border='0' width='100%' height='1000'> </iframe>"; 
    
      //echo "<div class='custom_clear'></div></div>";


   }

}