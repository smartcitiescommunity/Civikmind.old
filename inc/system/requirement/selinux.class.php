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

namespace Glpi\System\Requirement;

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * @since 9.5.0
 */
class SeLinux extends AbstractRequirement {

   public function __construct() {
      $this->title = __('Check SELinux configuration');
      $this->optional = true;
   }

   protected function check() {
      $is_slash_separator = DIRECTORY_SEPARATOR == '/';
      $are_bin_existing = file_exists('/usr/sbin/getenforce') && file_exists('/usr/sbin/getsebool');
      $are_functions_existing = function_exists('selinux_is_enabled')
         && function_exists('selinux_getenforce')
         && function_exists('selinux_get_boolean_active');

      if (!$is_slash_separator || (!$are_bin_existing && !$are_functions_existing)) {
         // This is not a SELinux system
         $this->out_of_context = true;
         $this->validated = false;
         return;
      }

      if (function_exists('selinux_is_enabled') && function_exists('selinux_getenforce')) {
         // Use https://pecl.php.net/package/selinux
         if (!selinux_is_enabled()) {
            $mode = 'disabled';
         } else {
            $mode = selinux_getenforce();
            // Make it human readable, with same output as the command
            if ($mode == 1) {
               $mode = 'enforcing';
            } else if ($mode == 0) {
               $mode = 'permissive';
            }
         }
      } else {
         $mode = strtolower(exec('/usr/sbin/getenforce'));
      }
      if (!in_array($mode, ['enforcing', 'permissive', 'disabled'])) {
         $mode = 'unknown';
      }

      //TRANS: %s is mode name (Permissive, Enforcing, Disabled or Unknown)
      $this->title = sprintf(__('SELinux mode is %s'), ucfirst($mode));

      if ('enforcing' !== $mode) {
         $this->validated = false;
         $this->validation_messages[] = __('For security reasons, SELinux mode should be Enforcing.');
         return;
      }

      // No need to check file context as DirectoryWriteAccess requirements will show issues

      $bools = [
         'httpd_can_network_connect',
         'httpd_can_network_connect_db',
         'httpd_can_sendmail',
      ];

      $has_missing_boolean = false;

      foreach ($bools as $bool) {
         if (function_exists('selinux_get_boolean_active')) {
            $state = selinux_get_boolean_active($bool);
            if ($state == 1) {
               $state = 'on';
            } else if ($state == 0) {
               $state = 'off';
            }
         } else {
            // command result is something like "httpd_can_network_connect --> on"
            $state = preg_replace(
               '/^.*(on|off)$/',
               '$1',
               strtolower(exec('/usr/sbin/getsebool ' . $bool))
            );
         }
         if (!in_array($state, ['on', 'off'])) {
            $state = 'unknown';
         }

         if ('on' !== $state) {
            $has_missing_boolean = true;
            $this->validation_messages[] = sprintf(
               __('SELinux boolean %s is %s, some features may require this to be on.'),
               $bool,
               $state
            );
         }
      }

      $this->validated = !$has_missing_boolean;

      if (!$has_missing_boolean) {
         $this->validation_messages[] = __('SELinux configuration is OK.');
      }
   }
}
