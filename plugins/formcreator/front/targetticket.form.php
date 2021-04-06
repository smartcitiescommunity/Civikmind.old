<?php
/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @copyright Copyright © 2011 - 2021 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

include ('../../../inc/includes.php');

Session::checkRight('entity', UPDATE);

// Check if plugin is activated...
$plugin = new Plugin();
if (!$plugin->isActivated('formcreator')) {
   Html::displayNotFoundError();
}
$targetticket = new PluginFormcreatorTargetTicket();

// Edit an existing target ticket
if (isset($_POST['update'])) {
   $targetticket->update($_POST);
   Html::back();

} else if (isset($_POST['actor_role'])) {
   $id          = (int) $_POST['id'];
   $actor_value = isset($_POST['actor_value_' . $_POST['actor_type']])
                  ? $_POST['actor_value_' . $_POST['actor_type']]
                  : '';
   $use_notification = ($_POST['use_notification'] == 0) ? 0 : 1;
   $targetTicket_actor = new PluginFormcreatorTarget_Actor();
   $targetTicket_actor->add([
      'itemtype'         => $targetticket->getType(),
      'items_id'         => $id,
      'actor_role'       => $_POST['actor_role'],
      'actor_type'       => $_POST['actor_type'],
      'actor_value'      => $actor_value,
      'use_notification' => $use_notification,
   ]);
   Html::back();

} else if (isset($_GET['delete_actor'])) {
   $targetTicket_actor = new PluginFormcreatorTarget_Actor();
   $targetTicket_actor->delete([
      'itemtype' => $targetticket->getType(),
      'items_id' => $id,
      'id'       => (int) $_GET['delete_actor']
   ]);
   Html::back();

   // Show target ticket form
} else {
   Html::header(
      __('Form Creator', 'formcreator'),
      $_SERVER['PHP_SELF'],
      'admin',
      'PluginFormcreatorForm'
   );

   $itemtype = PluginFormcreatorTargetTicket::class;
   $targetticket->getFromDB((int) $_REQUEST['id']);
   $form = $targetticket->getForm();

   $_SESSION['glpilisttitle'][$itemtype] = sprintf(
      __('%1$s = %2$s'),
      $form->getTypeName(1), $form->getName()
   );
   $_SESSION['glpilisturl'][$itemtype]   = $form->getFormURL()."?id=".$form->getID();

   $targetticket->display($_REQUEST);

   Html::footer();
}
