<?php

/***********************************************************************************************************
**
**	file:	footer.php
**
**	This file contains all footer information.
**
************************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/24/01
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


echo "<br><center><font size=0 color=$theme[text] face=\"$theme[font]\">$helpdesk_name<br>";
echo "$lang_powered Peter & <b><a href=\"https://odoo.casaria.net\">TheTeam</a></b> v$version<br>";
echo "<a href=\"https://icons8.com/icon/44052/Shopping-Cart-Loaded\"> some icon credits</a><br>";
if($enable_stats == 'On'){
    $mtime2 = explode(" ", microtime());
    $endtime = $mtime2[0] + $mtime2[1];
	$totaltime = $endtime - $starttime;
	$totaltime = number_format($totaltime, 7);
	
	echo "$lang_processed: $totaltime $lang_seconds, $db->queries $lang_queries<br>";
}
echo "</font> </center>";
?>