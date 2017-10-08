<?php

/**************************************************************************************************
**	file:	myrecent.php
**
**		This file grabs the information about all recent tickets of the supporter that is logged in
**	and displays that information.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	8/26/02
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

$limit = 15;	//limit the number of items to be displayed on the page.
$highest_pri = getRPriority(getHighestRank($mysql_tpriorities_table));	//set the highest priority rating
$supporter_id = getUserID($cookie_name);

startTable("$lang_yourrecent", "center");
	echo '<tr>
			<td class=back> ';

	//everything in here should be the ticket information
	//start the table, use a while loop to cycle through all the tickets.



	echo '<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<tr> 
			<td> 
				<table cellSpacing=1 cellPadding=5 width="100%" border=0>
					<tr>';

					    echo '<td align=center>
					          <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre"><b>'.$lang_id.'</b></a></td>';

  					    echo '<td align=center>
						  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=sup"><b>'.$lang_Supporter.'</b></a></td>';


						if($s == 'ds'){
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=sd"><b>'.$lang_shortdesc.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=ds"><b>'.$lang_shortdesc.'</b></a></td>';
						}

						if($s == 'ur'){
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=ru"><b>'.$lang_user.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=ur"><b>'.$lang_user.'</b></a></td>';
						}
							
						if($s == 'pr'){
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=rp"><b>'.$lang_priority.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=pr"><b>'.$lang_priority.'</b></a></td>';
						}

						if($s == 'cr'){
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=rc"><b>'.$lang_created.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmre&s=cr"><b>'.$lang_created.'</b></a></td>';
						}

						echo '<td align=center>
						      <a class=hf href='.$supporter_site_url.'/index.php?t=tmre"><b>'.$lang_status.'</b></a></td>';
						echo '<td align=center>
						      <a class=hf href='.$supporter_site_url.'/index.php?t=tmre"><b>'.$lang_time.'</b></a></td>';

					echo '</tr>';

	listRecentTickets($supporter_id, $s);

	endTable();
endTable();



function listRecentTickets($id, $sort)
{
	global $mysql_tickets_table, $cookie_name, $mysql_tstatus_table, $mysql_tpriorities_table, $db, $limit;

	$time = time();
	$updatelimit = $time - (30*60*60*24);	//the time 30 days ago.
	switch($sort){
		case ("sd"):
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate>$updatelimit order by short asc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
		case ("ds"):
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate > $updatelimit order by short desc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
		case ("ur"):
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate > $updatelimit order by user asc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
		case ("ru"):
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate>$updatelimit order by user desc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
		case ("pr"):
			listByPriority($id, "asc");
			break;
		case ("rp"):
			listByPriority($id, "desc");
			break;
		case ("cr"):
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate>$updatelimit order by create_date asc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
		case ("rc"):
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate>$updatelimit order by create_date desc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
		case ("id"):
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate>$updatelimit order by id desc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
		default:
			$sql = "select * from $mysql_tickets_table where status='".getRStatus(getHighestRank($mysql_tstatus_table))."' and supporter_id=$id and lastupdate>$updatelimit order by id desc limit $limit";
			$result = $db->query($sql);
			displayTicket($result);
			break;
	}

	
}

function listByPriority($id, $order)
{
	global $mysql_tpriorities_table, $mysql_tickets_table, $mysql_tstatus_table, $db, $limit;
	$updatelimit = time() - (30*60*60*24);

	$high_status = getRStatus(getHighestRank($mysql_tstatus_table));
	$sql = "select priority from $mysql_tpriorities_table order by rank $order limit $limit";
	$result = $db->query($sql);
	while($row = $db->fetch_row($result)){
		$sql2 = "select * from $mysql_tickets_table where priority='$row[0]' and supporter_id=$id and status='$high_status' and lastupdate>$updatelimit limit $limit";
		$result2 = $db->query($sql2);
		displayTicket($result2);
	}

	//now we have to list all of the tickets that have a priority other than what is in the priority list
	//ie. if a priority gets deleted via the admin tool and there are still tickets with that priority, we
	// still need to list them.

	$sql = "select * from $mysql_tickets_table where ";
	$prios = getPriorityList();
	for($i=0; $i<sizeof($prios); $i++){
		if($flag != 1){
			$sql .= "priority!='" . $prios[$i] . "'";
			$flag = 1;
		}
		else{
			$sql .= " and priority!='" . $prios[$i] ."'";
			$flag = 1;
		}
	}
	$sql .= "  limit $limit";
	$result3 = $db->query($sql);
	displayTicket($result3);

}



?>
