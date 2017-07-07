<?php

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class PluginFatherConfig
 */
class PluginFatherConfig extends CommonDBTM
{

	static $rightname = "plugin_father";

	/**
	 * @param bool $update
	 * @return null|PluginFatherConfig
	 */
	static function getConfig($update = false)
	{
		static $config = null;

		if (is_null($config)) {
			$config = new self();
		}
		if ($update) {
			$config->getFromDB(1);
		}
		return $config;
	}

	/**
	 * PluginFatherConfig constructor.
	 */
	function __construct()
	{
		if (TableExists($this->getTable())) {
			$this->getFromDB(1);
		}
	}


	/**
	 * @param string $interface
	 * @return array
	 */
	function getRights($interface = 'central')
	{

		$values = parent::getRights();

		unset($values[CREATE], $values[DELETE], $values[PURGE]);
		return $values;
	}

	public static function install(Migration $migration) {
		global $DB;


		$table = getTableForItemType(__CLASS__);

		if (!TableExists($table)) {
			$query = "CREATE TABLE `$table` (
				`id` INT(11)    NOT NULL          AUTO_INCREMENT,
				`father_ids` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
				`statut_impacted` TEXT COLLATE utf8_unicode_ci DEFAULT NULL,
				`copy_solution` BOOLEAN NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
					)
					COLLATE='utf8_unicode_ci'
					ENGINE=MyISAM";
			$DB->query($query) or die($DB->error());


			$query = "INSERT INTO `$table` (`id`, `father_ids`) VALUES
				(1, '[\"0\"]');";

			$DB->query($query) or die($DB->error());
		}
		return true;
	}

	public static function uninstall() {

		$query = "DROP TABLE IF EXISTS `" . getTableForItemType(__CLASS__) . "`";
		return $GLOBALS['DB']->query($query) or die($GLOBALS['DB']->error());
	}


	function showForm()
	{

		$this->getFromDB(1);
		echo "<div class='center'>";
		echo "<form name='form' method='post' action='" . $this->getFormURL() . "'>";
		echo "<table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='2'>" . __("Plugin configuration", "father") . "</th></tr>";

		echo "<input type='hidden' name='id' value='1'>";



		echo "<tr class='tab_bg_1'>";
		echo "<td id='show_father_td1' >";
		echo __("item impacted", "father");
		echo "</td>";
		echo "<td >";
		$item_ids = self::getValuesFatherItems();
		Dropdown::showFromArray('father_ids', $item_ids, array('multiple' => true, 'values' => importArrayFromDB($this->fields["father_ids"])));
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>";
		echo __("Status impacted", "father");
		echo "</td>";
		echo "<td >";
		$status_imp = Ticket::getAllStatusArray();
		Dropdown::showFromArray('statut_impacted', $status_imp, array('multiple' => true, 'values' => importArrayFromDB($this->fields["statut_impacted"])));

		echo "</td>";
		echo "</tr>";



		echo "<tr>";
		echo "<td>";
		echo __("Copy solution on all ticket's son", "father");
		echo "</td>";
		echo "<td >";
		Dropdown::showYesNo("copy_solution",$this->fields['copy_solution']);

		echo "</td>";
		echo "</tr>";


		echo "<tr class='tab_bg_1' align='center'>";
		echo "<tr class='tab_bg_1' align='center'>";
		echo "<td colspan='2' align='center'>";
		echo "<input type='submit' name='update' value=\"" . _sx("button", "Post") . "\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table>";
		Html::closeForm();
		echo "</div>";
	}

	function isSolutionOk() {
		if (in_array(5,importArrayFromDB($this->fields['statut_impacted'])) && $this->fields['copy_solution']){
			return True;
		}
		else {
			return False;
		}
	}

	function isStatusImpacted($status)
	{
		return in_array($status,importArrayFromDB($this->fields['statut_impacted']));
	}

	static function getValuesFatherItems()
	{

		$values[0] = __("Ticket");
		//      $values[1] = __("Problem");
		//      $values[2] = __("Change");
		return $values;   
	}
	/**
	 * @return array
	 */
	public function isOk($type)
	{
		return in_array($type,(importArrayFromDB($this->fields['father_ids'])));

	}




}
