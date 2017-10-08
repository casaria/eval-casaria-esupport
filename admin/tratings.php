<?php

/**************************************************************************************************
**	file:	tratings.php
**
**		This file allows the admin to add, delete, and modify ticket ratings.
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

if(isset($submit)){

	$sql = "insert into $mysql_tratings_table values(NULL, '$rank', '$ratings')";
	$db->query($sql, $mysql_tratings_table);

}
if($t == 'delete'){
	$sql = "delete from $mysql_tratings_table where id=$id";
	$db->query($sql, $mysql_tratings_table);
	
	$header = "$admin_site_url/index.php?t=trat";
	header("Location: $header");

}

if($m == 'update'){
$num_ratings = getNumRatings();
for($i=0; $i<$num_ratings; $i++){
	$ratings = "ratings" . $i;
	$ran = "rank" . $i;
	$id = "id" . $i;
	
	$sql = "update $mysql_tratings_table set rank='".$$ran."', rating='".$$ratings."' where id='". $$id . "'";
	$db->query($sql, $mysql_tratings_table);
}


}


echo '
	<form action=index.php?t=trat method=post>

	<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
				<TR> 
				<TD class=info align=middle><B>'.$lang_ticketratings.'</B></TD>
				</TR>
				
				<tr><td class=cat><br>
					'.$lang_ratingsexp.'<br><br>
				</td></tr>


';

				$num_rows = listtRatings();

echo '				</tr></td>

			 <tr><td class=back2>
				'.$lang_addsurvey.': 
				<input type=text size=40 name=ratings></input>
				'.$lang_rank.': <input type=text name=rank size=2></input><br>
				<input type=submit name=submit value="'.$lang_addsurvey.'"></input>

			</td></tr>
		</table>



			</td>
			</tr>
		</table>
';


function listtRatings()
{

	global $mysql_tratings_table, $db, $lang_rank, $lang_delete, $lang_update;

	$sql = "select * from $mysql_tratings_table order by rank asc";
	$result = $db->query($sql, $mysql_tratings_table);
	$num_rows = $db->num_rows($result);

	if($num_rows != 0){

		echo "<form name=form2 action=index.php?t=trat&m=update method=post>";

		$i = 0;

		while($row = $db->fetch_row($result)){

			echo "<input type=hidden name=id$i value='$row[0]'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=ratings$i value=\"$row[2]\" size=70%>";
			echo "&nbsp;&nbsp; $lang_rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo "&nbsp;&nbsp;<a href=tratings.php?t=delete&id=$row[0]>$lang_delete</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
		echo "<tr><td class=back><input type=submit name=m value=\"$lang_update\"></td></tr>";
		

	}

	return $num_rows;

}

function getNumRatings()
{
	global $mysql_tratings_table, $db;

	$sql = "select count(rating) from $mysql_tratings_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];

}



?>
