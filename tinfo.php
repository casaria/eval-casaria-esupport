<?php

/***************************************************************************************************
**	file: tinfo.php
**
**	This file contains the information available to users about their open tickets.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	10/05/01
	***********************************************************************************************
			**
			**	Copyright (C) 2001  <JD Bottorf>
			**
			**		This program is free software; you can redistribute it and/or
			**		modify it under the terms of the GNU General Public
			**		License as published by the Free Software Foundation; either
			**		version 2.1 of the License, or (at your option) any later version.
			**
			**		This program is distributed in the hope that it will be useful,
			**		but WITHOUT ANY WARRANTY; without even the implied warranty of
			**		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
			**		General Public License for more details.
			**
			**		You should have received a copy of the GNU General Public
			**		License along with This program; if not, write to the Free Software
			**		Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
			**
			***************************************************************************************/

require_once "common/config.php";
require_once "common/$database.class.php";
require_once "common/common.php";
$language = getLanguage($cookie_name);
if($language == '')
	require_once "lang/$default_language.lang.php";
else
	require_once "lang/$language.lang.php";

if(!$id){
        printerror($lang_missing_info);
        endtable();
        endtable();
        require "common/footer.php";
        exit;
}

if($action == 'download'){
	$query = $db->query("SELECT * from $mysql_attachments_table where id=$id");
	$file = $db->fetch_array($query);
		if($file[filename] == ''){
		echo $lang_fileremoved;
		exit;
	}
	$db->query("UPDATE $mysql_attachments_table SET downloads=downloads+1 WHERE id='$id'");
	// Send the attachment
	if(eregi("http://", $file[filename])){
		header("Location: $file[filename]");
		exit;
	}
	else{
		header("Content-disposition: filename=$file[filename]");
		header("Content-Length: ".strlen($file[attachment]));
		header("Content-type: $file[filetype]");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $file[attachment];
		exit;
	}
}

//$send_mail is set upon submit, defined in the user template loaded below
if(isset($send_mail)){
 	$ticket = getTicketInfo($id);
  RefreshLastUpdateTime($id);
	//attachement file handling
  ProcessAttachment();		
  // add the update to the log file
  
  if(! empty($user_update)){
  	$log_msg = "<i>$lang_userupdate:</i> ";
  	$log_msg .= addslashes(stripScripts($user_update));
  	$log = updateLog($id, $log_msg);
  	//echo $log;
  	$sql = "update $mysql_tickets_table set update_log='$log' where id=$id";
  	$db->query($sql);
  
   	//send the email if the ticket is assigned to a supporter and smtp is turned on.
    if($enable_pager == 'On' ){
    	$template_name = 'page_user_ticket_update';
    	$subject = "Ticket $id UPDATE";
    	if($ticket[supporter] != 'support_pool'){
    
    		//to, from, return, id, message
    		sendGroupPage( $template_name, $sg, $username, $short, $priority, $id, $subject, $ticket[supporter]);
    
    	}
    	else // no supporter assigned, send update to all
    	{
     		sendGroupPage( $template_name, $sg, $username, $short, $priority, $id, $subject);
     	}
    }
  }
}


$ticket = getTicketInfo($id);	//get the ticket info again so we have the updated update log.
$supporter = getUserInfo($ticket[supporter_id]); //get all relevant uaer info for the supporter

//+++
$group_valid = in_array($ticket[ugroupid], $ugID_list);
// this line would only allow user's own tickets
//if($pubpriv == "Private" && strcasecmp($ticket[user], $cookie_name)){
if($pubpriv == "Private" && !$group_valid){
	printerror($lang_noaccessgroup);     //no access if it's not user's group
	exit;
}


echo '<a href="supporter/updatelog.php?cookie_name='.$cookie_name.'&id='.$ticket['id'].'" target="myWindow" onClick="window.open(\'\', \'myWindow\',
					\'location=no, status=yes, scrollbars=yes, height=500, width=600, menubar=no, toolbar=no, resizable=yes\')">
					<img border=0 src="'.$theme['image_dir'].'orangeglow0_show_summary.png"></a> ';


