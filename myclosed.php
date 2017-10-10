<?php

/**************************************************************************************************
**	file:	myclosed.php
**
**		This file lists the recently closed tickets of the user.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	03/11/02
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
	
/**************************************************************************************************/

$num_to_show = 30;		//this limits the number of tickets to display so that users with thousands
						//of tickets don't have to view all of their previous tickets.

/**************************************************************************************************/

require_once "common/config.php";
require_once "common/$database.class.php";
require_once "common/common.php";

if(!isset($username) && $cookie_name == ''){
	startTable("$lang_youruname", "center");
		echo '<tr><td class=back2><center><br><b>'.$lang_namesearch.':</b><br>';
		 echo "<form method=post>
				<input type=text name=username>";
		echo "<br><br>";
		 echo "<input type=submit name=submit value=$lang_submit>";
		 echo "</form></center></td></tr>";
	endTable();

}

else{

	if($username == '')
		$username = $cookie_name;

	startTable("$lang_myclosed", "center");
		echo '<tr>
			<td class=back> ';

	//everything in here should be the ticket information
	//start the table, use a while loop to cycle through all the tickets.



	echo '<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<tr>
			<td>
				<table cellSpacing=1 cellPadding=5 width="100%" border=0>
					<tr>
						<td class=hf align=center><b>
						 ' . $lang_id . ' </b></td>
						<td class=hf align=center><b>
						 ' . $lang_Supporter . ' </b></td>
						<td class=hf align=center><b>
						 ' . $lang_group . ' </b></td>
						<td class=hf align=center><b>
						 ' . $lang_shortdesc . ' </b></td>
						<td class=hf align=center><b>
						 ' . $lang_created . ' </b></td>
						<td class=hf align=center><b>
						 ' . $lang_status . ' </b></td>
					</tr>';

			$summary = showTickets($username);

	endTable();
echo "$lang_summary: $lang_recordcount $summary[recordcount] $summary[remarks]";	
endTable();

}


function showTickets($name)
{
	global $mysql_tickets_table, $num_to_show, $mysql_tstatus_table, $db;

	$highest = getRStatus(getHighestRank($mysql_tstatus_table));	//set the highest priority rating

	//ticket list limited to 20.
	$sql = "select * from $mysql_tickets_table where user='$name' and status='$highest' order by create_date desc limit 20";
	$result = $db->query($sql);


	$i=0;
	while( ($row = $db->fetch_array($result)) && ($i < $num_to_show) ){
		$group_name = getGroupName($row['groupid']);
		if($group_name == '')
			$group_name = "&nbsp;";

		if($i%2 == 1){
			echo "<tr><td class=back>". str_pad($row['id'], 5, "0", STR_PAD_LEFT) ."</td>";
			echo "<td class=back>". $row['supporter'] ."</td>";
			echo "<td class=back>$group_name</td>";
			echo "<td class=back><a href=\"index.php?t=tinf&id=".$row['id']."\">". stripslashes($row['short']) ."</a></td>";
			echo "<td class=back>". date("m/d/y", $row['create_date']) ."</td>";
			echo "<td class=back>". $row['status'] ."</td></tr>";
		}
		else{
			echo "<tr><td class=back2>". str_pad($row['id'], 5, "0", STR_PAD_LEFT) ."</td>";
			echo "<td class=back2>". $row['supporter'] ."</td>";
			echo "<td class=back2>$group_name</td>";
			
			echo "<td class=back2><a href=\"index.php?t=tinf&id=".$row['id']."\">". $row ['equipment'].": ".stripslashes($row['short']) ."</a></td>";

			echo "<td class=back2>". date("m/d/y", $row['create_date']) ."</td>";
			echo "<td class=back2>". $row['status'] ."</td></tr>";
		}
		$i++;
	}
	$summary = array(	'recordcount' 	=> 	$i, 
		       		'remarks'	=>	''); 
        return $summary;
}

?>