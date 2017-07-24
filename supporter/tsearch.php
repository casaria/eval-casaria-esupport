<?php

/***************************************************************************************************
**	file:	tsearch.php
**
**		This file takes care of the ticket search front end.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	
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
require_once "../common/common.php";tickets
$highest_pri = getRPriority(getHighestRank($mysql_tpriorities_table));	//set the highest priority rating
$today = getdate();


if(isset($search) || isset($s)) {
	$time = time();
	//lets get the information ready to be passed to the displayTicket table.

	if ($stmt == '') {
		$sql = "select * from $mysql_tickets_table where (";

		if ($s == 'tim' || $s == 'mit') {
			$sql = "select t.*, ($time - t.lastupdate)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and (";
		}
	} else {
		$query = stripslashes($stmt);

		if ($s == 'tim' || $s == 'mit') {
			$query = preg_replace("/select \* from $mysql_tickets_table where \(/i", "select t.*, ($time - t.lastupdate)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and (", $query);
		} else {
			$query = preg_replace("/select t.*, \([0-9]* - t.lastupdate\)/p.response_time as a from $mysql_tickets_table t, $mysql_tpriorities_table p where p.priority=t.priority and \(/i", "select * from $mysql_tickets_table where (", $query);
		}

	}


	//if $sql is set, do not do all of the following checking.  Pass the $sql variable to the displayTicket
	//function right away.
	if (!isset($query) || $query == '') {

		if (isset($supp_group) && $supp_group != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " groupid=$supp_group";
				$flag = 1;
			} else {
				$sql .= " $andor groupid=$supp_group";
				$flag = 1;
			}
		}

		if (isset($user_group) && $user_group != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " ugroupid=$user_group";
				$flag = 1;
			} else {
				$sql .= " $andor ugroupid=$user_group";
				$flag = 1;
			}
		}

		if (isset($supporter) && $supporter != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " supporter='$supporter'";
				$flag = 1;
			} else {
				$sql .= " $andor supporter='$supporter'";
				$flag = 1;
			}
		}

		if (isset($priority) && $priority != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " priority='$priority'";
				$flag = 1;
			} else {
				$sql .= " $andor priority='$priority'";
				$flag = 1;
			}

			$pset = 1;
		}

		if (isset($status) && $status != '' && $status != 'notclosed') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " status='$status'";
				$flag = 1;
			} else {
				$sql .= " $andor status='$status'";
				$flag = 1;
			}
			$sset = 1;
		}

		if (isset($status) && $status != '' && $status == 'notclosed') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " status!='" . getRStatus(getHighestRank($mysql_tstatus_table)) . "'";
				$flag = 1;
			} else {
				$sql .= " $andor status!='CLOSED'";
				$flag = 1;
			}

			$sset = 1;
		}

		if (isset($user) && $user != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " user='$user'";
				$flag = 1;
			} else {
				$sql .= " $andor user='$user'";
				$flag = 1;
			}
		}

		if (isset($office) && $office != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " office='$office'";
				$flag = 1;
			} else {
				$sql .= " $andor office='$office'";
				$flag = 1;
			}
		}

		if (isset($category) && $category != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " category='$category'";
				$flag = 1;
			} else {
				$sql .= " $andor category='$category'";
				$flag = 1;
			}
		}


		if (isset($platform) && $platform != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " platform='$platform'";
				$flag = 1;
			} else {
				$sql .= " $andor platform='$platform'";
				$flag = 1;
			}
		}

		//lets create the timestamp information first.

		if (isset($syear) && isset($smonth) && isset($sday)) {
			$stimestamp = mktime(0, 0, 0, $smonth, $sday, $syear);
			$etimestamp = mktime(23, 59, 59, $emonth, $eday, $eyear);

			if ($flag != 1 || !isset($flag)) {
				$sql .= " (create_date > $stimestamp and create_date < $etimestamp)";
				$flag = 1;
			} else {
				$sql .= " $andor (create_date > $stimestamp and create_date < $etimestamp)";
				$flag = 1;
			}
		}

		if (isset($keywords) && $keywords != '') {
			if ($flag != 1 || !isset($flag)) {
				$sql .= " (short regexp '$keywords' or description regexp '$keywords')";
				$flag = 1;
			} else {
				$sql .= " $andor (short regexp '$keywords' or description regexp '$keywords')";
				$flag = 1;
			}
		}

	} else {
		$sql = stripslashes($query);
	}

	if (!isset($query) || $query == '') {
		$sql .= ")";
	}

	if (isset($input) && $input != '') {
		$sql = "select * from $mysql_tickets_table where " . $input;
	}

	switch ($s) {
		case ("id"):
			$sql .= " order by id asc";
			break;
		case ("di"):
			$sql .= " order by id desc";
			break;
		case ("sup"):
			$sql .= " order by supporter asc";
			break;
		case ("pus"):
			$sql .= " order by supporter desc";
			break;
		case ("equ"):
			$sql .= " order by equipment asc";
			break;
		case ("uqe"):
			$sql .= " order by equipment desc";
			break;

		case ("sho"):
			$sql .= " order by short asc";
			break;
		case ("ohs"):
			$sql .= " order by short desc";
			break;
		case ("usr"):
			$sql .= " order by user asc";
			break;
		case ("rsu"):
			$sql .= " order by user desc";
			break;
		case ("grp"):
			$sql .= " order by ugroupid asc, create_date";
			break;
		case ("prg"):
			$sql .= " order by ugroupid desc, create_date";
			break;


		case ("tim"):
			$sql .= " order by a asc";
			break;
		case ("mit"):
			$sql .= " order by a desc";
			break;
		case ("pri"):
			if (preg_match("/priority/i", $sql)) {
				$sql .= " order by priority asc";
			} else {

				//set the different sql statments based on the number of different priorities
				$num_prios = getNumberPriorities();
				$prios = sqlByPriority($sql, "asc");

			}
			break;

		case ("irp"):
			if (preg_match("/priority/i", $sql)) {
				$sql .= " order by priority desc";
			} else {

				//set the different sql statments based on the number of different priorities
				$num_prios = getNumberPriorities();
				$prios = sqlByPriority($sql, "desc");

			}
			break;

		case ("sta"):
			if (preg_match("/status/i", $sql)) {
				$sql .= " order by status asc";
			} else {
				$num_status = getNumberStatus();
				$status = sqlByStatus($sql, "asc");
			}
			break;

		case ("ats"):
			if (preg_match("/status/i", $sql)) {
				$sql .= " order by status desc";
			} else {
				$num_status = getNumberStatus();
				$status = sqlByStatus($sql, "desc");
			}
			break;

		case ("cre"):
			$sql .= " order by create_date asc";
			break;
		case ("erc"):
			$sql .= " order by create_date desc";
			break;
		case ("tim"):

		default:
			break;
	}

	//set up the sql statement for inclusion in the link for sorting and execute the current
	//sql statement for displaying the proper tickets.

	$sql2 = preg_replace("/ order(.*)/i", "", $sql);
	$sql2 = preg_replace("/ /i", "%20", $sql2);

	if ($sql == "select * from $mysql_tickets_table where ()") {
		printerror("$lang_searchcriteria");
	} else {
		createHeader("$lang_searchresults");

		echo '<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>';
		echo ' <tr> ';

		if ($s == 'id') {
			echo '<td align=center>
						<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=di&stmt=' . htmlentities($sql2) . '><b>' . $lang_id . '</b></a></td>';
		} else {
			echo '<td align=center>
						<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=id&stmt=' . htmlentities($sql2) . '><b>' . $lang_id . '</b></a></td>';
		}

		if ($s == 'sup') {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=pus&stmt=' . htmlentities($sql2) . '><b>' . $lang_Supporter . ' </b></a></td>';
		} else {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=sup&stmt=' . htmlentities($sql2) . '><b>' . $lang_Supporter . '</b></a></td>';
		}

		if ($s == 'equ') {
			echo '<td align=center>
						  <a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=uqe&stmt=' . htmlentities($sql2) . '><b>' . $lang_equipment . ' </b></a></td>';
		} else {
			echo '<td align=center>
						  <a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=equ&stmt=' . htmlentities($sql2) . '><b>' . $lang_equipment . ' </b></a></td>';
		}


		if ($s == 'sho') {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=ohs&stmt=' . htmlentities($sql2) . '><b>' . $lang_shortdesc . ' </b></a></td>';
		} else {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=sho&stmt=' . htmlentities($sql2) . '><b>' . $lang_shortdesc . '</b></a></td>';
		}


		if ($s == 'usr') {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=rsu&stmt=' . htmlentities($sql2) . '><b>' . $lang_user . ' </b></a></td>';
		} else {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=usr&stmt=' . htmlentities($sql2) . '><b>' . $lang_user . '</b></a></td>';
		}

		if ($s == 'grp') {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=prg&stmt=' . htmlentities($sql2) . '><b>' . $lang_group . ' </b></a></td>';
		} else {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=grp&stmt=' . htmlentities($sql2) . '><b>' . $lang_group . '</b></a></td>';
		}

		if ($s == 'pri' && $pset != 1) {
			echo '<td align=center>
						<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=irp&stmt=' . htmlentities($sql2) . '><b>' . $lang_priority . '</b></a></td>';
		} elseif ($pset != 1) {
			echo '<td align=center>
						<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=pri&stmt=' . htmlentities($sql2) . '><b>' . $lang_priority . '</b></a></td>';
		} else {
			echo '<td align=center>
						<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&stmt=' . htmlentities($sql2) . '><b>' . $lang_priority . '</b></a></td>';
		}

		if ($s == 'cre') {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=erc&stmt=' . htmlentities($sql2) . '><b>' . $lang_created . '</b></a></td>';
		} else {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=cre&stmt=' . htmlentities($sql2) . '><b>' . $lang_created . '</b></a></td>';
		}


		if ($s == 'sta' && $sset != 1) {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=ats&stmt=' . htmlentities($sql2) . '><b>' . $lang_status . '</b></a></td>';
		} elseif ($sset != 1) {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=sta&stmt=' . htmlentities($sql2) . '><b>' . $lang_status . '</b></a></td>';
		} else {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&stmt=' . htmlentities($sql2) . '><b>' . $lang_status . '</b></a></td>';
		}

		if ($s == 'tim') {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=mit&stmt=' . htmlentities($sql2) . '><b>' . $lang_time . '</b></a></td>';
		} else {
			echo '<td align=center>
							<a class=hf href=index.php?t=tsrc&pset=' . $pset . '&sset=' . $sset . '&s=tim&stmt=' . htmlentities($sql2) . '><b>' . $lang_time . '</b></a></td>';
		}

		echo '</tr>';

		switch ($s) {
			case ("pri"):
				for ($i = 0; $i < $num_prios; $i++) {
					$statement = $sql . " and priority='" . $prios[$i] . "'";
					$result = $db->query($statement);
					$summary = displayTicket($result);
				}
				break;
			case ("irp"):
				for ($i = 0; $i < $num_prios; $i++) {
					$statement = $sql . " and priority='" . $prios[$i] . "'";
					$result = $db->query($statement);
					$summary = displayTicket($result);
				}
				break;
			case ("sta"):
				for ($i = 0; $i < $num_status; $i++) {
					$statement = $sql . " and status='" . $status[$i] . "'";
					$result = $db->query($statement);
					$summary =  displayTicket($result);
				}
				break;
			case ("ats"):
				for ($i = 0; $i < $num_status; $i++) {
					$statement = $sql . " and status='" . $status[$i] . "'";
					$result = $db->query($statement);
					$summary = displayTicket($result);
				}
				break;
			default:
				$result = $db->query($sql);
				$summary = displayTicket($result);
				break;
		}

		endTable();

		echo "$lang_summary: $lang_recordcount $summary[recordcount] $summary[remarks]  <BR> $summary[recordcount]<BR>";


		endTable();
	}
} else {

	echo "<form method=post>";
	createHeader("$lang_ticketsearch");

	echo '
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
				<TR>
				<TD>
					<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_searchtype.': </td>
						<td class=back>
							<select name=andor><option value=and selected>'.$lang_and.'</option><option value=or>'.$lang_or.'</option></select>
						</td>
						</tr>
						
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_supportergroup.' </td>
						<td class=back><select name=supp_group>';
						createGroupMenu(2);
	echo '
						</select>
						</td>
						</tr>
						
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_usergroups.': </td>
						<td class=back><select name=user_group>';
						createUserGroupMenu(2);
	echo '
						</select>
						</td>
						</tr>
						
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_Supporter.': </td>
						<td class=back>
							<input type=text name=supporter>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_ticket.' '.$lang_priority.': </td>
						<td class=back><select name=priority>';
							createPriorityMenu(2);
	echo '
						</select>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_ticket.' '.$lang_status.': </td>
						<td class=back><select name=status>';
							createStatusMenu(1);
    echo '
						</select>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_ticket.' '.$lang_billingStatus.': </td>
						<td class=back><select name=billingstatus>';
   							 createBillingStatusMenu(1);
	echo '				
						<option value=notclosed>'.$lang_notclosed.'</option>
						</select>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_ticket.' '.$lang_category.': </td>
						<td class=back><select name=category>';
							createCategoryMenu(1);
	echo '				</select>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_platform.': </td>
						<td class=back><select name=platform>';
							createPlatformMenu(1);
	echo '				</select>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_username.': </td>
						<td class=back>
							<input type=text name=user>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_office.': </td>
						<td class=back>
							<input type=text name=office>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_betweendates.': </td>
						<td class=back>
						<select name=smonth>';
						for($i=1; $i<13; $i++){
							echo "<option value=$i";
								if(($today['mon']-2) == $i)
									echo ' selected';
							echo ">".$lang_month[$i]."</option>";
						}

echo '					</select>
						<select name=sday>
						<option></option>';
						for($i=1; $i<32; $i++){
							echo "<option value=$i";
							if($i == $today['mday'])
								echo ' selected';
							echo ">".$i."</option>\n";
						}
echo'					</select>
						<select name=syear>
						<option></option>';
						for($i=2001; $i<= $today['year']; $i++){
								echo "<option value=$i";
								if($today['year'] == $i)
									echo ' selected';
								echo ">".$i."</option>\n";
						}
						
echo '					</select>	
						<select name=emonth>';
						for($i=1; $i<13; $i++){
							echo "<option value=$i";
								if($today['mon'] == $i)
									echo ' selected';
							echo ">".$lang_month[$i]."</option>";
						}
echo '					</select>
							<select name=eday> <option></option>';
						for($i=1; $i<32; $i++){
							echo "<option value=$i";
								if($i == $today['mday'])
									echo ' selected';
							echo ">".$i."</option>\n";
						}
echo '
						</select>
						<select name=eyear>
						<option></option>';
						for($i=2001; $i<= $today['year']; $i++){
								echo "<option value=$i";
								if($today['year'] == $i)
									echo ' selected';
								echo ">".$i."</option>\n";
							}
echo'				
						</select>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_keywords.': </td>
						<td class=back>
							<input type=text size=52% name=keywords>
						</td>
						</tr>
						<TR>
						<TD class=back2 align=right width=27%>'.$lang_sqlstmt.': </td>
						<td class=back>'.$lang_sqlstmt2 . $mysql_tickets_table . $lang_sqlstmt3 . '
							<input type=text name=input size=47%>
						</td>
						</tr>

						
						</tr>		
					</table>
				</td>
				</tr>
			</table><br>
			
			<input type=submit value=\''.$lang_searchforticket.'\' name=search>
			<input type=hidden value='.$query.' name=query>
			
			</form>';

}


//returns an array containing the priority names
function sqlByPriority($query, $order)
{
	global $mysql_tpriorities_table, $mysql_tickets_table, $db;

	$sql = "select priority from $mysql_tpriorities_table order by rank $order";
	$result = $db->query($sql);

	$i = 0;
	while($row = $db->fetch_row($result)){
		$array[$i] = $row[0];
		$i++;
	}

	return $array;

}

function getNumberPriorities()
{
	global $mysql_tpriorities_table, $db;

	$sql = "select id from $mysql_tpriorities_table";
	$result = $db->query($sql);
	$total = $db->num_rows($result);

	return $total;

}

//returns an array containing the status names
function sqlByStatus($query, $order)
{
	global $mysql_tstatus_table, $mysql_tickets_table, $db;

	$sql = "select status from $mysql_tstatus_table order by rank $order";
	$result = $db->query($sql);

	$i = 0;
	while($row = $db->fetch_row($result)){
		$array[$i] = $row[0];
		$i++;
	}

	return $array;

}

function getNumberStatus()
{
	global $mysql_tstatus_table, $db;

	$sql = "select id from $mysql_tstatus_table";
	$result = $db->query($sql);
	$total = $db->num_rows($result);

	return $total;

}


?>
