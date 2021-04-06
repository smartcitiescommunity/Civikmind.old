<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2021 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/// Class PrinterModel
class PrinterModel extends CommonDropdown {

   public $additional_fields_for_dictionnary = ['manufacturer'];


   static function getTypeName($nb = 0) {
      return _n('Printer model', 'Printer models', $nb);
   }


   static function getFieldLabel() {
      return _n('Model', 'Models', 1);
   }


   function cleanDBonPurge() {

      // Temporary solution to clean wrong updated items
      $this->deleteChildrenAndRelationsFromDb(
         [
            CartridgeItem_PrinterModel::class,
         ]
      );
   }

}
