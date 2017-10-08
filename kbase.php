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

if($supkb == 'yes' && !isCookieSet($_SESSION['cookie_name'], $_SESSION['enc_pwd'])){
        echo "$lang_noprivs";
        exit;
}

if(isset($go)){

		if(eregi("\*", $item) || $item == ''){
			printError("$lang_searcherror");
		}
		else{
			$sql = setupSql($category, $platform, $item);
			$result = $db->query($sql);
			$num_rows = mysql_num_rows($result);

			if($num_rows < 1){
					unsuccessfulSearch($item);
			}
			else{
					successfulSearch($item);
			}


			startTable("$lang_searchresults", "center", 100, 3);
					echo "<tr><td colspan=3 class=back2 align=right><form method=post>";
							createKBMenu();
					echo "<input type=hidden name=go value=Go><input type=hidden name=t value=kbase>";
					echo "<input type=submit name=go value=\"$lang_go\">";
					echo "</td></tr></form>";
					echo "<tr><td class=back colspan=3><br></td></tr>";     
					//display results of search
					showSearchResults($result);
			endTable();
		}
}
else{

        //Platform is set, but category is not.
        if(isset($pla) && !isset($cat)){
                //show the categories, plus some questions from each category.
                $cats = getKCategoryList($pla);
                
                startTable($pla . " $lang_kbase", "center");
                        echo "<tr><td class=back2 align=right><form method=post>";
                                createKBMenu();
                        echo "<input type=hidden name=go value=Go><input type=hidden name=t value=kbase>";
                        echo "<input type=submit name=go value=\"$lang_go\">";
                        echo "</td></tr></form>";
                        echo "<tr><td class=back><b>";
                        echo "<a href=\"index.php?t=kbase";
                        if($supkb == 'yes'){
                                echo "&supkb=yes";
                        }
                        echo "\">". strtolower($lang_kbase) ."</a> >> " . strtolower($pla) . "</b><br><br><br>";
                        
                        for($i=0; $i<sizeof($cats); $i++){
                                //print out the category first.
                                echo "<font size=3><b><a class=kbase href=\"index.php?t=kbase&pla=$pla&cat=".$cats[$i];
                                if($supkb == 'yes'){
                                        echo "&supkb=yes";
                                }
                                echo "\">".$cats[$i]."</a></b></font><br><hr width=100%>";

                                //now for each category, list the first 5 questions based on popularity.
                                if($supkb != 'yes'){
                                        $sql = "SELECT id,question from $mysql_kbase_table where platform='$pla' and category='". $cats[$i] . "' and viewable_by='all' order by popularity desc limit 6";
                                }
                                else{
                                        $sql = "SELECT id,question from $mysql_kbase_table where platform='$pla' and category='". $cats[$i] . "' and viewable_by='supporters' order by popularity desc limit 6";
                                }

                                $result = $db->query($sql);
                                $num_rows = mysql_num_rows($result);

                                $j=0;
                                while($row = mysql_fetch_array($result)){
                                        if($j < 5){
                                                echo "<a href=\"index.php?t=kbase&act=kans&id=" . $row['id'] . "\">" . $row['question'] . "</a><br>";
                                        }
                                        $j++;
                                }

                                if($num_rows > 5){
                                        echo "<b>( <a href=\"index.php?t=kbase&cat=" . $cats[$i] . "&pla=" . $pla . "\">. . . more</a> )</b><br>";
                                }
                                echo "<br>";

                        }
                endTable();

        }

        //both platform and category are set
        elseif(isset($pla) && isset($cat)){
                //list all of the questions for this platform and category.
                if($supkb != 'yes'){
                        $sql = "SELECT id, question from $mysql_kbase_table where platform='$pla' and category='$cat' and viewable_by='all' order by popularity desc";
                }
                else{
                        $sql = "SELECT id, question from $mysql_kbase_table where platform='$pla' and category='$cat' and viewable_by='supporters' order by popularity desc";
                }

                $result = $db->query($sql);

                startTable($pla . " $lang_kbase", "center");
                        echo "<tr><td class=back2 align=right><form method=post>";
                                createKBMenu();
                        echo "<input type=hidden name=go value=Go><input type=hidden name=t value=kbase>";
                        echo "<input type=submit name=go value=\"$lang_go\">";
                        echo "</td></tr></form>";
                        echo "<tr><td class=back><b>";
                        echo "<a href=\"index.php?t=kbase\">" . strtolower($lang_kbase) . "</a> >> ";
                        echo "<a href=\"index.php?t=kbase&pla=$pla\">" . strtolower($pla) . "</a> >> " . strtolower($cat) . "</b><br><br><br>";
                        echo "<font size=3><b>".$cat."</b></font><br><hr width=100%>";
                        while($row = mysql_fetch_array($result)){
                                echo "<a href=\"index.php?t=kbase&act=kans&id=" . $row['id'] . "\">" . $row['question'] . "</a><br>";
                        }
                        echo "<br></td></tr>";
                endTable();

        }

        else{

                //neither platform or category are set.
                startTable("$lang_kbase", "center");
                        echo "<tr><td class=back2 align=right><form method=post>";
                                createKBMenu();
                        echo "<input type=hidden name=go value=Go><input type=hidden name=t value=kbase>";
                        echo "<input type=submit name=go value=\"$lang_go\">";
                        echo "</td></tr></form>";
                        echo "<tr><td class=back>";
                        echo "<table border=0 width=100% cellspacing=5 cellpadding=15><tr><td>";
                                $platforms = getPlatformsList();
                                for($i=0; $i<sizeof($platforms); $i++){
                                        if($i%2 == 1){
                                                echo "<td width=50% class=back>";
                                        }
                                        else{
                                                echo "<tr><td width=50% class=back>";
                                        }
                                        echo "<font face=\"Arial\" size=3><b><a class=kbase href=\"index.php?t=kbase&pla=".$platforms[$i];
                                        if($supkb == 'yes'){
                                                echo "&supkb=yes";
                                        }
                                        echo "\">" . $platforms[$i] . "</a></b></font><br>";

                                        //print out the categories;
                                        $cats = getKCategoryList($platforms[$i]);
                                        for($j=0; $j<sizeof($cats); $j++){
                                                if($j == 0){
                                                        echo "<a href=\"index.php?t=kbase&pla=" . $platforms[$i] . "&cat=" . $cats[$j];
                                                        if($supkb == 'yes'){
                                                                echo "&supkb=yes";
                                                        }
                                                        echo "\">" . $cats[$j] . "</a></font>";
                                                }
                                                else{
                                                        echo ", <a href=\"index.php?t=kbase&pla=" . $platforms[$i] . "&cat=" . $cats[$j];
                                                        if($supkb == 'yes'){
                                                                echo "&supkb=yes";
                                                        }
                                                        echo "\">" . $cats[$j] . "</a>";
                                                }
                                        }
                                        echo "<br><br><br>";
                                        
                                        if($i%2 == 1){
                                                echo "</td></tr>";
                                        }
                                        else{
                                                echo "</td>";
                                        }

                                }
                                echo "</td></tr>";
                        

                        echo "</table></td></tr>";
                                //if sent, let the user know.
                        if(isset($add)){
                                echo "<tr><td class=back>";
                                //send the information to the admins.
                                if($enable_smtp == 'lin'){
									sendmail($admin_email, $helpdesk_name, "$lang_nobody", $id, $msg, "$lang_kbaseq");
                                    $flag = 1;
							    }
								if($enable_smtp == 'win'){
									mail($admin_email, "$lang_kbaseq", $msg, "From: ".$helpdesk_name ."\n");
									$flag = 1;
								}
                                
                                if($flag == 1){
                                        printSuccess("$lang_questionsent");
                                }
								else{
									printError("$lang_smtpofferror");
									exit;
								}
                                echo "</td></tr>";
                                
                        }
                        else{
                                submitQuestion();
                        }
                endTable();

        }       
}



