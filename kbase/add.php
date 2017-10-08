<?php

/***************************************************************************************************
**
**	file:	add.php
**
**		This file is used for adding new entries to the knowledge base.
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

if($add == $lang_addtokb)
{
	
	if($question != '' && $answer != ''){	//if the question and answer are not blank, do the add to the database.

		//add the entry to the knowledge base.
		$question = addslashes (stripScripts($question));
		$answer = addslashes (stripScripts($answer));
		$keywords = addslashes (stripScripts($keywords));

		$sql = "INSERT into $mysql_kbase_table VALUES(NULL, '$time', '$platform', '$category', '$question', '$answer', '$keywords', 0, '$view', '$cookie_name', '', '')";

		if($db->query($sql)){				//if the query goes ok, print out the success message.
			if($enable_kattachments == 'On'){
				$kid = $db->insert_id();			//get the id of the inserted question/answer
					if(isset($the_file) && ($the_file != "none") && ($the_file != ""))
					{		//we have a file so we need to do something with it

                                                $attachment = addslashes(fread(fopen($the_file, "rb"), filesize($the_file)));
						if($the_file_type=="application/x-gzip-compressed"){
							$attachment = base64_decode($attachment);
						}
						$query = "INSERT into $mysql_attachments_table VALUES(NULL, $kid, NULL, '$the_file_name', '$the_file_type', '$the_file_size', '$attachment', 0, '$cookie_name', $time)";
						$db->query($query);	//insert all info about the attachment into the database.
					}
			}
			printSuccess("$lang_kbadded");
		}
		else{								//if the query screws up, print out the error message.
			printError("$lang_kberror");
		}
	}
	else{									//if the question or answer are blank, print the missing info message.
		printError("$lang_kbmissinginfo");
	}
			
}
else{
	echo "<form method=\"post\" name=\"input\" action=\""
	echo htmlentities($_SERVER[$PHP_SELF]).	" enctype=\"multipart/form-data\">";
	startTable("$lang_addtokb", "center", 100, 4);
		echo '<tr><td class=back2 align=right width=27%>'.$lang_platform.':</td> <td class=back>';
		echo '<select name=platform>';
			createPlatformMenu(0, $platform);
		echo '</select></td>';
		echo '<td align=right class=back2 width=27%>'.$lang_category.': </td> <td class=back>';
		echo '<select name=category>';
			createKCategoryMenu(0, $category);
		echo '</select></td></tr>';
		echo '<tr><td class=back2 align=right width=27%>
			'.$lang_question.': </td><td colspan=3 class=back><input type=text size=60 name=question value="'.$short.'"></input></td></tr>';
		echo '<tr valign=top><td align=right class=back2 width=27%>
			'.$lang_answer.': </td><td colspan=3 class=back> <textarea name=answer rows=15 cols=60>'.$description.'</textarea><br></td></tr>';
		echo '</td></tr>';
		echo '<tr><td class=back2 align=right width=27%>'.$lang_keywords.': <font size=1>('.$lang_sepbycomma.')</font></td> <td colspan=3 class=back>';
		echo '<input type=text size=60 name=keywords></input></td></tr>';
		echo '<tr><td class=back2 align=right width=27%>'.$lang_viewableby.':</td> <td colspan=3 class=back>';
        echo '<select name=view>';
            createViewableByMenu();
		echo '</select></td></tr>';
		if($enable_kattachments == 'On'){
			echo '<input type=hidden name="MAX_FILE_SIZE" value="1000000">';
			echo '<tr><td class=back2 align=right width=27%>'.$lang_addattachment.':</td> <td colspan=3 class=back>';
			echo "<input type=\"file\" name=\"the_file\" size=35>";
			echo '</td></tr>';
		}

	endTable();

	echo "<input type=hidden name=t value=kbase>";
	echo "<input type=hidden name=act value=kadd>";
	echo "<center> <input type=submit name=add value=\"$lang_addtokb\"></center></form>";

}

?>
