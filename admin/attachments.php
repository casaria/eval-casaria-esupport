<?php

/**************************************************************************************************
**	file:	attachments.php
**
**	This is the front end for most of the user options.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	10/02/01
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


if(isset($search)){
	$sql = setupSQLStatement($andor, $filename, $uploader, $search_in, $size_less, $size_greater, $down_less, $down_greater, $days_old);
	$result = $db->query($sql);
	displayFiles($result);
	echo "<br><br>";
	//echo "</td></tr></table>";
}

if($act == "del"){
	$sql = "DELETE from $mysql_attachments_table where id=$id";
	$db->query($sql);
}

if(!isset($search)){
	echo "<form method=\"post\">
			<TABLE class=border cellSpacing=0 cellPadding=0 width=\"70%\" align=center 
				border=0> 
				  <TR> 
					<TD> 
					  <TABLE cellSpacing=1 cellPadding=5 width=\"100%\" border=0>
						<TR> 
						  <TD colspan=2 class=info align=left><b> $lang_attachments </b></TD>
						</tr>
						<TR>
                                                        <TD class=back2 align=left>$lang_searchtype:</td>
							<td class=back align=left><SELECT name=andor>
								<option value=and>$lang_and</option>
								<option value=or>$lang_or</option>
								</SELECT></td>
						</TR>
						<TR>
							<TD class=back2 align=left>Where filename contains:</td>
							<td class=back align=left><input type=text name=filename size=30></td>
						</TR>
						<TR>
                                                        <TD class=back2 align=left>$lang_uploader:</td>
							<td class=back align=left><input type=text name=uploader size=30></td>
						</TR>
						<TR>
							<TD class=back2 align=left>Search in:</td>
							<td class=back><SELECT name=search_in>
								<option>All</option>
								<option value=ticket>Tickets Only</option>
								<option value=kbase>Knowledge Base Only</option>
								</SELECT>
						</TR>
						<TR>
							<TD class=back2 align=left>Where filesize is less than (bytes):</td>
							<td class=back align=left><input type=text name=size_less></td>
						</TR>
						<TR>
							<TD class=back2 align=left>Where filesize is greater than (bytes):</td>
							<td class=back align=left><input type=text name=size_greater></td>
						</TR>
						<TR>
							<TD class=back2 align=left>Where download count is less than:</td>
							<td class=back align=left><input type=text name=down_less size=8></td>
						</TR>
						<TR>
							<TD class=back2 align=left>Where download count is greater than:</td>
							<td class=back align=left><input type=text name=down_greater size=8></td>
						</TR>
						<TR>
							<TD class=back2 align=left>Where file is this many days old:</td>
							<td class=back align=left><input type=text name=days_old size=8></td>
						</TR>
						
					</table>
					</td></tr>
					</table>
					<br>
					<center><input type=submit name=search value=\"$lang_search\"></center>
					</form>
				<br><br>";
}



function displayFiles($result)
{
	global $db, $lang_delete, $lang_filename, $lang_filesize, $lang_uploader, $lang_downloads, $lang_filetype, $lang_ticket, $lang_kb, $enable_tattachments, $enable_kattachments, $enable_kbase, $admin_site_url, $site_url, $theme, $supporter_site_url, $lang_date, $lang_unknown;

	echo '<table class="border" cellSpacing="0" cellPadding="0" width="100%" align="center" border="0">
			<tr> 
			<td> 
				<table cellSpacing="1" cellPadding="5" width="100%" border="0">
					<tr>
				<td class=hf align="center">&nbsp;</td>
				<td class=hf>'.$lang_filename.'</td>
				<td class=hf>'.$lang_filesize.'</td>
				<td class=hf>'.$lang_filetype.'</td>
				<td class=hf align="center">'.$lang_uploader.'</td>';
				if($enable_tattachments == 'On')
					echo '<td class=hf align="center">'.$lang_ticket.'</td>';
				if($enable_kbase == 'On')
					echo '<td class=hf align="center">'.$lang_kb.'</td>';
				echo '<td class=hf>'.$lang_downloads.'</td>
						<td class=hf>'.$lang_date.'</td>
				
			</tr>';

	while($row = $db->fetch_array($result)){
		$filesize = convertFileSize($row[filesize]);
		$uid = getUserID($row[author]);
		echo "<tr>
				<td class=back2><a href=\"$admin_site_url/control.php?t=attachments&act=del&id=$row[id]\">$lang_delete</a>?</td>
				<td class=back><a target=_blank href=\"$site_url/tinfo.php?action=download&id=$row[id]\">" . $row[filename] . "</a></td>
				<td class=back2>" . $filesize . "</td>
				<td class=back>" . $row[filetype] . "</td>
				<td class=back2 align=\"center\"><a href=\"$admin_site_url/control.php?t=users&act=uedit&id=$uid\">" . $row[author] . "</a></td>";
					if($enable_tattachments == 'On'){
						if($row[tid] != ''){
							echo "<td class=back align=\"center\">";
							echo "<a href=\"$supporter_site_url/index.php?t=tupd&id=$row[tid]\">";
							echo "<img border=\"0\" src=\"../$theme[image_dir]/check.gif\"></a></td>";
						}
						else{
							echo "<td class=back>&nbsp;</td>";
						}
					}
					if($enable_kbase == 'On' && $enable_kattachments == 'On'){
						if($row[kid] != ''){
							echo "<td class=back2 align=\"center\">";
							echo "<a href=\"$admin_site_url/control.php?t=kbase&act=kedit&id=$row[kid]\">";
							echo "<img border=\"0\" src=\"../$theme[image_dir]/check.gif\"></a></td>";
						}
						else{
							echo "<td class=back2>&nbsp;</td>";
						}
					}
				echo "<td class=back align=\"center\">" . $row[downloads] . "</td>";
				if($row[timestamp] == '0')
					echo "<td class=back2 align=\"left\">" . $lang_unknown . "</td>";
				else
					echo "<td class=back2 align=\"left\">" . date("F j, Y, g:i a", $row[timestamp]) . "</td>";
			  echo "</tr>";

	}

	echo "</table></td></tr></table>";

}

function setupSQLStatement($andor, $filename, $uploader, $search_in, $size_less, $size_greater, $down_less, $down_greater, $days_old)
{
	global $mysql_attachments_table;

	$sql = "SELECT id, kid, tid, filename, filesize, filetype, downloads, author, timestamp from $mysql_attachments_table";
	if($filename != ''){
		$sql .= " where (filename regexp '$filename'";
		$started = 1;
	}

	if($uploader != ''){
		if($started == 1){
			$sql .= " $andor author='$uploader'";
		}
		else{
			$sql .= " where (author='$uploader'";
			$started = 1;
		}
	}

	if($search_in == 'ticket'){
		if($started == 1){
			$sql .= " $andor tid!='NULL'";
		}
		else{
			$sql .= " where (tid!='NULL'";
			$started = 1;
		}
	}

	if($search_in == 'kbase'){
		if($started == 1){
			$sql .= " $andor kid!='NULL'";
		}
		else{
			$sql .= " where (kid!='NULL'";
			$started = 1;
		}
	}

	if($size_less != ''){
		if($started == 1){
			$sql .= " $andor filesize < $size_less";
		}
		else{
			$sql .= " where (filesize < $size_less";
			$started = 1;
		}
	}

	if($size_greater != ''){
		if($started == 1){
			$sql .= " $andor filesize > $size_greater";
		}
		else{
			$sql .= " where (filesize > $size_greater";
			$started = 1;
		}
	}

	if($down_less != ''){
		if($started == 1){
			$sql .= " $andor downloads < $down_less";
		}
		else{
			$sql .= " where (downloads < $down_less";
			$started = 1;
		}
	}

	if($down_greater != ''){
		if($started == 1){
			$sql .= " $andor downloads > $down_greater";
		}
		else{
			$sql .= " where (downloads > $down_greater";
			$started = 1;
		}
	}
	
	if($days_old != ''){
		$date = time() - ($days_old * 60*60*24);
		if($started == 1){			
			$sql .= " $andor timestamp < $date";
		}
		else{
			$sql .= " where (timestamp < $date";
			$started = 1;
		}
	}

	if($started == 1){
		$sql .= ")";
	}

	$sql .= " order by filename asc";
	return $sql;


}

?>
