<?php

/**************************************************************************************************
**	file:	ugroups.php
**
**		This file allows for the creation and deletion of user groups.  
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


if(isset($submit)){
	//+++ fixed value for defaultsupportid, CloudControl
	$sql = "insert into $mysql_ugroups_table values(NULL, '$rank', '$group', '$emailtoggle', 1, '$CloudControl')";
	$db->query($sql);

	$sql = "select id from $mysql_ugroups_table where group_name='$group'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	$group_table = "ugroup" . $row[0];
	$sql = "create table $group_table(
			id int(11) not null auto_increment,
			user_id int(11) not null,
			user_name varchar(60) not null,
			primary key(id),
			unique(user_name)
			)";
	$db->query($sql);

}


if($rm == 'delete'){
	
	$group_table = "ugroup" . $id;
	$sql = "drop table $group_table";
	$db->query($sql);	

	$query = "delete from $mysql_ugroups_table where id=$id";
	$db->query($query);

	unset($rm);

}

if(isset($m)){
$num_ugroups = getNumUGroups();
for($i=0; $i<$num_ugroups; $i++){
	$grp = "group" . $i;
	$ran = "rank" . $i;
	$id = "id" . $i;
	$e_all = "emailtoggle" . $i;
	$ccc = "CloudControl" .$i;
	$defaultsupportid = "defaultsupportid" . $i;
	
	$sql = "update $mysql_ugroups_table set rank='".$$ran."', email_all='".$$e_all."', group_name='".$$grp."', CloudControl='".$$ccc."' where id='". $$id . "'";
	$db->query($sql, $mysql_ugroups_table);
	unset($m);
}


}


startTable("$lang_usergroups", "center", 100, 1);
echo '<tr><td class=cat><br>
		'.$lang_usergroupsdesc.' <br><br>
	  </td></tr>';

		$num_rows = listuGroups();

echo '</tr></td>
	 <tr><td class=back2><form action=control.php?t=users&act=ugrp method=post>
		'.$lang_addgroup.': 
		<input type=text name=group></input>
		'.$lang_rank.': <input type=text name=rank size=2></input>';

echo "  $lang_email_all: <select name=emailtoggle <option value=\"OFF\" selected> $lang_off </option>";
echo "<br> <option value=\"On\"> $lang_on </option></select>";

echo " $lang_CloudControl<select name=CloudControl> <option value=\"OFF\" selected> $lang_off </option>";
echo "<br> <option value=\"On\"> $lang_on </option></select><br>";
echo "<input type=submit name=submit value= $lang_addgroup></form>";

endTable();

function listuGroups()
{

	global $mysql_ugroups_table, $db, $lang_rank, $lang_email_all, $lang_delete, $lang_on, $lang_off, $lang_moreoptions, $lang_updategroup;

	$sql = "select * from $mysql_ugroups_table order by rank asc";
	$result = $db->query($sql, $mysql_ugroups_table);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){

		echo "<form action=control.php?t=users&m=update&act=ugrp method=post>";

		$i = 0;

		while($row = $db->fetch_row($result)){

			echo "\n<input type=hidden name=id$i value='$row[0]'>";
			echo "\n<tr><td class=back>";
	
			echo "\n<input type=text name=group$i value=\"$row[2]\">";
			
			echo " $lang_rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo " $lang_email_all: ";
			echo "<select name=emailtoggle$i>
				   	<option value='Off'  "; if($row[3] == $lang_off) echo "selected"; echo ">$lang_off</option>
		   			<option value='On' "; if($row[3] == $lang_on) echo "selected"; echo ">$lang_on</option>
					</select>\n";
			echo " Casaria Cloud Control: ";
			echo "<select name=CloudControl$i>
							<option value='Off'  "; if($row[5] == $lang_off) echo "selected"; echo ">$lang_off</option>
								<option value='On' "; if($row[5]  == $lang_on) echo "selected"; echo ">$lang_on</option>

				</select>\n";
			echo "<a href=control.php?t=users&act=ugrp&rm=delete&id=$row[0]>$lang_delete</a>?";
			echo " <font size=1><a href=control.php?t=users&g=$row[0]&act=uopt>$lang_moreoptions</a>...</font>";
			echo "\n</tr>";
			echo "</td>";
			echo "</tr>";
			$i++;
		}

		echo "<tr><td class=back><br><input type=submit name=m value=\"$lang_updategroup\">";
		echo "</form></td></tr>";

	}

	return $num_rows;

}

function getNumUGroups()
{
	global $mysql_ugroups_table, $db;

	$sql = "select count(group_name) from $mysql_ugroups_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];

}


?>
