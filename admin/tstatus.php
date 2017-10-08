<?php

/**************************************************************************************************
**	file:	tstatus.php
**
**		This file allows the admin to add, delete, and modify the status of the tickets.
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
	$sql = "insert into $mysql_tstatus_table values(NULL, '$rank', '$status', NULL)";
	$db->query($sql);
}

if($rm == 'delete'){
	$sql = "delete from $mysql_tstatus_table where id=$id";
	$db->query($sql);
	unset($rm);
}

if($m == 'Update'){
$num_status = getNumStatus();
for($i=0; $i<$num_status; $i++){
	$stat = "status" . $i;
	$ran = "rank" . $i;
	$id = "id" . $i;
	
	$sql = "update $mysql_tstatus_table set rank='".$$ran."', status='".$$stat."' where id='". $$id . "'";
	$db->query($sql);
}


}


echo "<form action=control.php?t=topts&act=tsta method=post>";
startTable("$lang_ticket $lang_status", "center", "100%", 1);

	echo '<tr><td class=cat><br>'.$lang_statusexp.'
			<br><br>
		  </td></tr>';

	$num_rows = listtStatus();

	echo '</tr></td>
		 <tr><td class=back2>
		'.$lang_addstatus.': 
		<input type=text name=status></input>
		'.$lang_rank.': <input type=text name=rank size=2></input><br>
		<input type=submit name=submit value="'.$lang_addstatus.'"></input>';

endTable();


function listtStatus()
{

	global $mysql_tstatus_table, $db, $lang_rank, $lang_delete, $lang_update;

	$sql = "select * from $mysql_tstatus_table order by rank, status asc";
	$result = $db->query($sql, $mysql_tstatus_table);
	$num_rows = mysql_num_rows($result);

	if($num_rows != 0){

		echo "<form name=form2 action=control.php?t=topts&m=update&act=tsta method=get>";

		$i = 0;

		while($row = $db->fetch_row($result)){

			echo "<input type=hidden name=id$i value='$row[0]'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=status$i value=\"$row[2]\">";
			echo "&nbsp;&nbsp; $lang_rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo "&nbsp;&nbsp;<a href=control.php?t=topts&act=tsta&rm=delete&id=$row[0]>$lang_delete</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
		echo "<tr><td class=back><input type=submit name=m value=\"$lang_update\"></td></tr>";
		

	}

	return $num_rows;

}

function getNumStatus()
{
	global $mysql_tstatus_table, $db;

	$sql = "select count(status) from $mysql_tstatus_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];

}



?>
