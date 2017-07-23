<?php

/***************************************************************************************************
**
**	file:	categories.php
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

echo "<form action=control.php?t=kbase&act=cate method=post>";


if($m == $lang_addcat){
	$sql = "INSERT into $mysql_kcategories_table VALUES(NULL, '$category')";
	$db->query($sql);
}

if($rm == 'delete'){
	$sql = "DELETE from $mysql_kcategories_table where id=$id";
	$db->query($sql);
	unset($rm);
}

if(isset($m)){
	//update the database with the new platform settings
	$num_categories = getNumKCategories();
	for($i=0; $i<$num_categories; $i++){
		$cat = "category" . $i;
		$id = "id" . $i;
		
		$sql = "update $mysql_kcategories_table set category='".$$cat."' where id='". $$id . "'";
		$db->query($sql);
	}
}


startTable("$lang_category $lang_options", "center");
	echo "<tr><td class=cat><br>$lang_categoriesmsg<br><br></td></tr>";

	echo "<form name=form2 action=control.php?t=kbase&act=cate&m=update method=get>";
	$num_rows = listKCategories();
	echo "<input type=hidden name=t value=kbase>";
	echo "<input type=hidden name=act value=cate>";
	echo "<tr><td class=back><input type=submit name=m value=\"$lang_update\"></td></tr>";

	echo '
		<tr><td class=back2>'.
		$lang_addcat.':
		<input type=text name=category></input>';
		echo '<br><input type=submit name=m value="'.$lang_addcat.'"></input>
		
		</form></td></tr>';

endTable();

?>
