	<?php

	/**************************************************************************************************
	**	file:	myopen.php
	**
	**		This file grabs the information about all open tickets of the supporter that is logged in
	**	and displays that information.
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

	$highest_pri = getRPriority(getHighestRank($mysql_tpriorities_table));	//set the highest priority rating
	$supporter_id = getUserID($cookie_name);

	$filter = $f; //via HTTP_POST


	startTable("$lang_youropen", "center");
		echo '<tr>
				<td class=back> ';

		//everything in here should be the ticket information
		//start the table, use a while loop to cycle through all the tickets.



		echo '<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
				<tr> 
				<td> 
					<table cellSpacing=1 cellPadding=5 width="100%" border=0>
						<tr>';

							if($s == 'id'){
								echo '<td align=center>
								 <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'"><b>'.$lang_id.'</b></a></td>';
							}
							else{
								echo '<td align=center>
								 <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=id"><b>'.$lang_id.'</b></a></td>';
							}


							echo '<td align=center>
							  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=sup"><b>'.$lang_Supporter.'</b></a></td>';

							//+++
							echo '<td align=center>
							  <b>'.$lang_equipment.'</b></td>';


							if($s == 'ds'){
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=sd"><b>'.$lang_shortdesc.'</b></a></td>';
							}
							else{
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=ds"><b>'.$lang_shortdesc.'</b></a></td>';
							}

							if($s == 'ur'){
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=ru"><b>'.$lang_user.'</b></a></td>';
							}
							else{
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=ur"><b>'.$lang_user.'</b></a></td>';
							}

							if($s == 'gr'){
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=rg"><b>'.$lang_group.'</b></a></td>';
							}
							else{
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=gr"><b>'.$lang_group.'</b></a></td>';
							}
							if($s == 'pr'){
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=rp"><b>'.$lang_priority.'</b></a></td>';
							}
							else{
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=pr"><b>'.$lang_priority.'</b></a></td>';
							}

							if($s == 'cr'){
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=rc"><b>'.$lang_created.'</b></a></td>';
							}
							else{
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=cr"><b>'.$lang_created.'</b></a></td>';
							}


							 echo '<td align=center>
							  <b>'.$lang_last_update.'</b></td>';

							if($s == 'st'){
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=ts"><b>'.$lang_status.'</b></a></td>';
							}
							else{
								echo '<td align=center>
								  <a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=st"><b>'.$lang_status.'</b></a></td>';
							}

							echo '<td align=center>
							  <b>'.$lang_billing.'</b></td>';

							if($s == 'rt'){
								echo '<td align=center>
									<a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=tr"><b>'.$lang_time.'</b></a></td>';
							 }
							 else{
								 echo '<td align=center>
									<a class=hf href="'.$supporter_site_url.'/index.php?t=tmop&f='.$f.'&s=rt"><b>'.$lang_time.'</b></a></td>';
							 }

		$summary = listOpenTickets($supporter_id, $s, $filter);

		endTable();
    echo "$lang_summary: $lang_recordcount $summary[recordcount] $summary[remarks]";
	endTable();



	function listOpenTickets($id, $sort, $filter)
	{
		global $mysql_tickets_table, $cookie_name, $mysql_tstatus_table, $mysql_tpriorities_table, $db;

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
			default:
				$status="='%'";
			break;

		}
		$time = time();
		switch($sort){
			case ("su"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by supporter asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("sd"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by short asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("ds"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by short desc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("ur"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by user asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("ru"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by user desc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("gr"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by ugroupid asc, create_date asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("rg"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by ugroupid desc, create_date asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("pr"):
				$summary = listByPriority($id, "asc");
				break;
			case ("rp"):
				$summary = listByPriority($id, "desc");
				break;
			case ("cr"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by create_date asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("rc"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by create_date desc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("st"):
				$summary = listByStatus($id, "asc");
				break;
			case ("ts"):
				$summary = listByStatus($id, "desc");
				break;
			case ("tr"):
				$sql = "SELECT t.*, ($time - t.lastupdate)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and t.status".$status." and t.supporter_id=$id group by t.id order by a desc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("rt"):
				$sql = "SELECT t.*, ($time - t.lastupdate)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and t.status".$status." and t.supporter_id=$id group by t.id order by a asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			case ("id"):
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by id desc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
			default:
				$sql = "select * from $mysql_tickets_table where status".$status." and supporter_id=$id order by id asc";
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
		}

		//return $summary;
	}

	function listByPriority($id, $order)
	{
		global $mysql_tpriorities_table, $mysql_tickets_table, $mysql_tstatus_table, $db;

		$high_status = getRStatus(getHighestRank($mysql_tstatus_table));
		$sql = "select priority from $mysql_tpriorities_table order by rank $order";
		$result = $db->query($sql);
		while($row = $db->fetch_row($result)){
			$sql2 = "select * from $mysql_tickets_table where priority='$row[0]' and supporter_id=$id and status!='$high_status'";
			$result2 = $db->query($sql2);
			displayTicket($result2);
		}

		//now we have to list all of the tickets that have a priority other than what is in the priority list
		//ie. if a priority gets deleted via the admin tool and there are still tickets with that priority, we
		// still need to list them.

		$sql = "select * from $mysql_tickets_table where ";
		$prios = getPriorityList();
		$flag=0;
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
		return displayTicket($result3);

	}



	function listByStatus($id, $order)
	{
		global $mysql_tstatus_table, $mysql_tickets_table, $db;

		$sql = "select status from $mysql_tstatus_table where status=".$status." order by rank $order";
		$result = $db->query($sql);
		while($row = $db->fetch_row($result)){
			$sql2 = "select * from $mysql_tickets_table where status='$row[0]' and supporter_id=$id";
			$result2 = $db->query($sql2);
			displayTicket($result2);
		}

		//now we have to list all of the tickets that have a priority other than what is in the priority list
		//ie. if a priority gets deleted via the admin tool and there are still tickets with that priority, we
		// still need to list them.

		$sql = "select * from $mysql_tickets_table where ";
		$list = getStatusList();
		$flag =0;
		for($i=0; $i<sizeof($list); $i++){
			if($flag != 1){
				$sql .= "status!='" . $list[$i] . "'";
				$flag = 1;
			}
			else{
				$sql .= " and status!='" . $list[$i] ."'";
				$flag = 1;
			}
		}

		$result3 = $db->query($sql);
		return displayTicket($result3);

	}


	?>
