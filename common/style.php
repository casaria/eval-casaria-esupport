<?php

/***************************************************************************************************
**
**	file: style.php
**
**		This file contains the style sheet.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	12/23/2001
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

//if the user is not logged in, set the default style sheet.
//otherwise, grab the selected theme from the database.
$theme = getThemeVars(getThemeName($cookie_name));

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE> <?php echo $helpdesk_name;?></TITLE>
<STYLE type="text/css">

	BODY {background: <?php echo $theme['bgcolor'];?> ; color: black;}

	a:link {text-decoration: none; color: <?php echo $theme['link']; ?>;}
	a:visited {text-decoration: none; color: <?php echo $theme['link']; ?>;}
	a:active {text-decoration: none; color: <?php echo $theme['link']; ?>;}
	a:hover {text-decoration: underline; color: <?php echo $theme['link']; ?>;}

	a.kbase:link {text-decoration: underline; font-weight: bold; color: <?php echo $theme['text']; ?>;}
	a.kbase:visited {text-decoration: underline; font-weight: bold; color: <?php echo $theme['text']; ?>;}
	a.kbase:active {text-decoration: underline; font-weight: bold; color: <?php echo $theme['text']; ?>;}
	a.kbase:hover {text-decoration: underline; font-weight: bold; color: <?php echo $theme['text']; ?>;}
	
	
	table.border {background: <?php echo $theme['table_border']; ?>; color: black;}
	td {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: <?php echo $theme['font_size']; ?>px;}
	tr {color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: <?php echo $theme['font_size']; ?>px;}
	td.back {background: <?php echo $theme['bg1']; ?>;}
	td.back2 {background: <?php echo $theme['bg2']; ?>;}
	td.printback {background: <?php echo $theme['print_bg']; ?>;}

	td.date {background: <?php echo $theme['category']; ?>; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size'];?>px; color: <?php echo $theme['text']; ?>;}
	
	td.hf {background: <?php echo $theme['header_bg']; ?>; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['header_text']; ?>;}
	
	td.printhf {background: <?php echo $theme['print_header_bg']; ?>; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['print_header_text']; ?>;}

	a.hf:link {text-decoration: none; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['header_text']; ?>;}
	
	a.hf:visited {text-decoration:none; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['header_text']; ?>;}
	
	a.hf:active {text-decoration: none; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['header_text']; ?>;}
	
	a.hf:hover {text-decoration: underline; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['header_text']; ?>;}

	td.info {background: <?php echo $theme['info_bg']; ?>; font-family: Arial, Helvetica, sans-serif; font-size: <?php echo ($theme['font_size']+1); ?>px; color: <?php echo $theme['header_text']; ?>;}

  td.extra {background: <?php echo $theme['info_bg']; ?>; font-family: Arial, Helvetica, sans-serif; font-size: <?php echo ($theme['font_size']+3); ?>px; color: <?php echo $theme['extra_text']; ?>;}

	td.printinfo {background: <?php echo $theme['print_info_bg']; ?>; font-family: Arial, Helvetica, sans-serif; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['print_info_text']; ?>;}
	
	a.info:link {text-decoration: none; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['info_text']; ?>;}
	
	a.info:visited {text-decoration:none; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['info_text']; ?>;}
	
	a.info:active {text-decoration: none; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['info_text']; ?>;}
	
	a.info:hover {text-decoration: underline; font-weight: normal; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['info_text']; ?>;}

<?php

if(eregi("IE", $HTTP_USER_AGENT)){ ?>
	select, option, textarea, input {border: 1px solid <?php echo $theme['table_border']; ?>; font-family: Verdana, arial, helvetica, sans-serif; font-size: 	11px; font-weight: bold; background: <?php echo $theme['subcategory']; ?>; color: <?php echo $theme['text']; ?>;} <?php
}
else{ ?>
	select, option, textarea, input {font-family: Verdana, arial, helvetica, sans-serif; font-size:	11px; background: <?php echo $theme['subcategory']; ?>; color: <?php echo $theme['text']; ?>;}
<?php
}
?>

	td.cat {background: <?php echo $theme['category']; ?>; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px; color: <?php echo $theme['text']; ?>;}
	
	td.stats {background: <?php echo $theme['category']; ?>; font-family: "<?php echo $theme['font']; ?>"; font-size: 10px; color: <?php echo $theme['text']; ?>;}
	
	td.error {background: <?php echo $theme['subcategory']; ?>; color: #ff0000; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px;}
	
	td.subcat {background: <?php echo $theme['subcategory']; ?>; color: <?php echo $theme['text']; ?>; font-family: "<?php echo $theme['font']; ?>"; font-size: <?php echo $theme['font_size']; ?>px;}

	
	
	input.box {border: 0px;}

	table.border2 {background: #6974b5;}
	td.install {background:#dddddd; color: #000000; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	table.install {background: #000099;}
	td.head	{background:#6974b5; color: #ffffff; font-family: Arial, Helvetica, sans-serif; font-size: 12px;}
	a.install:link {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:visited {text-decoration:none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #6974b5;}
	a.install:active {text-decoration: none; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}
	a.install:hover {text-decoration: underline; font-weight: normal; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000099;}

</STYLE>
</HEAD>
<?php

function getThemeVars($name)
{
	global $mysql_themes_table, $db;

	if($name == ''){
		return 'default';
	}
	else{
		$sql = "select * from $mysql_themes_table where name='$name'";
		$result = $db->query($sql);
		//$result = execsql($sql);
		$row = $db->fetch_array($result);

		return $row;
	}

}

/***********************************************************************************************************
**	function getThemeName():
**		Takes one argument.  Queries the database and selects the theme associated with the user name that
**	is given.  Returns the file path of the css file.
************************************************************************************************************/
function getThemeName($name)
{
	global $mysql_users_table, $mysql_themes_table, $mysql_settings_table, $default_theme, $db;

	if($name == '' || !isset($name)){
		return $default_theme;
	}
	else{
		$sql = "select theme from $mysql_users_table where user_name='$name'";
		$result = $db->query($sql);
		//$result = execsql($sql);
		$row = $db->fetch_array($result);

		if($row[0] == 'default'){	//if users theme is set to default, get the default theme from the db
			$sql = "select default_theme from $mysql_settings_table";
			$result = $db->query($sql);
			$row = $db->fetch_array($result);
		}
		
		return $row[0];
	}

}

?>

