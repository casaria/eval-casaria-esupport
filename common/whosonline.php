<?php

/***************************************************************************************************
**
**	file:	whosonline.php
**
**		Keeps track of who is online and displays that info at the bottom of each page.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	12/07/2001
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

//this is the delay time before the db is updated when a user is no longer online.
$timeoutseconds=60;
$timestamp = time(); 
$timeout = $timestamp-$timeoutseconds; 

//set the user variable so we can access it for database queries
if($cookie_name == '' || !isset($cookie_name))
	$user = 'guest123';
else
	$user = $cookie_name;

$db->query("INSERT IGNORE INTO $mysql_whosonline_table VALUES ('$timestamp', '$user', '$REMOTE_ADDR','$PHP_SELF')"); 
$db->query("DELETE FROM $mysql_whosonline_table WHERE timestamp<$timeout"); 
$result = $db->query("SELECT DISTINCT ip, user FROM $mysql_whosonline_table order by user"); 

$i=0;
while($row = $db->fetch_array($result)){
	$users[$i] = $row[user];			//create array with user names in it.
	$i++;
}

//get the count of the number of guests online.
$guest_count = 0;
$k = 0;
for($j=0; $j<sizeof($users); $j++){
	if($users[$j] == 'guest123'){		//if the user is guest123, add one to the guest count
		$guest_count++;
	}
	else{
		$online[$k] = $users[$j];		//create the array of user names of those online
		$k++;
	}
}

//now $array has a list of all the users that aren't guests.
if(sizeof($online) != 1){
	echo $lang_thereare . sizeof($online) . $lang_usersand;
}
else{
	echo $lang_thereis . sizeof($online) . $lang_userand;
}

if($guest_count != 1)
	echo $guest_count . $lang_guests;
else
	echo $guest_count . $lang_guest;

echo "$lang_whosonline: ";
$j = 0;
//now cycle through and print out the names of the people online.
for($i=0; $i<sizeof($online); $i++){
	if($j == 0){
		if(isSupporter($online[$i])){
			echo "<b>$online[$i]</b>";
		}
		else{
			echo "$online[$i]";
		}
		$j++;
	}
	else{
		if(isSupporter($array[$i])){
			echo ", <b>$online[$i]</b>";
		}
		else{
			echo ", $online[$i]";
		}
	}
}










?>