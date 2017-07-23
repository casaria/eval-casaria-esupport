<?php

/**************************************************************************************************
**	file:	member.php
**
**		This file displays information about a particular supporter for other supporters to view.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	02/28/02
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

$status = getRStatus(getHighestRank($mysql_tstatus_table));

$mem_id = getUserID($mem);
$closed_tickets = getTotalNumClosedTickets($mem_id);
$info = getUserInfo($mem_id);


startTable("$lang_supporterinfo", "center", 100, 2);
	echo "<tr><td class=back2 width=27%> $lang_username: </td><td class=back> ".$info['user_name']."</td></tr>";
	echo "<tr><td class=back2 width=27%> $lang_fullname: </td><td class=back> ". $info['first_name']." ".$info['last_name']."</td></tr>";

	echo "<tr><td class=back2 width=27%> $lang_status: </td><td class=back> ";
		if($info['admin'] == 1)
			echo "$lang_admin";
		elseif($info['supporter'] == 1)
			echo "$lang_Supporter";
		else
			echo "$lang_user";		
	echo "</td></tr>";

	echo "<tr><td class=back2 width=27%> $lang_email:</td><td class=back><a href=\"mailto:".$info['email']."\">".$info['email']."</a></td></tr>";

	echo "<tr><td class=back2 width=27%> $lang_office: </td><td class=back> " . $info['office'] . "</td></tr>";
	echo "<tr><td class=back2 width=27%> $lang_phoneext: </td><td class=back> " . $info['phone'] . "</td></tr>";
	echo "<tr><td class=back2 width=27%> $lang_yahoo: </td><td class=back> " . $info['yahoo'] . "</td></tr>";
	echo "<tr><td class=back2 width=27%> $lang_icq: </td><td class=back> " . $info['icq'] . "</td></tr>";
	echo "<tr><td class=back2 width=27%> $lang_msn: </td><td class=back> " . $info['msn'] . "</td></tr>";

	if($info['lastactive'] == 0)
		echo "<tr><td class=back2 width=27%> $lang_lastactive: </td><td class=back>$lang_never</td></tr>";
	else
		echo "<tr><td class=back2 width=27%> $lang_lastactive: </td><td class=back> " . date("F d Y, h:i a", $info['lastactive']) . "</td></tr>";

	echo "<tr><td class=back2 width=27%> $lang_totalclosedtickets: </td><td class=back> " . $closed_tickets . "</td></tr>";

endTable();









?>
