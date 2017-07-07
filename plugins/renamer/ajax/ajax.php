<?php
/*
------------------------------------------------------------------------
GLPI Plugin Renamer
Copyright (C) 2014 by the GLPI Plugin Renamer Development Team.

https://forge.indepnet.net/projects/rennamer
------------------------------------------------------------------------

LICENSE

This file is part of GLPI Plugin Renamer project.

GLPI Plugin Renamer is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

GLPI Plugin Renamer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with GLPI Plugin Renamer. If not, see <http://www.gnu.org/licenses/>.

------------------------------------------------------------------------

@package   GLPI Plugin Renamer
@author    Stanislas Kita (teclib')
@copyright Copyright (c) 2014 GLPI Plugin Renamer Development team
@license   GPLv3 or (at your option) any later version
http://www.gnu.org/licenses/gpl.html
@link      https://forge.indepnet.net/projects/renamer
@since     2014

------------------------------------------------------------------------
*/
include('../../../inc/includes.php');
require_once('../inc/renamer.class.php');
require_once('../lib/PoParser.php');

$lang               = null;
$file               = null;
$GLOBALS['entries'] = null;

if (isset($_POST['action'])) {
   
   $renamer  = new PluginRenamerRenamer();
   $poParser = new PoParser();
   
   switch ($_POST['action']) {
      case 'restoreALanguage':
         if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
            
            $lang = $_POST['lang'];
            $file = $renamer->getLanguageFile($lang);
            
            //remove locale file of glpi
            if (!PluginRenamerInstall::cleanLocalesFileOfGlpi($file)) {
               Session::addMessageAfterRedirect(__("Error while cleaning glpi locale files", "renamer"), false, ERROR);
               return false;
            }
            
            //restore local file of glpi with back og plugin renamer
            if (!PluginRenamerInstall::restoreLocalesFielOfGlpi($file)) {
               Session::addMessageAfterRedirect(__("Error while restore glpi locale files", "renamer"), false, ERROR);
               return false;
            }
            
            //clean table
            $DB->query("DELETE FROM `glpi_plugin_renamer_renamers` 
                        WHERE `glpi_plugin_renamer_renamers`.`lang` = '" . $lang . "'", "renamer");
            Session::addMessageAfterRedirect(__("Restoration Complete", "renamer"), false, INFO);
            
            $renamer->updateTranslation($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
            echo true;
            
         } else {
            Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, INFO);
            echo false;
         }
         
         break;



      case 'updateOverload':
         //checl if right access on glpi locales file
         if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
            
            //get record to update on bdd
            $renamer->getFromDB($_POST['id']);
            
            $lang     = $renamer->fields['lang'];
            $original = $renamer->fields['original'];
            $overload = $renamer->fields['overload'];
            $msgid    = $renamer->fields['msgid'];
            $msgctxt  = $renamer->fields['msgctxt'];
            
            $id      = unserialize(stripslashes(stripslashes($msgid)));
            $context = unserialize(stripslashes(stripslashes($msgctxt)));
            
            $newWord = $_POST['newWord'];
            
            $file     = $renamer->getLanguageFile($lang);
            $entries  = $poParser->parse($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
            $header   = $poParser->getHeaders();
            $newEntry = array();
            
            if (isset($entry['msgctxt'])) {
               
               foreach ($entries as $entry) {
                  if ($entry['msgid'] == $id) {
                     
                     if ($entry['msgctxt'] == $context) {
                        for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                           if ($entry['msgstr'][$i] == $overload) {
                              $entry['msgstr'][$i] = $newWord;
                              $find                = true;
                           }
                        }
                     }
                  }
                  $newEntry[] = $entry;
               }
               
            } else {
               
               foreach ($entries as $entry) {
                  if ($entry['msgid'] == $id) {
                     for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                        if ($entry['msgstr'][$i] == $overload) {
                           $entry['msgstr'][$i] = $newWord;
                           $find                = true;
                        }
                     }
                  }
                  $newEntry[] = $entry;
               }
               
            }
            
            //sauvegarde temporaire du fichier à updaté
            if ($renamer->saveFileIntoTmp($file)) {
               
               $poParser = new PoParser();
               $poParser->setEntries($newEntry);
               $poParser->setHeaders($header);
               $res = $poParser->write($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
               
               //si write ok
               if ($res) {
                  
                  //update translate
                  $renamer->updateTranslation($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
                  //delete tmp file
                  $renamer->removeFileIntoTmp($file);
                  
                  //update bdd entry
                  $input                  = array();
                  $input['id']            = $_POST['id'];
                  $input['overload']      = $newWord;
                  $input['date_overload'] = date("Y-m-d");
                  
                  $renamer->update($input);
                  Session::addMessageAfterRedirect(sprintf(__('\'%1$s\' replaced by \'%2$s\'', "renamer"), $overload, $newWord), false, INFO);
                  
                  echo true;
               } else {
                  //restore locales file from tmp
                  $renamer->restoreFileFromTmp($file);
                  //remove tmp file
                  $renamer->removeFileIntoTmp($file);
                  Session::addMessageAfterRedirect(sprintf(__('Can\'t access to file \'%1$s\'', 'renamer') . $file), false, INFO);
               }
               
            } else {
               Session::addMessageAfterRedirect(sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file), false, INFO);
               return false;
            }
            
         } else {
            Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, INFO);
            echo false;
         }
         
         break;
      
      
      
      case 'getWords':
         
         if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
            
            if (isset($_POST['word']) && $_POST['word'] != "" && isset($_POST['lang']) && $_POST['lang'] != "") {
               
               $file = $renamer->getLanguageFile($_POST['lang']);
               $word = $_POST['word'];
               
               if ($lang == null || ($lang != null && $lang != $_POST['lang'])) {
                  $GLOBALS['entries'] = $entries = $poParser->parse($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
                  $lang               = $_POST['lang'];
               } else {
                  $entries = $GLOBALS['entries'];
               }
               
               $content = "";
               
               $find = false;
               foreach ($entries AS $entry) {
                  if (existExactWord($word, $entry['msgid'], $entry['msgstr'], $file)) {
                     $content .= createTablerow($entry, $word);
                     $find = true;
                     
                     if (!isset($entry['msgctxt']))
                        break;
                  }
               }
               
               if (!$find) {
                  foreach ($entries AS $entry) {
                     if (existWord($word, $entry['msgid'], $entry['msgstr'], $file)) {
                        $content .= createTablerow($entry, $word);
                     }
                  }
               }
               
               echo $content;
            }
            
         } else {
            echo returnWarning();
         }
         
         break;
      
      
      
      case 'overloadWord':
         
         $wordToOverload = unserialize(stripslashes(stripslashes(str_replace("]", "'", $_POST['wordToOverload']))));
         $context        = '';
         
         if ($_POST['msgctxt'] == 'null') {
            $context = null;
         } else {
            $context = unserialize(stripslashes(stripslashes(str_replace("]", "'", $_POST['msgctxt']))));
         }
         
         
         if ($renamer->isAlreadyOverload($_POST['id'], $_POST['wordToOverload'], $_POST['msgctxt'])) {
            echo returnError(__('This Word is already overload ', 'renamer') . $file);
         } else {
            
            $newword = $_POST['word'];
            $lang    = $_POST['lang'];
            
            $id = unserialize(stripslashes(stripslashes($_POST['id'])));
            
            $file     = $renamer->getLanguageFile($lang);
            $entries  = $poParser->parse($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
            $header   = $poParser->getHeaders();
            $newEntry = array();
            
            if ($context == null) {
               //on parcours chaque entry
               foreach ($entries as $entry) {
                  //quand on à  l'id
                  if ($entry['msgid'] == $id) {
                     //on compare chaque msgstr
                     for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                        //quand on le trouve on le modifie
                        if ($entry['msgstr'][$i] == $wordToOverload) {
                           $entry['msgstr'][$i] = $newword;
                           $find                = true;
                        }
                     }
                  }
                  //toute les entry vont dans un nouveau tableau
                  $newEntry[] = $entry;
               }
            } else {
               //on parcours chaque entry
               foreach ($entries as $entry) {
                  //quand on à  l'id
                  if ($entry['msgid'] == $id) {
                     
                     if (isset($entry['msgctxt']) && $entry['msgctxt'] == $context) {
                        //on compare chaque msgstr
                        for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                           //quand on le trouve on le modifie
                           if ($entry['msgstr'][$i] == $wordToOverload) {
                              $entry['msgstr'][$i] = $newword;
                              $find                = true;
                           }
                        }
                     }
                  }
                  //toute les entry vont dans un nouveau tableau
                  $newEntry[] = $entry;
               }
            }
            
            //sauvegarde temporaire du fichier à updaté
            if ($renamer->saveFileIntoTmp($file)) {
               
               $poParser = new PoParser();
               $poParser->setEntries($newEntry);
               $poParser->setHeaders($header);
               $res = $poParser->write($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
               
               //si write ok
               if ($res) {
                  
                  //update tranlate
                  $renamer->updateTranslation($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
                  //del tmp file
                  $renamer->removeFileIntoTmp($file);
                  
                  $input                  = array();
                  $input['msgid']         = $_POST['id'];
                  $input['users_id']      = Session::getLoginUserID();
                  $input['date_overload'] = date("Y-m-d");
                  $input['lang']          = $_POST['lang'];
                  
                  if ($context == null)
                     $input['context'] = $context;
                  else
                     $input['context'] = $_POST['msgctxt'];
                  
                  $input['original'] = $_POST['wordToOverload'];
                  $input['overload'] = $newword;
                  //add bdd entry
                  $renamer->add($input);
                  
                  echo returnSuccess();
               } else {
                  $renamer->restoreFileFromTmp($file);
                  $renamer->removeFileIntoTmp($file);
                  echo returnError(sprintf(__('Can\'t access to file \'%1$s\'', 'renamer') . $file));
               }
               
            } else {
               echo returnError(sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file));
            }
            
         }
         
         break;
      
      
      
      case 'restoreWord':
         
         if (PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
            
            $renamer->getFromDB($_POST['id']);
            
            $lang     = $renamer->fields['lang'];
            $original = $renamer->fields['original'];
            $overload = $renamer->fields['overload'];
            $msgid    = $renamer->fields['msgid'];
            
            $id      = unserialize(stripslashes(stripslashes($msgid)));
            $newWord = unserialize(stripslashes(stripslashes(str_replace("]", "'", $original))));
            
            $file     = $renamer->getLanguageFile($lang);
            $entries  = $poParser->parse($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
            $header   = $poParser->getHeaders();
            $newEntry = array();
            
            foreach ($entries as $entry) {
               if ($entry['msgid'] == $id) {
                  for ($i = 0; $i < count($entry['msgstr']); ++$i) {
                     if ($entry['msgstr'][$i] == $overload) {
                        $entry['msgstr'][$i] = $newWord;
                        $find                = true;
                     }
                  }
               }
               $newEntry[] = $entry;
            }
            
            //sauvegarde temporaire du fichier à updaté
            if ($renamer->saveFileIntoTmp($file)) {
               
               $poParser = new PoParser();
               $poParser->setEntries($newEntry);
               $poParser->setHeaders($header);
               $res = $poParser->write($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
               
               //si write ok
               if ($res) {
                  
                  //update tranlate
                  $renamer->updateTranslation($_SERVER['DOCUMENT_ROOT'] . $CFG_GLPI["root_doc"] . '/locales/' . $file);
                  //del tmp file
                  $renamer->removeFileIntoTmp($file);
                  
                  //del bdd entry
                  $input       = array();
                  $input['id'] = $_POST['id'];
                  $renamer->delete($input);
                  
                  Session::addMessageAfterRedirect(sprintf(__('\'%1$s\' replaced by \'%2$s\'', "renamer"), $overload, $newWord), false, INFO);
                  echo true;
                  
               } else {
                  
                  $renamer->restoreFileFromTmp($file);
                  $renamer->removeFileIntoTmp($file);
                  Session::addMessageAfterRedirect(sprintf(__('Can\'t access to file ', 'renamer') . $file), false, INFO);
                  echo false;
               }
               
            } else {
               Session::addMessageAfterRedirect(sprintf(__('Can\'t save file  \'%1$s\' in tmp folder', 'renamer'), $file), false, INFO);
               echo false;
            }
            
         } else {
            Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, INFO);
            echo false;
         }
         
         break;
      
      
      
      case 'restore':
         
         //check if right access
         if (!PluginRenamerInstall::checkRightAccessOnGlpiLocalesFiles()) {
            Session::addMessageAfterRedirect(__("Please give write permission to the 'locales' folder of Glpi", "renamer"), false, ERROR);
            echo false;
         }
         
         //remove locale file of glpi
         if (!PluginRenamerInstall::cleanLocalesFilesOfGlpi()) {
            Session::addMessageAfterRedirect(__("Error while cleaning glpi locale files", "renamer"), false, ERROR);
            echo false;
         }
         
         //restore local file of glpi with back og plugin renamer
         if (!PluginRenamerInstall::restoreLocalesFielsOfGlpi()) {
            Session::addMessageAfterRedirect(__("Error while restore glpi locale files", "renamer"), false, ERROR);
            echo false;
         }
         
         //clean table
         $DB->query("TRUNCATE TABLE `glpi_plugin_renamer_renamers`", "renamer");
         Session::addMessageAfterRedirect(__("Restoration Complete", "renamer"), false, INFO);
         
         echo true;
         
         break;
      
      default:
         echo 0;
   }
   
} else {
   echo 0;
}


