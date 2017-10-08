<?php
/*****************************************************************************************
**	file: addsupporter.php
**
**	This file takes supporter information and dumps it into the user database.
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

require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";

if(!eregi("index.php", $PHP_SELF) && !eregi("control.php", $PHP_SELF)){
        echo "$lang_noaccess";
        exit;  
}

if(isset($adduser)){
	
	if($_POST[first_name] == '' || $_POST[last_name] == '' || $_POST[user_name] == '' || !validEmail($_POST[email]) || $_POST[pass1] == ''){
		$error = 1;
		$error_message = "<br>$lang_missing_info<br>";
	}

	//make sure the two passwords match, otherwise print out the error message.
	if(!checkPwd($pass1, $pass2)){
		$error = 1;
		$error_message .= "<br>$lang_passwordsnotmatch<br>";
	}
	else{
		$pwd = md5($pass1);
	}



	if($error != 1){
		if(userExists($_POST[user_name])){
			//if the user already exists, update the database rather than insert.
			$error = 1;
			$error_message = "<br>$lang_nameexists<br>";
			$timeoffset = GetServerTimeOffset($_POST[timezone]);
			$sql_head = "update ";
			$sql_tail = "where user_name = '$_POST[user_name]'";
		}
		else{
		  $sql_head = "insert into ";
		}
		
			switch($level){
                case ("superuser"):
                    $sql = $sql_head . "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',1,1,1,1,1,1,'$default_theme',null,null,null,0,'$default_language', '$timeoffset','$CloudControl')";
                    break;
                case ("accountant"):
                    $sql = $sql_head . "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',0,0,1,1,1,0,'$default_theme',null,null,null,0,'$default_language', '$timeoffset','$CloudControl')";
                    break;

				case ("admin"):
					$sql = $sql_head . "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',1,1,1,1,0,0'$default_theme',null,null,null,0,'$default_language','$timeoffset','$CloudControl')";
					break;
				case ("supporter"):
					$sql = $sql_head . "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',0,0,1,0,0,0,'$default_theme',null,null,null,0,'$default_language','$timeoffset','$CloudControl')";
					break;
                case ("supervisor"):
					$sql = $sql_head . "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',0,1,0,0,0,0,'$default_theme',null,null,null,0, '$default_language', '$timeoffset', '$CloudControl')";

                    break;

				case ("user"):
					$sql = $sql_head .  "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',1,0,0,0,0,0,'$default_theme',null,null,null,0, '$default_language', '$timeoffset', '$CloudControl')";
                                      // $sql = $sql_head . "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',1,0,0,'$default_theme',null,null,null,0,'$default_language', '$timeoffset')";
					break;
				default:
					$sql = $sql_head . "$mysql_users_table values(NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[user_name]','$_POST[email]','$_POST[pager]','$pwd','$_POST[office]','$_POST[phone]',1,0,0,0,0,0,'$default_theme',null,null,null,0,'$default_language', '$timeoffset', '$CloudControl')";
					break;
			}

			if($db->query($sql, $mysql_users_table)){
				$success = 1;
				$error_message .= "<br><font color=green>$_POST[user_name] $lang_addedsuccessfully</font><br>";
			}
									//echo($sql);
			//now lets take care of adding the user to the supporter groups.
			//for all the groups listed, if the box was checked, add the user to that group
			for($j=1; $j<=$num_sboxes; $j++){
				$inp = "sbox" . $j;
				if($$inp != ''){
					$user_id = getUserID($user_name);
					$sql = "INSERT into sgroup" . $$inp . " VALUES(NULL, '$user_id', '$user_name')";
					$db->query($sql);
				}
			}

			//now lets take care of adding the user to the user groups.
			//for all the groups listed, if the box was checked, add the user to that group
			for($j=1; $j<=$num_uboxes; $j++){
				$inp = "ubox" . $j;
				if($$inp != ''){
					$user_id = getUserID($user_name);
					$sql = "INSERT into ugroup" . $$inp . " VALUES(NULL, '$user_id', '$user_name')";
					$db->query($sql);
				}
			}
      $uinfo = getUserInfo($user_id); 
      //before quitting, lets send that user an email if his account has been activated.
      	if($uinfo[admin] == 1 || $uinfo[supporter]== 1 || $uinfo[user] == 1
      	   && $_POST[email] != ''){//user has user status
      		$sql = "SELECT template from $mysql_templates_table where name='email_activated_account'";
      		$result = $db->query($sql);
      		$template = $db->fetch_array($result);
      		$template=str_replace("\\'","'",$template[0]);
      		eval("\$email_msg = \"$template\";");
      
      		if($enable_smtp == 'lin'){
      			sendmail($_POST[email], $helpdesk_name, $admin_email, $user_id, $email_msg, $lang_registerforaccount);
      		}
      		if($enable_smtp == 'win'){
      			mail($_POST[email], "$lang_registerforaccount", $email_msg, "From: ".$helpdesk_name."\n");
      		}
      		//no other options...if enable_smtp is set to anything else, the email will not get sent.
      	}

			
		
	}

}


echo '
	<form action="control.php?t=users&act=add" method=post>';

if($error == 1){
	printError($error_message);
}

if($success == 1 && $error != 1){
	printSuccess($error_message);
}


if($error !=1 && $success != 1){

echo '
	<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
				<TR> 
				<TD class=info colspan=100% align=center><B>Add User</B></TD>
				</TR>

				<tr><td colspan=100% class=cat><br>'.
					$lang_adduserexp.'<br><br>
				</td></tr>';
			

			echo '
				<tr><td class=cat align=right width=20%><b>'.$lang_firstname.': </b></td><td class=back><input type=text name=first_name></td></tr>
				<tr><td class=cat align=right width=20%><b>'.$lang_lastname.': </b></td><td class=back><input type=text name=last_name></td></tr>
				<tr><td class=cat align=right width=20%><b>'.$lang_username.': </b></td><td class=back><input type=text name=user_name></td></tr>
				<tr><td class=cat align=right width=20%><b>'.$lang_emailaddy.': </b></td><td class=back><input type=text name=email></td></tr>';
			if($enable_pager == 'On'){
				echo "<tr><td class=cat align=right width=20%><b> $lang_pager $lang_email: </b></td><td class=back><input type=text name=pager></td></tr>";
			}
			echo '
				<tr><td class=cat align=right width=20%><b>'.$lang_password.': </b></td><td class=back><input type=password name=pass1></td></tr>
				<tr><td class=cat align=right width=20%><b>'.$lang_password.' '.$lang_again.': </b></td><td class=back><input type=password name=pass2></td></tr>
				<tr><td class=cat align=right width=20%><b>'.$lang_office.': </b></td><td class=back><input type=text name=office></td></tr>
				<tr><td class=cat align=right width=20%><b>'.$lang_phoneext.' </b></td><td class=back><input type=text name=phone></td></tr>
				<tr><td class=cat align=right width=20%><b> '.$lang_texttimezone.' </b></td>
				<td class=back><select name=timezone>
								<option value=-12>'.$lang_timezone1.'</option>
								<option value=-11>'.$lang_timezone2.'</option>
								<option value=-10>'.$lang_timezone3.'</option>
								<option value=-9>'.$lang_timezone4.'</option>
								<option value=-8>'.$lang_timezone5.'</option>
								<option value=-7>'.$lang_timezone6.'</option>
								<option value=-6>'.$lang_timezone7.'</option>
								<option value=-5>'.$lang_timezone8.'</option>
								<option value=-4>'.$lang_timezone9.'</option>
								<option value=-3.5>'.$lang_timezone10.'</option>
								<option value=-3>'.$lang_timezone11.'</option>
								<option value=-2>'.$lang_timezone12.'</option>
								<option value=-1>'.$lang_timezone13.'</option>
								<option value=0>'.$lang_timezone14.'</option>
								<option value=1>'.$lang_timezone15.'</option>
								<option value=2>'.$lang_timezone16.'</option>
								<option value=3>'.$lang_timezone17.'</option>
								<option value=3.5>'.$lang_timezone18.'</option>
								<option value=4>'.$lang_timezone19.'</option>
								<option value=4.5>'.$lang_timezone20.'</option>
								<option value=5>'.$lang_timezone21.'</option>
								<option value=5.5>'.$lang_timezone22.'</option>
								<option value=5.75>'.$lang_timezone23.'</option>
								<option value=6>'.$lang_timezone24.'</option>
								<option value=6.5>'.$lang_timezone25.'</option>
								<option value=7>'.$lang_timezone26.'</option>
								<option value=8>'.$lang_timezone27.'</option>
								<option value=9>'.$lang_timezone28.'</option>
								<option value=9.5>'.$lang_timezone29.'</option>
								<option value=10>'.$lang_timezone30.'</option>
								<option value=11>'.$lang_timezone31.'</option>
								<option value=12>'.$lang_timezone32.'</option>
								<option value=3>'.$lang_timezone33.'</option>
							  </select>';
							  
				echo"<tr><td class=cat align=right width=20%><b>Cloud Control:<td class=back><select name=CloudControl> <option value='Off' selected> Off </option>";
				echo"<option value='On'> On </option> </b></tr>";
				echo"<tr><td class=cat align=right width=20%><b> $lang_level: </b></td>
				<td class=back><select name=level>				
								<option value=inactive selected>$lang_inactive</option>
								<option value=user>$lang_user</option>
								<option value=supervisor>$lang_supervisor</option>
								<option value=supporter>$lang_supporter</option>
								<option value=admin>$lang_admin</option>
								<option value=accountant>$lang_accountant</option>
								<option value=superadmin>$lang_superadmin</option>								
							   </select>
				</td></tr>
				</td></tr>
			
			</td></tr>
		

</table>
			</td>
			</tr>
		</table>
		<br>";

startTable("$lang_addtosgroups:", "left", 100, 4);

	createSGroupCheckboxes();
	
endTable();

startTable("$lang_addtougroups:", "left", 100, 4);

	createUGroupCheckboxes();
	
endTable();
		if($error != 1){
			echo '<input type=submit name=adduser value="'.$lang_adduser.'">';
		}
}



?>
