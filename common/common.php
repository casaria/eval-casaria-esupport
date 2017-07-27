<?php
/***********************************************************************************************************
**
**	file:	common.php
**
**	This file contains all variables and common functions for the helpdesk
**	program.
**
************************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/24/01
	**
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

//create the connection to the database.
$pconnect = 0;
$db = new $database();
$db->connect($db_host, $db_user, $db_pwd, $db_name, $pconnect);

/**********************************************************************************************************/
/****************************	Other Variables	***********************************************************/
//set the variables from the database if not running the install
$var = getCRMVariables();

$crm_name = $var['name'];
//+++
$logfile = "logfile.txt";

/****************************	Other Variables	***********************************************************/
//set the variables from the database if not running the install
$var = getVariables();

$announcements_limit = $var['announcements_per'];		//number of announcements to display on the main page.
$users_limit = $var['users_per'];				//number of users to list in a user/supporter list
							//5 seems to almost fit on a single page
$enable_ratings = $var['ratings'];			//use the ticket rating system?
$helpdesk_name = $var['name'];				//name of the helpdesk
$address_name = $var['a_name'];				// Company Name
$address_street1 = $var['a_street1'];
$address_street2 = $var['a_street2'];
$address_city = $var['a_city'];
$address_state = $var['a_state'];
$address_zip = $var['a_zip'];



$admin_email = $var['admin_email'];			//email address of the helpdesk administrator
$enable_stats = $var['stats'];				//processed time statistics on or off
$supporter_site_url = $var['supporter_site_url'];		//url for supporters
$admin_site_url = $var['admin_site_url'];			//url for administrators
$rating_interval = $var['ticket_interval'];		//interval between tickets for rating service
$enable_ssl = $var['socket'];				//ssl on or off variable
$enable_forum = $var['forum'];						//forum on or off variable
$forum_site_url = $var['forum_site'];					//url for the forum if it exists
$enable_smtp = $var['smtp'];							//smtp server on or off variable
$sendmail_path = $var['sendmail_path'];				//path to sendmail on a *nix machine
$enable_helpdesk = $var['on_off'];					//helpdesk on or off variable
$on_off_reason = $var['reason'];						//reason for helpdesk being off
$enable_pager = $var['pager'];						//enable pager gateway
$pager_rank_low = $var['pager_rank_low'];				//lowest rank of priority that receives pages
$enable_whosonline = $var['whosonline'];				//enable whos online display
$closed_ticket_count = $var['ticket_count'];			//number of tickets that have been closed since last
													//ticket rating.
$enable_time_tracking = $var['time_tracking'];		//enable time tracking per ticket/supporter
$enable_kbase = $var['kbase'];						//enable knowledge base
//$enable_attachments = $var['attachments'];			//enable attachments +++ not a field in settings
$default_theme = $var['default_theme'];				//the name of the default theme that is set by the admin
$default_language = $var['default_language'];			//the default language
$version = $var['version'];							//version number of the helpdesk software
$pubpriv = $var['pubpriv'];							//public/private setting
$enable_tattachments = $var['tattachments'];			//enable ticket attachments
$enable_kattachments = $var['kattachments'];			//enable knowledge base attachments
$enable_uattachments = $var['uattachments'];			//enable user ticket attachments
$kpurge = $var['kpurge'];								//purge policy for knowledge base attachments

$GMTprivacyHour = $var['GMTPrivacyStart'];
$PrivacyDuration = $var['PrivacyDuration'];

//$tpurge = $var['tpurge'];								//purge policy for ticket attachments +++ not a field in settings


$delimiter = "--//--";								//this is the string that is inserted after the user name
													//and again after the message in the update log.  This can't
													//be the same as anything that a user would type.  If changed
													//this will mess up the update log...so don't change it.

$site_url = eregi_replace("/supporter.*", "", $supporter_site_url);
$time_format = "h:i A";
$current_time = gmdate("$time_format");

class SendPreferences {

  public $HonorSupporterPrivacy = false;
  public $HonorAdminPrivacy = false;
  public $TicketPriorityPrivacyOverride = "A";
  public $GMTPrivacyStart = 5;
  public $PrivacyDuration = 8;
  public $ForcePageAllInGroup = false;
  public $ForceAllSupporters = false;
  public $CopyAdminOnUserUpdate = true;
  public $CopyAllOnUserUpdate= false;
  public $BlockCloseUpdates = false;      

}

$EP = new SendPreferences();

  $EP->HonorSupporterPrivacy = true;
  $EP->HonorAdminPrivacy = false;
  $EP->TicketPriorityPrivacyOverride = "A";
  $EP->GMTPrivacyStart = 5;
  $EP->PrivacyDuration = 8;
  $EP->ForcePageAllInGroup = false;
  $EP->ForceAllSupporters = false;
  $EP->CopyAdminOnUserUpdate = true;
  $EP->CopyAllOnUserUpdate= false;
  $EP->BlockCloseUpdates = false;      




/***********************************************************************************************************
**
**	Function Definitions
**
***********************************************************************************************************/


/***********************************************************************************************************
**	function ProcessAttachment():
**		Takes no arguments.  Checks for valid file attachements upon submit and enters it to DB if any
************************************************************************************************************/
function ProcessAttachment()
{
	
	global $mysql_attachments_table, $mysql_tickets_table, $enable_uattachments, $uploaddir, $cookie_name, $time, $id, $db;
	
			//attachement file handling
  		$userfile = $_FILES['the_file']['name'];
  		$tmpfile = $_FILES['the_file']['tmp_name'];
        $file_type = $_FILES['the_file']['type'];
  		$file_size = $_FILES['the_file']['size'];
  		$file_err = $_FILES['the_file']['error'];
      
  		$uploadfile = $uploaddir . basename($userfile);

		  //insert the file into the database if it exists.
		  
		  if($enable_uattachments == 'On' && (!empty($userfile)) ){
		  	if (move_uploaded_file($tmpfile, $uploadfile)) {
    		  $attachment = addslashes(fread(fopen($uploadfile, "rb"), filesize($uploadfile)));
						
        		  
        			if($file_type=="application/x-gzip-compressed"){
        				$attachment = base64_decode($attachment);
        			}
        			$query = "INSERT into $mysql_attachments_table VALUES(NULL, NULL, $id, '$userfile', '$file_type', '$file_size', '$attachment', 0, '$cookie_name', $time)";
        			$db->query($query);	//insert all info about the attachment into the database.
        			$file_id = $db->insert_id();
        
        			$attachsize = $file_size;
        			if($attachsize >= 1073741824) { $attachsize = round($attachsize / 1073741824 * 100) / 100 . "gb"; }
        			elseif($attachsize >= 1048576) { $attachsize = round($attachsize / 1048576 * 100) / 100 . "mb"; }
        			elseif($attachsize >= 1024)     { $attachsize = round($attachsize / 1024 * 100) / 100 . "kb"; }
        			else { $attachsize = $attachsize . "b"; }
        
        			//update the update log
        			$msg = "\$lang_fileattached : ". $userfile . " ( $attachsize ) <br>";
        			$log = updateLog($id, $msg);
        			$sql = "update $mysql_tickets_table set update_log='$log' where id=$id";
        			$db->query($sql);
				} // file move
		  }  
}

/***********************************************************************************************************
**	function getCRMVariables():
**		Takes no arguments.  Gets the variables out of the CRMsettings table and returns them as an array.
************************************************************************************************************/
function getCRMVariables()
{
	global $mysql_crmsettings_table, $db;

	$sql = "select * from $mysql_crmsettings_table";
	$query = $db->query($sql);
	$result = $db->fetch_array($query);

	return $result;

}

/***********************************************************************************************************
**	function getVariables():
**		Takes no arguments.  Gets the variables out of the settings table and returns them as an array.
************************************************************************************************************/
function getVariables()
{
	global $mysql_settings_table, $db;

	$sql = "select * from $mysql_settings_table";
	$query = $db->query($sql);
	$result = $db->fetch_array($query);

	return $result;

}


/***********************************************************************************************************
**	function isEmpty():
**		Takes a table name as an argument.  Selects everything from that table.  Returns true if the number
**	of rows is greater than 0, otherwise false.
************************************************************************************************************/
function isEmpty($table)
{
	global $db;

	$sql = "select * from $table";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows > 0){
		return false;
	}
	else{
		return true;
	}
}

/***********************************************************************************************************
**	function checkPassword():
**		Takes two arguments, both strings.  If strings are equal to each other, return boolean true.  Else,
**	return boolean false.
************************************************************************************************************/
function checkPwd($pwd1, $pwd2)
{

	if($pwd1 == $pwd2)
		return true;
	else
		return false;
}

/***********************************************************************************************************
**	function userExists():
**		Takes one string as an argument.  Queries the user table and returns true if the user name is found.
**	Else, returns false.
************************************************************************************************************/
function userExists($name)
{
	global $mysql_users_table, $db;

	$sql = "select user_name from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows != 0){
		return true;
	}
	else{
		return false;
	}

}

/***********************************************************************************************************
**	function isCookieSet($cookie_name, $enc_pwd):
**	Returns boolean true or false if the presence of the cookie is detected.
**	
**  Modified to work without register_globals post php4.2.3
References checkUser();
************************************************************************************************************/
function isCookieSet($cookie_name, $enc_pwd)
{
        //global $cookie_name, $enc_pwd;
        //echo"isCookieSet<br>";
        //echo"$cookie_name, $enc_pwd";

	if(checkUser($cookie_name, $enc_pwd) && $cookie_name != ''){
		return true;
	}
	else{
		return false;
	}

}

/***********************************************************************************************************
**	function startSession():
**		Takes two  arguments.  
************************************************************************************************************/

function startSession() {
	global $session_time, $session_name;
    session_set_cookie_params($session_time);
    session_name($session_name);
    session_start();


}

function RewindSession() {
	global $session_time, $session_name;
    //Reset the expiration time upon page load
   if (isset($_COOKIE[$session_name]))
      setcookie($session_name, $_COOKIE[$session_name], time() + $session_time, "/");
  
}
/***********************************************************************************************************
**	function checkUser():
**		Takes two string arguments.  Name is the user name, pwd is the md5 encoded password.  Connects to the
**	database and checks to see if the specified user exists.  If so, the password in the database is
**	compared to the pwd argument.  If those match, then return boolean true.  All other cases, return boolean
**	false.
**	References checkPassword(), connect(), disconnect();
************************************************************************************************************/
function checkUser($name, $pwd)
{
	global $mysql_users_table, $db;
	//nov14  debug echo $name; echo":"; echo $pwd;

	//compare $name to what's in the database.
	//return true if the name is found in the database and the password matches.

	$sql = "select * from " . $mysql_users_table . " where user_name='" . $name . "'";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows != 1){
		return false;
	}

	$row = $db->fetch_array($result);

	if(!checkPwd($pwd, $row['password'])){
		return false;
	}
	
	if($row[user] == 0 && $name != ''){
		require_once "../common/style.php";
		printerror("Your account is not active.");
		exit;
	}

	//if user the password for the given user is correct, return true
	return true;
				
}


