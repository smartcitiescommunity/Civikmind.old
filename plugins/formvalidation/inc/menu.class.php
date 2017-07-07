<?php
class PluginFormvalidationMenu extends CommonGLPI {
   static $rightname = 'entity';

   static function getMenuName() {
      return __("Form Validations", "formvalidation");
   }

   static function getMenuContent() {

      if (!Session::haveRight('entity', READ)) {
         return;
      }

      $front_page = "/plugins/formvalidation/front";
      $menu = array();
      $menu['title'] = self::getMenuName();
      $menu['page']  = "$front_page/page.php";
 
      $itemtypes = array('PluginFormvalidationPage' => 'formvalidationpage',
                         'PluginFormvalidationForm' => 'formvalidationform',
                         'PluginFormvalidationField' => 'formvalidationfield');

      foreach ($itemtypes as $itemtype => $option) {
         $menu['options'][$option]['title']           = $itemtype::getTypeName(Session::getPluralNumber());
         switch( $itemtype ) {
            case 'PluginFormvalidationPage':
               $menu['options'][$option]['page']            = $itemtype::getSearchURL(false);
               $menu['options'][$option]['links']['search'] = $itemtype::getSearchURL(false);
               if ($itemtype::canCreate()) {
                  $menu['options'][$option]['links']['add'] = $itemtype::getFormURL(false);
               }
               break ;
            case 'PluginFormvalidationForm':
            case 'PluginFormvalidationField':
               $menu['options'][$option]['page']            = $itemtype::getSearchURL(false);
               $menu['options'][$option]['links']['search'] = $itemtype::getSearchURL(false);
               break;
            default :
               $menu['options'][$option]['page']            = PluginFormvalidationPage::getSearchURL(false);
               break ;
         }
         
      }
      return $menu;
   }


}