<?php

/**************************************************************************************************
**	file:	announce.php
**
**		This file is the default page that is loaded by index.php.  It prints out all of the
**	announcements that are in the database and allows new ones to be added.  
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

require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";

if(isset($submit)){
	$time = time();
	$message = addslashes(stripScripts($message));
	$sql = "insert into $mysql_announcement_table values(NULL, $time, '$message')";
	$db->query($sql);
}

if($t == 'delete'){
	$sql = "delete from $mysql_announcement_table where id=$id";
	$db->query($sql);
}

if(isset($update)){
	$message = addslashes(stripScripts($message));
	$sql = "update $mysql_announcement_table set message='$message' where id=$id";
	$db->query($sql);
}


if($m == 'update'){
	startTable("$lang_announcements", "center", "100%", 1);

		getAnnouncements('admin');
		$message = getMessage($id);

		echo '</tr></td>
		<tr><td class=cat>
		<form action=index.php method=post>
		<textarea name=message rows=8 cols=60%>'.stripslashes($message).'</textarea><br><br>

		<input type=submit name=update value="'.$lang_update . ' ' .$lang_announcement.'">
		<input type=hidden name=id value='.$id.'>
		</form>';

	endTable();
}
else{	//if no update.


startTable("$lang_announcements", "center");

	if(isAdministrator($cookie_name)){
		getAnnouncements('admin');
	}
	else{
		getAnnouncements('supporter');
	}

	echo '</tr></td>
	  <tr><td class=cat align=right>';
			if($a == 1){
				echo '<font size=1> [ <a href="'.$supporter_site_url.'">
						'.$lang_collapse.'</a> ] </font></td></tr>';
			}
			else{
				echo '<font size=1> [ <a href="'.$supporter_site_url.'/?a=1#place">
						'.$lang_expand.'</a> ] </font></td></tr>';
			}
	echo '<tr><td class=cat>
	  <form action=index.php method=post>
		<textarea name=message rows=8 cols=60%></textarea><br><br>
		<input type=submit name=submit value="'.$lang_addannouncement.'">
	  </form>';

endTable();

}
?>
