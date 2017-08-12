<?php
/*****************************************************************************************
**	file:	uedit.php
**
**	This file will serve as the frontend for editing a user account.
**
******************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/25/01
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


$uinfo = getUserInfo($id);
$last_active = getLastActiveTime($uinfo[user_name]);		//get the lastactive time for the user you're editing

//check to make sure the file is called from either control.php or index.php and not called directly.
if(!eregi("index.php", $PHP_SELF) && !eregi("control.php", $PHP_SELF)){
	echo "$lang_noaccess";
	exit;
}

require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";

if(isset($submit)){

	if($level == "admin"){
		$admin = 1;
		$supporter=1;
		$user = 1;
	}

	if($level == "supporter"){
		$admin = 0;
		$supporter = 1;
		$user = 1;
	}

	if($level == "user"){
		$admin = 0;
		$supporter = 0;
		$user = 1;
	}

	if($level == "inactive"){
		$admin = 0;
		$supporter = 0;
		$user = 0;
	}

	if($password != ''){
		$pwd = md5($_POST[password]);
		$sql = "UPDATE $mysql_users_table set first_name='$_POST[first_name]',last_name='$_POST[last_name]',";
		$sql .= "user_name='$_POST[user_name]',phone='$_POST[phone]',";
		$sql .= "office='$_POST[office]', email='$_POST[email]',admin='$admin', ";
		$sql .= "user='$user', supporter='$supporter', password='$pwd', CloudControl='$CloudControl'";
		 if($enable_pager == 'On')
			 $sql .= ", pager_email='$pager'";
		 $sql .= " where id=$id";
	}
	else{
		$sql = "UPDATE $mysql_users_table set first_name='$_POST[first_name]',last_name='$_POST[last_name]'";
		$sql .= ",user_name='$_POST[user_name]',phone='$_POST[phone]',";
		$sql .= "office='$_POST[office]', email='$_POST[email]',admin='$admin', ";
		$sql .= "user='$user', supporter='$supporter', CloudControl='$CloudControl'";
		 if($enable_pager == 'On')
			 $sql .= ", pager_email='$_POST[pager]'";
		 $sql .= " where id=$id";
	}

	//get the old user name before we execute the query.
	$query = "select user_name from $mysql_users_table where id=$id";
	$result = $db->query($query);
	$row = $db->fetch_array($result);
	$old_username = $row['user_name'];

	if($old_username != $user_name)
	{
		//usernames do not match, so we need to update all of the user/supporter groups.
		$sgroup_list = getGroupList("");
		$ugroup_list = getUGroupList();

		for($i=0; $i<sizeof($sgroup_list); $i++){
			$query = "UPDATE " . $sgroup_list[$i] . " set user_name='$user_name' where user_name='$old_username'";
			$db->query($query);
		}

		for($i=0; $i<sizeof($ugroup_list); $i++){
			$query = "UPDATE " . $ugroup_list[$i] . " set user_name='$user_name' where user_name='$old_username'";
			$db->query($query);
		}
	}

	$db->query($sql);

	//now lets take care of adding the user to the supporter groups.
	//for all the groups listed, if the box was checked, add the user to that group
	$sql = "SELECT id from $mysql_sgroups_table where group_name!='All Supporters' order by group_name asc";
	$result2 = $db->query($sql);
	$i=0;
	while($row = $db->fetch_array($result2)){
		$sgroup[$i] = $row[0];
		$i++;
	}
	$i=0;
	for($j=1; $j<=$num_sboxes; $j++){
		$inp = "sbox" . $j;
		if($$inp != ''){
			//echo "sgroup is: $sgroup[$i] <br>";
			$user_id = getUserID($user_name);
			$sql = "INSERT IGNORE into sgroup" . $$inp . " VALUES(NULL, '$user_id', '$user_name')";
			$db->query($sql);
		}
		else{
			$user_id = getUserID($user_name);
			$sql = "DELETE from sgroup" . $sgroup[$i] ." where user_id=$user_id";
			$db->query($sql);
		}
		$i++;
		
	}


	//now lets take care of adding the user to the user groups.
	//for all the groups listed, if the box was checked, add the user to that group
	$sql = "SELECT id from $mysql_ugroups_table order by group_name asc";
	$result2 = $db->query($sql);
	$i=0;
	while($row = $db->fetch_array($result2)){
		$ugroup[$i] = $row[0];
		$i++;
	}
	$i=0;
	for($j=1; $j<=$num_uboxes; $j++){
		$inp = "ubox" . $j;
		if($$inp != ''){
			$user_id = getUserID($user_name);
			$sql = "INSERT IGNORE into ugroup" . $$inp . " VALUES(NULL, '$user_id', '$user_name')";
			$db->query($sql);
		}
		else{
			$user_id = getUserID($user_name);
			$sql = "DELETE from ugroup" . $ugroup[$i] ." where user_id=$user_id";
			$db->query($sql);
		}
		$i++;
	}

	//before quitting, lets send that user an email if his account has been activated.
	if($uinfo[admin] == 0 && $uinfo[supporter]== 0 && $uinfo[user] == 0
	   && ($user == 1 || $supporter == 1 || $admin ==1) && $_POST[email] != ''){//user was inactive before, but now has user status
		$sql = "SELECT template from $mysql_templates_table where name='email_activated_account'";
		$result = $db->query($sql);
		$template = $db->fetch_array($result);
		$template=str_replace("\\'","'",$template[0]);
		eval("\$email_msg = \"$template\";");

		if($enable_smtp == 'lin'){
			sendmail($_POST[email], $helpdesk_name, $admin_email, $id, $email_msg, $lang_registerforaccount);
		}
		if($enable_smtp == 'win'){
			mail($_POST[email], "$lang_registerforaccount", $email_msg, "From: ".$helpdesk_name."\n");
		}
		//no other options...if enable_smtp is set to anything else, the email will not get sent.
	}


}

//$info is an array that contains the user information for that id number.
$info = getUserInfo($id);
//$sgroups = getGroupIDList($info[user_name]);

echo "<form action=\"control.php?t=users&act=uedit&id=$id\" method=post>";

startTable("$lang_edit $lang_user", "center", "100%", 2);


			echo "
				<tr><td class=cat align=right width=20%><b> $lang_firstname: </b></td><td class=back>
					<input type=text value='".$info['first_name']."' name=first_name></td></tr>
				<tr><td class=cat align=right width=20%><b> $lang_lastname: </b></td><td class=back>
					<input type=text value='".$info['last_name']."' name=last_name></td></tr>
				<tr><td class=cat align=right width=20%><b> $lang_username: </b></td><td class=back>
					<input type=text value='".$info['user_name']."' name=user_name></td></tr>
				<tr><td class=cat align=right width=20%><b> $lang_emailaddy: </b></td><td class=back>
					<input type=text value='".$info['email']."' name=email></td></tr>";
				
				if($enable_pager == 'On'){
					echo "<tr><td class=cat align=right width=20%><b> $lang_pager $lang_email: </b></td><td class=back>
						<input type=text value='".$info['pager_email']."' name=pager></td></tr>";
				}
				echo "
				<tr><td class=cat align=right width=20%><b> $lang_office:</b></td><td class=back>
					<input type=text value='".$info['office']."' name=office></td></tr>
				<tr><td class=cat align=right width=20%><b> $lang_phoneext:</b></td><td class=back>
					<input type=text value='".$info['phone']."' name=phone></td></tr>
				<tr><td class=cat align=right width=20%><b>";
					if($info[user] == 0){
						echo "<font color=\"red\">";
					}
				echo "$lang_level";
					if($info[user] == 0){
						echo "</font>";
					}
				echo ": </b></td>";
				
				
				echo		"<td class=back><select name=level>";
				//different browsers do different things when multiple values are selected, so just set one value 
				//to be selected instead of doing browser check hacks that tend to fail
				$valueset = false;
				echo "	
					<option value=admin";
						if($info['admin'] == 1 && $valueset == false){ 
							$valueset = true; echo ' selected';} 
						echo ">$lang_admin</option>
					<option value=supporter";
						if($info['supporter'] == 1 && $valueset == false){ 
							$valueset = true; echo 'ed';} 
						echo ">$lang_Supporter</op selecttion>
					<option value=user";
						if($info['user'] == 1 && $valueset == false){
 							$valueset = true; echo ' selected';}
 						echo ">$lang_user</option>
					<option value=inactive";
						if($info['user'] == 0 && $valueset == false){ 
							$valueset = true; echo ' selected';}
						echo ">$lang_inactive</option>";
				echo "</select>";
				
				echo"<tr><td class=cat align=right width=20%><b>Cloud Control:<td class=back><select name=CloudControl> <option value='Off'";
				if ($info['CloudControl'] == $lang_off) echo "selected";
				echo "> Off </option>";
				echo"<option value='On'";
				if ($info['CloudControl'] == $lang_on) echo "selected";
				echo "> On </option> </b></tr></td>";
				
				echo "<tr><td class=cat align=right width=20%><b> $lang_password: </b></td><td class=back><input type=password name=password>
							&nbsp;&nbsp;&nbsp;<font size=1> ($lang_leaveblank) </font></td></tr>

						<tr><td width=27% class=back2 align=right><b>$lang_lastactive: </b></td><td class=back>";
						if($last_active == 0)
							echo "$lang_never";
						else
							echo gmdate("n-d-Y \a\\t h:i a", $last_active + ($time_offset * 3600));
						echo "</td></tr>

					   <input type=hidden name=id value=".$info['id'].">
				</td></tr>"; 
				
		endTable();

		startTable("$lang_addtosgroups:", "left", 100, 4);

			createSGroupCheckboxes();
			
		endTable();

		startTable("$lang_addtougroups:", "left", 100, 4);

			createUGroupCheckboxes();
			
		endTable();

		if($error != 1){
			echo '<input type=submit name=submit value="'.$lang_update.'">';
		}



?>