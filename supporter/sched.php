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
require_once "../common/scripts.php";

startTable("$lang_groupslists", "center");
	echo "<tr><td class=cat> $lang_groupslists2 </td></tr>";
	echo "<tr><td class=back2>";

	$group_array = getGroupList($cookie_name, 0);
	if(sizeof($group_array) == 0){
		printerror("$lang_nogroups");
	}
	echo "</td></tr>";
//  endTable();



function listGroupMembers($group)
{
    global $supporter_site_url, $db, $lang_group;

    $group_id = eregi_replace("sgroup", "", $group);

    $sql = "select user_name from $group order by user_name";
    $result = $db->query($sql);
    startTable("$lang_group  --  ".getsGroup($group_id), "left");
    echo "<tr><td class=back2>";
    while($row = $db->fetch_array($result)){
        echo "<li><a href=\"".$supporter_site_url."/index.php?t=memb&mem=".$row['user_name']."\">" . $row['user_name'] . "</a></li>";
    }
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


<div class="container">
			<h1>Scheduler</h1>
			<div id="sandbox">
				<div class="row">
					<div class="col-sm-6">
						<h2>Filters</h2>
						<ul id="filters">
							<li>
								<a href="#" data-filter="*">all</a>
							</li>
							<li>
								<a href="#" data-filter=".overhead">Overhead</a>
							</li>
							<li>
								<a href="#" data-filter=".billable">billable</a>
							</li>
						</ul>
					</div>
					<div class="col-sm-6">
						<h2>Sorts</h2>
						<ul id="sorts">
							<li>
								<a href="#">text</a>
							</li>
							<li>
								<a href="#" data-sort-by="foo">data-foo</a>
							</li>
							<li>
								<a href="#" data-sort-way="desc">text desc</a>
							</li>
							<li>
								<a href="#" data-sort-by="foo" data-sort-way="desc">data-foo desc</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="container">
                    <div id="dylay" class="row">
						<div class="col-sm-12 overhead"  data-foo="5">
							<span style="height: 200px;">#4530<br>Short dedcription<br>line 2</span>
						</div>
						<div class="col-sm-12 consonne" data-foo="1">
							<span style="height: 60px;">#4584</span>
						</div>
						<div class="col-sm-12 billable" data-foo="17">
							<span style="height: 40px;">#4000</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="assets/vendor/jquery.easing.1.3.js"></script>
		<script src="src/dylay.js"></script>
		<script src="assets/js/main.js"></script>


