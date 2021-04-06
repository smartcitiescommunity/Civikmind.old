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

include ('../inc/includes.php');

Session::checkRight("link", READ);

if (isset($_GET["lID"])) {
   $iterator = $DB->request([
      'SELECT' => ['id', 'link', 'data'],
      'FROM'   => 'glpi_links',
      'WHERE'  => [
         'id' => $_GET['lID']
      ]
   ]);

   if (count($iterator) == 1) {
      $current = $iterator->next();
      $file = $current['data'];
      $link = $current['link'];

      if ($item = getItemForItemtype($_GET["itemtype"])) {
         if ($item->getFromDB($_GET["id"])) {
            $content_filename = Link::generateLinkContents($link, $item);
            $content_data     = Link::generateLinkContents($file, $item);

            if (isset($_GET['rank']) && isset($content_filename[$_GET['rank']])) {
               $filename = $content_filename[$_GET['rank']];
            } else {
               // first one (the same for all IP)
               $filename = reset($content_filename);
            }

            if (isset($_GET['rank']) && isset($content_data[$_GET['rank']])) {
               $data = $content_data[$_GET['rank']];
            } else {
               // first one (probably missing arg)
               $data = reset($content_data);
            }
            header("Content-disposition: filename=\"$filename\"");
            $mime = "application/scriptfile";

            header("Content-type: ".$mime);
            header('Pragma: no-cache');
            header('Expires: 0');

            // May have several values due to network datas : use only first one
            echo $data;
         }
      }
   }
}