/***********************************************************************************************************
**	function getTotalUsers():
**		Takes no arguments.  Queries the user table and returns the number of different users there are as
**	an integer value.
************************************************************************************************************/
function getTotalUsers()
{
	global $mysql_users_table, $db;

	$sql = "select count(user_name) from $mysql_users_table";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getTotalAdmins():
**		Takes no arguments.  Queries the user table and returns the number of different users there are as
**	an integer value.
************************************************************************************************************/
function getTotalAdmins()
{
	global $mysql_users_table, $db;

	$sql = "select count(user_name) from $mysql_users_table where admin=1";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getTotalSupporters():
**		Takes no arguments.  Queries the user table and returns the number of different users there are as
**	an integer value.
************************************************************************************************************/
function getTotalSupporters()
{
	global $mysql_users_table, $db;

	$sql = "select count(user_name) from $mysql_users_table where supporter=1";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function getUserInfo():
**		Takes one integer value as an input.  Queries the user table and returns an array containing all of
**	the information that the database contains about the user with the id specified.
************************************************************************************************************/
function getUserInfo($id)
{
	global $mysql_users_table, $db;

	$sql = "select * from $mysql_users_table where id=$id";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	return $row;

}

/***********************************************************************************************************
**	function listMembers():
**		Takes a user id and a category as an input.  The category determines whether the data is queried
**	from all users or from only supporters.  It simply lists the members of the particular group along 
**	with a link to delete that particular user.
************************************************************************************************************/
function listMembers($id, $cat)
{

	global $mysql_sgroups_table, $mysql_ugroups_table, $db, $lang_delete;

	if($cat == 'users')
		$group_table = "ugroup" . $id;
	if($cat == 'supporters')
		$group_table = "sgroup" . $id;


	$sql = "select * from $group_table where user_name != 'support_pool' order by user_name asc";
	$result = $db->query($sql);

	echo "<tr><td class=back>";
	while($row = $db->fetch_row($result)){
		echo "<LI>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row[2]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		if($cat == 'users')
			echo "<a href=control.php?t=users&act=uopt&table=$group_table&rm=delete&gid=$row[0]&g=$id>$lang_delete</a>?</LI>";
		if($cat == 'supporters')
			echo "<a href=control.php?t=users&act=sopt&table=$group_table&rm=delete&gid=$row[0]&g=$id>$lang_delete</a>?</LI>"; 
	}
	
	echo "</td></tr>";

}

/***********************************************************************************************************
**	function getAnnouncements():
**		Takes no arguments.  Prints out the announcements from the announcement table in the database in
**	an easy to read format.
************************************************************************************************************/
function getAnnouncements($flag)
{
	global $announcements_limit, $mysql_announcement_table, $a, $db, $lang_delete, $lang_edit;
	if($a == 1){
		$sql = "select * from $mysql_announcement_table order by id desc";
	}
	else{
		$sql = "select * from $mysql_announcement_table order by id desc limit $announcements_limit";
	}

	$result = $db->query($sql);
	$i=0;

	if($flag == 'user' || $flag == 'supporter'){
		while($row = $db->fetch_row($result)){
			echo "\n<td class=date><b>".date("F d, Y",$row[1])."</b>";
			
			if($i == $announcements_limit-1){
				echo "<a name=place></a>";
			}

			echo "\n</td></tr>";
                        echo "\n<tr><td class=back2>&nbsp;&nbsp;&nbsp;&nbsp;".nl2br(stripslashes($row[2]))."\n</td></tr>";
			$i++;
		}
	}

	if($flag == 'admin'){
		while($row = $db->fetch_row($result)){
			echo "<td class=date><b>".date("F d, Y",$row[1])."</b>";
			if($i==$announcements_limit-1){
				echo "<a name=place></a>";
			}
			echo "&nbsp;&nbsp;&nbsp;&nbsp; ";
			echo "<a href=\"index.php?t=delete&id=$row[0]\">$lang_delete</a>";
			
			echo ", <a href=\"index.php?m=update&id=$row[0]\">";
			echo " $lang_edit</a>?";

			echo "</td></tr>";
                        echo "<tr><td class=back2>&nbsp;&nbsp;&nbsp;&nbsp;".nl2br(stripslashes($row[2]))."</td></tr>";
			$i++;
		}
	}

}


/***********************************************************************************************************
**	function getUserList():
**		Takes a sting, integer, and string as inputs.  The order variable contains the keyword which
**	determines the order in which the users are listed.  Offset is the variable that is passed around which
**	helps determine what position we are at in the database (makes Next/Previous buttons work the way they
**	should).  Group variable signifies whether we are querying all users or just supporters.  This function
**	prints out the table with options to edit/delte/and view history links.
************************************************************************************************************/
function getUserList($order, $offset, $group)
{
	global $mysql_users_table, $users_limit, $db, $admin_site_url, $lang_email, $lang_office, $lang_realname, $lang_username, $lang_infoforuser, $lang_edit, $lang_delete, $lang_stats;

	if(!isset($offset))
		$offset = 0;

	$low = $offset;

	//if the group is only supporters, grab only information about supporters and not all users.
	if($group == "admins"){
		switch($order){
			case ("user_name"):
				$sql = "select * from $mysql_users_table where admin=1 and user_name != 'support_pool' order by user_name asc limit $low, $users_limit";
				break;
			case ("office"):
				$sql = "select * from $mysql_users_table where admin=1 and user_name != 'support_pool' order by office, user_name asc limit $low, $users_limit";
				break;
			default:
				$sql = "select * from $mysql_users_table where admin=1 and user_name != 'support_pool' order by id asc limit $low, $users_limit";
				break;

		}
	}

	if($group == "supporters"){
		switch($order){
			case ("user_name"):
				$sql = "select * from $mysql_users_table where supporter=1 and user_name != 'support_pool' order by user_name asc limit $low, $users_limit";
				break;
			case ("office"):
				$sql = "select * from $mysql_users_table where supporter=1 and user_name != 'support_pool' order by office, user_name asc limit $low, $users_limit";
				break;
			default:
				$sql = "select * from $mysql_users_table where supporter=1 and user_name != 'support_pool' order by id asc limit $low, $users_limit";
				break;

		}
	}
	
	//grab the information for all users.
	if($group == "users"){
		switch($order){
			case ("user_name"):
				$sql = "select * from $mysql_users_table where user_name != 'support_pool' order by user_name asc limit $low, $users_limit";
				break;
			case ("office"):
				$sql = "select * from $mysql_users_table where user_name != 'support_pool' order by office asc limit $low, $users_limit";
				break;
			default:
				$sql = "select * from $mysql_users_table where user_name != 'support_pool' order by id asc limit $low, $users_limit";
				break;

		}
	}



	$result = $db->query($sql);

	//get all of the data into readable variables.
	while($row = $db->fetch_array($result)){
		$id = $row['id'];
		$first = ucwords($row['first_name']);
		$last = ucwords($row['last_name']);
		$user_name = $row['user_name'];
		$email = $row['email'];
		if($email == '')
			$email = '&nbsp;';
		$pager = $row['pager_email'];
		if($pager == '')
			$pager = '&nbsp;';
		$office = $row['office'];
		if($office == '')
			$office = '&nbsp;';
		$user = $row['user'];
		$supp = $row['supporter'];
		$admin = $row['admin'];

	//print out the html crap...this is ugly.
	echo '	<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
				<tr> 
				<td> 
					<table cellSpacing=1 cellPadding=5 width="100%" border=0>
						<tr> 
							<td class=info align=left align=center><b>'.$lang_infoforuser.' '. $id . '</b></td>
							<td class=info align=left align=center>';
								echo "<a class=info href=\"".$admin_site_url."/control.php?t=users&act=uedit&id=$id\">$lang_edit</a>,
								<a class=info href=index.php?t=";
									switch ($group){
										case ("admins"):
											echo "a";
											break;
										case ("supporters"):
											echo "s";
											break;
										case ("users"):
											echo "u";
											break;
										default:
											echo "u";
											break;
									}
									
										echo 'list&m=delete&id='.$id.'>'.$lang_delete.'</a>, or
										<a class=info href=index.php?t=tstats&id='.$id.'>'.$lang_stats.'</a>?</td>
						</tr>		
					
						<tr>
							<td width=27% class=back2 align=right>'.$lang_username.':</td><td class=back>'. $user_name .'</td>
						</tr>
						<tr>
							<td width=27% class=back2 align=right>'.$lang_realname.':</td><td class=back>'. $first .' '. $last .'</td>
						</tr>
						<tr>
							<td width=27% class=back2 align=right>'.$lang_email.'</td><td class=back><a href=mailto:'. $email .'>'.$email.'</td>
						</tr>
						<tr>
							<td width=27% class=back2 align=right>'.$lang_office.':</td><td class=back>'. $office .'</td>
						</tr>
					</table>
				</td>
				</tr>
			</table>
		<br>';

	}	//end while

}

/***********************************************************************************************************
**	function getUserId():
**		Takes a string as an argument.  Takes the user name and returns the id of that user in the user
**	table in the database.
************************************************************************************************************/
function getUserID($name)
{
	global $mysql_users_table, $db;

	$sql = "select id from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function getGroupId():
**		Takes a string as an argument.  Takes the group name and returns the id of that group in the group
**	table in the database.
************************************************************************************************************/
function getGroupID($name)
{
	global $mysql_sgroups_table, $db;

	$sql = "select id from $mysql_sgroups_table where group_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function getUGroupId():
**		Takes a string as an argument.  Takes the group name and returns the id of that group in the group
**	table in the database.
************************************************************************************************************/
function getUGroupID($name)
{
	global $mysql_ugroups_table, $db;

	$sql = "select id from $mysql_ugroups_table where group_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function getGroupName():
**		Takes an integer as an argument.  Takes the group id and returns the name of that group in the group
**	table in the database.
************************************************************************************************************/
function getGroupName($id)
{
	global $mysql_sgroups_table, $db;

	$sql = "select group_name from $mysql_sgroups_table where id=$id";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function groupExists():
**		Takes an integer as an argument.  Takes the group id and returns true if that group exists,
**	otherwise returns false.
************************************************************************************************************/
function groupExists($id)
{
	global $mysql_sgroups_table, $db;

	$sql = "SELECT group_name from $mysql_sgroups_table where id=$id";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows == 0){
		return false;
	}
	else{
		return true;
	}

	//can't get here, but...
	return false;

}

/***********************************************************************************************************
**	function getPriority():
**		Takes an integer as an argument.  Takes the integer and returns the value of that id in the priority
**	table in the database.
************************************************************************************************************/
function getPriority($id)
{
	global $mysql_tpriorities_table, $db;

	$sql = "select priority from $mysql_tpriorities_table where id='$id'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getStatus():
**		Takes an integer as an argument.  Takes the integer and returns the value of that id in the status
**	table in the database.
************************************************************************************************************/
function getStatus($id)
{
	global $mysql_tstatus_table, $db;

	$sql = "select status from $mysql_tstatus_table where id='$id'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}



/***********************************************************************************************************
**	function isSupporter():
**		Takes a string as an argument.  Queries the database and returns true if the supporter flag is set
**	to 1.  Else, returns false.
************************************************************************************************************/
function isSupporter($name)
{
	global $mysql_users_table, $db;

	$sql = "select supporter from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	if($row['supporter'] == 1){
		return true;
	}
	else{
		return false;
	}
	return false;		//just in case.
}

/***********************************************************************************************************
**	function isAdministrator():
**		Takes a string as an argument.  Queries the database and returns true if the admin flag is set
**	to 1.  Else, returns false.
************************************************************************************************************/
function isAdministrator($name)
{
	global $mysql_users_table, $db;

	$sql = "select admin from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	if($row[0] == 1){
		return true;
	}
	else{
		return false;
	}

}

/***********************************************************************************************************
**	function getsGroup():
**		Takes an integer as input.  Queries the supporter groups table and returns the group name associated
**	with the id that is given.
************************************************************************************************************/
function getsGroup($id)
{
	global $mysql_sgroups_table, $db;

	$sql = "select group_name from $mysql_sgroups_table where id=$id";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getGroupList():
**		Takes two arguments.  Queries the supporter group tables and gets a list of all sgroups in an array.
**	If the flag is not set, prints out the members of each group if the name given is in that particular
**	group.  If the flag is set, group members are not listed.  In both cases, the array of sgroups is 
**	returned.
************************************************************************************************************/
function getGroupList($name, $flag=1)
{
	global $mysql_sgroups_table, $db;

	$sql = "select id from $mysql_sgroups_table where id != 1";
	$result = $db->query($sql);
	$i = 0;
	while ($row = $db->fetch_row($result)){
		$group[$i] = "sgroup" . $row[0];
		$i++;
		
	}
	//now list contains a list of all the groups....now we have to cycle through that list
	//and determine whether the logged in user is in each group.

	if($name != '' && $flag != 1){
		for($i=0; $i<sizeof($group); $i++){
			if(inGroup($name, $group[$i])){
				listGroupMembers($group[$i]);
			}
		}
	}

	return $group;

}

/***********************************************************************************************************
**	function getUGroupList():
**		Takes no arguments.  Queries the user group tables and gets a list of all ugroups in an array.
**	The array of ugroups is returned.
************************************************************************************************************/
function getUGroupList()
{
	global $mysql_ugroups_table, $db;

	$sql = "select id from $mysql_ugroups_table where id != 0";
	$result = $db->query($sql);
	$i = 0;
	while ($row = $db->fetch_row($result)){
		$group[$i] = "ugroup" . $row[0];
		$i++;
		
	}
	//now list contains a list of all the groups

	return $group;

}


/***********************************************************************************************************
**	function inGroup():
**		Takes two arguments.  Takes the group id, and the user name.  Returns true if the user name given is
**	a member of the group given.  Otherwise, returns false.
************************************************************************************************************/
function inGroup($user_name, $group_id)
{
	global $db;

	$sql = "SELECT * from " . $group_id . " where user_name='$user_name'";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows == 0)
		return false;
	else
		return true;

}

/***********************************************************************************************************
**	function getuGroup():
**		Takes an integer as input.  Queries the user groups table and returns the group name associated
**	with the id that is given.
************************************************************************************************************/
function getuGroup($id)
{

	global $mysql_ugroups_table, $db;

	$sql = "select group_name from $mysql_ugroups_table where id=$id";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getMessage():
**		Takes an integer value as input.  Queries the announcement table and returns the announcement
**	associated with the given id number.
************************************************************************************************************/
function getMessage($id)
{
	global $mysql_announcement_table, $db;

	$sql = "select message from $mysql_announcement_table where id=$id";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function printError():
**		Takes a string as input.  Outputs the error message in a nice table format.
************************************************************************************************************/
function printError($error)
{
	global $lang_error;

	echo '<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
				<TR> 
				<TD class=info align=center><B>'.$lang_error.'</B></TD>
				</TR>

				<tr><td class=error><br><b>';
					echo $error . "</b><br><br>";
				echo '</td></tr>
			</table>
			</td>
			</tr>
			</table>';


}

/***********************************************************************************************************
**	function printSuccess():
**		Takes a string as input.  Outputs the message in a nice table format.
************************************************************************************************************/
function printSuccess($msg)
{
	global $lang_success;

	echo '<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
				<TR> 
				<TD class=info align=center><B>'.$lang_success.'</B></TD>
				</TR>

				<tr><td class=error><br><b><font color=green>';
					echo $msg . "</font></b><br><br>";
				echo '</td></tr>
			</table>
			</td>
			</tr>
			</table>';


}

/***********************************************************************************************************
**	function getRank():
**		Takes a two strings as input.  Second string is the table to query.  First string is the text to 
**	query the table for.  Returns the rank value of the given text.
************************************************************************************************************/
function getRank($string, $table)
{
	global $mysql_tpriorities_table, $mysql_tstatus_table, $db, $lang_tableerror;

	switch($table){
		case ($mysql_tpriorities_table):
			$sql = "select rank from $table where priority=\"$string\"";
			break;
		case ($mysql_tstatus_table):
			$sql = "select rank from $table where status=\"$string\"";
			break;
		default:
			printError("$lang_tableerror");
			exit;
	}

	$result = $db->query($sql);
	$row = $db->fetch_row($result);
	return $row[0];
}

/***********************************************************************************************************
**	function getRPriority():
**		Takes an integer as input.  The integer value is the rank.  Select the name of the priority based on
**	the rank and return the string.
************************************************************************************************************/
function getRPriority($rank)
{
	global $mysql_tpriorities_table, $db;

	$sql = "select priority from $mysql_tpriorities_table where id=$rank";
		
	$result = $db->query($sql);
	$row = $db->fetch_row($result);
	return $row[0];
	
}

/***********************************************************************************************************
**	function getRStatus():
**		Takes an integer as input.  The integer value is the rank.  Select the name of the status based on
**	the rank and return the string.
************************************************************************************************************/
function getRStatus($rank)
{
	global $mysql_tstatus_table, $db;

	$sql = "select status from $mysql_tstatus_table where id=$rank";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getHighestRank():
**		Takes one argument.  If the table is the ticket status table, the ranking is reversed so there is a
**	different sql statement.  Selects the item in the table that has the highest rank and returns the id.
************************************************************************************************************/
function getHighestRank($table)
{
	global $mysql_tstatus_table, $db;

	if($table == $mysql_tstatus_table) {
        $sql = "select id from $table order by rank desc";
    }else{
		$sql = "select id from $table order by rank asc";
	}
	
	$result = $db->query($sql);
	$row = $db->fetch_row($result);
	return $row[0];

}


/***********************************************************************************************************
**	function getLowestRank():
**	+++ gets the drafult_new status
**		Takes one argument.  If the table is the ticket status table, the ranking is reversed so there is a
**	different sql statement.  Selects the item in the table that has the highest rank and returns the id.
************************************************************************************************************/
function getLowestRank($table)
{
	global $mysql_tstatus_table, $db;

	if($table == $mysql_tstatus_table){
		$sql = "select id, default_create from $table order by rank asc";
	}
	else{
		$sql = "select id, default_create from $table order by rank desc";
	}
	
	$result = $db->query($sql);
	
	while($row = $db->fetch_array($result)){
	     
	     if ($row["default_create"]) return $row[0];
	}
}

/***********************************************************************************************************
**	function getHoldRank():
**		Takes one argument.  If the table is the ticket status table, the ranking is reversed so there is a
**	different sql statement.  Selects the item in the table that has the highest rank and returns the id.
************************************************************************************************************/
function getHoldRank($table)
{
	global $mysql_tstatus_table, $db;

	if($table == $mysql_tstatus_table){
		$sql = "select id, default_create from $table order by rank asc";
	}
	else{
		$sql = "select id, default_create from $table order by rank desc";
	}
	
	$result = $db->query($sql);
	
	$row = $db->fetch_array($result);
        return $row[0];
	
}

/**********************************************************************************************************
**	function getSecondStatus():
**		Takes no arguments.  Selects the second item in the table that has the lowest rank and returns the
**	status.
*********************************************************************************************************** */
function getSecondStatus()
{
	global $mysql_tstatus_table, $db;

	$sql = "select status from $mysql_tstatus_table order by rank asc";
	$result = $db->query($sql);
	for($i=0; $i<2; $i++){
		$row = $db->fetch_row($result);
	}
	return $row[0];

}

/***********************************************************************************************************
**	function getSecondStatus():
**		Takes no arguments.  Selects the second item in the table that has the lowest rank and returns the
**	status.
************************************************************************************************************/
function getSecondPriority()
{
	global $mysql_tpriorities_table, $db;

	$sql = "select priority from $mysql_tpriorities_table order by rank asc";
	$result = $db->query($sql);
	for($i=0; $i<2; $i++){
		$row = $db->fetch_row($result);
	}
	return $row[0];

}


/***********************************************************************************************************
**	function getPriorityList():
**		Takes no arguments.  Queries the ticket priority table and returns an array containing each element
**	in the table orderd by rank.
************************************************************************************************************/
function getPriorityList()
{
	global $mysql_tpriorities_table, $db;

	$sql = "select priority from $mysql_tpriorities_table order by rank asc";
	$result = $db->query($sql);
	$i = 0;
	while ($row = $db->fetch_row($result)){
		$list[$i] = $row[0];
		$i++;
	}

	return $list;
}


/***********************************************************************************************************
**	function getCategoryList():
**		Takes no arguments.  Queries the ticket categories table and returns an array containing each element
**	in the table orderd by rank.
************************************************************************************************************/
function getCategoryList()
{
	global $mysql_tcategories_table, $db;

	$sql = "select category from $mysql_tcategories_table order by rank asc";
	$result = $db->query($sql);
	$i = 0;
	while ($row = $db->fetch_row($result)){
		$list[$i] = $row[0];
		$i++;
	}

	return $list;
}

/***********************************************************************************************************
**	function getStatusList():
**		Takes no arguments.  Queries the ticket status table and returns an array containing each element
**	in the table orderd by rank.
************************************************************************************************************/
function getStatusList()
{
	global $mysql_tstatus_table, $db;

	$sql = "select status from $mysql_tstatus_table order by rank asc";
	$result = $db->query($sql);
	$i = 0;
	while ($row = $db->fetch_row($result)){
		$list[$i] = $row[0];
		$i++;
	}

	return $list;
}


/***********************************************************************************************************
**	function createHeader():
**		Takes one argument.  Creates the html associated with the header.
************************************************************************************************************/
function createHeader($msg)
{

echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info align=center><B>';
						echo $msg;
echo '				</td>
					</TR>		
				</table>
			</td>
			</tr>
		</table><br>';

}


/***********************************************************************************************************
 **	function createUserGroupMenu():
 **		Takes one argument.  Creates the group drop down menu based on the data in the ugroups table.  If
 **	the flag is set to 0, or not set, the value of each group is set for the ticket creation.  If the flag
 **	is set to 1, the value of each group is set for ticket updating.
 ************************************************************************************************************/

function createUserGroupMenu($flag=0)
{
	global $mysql_ugroups_table, $sg, $info, $id, $db;
//we do have the information for info here.  In the case of creating a ticket, info array is empty.
//in the case of updating a ticket, info array is full of stuff.
	$sql = "select id, group_name from $mysql_ugroups_table order by rank asc";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if ($flag == 0 || !isset($flag)) {
		while ($row = $db->fetch_array($result)) {

			if ($num_rows == 1 || $row[id] != 1) {
				echo "<option value=\"index.php?t=tcre&sg=$row[id]\"";
				if ($sg == $row[id] || $info[groupid] == $row[id]) {
					echo " selected";
				}
				echo ">" . $row[group_name] . "</option>";
			}
		}
	}
//flag is 1 is being called from tupdate.php
	if ($flag == 1) {
		while ($row = $db->fetch_array($result)) {
			if ($num_rows == 1 || $row[id] != 1) {
				echo "<option value=\"index.php?t=tupd&sg=$row[id]&id=$id&groupid=change\"";
				if ($sg == $row[id] || $info['groupid'] == $row[id]) {
					echo " selected";
				}
				echo ">" . $row[group_name] . "</option>";
			}
		}
	}

//flag is 2 if being called from tsearch.php
	if ($flag == 2) {

		echo "<option></option>";
		while ($row = $db->fetch_row($result)) {
			if ($num_rows == 1 || $row[0] != 1) {
				echo "<option value=\"$row[0]\"";
				if ($sg == $row[0] || $info['groupid'] == $row[0]) {
					echo " selected";
				}
				echo ">" . $row[1] . "</option>";
			}
		}
	}
}


/***********************************************************************************************************
**	function createGroupMenu():
**		Takes one argument.  Creates the group drop down menu based on the data in the sgroups table.  If
**	the flag is set to 0, or not set, the value of each group is set for the ticket creation.  If the flag
**	is set to 1, the value of each group is set for ticket updating.
************************************************************************************************************/
function createGroupMenu($flag=0)
{
	global $mysql_sgroups_table, $sg, $info, $id, $db;

//we do have the information for info here.  In the case of creating a ticket, info array is empty.
//in the case of updating a ticket, info array is full of stuff.
	$sql = "select id, group_name from $mysql_sgroups_table order by rank asc";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if ($flag == 0 || !isset($flag)) {
		while ($row = $db->fetch_array($result)) {
			if ($num_rows == 1 || $row[id] != 1) {
				echo "<option value=\"index.php?t=tcre&sg=$row[id]\"";
				if ($sg == $row[id] || $info[groupid] == $row[id]) {
					echo " selected";
				}
				echo ">" . $row[group_name] . "</option>";
			}
		}
	}
//flag is 1 is being called from tupdate.php
	if ($flag == 1) {
		while ($row = $db->fetch_array($result)) {
			if ($num_rows == 1 || $row[id] != 1) {
				echo "<option value=\"index.php?t=tupd&sg=$row[id]&id=$id&groupid=change\"";
				if ($sg == $row[id] || $info['groupid'] == $row[id]) {
					echo " selected";
				}
				echo ">" . $row[group_name] . "</option>";
			}
		}
	}

//flag is 2 if being called from tsearch.php
	if ($flag == 2) {

		echo "<option></option>";
		while ($row = $db->fetch_row($result)) {
			if ($num_rows == 1 || $row[0] != 1) {
				echo "<option value=\"$row[0]\"";
				if ($sg == $row[0] || $info['groupid'] == $row[0]) {
					echo " selected";
				}
				echo ">" . $row[1] . "</option>";
			}
		}
	}


}


/***********************************************************************************************************
**	function createPriorityMenu():
**		Takes no arguments.  Creates the drop down menu for the list of priorities.
************************************************************************************************************/
function createPriorityMenu($flag=0, $all=true)
{
	global $mysql_tpriorities_table, $info, $db;
	if ($all) {
		$sql = "select priority from $mysql_tpriorities_table order by rank desc";
	}
	else {
		$sql = "select priority from $mysql_tpriorities_table where rank < 9999 order by rank desc";
	}
	$result = $db->query($sql, $mysql_tpriorities_table);
	$num_rows = $db->num_rows($result);

	if($info['priority'] == '' && $flag != 2){
		$select = floor($num_rows / 2);
		$i=0;
	}

	if($flag == 1 || $flag == 2)
		echo "<option></option>";

	while($row = $db->fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['priority'] == '' && $i == $select && isset($i))
				echo "selected";
			if($info['priority'] == $row[0])
				echo "selected";

			echo "> $row[0] </option>";
			$i++;
	}
}


/***********************************************************************************************************
 **	function createBillingStatusMenu():
 **		Takes no arguments.  Creates the drop down menu for the billing status list.
 ************************************************************************************************************/
function createBillingStatusMenu($flag = 0, $new = 0)
{
    global $mysql_tBillingStatus_table, $info, $db;

    $sql = "select status, default_create, icon_ref from $mysql_tBillingStatus_table order by rank asc";
    $result = $db->query($sql, $mysql_tBillingStatus_table);

    if($flag == 1)
        echo "<option></option>";

    while($row = $db->fetch_array($result)){
        echo "<option value=\"$row[status]\" ";
        if ($new){
            if($row['default_create']) echo "selected";
        }
        else{
            if($info['status'] == $row['status']) echo "selected";
        }
        echo "> $row[status]  </option><br>";

    }

}
/***********************************************************************************************************
**	function createStatusMenu():
**		Takes no arguments.  Creates the drop down menu for the status list.
************************************************************************************************************/
function createStatusMenu($flag = 0, $new = 0)
{
	global $mysql_tstatus_table, $info, $db;

	$sql = "select status, default_create from $mysql_tstatus_table order by rank asc";
	$result = $db->query($sql, $mysql_tstatus_table);

	if($flag == 1)
		echo "<option></option>";

	while($row = $db->fetch_array($result)){
		echo "<option value=\"$row[status]\" ";
			if ($new){ 
			  if($row['default_create']) echo "selected";
			}
			else{
			  if($info['status'] == $row['status']) echo "selected";
			}
			echo "> $row[status] </option><br>";
			
	}

}


/***********************************************************************************************************
**	function createThemeMenu():
**		Takes no arguments.  Creates the drop down menu for the theme list.
************************************************************************************************************/
function createThemeMenu($flag = 0)
{
	global $mysql_themes_table, $user_info, $default_theme, $db;

	$sql = "select name from $mysql_themes_table";

	$result = $db->query($sql);

	if($flag == 1){
		while($row = $db->fetch_row($result)){
			echo "<option value=\"$row[0]\" ";
				if($default_theme == $row[0]) echo "selected";
				echo "> $row[0] </option>";
		}
	}

	else{
		echo "<option value=\"default\"> Default </option>";
		while($row = $db->fetch_row($result)){
			echo "<option value=\"$row[0]\" ";
				if($user_info['theme'] == $row[0]) echo "selected";
				echo "> $row[0] </option>";
		}
	}

	return $row;

}

/***********************************************************************************************************
**	function createLanguageMenu():
**		Takes no arguments.  Creates the drop down menu for the language list.
************************************************************************************************************/
function createLanguageMenu($flag=0)
{	
	//scan the lang directory and create the menu based on the language files present

	global $language, $default_language;
	
	if($flag == 0)
		$path = "../lang";
	elseif ($flag == 1)
		$path = "lang";
	else
		$path = "../lang";

	if($flag == 2)
		$language = $default_language;

	echo "<select name=\"langfile\">\n";

	$dir = opendir("$path");	
	while ($thafile = readdir($dir)) {
		if (is_file("$path/$thafile")) {
			$thafile = str_replace(".lang.php", "", $thafile);
			echo $thafile . "<br>";
			if ($thafile == $language) {
				echo "<option value=\"$thafile\" selected=\"selected\">$thafile</option>\n";
			}
			else {
				echo "<option value=\"$thafile\">$thafile</option>\n";
			}
		}
	}

	echo "</select>";
}

/***********************************************************************************************************
**	function createTimeOffsetMenu():
**		Takes no arguments.  Creates the drop down menu for the language list.
************************************************************************************************************/
function createTimeOffsetMenu($selected)
{
	global $lang_timezone1, $lang_timezone2, $lang_timezone3, $lang_timezone4, $lang_timezone5, $lang_timezone6,
			$lang_timezone7, $lang_timezone8, $lang_timezone9, $lang_timezone10, $lang_timezone11, $lang_timezone12,
			$lang_timezone13, $lang_timezone14, $lang_timezone15, $lang_timezone16, $lang_timezone17, $lang_timezone18,
			$lang_timezone19, $lang_timezone20, $lang_timezone21, $lang_timezone22, $lang_timezone23, $lang_timezone24,
			$lang_timezone25, $lang_timezone26, $lang_timezone27, $lang_timezone28, $lang_timezone29, $lang_timezone30,
			$lang_timezone31, $lang_timezone32, $lang_timezone33, $db, $mysql_users_table, $time_offset;
	$j=1;
	for($i=-12; $i<14; $i++){
		$zone = "lang_timezone" . $j;
		echo "<option value=\"$i\" ";
		if($time_offset == $i)
			echo "selected";
		echo ">".$$zone."</option>";
		$j++;
	}



}
function displayTicket($result)
{
	global $cookie_name, $mysql_ugroups_table, $lang_summary, $lang_recordcount, $supporter_site_url, $highest_pri, $theme, $db, $admin_site_url, $mysql_BillingStatus_table;
    $second = getSecondPriority();
    $sql3 = "select * from $mysql_ugroups_table ";
    $sqlBS = "select * from $mysql_BillingStatus_table";
    $recordcount = 0;
    $csv_string = "";
    $closed_ts = 0;

    while ($row = $db->fetch_array($result))
    {
        $last_update = $row['lastupdate'];  //last update timestamp.

        echo "<tr>
				<td class=back>" . str_pad($row['id'], 5, "0", STR_PAD_LEFT) . "</td>";
        if (isAdministrator($cookie_name)) {
            echo "<td class=back2><a href=\"" . $admin_site_url . "/control.php?t=users&act=uedit&id=" . getUserID($row['supporter']) . "\">" . $row['supporter'] . "</td>";
        } else {
            echo "<td class=back2><a href=\"index.php?t=memb&mem=" . $row['supporter'] . "\">" . $row['supporter'] . "</td>";
        }
        echo "<td class=\"back\">";
        echo stripslashes($row['equipment']) . "</td>";

        echo "<td class=\"back2\">";
        echo "<a href=\"?t=tupd&id=" . $row['id'] . "\">";
        echo stripslashes($row['short']) . "</a></td>
			
				<td class=back>" . $row['user'] . "</td>";
        $grp_name = 'NONE';
        $resultgroup = $db->query($sql3);
        while ($row2 = $db->fetch_array($resultgroup)) {
            if ($row2['id'] == $row['ugroupid']) {
                $grp_name = $row2['group_name'];
            }
        }

        echo "<td class=back2>" . $grp_name . "</td>

				<td class=back>";

        switch ($row['priority']) {
            case ("$highest_pri"):
                echo "<font color=red><b>" . $row[priority] . "</b></font>";
                break;
            case ($second):
                echo "<b>" . $row[priority] . "</b>";
                break;
            default:
                echo $row[priority];
                break;
        }

        echo "</td>
				<td class=back2> " . date("m/d/y", $row[create_date]) . "</td>";
        echo "<td class=back> " . date("m/d/y", $row[lastupdate]) . "</td>";

        //cookie_name='.$cookie_name.'
        echo "<td class=back>";
        $resultBStatus = $db->query($sqlBS);
        while ($row2 = $db->fetch_array($resultBStatus)) {
            if ($row2['id'] == $row['BILLING_STATUS']) {
                $bsIconRef = $row2['icon_ref'];
            }
        }
        echo '<a href="updatelog.php?&id=' . $row[id] . '" target="myWindow" onClick="window.open(\'\', \'myWindow\',
					\'location=no, status=yes, scrollbars=yes, height=500, width=600, menubar=no, toolbar=no, resizable=yes\')">';

        echo $row[status];
        echo "</a></td>";
        echo "<td class=back align=center><img height=28 src=\"../$theme[image_dir]$bsIconRef\"></td>";

        $response = setResponse($last_update, $row[priority], $row[id]);

        switch ($response) {
            case('1'):
                echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass1.gif\"></td>";
                break;
            case('2'):
                echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass2.gif\"></td>";
                break;
            case('3'):
                echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass3.gif\"></td>";
                break;
            case('4'):
                echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass4.gif\"></td>";
                break;
            default:
                echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass1.gif\"></td>";
                break;
        }

        echo "</tr>";
        $recordcount++;
        $csv_string = $csv_string . $row['id'] . ",";
    }

    /*  while ($row = $db->fetch_array($result)) {
           $row_is_closed = 0;
           $last_update = $row['lastupdate'];  //last update timestamp.
           $cs = getHighestRank($mysql_status_table);
           if ( $row['status'] == $cs ){
               $closed_ts = $row['closed_date'];
               $row_is_closed = 1;
           } //closed timestamp


           echo "<tr>
                   <td class=back>" . str_pad($row['id'], 5, "0", STR_PAD_LEFT) . "</td>";
           if (isAdministrator($cookie_name)) {
               echo "<td class=back2><a href=\"" . $admin_site_url . "/control.php?t=users&act=uedit&id=" . getUserID($row['supporter']) . "\">" . $row['supporter'] . "</td>";
           } else {
               echo "               echo "<td class=back2>=" . $row['supporter'] . "\">" . $row['supporter'] . "</td>";

           echo "<td class=\"back\">";
           echo stripslashes($row['equipment']) . "</td>";


           echo "<td class=\"back2\">";
           echo "<a href=\"?t=tupd&id=" . $row['id'] . "\">";
           echo stripslashes($row['short']) . "</a></td>

                   <td class=back>" . $row['user'] . "</td>";
           $grp_name = 'NONE';
           $resultgroup = $db->query($sql3);
           while ($row2 = $db->fetch_array($resultgroup)) {
               if ($row2['id'] == $row['ugroupid']) {
                   $grp_name = $row2['group_name'];
               }
           }

           echo "<td class=back2>" . $grp_name . "</td>

                   <td class=back>";

           switch ($row['priority']) {
               case ("$highest_pri"):
                   echo "<font color=red><b>" . $row[priority] . "</b></font>";
                   break;
               case ($second):
                   echo "<b>" . $row[priority] . "</b>";
                   break;
               default:
                   echo $row[priority];
                   break;
           }

           echo "</td>
                   <td class=back2> " . date("m/d/y", $row[create_date]) . "</td>";
           echo "<td class=back> " . date("m/d/y", $row[lastupdate]) . "</td>";

           //cookie_name='.$cookie_name.'
           echo "<td class=back>";
           $resultBStatus = $db->query($sqlBS);
           while ($row2 = $db->fetch_array($resultBStatus)) {
               if ($row2['id'] == $row['BILLING_STATUS']) {
                   $bsIconRef = $row2['icon_ref'];
               }
           }
           echo '<a href="updatelog.php?&id=' . $row[id] . '" target="myWindow" onClick="window.open(\'\', \'myWindow\',
                       \'location=no, status=yes, scrollbars=yes, height=500, width=600, menubar=no, toolbar=no, resizable=yes\')">';

           echo ($row_is_closed) ? $row[status] : date("m/d/y", $closed_ts) ;
           echo "</a></td>";
           echo "<td class=back align=center><img height=28 src=\"../$theme[image_dir]$bsIconRef\"></td>";

           $response = setResponse($last_update, $row[priority], $row[id]);

           switch ($response) {
               case('1'):
                   echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass1.gif\"></td>";
                   break;
               case('2'):
                   echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass2.gif\"></td>";
                   break;
               case('3'):
                   echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass3.gif\"></td>";
                   break;
               case('4'):
                   echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass4.gif\"></td>";
                   break;
               default:
                   echo "<td class=back align=center><img height=20 src=\"../$theme[image_dir]hourglass1.gif\"></td>";
                   break;
           }

           echo "</tr>";
           $recordcount++;
           $csv_string = $csv_string . $row[id] . ",";
       }
       */


    endTable();

    $linkString= "<a href=$supporter_site_url/index.php?t=time&tids=\"$csv_string\">"."\"link to CSV list\"";
    echo '<form name="formTimeTrack"  action="index.php?t=time" method=GET>';
    echo '<input type="hidden" name="t" value="time">';
	echo '<input type="hidden" name="tids" value="$csv_string">';
    echo '<input type="hidden" value="$lang_printstats" name="hidemenu">';
    ?>
	<a href="#" onClick="document.formTimeTrack.submit();"> <?php echo "Time Track"; ?>!</a>

	</form>
	<?php
/*    echo "<form method=post>";
    startTable("$lang_timetracking", "center");
    echo "<tr><td class=back><br>";
    startTable("$lang_selecttickets", "center", "80%");
    echo "<tr><td class=cat>$lang_selectticketsexp</td></tr>";
    echo "<tr><td class=back2><br>$lang_ticket $lang_ids: <input type=text name=tids size=60% value=$csv_string><br><br></td></tr>";
    endTable();
    echo "<center><input type=submit value=\"$lang_getstats\" name=\"getstats\"> ";
    echo "<input type=submit value=\"$lang_printstats\" name=\"hidemenu\"></center><br>";
    echo "</td></tr>";
    endTable();
    echo "</form>";
*/
    $summary = array("recordcount" => $recordcount, "remarks" => "list (CSV):", "tktlist" => $linkString);
    echo "$lang_summary: $lang_recordcount $summary[recordcount] $summary[remarks]  $summary[tktlist]";
    return $summary;

}
/***********************************************************************************************************
**	function createTicketInfo():
**		Takes 2 arguments (attachement , equipmentgroupid).
**      Html code for displaying the information about a particular ticket.
************************************************************************************************************/
function createTicketInfo($flag='allow', $equipmentgroupid = 0)
{
	global $info, $enable_smtp, $cookie_name, $theme, $db, $lang_equipment, $lang_ticketinfo, $lang_platform, $lang_shortdesc, $lang_category, $lang_desc, $lang_email, $lang_user, $lang_update, $lang_attachment, $enable_tattachments;

		echo '	<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
				<tr> 
				<td> 
					<table cellSpacing=1 cellPadding=5 width="100%" border=0>
						<tr> 
							<td class=info align=left colspan=4 align=center><b>'.$lang_ticketinfo.'</b></td>
						</tr>		
						<tr>
							<td class=back2 width=27% align=right>* '.$lang_platform.':</td>
							<td width=20% class=back><select name=platform>'; createPlatformMenu(0);
							echo '	</select></td><td class=back2 width=100 align=right>* '.$lang_category.':</td>
							<td class=back><select name=category>';  createCategoryMenu(0);
							echo '	</select></td>
						</tr>
						<tr>
							<td width=27% class=back2 align=right>* '.$lang_equipment.':</td>
							<td class=back colspan=3><select name=equipment>';  createEquipmentMenu(0,$equipmentgroupid);
							echo '	</select></td>
						
						</tr>
						<tr>
							<td width=27% class=back2 align=right>* '.$lang_shortdesc.':</td>
							<td class=back colspan=3>
						
							<input type=text size=60 name=short value="'.stripslashes($info['short']).'">
							</td>
						
						</tr>
						<tr>

							<td class=back2 align=right valign=top width=27%>* '.$lang_desc.': </td>
							<td class=back colspan=3><textarea name=description rows=5 cols=60>'.stripslashes($info['description']).'</textarea></td>


						</tr>';
if(isset($info)){
	
	if($enable_smtp == "win" || $enable_smtp == "lin"){
		echo '

			<tr>
				<td class=back2 align=right valign=top width=27%> '.$lang_email.' '. $lang_user.': </td>
				<td class=back colspan=3 valign=bottom> <textarea name=email_msg rows=5 cols=60></textarea> </td>
			</tr>';
	}
	echo '
		<tr>

			<td class=back2 align=right valign=top width=27%> '.$lang_update.': </td>
			<td class=back colspan=3 valign=bottom> <textarea name=update_log rows=5 cols=60></textarea>

				<a href="updatelog.php?cookie_name='.$cookie_name.'&id='.$info['id'].'" target="myWindow" onClick="window.open(\'\', \'myWindow\',
					\'location=no, status=yes, scrollbars=yes, height=500, width=600, menubar=no, toolbar=no, resizable=yes\')">
					<img border=0 src="../'.$theme['image_dir'].'log_button.jpg"></a>

			</td>
		</tr>';
}
		if($enable_tattachments == 'On' && $flag == 'allow'){
			echo '<tr>
				<td class=back2 align=right valign=top width=27%>'.$lang_attachment.': </td>';
			
			echo "<td class=back colspan=3 valign=bottom>";
			//echo "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"1000000\">";
			echo "<input type=\"file\" name=\"the_file\" size=60>";

			echo '</td></tr>';
		}


echo '
					</table>
				</td>
				</tr>
			</table>
		<br>';

}
?>
<?php
/***********************************************************************************************************
**	function createUGroupsMenu():
**		Takes no arguments.  Creates the drop down menu 
************************************************************************************************************/
function createUGroupsMenu($flag)
{
	global $mysql_ugroups_table, $sg, $ug, $info, $id, $db;

	$sql = "select id, group_name from $mysql_ugroups_table order by rank asc";
	$result = $db->query($sql);
	$num_rows = 0;
	
	if($flag == 1)
		echo "<option></option>\n";


		while($row = $db->fetch_array($result)){
			if($num_rows == 1 || $row[id] != 0){
				echo "<option value=\"index.php?t=tcre&sg=$sg&ug=$row[id]\"";
					if($ug == $row[id]) echo " selected";
				echo ">".$row[group_name]."</option>";
				//set $ug to the 1st item as default
				If (! isset($ug)) $ug=$row[id];
				
			}
		}
	return $ug;
}
/***********************************************************************************************************
**	function createEquipmentMenu():
**		Argument : $equipmentgroupid, if 0 all equipment will be listed. 
**      Creates the drop down menu for the list of Equipment by current facility.
************************************************************************************************************/
function createEquipmentMenu($flag, $equipmentgroupid=0)
{
	global $mysql_tequipment_table, $info, $db;
        
	$sql = "select short from $mysql_tequipment_table where";
	if ($equipmentgroupid==0 ) 
	   $sql .=" groupid >= 1";
	else
	   $sql .=" groupid = $equipmentgroupid";
	// groupid - is equipment for all groups
	$sql .=" or groupid=0 order by rank asc";
	$result = $db->query($sql);
	
	if($flag == 1)
		echo "<option></option>\n";

	while($row = $db->fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['equipment'] == $row[0]) echo "selected";
			echo ">$row[0]</option>\n";
	}

}

/***********************************************************************************************************
**	function createCategoryMenu():
**		Takes no arguments.  Creates the drop down menu for the list of categories.
************************************************************************************************************/
function createCategoryMenu($flag)
{
	global $mysql_tcategories_table, $info, $db;

	$sql = "select category from $mysql_tcategories_table order by rank asc";
	$result = $db->query($sql);
	
	if($flag == 1)
		echo "<option></option>\n";

	while($row = $db->fetch_row($result)){
		echo "<option value=\"$row[0]\" ";
			if($info['category'] == $row[0]) echo "selected";
			echo ">$row[0]</option>\n";
	}

}

/***********************************************************************************************************
**	function createKCategoryMenu():
**		Takes no arguments.  Creates the drop down menu for the list of knowledge base categories.
************************************************************************************************************/
function createKCategoryMenu($flag=0, $category)
{
	global $mysql_kcategories_table, $info, $db;

	$sql = "select category from $mysql_kcategories_table order by category asc";
	$result = $db->query($sql);
	
	if($flag == 1)
		echo "<OPTION></OPTION>\n";

	if($category == ''){
		while($row = $db->fetch_row($result)){
			echo "<OPTION value=\"$row[0]\"";
				if($info['category'] == $row[0]) echo " selected";
				echo ">$row[0]</OPTION>\n";
		}
	}
	else{
		while($row = $db->fetch_row($result)){
			echo "<OPTION value=\"$row[0]\"";
				if($row[0] == $category) echo " selected";
				echo ">$row[0]</OPTION>\n";
		}
	}


}


/***********************************************************************************************************
**	function createPlatformMenu():
**		Takes no arguments.  Creates the drop down menu for the list of platforms.
************************************************************************************************************/
function createPlatformMenu($flag=0, $platform)
{
	global $mysql_platforms_table, $info, $db;

	$sql = "select platform from $mysql_platforms_table order by rank asc";
	$result = $db->query($sql);

	if($flag == 1)
		echo "<option></option>\n";
	
	if($platform == ''){
		while($row = $db->fetch_row($result)){
			echo "<option value=\"$row[0]\" ";
				if($info['platform'] == $row[0]) echo "selected";
				echo "> $row[0] </option>\n";
		}
	}
	else{
		//echo "We're here!";
		while($row = $db->fetch_row($result)){
			echo "<option value=\"$row[0]\" ";
				if($row[0] == $platform) echo "selected";
				echo "> $row[0] </option>\n";
		}
	}

}


function RefreshLastUpdateTime($id)
{
	global $mysql_tickets_table, $db, $cookie_name;
	$time_offset = getTimeOffset($cookie_name);
	$time = time() + ($time_offset * 3600);
	$sql = "UPDATE $mysql_tickets_table set lastupdate='$time' where id=$id";
	$db->query($sql);		//this sets the lastupdate time so we can compare later on.
}


/***********************************************************************************************************
**	function updateLog($ticket_id, $msg):
**		Takes an integer and a string as input.  The integer value is the ticket id number.  The string is
**	the message to append to the update log along with a timestamp.
************************************************************************************************************/
function updateLog($ticket_id, $msg)
{
	global $mysql_tickets_table, $cookie_name, $delimiter, $helpdesk_name, $db, $lang_transferred, $lang_statuschange, $lang_prioritychange, $lang_by, $lang_createdbyweb;
	$time_offset = getTimeOffset($cookie_name);
	$time = time() + ($time_offset * 3600);
	

	//grab the current update log from the tickets table.
	$log = getCurrentLog($ticket_id);
	$log = addslashes($log);

	//add italics for the transferred/status change/priority change message.
	if(ereg("^\$lang_transferred", $msg) || ereg("^\$lang_statuschange", $msg) || ereg("^\$lang_prioritychange", $msg)){
		$msg = "<i>" . $msg . "</i>";
	}

	if($msg != ''){	//only if the message actually contains text do we want to add it to the update log.
		if(eregi($lang_createdbyweb, $msg))
			$log .= $time . "$delimiter" . addslashes($msg) . "$delimiter";
		else
			$log .= "$time \$lang_by $cookie_name $delimiter" . addslashes($msg) . "$delimiter";
	}

	return $log;

}

/***********************************************************************************************************
**	function getCurrentLog():
**		Takes one argument.  Gets the current update log string of the ticket given the id and returns it.
************************************************************************************************************/
function getCurrentLog($id)
{
	global $mysql_tickets_table, $db;

	$sql = "select update_log from $mysql_tickets_table where id=$id";
	$result = $db->query($sql);

	$row = $db->fetch_row($result);

	//returns the entire contents of the update log as a string.
	return $row[0];

}

/***********************************************************************************************************
**	function deleteFromGroups():
**		Takes one argument.  Cycles through the list of supporter groups that the use is a member of and 
**	deletes that user from the group.  This is called when a user is deleted so that user is not left in 
**	each group.
************************************************************************************************************/
function deleteFromGroups($id)
{
	global $mysql_sgroups_table, $db;

	//first, create an array that contains all of the user groups the user is in.
	$sql = "select id from $mysql_sgroups_table where id!= 1";
	$result = $db->query($sql);
	$i=0;
	while($row = $db->fetch_array($result)){
		$sgroups_list[$i] = $row[0];
		$i++;
	}

	$sql = "select id from ugroups";
	$result = $db->query($sql);
	$i=0;
	while($row = $db->fetch_array($result)){
		$ugroups_list[$i] = $row[0];
		$i++;
	}

	//now both the sgroups list is filled and the ugroups list is filled.
	//now we can cycle through the array and delete the user from each table if they are a member.
	for($i=0; $i<sizeof($sgroups_list); $i++){
		$sql = "delete from sgroup" . $sgroups_list[$i] . " where user_id=$id";
		$db->query($sql);
	}

	for($i=0; $i<sizeof($ugroups_list); $i++){
		$sql = "delete from ugroup" . $ugroups_list[$i] . " where user_id=$id";
		$db->query($sql);
	}

}


/***********************************************************************************************************
**	function getTotalNumOpenTickets():
**		Takes one argument.  If the id is not set, this returns the total number of open tickets in the 
**	database.  If the id is set, it returns the total number of tickets that are open and assigned to the 
**	user with the given id.
************************************************************************************************************/
function getTotalNumOpenTickets($id)
{
	global $mysql_tickets_table, $mysql_tstatus_table, $status, $db;

	if(!isset($id) || $id == ''){
		$sql = "select count(id) from $mysql_tickets_table where status!='$status'";
	}
	else{
		$sql = "select count(id) from $mysql_tickets_table where status!='$status' and supporter_id=$id";
	}

	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function getTotalNumClosedTickets():
**		Takes one argument.  If the id is not set, this returns the total number of closed tickets in the 
**	database.  If the id is set, it returns the total number of tickets that are closed and assigned to the 
**	user with the given id.
************************************************************************************************************/
function getTotalNumClosedTickets($id)
{
	global $mysql_tickets_table, $mysql_tstatus_table, $status, $db;

	if(!isset($id) || $id == ''){
		$sql = "select count(id) from $mysql_tickets_table where status='$status'";
	}
	else{
		$sql = "select count(id) from $mysql_tickets_table where status='$status' and supporter_id=$id";
	}

	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}


/***********************************************************************************************************
**	function getTotalNumTickets():
**		Takes one argument.  If the id is not set, this returns the total number of tickets in the 
**	database.  If the id is set, it returns the total number of tickets that are assigned to the 
**	user with the given id.
************************************************************************************************************/
function getTotalNumTickets($id)
{
	global $mysql_tickets_table, $mysql_tstatus_table, $db;

	if(!isset($id) || $id == ''){
		$sql = "select count(id) from $mysql_tickets_table";
	}
	else{
		$sql = "select count(id) from $mysql_tickets_table where supporter_id=$id";
	}

	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

/***********************************************************************************************************
**	function getTotalNumOpenTickets():
**		Takes one argument.  Returns true if the email address given is valid (of the form a@b.c).
**	Otherwise returns false.
************************************************************************************************************/
function validEmail($address)
{
	if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address)) 
		return true;
	else
		return false;

}


/***********************************************************************************************************
**	function startTable():
**		Takes two arguments.  Starts the html table with a header included and the alignment of that
**	header.
************************************************************************************************************/
function startTable($msg, $align, $width=100, $colspan=1, $class=info)
{
	if($width == '')
		$width = '100';

	echo '<TABLE class=border cellSpacing=0 cellPadding=0 width="'.$width.'%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class='.$class.' colspan='.$colspan.' align='.$align.'><B>';
						echo $msg;
						echo '</B></td>
						</TR>	';	
}

/***********************************************************************************************************
**	function endTable():
**		Takes no arguments.  Html code that ends the table started with startTable().
************************************************************************************************************/
function endTable()
{
    echo '
		</table>
			</td>
			</tr>
		</table><br>';
}

/***********************************************************************************************************
**	function sendmail():
**		Takes six arguments.  The To address, from address, return path, ticket id number, and a message.
**	This is used for the *nix mail function.  By using this over the mail function provided with php, we can
**	override some header functions and set a return-to path that cannot be done otherwise.  This allows for
**	bogus email addresses not to choke the system.  Windows users will have this problem if there are invalid
**	(not in syntax) email addresses used.
**
**	Note:	This is all in English . . . for other languages, text must be modified manually.
**
************************************************************************************************************/
function sendmail($to, $from, $return, $id, $msg, $subject="")
{
	global $admin_email, $sendmail_path;
  
	$msg = stripslashes($msg);
	$mailprog = $sendmail_path . "sendmail -r '$admin_email' -t";

  $fd = popen($mailprog,"w");
	
	fputs($fd, "To: $to\n"); 
	if($subject == ''){
		fputs($fd, "Subject: Ticket $id\n");
	}
	else{
		fputs($fd, "Subject: $subject\n");
	}
	fputs($fd, "From: $from <$return>\n");
	fputs($fd, "Reply-To: $return\n");
	fputs($fd, "Return-Path: $return\n");
	fputs($fd, "$msg\n");
  pclose($fd);

}

function QueueMail($to, $from, $return, $id, $msg, $subject="")
{
	global $admin_email, $MailQueuePath;

  $msg = stripslashes($msg);
  list($usec, $sec) = explode(" ", microtime());
	$QueueFile = $MailQueuePath."MAILQ".(string)$sec.(string)$usec.".TXT";

	$fd = fopen($QueueFile, "w");
	
	 
	fputs($fd, "To: $to\n"); 
	if($subject == ''){
		fputs($fd, "Subject: Ticket $id\n");
	}
	else{
		fputs($fd, "Subject: $subject\n");
	}
	fputs($fd, "From: $from <$return>\n");
	fputs($fd, "Reply-To: $return\n");
	fputs($fd, "Return-Path: $return\n");
	fputs($fd, "$msg\n");

	fclose ($fd);
}

/***********************************************************************************************************
**	function getEmailAddress():
**		Takes one argument.  Returns the email address of the user name specified.
************************************************************************************************************/
function getEmailAddress($name)
{
	global $mysql_users_table, $db;

	$sql = "select email from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);

	$row = $db->fetch_row($result);
	return $row[0];

}


//this function takes an integer value (the number of seconds) and prints out the days, hours, minutes, and seconds.
function showFormattedTime($seconds, $flag=0)
{
	global $lang_na, $lang_day, $lang_days, $lang_hour, $lang_hours, $lang_minute, $lang_minutes, $lang_second, $lang_seconds;

	if($seconds <= 0){
		echo "<b>$lang_na</b>";
	}
	else{
		$days = (int) ($seconds / (24*60*60));
		$remainder = $seconds % (24*60*60);

		$hours = (int) ($remainder / (60*60));
		$remainder = $remainder % (60*60);

		$minutes = (int) ($remainder / 60);
		$seconds = $remainder % 60;

		if($days != 0){
			echo "$days";
			if($days > 1)
				echo " $lang_days";
			else
				echo " $lang_day";
			if($hours !=0) echo ", ";
		}

		if($hours !=0){
			echo "$hours";
			if($hours > 1)
				echo " $lang_hours";
			else
				echo " $lang_hour";
			if($minutes !=0) echo ", ";
		}

		if($minutes !=0){
			echo "$minutes";
			if($minutes > 1)
				echo " $lang_minutes";
			else
				echo " $lang_minute";

			if($seconds !=0) echo ", ";
			//comment all of these lines if you don't want to keep track of seconds as well.
			//if($flag == 0)
			//	echo ", ";
				
		}
		
		if($seconds != 0){
			echo "$seconds";
			if($seconds > 1)
				echo " $lang_seconds";
			else
				echo " $lang_second";
		}
			
	}
}

function listPlatforms()
{

	global $mysql_platforms_table, $db, $HTTP_REFERER, $lang_delete, $lang_rank;

	$sql = "select * from $mysql_platforms_table order by rank asc";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows != 0){
		$i = 0;
		while($row = $db->fetch_row($result)){
			echo "<input type=hidden name=id$i value='$row[0]'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=platform$i value=\"$row[2]\">";
			echo "&nbsp;&nbsp; $lang_rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			
			if(!eregi("kbase", $HTTP_REFERER))
				echo "&nbsp;&nbsp;<a href=control.php?t=topts&act=tpla&rm=delete&id=$row[0]>$lang_delete</a>?";
			else
				echo "&nbsp;&nbsp;<a href=control.php?t=kbase&act=plat&rm=delete&id=$row[0]>$lang_delete</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
	}

	return $num_rows;

}


function getNumPlatforms()
{
	global $mysql_platforms_table, $db;

	$sql = "select count(platform) from $mysql_platforms_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];

}


function listKCategories()
{

	global $mysql_kcategories_table, $db, $lang_delete;

	$sql = "select * from $mysql_kcategories_table order by category asc";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows != 0){
		$i = 0;
		while($row = $db->fetch_array($result)){
			echo "<input type=hidden name=id$i value='".$row['id']."'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=category$i value=\"".$row['category']."\">";
			//echo "&nbsp;&nbsp; Rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo "&nbsp;&nbsp;<a href=control.php?t=kbase&act=cate&rm=delete&id=".$row['id'].">$lang_delete</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
	}

	return $num_rows;

}

function getNumKCategories()
{
	global $mysql_kcategories_table, $db;

	$sql = "select count(category) from $mysql_kcategories_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];

}

function createKBMenu()
{
	global $mysql_kcategories_table, $mysql_platforms_table, $lang_searchfor, $lang_incategory, $lang_under;

	echo "<b>$lang_searchfor: </b>";
	echo "<input type=text name=item> $lang_incategory <select name=category>";
		createKCategoryMenu(1);
	echo "</select> $lang_under ";
	echo "<select name=platform>";
		createPlatformMenu(1);
	echo"</select> ";

}

function makeClickable($text)
{
    $ret = eregi_replace( "([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])",  "<a href=\"\\1://\\2\\3\" target=\"_blank\" target=\"_new\">\\1://\\2\\3</a>", $text);
    $ret = eregi_replace( "(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))",  "<a href=\"mailto:\\1\" target=\"_new\">\\1</a>", $ret);
    return($ret);
}

function setResponse($last, $prio, $tid)
{
	global $mysql_tpriorities_table, $db, $mysql_time_table;

	$sql = "SELECT closed_date from $mysql_time_table where ticket_id=$tid order by id desc;";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);
	$closed_time = $row[closed_date];

	if($closed_time == '' || $closed_time == '0')
		$curr_time = time();
	else
		$curr_time = $closed_time;

	$sql = "SELECT response_time from $mysql_tpriorities_table where priority='$prio'";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);
	$time_allowed = $row['response_time'];

	$time_since_update = $curr_time - $last;		//time since last update in seconds

	if($time_since_update >= $time_allowed)
		$response = 4;
	if($time_since_update < ($time_allowed))
		$response = 3;
	if($time_since_update <= ($time_allowed * 0.667))
		$response = 2;
	if( $time_since_update <= ($time_allowed * 0.333))
		$response = 1;


	return $response;
}

/***********************************************************************************************************
**      function createViewableByMenu():
**              Takes no arguments.  Creates the drop down menu for the list of knowledge base categories.
************************************************************************************************************/
function createViewableByMenu($flag=0)
{
        global $info, $lang_allusers, $lang_onlysupporters;

        if($flag == 1)
                echo "<option></option>";


        //option 1: viewable by all users
                echo "<option value='all'";
                        if($info['viewable_by'] == 'all'){
                                echo "selected";
                        }
                echo "> $lang_allusers </option>";

        //option 2: viewable by only supporters
                echo "<option value='supporters'";
                        if($info['viewable_by'] == 'supporters'){
                                echo "selected";
                        }
                echo "> $lang_onlysupporters </option>";
        
}

function convertFileSize($attachsize)
{
	global $lang_unknown;

	if($attachsize >= 1073741824) { $attachsize = round($attachsize / 1073741824 * 100) / 100 . "gb"; }
	elseif($attachsize >= 1048576) { $attachsize = round($attachsize / 1048576 * 100) / 100 . "mb"; }
	elseif($attachsize >= 1024)	{ $attachsize = round($attachsize / 1024 * 100) / 100 . "kb"; }
	else { $attachsize = $attachsize . "b"; }

	if($attachsize == 'b'){
		$attachsize = "$lang_unknown";
	}
	return $attachsize;
}

function getLanguage($name)
{
	global $mysql_users_table, $db;

	$sql = "SELECT language from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$language = $db->fetch_array($result);
	return $language[language];
}

function createSGroupCheckboxes()
{
	global $mysql_sgroups_table, $db, $id;	//$id is the user id

	$sql = "SELECT id, group_name from $mysql_sgroups_table where id != 1 order by group_name asc";
	$result = $db->query($sql);

	echo "<tr>";

	$i=1;
	while($row = $db->fetch_array($result)){
		if($row[id] != '' && isset($id)){
			$sql = "select user_id from sgroup" . $row[id] . " where user_id=$id";
			$result2 = $db->query($sql);
			$num_rows = $db->num_rows($result2);
		}
		if($i%4 == 0){
			echo "<td class=subcat width=25%><b>
					<input class=box type=checkbox";
				if($num_rows > 0){
					echo " checked";
				}
			echo " name=sbox".$i." value=".$row['id'].">&nbsp;&nbsp;&nbsp;".$row['group_name']."</b><br></td></tr><tr>";
		}
		else{
			echo "<td class=subcat width=25%><b>
					<input class=box type=checkbox";
				if($num_rows > 0){
					echo " checked";
				}					
			echo " name=sbox".$i." value=".$row['id'].">&nbsp;&nbsp;&nbsp;".$row['group_name']."</b><br></td>";
		}
		$i++;
	}
	
	$num_boxes = $i - 1;
	echo "<input type=hidden name=num_sboxes value=$num_boxes>";

	while($i%4 != 1){
		echo "<td class=subcat>&nbsp;</td>";
		$i++;
	}

	echo "</tr>";

}

function createUGroupCheckboxes()
{
	global $mysql_ugroups_table, $db, $id;

	$sql = "SELECT id, group_name from $mysql_ugroups_table order by group_name asc";
	$result = $db->query($sql);

	echo "<tr>";

	$i=1;
	while($row = $db->fetch_array($result)){
		if($row[id] != '' && isset($id)){
			$sql = "select user_id from ugroup" . $row[id] . " where user_id=$id";
			$result2 = $db->query($sql);
			$num_rows = $db->num_rows($result2);
		}
		if($i%4 == 0){
			echo "<td class=subcat width=25%><b>
					<input class=box type=checkbox";
				if($num_rows > 0){
					echo " checked";
				}
			echo " name=ubox".$i." value=".$row['id'].">&nbsp;&nbsp;&nbsp;".$row['group_name']."</b><br></td></tr><tr>";
		}
		else{
			echo "<td class=subcat width=25%><b>
					<input class=box type=checkbox";
				if($num_rows > 0){
					echo " checked";
				}
			echo " name=ubox".$i." value=".$row['id'].">&nbsp;&nbsp;&nbsp;".$row['group_name']."</b><br></td>";
		}
		$i++;
	}
	
	$num_boxes = $i - 1;
	echo "<input type=hidden name=num_uboxes value=$num_boxes>";

	while($i%4 != 1){
		echo "<td class=subcat>&nbsp;</td>";
		$i++;
	}

	echo "</tr>";

}

function getTimeOffset($name)
{
	global $db, $mysql_users_table;

	$sql = "SELECT time_offset from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	if($row[0] == '')
		return 0;
	else
		return $row[0];
}

function getLastActiveTime($name)
{
	global $db, $mysql_users_table;

	$sql = "SELECT lastactive from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	if($row[lastactive] == '0')
		return "Never";
	else
		return $row[lastactive];

}

function stripScripts($text)
{
	$text = eregi_replace("<script>", "(not allowed)", $text);
	$text = eregi_replace("</script>", "(/not allowed)", $text);
	return $text;

}


function getDefaultSupporterGroupID($groupid=0)
{
	global $mysql_sgroups_table, $mysql_ugroups_table, $db;

	//$sql = "SELECT id from $mysql_sgroups_table where default_group='Yes'";
	//$result = $db->query($sql);
	//$row = $db->fetch_array($result);
	$sql = "SELECT $mysql_sgroups_table.id from $mysql_sgroups_table, $mysql_ugroups_table";
	$sql .= " where $mysql_ugroups_table.id=$groupid AND $mysql_ugroups_table.defaultsupportid = $mysql_sgroups_table.id";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	return $row[id];
	//return 3;
}

function sendGroupPage($template_name, $sg, $user_name, $short, $priority, $id, $subject='', $assignedSupporterOnly='', $SendPreferences='')
{
	global $user_update, $site_url, $logfile, $mysql_users_table, $lang_equipment, $admin_email, $helpdesk_name, $enable_smtp, $db, $mysql_templates_table, $mysql_tickets_table, $lang_status, $lang_ticket, $lang_from, $lang_created, $lang_ticketcreatedby, $supporter_site_url, $lang_shortdesc, $lang_priority;
  
	$sql = "SELECT * from $mysql_tickets_table where id=$id";
	$result = $db->query($sql);
	$ticket = $db->fetch_array($result);		//setup the ticket array so all variables are available.
    if (!empty($assignedSupporterOnly))
    {
    	 $sql = "select pager_email from $mysql_users_table where user_name='$assignedSupporterOnly'";
    	 $result = $db->query($sql);
    	 while($row = $db->fetch_array($result)){
    	 	  if($row[pager_email] != ''){
    			  $to_list = $row[pager_email];
    	    }
    	 }
    }
    else
    {
    		//we have the groupid (sg), the username, short description.
    		//get the list of supporters to page.
    		if($sg == 1)
    			$sql = "SELECT pager_email from $mysql_users_table";
    		else
    			$sql = "select pager_email from $mysql_users_table, sgroup" . $sg . " where $mysql_users_table.user_name=sgroup" . $sg . ".user_name";
    
    		$result = $db->query($sql);
    		$list = 0;
    		while($row = $db->fetch_array($result)){
    			//create the header list for the to address in the email.
    			if($row[pager_email] != ''){
    				if($list != 1){
    					$to_list = $row[pager_email];
    					$list = 1;
    				}
    				else{
    					$to_list .= ", " . $row[pager_email];
    					$list = 1;
    				}
    			}
    		}
    }
		$sql = "SELECT template from $mysql_templates_table where name='$template_name'";
		$result = $db->query($sql);
		$template = $db->fetch_array($result);
		$template=str_replace("\\'","'",$template[0]);
		$email_msg='empty';
		if (empty($subject)) $subject = "TICKET $id $ticket[status]";
		eval("\$email_msg = \"$template\";");
    
		if($enable_smtp == 'lin'){
			//+++ SendMail
			QueueMail($to_list, $helpdesk_name, $admin_email, $id, $email_msg, $subject);
		}
		if($enable_smtp == 'win'){
			mail($to_list, "TICKET $id $ticket[status]", $email_msg, "From: ".$helpdesk_name."\n");
		}
		//no other options...if enable_smtp is set to anything else, the email will not get sent.
		
		if (is_writable($logfile)) {
		    if (!$handle = fopen($logfile, 'a')) {
                        //cannot append
                         exit;
                    }
                
                    // Write to our opened file.
                    if (fwrite($handle, date("l m/d/y H:i:s ") ."$lang_ticket $id / $lang_ticketcreatedby:$user_name / $lang_priority::$priority / $lang_equipment:$ticket[equipment]\nEMAIL/PAGE sent to: $to_list\n") === FALSE) {
                        //echo "Cannot write to file ($filename)";
                        exit;
                    }
                         
                    fclose($handle);
		}


}

function ProcessExtendedNotifications($cc_template_name, $mms_template_name, $statuschange_template_name,
         $mms_yes, $email_yes, $status_change_yes, $email_msg, $id ) {

	global $logfile, $mysql_users_table, $lang_equipment, $admin_email, $helpdesk_name, $enable_smtp, $db, $mysql_templates_table, $mysql_tickets_table, $lang_status, $lang_ticket,
	 $lang_from, $lang_created, $lang_ticketcreatedby, $supporter_site_url, $lang_shortdesc,
	 $lang_priority, $lang_username, $lang_update, $lang_cc_subject;
	
	
	$sql = "SELECT * from $mysql_tickets_table where id=$id";
	$result = $db->query($sql);
	$ticket = $db->fetch_array($result);		//setup the ticket array so all variables are available.
	$supporter = getUserInfo($ticket[supporter_id]);
	
	  	//takes care of status change and email_msg
          	if ($email_yes && $email_msg !='') {
          		// build tolist +++
          		$to_list = "horrack@hotmail.com";
          
          		$sql = "SELECT template from $mysql_templates_table where name='$cc_template_name'";
          		$result = $db->query($sql);
          		$template = $db->fetch_array($result);
          		$template=str_replace("\\'","'",$template[0]);
          		
          		eval("\$email_msg = \"$template\";");
          
          		if($enable_smtp == 'lin'){
          			sendmail($to_list, $helpdesk_name, $admin_email, $id, $email_msg, "$lang_cc_subject #$id $ticket[status]");
          		}
          		if($enable_smtp == 'win'){
          			mail($to_list, "$lang_cc_subject #$id $ticket[status]", $email_msg, "From: ".$helpdesk_name."\n");
          		}
          		//no other options...if enable_smtp is set to anything else, the email will not get sent.
          	} else 
          		if ($status_change_yes) {
	   			//use statuschange template
                  		// build tolist +++
                  		// send a partial history
                  		$to_list = "horrack@hotmail.com";
                  
                  		$sql = "SELECT template from $mysql_templates_table where name='$cc_template_name'";
                  		$result = $db->query($sql);
                  		$template = $db->fetch_array($result);
                  		$template=str_replace("\\'","'",$template[0]);
                  		
                  		eval("\$email_msg = \"$template\";");
                  
                  		if($enable_smtp == 'lin'){
                  			sendmail($to_list, $helpdesk_name, $admin_email, $id, $email_msg, "$lang_cc_subject #$id $ticket[status]");
                  		}
                  		if($enable_smtp == 'win'){
                  			mail($to_list, "$lang_cc_subject #$id $ticket[status]", $email_msg, "From: ".$helpdesk_name."\n");
                  		}	   			
        	   			
			}
		
	   	if ($mms_yes) {
	   		// send to supporter of ticket
	   		//include details from log
	   		//use mms_template
		}		
}


/***********************************************************************************************************
**	function displayUserTicket():
**		Takes one argument.  Takes the result of a sql query that searches the tickets table and displays
**	all pertinent information about the ticket in a nice table format.
************************************************************************************************************/
function displayUserTicket($result)
{
    global $cookie_name, $highest_pri, $theme, $db, $admin_site_url;

    $second = getSecondPriority();

    $recordcount = 0;
    $csv_string = "";
    while ($row = $db->fetch_array($result)) {

        $last_update = $row['lastupdate'];  //last update timestamp.

        echo "<tr>
				<td class=back>" . str_pad($row['id'], 5, "0", STR_PAD_LEFT) . "</td>";
        if (isAdministrator($cookie_name)) {
            echo "<td class=back2><a href=\"" . $admin_site_url . "/control.php?t=users&act=uedit&id=" . getUserID($row['supporter']) . "\">" . $row['supporter'] . "</td>";
        } else {
            echo "<td class=back2>" . $row['supporter'] . "</td>";
        }
        echo "<td class=\"back\">";

        echo $row['equipment'] . "</td>";


        echo "<td class=\"back2\">";
        echo "<a href=\"?t=tinf&id=" . $row['id'] . "\">";
        echo stripslashes($row['short']) . "</a></td>
			
				<td class=back>" . $row['user'] . "</td>
				<td class=back2>";

        switch ($row['priority']) {
            case ("$highest_pri"):
                echo "<font color=red><b>" . $row[priority] . "</b></font>";
                break;
            case ($second):
                echo "<b>" . $row[priority] . "</b>";
                break;
            default:
                echo $row[priority];
                break;
        }

        echo "</td>
				<td class=back> " . date("m/d/y", $row[create_date]) . "</td>
				<td class=back2>";
        //cookie_name='.$cookie_name.'
        echo '<a href="supporter/updatelog.php?&id=' . $row['id'] . '" target="myWindow" onClick="window.open(\'\', \'myWindow\',
					\'location=no, status=yes, scrollbars=yes, height=500, width=600, menubar=no, toolbar=no, resizable=yes\')">';

        echo $row['status'] . "</a></td>";


        // Calculates total time spent on the ticket in minutes
        $sql3 = "SELECT sum(minutes) FROM tickets,time_track WHERE (tickets.id=time_track.ticket_id AND tickets.id=" . $row[id] . ")";
        echo '<td class=back2 align=right>';
        $result3 = $db->query($sql3);
        $row3 = $db->fetch_array($result3);

        if ($row3[0]) {
            $minutes = $row3[0];
        } else {
            $minutes = "0";
        }

        showFormattedTime($minutes * 60, 1);
        echo '</td>';


        echo "</tr>";
        $recordcount++;
        $csv_string = $csv_string . $row['id'] . ",";

    }

    $summary = array("recordcount" => $recordcount, "remarks" => "list (CSV):", "tktlist" => $csv_string);
    return $summary;
}

    /**	Takes the user id and returns an array containing the list of group tablenames(ugroupN) that the user is in.	**/
function getUsersGroupList($id)
{
	global $mysql_ugroups_table, $num_groups, $db;



	//if($num_groups == 1)
		$sql = "select id from $mysql_ugroups_table";
	//else
	//	$sql = "select id from $mysql_ugroups_table where id != 1";

	$result = $db->query($sql);
	//now we have the list of all the user groups.
	$i=0;
	while($row = $db->fetch_row($result)){
		if($num_groups != 1){
			$sql2 = "select id from ugroup" . $row[0] . " where user_id=$id";
			$result2 = $db->query($sql2);
			if($db->num_rows($result2) != 0){
				$grouplist[$i] = "ugroup" . $row[0];
				$i++;
			}
		 }
	}
	//$grouplist[0]="ugroup1";
	//returns a list of strings (group table names).
	return $grouplist;

}

/**	Takes the user id and returns an array containing the list of group tablenames(ugroupN) that the user is in.	**/
function getUsersGroupIDList($id)
{
	global $mysql_ugroups_table, $num_groups, $db;



	//if($num_groups == 1)
		$sql = "select id from $mysql_ugroups_table";
	//else
	//	$sql = "select id from $mysql_ugroups_table where id != 1";

	$result = $db->query($sql);
	//now we have the list of all the user groups.
	$i=0;
	while($row = $db->fetch_row($result)){
		if($num_groups != 1){
			$sql2 = "select id from ugroup" . $row[0] . " where user_id=$id";
			$result2 = $db->query($sql2);
			if($db->num_rows($result2) != 0){
				$grouplist[$i] = $row[0];
				$i++;
			}
		 }
	}

	//returns a list of group ID the user is member of).
	return $grouplist;

}


function testPDF() {

        $pdf = PDF_new(); 
        PDF_open_file($pdf); 
         PDF_set_info($pdf, "author", "John Coggeshall");  
    PDF_set_info($pdf, "title", "Zend.com Example");  
    PDF_set_info($pdf, "creator", "Zend.com");  
    PDF_set_info($pdf, "subject", "Code Gallery  Spotlight"); 
    PDF_begin_page($pdf, 450, 450); 
    $font = PDF_findfont($pdf, "Helvetica-Bold",  "winansi",0);     
    PDF_setfont($pdf, $font, 12); 
    PDF_show_xy($pdf, "WORK ORDER No. 10456", 5, 425); 
    PDF_end_page($pdf); 
    PDF_close($pdf);
$buffer = PDF_get_buffer($pdf); 
header("Content-type: application/pdf"); 
header("Content-Length: ".strlen($buffer)); 
header("Content-Disposition: inline; filename=zend.pdf"); 

echo $buffer; 
PDF_delete($pdf); 
}

function GetServerTimeOffset($timezone) {
  global $server_gmt_offset;
  	return ($timezone - $server_gmt_offset);
}

function CreateTimeHistoryArray($id)
{
	global $mysql_users_table, $mysql_settings_table, $db, $lang_timespent, $lang_timespent1, $lang_timespent2;
  global $lang_timehistory, $lang_month, $timestamp;
  
	$arry = array();
	$i = 0;
	
	$sql = "select trk.supporter_id, trk.work_date, trk.reference,  trk.minutes from tickets as tkt, time_track as trk where (tkt.id=trk.ticket_id AND tkt.id=$id)";
	$resultsupporters = $db->query($sql);

  
  while($row = $db->fetch_array($resultsupporters)){
    if ($row[minutes] != 0) {	
    	   	if ($row['work_date'])
    		    $arry[$i]["date"] = date("F j, Y", $row[work_date]);
    		  else
    		    $arry[$i]["date"]= "- No Date -";
    		  $sql = "select * from $mysql_users_table where id=$row[supporter_id]";
    		  $result = $db->query($sql);
    		  $sup_row = $db->fetch_array($result);
    			$arry[$i]["user"]= $sup_row[user_name]; 
    		  $arry[$i]["time"]= $row[minutes]; 
    			$arry[$i]["reference"]= "$row[reference]"; 
    			$arry[$i]["after_hours"]= $row[after_hours]; 
    			$arry[$i]["engineer_rate"]= $row[engineer_rate]; 
    			
 	  }
	  $i++;
	}
	return $arry;
}


function getCredentialsArray($name)
{
	global $mysql_users_table, $db;

	$sql = "SELECT email, password, first_name, last_name, user_name from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);
	return $row;
}

/* exported from /admin/timedetailed.php */
// joins the ticket and time_track and returns the supporter records (filtered)
function getSupporterList($id, $filter)
{
	global $mysql_users_table, $mysql_time_table, $db;
  switch ($filter) {

		case ("straight"):	
			$sql = "SELECT supporter_id, $mysql_users_table.user_name, sum(minutes) as sum, tt.after_hours, tt.engineer_rate from $mysql_users_table, $mysql_time_table as tt where tt.engineer_rate = 0 and tt.after_hours = 0 AND $mysql_users_table.id=supporter_id and ticket_id=$id group by supporter_id";
	  	break;
		case ("after_hours"):	
			$sql = "SELECT supporter_id, $mysql_users_table.user_name, sum(minutes) as sum, tt.after_hours, tt.engineer_rate from $mysql_users_table, $mysql_time_table as tt where tt.engineer_rate = 0 and tt.after_hours != 0 AND $mysql_users_table.id=supporter_id and ticket_id=$id group by supporter_id";
	  	break;
		case ("engineer_rate"):	
			$sql = "SELECT supporter_id, $mysql_users_table.user_name, sum(minutes) as sum, tt.after_hours, tt.engineer_rate from $mysql_users_table, $mysql_time_table as tt where tt.engineer_rate != 0 AND $mysql_users_table.id=supporter_id and ticket_id=$id group by supporter_id";
	  	break;	  	
		case ("all"):	
		case ("default"):	
			$sql = "SELECT supporter_id, $mysql_users_table.user_name, sum(minutes) as sum, tt.after_hours, tt.engineer_rate from $mysql_users_table, $mysql_time_table as tt where $mysql_users_table.id=supporter_id and ticket_id=$id group by supporter_id";
	  	break;
  }
	$result = $db->query($sql);
	
	$i = 0;
	while($row = $db->fetch_array($result)){
		//each row contains a different user name and sum.
		$array[$i]['user_name'] = $row['user_name'];
		$array[$i]['after_hours'] = $row['after_hours'];
		$array[$i]['engineer_rate'] = $row['engineer_rate'];
		$array[$i]['sum'] = $row['sum'];
		$i++;
	}
	return $array;
}


function getTicketInfo($id)
{
	global $mysql_tickets_table, $db;

	$sql = "select * from $mysql_tickets_table where id=$id";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	return $row;
}

function getTicketTimeInfo($id)
{
	global $db;

//ticket create date is contained in tickets table
//first response time is opened_date field in time_track table
//closed date is closed_date field in time_track table

	global $mysql_users_table, $mysql_time_table;
  
   		$sql = "select $mysql_users_table.user_name, sum(minutes) as sum, tt.after_hours, supporter_id, opened_date, closed_date from $mysql_users_table, $mysql_time_table as tt where ticket_id=$id and supporter_id=$mysql_users_table.id group by supporter_id, opened_date, closed_date order by sum asc";
        $resarray = NULL;
	$result = $db->query($sql);
	while($row = $db->fetch_array($result)){
		//create the array based on the db data.
		if(($row['closed_date'] > $resarray['closed_date']) && $row['closed_date'] != 0){
			$resarray['closed_date'] = $row['closed_date'];
		}
		if(/*($row['sum'] > $array['sum']) && */$row['sum'] != 0){
			$resarray['sum'] += $row['sum'];
		}
		if(($row['opened_date'] > $resarray['opened_date']) && $row['opened_date'] != 0){
			$resarray['first_response'] = $row['opened_date'];
		}
		
	}

	//return an array of that relevant data per ticket.
	return $resarray;

}



// returns an array with 3 different supportername/time arrays
function getTicketTotalTime($id)
{
		$supporters = getSupporterList($id,'straight');
		$supporters_after_hours = getSupporterList($id,'after_hours');
		$supporters_engineer_rate = getSupporterList($id,'engineer_rate');
    $total_time =0;
    
    if(sizeof($supporters) > 0){
    	foreach($supporters as $items){
				 $total_time += $items['sum'];
 			}		
		}			
    if(sizeof($supporters_after_hours) > 0){ 
    	foreach($supporters_after_hours as $items){
				 $total_time += $items['sum'] * 1.5;
			}
		}
    if(sizeof($supporters_engineer_rate) > 0){ 
    	foreach($supporters_engineer_rate as $items){
				 $total_time += $items['sum'] * (($items['after_hours'] == 1) ? 1.5 : 1);
			}
		}
		$result = array(	'supporters' 	=> 	$supporters, 'supporters_after_hours'	=>	$supporters_after_hours, 
											'supporters_engineer_rate' => $supporters_engineer_rate, 'total_time' => $total_time); 
		return $result;
}

function DrawTableSupporterTotals($array, $id, $title)
{
	
  			$supporters = $array['supporters'];
				$supporters_after_hours = $array['supporters_after_hours'];
				$supporters_engineer_rate= $array['supporters_engineer_rate'];
				$total_time = $array['total_time'];
				$supporter_total = 0;
			    $supporter_after_hours_total = 0;
    			$supporter_engineer_total = 0;
				$ticket_data = getTicketTimeInfo($id);
					
				startTable($title, "left", 100, 2);
				if(sizeof($supporters) > 0){
					foreach($supporters as $items){
						echo "<tr><td class=subcat width=27%>" . $items['user_name'] . ": </td><td class=back>"; showFormattedTime($items['sum'] * 60);
						//exclude engineer time from total since this will be listed separately
						//echo "eng:$items[engineer_rate]";
						if ($items['engineer_rate'] == '0') {
							  $supporter_total[$items['user_name']] += $items['sum'];
						
    						if($ticket_data['sum'] != 0){
    							$percentage = number_format(($items['sum'] / $total_time) * 100, 2);
    							echo "  (".$percentage."%)";
    						}
    						else{
    							$percentage = 0;
    						}
						}
						echo "</td></tr>";
					}
				} //end of previous table code
				if(sizeof($supporters_after_hours) > 0){
					foreach($supporters_after_hours as $items){
						echo "<tr><td class=subcat width=27%>" . $items['user_name']." (after hours):"." </td><td class=back>"; 
					  showFormattedTime($items['sum'] * 60 ); echo "  (after hours  x 1.5)->       ";	showFormattedTime($items['sum'] * 60 * 1.5);
						//exclude engineer time from toatl since this will be listed separately
						if ($items['engineer_rate'] == '0') {
									$supporter_after_hours_total[$items['user_name']." (after hours)"] += $items['sum'] * 1.5;
						}
						if($ticket_data['sum'] != 0){
							$percentage = number_format(($items['sum'] *1.5 / $total_time) * 100, 2);
							echo "  (".$percentage."%)";
						}
						else{
							$percentage = 0;
						}
						
						echo "</td></tr>";
					}
				} //end of previous table code
				
				if(sizeof($supporters_engineer_rate) > 0){
					foreach($supporters_engineer_rate as $items){
						if ($items['after_hours'] == 1) {
							$mult = 1.5;
							$suffix = " (engineer/after_hrs):";
						} else {
							$mult = 1;
							$suffix = " (engineer):";							
						}						
						echo "<tr><td class=subcat width=27%>" . $items['user_name'].$suffix." </td><td class=back>"; 
					  $time_engineer = $items['sum'] ;
						showFormattedTime( $time_engineer * 60 );
						if ($items['after_hours'] == 1) {
							echo "  (after hours  x 1.5)->       ";	showFormattedTime($items['sum'] * 60 * 1.5);
						}
						$time_engineer *= $mult; 
						$supporter_engineer_total[$items['user_name'].$suffix] += $time_engineer ;
					  if($time_engineer != 0){
							$percentage = number_format($time_engineer/$total_time * 100, 2);
							echo "  (".$percentage."%)";
						}	else{
							$percentage = 0;
					  }	
					  echo "</td></tr>";
					}
				} //end of previous table code
				
       endTable();
}

function displayTimeHistory()
{
	global $sg, $info, $id, $mysql_users_table, $mysql_settings_table, $db, $lang_timespent, $lang_timespent1, $lang_timespent2;
  global $lang_timehistory, $lang_month, $timestamp;
  
	
	startTable("$lang_timehistory", "left", 100, 6);

	$sql = "select trk.supporter_id, trk.work_date, trk.reference,  trk.minutes, trk.after_hours, trk.engineer_rate from tickets as tkt, time_track as trk where (tkt.id=trk.ticket_id AND tkt.id=$id)";
	$resultsupporters = $db->query($sql);


  while($row = $db->fetch_array($resultsupporters)){
    if ($row[minutes] != 0) {	
    	echo '<tr>
    		<td width=27% class=back2 align=right>';
    		if ($row['work_date'])
    		    echo date("F j, Y", $row[work_date]);
    		  else
    		    echo "- No Date -";
    	echo '</td>';
    	echo '<td width=10% class=back>';
    		  $sql = "select * from $mysql_users_table where id=$row[supporter_id]";
    		  $result = $db->query($sql);
    		  $sup_row = $db->fetch_array($result);
    		  echo "$sup_row[user_name]";
    			
    	echo '</td>';	
   	  echo '<td width=10% class=back2>';
    		  //
          echo "<input class=box type=checkbox";
				     if($row[after_hours] != "0"){
					   echo " checked";
			     	}	
			    echo ">after_hrs";
    	echo '</td>';    	

   	  echo '<td width=10% class=back>';
    		  //
          echo "<input class=box type=checkbox";
				     if($row[engineer_rate] != "0"){
					   echo " checked";
			     	}	
			    echo ">engineer_rate";    			
    	echo '</td>';     	
    	    		
    	echo '<td width=15% class=back2>';
    			showFormattedTime($row[minutes] * 60, 1); 
    	echo '</td>';			
    	echo '<td class=back>';
    			echo "$row[reference]"; 
    	echo '</td>';				
	  }
	}
	
	// Calculates total time spent on the ticket in minutes
	$sql = "select sum(minutes) from tickets,time_track where (time_track.after_hours = 0 AND tickets.id=time_track.ticket_id AND tickets.id=$id)";
  $sql_after_hours = "select sum(minutes) from tickets,time_track where (time_track.after_hours != 0 AND tickets.id=time_track.ticket_id AND tickets.id=$id)";

  echo '<tr><td width=24% class=back2 align=right>'; 
  echo '</td> <td class=back >';
  echo '</td> <td class=back colspan=2>';
  echo "After Hours (multiplied):";
  echo '</td> <td class=back  colspan=1>';
  echo 'Straight Time:';
  echo '</td> <td class=back2  colspan=1>';
  echo '<B>Grand Total:</B>';
    
  echo '<tr><td width=24% class=back2 align=right><B>Total Time:</B>';
	echo '</td> <td class=back >';
	echo '</td> <td class=back colspan=2>';
	$result = $db->query($sql);
	$row = $db->fetch_array($result);
	$result_after_hours = $db->query($sql_after_hours);
	$row_after_hours = $db->fetch_array($result_after_hours);

	if ($row[0]) $minutes = $row[0]; else $minutes = "0";
	
	$minutes_after_hours = 1.5 * $row_after_hours[0];
	
	showFormattedTime($minutes_after_hours * 60, 1);
	
	echo'</td> <td class=back> ';
	showFormattedTime($minutes * 60, 1);
	echo '</td>';

	echo'</td> <td class=back2> <B>';
	showFormattedTime($minutes * 60 + $minutes_after_hours  * 60, 1);
	echo '</B></td>';

	endTable();
}

function createTicketHeader($msg)
{
	global $info;

	startTable($msg, "center");	
	endTable();

}



/***********************************************************************************************************
**	function getCloudControlUserSetting():
**		Takes a string as an argument.  Takes the user name and returns the CloudControl (On or Off) of that user in the user
**	table in the database.
************************************************************************************************************/
function getCloudControlUserSetting($name)
{
	global $mysql_users_table, $db;

	$sql = "select CloudControl from $mysql_users_table where user_name='$name'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}
?>