$ticket[create_date] = date("F j, Y, g:i a", $ticket[create_date]);
if($ticket[lastupdate] != 0)
	$ticket[lastupdate] = date("F j, Y, g:i a", $ticket[lastupdate]);
else
	$ticket[lastupdate] = $lang_never;
$ticket[short] = stripslashes($ticket[short]);
$ticket[description] = stripslashes($ticket[description]);

//if there attachments, get them and setup the links to them.
$sql = "SELECT * from $mysql_attachments_table where tid=$id";
$result = $db->query($sql);
$num_attachments = $db->num_rows($result);
if($num_attachments > 0){
	while($attachment = $db->fetch_array($result)){
		$attachment[filesize] = convertFileSize($attachment[filesize]);
		$attachments .= "<a target=_blank href=\"tinfo.php?action=download&id=$attachment[id]\">$attachment[filename] </a> ( $attachment[filesize] ) <br>";
	}
}

//setup the update log html
$user_update_log = stripslashes(stripslashes(updateLogUserView($ticket[update_log])));
$padded_id = str_pad($id, 5, '0', STR_PAD_LEFT);

if($enable_uattachments == 'On'){
	$attachment_area  = "
		<tr><td class=\"back2\">$lang_addattachment: </td>
		    <td class=\"back\" valign=bottom>
		        <input type=hidden name=\"MAX_FILE_SIZE\" value=\"1000000\">
		        <input type=\"file\" name=\"the_file\" size=35>
		    </td>
		</tr>";
}

//get the template information
// display the form and submit button(s)
// $send_mail is set upon submit
// $user_update contains the textfield with  updates
$sql = "SELECT template from $mysql_templates_table where name='user_ticket_info2'";
$result = $db->query($sql);
$template = $db->fetch_array($result);
$template=str_replace("\\'","'",$template[0]);
eval("\$template = \"$template\";");
echo $template;

// time track sub table

displayTimeHistory();
	
endTable();

function updateLogUserView($log){
	global $lang_updates, $delimiter, $lang_emailsent, $lang_fileattached, $lang_emailsent, $lang_updatedby, $lang_ticketcreatedby, $cookie_name, $lang_transferred, $lang_prioritychange, $lang_statuschange;
	
	$log = explode($delimiter, $log);
		for($i=1; $i<sizeof($log); $i=$i+2){

			//to show all updates to the ticket, comment out the next line
			//if(eregi('\$lang_emailsent', $log[$i]) || eregi('\$lang_fileattached', $log[$i]) || eregi('\$lang_ticketcreatedby', $log[$i]) || eregi($lang_emailsent, $log[$i]) || eregi($lang_fileattached, $log[$i]) || eregi($lang_ticketcreatedby, $log[$i]) || eregi("$lang_updatedby $cookie_name", $log[$i]) || eregi('\$lang_updatedby ' . $cookie_name, $log[$i]) ||eregi('\$lang_transferred', $log[$i]) || eregi($lang_transferred, $log[$i])){
				if(eregi("^[0-9]{1,11}", $log[$i-1])){		//if it contains just the timestamp, edit it.
					$date = eregi_replace("by*", "", $log[$i-1]);
					$date = date("F j, Y, g:i a", $date);
					$log[$i-1] = eregi_replace("[0-9]*(.*)lang_by", "$date by", $log[$i-1]);
				}
				
				$update_log .= "<tr><td class=back2><font size=1> ". $log[$i-1] ." </font><br>";
				$text = $log[$i];
				eval("\$text = \"$text\";");
				$update_log .= "<b>$text</b></td></tr>";

			//}	//to show all updates to the ticket, comment out this entire line
		}

	return $update_log;
}
		
?>