function returnSuccess() {
   global $CFG_GLPI;
   return "<img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/check16.png'/>";
}

function returnError($error) {
   global $CFG_GLPI;
   return "<div><img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/cross16.png'/> " . $error . "</div>";
}

function returnWarning() {
   global $CFG_GLPI;
   return "<td colspan='6'><img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/warning.png'/> " . __("Please give write permission to the 'locales' folder of Glpi", "renamer") . "
            <img src='" . $CFG_GLPI['root_doc'] . "/plugins/renamer/pics/warning.png'/></td>";
}


/**
 * Function to search on msgid
 * @param $word
 * @param $msgid
 * @param bool $exact
 * @return bool
 */
function searchOnMsgid($word, $msgid, $exact = false) {
   
   if ($exact) {
      foreach ($msgid as $id) {
         if (strtolower($id) == strtolower($word))
            return true;
      }
   } else {
      foreach ($msgid as $id) {
         if (preg_match_all("#" . $word . "#i", $id))
            return true;
      }
   }
   
}


/**
 * function to search word on msgstr
 * @param $word
 * @param $msgstr
 * @param bool $exact
 * @return bool
 */
function searchOnMsgstr($word, $msgstr, $exact = false) {
   
   if ($exact) {
      foreach ($msgstr as $str) {
         if (strtolower($str) == stripslashes(strtolower($word)))
            return true;
      }
   } else {
      foreach ($msgstr as $str) {
         if (preg_match_all("#" . $word . "#i", $str)) {
            return true;
         }
      }
   }
   
}


