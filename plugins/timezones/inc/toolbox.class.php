<?php

/**
 * PluginTimezonesToolbox short summary.
 *
 * extends Toolbox logInFile to save timezone in the log files
 *
 * @version 1.0
 * @author morono
 */
class PluginTimezonesToolbox extends Toolbox {
   /**
    * Log a message in log file
    *
    * @param $name   string   name of the log file
    * @param $text   string   text to log
    * @param $force  boolean  force log in file not seeing use_log_in_files config (false by default)
    **/
   static function logInFile($name, $text, $force=false) {
      global $CFG_GLPI;

      $user = '';
      if (method_exists('Session', 'getLoginUserID')) {
         $user = " [".Session::getLoginUserID().'@'.php_uname('n')."]";
      }

      $ok = true;
      if ((isset($CFG_GLPI["use_log_in_files"]) && $CFG_GLPI["use_log_in_files"])
          || $force) {
         $ok = error_log(date("Y-m-d H:i:s e")."$user\n".$text, 3, GLPI_LOG_DIR."/".$name.".log");
      }

      if (isset($_SESSION['glpi_use_mode'])
          && ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE)
          && isCommandLine()) {
         fwrite(STDERR, $text);
      }
      return $ok;
   }

   /**
    * Specific error handler in Normal mode
    *
    * @param $errno     integer  level of the error raised.
    * @param $errmsg    string   error message.
    * @param $filename  string   filename that the error was raised in.
    * @param $linenum   integer  line number the error was raised at.
    * @param $vars      array    that points to the active symbol table at the point the error occurred.
    **/
   static function userErrorHandlerNormal($errno, $errmsg, $filename, $linenum, $vars) {

      // Date et heure de l'erreur
      $errortype = array(E_ERROR           => 'Error',
                         E_WARNING         => 'Warning',
                         E_PARSE           => 'Parsing Error',
                         E_NOTICE          => 'Notice',
                         E_CORE_ERROR      => 'Core Error',
                         E_CORE_WARNING    => 'Core Warning',
                         E_COMPILE_ERROR   => 'Compile Error',
                         E_COMPILE_WARNING => 'Compile Warning',
                         E_USER_ERROR      => 'User Error',
                         E_USER_WARNING    => 'User Warning',
                         E_USER_NOTICE     => 'User Notice',
                         E_STRICT          => 'Runtime Notice',
                         // Need php 5.2.0
                         4096 /*E_RECOVERABLE_ERROR*/  => 'Catchable Fatal Error',
                         // Need php 5.3.0
                         8192 /* E_DEPRECATED */       => 'Deprecated function',
                         16384 /* E_USER_DEPRECATED */ => 'User deprecated function');
      // Les niveaux qui seront enregistrés
      $user_errors = array(E_USER_ERROR, E_USER_NOTICE, E_USER_WARNING);

      $err = '  *** PHP '.$errortype[$errno] . "($errno): $errmsg\n";
      if (in_array($errno, $user_errors)) {
         $err .= "Variables:".wddx_serialize_value($vars, "Variables")."\n";
      }

      $skip = array('Toolbox::backtrace()');
      if (isset($_SESSION['glpi_use_mode']) && $_SESSION['glpi_use_mode'] == Session::DEBUG_MODE) {
         $hide   = "Toolbox::userErrorHandlerDebug()";
         $skip[] = "Toolbox::userErrorHandlerNormal()";
      } else {
         $hide = "Toolbox::userErrorHandlerNormal()";
      }

      $err .= self::backtrace(false, $hide, $skip);

      // Save error
      self::logInFile("php-errors", $err);

      return $errortype[$errno];
   }
}

