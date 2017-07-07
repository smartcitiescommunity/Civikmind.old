<?php

class PluginFatherFather extends CommonDBTM {

	public $dohistory = true;

	const TAG_SEARCH_NUM = 38500;

	public function showForm($ID, $options = array()) {

	}

	public static function install(Migration $migration) {
		global $DB;

		$table = getTableForItemType(__CLASS__);

		if (!TableExists($table)) {
			$query = "CREATE TABLE IF NOT EXISTS `$table` (
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`isfather` BOOLEAN NOT NULL DEFAULT '0',
				`items_id` INT(11) NOT NULL DEFAULT '1',
				`itemtype` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_unicode_ci',
				PRIMARY KEY (`id`),
				UNIQUE INDEX `unicity` (`itemtype`, `items_id`, `isfather`)
					)
					COLLATE='utf8_unicode_ci'
					ENGINE=MyISAM";
			$DB->query($query) or die($DB->error());
		}

		if (isIndex($table, "name")) {
			$query = "ALTER TABLE `$table`
				DROP INDEX `name`";
			$DB->query($query) or die($DB->error());
		}

		if (!isIndex($table, "unicity")) {
			$query = "ALTER TABLE `$table`
				ADD UNIQUE INDEX `unicity` (`items_id`, `itemtype`, `isfather`)";
			$DB->query($query) or die($DB->error());
		}


		return true;
	}

	public static function uninstall() {
		$query = "DROP TABLE IF EXISTS `" . getTableForItemType(__CLASS__) . "`";
		return $GLOBALS['DB']->query($query) or die($GLOBALS['DB']->error());
	}

	static function fatherYesNo($options = array()) {
		if ($father_id = self::getFatherFromDB($_REQUEST['id'], $_REQUEST['itemtype'])) {
			Dropdown::showYesNo("father", $father_id['isfather']);
		} else {
			Dropdown::showYesNo("father");
		}
	}

	static function getFatherFromDB($item_id, $itemtype) {
		if ($data_father = getAllDatasFromTable("glpi_plugin_father_fathers", '`items_id` = ' . $item_id . ' and itemtype="' . strtolower($itemtype) . '"')) {
			return reset($data_father);
		}

		return False;
	}

	static function isFather($item_id, $itemtype) {

		$fatherDB = self::getFatherFromDB($item_id, $itemtype);
		return $fatherDB['isfather'];
	}

	static function beforeUpdate($item) {
		$father_ticket = new self();
		if (isset($item->input['plugin_father_father_id'])) {
			if ($father_id = self::getFatherFromDB($item->fields['id'], get_class($item))) {
				$father_ticket->update(array('id' => $father_id['id'],
							'isfather' => $item->input['father']));
			} else {

				$father_ticket->add(array('items_id' => $item->input['plugin_father_father_id'], 'isfather' => $item->input['father'], 'itemtype' => strtolower(get_class($item))));
			}
		}
		if (isset($item->input['father'])) {
			$isfather = $item->input['father'];
		} else {
			$isfather = self::isFather($item->fields['id'], get_class($item));
		}
		$config = new PluginFatherConfig();
		$a=( isset($item->input['status']) && $config->isStatusImpacted($item->input['status']));
		$b=( isset($item->input['solutiontypes_id']) && $config->isSolutionOk());
		$c= $config->isSolutionOk();
		if ((isset($item->input['status']) || isset($item->input['solutiontypes_id'])) && $isfather && !isset($item->input['_massive_father'])) {
			$son_ticket = new Ticket();
			$test_ticket = new Ticket();
			$config = new PluginFatherConfig();
			if ( (isset($item->input['status']) && $config->isStatusImpacted($item->input['status'])) || ( isset($item->input['solutiontypes_id']) )) {
				foreach (Ticket_Ticket::getLinkedTicketsTo($item->fields['id']) as $tick) {

					if (isset($item->input['status']) && $item->input['status'] != $item->fields['status']) {
						$son_update = array('id' => $tick['tickets_id'],
								'status' => $item->input['status'],
								'_auto_update' => true,
								'_massive_father' => true
								);
					}
					elseif (isset($item->input['solutiontypes_id'])) {
						$test_ticket->getFromDB($tick['tickets_id']);
						echo $test_ticket->fields['status'];
						sleep (3);
						if ($config->isSolutionOk() && $test_ticket->fields['status']!=5 && $test_ticket->fields['status']!=6 )
						{
							$son_update = array('id' => $tick['tickets_id'],
									//'status'       => $item->input['status'],
									'solution' => $item->input['solution'],
									'solutiontypes_id' => $item->input['solutiontypes_id'],
									'_auto_update' => true,
									'_massive_father' => true
									);
						}
						else {
							$son_update = array('id' => $tick['tickets_id'],
									'status' => 5,
									'_auto_update' => true,
									'_massive_father' => true);

						}
					}
					if (isset($son_update)){
						$son_ticket->update($son_update);
					}
				}
			}
		}
	}

}
