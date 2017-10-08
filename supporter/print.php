<?php

/***************************************************************************************************
**
**	file:	print.php
**
**		Description:  This file creates the printable version of the ticket provided via the id.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	03/11/02
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

require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";
require_once "../common/login.php";
$language = getLanguage($cookie_name);

if($language == '')
	require_once "../lang/$default_language.lang.php";
else
	require_once "../lang/$language.lang.php";

$sql = "SELECT template from $mysql_templates_table where name='supporter_ticket_printable'";
$result = $db->query($sql);
$template = $db->fetch_array($result);

$sql = "SELECT * from $mysql_tickets_table where id=$id";
$result = $db->query($sql);
$ticket = $db->fetch_array($result);
$log = explode($delimiter, stripslashes($ticket['update_log']));
for($i=0; $i<sizeof($log)-1; $i++){
	$update_log .= "&nbsp;&nbsp;&nbsp;&nbsp;";
		if(eregi("^[0-9]{1,11}", $log[$i])){		//if it contains just the timestamp, edit it.
			$date = substr(eregi_replace("lang(.*)", "", $log[$i]), 0, -2);
			$date = date("F j, Y, g:i a", $date);
			$date = eregi_replace("^[0-9]*", "$date ", $log[$i]);
			eval("\$log[$i] = \"$log[$i]\";");
		}
	if($i%2 == 0){
		$update_log .= $date . "<br>";
	}
	else
		$update_log .= "&nbsp;&nbsp;&nbsp;&nbsp;" . $log[$i] . "<br><br>";
}

$ticket[description] = nl2br($ticket[description]);
$update_log = nl2br(stripslashes($update_log));
eval("\$update_log = \"$update_log\";");

$create_date = date("F d Y,		 h:i a", $ticket[create_date]);

$template=str_replace("\\'","'",$template[0]);
eval("\$template = \"$template\";");
echo $template;


?>
