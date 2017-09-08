<?php

/***************************************************************************************************
**
**	file:	edit.php
**
**		This file is used for editing existing entries in the knowledge base provided an id number.
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

$time = time();
if(!isset($id)){
	//lets display all of the questions first.
	
	switch($order){
		default:
			$sql = "SELECT id, platform, category, question from $mysql_kbase_table order by platform, category, question asc";
			break;
		case("category"):
			$sql = "SELECT id, platform, category, question from $mysql_kbase_table order by category, question, popularity asc";
			break;
		case("question"):
			$sql = "SELECT id, platform, category, question from $mysql_kbase_table order by question, popularity asc";
			break;
	}

	echo '<table class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<tr> 
			 <td> 
				<table cellSpacing=1 cellPadding=5 width="100%" border=0><tr><td class=hf>';

echo "<form action=\"control.php?t=kbase&act=kdel\" method=post>";
$location = $HTTP_REFERER . "&act=kedit";
echo "<input type=hidden name=location value=\"".$location."\">";

	echo "<b><a href=\"control.php?t=kbase&act=kedit&order=platform\">$lang_platform</a></b></td>";
	echo "<td class=hf><b><a href=\"control.php?t=kbase&act=kedit&order=category\">$lang_category</a></b></td>";
	echo "<td class=hf><b><a href=\"control.php?t=kbase&act=kedit&order=question\">$lang_question</a></b></td></tr>";

	$result = $db->query($sql);
	$i=0;
	while($row = $db->fetch_array($result)){
		if($i%2 == 0){
			echo "<tr><td class=back>" . $row['platform'] . "</td><td class=back>" . $row['category'];
			echo "</td><td class=back><a href=\"$admin_site_url/control.php?t=kbase&act=kedit&id=" . $row['id'] . "\">";
			echo $row['question'] . "</a></td></tr>";
		}
		else{
			echo "<tr><td class=back2>" . $row['platform'] . "</td><td class=back2>" . $row['category'];
			echo "</td><td class=back2><a href=\"$admin_site_url/control.php?t=kbase&act=kedit&id=" . $row['id'] . "\">";
			echo $row['question'] . "</td></tr>";
		}
		$i++;
	}

	echo "</td></tr></table></table>";

}
else{		//if id is set, we are already editing an entry.

	if($edit == "$lang_edit $lang_entry"){
		//update the database according to the id
		$question = stripScripts($question);
		$answer = stripScripts($answer);
		$keywords = stripScripts($keywords);

		$sql = "UPDATE kbase set platform='$platform', category='$category', question='$question', answer='$answer', viewable_by='$view', edited_by='$cookie_name', last_edited='$time' where id='$id'";
		$db->query($sql);

		//if a file is attached...
		if($enable_kattachments == 'On'){
			if(!empty($attach)){		  //NOV14 $attach != "none" && $attach != ""  //we have a file so we need to do something with it
						
            $attachment = addslashes(fread(fopen($attach, "rb"), filesize($attach)));
				if($attach_type=="application/x-gzip-compressed"){
					$attachment = base64_decode($attachment);
				}
				$query = "INSERT into $mysql_attachments_table VALUES(NULL, $id, NULL, '$attach_name', '$attach_type', '$attach_size', '$attachment', 0, '$cookie_name', $time)";
				$db->query($query);	//insert all info about the attachment into the database.
			}
		}
	}

	if($delete == "attachment"){
		$sql = "DELETE from $mysql_attachments_table where kid=$id";
		$db->query($sql);
	}

	if($delete == "$lang_delete $lang_entry"){

		switch($kpurge){
			case ("Always"):
				$ok = $lang_delete;
				break;
			case ("Never"):
				$ok = $lang_save;
				break;
			case ("Prompt"):
				//prompt user to delete any associated files:
				$sql = "SELECT id, filename from $mysql_attachments_table where kid='$id'";
				$result = $db->query($sql);
				$attach = $db->fetch_array($result);
				if($attach && !isset($ok)){		//if we're here, then there are attachments associated with this entry.
					echo "<form action=\"?t=kbase&act=kedit\" method=post>";
					startTable("$lang_delete $lang_attachment", "left", 100, 1); 
						echo "<tr><td class=back><br>";
						echo $lang_deletekattachment1."&nbsp; <b> ".$attach[filename]." </b> ".$lang_deletekattachment2;
						echo "<br><br>";
						echo "<input type=hidden name=delete value=\"$lang_delete $lang_entry\">";
						echo "<input type=hidden name=id value=$id>";
						echo "<input type=submit name=ok value=\"$lang_delete\"> &nbsp;&nbsp; ";
						echo "<input type=submit name=ok value=\"$lang_save\">";
						echo "</form></td></tr>";
					endTable();
					$break = 'yes';
				}
				else{
					$sql = "DELETE from $mysql_kbase_table where id='$id'";
					$db->query($sql);
					$break = 'yes';
				}

				break;
		}


		if($ok == $lang_delete){
			$sql = "DELETE from $mysql_attachments_table where kid='$id'";
			$db->query($sql);				//delete the attachment associated with the kb entry.
			$sql = "DELETE from $mysql_kbase_table where id='$id'";
			$db->query($sql);
			unset($id);

			echo "<meta HTTP-EQUIV=\"refresh\" content=\"0; url=\"" . $admin_site_url . "/control.php?t=kbase\">";
			exit;
		}
		if($ok == $lang_save){
			$sql = "DELETE from $mysql_kbase_table where id='$id'";
			$db->query($sql);
			unset($id);
			echo "<meta HTTP-EQUIV=\"refresh\" content=\"0; url=\"" . $admin_site_url . "/control.php?t=kbase\">";
			exit;
		}


}

if($break != 'yes'){

	//get the data from the kbase table into an array based on the id.
	$sql = "SELECT * from $mysql_kbase_table where id=$id";
	$result = $db->query($sql);
	$info = $db->fetch_array($result);

	echo "<form action=\"?t=kbase&act=kedit\" method=post enctype=\"multipart/form-data\">";

	startTable("$lang_edit $lang_entry", "center", 100, 4);
		echo '<tr><td class=back2 align=right width=27%>'.$lang_platform.':</td> <td class=back>';
		echo '<select name=platform>';
			createPlatformMenu();
		echo '</select></td>';
		echo '<td align=right class=back2 width=27%>'.$lang_category.': </td> <td class=back>';
		echo '<select name=category>';
			createKCategoryMenu();
		echo '</select></td></tr>';
		echo '<tr><td class=back2 align=right width=27%>
			'.$lang_question.': </td><td colspan=6 class=back><input type=text size=60 name=question value="' . $info['question'] . '"></input></td></tr>';
		echo '<tr valign=top><td align=right class=back2 width=27%>
			'.$lang_answer.': </td><td colspan=6 class=back> <textarea name=answer rows=15 cols=200>'.$info['answer'].'</textarea><br></td></tr>';
		echo '</td></tr>';

		echo '<tr><td class=back2 align=right width=27%>'.$lang_viewableby.':</td> <td colspan=3 class=back>';
		echo '<select name=view>';
			createViewableByMenu();
		echo '</select></td></tr>';
		if($enable_kattachments == 'On'){
			$sql = "SELECT filename from $mysql_attachments_table where kid=$id";
			$result = $db->query($sql);
			$row = $db->fetch_array($result);
			if($row[filename] != ''){
				echo '<tr><td class=back2 align=right width=27%>'.$lang_attachment.':</td><td colspan=3 class=back>';
				echo $row[filename] . "&nbsp;&nbsp;&nbsp;&nbsp; ";
				if(eregi("admin", $PHP_SELF))
                                        echo "<a href=\"$admin_site_url/control.php?t=kbase&act=kedit&delete=attachment&id=$id\"> $lang_delete</a>?";
				else
                                        echo "<a href=\"$supporter_site_url/index.php?t=kbase&act=kedit&delete=attachment&id=$id\"> $lang_delete</a>?";

				echo "</td></tr>";
			}
			else{
				echo '<input type=hidden name="MAX_FILE_SIZE" value="1000000">';
				echo '<tr><td class=back2 align=right width=27%>'.$lang_addattachment.':</td>';
				echo "<td colspan=3 class=back> <input type=\"file\" name=\"attach\" size=35>";
				echo "</td></tr>";
			}
		}

	endTable();

	echo "<input type=hidden name=id value=$id>";
	echo "<center> <input type=submit name=edit value=\"$lang_edit $lang_entry\"> &nbsp;&nbsp;";
	echo "<input type=submit name=delete value=\"$lang_delete $lang_entry\"></center></form>";

	}
}
?>
