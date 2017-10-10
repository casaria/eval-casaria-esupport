<?php

/**************************************************************************************************
**	file:	index.php
**
**		This file lists the groups that the supporter is associated with, not including user groups.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/19/01
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


startTable("$lang_groupslists", "center");
	echo "<tr><td class=cat> $lang_groupslists2 </td></tr>";
	//echo "<tr><td class=back2>";



	listGroupMembers("sgroup2");
?>
    </div>
</div>

<?php
	echo "</td></tr>";
endTable();



function listGroupMembers($group)
{
    global $supporter_site_url, $db, $lang_group;

    $group_id = eregi_replace("sgroup", "", $group);

    $sql = "SELECT * FROM `users`\n"
        . "INNER JOIN `sgroup2` ON `sgroup2`.`user_name` = `users`.`user_name` ";

    $result = $db->query($sql);
    startTable("$lang_group  --  ".getsGroup($group_id), "left");
    echo "<tr><td class=back>";

    echo '<div class="container">';

    echo '<div id="dylay" class="row no-gutters">';
    while($row = $db->fetch_array($result)){


        echo "<div class=\"col-xs-12 col-sm-6 col-md-3 ".($row['supporter'] >= 1 ? "active" : "inactive")."\" username=\"$row[user_name]\">";
							echo "<span style=height:60px;>";
                            echo "$row[first_name] $row[last_name] <b>($row[user_name])</b><br>$row[email] ".date("m/d/Y", $row[lastactive])."</span></div>";


    }
    echo '</div></div></div>';
    echo "</td></tr>";


    endTable();

}


/***********************************************************************************************************
 **	function getGroupList():
 **		Takes two arguments.  Queries the supporter group tables and gets a list of all sgroups in an array.
 **	If the flag is not set, prints out the members of each group if the name given is in that particular
 **	group.  If the flag is set, group members are not listed.  In both cases, the array of sgroups is
 **	returned.
 ************************************************************************************************************/
/*
function getGroupList($name, $flag=1)
{
    global $mysql_sgroups_table, $db;

    $sql = "select id from $mysql_sgroups_table where id != 1";
    $result = $db->query($sql);
    $i = 0;
    while ($row = $db->fetch_row($result)){
        $group[$i] = "sgroup" . $row[0];
        $i++;

    }
    //now list contains a list of all the groups....now we have to cycle through that list
    //and determine whether the logged in user is in each group.

    if($name != '' && $flag != 1){
        for($i=0; $i<sizeof($group); $i++){
            if(inGroup($name, $group[$i])){
                listGroupMembers($group[$i]);
            }
        }
    }

    return $group;

}
*/
?>



