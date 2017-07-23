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

require_once "common/config.php";
require_once "common/$database.class.php";
require_once "common/common.php";

$user_id = getUserID($cookie_name);
$highest_pri = getRPriority(getHighestRank($mysql_tpriorities_table));	//set the highest priority rating

if ($opentickets) 
  startTable("$lang_yourgroups", "center");
else 
  startTable("$lang_yourgroupsclose", "center");

	echo '<tr><td class=back> ';
	$num_groups = getNumberGroups();
	$groups = getUsersGroupList($user_id);
	echo "$lang_usergroups:<br>";
	for ($i=0; $i< sizeof ($groups); $i++) {
		 $group_id =  eregi_replace("ugroup", "", $groups[$i]);
		 $groupname = getuGroup($group_id);
		 echo "$groupname <br>";
	}
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
       		      if ($opentickets) 
     						{
        						if($s == 'id'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=di><b>'.$lang_id.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=id><b>'.$lang_id.'</b></a></td>';
        						}
        						
        						if($s == 'sup'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=pus><b>'.$lang_Supporter.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=sup><b>'.$lang_Supporter.'</b></a></td>';
        						}
        
        						if($s == 'equ'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=uqe><b>'.$lang_equipment.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=equ><b>'.$lang_equipment.'</b></a></td>';
        						}
        
        						if($s == 'sho'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=ohs><b>'.$lang_shortdesc.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=sho><b>'.$lang_shortdesc.'</b></a></td>';
        						}
        
        						if($s == 'usr'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=rsu><b>'.$lang_user.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=usr><b>'.$lang_user.'</b></a></td>';
        						}
        
        						if($s == 'pri'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=irp><b>'.$lang_priority.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=pri><b>'.$lang_priority.'</b></a></td>';
        						}
        
        						if($s == 'cre'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=erc><b>'.$lang_created.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=cre><b>'.$lang_created.'</b></a></td>';
        						}
        
        						if($s == 'sta'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=ats><b>'.$lang_status.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgo&s=sta><b>'.$lang_status.'</b></a></td>';
        						}
        						if($s == 'rt'){
        							echo '<td align=center>
        								<a class=hf href=index.php?t=tmgo&s=sta><b>'.$lang_time.'</b></a></td>';
        						 }
        						 else{
        							echo '<td align=center>
        								<a class=hf href=index.php?t=tmgo&s=sta><b>'.$lang_time.'</b></a></td>';
        						 }
               } else
               { // closed tickets
        						if($s == 'id'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=di><b>'.$lang_id.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=id><b>'.$lang_id.'</b></a></td>';
        						}
        						
        						if($s == 'sup'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=pus><b>'.$lang_Supporter.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=sup><b>'.$lang_Supporter.'</b></a></td>';
        						}
        
        						if($s == 'equ'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=uqe><b>'.$lang_equipment.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=equ><b>'.$lang_equipment.'</b></a></td>';
        						}
        
        						if($s == 'sho'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=ohs><b>'.$lang_shortdesc.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=sho><b>'.$lang_shortdesc.'</b></a></td>';
        						}
        
        						if($s == 'usr'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=rsu><b>'.$lang_user.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=usr><b>'.$lang_user.'</b></a></td>';
        						}
        
        						if($s == 'pri'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=irp><b>'.$lang_priority.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=pri><b>'.$lang_priority.'</b></a></td>';
        						}
        
        						if($s == 'cre'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=erc><b>'.$lang_created.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=cre><b>'.$lang_created.'</b></a></td>';
        						}
        
        						if($s == 'sta'){
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=ats><b>'.$lang_status.'</b></a></td>';
        						}
        						else{
        							echo '<td align=center>
        							  <a class=hf href=index.php?t=tmgc&s=sta><b>'.$lang_status.'</b></a></td>';
        						}
        						if($s == 'rt'){
        							echo '<td align=center>
        								<a class=hf href=index.php?t=tmgc&s=sta><b>'.$lang_time.'</b></a></td>';
        						 }
        						 else{
        							echo '<td align=center>
        								<a class=hf href=index.php?t=tmgc&s=sta><b>'.$lang_time.'</b></a></td>';
        						 }               	
               	
               } //closed tickets
            echo '</tr>';
	 $summary = listTickets($user_id, $s, $groups, $opentickets);
	 endTable();
}
echo "$lang_summary: $lang_recordcount $summary[recordcount] $summary[remarks] <BR>";

