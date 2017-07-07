<?php

/*
  ------------------------------------------------------------------------
  Surveyticket
  Copyright (C) 2012-2016 by the Surveyticket plugin Development Team.

  ------------------------------------------------------------------------

  LICENSE

  This file is part of Surveyticket plugin project.

  Surveyticket plugin is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Surveyticket plugin is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with Surveyticket plugin. If not, see <http://www.gnu.org/licenses/>.

 */


/**
 * Update from 1.3 to 1.4
 *
 * @return bool for success (will die for most error)
 * */
function update13to14() {

   $migration = new Migration(14);

   // add field mandatory
   $migration->addField('glpi_plugin_surveyticket_surveyquestions', 'mandatory', 'bool', array('value' => 0));

   $migration->executeMigration();

   return true;
}