<?php

/**************************************************************************************************
**	file:	index.php
**
**		This file lists the groups that the supporter is associated with, not including user groups.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/19/01
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
equire_once "../common/scripts.php";


startTable("$lang_groupslists", "center");
	echo "<tr><td class=cat> $lang_groupslists2 </td></tr>";
	echo "<tr><td class=back>";

	$group_array = getGroupList($cookie_name, 0);
	if(sizeof($group_array) == 0){
		printerror("$lang_nogroups");
	}


	echo "</td></tr>";
endTable();

function listGroupMembers($group)
{
	global $supporter_site_url, $db, $lang_group;

	$group_id = eregi_replace("sgroup", "", $group);

	$sql = "select user_name from $group order by user_name";
	$result = $db->query($sql);
	startTable("$lang_group  --  ".getsGroup($group_id), "left");
	echo "<tr><td class=back2>";
	while($row = $db->fetch_array($result)){
		echo "<li><a href=\"".$supporter_site_url."/index.php?t=memb&mem=".$row['user_name']."\">" . $row['user_name'] . "</a></li>";
	}
	echo "</td></tr>";
	endTable();

}
?>
