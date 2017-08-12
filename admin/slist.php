<?php
/*****************************************************************************************
**	file:	slist.php
**
**	This file lists the users/supporters/admins from the database and provides the links
**	to edit their information.
**
******************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/25/01
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


	echo '<script language="JavaScript">
			<!--
			function MM_jumpMenu(targ,selObj,restore){ //v3.0
			  eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
			  if (restore) selObj.selectedIndex=0;
			}
			//-->
			</script>';


if($m == 'delete2'){
	
	deleteFromGroups($id);

	$sql = "delete from $mysql_users_table where id=$id";
	if(!$result = $db->query($sql, $mysql_users_table)){
		echo "$lang_userdelerror<br>";
	}


}

if($m == 'delete'){

	echo "<form method=post>";
	echo createHeader("$lang_confirm");
	createHeader("<font color=red size=4>$lang_areyousure</font>");
	echo "<input type=hidden name=m value=delete2>";
	echo "<input type=hidden name=id value=$id>";
	echo "<br><br><center><input type=submit name=delete2 value=\"$lang_delete\"></center>";	
}
else{
	echo '
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
			<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
				<TR> 
				<TD class=info colspan=2 align=right><B>
				<form action=self>Sort By:
				<select name=o onChange="MM_jumpMenu(\'parent\',this,0)">
				<option value="index.php?t=slist&o=id"'; if($o=='id') echo ' selected'; echo '>'.$lang_id.'</option>
				<option value="index.php?t=slist&o=user_name"'; if($o=='user_name') echo ' selected'; echo '>'.$lang_username.'</option>
				<option value="index.php?t=slist&o=office"'; if($o=='office') echo ' selected'; echo '>'.$lang_office.'</option>
				</select></b></td></form>
				</TR>		
			</td></tr>
			</td></tr>
			</td></tr>
		</table>
			</td>
			</tr>
		</table><br>';

		getUserList($o, $offset, "supporters");

		echo "<center>";

		$offset = $offset - $users_limit;

		if($offset < 0){
			echo "&nbsp;$lang_previous";
		}
		else{
			echo "&nbsp;<a href=index.php?t=slist&o=$o&offset=$offset>$lang_previous</a>";
		}

		echo "&nbsp; | &nbsp;";
		$offset = $offset + $users_limit +$users_limit;
	


		if($offset < getTotalSupporters() - 1){
			echo "&nbsp;<a href=index.php?t=slist&o=$o&offset=$offset>$lang_next</a>";
		}
		else{
			echo "&nbsp;$lang_next";
		}
}	
		


?>
