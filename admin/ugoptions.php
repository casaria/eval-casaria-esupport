<?php

/**************************************************************************************************
**	file:	ugoptions.php
**
**		This file allows the admin to add users to user groups and delete users from user groups.
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


if(isset($add)){
	if(userExists($groupy)){
		$id = getUserID($groupy);
		$sql = "insert into $table values(NULL, $id, '$groupy')";

		$db->query($sql);
	}
	else{
		echo "$lang_usernotexist";
		exit;
	}

}


if($rm == 'delete'){
	$sql = "delete from $table where id=$gid";
	$db->query($sql);
	unset($rm);
}



echo '<form action=control.php?t=users&act=uopt&g=$g method=post>';

startTable("$lang_usergroups", "center", "100%", 1);

echo '<tr><td class=cat><br>'.$lang_usersin.' <b>';
echo getuGroup($g);
echo ' </b>'.$lang_group2.'.<br><br> 
		</td></tr>';

				//echo "<tr><td class=info>&nbsp;</td></tr>";
				listMembers($g, "users");

	$group_table = "ugroup" . $g;

	echo "<tr><td class=back2>";
        echo "$lang_addusertogroup: ";
	echo "<input type=text name=groupy>&nbsp;&nbsp;&nbsp;&nbsp; <font size=1>($lang_username)</font>";
	echo "<input type=hidden name=table value=$group_table><br>";
        echo "<input type=hidden name=g value=$g><br>";
	echo "<input type=submit name=add value=\"$lang_addusertogroup\"><br><br>";
	echo "</td></tr>";
	echo "</form>";


echo '				</tr></td>

			</td></tr>
		</table>



			</td>
			</tr>
		</table>
';
