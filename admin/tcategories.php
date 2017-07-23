<?php

/**************************************************************************************************
**	file:	tcategories.php
**
**		This file allows the admin to add, delete, and modify ticket categories.
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

	$sql = "insert into $mysql_tcategories_table values(NULL, '$rank', '$category')";
	$db->query($sql);

}
if($rm == 'delete'){
	$sql = "delete from $mysql_tcategories_table where id=$id";
	$db->query($sql);
	unset($rm);
}

if(isset($m)){
$num_cats = getNumCategories();
for($i=0; $i<$num_cats; $i++){
	$cat = "category" . $i;
	$ran = "rank" . $i;
	$id = "id" . $i;
	
	$sql = "update $mysql_tcategories_table set rank='".$$ran."', category='".$$cat."' where id='". $$id . "'";
	$db->query($sql);
}


}


echo "<form action=control.php?t=topts&act=tcat method=post>";
startTable("$lang_ticket $lang_categories", "center", "100%", 1);
				
echo '<tr><td class=cat><br>
		'.$lang_categoriesexp.'
		<br><br>
	  </td></tr>';

$num_rows = listtCategories();

echo '</tr></td>
		 <tr><td class=back2>
			'.$lang_addcategory.': 
			<input type=text name=category></input>
			'.$lang_rank.': <input type=text name=rank size=2></input><br>
			<input type=submit name=submit value="'.$lang_addcategory.'"></input>';

endTable();



function listtCategories()
{

	global $mysql_tcategories_table, $db, $lang_delete, $lang_rank, $lang_update;

	$sql = "select * from $mysql_tcategories_table order by rank, category asc";
	$result = $db->query($sql);
	$num_rows = $db->num_rows($result);

	if($num_rows != 0){

		echo "<form name=form2 action=control.php?t=topts&m=update&act=tcat method=get>";

		$i = 0;

		while($row = $db->fetch_row($result)){

			echo "<input type=hidden name=id$i value='$row[0]'></input>";
			echo "<tr><td class=back>";
			echo "<input type=text name=category$i value=\"$row[2]\">";
			echo "&nbsp;&nbsp; $lang_rank: <input type=text size=2 value='$row[1]' name=rank".$i.">";
			echo "&nbsp;&nbsp;<a href=control.php?t=topts&act=tcat&rm=delete&id=$row[0]>$lang_delete</a>?";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
		echo "<tr><td class=back><input type=submit name=m value=\"$lang_update\"></td></tr>";
		

	}

	return $num_rows;

}

function getNumCategories()
{
	global $mysql_tcategories_table, $db;

	$sql = "select count(category) from $mysql_tcategories_table";
	$result = $db->query($sql);
	$total = $db->fetch_row($result);

	return $total[0];

}



?>
