<?php

/***************************************************************************************************
**	file: tcreate.php
**
**	This file contains the frontend for creating a new ticket.  Provides error checking and also
**	accesses the database to insert the information.
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

if($pubpriv == 'Private')
	require_once "common/login.php";

$language = getLanguage($cookie_name);

if($language == '')
	require_once "lang/$default_language.lang.php";
else
	require_once "lang/$language.lang.php";

if(isset($create)){

	//if the system is public, set some cookies so user information will be recorded for next time
	setcookie("cookie_user_name", $username, time()+31536000);
	setcookie("cookie_email", $email, time()+31536000);
	setcookie("cookie_office", $office, time()+31536000);
	setcookie("cookie_phone", $phone, time()+31536000);

	$time = time() + ($time_offset*3600);
	
	if($group == '' || $priority == '' || $username == '' || $description == ''){
		header("Location: index.php?t=terr");
	}
	else{
		if($short == ''){
			$short = "$lang_nodesc";
		}
		if($sg == ''){
			$sg = 1;
		}

		$status = getStatus(getLowestRank($mysql_tstatus_table));
		
		$short = addslashes(stripScripts($short));
		$description = addslashes(stripScripts($description));
		//enter fixed vaules support pool and group
		$sql = "INSERT into $mysql_tickets_table values(NULL, '$time', $sg, 'support_pool', 1, '$priority', '$status',
				'$username', '$email', '$office', '$phone', '$equipment', '$category', '$platform', '$short', '$description', NULL, 0, '$time', 0)";
		
		$db->query($sql);
		$id = $db->insert_id();
		
		//update the log so it shows who created the ticket now.
		if($pubpriv == "Public")
			$msg = "<i> \$lang_createdbyweb </i>";
		else
			$msg = "<i>  \$lang_ticketcreatedby " . $cookie_name . "</i>";
		$log = updateLog($id, $msg);
		$sql = "update $mysql_tickets_table set update_log='$log' where id=$id";
		$db->query($sql);

		//insert the file into the database if it exists.
		if($enable_uattachments == 'On' && $the_file != '' && $the_file != 'none'){
                        $attachment = addslashes(fread(fopen($the_file, "rb"), filesize($the_file)));
			if($the_file_type=="application/x-gzip-compressed"){
				$attachment = base64_decode($attachment);
			}
			$query = "INSERT into $mysql_attachments_table VALUES(NULL, NULL, $id, '$the_file_name', '$the_file_type', '$the_file_size', '$attachment', 0, '$cookie_name', $time)";
			$db->query($query);	//insert all info about the attachment into the database.
			$file_id = $db->insert_id();

			$attachsize = $the_file_size;
			if($attachsize >= 1073741824) { $attachsize = round($attachsize / 1073741824 * 100) / 100 . "gb"; }
			elseif($attachsize >= 1048576) { $attachsize = round($attachsize / 1048576 * 100) / 100 . "mb"; }
			elseif($attachsize >= 1024)     { $attachsize = round($attachsize / 1024 * 100) / 100 . "kb"; }
			else { $attachsize = $attachsize . "b"; }

			//update the update log
			$msg = "\$lang_fileattached : ". $the_file_name . " ( $attachsize ) <br>";
			$log = updateLog($id, $msg);
			$sql = "update $mysql_tickets_table set update_log='$log' where id=$id";
			$db->query($sql);
		}
		//if the pager gateway is enabled...send a page to the supporters of that group if the ticket is set above the default.
	
		if($enable_pager == 'On' && (getRank($priority, $mysql_tpriorities_table) >= $pager_rank_low) ){
			$template_name = 'email_group_page';
			sendGroupPage($template_name, $sg, $username, $short, $priority, $id);
		}
		//now print out the html that lets the user know that their ticket was submitted successfully.
		header("Location: index.php?t=tsuc&id=$id");
		
	}

}

else{
	echo "<form action=tcreate.php method=post enctype=\"multipart/form-data\">";
?>
	
	<script language="JavaScript">
		<!--
		function MM_jumpMenu(targ,selObj,restore){ //v3.0
		  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
		  if (restore) selObj.selectedIndex=0;
		}
		//--></script>

<?php

	createTicketHeader("$lang_create $lang_ticket");
	createSupporterInfo();
	createUserInfo();
	if($enable_uattachments == 'Off'){
		createTicketInfo('disallow');
	}
	else{
		createTicketInfo();
	}

	echo "<center>";
	echo "<input type=submit name=create value=\"$lang_create $lang_ticket\">";
	echo "&nbsp;&nbsp;&nbsp;";
	echo "<input type=reset name=reset value=$lang_reset>";
	echo "<input type=hidden name=sg value=".$sg.">";
	echo "</form>";
	echo "</center>";

}




function createTicketHeader($msg)
{
	startTable($msg, "middle");
	endTable();
}

function createSupporterInfo()
{
	global $sg, $lang_supporterinfo, $lang_supportergroup, $lang_priority, $lang_ticket;

	if($sg == ''){
		$sg = getDefaultSupporterGroupID();
	}

	startTable("$lang_supporterinfo", "left", 100, 4);
					
		echo '<tr>
				<td width=27% class=back2 align=right>'.$lang_supportergroup.':</td>
				<td class=back width=20%>';
				?>
				<select name=group onChange="MM_jumpMenu('parent', this, 0)">
				<?php
				
				createGroupMenu(0);

		echo '</select>
				</td>
				<td width=100 class=back2 align=right>'.$lang_ticket.' '.$lang_priority.':</td>
				<td class=back>
				<select name=priority>';
				
				createPriorityMenu();
							
							
		echo '  </select>
				</td>
				</tr>';

	endTable();

}





function createSupporterMenu($group_id)
{
	global $mysql_users_table, $db;

	if($group_id == '' || !isset($group_id) || $group_id == 1){
		$sql = "select id,user_name from $mysql_users_table where supporter=1 order by user_name asc";
		$table = $mysql_users_table;
	}
	else{
		$table = "sgroup" . $group_id;
		$sql = "select user_id,user_name from $table order by user_name asc";
	}

	$result = $db->query($sql);

	while($row = $db->fetch_row($result)){
		echo "<option value=\"$row[id]\"> $row[user_name] </option>";
	}

}




function createUserInfo()
{
	global $pubpriv, $mysql_users_table, $db, $cookie_name, $lang_username, $lang_email, $lang_office, $lang_phoneext, $lang_userinfo;

	if($pubpriv == 'Private'){
		$sql = "SELECT * from $mysql_users_table where user_name='$cookie_name'";
		$result = $db->query($sql);
		$row = $db->fetch_array($result);
		$cookie_phone = $row[phone];
		$cookie_email = $row[email];
		$cookie_user_name = $row[user_name];
		$cookie_office = $row[office];
	}
	else{
		global $cookie_phone, $cookie_email, $cookie_user_name, $cookie_office;
	}
	startTable("$lang_userinfo", "left", 100, 4);	
		if($pubpriv == "Private"){
			echo "<tr>
				<td width=27% class=back2 align=right>$lang_username:</td>
				<td class=back width=20%>$cookie_user_name
					<input type=hidden name=username value=\"$cookie_user_name\">
				</td>";
		}
		else{
			echo "<tr>
				<td width=27% class=back2 align=right>$lang_username:</td>
				<td class=back width=20%>
					<input type=text size=16 name=username value=\"$cookie_user_name\">
				</td>";
		}


			echo "
				<td class=back2 align=right width=100> $lang_email: </td>
				<td class=back align=left>
					<input type=text name=email value=\"$cookie_email\">
				</td>
				</tr>
				<tr>
				<td width=27% class=back2 align=right>$lang_office:</td>
				<td class=back>
					<input type=text size=16 name=office value=\"$cookie_office\">
				</td>
				<td class=back2 align=right width=100>$lang_phoneext:</td>
				<td class=back>
					<input type=text name=phone value=\"$cookie_phone\">
				</td>";

	endTable();
}



?>
