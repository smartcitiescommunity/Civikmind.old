<?php

class PluginProtocolsmanagerConfig extends CommonDBTM {
	
	
	function showFormProtocolsmanager() {
		global $CFG_GLPI, $DB;
		$plugin_conf = self::checkRights();
		if ($plugin_conf == 'w') {	
			self::displayContent();	
		} else {
			echo "<div align='center'><br><img src='".$CFG_GLPI['root_doc']."/pics/warning.png'><br>".__("Access denied")."</div>";
		}
	}
	
	
	static function checkRights() {
		global $DB;
		$active_profile = $_SESSION['glpiactiveprofile']['id'];
		$req = $DB->request('glpi_plugin_protocolsmanager_profiles',
						['profile_id' => $active_profile]);
						
		if ($row = $req->next()) {
			$plugin_conf = $row["plugin_conf"];
		} else {
			$plugin_conf = "";
		}
		return $plugin_conf;
	}
	
	
	static function displayContent() {
		global $CFG_GLPI, $DB;
		
		if (isset($_POST["edit_id"])) {
			
			$edit_id = $_POST['edit_id'];
			$mode = $edit_id;
			
			$req = $DB->request(
				'glpi_plugin_protocolsmanager_config',
				['id' => $edit_id ]);
				
			if ($row = $req->next()) {
				$template_content = $row["content"];
				$template_footer = $row["footer"];
				$template_name = $row["name"];
				$font = $row["font"];
				$fontsize = $row["fontsize"];
				$city = $row["city"];
				$logo = $row["logo"];
				$serial_mode = $row["serial_mode"];
				$orientation = $row["orientation"];
				$breakword = $row["breakword"];
			}
			
		} else {
			$template_content = '';
			$template_footer = '';
			$template_name = '';
			$font = '';
			$fontsize = '9';
			$city = '';
			$mode = 0;
			$serial_mode = 1;
			$orientation = "p";
			$breakword = 1;
		}
		
		$fonts = array('Courier' => 'Courier',
						'Helvetica' => 'Helvetica', 
						'Times' => 'Times',
						'Istok' => 'Istok',
						'UbuntuMono' => 'UbuntuMono',
						'Roboto' => 'Roboto',
						'Liberation-Sans' => 'Liberation-Sans',
						'DroidSerif' => 'DroidSerif',
						'DejaVu Sans' => 'DejaVu Sans');
						
		$fontsizes = array('7' => '7',
							'8' => '8',
							'9' => '9',
							'10' => '10',
							'11' => '11',
							'12' => '12');
						
		$orientations = array('Portrait' => 'Portrait',
							'Landscape' => 'Landscape');
		
		if (!isset($font)) {
			$font='freesans';
		}
	
		echo "<div class='center'>";
		echo "<table class='tab_cadre_fixe' style='width:90%;'>";
		echo "<td style='font-size:12pt; font-weight:bold; text-align:center;'>Protocols Manager - ".__('Templates')."</td>";
		echo "</table>";
		
		echo "<form name='form' action='config.form.php' method='post'  enctype='multipart/form-data'>";
		echo "<input type='hidden' name='MAX_FILE_SIZE' value=1948000>";
		echo "<input type='hidden' name='mode' value='$mode'>";
		echo "<table class='tab_cadre_fixe'>";
		echo "<tr><th colspan='3'>".__('Create')." ".__('template')."</th></tr>";
		echo "<tr><td>".__('Template name')."</td><td colspan='2'><input type='text' name='template_name' style='width:80%;' value='$template_name'></td></tr>";			
		echo "<tr><td>Font</td><td colspan='2'><select name='font' style='width:150px'>";
			foreach($fonts as $code => $fontname) {
				echo "<option value='".$code."' ";
				if ($code == $font) {
					echo " selected";
				}
				echo ">".$fontname."</option>";
			}
		echo "</select></td></tr>";
		
		echo "<tr><td>Font size</td><td colspan='2'><select name='fontsize' style='width:150px'";
			foreach($fontsizes as $fsize => $fsizes) {
				echo "<option value='".$fsize."' ";
				if ($fsize == $fontsize) {
					echo " selected";
				}
				echo ">".$fsizes."</option>";
			}
			
		echo "<tr><td>Word breaking</td><td><input type='radio' name='breakword' value=1 ";
		if ($breakword == 1)
			echo "checked='checked'";
		echo "> On</td>";
		echo "<td><input type='radio' name='breakword' value=0 ";
		if ($breakword == 0)
			echo "checked='checked'";
		echo "> Off</td></tr>";
		
		echo "<tr><td>".__('City')."</td><td colspan='2'><input type='text' name='city' style='width:80%;' value='$city'></td></tr>";
		echo "<tr><td>".__('Content')."</td><td colspan='2' class='middle'><textarea style='width:80%; height:100px;' cols='50' rows'8' name='template_content'>".$template_content."</textarea></td></tr>";
		echo "<tr><td>".__('Footer')."</td><td class='middle' colspan='2'><textarea style='width:80%; height:100px;' cols='45' rows'4' name='footer_text'>".$template_footer."</textarea></td></tr>";
		echo "<tr><td>".__('Orientation')."</td><td colspan='2'><select name='orientation' style='width:150px'>";
			foreach($orientations as $vals => $valname) {
				echo "<option value='".$vals."' ";
				if ($vals == $orientation) {
					echo " selected";
				}
				echo ">".$valname."</option>";
			}	
		echo "</select></td></tr>";
		echo "<tr><td>".__('Serial number')."</td><td><input type='radio' name='serial_mode' value='1' ";
		if ($serial_mode == 1)
			echo "checked='checked'";
		echo "> serial and inventory number in separate columns</td>";
		echo "<td><input type='radio' name='serial_mode' value='2' ";
		if ($serial_mode == 2)
			echo "checked='checked'";
		echo "> serial or inventory number if serial doesn't exists</td></tr>";
		echo "<tr><td>".__('Logo')."</td><td colspan='2'><input type='file' name='logo' accept='image/png, image/jpeg'>";
		if (isset($logo)) {
			$full_img_name = GLPI_ROOT.'/files/_pictures/'.$logo;
			$img_type = pathinfo($full_img_name, PATHINFO_EXTENSION);
			$img_data = file_get_contents($full_img_name);
			$base64 = 'data:image/'.$img_type.';base64,'.base64_encode($img_data);
			$img_delete = true;
			echo "&nbsp&nbsp<img src = ".$base64." style='height:50px; width:auto;'>";
			echo "&nbsp&nbsp<input type='checkbox' name='img_delete' value='$img_delete'>&nbsp ".__('Delete')." ".__('File');
		}
		echo "</td></tr></table>";
		echo "<table class='tab_cadre_fixe'><td style='text-align:right;'><input type='submit' name='save' class='submit'></td>";
		Html::closeForm();
		echo "<form name='cancelform' action='config.form.php' method='post'><td style='text-align:left;'><input type='submit' class='submit' name='cancel' value=".__('Cancel')."></td></table>";
		Html::closeForm();
		echo "</div><br>";
		self::showConfigs();

		
	}
	