function getPlatformsList()
{
        global $mysql_platforms_table, $db;

        $sql = "SELECT platform from $mysql_platforms_table order by rank asc";
        $result = $db->query($sql);
        $i=0;
        while($row = $db->fetch_row($result)){
                $array[$i] = $row[0];
                $i++;
        }

        return $array;
}

function getKCategoryList($platform)
{
        global $supkb, $db, $mysql_kbase_table;
        
        //select the category based on the platform and order it so the most popular category is listed first.
        if($supkb != 'yes'){
                $sql = "SELECT distinct(category), sum(popularity) as sum from $mysql_kbase_table where platform='$platform' and viewable_by='all' group by category order by sum desc";
        }
        else{
                $sql = "SELECT distinct(category), sum(popularity) as sum from $mysql_kbase_table where platform='$platform' and viewable_by='supporters' group by category order by sum desc";
        }

        $result = $db->query($sql);
        $i=0;
        while($row = $db->fetch_row($result)){
                $array[$i] = $row[0];
                $i++;
        }

        return $array;
}

function setupSql($category, $platform, $item)
{
        global $db, $mysql_kbase_table, $supkb;

        //this is where we display the results of the search.
                        $sql = "SELECT * from $mysql_kbase_table where ";

                        //we have category, platform, and item available.
                        if($category != ''){
                                $sql .= "category='$category' ";
                                $flag = 1;
                        }
                        if($platform != ''){
                                if($flag == 1){
                                        $sql .= "and platform='$platform' ";
                                        $flag = 1;
                                }
                                else{
                                        $sql .= "platform='$platform' ";
                                        $flag = 1;
                                }
                        }

						if($supkb != 'yes'){
                                if($flag == 1){
                                        $sql .= "and viewable_by!='supporters' ";
                                        $flag = 1;
                                }
                                else{
                                        $sql .= "viewable_by!='supporters' ";
                                        $flag = 1;
                                }
                        }

                        if($item != ''){
                                //first, separate the words by either commas or spaces.
                                if(ereg(",", $item)){
                                        $items = explode(",", $item);
                                }
                                elseif(ereg(" ", $item)){
                                        $items = explode(" ", $item);
                                }
                                else{
                                        $items[0] = $item;
                                }
                                for($i=0; $i<sizeof($items); $i++){
                                        if($flag == 1){
                                               $sql .= "and (question regexp '$items[$i]' or answer regexp '$items[$i]' or keywords LIKE '%$items[$i]%') ";
                                                $flag = 1;
                                        }
                                        else{
												$sql .= "(question regexp '$items[$i]' or answer regexp '$items[$i]' or keywords LIKE '%$items[$i]%') ";
                                                $flag = 1;
                                        }
                                }
                        }

                        $sql .= " order by popularity asc";

        return $sql;
}

