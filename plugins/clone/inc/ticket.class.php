
<?php
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginCloneTicket extends Ticket {
//class PluginCloneTicket {

/*
if (!isset($_REQUEST['tickets_id'])) exit;

PluginCloneTicket::clone($_REQUEST['tickets_id']);

*/

 /**
    * Clone a ticket and his relations
    * @param  integer $tickets_id id of the ticket to clone
    * @return print a json response (return nothing)
    */
   static function cloneTicket($tickets_id) {
      global $DB, $CFG_GLPI, $today;

      //get old ticket
      $ticket = new Ticket;
      if (!$ticket->getFromDB($tickets_id)) {
         echo "{success:false, message:\"".__("Error : get old ticket", "clone")."\"}";
         exit;
      }

		$now = date("Y-m-d H:i:s");

      //set fields 
      $fields = $ticket->fields;
      $fields = array_map(array('Toolbox','addslashes_deep'), $fields);
      $fields['id']                  = 0;
      $fields['_users_id_requester'] = 0;
      $fields['status']              = CommonITILObject::INCOMING;
      $fields['date']                = $now;
      

      /*var_dump($fields);
      exit;*/

      //create new ticket (duplicate from previous)
      if (!$newID = $ticket->add($fields)) {
         echo "{success:false, message:\"".__("Error : adding new ticket", "clone")."\"}";
         exit;
      }

/*
      //add link between them
      $ticket_ticket = new Ticket_Ticket;
      if (!$ticket_ticket->add(array(
         'tickets_id_1' => $tickets_id,
         'tickets_id_2' => $newID,
         'link'         => 2 // duplicated
      ))) {
         echo "{success:false, message:\"".
               __("Error : adding link between the two tickets", "clone")."\"}";
         exit;
      }
*/
/*      //add a followup to indicate duplication
      $followup = new TicketFollowup;
      if (!$followup->add(array(
         'tickets_id'      => $newID,
         'users_id'        => Session::getLoginUserID(),
         'content'         => __("This ticket has been from the ticket num", "clone")." ".
                              $tickets_id,
         'is_private'      => true,
         'requesttypes_id' => 6 //other
      ))) {
         echo "{success:false, message:\"".__("Error : adding followups", "clone")."\"}";
         exit;
      }
*/
      //add actors to the new ticket (without assign)
      //users
      $query_users = "INSERT INTO glpi_tickets_users 
      SELECT '' AS id, $newID as tickets_id, users_id, type, use_notification, alternative_email
      FROM glpi_tickets_users
      WHERE tickets_id = $tickets_id AND type != 2";
      if (!$res = $DB->query($query_users)) {
         echo "{success:false, message:\"".__("Error : adding actors (user)", "clone")."\"}";
         exit;
      }
      
      //groups
      $query_groups = "INSERT INTO glpi_groups_tickets 
      SELECT '' AS id, $newID as tickets_id, groups_id, type
      FROM glpi_groups_tickets
      WHERE tickets_id = $tickets_id AND type != 2";
      if (!$res = $DB->query($query_groups)) {
         echo "{success:false, message:\"".__("Error : adding actors (group)", "clone")."\"}";
         exit;
      }            

      //add documents
      $query_docs = "INSERT INTO glpi_documents_items 
      SELECT '' AS id, documents_id, $newID as items_id, 'Ticket' as itemtype, entities_id, is_recursive, date_mod
      FROM glpi_documents_items
      WHERE items_id = $tickets_id AND itemtype = 'Ticket'";
      if (!$res = $DB->query($query_docs)) {
         echo "{success:false, message:\"".__("Error : adding documents", "clone")."\"}";
         exit;
      }

      //add history to the new ticket
      $changes[0] = '0';
      $changes[1] = __("This ticket has been from the ticket num", "clone")." ".$tickets_id;
      $changes[2] = "";
      Log::history($newID, 'Ticket', $changes, 'Ticket');

      //add message (ticket cloned) after redirect
      Session::addMessageAfterRedirect(__("This ticket has been cloned from the ticket num", "clone")." ".$tickets_id);
      
      //all ok
      echo "{success:true, newID:$newID}";
      
      //redirect to new ticket
      //$new_ticket = $CFG_GLPI['url_base'] ."/front/ticket.form.php?id=".$newID;
		header("Location: ".$CFG_GLPI['url_base']."/front/ticket.form.php?id=".$newID."");

   }
   
   /*
   				//CLONE               
					if (self::canUpdate() ) {                                                 
                    //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class='vsubmit'  onclick='return confirm(\"Clone Ticket?\");location.href=\"../plugins/clone/front/clone.php?tickets_id=".$ID."\"'>". __('Clone Ticket')."</a>";                    
                    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class='vsubmit'  href='../plugins/clone/front/clone.php?tickets_id=".$ID."' onclick = \"if (! confirm('Clone Ticket?')) return false;\">". __('Clone Ticket')."</a>";                    
                  } 
   */
   
}