/**
 *  Search if string exist (==) on another string
 * @param $word
 * @param $id
 * @param $string
 * @param $file
 * @return bool
 */
function existExactWord($word, $id, $string, $file) {
   
   $resultId     = searchOnMsgid($word, $id, true);
   $resultString = searchOnMsgstr($word, $string, true);
   if ($resultString || $resultId)
      return true;
   
}

/**
 * Search if string exist (stripos) on another string
 * @param $word search
 * @param $string pattern
 * @param $file where search
 * @return bool
 */
function existword($word, $string, $id, $file) {
   
   $resultId     = searchOnMsgid($word, $id, false);
   $resultString = searchOnMsgstr($word, $string, false);
   if ($resultString || $resultId)
      return true;
   
}

/**
 * Create table row for an entry
 * @param $entry
 * @return string
 */
function createTableRow($entry, $word) {
   
   global $CFG_GLPI;
   $i       = 0;
   $content = "";
   
   foreach ($entry['msgstr'] as $str) {
      
      $nb = rand();
      
      $content .= "<tr class='tab_bg_1'>";
      
      if ($i != 1) {
         $content .= "<td rowspan=" . count($entry['msgstr']) . ">";
         $content .= addHighlightingWord(implode('<br>', $entry['msgid']), $word);
         $content .= "</td>";
         
         $content .= "<td rowspan=" . count($entry['msgstr']) . ">";
         if (isset($entry['msgctxt']))
            $content .= implode('<br>', $entry['msgctxt']);
         else
            $content .= __('No', 'renamer');
         
         $content .= "</td>";
         
         $content .= "<td rowspan=" . count($entry['msgstr']) . ">";
         if (isset($entry['msgid_plural']))
            $content .= addHighlightingWord(implode('<br>', $entry['msgid_plural']), $word);
         else
            $content .= __('No', 'renamer');
         
         $content .= "</td>";
         $i++;
      }
      
      $content .= "<td>" . addHighlightingWord($str, $word) . "</td>";
      $content .= "<td>";
      $content .= "<input type='text' id='newWord" . $entry['index'] . $nb . "' /> ";
      $content .= "<input onclick='overloadWord(" . $entry['index'] . $nb . ");' value='" . __('Overload', 'renamer') . "' class='submit' style='width: 80px;'>";
      $content .= "</td>";
      
      $content .= "<td>";
      $content .= "<div style='min-width:24px; float:right; padding-left:10px;' id='info" . $entry['index'] . $nb . "'></div><img id='waitLoadingOverload" . $entry['index'] . $nb . "' style='width:24px; display:none;' src='../pics/loading.gif'>";
      $content .= "</td>";
      
      $content .= "<input type='hidden' name='msgid' id='msgid" . $entry['index'] . $nb . "' value='" . addslashes(serialize($entry['msgid'])) . "'>";
      $content .= "<input type='hidden' name='msgstr' id='msgstr" . $entry['index'] . $nb . "' value='" . addslashes(serialize(str_replace("'", "]", $str))) . "'>";
      
      if (isset($entry['msgctxt']))
         $content .= "<input type='hidden' name='msgctxt' id='msgctxt" . $entry['index'] . $nb . "' value='" . addslashes(serialize(str_replace("'", "]", $entry['msgctxt']))) . "'>";
      else
         $content .= "<input type='hidden' name='msgctxt' id='msgctxt" . $entry['index'] . $nb . "' value='null'>";
      
      $content .= "</tr>";
   }
   
   return $content;
}

/**
 * add Highlightning to a word in string
 * @param $str
 * @param $word
 * @return mixed
 */
function addHighlightingWord($str, $word) {
   
   $motif  = '`(.*?)(' . $word . ')(.*?)`si';
   $sortie = '$1<span class="highlighting">$2</span>$3';
   return preg_replace($motif, $sortie, $str);
}
