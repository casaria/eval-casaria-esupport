<?php

/**************************************************************************************************
**	file:	tpriorities.php
**
**		This file allows the admin to add, delete, and modify the ticket priorities.
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

	$sql = "insert into $mysql_tpriorities_table values(NULL, '$rank', '$priority', '$response_time')";
	$db->query($sql);

}
if($rm == 'delete'){
	$sql = "delete from $mysql_tpriorities_table where id=$id";
	$db->query($sql);
	unset($rm);
}

if(isset($m)){
	$num_priorities = getNumPriorities();
	for($i=0; $i<=$num_priorities; $i++){
		$pri = "priority" . $i;
		$ran = "rank" . $i;
		$id = "id" . $i;
		$response = "response" . $i;

		$response_time = $$response * 3600;

		$sql = "update $mysql_tpriorities_table set rank='".$$ran."', priority='".$$pri."', response_time='".$response_time."' where id='".$$id."'";
		$db->query($sql, $mysql_tpriorities_table);
	}

}


echo "<form action=control.php?t=topts&act=tpri method=post>";
startTable("$lang_ticket $lang_priorities", "center", "100%", 1);

	echo '<tr><td class=cat><br>
			'.$lang_prioritiesexp.'<br><br>
		  </td></tr>';

	$num_rows = listtPriorities();

	echo '</tr></td>
		 <tr><td class=back2>
		'.$lang_addpriority.': 
		<input type=text name=priority></input><br>
		'.$lang_responsetime.': <input type=text name=response size=2></input> '.$lang_hours.'<br>
		'.$lang_rank.': <input type=text name=rank size=2></input><br>
		<input type=submit name=submit value="'.$lang_addpriority.'"></input>';

endTable();


function listtPriorities()
{

	global $mysql_tpriorities_table, $db, $lang_responsetime, $lang_delete, $lang_rank, $lang_update, $lang_hours;

	$sql = "select * from $mysql_tpriorities_table order by rank asc";
	$result = $db->query($sql, $mysql_tpriorities_table);
	$num_rows = $db->num_rows($result);

	if($num_rows != 0){

		echo "<form action=control.php?t=topts&m=update&act=tpri method=post>";

		$i = 0;

		while($row = $db->fetch_array($result)){
			echo "<input type=hidden name=id$i value='".$row[id]."'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=priority$i value=\"".$row['priority']."\">";
			echo "&nbsp;&nbsp; $lang_responsetime: <input type=text size=2 value='".toHours($row['response_time'])."' name=response".$i."> $lang_hours ";
			echo "&nbsp;&nbsp; $lang_rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo "&nbsp;&nbsp;<a href=control.php?t=topts&act=tpri&rm=delete&id=$row[0]>$lang_delete</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
		echo "<tr><td class=back><input type=submit name=m value=\"$lang_update\"></td></tr>";

	}

	return $num_rows;

}

function getNumPriorities()
{
	global $mysql_tpriorities_table, $db;

	$sql = "select count(priority) from $mysql_tpriorities_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];
}

function toHours($num)
{
	$num = $num / 3600;
	return $num;
}



?>
