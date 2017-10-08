<?php

/**************************************************************************************************
**	file:	editprofile.php
**
**		This file allows the supporter to edit his/her personal profile.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	11/19/01
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

if(eregi("supporter", $PHP_SELF)){
	require_once "../common/config.php";
	require_once "../common/$database.class.php";
	require_once "../common/common.php";
}
else{
	require_once "common/config.php";
	require_once "common/$database.class.php";
	require_once "common/common.php";
}
$language = getLanguage($cookie_name);
if($language == ''){
	if(eregi("supporter", $PHP_SELF))
		require_once "../lang/$default_language.lang.php";
	else
		require_once "../lang/$default_language.lang.php";		
}
else{
	if(eregi("supporter", $PHP_SELF))
		require_once "../lang/$language.lang.php";
	else
		require_once "lang/$language.lang.php";
}

if(eregi("supporter", $PHP_SELF))
	require_once "../common/style.php";
else
	require_once "common/style.php";

if(isset($submit)){
	//update the database with the information

	$id = getUserID($cookie_name);

	if($password == '' || !isset($password)){
		$sql = "update $mysql_users_table set user_name='$user', first_name='$first', last_name='$last', office='$office',
				theme='$new_theme', yahoo='$yahoo', msn='$msn', icq='$icq', pager_email='$pager', email='$email', phone='$phone', language='$langfile', time_offset='$offset' where id=$id";
	}
	else{
		$pwd = md5($password);
		$sql = "update $mysql_users_table set user_name='$user', first_name='$first', last_name='$last', office='$office',
				theme='$new_theme', yahoo='$yahoo', msn='$msn', icq='$icq', password='$pwd', pager_email='$pager', email='$email',
				phone='$phone', language='$langfile', time_offset='$offset' where id=$id";
	}

	$db->query($sql);

	//if the user name is changed, we need to log that user out
	if($cookie_name != $user){
		echo "<meta HTTP-EQUIV=\"refresh\" content=\"0; url=".$site_url."/common/logout.php\">";
		exit;
	}
	else{
		if(eregi("supporter", $PHP_SELF))
			echo "<meta HTTP-EQUIV=\"refresh\" content=\"0; url=".$supporter_site_url."/index.php?t=epro\">";
		else
			echo "<meta HTTP-EQUIV=\"refresh\" content=\"0; url=".$site_url."/index.php?t=epro\">";
	}

}

//grab the users info from the database and store in an array.
$user_info = getUserInfo(getUserID($cookie_name));

echo "<form method=\"post\">";
startTable("$lang_editprofile", "center");
	echo "<tr><td class=back>";


		startTable("$lang_editprofile - $lang_required", "left", 100, 2);
			echo "<tr><td width=27% class=back2>$lang_username: </td>
				<td class=back> <input type=hidden name=user value=\"".$user_info['user_name']."\">$user_info[user_name]</td></tr>\n";

			echo "<tr><td width=27% class=back2>$lang_firstname: </td>
				<td class=back> <input type=text size=30 name=first value=\"".$user_info['first_name']."\"></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_lastname: </td>
				<td class=back><input type=text size=30 name=last value=\"".$user_info['last_name']."\"></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_emailaddy: </td>
				<td class=back><input type=text size=30 name=email value=\"".$user_info['email']."\"></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_password: </td>
				<td class=back> <input type=password name=password> $lang_enterpwd</td></tr>\n";
		endTable();

		startTable("$lang_editprofile - $lang_optional", "left", 100, 2);
			echo "<tr><td width=27% class=back2>$lang_theme: </td><td class=back><select name=new_theme>";
				createThemeMenu();
			echo "</select></td></tr>";
			//echo "<tr><td width=27% class=back2>$lang_timeoffset: </td><td class=back><select name=offset>";
			//	createTimeOffsetMenu($time_offset);
			//echo "</select></td></tr>";
			echo "<tr><td width=27% class=back2>$lang_language: </td><td class=back>";
				if(eregi("supporter", $PHP_SELF))
					createLanguageMenu(0);
				else
					createLanguageMenu(1);
			echo "</td></tr>";
			if($enable_pager == 'On' && isSupporter($cookie_name)){
				echo "<tr><td width=27% class=back2>$lang_pager $lang_email: </td><td class=back>
				      <input type=text size=30 name=pager value=\"".$user_info['pager_email']."\"></td></tr>";
			}
			echo "<tr><td width=27% class=back2>$lang_office: </td>
				<td class=back> <input type=text size=30 name=office value=\"".$user_info['office']."\"></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_phoneext: </td>
				<td class=back> <input type=text size=30 name=phone value=\"".$user_info['phone']."\"></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_yahoo: </td><td class=back>
					<input type=text size=30 name=yahoo value=\"".$user_info['yahoo']."\"></td></tr>";
			echo "<tr><td width=27% class=back2>$lang_msn: </td><td class=back> <input type=text size=30 name=msn value=\"".$user_info['msn']."\"></td></tr>";
			echo "<tr><td width=27% class=back2>$lang_icq: </td><td class=back> <input type=text size=30 name=icq value=\"".$user_info['icq']."\"></td></tr>";
			echo "<tr><td width=27% class=back2>$lang_lastactive: </td><td class=back>";
				//echo gmdate("n-d-Y \a\\t h:i a", $last_active + ($time_offset * 3600));
				echo  date("F j, Y, g:i a", $last_active);
			echo "</td></tr>";
		endTable();


	echo "<input type=submit name=submit value='$lang_update'>";
	echo "</form>";
	echo "</td></tr>";

endTable();



?>