	static function saveConfigs() {
		global $DB, $CFG_GLPI;
		
		$template_name = $_POST['template_name'];
		$template_content = $_POST['template_content'];
		$template_footer = $_POST['footer_text'];
		$font = $_POST["font"];
		$fontsize = $_POST["fontsize"];
		$city = $_POST["city"];
		$mode = $_POST["mode"];
		$serial_mode = $_POST["serial_mode"];
		$orientation = $_POST["orientation"];
		$breakword = $_POST["breakword"];
		
		if (isset($_POST['img_delete'])) {
			
			$DB->update('glpi_plugin_protocolsmanager_config', [
					'logo' => $full_img_name
				], [
					'id' => $mode
				]
			);
		}
		
		$full_img_name = self::uploadImage();
		
		//if new template
		if ($mode == 0) {
			
			$DB->insert('glpi_plugin_protocolsmanager_config', [
				'name' => $template_name,
				'content' => $template_content,
				'footer' => $template_footer,
				'logo' => $full_img_name,
				'font' => $font,
				'fontsize' => $fontsize,
				'city' => $city,
				'serial_mode' => $serial_mode,
				'orientation' => $orientation,
				'breakword' => $breakword
				]
			);
		}
		
		//if edit template
		if ($mode != 0) {
			
			//if logo is uploaded
			if (isset($full_img_name)) {
				
				$DB->update('glpi_plugin_protocolsmanager_config', [
						'name' => $template_name,
						'content' => $template_content,
						'footer' => $template_footer,
						'logo' => $full_img_name,
						'font' => $font,
						'fontsize' => $fontsize,
						'city' => $city,
						'serial_mode' => $serial_mode,
						'orientation' => $orientation,
						'breakword' => $breakword
					], [
						'id' => $mode
					]
				);
			} else {
				
				$DB->update('glpi_plugin_protocolsmanager_config', [
						'name' => $template_name,
						'content' => $template_content,
						'footer' => $template_footer,
						'font' => $font,
						'fontsize' => $fontsize,
						'city' => $city,
						'serial_mode' => $serial_mode,
						'orientation' => $orientation,
						'breakword' => $breakword
					], [
						'id' => $mode
					]
				);
			}
		}
			
	}
	

