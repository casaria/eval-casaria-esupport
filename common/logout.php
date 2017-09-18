<?php

/***************************************************************************************************
**	file: logout.php
**
**	This file logs out the currently logged in user by destroying all of the session variables and
**	then deleting the session.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	08/10/01
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

require_once "config.php";
require_once "$database.class.php";
require_once "common.php";

startSession();
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}
session_destroy();
if($ssl == 'On'){
	$referer = eregi_replace("http", "https", $HTTP_REFERER);
}
else{
	//$referer = $HTTP_REFERER;
	//back to base
	$referer = $site_url . "/index.php";
}

header("Location: $referer");
?>
