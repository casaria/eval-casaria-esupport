<?php

/**************************************************************************************************
**	file:	mygroupopen.php
**
**		This file grabs group information about the logged in supporter and displays all open
**	tickets of that user.
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

$supporter_id = getUserID($cookie_name);
$highest_pri = getRPriority(getHighestRank($mysql_tpriorities_table));	//set the highest priority rating

$filter = $f; //via HTTP_POST
	
startTable("$lang_yourgroupselect", "center");
	echo '<tr><td class=back> ';
	$num_groups = getNumberGroups();
	//echo "numgroups: $num_groups";
	$groups = getSGroupList($supporter_id);
	if(sizeof($groups) == 0 && $num_groups != 1){
		printerror("$lang_nogroups");
		$no_groups = 1;
	}


	//everything in here should be the ticket information
	//start the table, use a while loop to cycle through all the tickets.

if($no_groups != 1){
	echo '<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<tr> 
			 <td> 
				<table cellSpacing=1 cellPadding=5 width="100%" border=0>
					<tr>';
						if($s == 'id'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=di><b>'.$lang_id.'</b></a></td>';						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=id><b>'.$lang_id.'</b></a></td>';
						}
						
						if($s == 'sup'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=pus><b>'.$lang_Supporter.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=sup><b>'.$lang_Supporter.'</b></a></td>';
						}
						
						if($s == 'equ'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=uqe><b>'.$lang_equipment.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=equ><b>'.$lang_equipment.'</b></a></td>';
						}
						if($s == 'sho'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=ohs><b>'.$lang_shortdesc.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=sho><b>'.$lang_shortdesc.'</b></a></td>';
						}

						if($s == 'usr'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=rsu><b>'.$lang_user.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=usr><b>'.$lang_user.'</b></a></td>';
						}

						if($s == 'grp'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=prg><b>'.$lang_group.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=grp><b>'.$lang_group.'</b></a></td>';
						}

						if($s == 'pri'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=irp><b>'.$lang_priority.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=pri><b>'.$lang_priority.'</b></a></td>';
						}

						if($s == 'cre'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=erc><b>'.$lang_created.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=cre><b>'.$lang_created.'</b></a></td>';
						}

						if($s == 'lupd'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=dpul><b>'.$lang_last_update.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=lupd><b>'.$lang_last_update.'</b></a></td>';
						}
						if($s == 'sta'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=ats><b>'.$lang_status.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=sta><b>'.$lang_status.'</b></a></td>';
						}

						if($s == 'bst'){
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=tsb><b>'.$lang_billing.'</b></a></td>';
						}
						else{
							echo '<td align=center>
							  <a class=hf href=index.php?t=tmgo&f='.$f.'&s=bst><b>'.$lang_billing.'</b></a></td>';
						}





						if($s == 'rt'){
							echo '<td align=center>
								<a class=hf href="'.$supporter_site_url.'/index.php?t=tmgo&f='.$f.'&s=tr"><b>'.$lang_time.'</b></a></td>';
						 }
						 else{
							echo '<td align=center>
								<a class=hf href="'.$supporter_site_url.'/index.php?t=tmgo&f='.$f.'&s=rt"><b>'.$lang_time.'</b></a></td>';
						 }
					echo '</tr>';

			$summary = listOpenTickets($supporter_id, $s, $groups, $filter);
			
	endTable();
	}
echo "$lang_summary: $lang_recordcount $summary[recordcount] $summary[remarks]";	
endTable();



function listOpenTickets($id, $sort, $groups, $filter)
{
	global $mysql_tickets_table, $cookie_name, $mysql_tstatus_table, $mysql_tpriorities_table, $num_groups, $db;
	$time_offset = getTimeOffset($cookie_name);
	$time =time() + ($time_offset * 3600);
	$day_const = 86400;
	$day_difference = 90 * $day_const;
	$time_from =time() - $day_difference;
	$timeConstraint="";
	$statusmessage = '';
	
	switch ($filter) {
		case ("normal"):
		    $status="!='".getRStatus(getHighestRank($mysql_tstatus_table))."' and status !='".getRStatus(getHoldRank($mysql_tstatus_table))."'";
		break;
		case ("hold"):
		    $status="='".getRStatus(getHoldRank($mysql_tstatus_table))."'";
		break;
		case ("closed"):
		    $status="='".getRStatus(getHighestRank($mysql_tstatus_table))."'";
		break;
		case ("closed_recent"):
				$status="='".getRStatus(getHighestRank($mysql_tstatus_table))."'";
				$timeConstraint=" AND lastupdate > $time_from ";
				$statusmessage="Tickets updated within the last 90 days";
		break;
		default:
		    $status="='%'";
		break;
		
	}
	//grab the list of groups that the user is in so we can print out all tickets for that user's groups.

	//we have a list of groups that the user is a member of...now print out all tickets for all users in those groups.
	if($num_groups == 1){
		$sql2 = "select * from $mysql_tickets_table where status".$status." and (groupid=1";

		if($sort == 'rt' || $sort == 'tr'){
			$sql2 = "SELECT t.*, ($time - t.lastupdate)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and status!='".getRStatus(getHighestRank($mysql_tstatus_table))."' and (groupid=1";
		}
	}
	else{
		$sql2 = "select * from $mysql_tickets_table where status".$status." and (groupid=";
		//+++ add all rows with groupid = 1 
		$sql2 .="1 or groupid=";
		if($sort == 'rt' || $sort == 'tr'){
			$sql2 = "SELECT t.*, ($time - t.lastupdate)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and status!='".getRStatus(getHighestRank($mysql_tstatus_table))."' and (groupid=";
			//+++ add all rows with groupid = 1 
			$sql2 .="1 or groupid=";
		}
		
		//finish setting up the sql2 statement.
		for($i=0; $i<sizeof($groups);$i++){
			//special case:  sgroup1 is in the list means no other groups are present at all.
			if($groups[$i] == 'sgroup1'){
				$sql2 = "	'".getRStatus(getHighestRank($mysql_tstatus_table));
				 
			}
			else {  
				
				if(inGroup($cookie_name, $groups[$i])){
					$group_id = eregi_replace("sgroup", "", $groups[$i]);
					//echo "groupid: $group_id";
					if($flag != 1){
						$sql2 .= $group_id;
						$flag = 1;
					}
					else{
						$sql2 .= " or groupid=" . $group_id;
						$flag = 1;
					}	//end else statement
				}
			}
		
		}	//end for loop
	}	//end else

	$sql2 .=")";
	$sql2 .= $timeConstraint; // add to where clause before order by

	switch($sort){
		case ("id"):
			$sql2 .= " order by id asc";
			break;
		case ("di"):
			$sql2 .= " order by id desc";
			break;
		case ("sup"):
			$sql2 .= " order by supporter asc, create_date asc";
			break;
		case ("pus"):
			$sql2 .= " order by supporter desc, create_date asc";
			break;
		case ("equ"):
			$sql2 .= " order by short asc, equipment asc";
			break;
		case ("uqe"):
			$sql2 .= " order by short desc, equipment asc";
			break;
		case ("sho"):
			$sql2 .= " order by short asc, create_date asc";
			break;
		case ("ohs"):
			$sql2 .= " order by short desc, create_date asc";
			break;
		case ("usr"):
			$sql2 .= " order by user asc, create_date asc";
			break;
		case ("rsu"):
			$sql2 .= " order by user desc, create_date asc";
			break;
		case ("grp"):
			$sql2 .= " order by ugroupid asc, create_date asc";
			break;
		case ("prg"):
			$sql2 .= " order by ugroupid desc, create_date asc";
			break;		case ("pri"):
			listByPriority($id, $sql2, "asc");
			break;
		case ("irp"):
			listByPriority($id, $sql2, "desc");
			break;
		case ("cre"):
			$sql2 .= " order by create_date asc";
			break;
		case ("erc"):
			$sql2 .= " order by create_date desc";
			break;
		case ("sta"):
			listByStatus($id, $sql2, "asc");
			break;
		case ("ats"):
			listByStatus($id, $sql2, "desc");
			break;
		case ("tr"):
			$sql2 .= " order by a desc";
			break;
		case ("rt"):
			$sql2 .= " order by a asc";
			break;
		case ("bst"):
			$sql2 .= " order by ugroupid asc, BILLING_STATUS desc, lastupdate asc";
			break;
		case ("tsb"):
			$sql2 .= " order by ugroupid asc, BILLING_STATUS asc, lastupdate asc";
			break;
		case ("lupd"):
			$sql2 .= " order by ugroupid asc, lastupdate desc";
			break;
		case ("dpul"):
			$sql2 .= " order by ugroupid asc, lastupdate asc";
			break;
			
			
			
		default:
			$sql2 .= " order by ugroupid desc, id asc";
			break;
	}
  
  echo $statusmessage;

if($sort != "pri" && $sort != "sta" && $sort !='irp' && $sort != 'ats'){
	$result2 = $db->query($sql2);
	$summary = displayTicket($result2);
	
}
return $summary;
	
}

/**	Takes the user id and returns an array containing the list of group ids that the user is in.	**/
function getSGroupList($id)
{
	global $mysql_sgroups_table, $num_groups, $db;



	if($num_groups == 1)
		$sql = "select id from $mysql_sgroups_table";
	else
		$sql = "select id from $mysql_sgroups_table where id != 1";

	$result = $db->query($sql);
	//now we have the list of all the supporter groups.
	$i=0;
	while($row = $db->fetch_row($result)){
		if($num_groups != 1){
			$sql2 = "select id from sgroup" . $row[0] . " where user_id=$id";
			$result2 = $db->query($sql2);
			if($db->num_rows($result2) != 0){
				$grouplist[$i] = "sgroup" . $row[0];
				$i++;
			}
		 }
	}

	//returns a list of strings (group table names).
	return $grouplist;

}


