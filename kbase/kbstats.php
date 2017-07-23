<?php

/**************************************************************************************************
**	file:	kbase.php
**
**		This is the front end for all of the knowledge base stuff for the users.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	01/30/02
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

$limit = 15;

startTable("$lang_kbstats", "center", 100);
        
        echo "<tr><td class=back>";

				$sql = "SELECT * from $mysql_kb_queries_table where success=0 and query!='' order by timestamp desc limit $limit";
        $result = $db->query($sql);
        startTable("$limit $lang_latestunsuccessful", "left", 100, 3);
                echo "<tr><td class=back2>$lang_query</td><td class=back2>$lang_query $lang_date</td><td class=back2>$lang_query $lang_by</td></tr><tr>";
                        while($row = mysql_fetch_array($result)){
                                $date = date("F j, Y, g:i:s a", $row[timestamp]);
                                echo "<td class=back>".$row[query]."</td><td class=back>".$date."</td>";
								if($pubpriv == "Private" && $row[username] != ''){
									echo "<td class=back><a href=\"$supporter_site_url/index.php?t=memb&mem=";
									echo $row[username]."\">".$row[username]."</a></td></tr>";
								}
								else{
									echo "<td class=back>".$row[ip]."</td></tr>";
								}
                        }
                
                echo"</td>";
                echo "</tr>";
        endTable();

        $sql = "SELECT * from $mysql_kb_queries_table where success=1 and query!='' order by timestamp desc limit $limit";
        $result = $db->query($sql);
        startTable("$limit $lang_latestsuccessful", "left", 100, 3);
                echo "<tr><td class=back2>$lang_query</td><td class=back2>$lang_query $lang_date</td><td class=back2>$lang_query $lang_by</td></tr><tr>";
                        while($row = mysql_fetch_array($result)){
                                $date = date("F j, Y, g:i:s a", $row[timestamp]);
                                echo "<td class=back>".$row[query]."</td><td class=back>".$date."</td>";
								if($pubpriv == "Private" && $row[username] != ''){
									echo "<td class=back><a href=\"$supporter_site_url/index.php?t=memb&mem=";
									echo $row[username]."\">".$row[username]."</a></td></tr>";
								}
								else{
									echo "<td class=back>".$row[ip]."</td></tr>";
								}
						}
                
                echo"</td>";
                echo "</tr>";
        endTable();

        $sql = "SELECT * from $mysql_kbase_table order by time desc limit $limit";
        $result = $db->query($sql);
        startTable("$limit $lang_latestquestionsadded", "left", 100, 3);
                echo "<tr><td class=back2>$lang_question</td><td class=back2>$lang_createdate</td><td class=back2>$lang_author</td></tr><tr>";
                        while($row = mysql_fetch_array($result)){
                                $date = date("F j, Y, g:i:s a", $row[time]);
								echo "<td class=back>";
                                echo "<a href=\"$supporter_site_url/index.php?t=kbase&act=kedit&id=$row[id]\">".$row[question]."</a></td><td class=back>".$date."</td><td class=back><a href=\"$supporter_site_url/index.php?t=memb&mem=".$row[added_by]."\">".$row[added_by]."</a></td></tr>";
                        }
                
                echo"</td>";
                echo "</tr>";
        endTable();

        $sql = "SELECT * from $mysql_kbase_table where last_edited!='NULL' and last_edited!='0' order by last_edited desc limit $limit";
				$result = $db->query($sql);
        startTable("$limit $lang_latestquestionsedited", "left", 100, 3);
                echo "<tr><td class=back2>$lang_question</td><td class=back2>$lang_editedon</td><td class=back2>$lang_lastedited</td></tr><tr>";
                        while($row = mysql_fetch_array($result)){
                                $date = date("F j, Y, g:i:s a", $row[last_edited]);
  								echo "<td class=back>";
                                echo "<a href=\"$supporter_site_url/index.php?t=kbase&act=kedit&id=$row[id]\">".$row[question]."</a></td><td class=back>".$date."</td><td class=back><a href=\"$supporter_site_url/index.php?t=memb&mem=".$row[added_by]."\">".$row[added_by]."</a></td></tr>";
                        }
                
                echo"</td>";
                echo "</tr>";
        endTable();
        
?>