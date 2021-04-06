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

use Glpi\Event;

include '../inc/includes.php';
Session ::checkRight('itiltemplate', UPDATE);

if (!isset($itiltype)) {
   Html::displayErrorAndDie("Missing ITIL type");
}

if (!isset($fieldtype)) {
   Html::displayErrorAndDie("Missing field type");
}

$item_class = $itiltype . 'Template' . $fieldtype . 'Field';
$item = new $item_class;

if ($fieldtype == 'Predefined') {
   $itil_type = $item_class::$itiltype;
   $item_field = getForeignKeyFieldForItemType($itil_type::getItemLinkClass());
   if (isset($_POST[$item_field]) && isset($_POST['add_items_id'])) {
      $_POST[$item_field] = $_POST[$item_field]."_".$_POST['add_items_id'];
   }
}

if (isset($_POST["add"]) || isset($_POST['massiveaction'])) {
   $item->check(-1, UPDATE, $_POST);

   if ($item->add($_POST)) {
      switch ($fieldtype) {
         case 'Hidden':
            $fieldtype_name = __('hidden');
            break;
         case 'Mandatory':
            $fieldtype_name = __('mandatory');
            break;
         case 'Predefined':
            $fieldtype_name = __('predefined');
            break;

      }

      Event::log(
         $_POST[$item::$items_id],
         strtolower($item::$itemtype),
         4,
         "maintain",
         sprintf(
            //TRANS: %1$s is the user login, %2$s the field type
            __('%1$s adds %2$s field'),
            $_SESSION["glpiname"],
            $fieldtype_name
         )
      );
   }
   Html::back();
}

Html::displayErrorAndDie("lost");
