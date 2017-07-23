<?php

/**************************************************************************************************
**	file:	tstats.php
**
**		This file allows the admin to see the stats for the tickets based on id or total tickets.
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


$status = getRStatus(getHighestRank($mysql_tstatus_table));
$total_open = getTotalNumOpenTickets($id);
$user_total = getTotalNumTickets($id);

startTable("$lang_statistics", "center");
echo "<tr><td class=back><br>";

//show total open tickets
showTicketStats($id);

//show ticket priority of open tickets.
showPriorityStats($id);

//show ticket status of open tickets.
showStatusStats($id);

//show category stats
showCategoryStats($id);

//show average open time for tickets.

//show open ticket count per user.



//show total tickets closed.

//show average answer time.

echo "</td></tr>";
endTable();



function showTicketStats($id)
{
	global $total_open, $lang_ticket, $lang_statistics, $lang_tickets, $lang_open, $lang_closed,$lang_total;

	startTable("$lang_ticket $lang_statistics", "center", 100, 2);

		echo "<tr><td class=back2 width=27%>$lang_open $lang_tickets:</td>
				<td class=back>".
					$total_open
				."</td></tr>";

		echo "<tr><td class=back2 width=27%>$lang_closed $lang_tickets:</td>
				<td class=back>".
					getTotalNumClosedTickets($id)
				."</td></tr>";

		echo "<tr><td class=back2 width=27%>$lang_total $lang_tickets:</td>
				<td class=back>".
					getTotalNumTickets($id)
				."</td></tr>";

	endTable();

}


function showPriorityStats($id)
{
	global $mysql_tickets_table, $mysql_tpriorities_table, $total_open, $status, $theme, $db, $lang_priority, $lang_statistics, $lang_percentofopen, $lang_totalforeach;

	//order by rank.
	$list = getPriorityList();
	

	startTable("$lang_priority $lang_statistics", "center", 100, 2);
	echo "<tr><td colspan=2 class=stats>$lang_totalforeach $lang_priority ($lang_percentofopen)</td></tr>";

	for($i=0; $i<sizeof($list); $i++){
		if(!isset($id) || $id == '')
			$sql = "select count(id) from $mysql_tickets_table where priority='$list[$i]' and status!='$status'";
		else
			$sql = "select count(id) from $mysql_tickets_table where priority='$list[$i]' and supporter_id=$id and status!='$status'";

		$result = $db->query($sql);
		$row = $db->fetch_row($result);

		if($total_open == 0)
			$percentage = 0;
		else		
			$percentage = round( (( $row[0] / $total_open )* 100), 2);


		echo "<tr>
			<td class=back2 width=27%>
				$list[$i]
			</td>
			<td class=back>
				<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
			</td>
			</tr>";

	}

	endTable();

}


function showStatusStats($id)
{
	global $mysql_tickets_table, $mysql_tstatus_table, $total_open, $status, $user_total, $theme, $db, $lang_status, $lang_statistics, $lang_totalforeach, $lang_percentofopen;

	//order by rank.
	$list = getStatusList();

	startTable("$lang_status $lang_statistics", "center", 100, 2);
	echo "<tr><td colspan=2 class=stats>$lang_totalforeach $lang_status ($lang_percentofopen)</td></tr>";

	for($i=0; $i<sizeof($list)-1; $i++){
		
		if(!isset($id) || $id == '')
			$sql = "select count(id) from $mysql_tickets_table where status='$list[$i]'";
		else
			$sql = "select count(id) from $mysql_tickets_table where status='$list[$i]' and supporter_id=$id";

		$result = $db->query($sql);
		$row = $db->fetch_row($result);
	
			if($total_open == 0)
				$percentage = 0;
			else		
				$percentage = round( (( $row[0] / $total_open )* 100), 2);
		

		echo "<tr>
			<td class=back2 width=27%>
				$list[$i]
			</td>
			<td class=back>
				<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
			</td>
			</tr>";

	}

	endTable();

}


function showCategoryStats($id)
{
	global $mysql_tickets_table, $mysql_tcategories_table, $total_open, $user_total, $status, $theme, $db, $lang_category, $lang_statistics, $lang_totalforeach, $lang_percentofopen;

	//order by rank.
	$list = getCategoryList();

        startTable("$lang_category $lang_statistics", "center", 100, 2);
	echo "<tr><td colspan=2 class=stats>$lang_totalforeach $lang_category ($lang_percentofopen)</td></tr>";

	for($i=0; $i<sizeof($list); $i++){
		if(!isset($id) || $id == '')
			$sql = "select count(id) from $mysql_tickets_table where category='$list[$i]' and status!='$status'";
		else
			$sql = "select count(id) from $mysql_tickets_table where category='$list[$i]' and supporter_id=$id and status!='$status'";

		$result = $db->query($sql);
		$row = $db->fetch_row($result);

		if($total_open == 0)
				$percentage = 0;
		else		
				$percentage = round( (( $row[0] / $total_open )* 100), 2);


		echo "<tr>
			<td class=back2 width=27%>
				$list[$i]
			</td>
			<td class=back>
				<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
			</td>
			</tr>";

	}

	endTable();

}






?>
