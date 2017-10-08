<?php

/**************************************************************************************************
**	file:	sgroups.php
**
**		This file allows for the creation and deletion of supporter groups.  
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

//check to make sure the file is called from either control.php or index.php and not called directly.
if(!eregi("index.php", $PHP_SELF) && !eregi("control.php", $PHP_SELF)){
	echo "$lang_noaccess";
	exit;
}

require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";

if(isset($alter)){

	$sql = "INSERT into $mysql_sgroups_table values(NULL, '$rank', '$group', 'No')";
	$db->query($sql);

	$sql = "select id from $mysql_sgroups_table where group_name='$group'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	$group_table = "sgroup" . $row[0];
	$sql = "create table $group_table(
			id int(11) not null auto_increment,
			user_id int(11) not null,
			user_name varchar(60) not null,
			primary key(id),
			unique(user_name)
			)";
	$db->query($sql);

	$sql = "INSERT into $group_table VALUES(NULL, 1, 'support_pool')";
	$db->query($sql);

	//once a group is created, we can change the ranking of the "All Supporters" group
	//to take care of the command line issues.
	//$sql = "DELETE from $mysql_sgroups_table where group_name='All Supporters'";
	//$db->query($sql);
	unset($alter);

}


if($rm == 'delete'){
	
	$group_table = "sgroup" . $id;
	$sql = "drop table IF EXISTS $group_table";
	$db->query($sql);	

	$query = "delete from $mysql_sgroups_table where id=$id";
	$db->query($query);

	//if there are no other groups, we need to change the rank of the "All Supporters" group
	//back to 0 so it shows up again.

	if(getNumSGroups() == 1){
		$sql = "INSERT into $mysql_sgroups_table VALUES(NULL, 0, 'All Supporters', 'Yes')";
		$db->query($sql);
	}
	unset($rm);
}

if(isset($m)){
	$num_sgroups = getNumSGroups();
	for($i=0; $i<$num_sgroups; $i++){
		$grp = "group" . $i;
		$ran = "rank" . $i;
		$id = "id" . $i;
		
		$sql = "update $mysql_sgroups_table set rank='".$$ran."', group_name='".$$grp."' where id='". $$id . "'";
		$db->query($sql);
		unset($m);

	}

	//reset all default_group settings;
	$sql = "UPDATE sgroups set default_group='No'";
	$db->query($sql);

	//now set the default_group based on the input.
	$sql = "UPDATE $mysql_sgroups_table set default_group='Yes' where id=$did";
	$db->query($sql);
}


startTable("$lang_supportergroups", "center", "100%", 2);
echo '<tr><td class=cat colspan=2><br>
		'.$lang_supportergroupexp.'<br><br>
      </td></tr>';

$num_rows = listsGroups();

echo '</tr></td>
		 <tr><td class=back2 colspan=2><form method=post action="'.$admin_site_url.'/control.php?t=users&act=sgrp">
			'.$lang_addgroup.': 
			<input type=text name=group></input>
			'.$lang_rank.': <input type=text name=rank size=2></input><br>
			<input type=submit name=alter value="'.$lang_addgroup.'"></form>';
endTable();


function listsGroups()
{

	global $mysql_sgroups_table, $db, $lang_delete, $lang_rank, $lang_update, $lang_moreoptions;

	$sql = "select * from $mysql_sgroups_table where group_name != 'All Supporters' order by rank asc";
	$result = $db->query($sql, $mysql_sgroups_table);
	$num_rows = $db->num_rows($result);

	$sql2 = "SELECT id from $mysql_sgroups_table where default_group='Yes'";
	$result2 = $db->query($sql2);
	$row2 = $db->fetch_array($result2);

	if($num_rows != 0){

		echo "<form action=control.php?t=users&act=sgrp method=post>";
		echo "<tr><td class=cat width=\"12\">Default</td><td class=back width=\"100%\">&nbsp;</td></tr>";

		$i = 0;

		while($row = $db->fetch_array($result)){

			echo "\n<input type=hidden name=id$i value='$row[0]'>";
			echo "\n<tr><td class=back valign=\"center\" align=\"center\" width=\"12\"><input type=radio name=did value=\"$row[id]\"";
			if($row2[id] == $row[id]){
				echo " checked";
			}
			echo "></td><td class=back width=\"100%\">";
			echo "\n<input type=text name=group$i value=\"$row[2]\">";
			echo "\n&nbsp;&nbsp; $lang_rank: <input type=text size=2 value='$row[1]' name=rank".$i.">\n";
			//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=sgroups.php?t=delete&id=$row[0]>Delete</a>?";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=control.php?t=users&act=sgrp&rm=delete&id=$row[0]>$lang_delete</a>?";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "<font size=1><a href=control.php?t=users&act=sopt&g=$row[0]>$lang_moreoptions</a>...</font>";
			echo "\n</tr>";
			echo "</td>";
			echo "</tr>";
			$i++;
		}

		echo "<tr><td class=back colspan=2><br><input type=submit name=m value=\"$lang_update\">";
		echo "</form></td></tr>";

	}

	return $num_rows;

}

function getNumSGroups()
{
	global $mysql_sgroups_table, $db;

	$sql = "select count(group_name) from $mysql_sgroups_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];

}


?>