endTable();



function listTickets($id, $sort, $groups, $opentickets)
{
	global $mysql_tickets_table, $cookie_name, $mysql_tstatus_table, $mysql_tpriorities_table, $num_groups, $db;
	$time =time();

	//grab the list of groups that the user is in so we can print out all tickets for that user's groups.

	//we have a list of groups that the user is a member of...now print out all tickets for all users in those groups.
	
		$sql2 = "select * from $mysql_tickets_table where status";
		if ($opentickets) 
		  $sql2 .= "!='";
		else
		  $sql2 .= "='";
		
		$sql2 .= getRStatus(getHighestRank($mysql_tstatus_table)). "' and status !='" . getRStatus(getHoldRank($mysql_tstatus_table))."' and (ugroupid=";
		//echo $sql2;

		if($sort == 'rt' || $sort == 'tr'){
			$sql2 = "SELECT t.*, ($time - t.lastupdate)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and status!='".getRStatus(getHighestRank($mysql_tstatus_table))."' and (groupid=";
		}
		
		//finsih setting up the sql2 statement.
		for($i=0; $i<sizeof($groups);$i++){		
				$group_id = eregi_replace("ugroup", "", $groups[$i]);
				if($flag != 1){
					$sql2 .= $group_id;
					$flag = 1;
				}
				else{
					$sql2 .= " or groupid=" . $group_id;
					$flag = 1;
				}	//end else statement
		}	//end for loop
	

	$sql2 .=")";

	switch($sort){
		case ("id"):
			$sql2 .= " order by id desc";
			break;
		case ("di"):
			$sql2 .= " order by id asc";
			break;		
		
		case ("sup"):
			$sql2 .= " order by supporter asc, create_date asc";
			break;
		case ("pus"):
			$sql2 .= " order by supporter desc, create_date asc";
			break;
		case ("equ"):
			$sql2 .= " order by equipment asc, create_date asc";
			break;
		case ("uqe"):
			$sql2 .= " order by equipment desc, create_date asc";
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
		case ("pri"):
			$summary = listByPriority($id, $sql2, "asc");
			break;
		case ("irp"):
			$summary = listByPriority($id, $sql2, "desc");
			break;
		case ("cre"):
			$sql2 .= " order by create_date asc";
			break;
		case ("erc"):
			$sql2 .= " order by create_date desc";
			break;
		case ("sta"):
			$summary = listByStatus($id, $sql2, "asc");
			break;
		case ("ats"):
			$summary = listByStatus($id, $sql2, "desc");
			break;
		case ("tr"):
			$sql2 .= " order by a desc";
			break;
		case ("rt"):
			$sql2 .= " order by a asc";
			break;
		default:
			$sql2 .= " order by id asc";
			break;
	}


if($sort != "pri" && $sort != "sta" && $sort !='irp' && $sort != 'ats'){
	$result2 = $db->query($sql2);

	$summary = displayUserTicket($result2);
	
}	
return $summary;
	
}




function listByPriority($id, $query, $order)
{
	global $mysql_tpriorities_table, $mysql_tickets_table, $db;

	$sql = "select priority from $mysql_tpriorities_table order by rank $order";
	$result = $db->query($sql);
	while($row = $db->fetch_row($result)){
		$query2 = $query . " and priority='$row[0]'";
		$result2 = $db->query($query2);
		displayUserTicket($result2);
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
	return displayUserTicket($result3);

}

function listByStatus($id, $query, $order)
{
	global $mysql_tstatus_table, $mysql_tickets_table, $db;

	$sql = "select status from $mysql_tstatus_table order by rank $order";
	$result = $db->query($sql);
	while($row = $db->fetch_row($result)){
		$query2 = $query . " and status='$row[0]'";
		$result2 = $db->query($query2);
		displayUserTicket($result2);
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
	return displayUserTicket($result3);

}


function getNumberGroups()
{
	global $mysql_ugroups_table, $db;

	$sql = "select id from $mysql_ugroups_table";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	return $num_rows;

}


?>

