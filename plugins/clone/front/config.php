<?php

include ('../../../inc/includes.php');

$plugin = new Plugin();
	if ($plugin->isActivated("clone")) {

      Html::header('Clone', "", "plugins", "clone");
  
      
$file =  '../../../inc/ticket.class.php'; 

$string = file_get_contents( $file ); 
// poderia ser um string ao invés de file_get_contents().  /(.*).php  js/notify(.*)<

$acha = preg_match('/button.php/', $string, $matches );
   
      echo "<div id='config' class='center here'>
      		<br><p>
            <span style='color:blue; font-weight:bold; font-size:18pt;'>".__('Clone Plugin','clone')."</span> <br><br><p>";
            
      if($acha === 0) {
			echo "<span style='color:red; font-weight:bold; font-size:16pt; margin-bottom:15px;'>".__('Status').": ".__('Disabled')."</span></p>";
			echo "<span>" .__('Before you enable Clone plugin make sure your file <b>inc/ticket.class.php</b> is owned by apache user, usually www-data or wwwrun','clone')."</span>";
			}
		else { 
			echo "<span style='color:green; font-weight:bold; font-size:16pt;'>".__('Status').": "._x('plugin','Enabled')."</span>" ;}
            
                        
		echo" <table border='0' width='200px' style='margin-left: auto; margin-right: auto; margin-bottom: 25px; margin-top:30px;'>
				<tr>            
      		<td><a class='vsubmit' type='submit' onclick=\"window.location.href = 'config.php?opt=ativar';\"> "._x('button','Enable')." </a></td>
      		<td><a class='vsubmit' type='submit' onclick=\"window.location.href = 'config.php?opt=desativar';\"> ".__('Disable')." </a></td>
				</tr>
				</table>
				
				</div>      
      		";


   } else {
      Html::header(__('Setup'),'',"config","plugins");
      echo "<div class='center'><br><br>";
      echo "<img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt='".__s('Warning')."'><br><br>";
      echo "<b>".__('Please activate the plugin', 'clone')."</b></div>";
   }



if(isset($_REQUEST['opt'])) {

$action = $_REQUEST['opt'];

if($action == 'ativar') {

	
//$search = ' if (self::canDelete()) {';  if (self::canUpdate() ) {
$search = 'if (self::canUpdate() ) {';
$replace = 'if (self::canUpdate() ) {' .PHP_EOL. "include_once('../plugins/clone/front/button.php');" ;
file_put_contents('../../../inc/ticket.class.php', str_replace($search, $replace, file_get_contents('../../../inc/ticket.class.php')));

echo "<div id='config' class='center'><h2>";
echo "Plugin  "._x('plugin', 'Enabled')." </h2><br><br><p>
 		</div>";
}


if($action == 'desativar') {
	
$search = "include_once('../plugins/clone/front/button.php');";	
$replace = " ";
file_put_contents('../../../inc/ticket.class.php', str_replace($search, $replace, file_get_contents('../../../inc/ticket.class.php')));

echo "<div id='config' class='center'><h2>";
echo "Plugin  ".__('Disabled')."  </h2><br><br><p>
		</div>";

//header("Location: config.php");

}

}

echo "<div id='config' class='center'>
		<a class='vsubmit' type='submit' onclick=\"window.location.href = '". $CFG_GLPI['root_doc'] ."/front/plugin.php';\" >  ".__('Back')." </a> 
		</div>";

//Html::footer();

?>
