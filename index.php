<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2017 Teclib' and contributors.
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


/** @file
* @brief
*/

use Glpi\Event;

//Load GLPI constants
define('GLPI_ROOT', __DIR__);
include (GLPI_ROOT . "/inc/based_config.php");
include_once (GLPI_ROOT . "/inc/define.php");

// Check PHP version not to have trouble
if (version_compare(PHP_VERSION, GLPI_MIN_PHP) < 0) {
   die(sprintf("PHP >= %s required", GLPI_MIN_PHP));
}

define('DO_NOT_CHECK_HTTP_REFERER', 1);

// If config_db doesn't exist -> start installation
if (!file_exists(GLPI_CONFIG_DIR . "/config_db.php")) {
   include_once (GLPI_ROOT . "/inc/autoload.function.php");
   Html::redirect("install/install.php");
   die();

} else {
   $TRY_OLD_CONFIG_FIRST = true;
   include (GLPI_ROOT . "/inc/includes.php");
   $_SESSION["glpicookietest"] = 'testcookie';

   // For compatibility reason
   if (isset($_GET["noCAS"])) {
      $_GET["noAUTO"] = $_GET["noCAS"];
   }

   if (!isset($_GET["noAUTO"])) {
      Auth::redirectIfAuthenticated();
   }
   Auth::checkAlternateAuthSystems(true, isset($_GET["redirect"])?$_GET["redirect"]:"");

   // Send UTF8 Headers
   header("Content-Type: text/html; charset=UTF-8");

   // Start the page
   echo "<!DOCTYPE html>\n";
   echo "<html lang=\"{$CFG_GLPI["languages"][$_SESSION['glpilanguage']][3]}\" class='loginpage'>";
   echo '<head><title>'.__('Civikmind - Ingreso').'</title>'."\n";
   echo '<meta charset="utf-8"/>'."\n";
   echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
   echo '<link rel="shortcut icon" type="images/x-icon" href="'.$CFG_GLPI["root_doc"].
          '/pics/favicon.ico" />';

   // auto desktop / mobile viewport
   echo "<meta name='viewport' content='width=device-width, initial-scale=1'/>";

   // Appel CSS
   echo '<link rel="stylesheet" href="'.$CFG_GLPI["root_doc"].'/css/styles.css" type="text/css" '.
         'media="screen" />';
	echo '<link rel="stylesheet" href="'.$CFG_GLPI["root_doc"].'/css/normalize.css" type="text/css" '.
         'media="screen" />';
	echo '<link rel="stylesheet" href="'.$CFG_GLPI["root_doc"].'/css/overlay.css" type="text/css" '.
         'media="screen" />';
	echo '<link rel="stylesheet" href="'.$CFG_GLPI["root_doc"].'/css/pattern.css" type="text/css" '.
         'media="screen" />';
   // CSS theme link
      echo Html::css("css/palettes/".$CFG_GLPI["palette"].".css");
   // surcharge CSS hack for IE
   echo "<!--[if lte IE 8]>";
   echo Html::css("css/styles_ie.css");
   echo "<![endif]-->";
   echo "<script type='text/javascript' >\n";
   echo "document.documentElement.className = 'js';";
   echo "</script>";
   echo "</head>";

   echo "<body class='demo-6'>";
   
   echo "<div id='firstboxlogin'>";
   echo "<div id='logo_login'></div>";
   echo "<div id='text-login'>";
   echo nl2br(Toolbox::unclean_html_cross_side_scripting_deep($CFG_GLPI['text_login']));
   echo "</div>";

   echo "<div id='boxlogin'>";
   echo "<form action='".$CFG_GLPI["root_doc"]."/front/login.php' method='post'>";

   $_SESSION['namfield'] = $namfield = uniqid('fielda');
   $_SESSION['pwdfield'] = $pwdfield = uniqid('fieldb');
   $_SESSION['rmbfield'] = $rmbfield = uniqid('fieldc');

   // Other CAS
   if (isset($_GET["noAUTO"])) {
      echo "<input type='hidden' name='noAUTO' value='1' />";
   }
   // redirect to ticket
   if (isset($_GET["redirect"])) {
      Toolbox::manageRedirect($_GET["redirect"]);
      echo '<input type="hidden" name="redirect" value="'.$_GET['redirect'].'"/>';
   }
   echo '<p class="login_input">
         <input type="text" name="'.$namfield.'" id="login_name" required="required"
                placeholder="'.__('Login').'" autofocus="autofocus" />
         <span class="login_img"></span>
         </p>';
   echo '<p class="login_input">
         <input type="password" name="'.$pwdfield.'" id="login_password" required="required"
                placeholder="'.__('Password').'"  />
         <span class="login_img"></span>
         </p>';
   //if ($CFG_GLPI["login_remember_time"]) {
   //   echo '<p class="login_input">
   //         <label for="login_remember">
   //                <input type="checkbox" name="'.$rmbfield.'" id="login_remember"
   //                '.($CFG_GLPI['login_remember_default']?'checked="checked"':'').' />
   //         '.__('Remember me').'</label>
   //         </p>';
   //}
   echo '<p class="login_input">
         <input type="submit" name="submit" value="'._sx('button', 'Post').'" class="submit" />
         </p>';

   if ($CFG_GLPI["notifications_mailing"]
       && countElementsInTable('glpi_notifications',
                               "`itemtype`='User'
                                AND `event`='passwordforget'
                                AND `is_active`=1")) {
      echo '<a id="forget" href="front/lostpassword.php?lostpassword=1">'.
             __('Forgotten password?').'</a>';
   }
   Html::closeForm();

   echo "<script type='text/javascript' >\n";
   echo "document.getElementById('login_name').focus();";
   echo "</script>";

   echo "</div>";  // end login box


   echo "<div class='error'>";
   echo "<noscript><p>";
   echo __('You must activate the JavaScript function of your browser');
   echo "</p></noscript>";

   if (isset($_GET['error']) && isset($_GET['redirect'])) {
      switch ($_GET['error']) {
         case 1 : // cookie error
            echo __('You must accept cookies to reach this application');
            break;

         case 2 : // GLPI_SESSION_DIR not writable
            echo __('Checking write permissions for session files');
            break;

         case 3 :
            echo __('Invalid use of session ID');
            break;
      }
   }
   echo "</div>";

   // Display FAQ is enable
   if ($CFG_GLPI["use_public_faq"]) {
      echo '<div id="box-faq">'.
            '<a href="front/helpdesk.faq.php">[ '.__('Access to the Frequently Asked Questions').' ]';
      echo '</a></div><div class="content content--demo-6">
				<div class="hamburger hamburger--demo-6 js-hover">
					<div class="hamburger__line hamburger__line--01">
						<div class="hamburger__line-in hamburger__line-in--01 hamburger__line-in--demo-5"></div>
					</div>
					<div class="hamburger__line hamburger__line--02">
						<div class="hamburger__line-in hamburger__line-in--02 hamburger__line-in--demo-5"></div>
					</div>
					<div class="hamburger__line hamburger__line--03">
						<div class="hamburger__line-in hamburger__line-in--03 hamburger__line-in--demo-5"></div>
					</div>
					<div class="hamburger__line hamburger__line--cross01">
						<div class="hamburger__line-in hamburger__line-in--cross01 hamburger__line-in--demo-5"></div>
					</div>
					<div class="hamburger__line hamburger__line--cross02">
						<div class="hamburger__line-in hamburger__line-in--cross02 hamburger__line-in--demo-5"></div>
					</div>
				</div></div>';
   }

   echo "<div id='display-login'>";
   Plugin::doHook('display_login');
   echo '</div>';


   echo '<div class="demo-title demo-title--demo-6">Civikmind</div>
				<div class="global-menu">
					<div class="global-menu__wrap">
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Facebook"><h1>Facebook</h1></a>
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Linkedin"><h1>Linkedin</h1></a>
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Twitter"><h1>Twitter</h1></a>
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Youtube"><h1>Youtube</h1></a>
					</div>
				</div>'; // end contenu login
   
      echo '<svg class="hidden">
			<symbol id="icon-arrow" viewBox="0 0 24 24">
				<title>arrow</title>
				<polygon points="6.3,12.8 20.9,12.8 20.9,11.2 6.3,11.2 10.2,7.2 9,6 3.1,12 9,18 10.2,16.8 "/>
			</symbol>
			<symbol id="icon-drop" viewBox="0 0 24 24">
				<title>drop</title>
				<path d="M12,21c-3.6,0-6.6-3-6.6-6.6C5.4,11,10.8,4,11.4,3.2C11.6,3.1,11.8,3,12,3s0.4,0.1,0.6,0.3c0.6,0.8,6.1,7.8,6.1,11.2C18.6,18.1,15.6,21,12,21zM12,4.8c-1.8,2.4-5.2,7.4-5.2,9.6c0,2.9,2.3,5.2,5.2,5.2s5.2-2.3,5.2-5.2C17.2,12.2,13.8,7.3,12,4.8z"/><path d="M12,18.2c-0.4,0-0.7-0.3-0.7-0.7s0.3-0.7,0.7-0.7c1.3,0,2.4-1.1,2.4-2.4c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7C15.8,16.5,14.1,18.2,12,18.2z"/>
			</symbol>
			<symbol id="icon-github" viewBox="0 0 32.6 31.8">
				<title>github</title>
				<path d="M16.3,0C7.3,0,0,7.3,0,16.3c0,7.2,4.7,13.3,11.1,15.5c0.8,0.1,1.1-0.4,1.1-0.8c0-0.4,0-1.4,0-2.8c-4.5,1-5.5-2.2-5.5-2.2c-0.7-1.9-1.8-2.4-1.8-2.4c-1.5-1,0.1-1,0.1-1c1.6,0.1,2.5,1.7,2.5,1.7c1.5,2.5,3.8,1.8,4.7,1.4c0.1-1.1,0.6-1.8,1-2.2c-3.6-0.4-7.4-1.8-7.4-8.1c0-1.8,0.6-3.2,1.7-4.4C7.4,10.7,6.8,9,7.7,6.8c0,0,1.4-0.4,4.5,1.7c1.3-0.4,2.7-0.5,4.1-0.5c1.4,0,2.8,0.2,4.1,0.5c3.1-2.1,4.5-1.7,4.5-1.7c0.9,2.2,0.3,3.9,0.2,4.3c1,1.1,1.7,2.6,1.7,4.4c0,6.3-3.8,7.6-7.4,8c0.6,0.5,1.1,1.5,1.1,3c0,2.2,0,3.9,0,4.5c0,0.4,0.3,0.9,1.1,0.8c6.5-2.2,11.1-8.3,11.1-15.5C32.6,7.3,25.3,0,16.3,0z"/>
			</symbol>
			<symbol id="icon-keyboard" viewBox="0 0 100 70">
				<title>keyboard</title>
				<!-- https://thenounproject.com/term/keyboard/783/ by Paul te Kortschot from the Noun Project -->
				<path d="M 60.94,1.83 39.22,1.83 C 36.71,1.83 34.67,3.86 34.67,6.376 L 34.67,28.1 C 34.67,30.61 36.71,32.65 39.22,32.65 L 60.94,32.65 C 63.45,32.65 65.5,30.61 65.5,28.1 L 65.5,6.376 C 65.5,3.86 63.45,1.83 60.94,1.83 Z M 44.79,18.63 50.08,11.74 55.37,18.63 Z" opacity="0.2"/>
				<path d="M 60.86,36.75 39.14,36.75 C 36.63,36.75 34.59,38.79 34.59,41.3 L 34.59,63.02 C 34.59,65.53 36.63,67.57 39.14,67.57 L 60.86,67.57 C 63.38,67.57 65.41,65.53 65.41,63.02 L 65.41,41.3 C 65.42,38.79 63.38,36.75 60.86,36.75 Z M 50.08,57.45 44.79,50.55 55.37,50.55 Z" opacity="0.2" />
				<path d="M 95.45,36.75 73.73,36.75 C 71.22,36.75 69.18,38.79 69.18,41.3 L 69.18,63.02 C 69.18,65.53 71.22,67.57 73.73,67.57 L 95.45,67.57 C 97.97,67.57 100,65.53 100,63.02 L 100,41.3 C 100,38.79 97.97,36.75 95.45,36.75 Z M 83.4,57.45 83.4,46.86 90.3,52.16 Z" />
				<path d="M 26.27,36.75 4.55,36.75 C 2.037,36.75 0,38.79 0,41.3 L 0,63.02 C 0,65.53 2.037,67.57 4.55,67.57 L 26.27,67.57 C 28.78,67.57 30.82,65.53 30.82,63.02 L 30.82,41.3 C 30.82,38.79 28.78,36.75 26.27,36.75 Z M 16.69,57.45 9.79,52.16 16.69,46.86 Z" />
			</symbol>
		</svg> ';
		
   echo '<main class="main main--demo-6">
			<div class="content content--fixed">
			</div>
			
				</div>
				<div class="global-menu">
					<div class="global-menu__wrap">
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Facebook"><h1>Facebook</h1></a>
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Linkedin"><h1>Linkedin</h1></a>
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Twitter"><h1>Twitter</h1></a>
						<a class="global-menu__item global-menu__item--demo-6" href="http://bit.ly/Smart-Cities-Community-Youtube"><h1>Youtube</h1></a>
					</div>
				</div>
				<svg class="shape-overlays" viewBox="0 0 100 100" preserveAspectRatio="none">
					<defs>
						<linearGradient id="gradient1" x1="0%" y1="0%" x2="0%" y2="100%">
							<stop offset="0%"   stop-color="#00c99b"/>
							<stop offset="100%" stop-color="#ff0ea1"/>
						</linearGradient>
						<linearGradient id="gradient2" x1="0%" y1="0%" x2="0%" y2="100%">
							<stop offset="0%"   stop-color="#ffd392"/>
							<stop offset="100%" stop-color="#ff3898"/>
						</linearGradient>
						<linearGradient id="gradient3" x1="0%" y1="0%" x2="0%" y2="100%">
							<stop offset="0%"   stop-color="#110046"/>
							<stop offset="100%" stop-color="#32004a"/>
						</linearGradient>
					</defs>
					<path class="shape-overlays__path"></path>
					<path class="shape-overlays__path"></path>
					<path class="shape-overlays__path"></path>
				</svg>
			</div>
		</main>';
		

   echo '';
   
   if (GLPI_DEMO_MODE) {
      echo "<div class='center'>";
      Event::getCountLogin();
      echo "</div>";
   }
   echo "<div id='footer-login' class='home'>" . Html::getCopyrightMessage() . "</div>";
}
// call cron
if (!GLPI_DEMO_MODE) {
   CronTask::callCronForce();
}

		
		echo "<script src='js/demo.js'>";
		echo "</script>";
		echo "<script src='js/easings.js'>";
		echo "</script>";
		echo "<script src='js/demo6.js'>";
		echo "</script>";
		echo "</body>";
		echo "</html>";