function showSearchResults($result)
{
		global $db, $lang_platform, $lang_category, $lang_question;

        $i=0;
        echo "<tr><td class=info align=center> $lang_platform </td><td class=info align=center> $lang_category </td><td class=info align=center> $lang_question </td></tr>";
        while($row = $db->fetch_array($result)){
                if($i%2 == 0){
                        echo "<tr><td class=back align=center>";
                        echo $row['platform'] . "</td>";
                        echo "<td class=back align=center>";
                        echo $row['category'] . "</td><td class=back>";
                        echo "<a href=\"?t=kbase&act=kans&id=".$row['id']."\">" . $row['question'] . "</a></td></tr>";
                }
                else{
                        echo "<tr><td class=back2 align=center>";
                        echo $row['platform'] . "</td>";
                        echo "<td class=back2 align=center>";
                        echo $row['category'] . "</td><td class=back2>";
                        echo "<a href=\"?t=kbase&act=kans&id=".$row['id']."\">" . $row['question'] . "</a></td></tr>";
                }
                        
                $i++;
        }

}

function submitQuestion()
{
		global $lang_submitq, $lang_submitquestion;
        echo "<form method=post>";
        echo "<tr><td class=back2><b> $lang_submitquestion: </b><textarea name=msg rows=2 cols=90></textarea> <br><br> <input type=submit name=add value=\"$lang_submitq\"></td></tr></form>";

}

function unsuccessfulSearch($thing)
{
        global $REMOTE_ADDR, $db, $cookie_name;
        $sql = "INSERT into kb_queries values(null, null, '$thing', '$REMOTE_ADDR', '0', ".time().", '$cookie_name')";
        $db->query($sql);
}

function successfulSearch($thing)
{
        global $REMOTE_ADDR, $db, $cookie_name;
        $sql = "INSERT into kb_queries values(null, null, '$thing', '$REMOTE_ADDR', '1', ".time().", '$cookie_name')";
        $db->query($sql);
}

?>