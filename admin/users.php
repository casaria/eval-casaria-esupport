<?php

/**************************************************************************************************
**	file:	users.php
**
**	This is the front end for most of the user options.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	10/02/01
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
	echo "<table width=100% border=0><tr valign=top><td width=23%>";
		startTable("$lang_ticketoptions", "left");
		//create the left hand menu
			echo "<tr><td class=back2><LI> <a href=\"control.php?t=users&act=add\">$lang_adduser</a>";
			echo "<LI> <a href=\"control.php?t=users&act=ugrp\">$lang_usergroups</a>";
			echo "<LI> <a href=\"control.php?t=users&act=sgrp\">$lang_supportergroups</a>";
			echo "<LI> <a href=\"control.php?t=users&act=srch\">$lang_usersearch</a>";
			echo "</td></tr>";
		endTable();
	echo "</td><td class=back>";



	switch($act){

		case("add"):
			require "addsupporter.php";
			break;

		case("ugrp"):
			require "ugroups.php";
			break;

		case ("uopt"):
			require "ugoptions.php";
			break;

		case ("sopt"):
			require "sgoptions.php";
			break;
		
		case ("sgrp"):
			require "sgroups.php";
			break;

		case ("uedit"):
			require "uedit.php";
			break;

		default:
		case ("srch"):
			require "usersearch.php";
			break;
		
	}
		echo "</TABLE></TD></TR>";	



?>