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


/**
 * @since 0.84
**/
class HTMLTableSubHeader extends HTMLTableHeader {

   // The headers of each column
   private $header;


   /**
    * @param HTMLTableSuperHeader $header
    * @param string               $name
    * @param string               $content
    * @param HTMLTableHeader      $father
   **/
   function __construct(HTMLTableSuperHeader $header, $name, $content,
                        HTMLTableHeader $father = null) {

      $this->header = $header;
      parent::__construct($name, $content, $father);
      $this->copyAttributsFrom($this->header);
   }


   function isSuperHeader() {
      return false;
   }


   function getHeaderAndSubHeaderName(&$header_name, &$subheader_name) {

      $header_name    = $this->header->getName();
      $subheader_name = $this->getName();
   }


   function getCompositeName() {
      return $this->header->getCompositeName().$this->getName();
   }


   protected function getTable() {
      return $this->header->getTable();
   }


   function getHeader() {
      return $this->header;
   }


   /**
    * @param $numberOfSubHeaders
   **/
   function updateColSpan($numberOfSubHeaders) {
      $this->setColSpan($this->header->getColSpan() / $numberOfSubHeaders);
   }
}
