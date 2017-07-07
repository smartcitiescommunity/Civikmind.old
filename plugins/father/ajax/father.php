<?php
include ('../../../inc/includes.php');

switch ($_POST['action']) {
       case 'father_values':

      $class = ($_POST['itemtype'] == 'ticket') ? "tab_bg_1" : '';

      echo "<tr class='$class tab_bg_1'>";
      echo "<th>".__('Father type','father')."</th>";
      echo "<td colspan='3'>";
      PluginFatherFather::fatherYesNo();
      echo "</td>";
      echo "</tr>";
      break;

}
