<?php
/*****************************************************************************************
**	file:	ulist.php
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



if($m =='delete2'){
	
	deleteFromGroups($id);

	$sql = "delete from $mysql_users_table where id=$id";
	if(!$result = $db->query($sql, $mysql_users_table)){
		echo "There was an error deleting that user.<br>";
	}

}

if($m == 'delete'){

	echo "<form method=post>";
	echo createHeader("Confirmation");
	createHeader("<font color=red size=4>Are you sure?</font>");
	echo "<input type=hidden name=m value=delete2>";
	echo "<input type=hidden name=id value=$id>";
	echo "<br><br><center><input type=submit name=delete2 value=Delete></center>";	
}
else{
	echo '
		<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR> 
					<TD class=info colspan=2 align=right><form method=post><B>
					Sort By:
					<select name=o onChange="MM_jumpMenu(\'parent\',this,0)">
					<option value="index.php?t=ulist&o=id"'; if($o=='id') echo ' selected'; echo '>ID</option>
					<option value="index.php?t=ulist&o=user_name"'; if($o=='user_name') echo ' selected'; echo '>User Name</option>
					<option value="index.php?t=ulist&o=office"'; if($o=='office') echo ' selected'; echo '>Office</option>
					</select></b></td></form>




					</TR>		
				</table>
			</td>
			</tr>
		</table><br>';

		getUserList($o, $offset, "users");
		echo "<center>";

		$offset = $offset - $users_limit;

		if($offset < 0){
			echo "&nbsp;Previous";
		}
		else{
			echo "&nbsp;<a href=index.php?t=ulist&o=$o&offset=$offset>Previous</a>";
		}

		echo "&nbsp; | &nbsp;";
		$offset = $offset + $users_limit +$users_limit;
	


		if($offset < getTotalUsers()){
			echo "&nbsp;<a href=index.php?t=ulist&o=$o&offset=$offset>Next</a>";
		}
		else{
			echo "&nbsp;Next";
		}
		
}
		


?>