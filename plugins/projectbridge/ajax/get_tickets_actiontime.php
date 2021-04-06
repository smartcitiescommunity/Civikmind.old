<?php

require('../../../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if ($_SERVER['REQUEST_METHOD'] == 'POST'
    && !empty($_POST['ticket_ids'])
    && is_array($_POST['ticket_ids'])
) {
    $ticket = new Ticket();
    $tickets = $ticket->find("TRUE AND id IN (" . implode(', ', $_POST['ticket_ids']) . ")");

    $tickets_actiontime = [];

   foreach ($tickets as $ticket_data) {
       $actiontime = (int) $ticket_data['actiontime'];

      if (!empty($actiontime)) {
          $actiontime = round($actiontime / 3600 * 100) / 100;
      }

         $tickets_actiontime[$ticket_data['id']] = $actiontime;
   }

    echo json_encode($tickets_actiontime);
}
