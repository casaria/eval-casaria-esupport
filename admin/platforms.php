<?php

/**************************************************************************************************
**	file:	platforms.php
**
**		This file allows the admin to create new, and edit existing platform choices.
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

	$sql = "insert into $mysql_platforms_table values(NULL, '$rank', '$platform')";
	$db->query($sql);
	unset($submit);
}

if($rm == 'delete'){
	$sql = "delete from $mysql_platforms_table where id=$id";
	$db->query($sql);
	unset($rm);
}

if(isset($m)){

$num_platforms = getNumPlatforms();
for($i=0; $i<$num_platforms; $i++){
	$plat = "platform" . $i;
	$ran = "rank" . $i;
	$id = "id" . $i;
	
	$sql = "update $mysql_platforms_table set rank='".$$ran."', platform='".$$plat."' where id='". $$id . "'";
	$db->query($sql);
	unset($m);
}


}


echo "<form action=control.php?t=topts&act=tpla method=post>";
startTable("$lang_platforms", "center", "100%", 1);
	echo '<tr><td class=cat><br>
			'.$lang_platformexp.'<br><br>
		  </td></tr>';
	echo "<form name=form2 action=\"control.php?t=topts&m=update&act=tpla\" method=get>";

	$num_rows = listPlatforms();

	echo "<tr><td class=back><input type=submit name=m value=\"$lang_update\"></td></tr>";
	echo '</tr></td>
		 <tr><td class=back2>
		'.$lang_addplatform.': 
		<input type=text name=platform></input>
		'.$lang_rank.': <input type=text name=rank size=2></input><br>
		<input type=submit name=submit value="'.$lang_addplatform.'"></input>';

endTable();




?>
