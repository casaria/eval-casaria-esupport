<?php

/**************************************************************************************************
**	file:	usersearch.php
**
**	This file hold the template that is used for searching for a user.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	10/02/01
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
//check to make sure the file is called from either control.php or index.php and not called directly.
if(!eregi("index.php", $PHP_SELF) && !eregi("control.php", $PHP_SELF)){
	echo "$lang_noaccess";
	exit;
}

require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";

if(isset($done)){

	if(isset($changes)){
		//we need to update the database.
		//id(x) = id number of user to be modified
		//pwd(x) = pwd of that user...etc.

		for($i=0; $i<$count; $i++){
			unset($flag);
			$del = "del" . $i;
			$user_id = "id" . $i;
			$pwd = "pwd" . $i;
			  if($$pwd != ''){
				  $new_password = md5($$pwd);
			  }
			  else{
				  $new_password = "not changed";
			  }
			$level = "level" . $i;

			if($$del == 'on'){
				$sql = "delete from $mysql_users_table where id=" . $$user_id;
				$db->query($sql);
				deleteFromGroups($$user_id);		//must delete from groups as well.
			}
			else{
				$sql = "update $mysql_users_table set";

					//if the password has been changed, update the database, otherwise don't or it gets set wrong
					if($new_password != "not changed"){
						$sql .= " password='".$new_password."'";
						$flag = 1;
					}
					
					if($$level == 'admin'){
						if($flag == 1){
							$sql .= ", admin=1, supporter=1, user=1";
							$flag = 1;
						}
						else{
							$sql .= " admin=1, supporter=1, user=1";
						}
					}

					if($$level == 'supporter'){
						if($flag == 1){
							$sql .= ", admin=0, supporter=1, user=1";
							$flag = 1;
						}
						else{
							$sql .= " admin=0, supporter=1, user=1";
						}
					}

					if($$level == 'user'){
						if($flag == 1){
							$sql .= ", admin=0, supporter=0, user=1";
							$flag = 1;
						}
						else{
							$sql .= " admin=0, supporter=0, user=1";
						}
					}

					if($$level == 'inactive'){
						if($flag == 1){
							$sql .= ", admin=0, supporter=0, user=0";
							$flag = 1;
						}
						else{
							$sql .= " admin=0, supporter=0, user=0";
						}
					}

				$sql .= " where id=" . $$user_id;
				$db->query($sql);
			}

		}
		
	}	//else if not $changes
	else{
		echo "<form method=\"post\" action=\"control.php?t=users&act=srch\">";

			if($user == ''){
				if($level == "$lang_alllevels"){
					$sql = "select id, user_name, admin, supporter, user from $mysql_users_table where user_name != 'support_pool' order by user_name asc";
				}
				else{
					if($level == 'inactive'){
						$sql = "select id, user_name, admin, supporter, user from $mysql_users_table where (user_name != 'support_pool' and user=0) order by user_name asc";
					}
					else{
						$sql = "select id, user_name, admin, supporter, user from $mysql_users_table where (user_name != 'support_pool' and " . $level . "=1) order by user_name asc";
					}
				}
			}
			else{
				$sql = "select id, user_name, admin, supporter, user from $mysql_users_table where user_name REGEXP '".$user."'";
			}

		$result = $db->query($sql);
		startTable("$lang_users", "left", "100%", 5);
		echo "<tr><td class=cat width=20>$lang_delete?</td>
				  <td class=cat>$lang_username:</td>
				  <td class=cat>$lang_newpassword?</td>
				  <td class=cat>$lang_level:</td>
				  <td class=cat>$lang_edit:</td>
			  </tr>";
		$i=0;
		while($row = $db->fetch_array($result)){
			echo "<tr>";
			echo "<input type=hidden name=id".$i." value=".$row['id'].">";
			echo "<td class=subcat align=center> <input class=box type=checkbox name=del".$i."> </td>";
			echo "<td class=back2>". $row['user_name'] . "</td>";
			echo "<td class=back2> <input type=password name=pwd".$i."></td>";
			//different browsers do different things when multiple values are selected, so just set one value
			//to be selected instead of doing browser check hacks that tend to fail
			$valueset = false;
			echo "<td class=back2><select name=level".$i.">
				<option value=admin";
					if($row['admin'] == 1 && $valueset == false){
						$valueset = true; echo ' selected';}
					echo ">$lang_admin</option>
				<option value=supporter";
					if($row['supporter'] == 1 && $valueset == false){
						$valueset = true; echo ' selected';}
					echo ">$lang_Supporter</option>
				<option value=user";
					if($row['user'] == 1 && $valueset == false){
						$valueset = true; echo ' selected';} 
					echo ">$lang_user</option>
				<option value=inactive";
					if($row['user'] == 0 && $valueset == false){ 
						$valueset = true; echo ' selected';}
					echo ">$lang_inactive</option>
				</select></td>";
			echo "<td class=back2 align=center><a href=\"control.php?t=users&act=uedit&id=".$row['id']."\">$lang_edit</a></td>";
			echo "</tr>";
			$i++;
		}

		endTable();

		echo "<input type=hidden name=t value=users>";
		echo "<input type=hidden name=count value=".$i.">";
		echo "<input type=hidden name=done value=done>";
		echo "<center><input type=\"submit\" name=\"changes\" value=\"$lang_submitchanges\"></form></center><br>";
	}	//end while
}	//end else
else{	//if $done is not set
                startTable("$lang_searchforuser", "center", "100%", 1);
		echo "<tr><td class=back align=center>";
		echo "<form method=\"post\" action=\"control.php?t=users&act=srch&done=done\">";
		//Search for users:
		echo "$lang_searchforuser: <input type=text name=user> $lang_withlevel:";
			echo " <select name=level>
						<option>$lang_alllevels</option>
						<option value=admin>$lang_admin</option>
						<option value=supporter>$lang_Supporter</option>
						<option value=user>$lang_user</option>
						<option value=inactive>$lang_inactive</option>
					</select>
					</td>";
		echo "</tr>";

		echo '<input type=hidden name=t value=users>';
		echo "<tr><td class=back align=center><input type=\"submit\" value=\"$lang_search\"></form></td></tr>";
		endTable();
}

?>
