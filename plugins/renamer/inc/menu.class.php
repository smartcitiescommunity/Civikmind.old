<?php

/*
------------------------------------------------------------------------
GLPI Plugin Renamer
Copyright (C) 2014 by the GLPI Plugin Renamer Development Team.

https://forge.indepnet.net/projects/mantis
------------------------------------------------------------------------

LICENSE

This file is part of GLPI Plugin Renamer project.

GLPI Plugin Renamer is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

GLPI Plugin Renamer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI Plugin Renamer. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------------

@package   GLPI Plugin Renamer
@author    Stanislas Kita (teclib')
@co-author François Legastelois (teclib')
@co-author Le Conseil d'Etat
@copyright Copyright (c) 2014 GLPI Plugin Renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://forge.indepnet.net/projects/mantis
@since     2014

------------------------------------------------------------------------
*/

class PluginRenamerMenu extends CommonGLPI {
   
   static function getTypeName($nb = 0) {
      return __('Renamer', 'renamer'); //useless ?
   }
   
   static function getMenuName() {
      return __('Renamer', 'renamer');
   }
   
   static function getMenuContent() {
      global $CFG_GLPI;
      $menu          = array();
      $menu['title'] = self::getMenuName();
      $menu['page']  = '/plugins/renamer/front/renamer.form.php';
      
      if (Session::haveRight('config', READ)) {
         
         $menu['options']['model']['title']           = self::getTypeName();
         $menu['options']['model']['page']            = Toolbox::getItemTypeSearchUrl('PluginRenamerRenamer', false);
         $menu['options']['model']['links']['search'] = Toolbox::getItemTypeSearchUrl('PluginRenamerRenamer', false);
         
         if (Session::haveRight('config', UPDATE)) {
            $menu['options']['model']['links']['add'] = Toolbox::getItemTypeFormUrl('PluginRenamerRenamer', false);
         }
         
      }
      
      return $menu;
   }
}
