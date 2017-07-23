<?php

/***************************************************************************************************
**
**	file:	sstats.php
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


$total_sent = getTotalSent();
$total_received = getTotalReceived();


startTable("$lang_statistics", "center");
	echo "<tr><td class=back>";
		startTable("$lang_surveystats", "center");
			echo "<tr><td width=27% class=back2>";
			//print out the total number of surveys sent out
			echo "$lang_totalsurveyssent:</td><td class=back>";
			echo $total_sent . "</td></tr>";
			//print out the total number of surveys received

			echo "<tr><td width=27% class=back2>";
			echo "$lang_totalsurveysreceived:</td><td class=back>";
			echo $total_received;
			//print out the percentage of surveys received vs sent
			echo "<tr><td width=27% class=back2>";
			echo "$lang_returnpercent:</td><td class=back>";
			if($total_sent == 0)
				echo "0%";
			else
				echo ($total_received / $total_sent ) * 100 . "%";
		endTable();

		startTable("$lang_qsandratings", "center");
			echo "<tr><td class=cat width=80%>$lang_question</td><td class=cat>$lang_avgratings:</td></tr>";
			//echo "<tr><td class=back width=65%>";
			printRatings();
			echo "<tr><td class=subcat colspan=100%>
				<font size=1>* </font></td></tr>";
		endTable();

endTable();



function printRatings()
{
	global $mysql_survey_table, $mysql_tratings_table, $db;
	$qarray = getQids();

	for($i=0; $i<sizeof($qarray); $i++){
		$sql = "select rating from $mysql_survey_table where qid=".$qarray[$i]." group by id";
		$result = $db->query($sql);
		$j=0;
		while($row = $db->fetch_array($result)){
			$ratings[$j] = $row['rating'];
			$j++;
		}

		//now that we have all the ratings for that question in an array, we can compute the average.
		$size = sizeof($ratings);
		if($size != 0){
			$avg = sum_array($ratings) / $size;
		}
		else{
			$avg = 0;
		}

		//now that we have the average, lets print out the question and the score.
		$sql = "select rating from $mysql_tratings_table where id=$qarray[$i]";
		$result = $db->query($sql);
		$row = $db->fetch_array($result);

		echo "<tr><td class=back2 width=80%><b>".$row['rating']."</b></td>";
		echo "<td class=back align=center>" . round($avg, 2) . "</td>";

	}

}

function getTotalSent()
{
	global $mysql_tickets_table, $db;

	$sql = "select count(id) as count from $mysql_tickets_table where survey=1";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

function getTotalReceived()
{
	global $mysql_survey_table, $db;

	$sql = "select count(distinct tid) from $mysql_survey_table";
	$result = $db->query($sql);
	$row = $db->fetch_row($result);

	return $row[0];

}

function getQids()
{
	global $mysql_tratings_table, $db;

	$sql = "select id from $mysql_tratings_table";
	$result = $db->query($sql);
	$i=0;
	while($row = $db->fetch_row($result)){
		$ids[$i] = $row[0];
		$i++;
	}

	return $ids;

}

function sum_array($array)
{
	$total = 0;
	for($i=0; $i<sizeof($array); $i++){
		$total = $total + $array[$i];
	}

	return $total;

}




?>