	static function showConfigs() {
		global $DB, $CFG_GLPI;
		$configs = [];
		
		echo "<div class='spaced'>";
		echo "<table class='tab_cadre_fixehov' style='width:90%;'>";
		echo "<tr class='tab_bg_1'><th colspan='3'>".__('Templates')."</th></tr>";
		echo "<tr class='tab_bg_1'><td class='center'><b>".__('Name')."</b></td>";
		echo "<td class='center' colspan=2'><b>".__('Action')."</b></td></tr>";
		
		foreach ($DB->request(
			'glpi_plugin_protocolsmanager_config') as $config_data => $configs) {
				
				echo "<tr class='tab_bg_1'><td class='center'>";
				echo $configs['name'];
				echo "</td>";
				$conf_id = $configs['id'];
				echo "<td class='center' width='7%'>
						<form method='post' action='config.form.php'><input type='hidden' value='$conf_id' name='edit_id'><input type='submit' name='edit' value=".__('Edit')." class='submit'></td>";
						Html::closeForm();	
						echo "<td class='center' width='7%'><form method='post' action='config.form.php'><input type='hidden' value='$conf_id' name='conf_id'><input type='submit' name='delete' value=".__('Delete')." class='submit'></td></tr>";
						Html::closeForm();				
			}
		echo "</table></div>";
	}
	
	static function uploadImage() {
		global $DB, $CFG_GLPI;;
		
		if($_FILES['logo']['name']) {
			
			if($_FILES['logo']['error'] != UPLOAD_ERR_FORM_SIZE) {
			
				if (!$_FILES['logo']['error']) {
					
					if ($_FILES['logo']['type'] == 'image/jpeg' || $_FILES['logo']['type'] == 'image/png' || $_FILES['logo']['type'] == 'image/jpg') {
						
						$img_name = "logo".time();
						$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
						$full_img_name = $img_name.'.'.$ext;
						$img_path = GLPI_ROOT.'/files/_pictures/'.$full_img_name;
						
						move_uploaded_file($_FILES['logo']['tmp_name'], $img_path);
						
						return $full_img_name;
						
					} else {
						Session::addMessageAfterRedirect('Wrong file type. Only .jpg and .png files accepted', 'WARNING', true);
					}
				} else {
					Session::addMessageAfterRedirect(__('Unknown error'), 'WARNING', true);
				}
			} else {
				Session::addMessageAfterRedirect('File size too large', 'WARNING', true);
			}
		}

	}
	
	static function deleteConfigs() {
		global $DB;
		
		$conf_id = $_POST['conf_id'];
		
		$DB->delete(
			'glpi_plugin_protocolsmanager_config', [
				'id' => $conf_id
			]
		);	
		
	}


}

?>