function listByPriority($id, $query, $order)
{
	global $mysql_tpriorities_table, $mysql_tickets_table, $db;

	$sql = "select priority from $mysql_tpriorities_table order by rank $order";
	$result = $db->query($sql);
	while($row = $db->fetch_row($result)){
		$query2 = $query . " and priority='$row[0]'";
		$result2 = $db->query($query2);
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

	$result3 = $db->query($sql);
	displayTicket($result3);

}

function listByStatus($id, $query, $order)
{
	global $mysql_tstatus_table, $mysql_tickets_table, $db;

	$sql = "select status from $mysql_tstatus_table order by rank $order";
	$result = $db->query($sql);
	while($row = $db->fetch_row($result)){
		$query2 = $query . " and status='$row[0]'";
		$result2 = $db->query($query2);
		displayTicket($result2);
	}

	//now we have to list all of the tickets that have a priority other than what is in the priority list
	//ie. if a priority gets deleted via the admin tool and there are still tickets with that priority, we
	// still need to list them.

	$sql = "select * from $mysql_tickets_table where ";
	$status = getStatusList();
	for($i=0; $i<sizeof($status); $i++){
		if($flag != 1){
			$sql .= "status!='" . $status[$i] . "'";
			$flag = 1;
		}
		else{
			$sql .= " and status!='" . $status[$i] ."'";
			$flag = 1;
		}
	}

	$result3 = $db->query($sql);
	displayTicket($result3);

}


function getNumberGroups()
{
	global $mysql_sgroups_table, $db;

	$sql = "select id from $mysql_sgroups_table";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	return $num_rows;

}


?>

