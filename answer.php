<?php

/**************************************************************************************************
**	file:	answer.php
**
**		This file is the shows the answer to the asked question in the knowledge base.  
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
require_once "common/config.php";
require_once "common/$database.class.php";
require_once "common/common.php";
//since we have the id...lets grab all the info from the db and make this look pretty.

if($action == 'download'){
	
	require_once "common/config.php";
	require_once "common/$database.class.php";
	require_once "common/common.php";
	$language = getLanguage($cookie_name);
	if($language == '')
		require_once "lang/$default_language.lang.php";
	else
		require_once "lang/$language.lang.php";

	$query = $db->query("SELECT * from $mysql_attachments_table where kid=$kid");
	$file = $db->fetch_array($query);
	$db->query("UPDATE $mysql_attachments_table SET downloads=downloads+1 WHERE id='$id'");
	// Send the attachment
	header("Content-disposition: filename=$file[filename]");
	header("Content-Length: ".strlen($file[attachment]));
	header("Content-type: $file[filetype]");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $file[attachment];
	
}
else{

$sql = "SELECT * from $mysql_kbase_table where id='$id'";
$result = $db->query($sql);
$row = $db->fetch_array($result);

//update the popularity setting in the database.
$popularity = $row['popularity'] + 1;
$sql = "UPDATE $mysql_kbase_table set popularity='$popularity' where id=$id";
$db->query($sql);
$sql = "INSERT into $mysql_kb_queries_table values(null, '$id', '', '$REMOTE_ADDR', 1, ".time().", '$cookie_name')";
$db->query($sql);

startTable("$lang_kbase", "center");
	echo "<tr><td class=back2 align=right><form method=post>";
		createKBMenu();
	echo "<input type=hidden name=go value=Go><input type=hidden name=t value=kbase>";
	echo "<input type=submit name=go value=\"$lang_go\"></form>";
	echo "</td></tr>";
	echo "<tr><td class=back><b>";
	echo "<a href=\"index.php?t=kbase\">". strtolower($lang_kbase) ."</a> >> ";
	echo "<a href=\"index.php?t=kbase&pla=" . $row['platform'] . "\">" . strtolower($row['platform']) . "</a> >> ";
	echo "<a href=\"index.php?t=kbase&pla=" . $row['platform'] . "&cat=" . $row['category'] . "\">" . strtolower($row['category']);
	echo "</a></b><br><br><br>";

	echo "<font size=2><i>" . $row['question'] . "</i></font><br><br>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	$created = $row['time'];
	$last_edited = $row['last_edited'];
	$edited_by = $row['edited_by'];

	//this is where we'll need to format any text (ie create clickable links based on html stuff).
	$text = eregi_replace("  ", "&nbsp;", $row['answer']);
        echo nl2br($text) . "<br><br>";

	if($enable_kattachments == 'On'){
		//if there is an attachment, make it available for download
		$sql = "SELECT * from $mysql_attachments_table where kid=$id";
		$result = $db->query($sql);
		while($row = $db->fetch_array($result)){
			$attachsize = $row['filesize'];
			if($attachsize >= 1073741824) { $attachsize = round($attachsize / 1073741824 * 100) / 100 . "gb"; }
			elseif($attachsize >= 1048576) { $attachsize = round($attachsize / 1048576 * 100) / 100 . "mb"; }
			elseif($attachsize >= 1024)	{ $attachsize = round($attachsize / 1024 * 100) / 100 . "kb"; }
			else { $attachsize = $attachsize . "b"; }

			echo $lang_attachment . ": "; 
                        echo "<a target=_blank href=\"".$site_url."/answer.php?action=download&kid=".$row['kid']."&id=" . $row['id'] . "\">" . $row['filename'] . "</a> ( $attachsize ) <br>";
			echo $lang_downloaded . " " . $row['downloads'] . " " . $lang_times . "<br>";
		}
	}
	


	if(isSupporter($cookie_name)){
                
                echo "<br><div align=right><i> $lang_createdon: " . date("F j, Y, g:i a", $created);

                if($edited_by != ''){
                        echo "<br>$lang_editedon: " . date("F j, Y, g:i a", $last_edited);
                        echo "<br>$lang_lastedited: ". $edited_by . "<br>";
                }

                echo "</i></div>";
                
        }

	echo "</td></tr>";

	showReportEntry($id);

endTable();

}

function showReportEntry($id)
{
	global $cookie_name, $supporter_site_url, $lang_editthis, $lang_reportthis;

	echo "<tr><td class=back2 align=right>";
	echo "<a href=\"index.php?t=repo&id=$id\">$lang_reportthis</a>";

	 if(isSupporter($cookie_name)){
                echo "<br><a href=\"$supporter_site_url/index.php?t=kbase&act=kedit&id=$id\">$lang_editthis</a>";
        }

	echo "</td></tr>";
}

?>
