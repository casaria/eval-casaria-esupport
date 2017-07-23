<?php

/**************************************************************************************************
**	file:	time.php
**
**		This file displays all of the time tracking stats based on the ticket ids given.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	01/10/02
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

if(isset($getstats)){

	//put the tids into an array
	$list = explode(",", $tids);
	//remove duplicate tickets from the list.
	$list = array_unique($list);
	//remove any characters from the array that aren't numbers.
	$list = array_filter($list, "is_Numeric");
	$i = 0;
	startTable("$lang_timetracking", "center");
	echo "<tr><td class=back>";
	foreach($list as $value){
		$create_date = getCreateDate($value);
		//throw this in here just in case an invalid ticket number is input.
		//if the create date is not set, the ticket does not exist.
		if($create_date != ''){
			$ticket_data = getTicketTimeInfo($value);
			$ticket = getTicketInfo($value);

			startTable("<a href=\"".$supporter_site_url."/index.php?t=tupd&id=".$value."\">$lang_ticket $value:</a>
			           : $ticket[equipment] / $ticket[short]
			           ", "left", 100, 2);
				
				echo "<tr><td class=back2 width=27%>$lang_created:</td><td class=back>". date("F j, Y, g:i a", $create_date)."</td></tr>";
				echo "<tr><td class=back2 width=27%>$lang_firstresponse:</td>";

				if($ticket_data['first_response'] != 0){
					$response_time = (Integer)$ticket_data['first_response'] - $create_date;
					echo "<td class=back>".date("F j, Y, g:i a", $ticket_data['first_response'])."</td></tr>";
					echo "<tr><td class=back2 width=27%>$lang_responsetime:</td><td class=back>";
						showFormattedTime($response_time);
					echo "</td></tr>";
				}
				else{
					echo "<td class=back><font color=red><b>$lang_noresponseyet</b></font></td></tr>";
					echo "<tr><td class=back2 width=27%>$lang_responsetime:</td><td class=back>";
					$response_time = (Integer)$ticket_data['first_response'] - $create_date;
						showFormattedTime($response_time);
					//reset response time for average stat tracking now.
					$response_time = time() - $create_date;
				}

				

				echo "<tr><td class=back2 width=27%>$lang_totaltimespent:</td><td class=back>";
					showFormattedTime($ticket_data['sum'] * 60);
				echo "</td></tr>";

			//now display all of the information that is specific to supporters
				$supporters = getSupporterList($value);
				
				if(sizeof($supporters) > 0){
					foreach($supporters as $items){
						echo "<tr><td class=subcat width=27%>" . $items['user_name'] . ": </td><td class=back>"; showFormattedTime($items['sum'] * 60);
						$supporter_total[$items['user_name']] += $items['sum'];
						//show the percentage of time spent as well
						if($ticket_data['sum'] != 0){
							$percentage = number_format(($items['sum'] / $ticket_data['sum']) * 100, 2);
							echo "  (".$percentage."%)";
						}
						else{
							$percentage = 0;
						}
						
						echo "</td></tr>";
					}
				}
			endTable();

			//before we end the for loop, we need to calculate our average values.
	
			//calculate the average response time, avg time spent per ticket, avg time from open to close.
			$avg_sum[$i] = $ticket_data['sum'];		//store the sum values in an array so we can avg them all later.

			//store the response time in an array so we can avg them later.
			
			$avg_response[$i] = $response_time;
			$avg_time = $ticket_data['closed_date'] - $create_date;

			if($ticket_data['closed_date'] > 0){
				$avg_ticket_time[$i] = $ticket_data['closed_date'] - $create_date;
			}
			else{
				$avg_ticket_time[$i] = time() - $create_date;
			}
						
			$i++;
		}
	}
	echo "</td></tr>";
	endTable();

	startTable("$lang_time_totals", "left", 100, 2);
		foreach($supporter_total as $k => $v){
			echo "<tr><td class=subcat width=27%>" . $k . ": </td><td class=back>"; showFormattedTime($v * 60);
		}
		echo "</td></tr>";



	endTable();

	startTable("$lang_average $lang_statistics", "left", 100, 2);
		echo "<tr><td class=back2 width=27%>$lang_avgtimespent:</td><td class=back>";
		if(sizeof($avg_sum) != 0){
			showFormattedTime(array_sum($avg_sum) / sizeof($avg_sum)* 60);
		}
		echo "</td></tr>";

		echo "<tr><td class=back2 width=27%>$lang_avgresponsetime :</td><td class=back>";
		if(sizeof($avg_response) != 0){
			showFormattedTime(array_sum($avg_response) / sizeof($avg_response));
		}
		echo "</td></tr>";

		echo "<tr><td class=back2 width=27%>$lang_avgticketspan:</td><td class=back>";
		if(sizeof($avg_ticket_time) != 0){
			showFormattedTime(array_sum($avg_ticket_time) / sizeof($avg_ticket_time));
		}
		echo "</td></tr>";
	endTable();


}
else{

echo "<form method=post>";
startTable("$lang_timetracking", "center");
	echo "<tr><td class=back><br>";
	startTable("$lang_selecttickets", "center", "80%");
		echo "<tr><td class=cat>$lang_selectticketsexp</td></tr>";
		echo "<tr><td class=back2><br>$lang_ticket $lang_ids: <input type=text name=tids size=60%><br><br></td></tr>";
	endTable();
	echo "<center><input type=submit value=\"$lang_getstats\" name=\"getstats\"></center><br>";
	echo "</td></tr>";
endTable();
echo "</form>";
}



function getCreateDate($id)
{
	global $mysql_tickets_table, $db;

	$sql = "SELECT create_date from $mysql_tickets_table where id='$id'";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);
	return $row[0];

}

function getSupporterList($id)
{
	global $mysql_users_table, $mysql_time_table, $db;

	$sql = "SELECT supporter_id, $mysql_users_table.user_name, sum(minutes) as sum from $mysql_users_table, $mysql_time_table where $mysql_users_table.id=supporter_id and ticket_id=$id group by supporter_id";
	
	$result = $db->query($sql);
	
	$i = 0;
	while($row = $db->fetch_array($result)){
		//each row contains a different user name and sum.
		$array[$i]['user_name'] = $row['user_name'];
		$array[$i]['sum'] = $row['sum'];
		$i++;
	}

	return $array;
}


?>

