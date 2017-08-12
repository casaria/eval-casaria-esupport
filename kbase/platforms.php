<?php

/***************************************************************************************************
**
**	file:	platforms.php
**
**		This file contains the necessary functions for editing the categories within the knowledge
**	base.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	01/30/02
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

echo "<form action=control.php?t=kbase&act=plat method=post>";
//if platforms is selected, do the update if set, and then display the settings.

if($m == 'Add New Platform'){
	$sql = "insert into $mysql_platforms_table values(NULL, '$rank', '$platform')";
	$db->query($sql);
	unset($m);
}

if($rm == 'delete'){
	$sql = "DELETE from $mysql_platforms_table where id=$id";
	$db->query($sql);
	unset($rm);
}

if(isset($m)){
	//update the database with the new platform settings
	$num_platforms = getNumPlatforms();
	for($i=0; $i<$num_platforms; $i++){
		$plat = "platform" . $i;
		$ran = "rank" . $i;
		$id = "id" . $i;
		
		$sql = "update $mysql_platforms_table set rank='".$$ran."', platform='".$$plat."' where id='". $$id . "'";
		$db->query($sql);
	}
	unset($m);
}


startTable("$lang_platform $lang_options", "center");
	echo "<tr><td class=cat><br>$lang_platformidentical<br><br></td></tr>";

	echo "<form name=form2 action=control.php?t=kbase&act=pla&m=update method=post>";
	$num_rows = listPlatforms();
	echo "<input type=hidden name=t value=kbase>";
	echo "<input type=hidden name=act value=plat>";
	echo "<tr><td class=back><input type=submit name=m value=\"$lang_update\"></td></tr>";


echo '<tr><td class=back2>
	'.$lang_addplatform.': 
	<input type=text name=platform></input>
	'.$lang_rank.': <input type=text name=rank size=2></input><br>
	<input type=submit name=m value="'.$lang_addplatform.'"></input>
	</form></td></tr>';

endTable();

?>