<?php

/***************************************************************************************************
**
**	file:	gstats.php
**
**		Survey stats displays the list of questions, and the average ratings of each question.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	12/19/2001
	**
	***********************************************************************************************
			**
			**	Copyright (C) 2001  <JD Bottorf>
			**
			**		This library is free software; you can redistribute it and/or
			**		modify it under the terms of the GNU Lesser General Public
			**		License as published by the Free Software Foundation; either
			**		version 2.1 of the License, or (at your option) any later version.
			**
			**		This library is distributed in the hope that it will be useful,
			**		but WITHOUT ANY WARRANTY; without even the implied warranty of
			**		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
			**		Lesser General Public License for more details.
			**
			**		You should have received a copy of the GNU Lesser General Public
			**		License along with this library; if not, write to the Free Software
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

$open_tickets = 0;

if(!isset($ug) && !isset($sg)){
	//list all the groups, both user and supporter.
	startTable("$lang_groupstats", "center");
		echo "<tr><td class=back width=100%>";
		startTable("$lang_supportergroups", "left");
			echo "<tr><td class=back2 width=100%>";
			listGroups($mysql_sgroups_table);
			echo "</td></tr>";
		endTable();

		startTable("$lang_usergroups", "left");
			echo "<tr><td class=back2 width=100%>";
			listGroups($mysql_ugroups_table);
			echo "</td></tr>";
		endTable();
	endTable();
}
else{

	$highest_status = getRStatus(getHighestRank($mysql_tstatus_table));
	$lowest_status = getRStatus(getLowestRank($mysql_tstatus_table));

	if(isset($sg)){
		printGroupStats($sg, $mysql_sgroups_table);
	}
	else{
		printGroupStats($ug, $mysql_ugroups_table);
	}

}


function listGroups($table)
{
	global $mysql_sgroups_table, $db;

	$sql = "SELECT * from $table where group_name!='All Supporters' order by rank asc";
	$result = $db->query($sql);

	if($table == $mysql_sgroups_table){
		while($row = $db->fetch_array($result)){
			echo "<a href=\"index.php?t=gstats&sg=".$row['id']."\">".$row['group_name'] . "</a><br>";
		}
	}
	else{
		while($row = $db->fetch_array($result)){
			echo "<a href=\"index.php?t=gstats&ug=".$row['id']."\">".$row['group_name'] . "</a><br>";
		}
	}


}

function printGroupStats($gid, $table)
{
	global $mysql_tickets_table, $db;

	$sql = "SELECT group_name from $table where id=$gid";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	startTable("$lang_group \"".$row[0]."\" $lang_stats", "center");
		echo "<tr><td class=back width=100%><br>";
			ShowTotalTicketStats($gid, $table);
			ShowPriorityStats($gid, $table);
			ShowStatusStats($gid, $table);
			ShowCategoryStats($gid, $table);
		echo "</td></tr>";
	endTable();

}

function ShowTotalTicketStats($gid, $table)
{
	global $mysql_sgroups_table, $open_tickets, $lang_open, $lang_closed, $lang_tickets, $lang_total, $lang_ticketstats;

	if($table == $mysql_sgroups_table){
		//get the list of supporters in the group;
		$slist = getSupportPoolUserList($gid);
		$open_tickets = getTotalOpen($slist, 'supporter');
		$closed_tickets = getTotalClosed($slist, 'supporter');
		$total_tickets = getTotalTickets($slist, 'supporter');
	}
	else{
		$slist = getUsersList($gid);
		$open_tickets = getTotalOpen($slist, 'user');
		$closed_tickets = getTotalClosed($slist, 'user');
		$total_tickets = getTotalTickets($slist, 'user');
	}

		startTable("$lang_ticketstats", "center", 100, 2);
			echo "<tr><td class=back2 align=left width=27%>$lang_open $lang_tickets:</td>";
			echo "<td class=back> $open_tickets </td></tr>";
			echo "<tr><td class=back2 align=left width=27%>$lang_closed $lang_tickets:</td>";
			echo "<td class=back> $closed_tickets </td></tr>";
			echo "<tr><td class=back2 align=left width=27%>$lang_total $lang_tickets:</td>";
			echo "<td class=back> $total_tickets </td></tr>";
		endTable();
	

}

function getSupportPoolUserList($id)
{
	global $db;

	$table = "sgroup" . $id;
	$sql = "SELECT user_name from $table where user_name != 'support_pool'";
	$result = $db->query($sql);

	$i=0;
	while($row = $db->fetch_array($result)){
		$list[$i] = $row[0];
		$i++;
	}

	return $list;
}

function getUsersList($id)
{       
        global $db;
 
        $table = "ugroup" . $id;
        $sql = "SELECT user_name from $table where user_name != 'support_pool'";
        $result = $db->query($sql);
                
        $i=0;
        while($row = $db->fetch_array($result)){
                $list[$i] = $row[0];
                $i++;
        }
        
        return $list;
}

function getTotalOpen($list, $group)
{
	global $mysql_tickets_table, $highest_status, $db;

	if($group == 'supporter'){
		$sql = "SELECT count(id) from $mysql_tickets_table where (supporter='$list[0]' ";
			for($i=1; $i<sizeof($list); $i++){
				$sql .= "or supporter='".$list[$i]."'";
			}
	}
	else{
		$sql = "SELECT count(id) from $mysql_tickets_table where (user='$list[0]' ";
			for($i=1; $i<sizeof($list); $i++){
				$sql .= "or user='".$list[$i]."'";
			}
	}

	$sql .= ") and status != '" . $highest_status . "'";

	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	return $row[0];
}

function getTotalClosed($list, $group)
{
	global $mysql_tickets_table, $highest_status, $db;

	if($group == 'supporter'){
		$sql = "SELECT count(id) from $mysql_tickets_table where (supporter='$list[0]' ";
			for($i=1; $i<sizeof($list); $i++){
				$sql .= "or supporter='".$list[$i]."'";
			}
	}
	else{
		$sql = "SELECT count(id) from $mysql_tickets_table where (user='$list[0]' ";
			for($i=1; $i<sizeof($list); $i++){
				$sql .= "or user='".$list[$i]."'";
			}
	}

		$sql .= ") and status = '" . $highest_status . "'";

	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	return $row[0];
}

function getTotalTickets($list, $group)
{
	global $mysql_tickets_table, $db;

	if($group == 'supporter'){
		$sql = "SELECT count(id) from $mysql_tickets_table where (supporter='$list[0]' ";
			for($i=1; $i<sizeof($list); $i++){
				$sql .= "or supporter='".$list[$i]."'";
			}
		$sql .= ")";
	}
	else{
		$sql = "SELECT count(id) from $mysql_tickets_table where (user='$list[0]' ";
			for($i=1; $i<sizeof($list); $i++){
				$sql .= "or user='".$list[$i]."'";
			}
		$sql .= ")";
	}

	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	return $row[0];
}


function ShowPriorityStats($id, $table)
{
	global $mysql_tickets_table, $mysql_tpriorities_table, $mysql_ugroups_table, $open_tickets, $highest_status, $theme, $db, $lang_priority, $lang_totalforeach, $lang_percentofopen, $lang_statistics;

	//order by rank.
	$plist = getPriorityList();

	if($table == $mysql_ugroups_table){
		$ulist = getUsersList($id);

		startTable("$lang_priority $lang_statistics", "center", 100, 2);
		echo "<tr><td colspan=2 class=stats>$lang_totalforeach $lang_priority ($lang_percentofopen)</td></tr>";

		for($i=0; $i<sizeof($plist); $i++){
				$sql = "select count(id) from $mysql_tickets_table where (priority='$plist[$i]' and status!='$highest_status')";
				$sql .= " and (user='$ulist[0]'";

				for($j=1; $j<sizeof($ulist); $j++){
					$sql .= " or user='$ulist[$j]'";
				}

				$sql .= ")";

			$result = $db->query($sql);
			$row = $db->fetch_row($result);

			if($open_tickets == 0)
				$percentage = 0;
			else		
				$percentage = round( (( $row[0] / $open_tickets )* 100), 2);


			echo "<tr>
				<td class=back2 width=27%>
					$plist[$i]
				</td>
				<td class=back>
					<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
				</td>
				</tr>";

		}
	}
	else{
		$slist = getSupportPoolUserList($id);

		startTable("$lang_priority $lang_statistics", "center", 100, 2);
		echo "<tr><td colspan=2 class=stats>$lang_totalforeach $lang_priority ($lang_percentofopen)</td></tr>";

		for($i=0; $i<sizeof($plist); $i++){
				$sql = "select count(id) from $mysql_tickets_table where (priority='$plist[$i]' and status!='$highest_status') and (supporter='$slist[0]'";

				for($j=1; $j<sizeof($slist); $j++){
					$sql .= " or supporter='$slist[$j]'";
				}

				$sql .= ")";

			$result = $db->query($sql);
			$row = $db->fetch_row($result);

			if($open_tickets == 0)
				$percentage = 0;
			else		
				$percentage = round( (( $row[0] / $open_tickets )* 100), 2);


			echo "<tr>
				<td class=back2 width=27%>
					$plist[$i]
				</td>
				<td class=back>
					<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
				</td>
				</tr>";

		}
	}

	endTable();

}


function showStatusStats($id, $table)
{
	global $mysql_tickets_table, $mysql_tstatus_table, $mysql_ugroups_table, $open_tickets, $highest_status, $theme, $db, $lang_status, $lang_totalforeach, $lang_percentofopen, $lang_statistics;

	//order by rank.
	$list = getStatusList();

	startTable("$lang_status $lang_statistics", "center", 100, 2);
	echo "<tr><td colspan=2 class=stats>$lang_totalforeach $lang_status ($lang_percentofopen)</td></tr>";

	if($table == $mysql_ugroups_table){
		$ulist = getUsersList($id);
		for($i=0; $i<sizeof($list)-1; $i++){
			$sql = "select count(id) from $mysql_tickets_table where status='$list[$i]' and (user='$ulist[0]'";

			for($j=1; $j<sizeof($ulist); $j++){
				$sql .= " or user='$ulist[$j]'";
			}

			$sql .= ")";

			$result = $db->query($sql);
			$row = $db->fetch_row($result);
		
				if($open_tickets == 0)
					$percentage = 0;
				else		
					$percentage = round( (( $row[0] / $open_tickets )* 100), 2);
			

			echo "<tr>
				<td class=back2 width=27%>
					$list[$i]
				</td>
				<td class=back>
					<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
				</td>
				</tr>";

		}
	}
	else{

		$ulist = getSupportPoolUserList($id);
		for($i=0; $i<sizeof($list)-1; $i++){
			$sql = "select count(id) from $mysql_tickets_table where status='$list[$i]' and (supporter='$ulist[0]'";

			for($j=1; $j<sizeof($ulist); $j++){
				$sql .= " or supporter='$ulist[$j]'";
			}

			$sql .= ")";

			$result = $db->query($sql);
			$row = $db->fetch_row($result);
		
				if($open_tickets == 0)
					$percentage = 0;
				else		
					$percentage = round( (( $row[0] / $open_tickets )* 100), 2);
			

			echo "<tr>
				<td class=back2 width=27%>
					$list[$i]
				</td>
				<td class=back>
					<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
				</td>
				</tr>";

		}


	}
	endTable();

}


function showCategoryStats($id, $table)
{
	global $mysql_tickets_table, $mysql_tcategories_table, $mysql_ugroups_table, $open_tickets, $highest_status, $theme, $db, $lang_category, $lang_totalforeach, $lang_percentofopen, $lang_statistics;

	//order by rank.
	$list = getCategoryList();

	startTable("$lang_category $lang_statistics", "center", 100, 2);
	echo "<tr><td colspan=2 class=stats>$lang_totalforeach $lang_category ($lang_percentofopen)</td></tr>";

	if($table == $mysql_ugroups_table){
		$ulist = getUsersList($id);
		for($i=0; $i<sizeof($list); $i++){
			$sql = "select count(id) from $mysql_tickets_table where category='$list[$i]' and status!='$highest_status'";
			$sql .= " and (user='$ulist[0]'";

			for($j=1; $j<sizeof($ulist); $j++){
				$sql .= " or user='$ulist[$j]'";
			}

			$sql .= ")";

			$result = $db->query($sql);
			$row = $db->fetch_row($result);

			if($open_tickets == 0)
					$percentage = 0;
			else		
					$percentage = round( (( $row[0] / $open_tickets )* 100), 2);


			echo "<tr>
				<td class=back2 width=27%>
					$list[$i]
				</td>
				<td class=back>
					<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
				</td>
				</tr>";

		}
	}
	else{
		$ulist = getSupportPoolUserList($id);
		for($i=0; $i<sizeof($list); $i++){
			$sql = "select count(id) from $mysql_tickets_table where category='$list[$i]' and status!='$highest_status'";
			$sql .= " and (supporter='$ulist[0]'";

			for($j=1; $j<sizeof($ulist); $j++){
				$sql .= " or supporter='$ulist[$j]'";
			}

			$sql .= ")";

			$result = $db->query($sql);
			$row = $db->fetch_row($result);

			if($open_tickets == 0)
					$percentage = 0;
			else		
					$percentage = round( (( $row[0] / $open_tickets )* 100), 2);


			echo "<tr>
				<td class=back2 width=27%>
					$list[$i]
				</td>
				<td class=back>
					<img src=\"../".$theme['image_dir']."bar.jpg\" height=15 width=\"".$percentage."%\"> $percentage%
				</td>
				</tr>";

		}
	}


	endTable();

}


?>
