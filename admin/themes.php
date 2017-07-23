<?php

/***************************************************************************************************
**
**	file:	themes.php
**
**		Description:  all code relating to adding/deleting/modifying themes.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	04/25/02
	**
	************************************************************************************************/

//check to make sure the file is called from either control.php or index.php and not called directly.
if(!eregi("index.php", $PHP_SELF) && !eregi("control.php", $PHP_SELF)){
	echo "$lang_noaccess";
	exit;
}

echo "<form method=\"post\" action=\"control.php?modify=yes&t=theme&tid=$tid\">";
	if(isset($modify)){

		if($details == 'add'){
			if(isset($add)){
				//if anything is not set, we need to have default values inserted.
				if($bgcolor == '') $bgcolor='#ffffff';
				if($text == '') $text = '#000000';
				if($link == '') $link = '#555555';
				if($border == '') $border = '#000099';
				if($bg1 == '') $bg1 = '#ffffff';
				if($bg2 == '') $bg2 = '#dddddd';
				if($header_bg == '') $header_bg = '#6974b5';
				if($header_text == '') $header_text = '#330066';
				if($info_bg == '') $info_bg = '#6974b5';
				if($info_text == '') $info_text = '#ffffff';
				if($cat == '') $cat = '#bbbbbb';
				if($subcat == '') $subcat = '#eeeeee';
				if($font == '') $font = 'Arial';
				if($size == '') $size = '12px';
				if($width== '') $width = '80%';
				if($logo_path == '') $logo_path = 'logo.jpg';
				if($image_dir == '') $image_dir = 'images/default/';
				//update the database.
				$sql = "INSERT into $mysql_themes_table values(NULL,'$name','$bgcolor','$text','$link','$border','$bg1','$bg2','$header_bg','$header_text','$info_bg','$info_text','$cat','$subcat','$font','$size', '$width', '$logo_path','$image_dir')";
				$db->query($sql);
			}
			else{
				startTable("$lang_createtheme:", "left", "90%", 3);
					echo "<tr><td class=cat>$lang_desc:</td><td class=cat colspan=2>$lang_setting:</td></tr>";

					echo "<tr><td class=subcat>$lang_themename:</td><td class=back2 colspan=2><input type=text name=name></td></tr>";

					echo "<tr><td class=subcat>$lang_bg $lang_color:</td><td class=back2><input type=text name=bgcolor></td>";
					echo "<td bgcolor=".$row['bgcolor'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_text $lang_color:</td><td class=back2><input type=text name=text></td>";
					echo "<td bgcolor=".$row['text'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_link $lang_color:</td><td class=back2><input type=text name=link></td>";
					echo "<td bgcolor=".$row['link'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_tableborder $lang_color:</td><td class=back2><input type=text name=border></td>";
					echo "<td bgcolor=".$row['table_border'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_bg $lang_color 1:</td><td class=back2><input type=text name=bg1></td>";
					echo "<td bgcolor=".$row['bg1'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_bg $lang_color 2:</td><td class=back2><input type=text name=bg2></td>";
					echo "<td bgcolor=".$row['bg2'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_header $lang_bg $lang_color:</td><td class=back2><input type=text name=header_bg></td>";
					echo "<td bgcolor=".$row['header_bg'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_header $lang_text $lang_color:</td><td class=back2><input type=text name=header_text ></td>";
					echo "<td bgcolor=".$row['header_text'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_info $lang_bg $lang_color:</td><td class=back2><input type=text name=info_bg ></td>";
					echo "<td bgcolor=".$row['info_bg'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_info $lang_text $lang_color:</td><td class=back2><input type=text name=info_text ></td>";
					echo "<td bgcolor=".$row['info_text'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_category $lang_color:</td><td class=back2><input type=text name=cat></td>";
					echo "<td bgcolor=".$row['category'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_subcat $lang_color:</td><td class=back2><input type=text name=subcat></td>";
					echo "<td bgcolor=".$row['subcategory'].">&nbsp;</td></tr>";

					echo "<tr><td class=subcat>$lang_font:</td><td class=back2 colspan=2><input type=text name=font></td></tr>";

					echo "<tr><td class=subcat>$lang_font $lang_size:</td><td class=back2 colspan=2><input type=text name=size></td></tr>";

					echo "<tr><td class=subcat>$lang_tablewidth</td><td class=back2 colspan=2><input type=text name=width></td></tr>";

					echo "<tr><td class=subcat>$lang_hdlogo:</td><td class=back2 colspan=2><input type=text name=logo_path></td></tr>";

					echo "<tr><td class=subcat>$lang_imagesdir:</td><td class=back2 colspan=2><input type=text name=image_dir></td></tr>";


				echo "</td></tr></table><tr><td class=back><br>";
				echo "<input type=hidden name=t value=theme>";
				echo "<input type=hidden name=details value=add>";
				echo "<input type=hidden name=modify value=yes>";
				echo "<center><input type=\"submit\" name=\"add\" value=\"$lang_addtheme\"></form></center><br>";
				
			}

		}	//end if $details ==add

		
		if($details == 'change'){
			
			if(isset($update)){
				//perform the database queries
				$sql = "update $mysql_themes_table set bgcolor='$bgcolor', text='$text', link='$link', table_border='$border', bg1='$bg1', bg2='$bg2', header_bg='$header_bg', header_text='$header_text', info_bg='$info_bg', info_text='$info_text', category='$cat', subcategory='$subcat', font='$font', font_size='$size', width='$width', logo_path='$logo_path', image_dir='$image_dir' where id=$tid";

				$db->query($sql);
				//refresh the page so the changes take effect.
				echo "<meta HTTP-EQUIV=\"refresh\" content=\"0; url=".$admin_site_url."/control.php?tid=$tid&modify=1&t=theme&details=change\">"; 

			}	//end if $update
		
			//get the details of the theme.
			$sql = "select * from $mysql_themes_table where id=$tid";
			$result = $db->query($sql);
			$row = $db->fetch_array($result);

			//modify the existing theme
			//create that nifty table that will help us do the editing.
			startTable("$lang_theme $lang_settings", "left", "90%", 3);
				echo "<tr><td class=cat>$lang_desc:</td><td class=cat colspan=2>Setting:</td></tr>";
				echo "<tr><td class=subcat>$lang_themename:</td><td class=back2 colspan=2><b>".$row['name']."</b></td></tr>";
				echo "<tr><td class=subcat>$lang_bg $lang_color:</td><td class=back2><input type=text name=bgcolor value='".$row['bgcolor']."'></td>";
				echo "<td bgcolor=".$row['bgcolor'].">&nbsp;</td></tr>";
				echo "<tr><td class=subcat>$lang_text $lang_color:</td><td class=back2><input type=text name=text value='".$row['text']."'></td>";
				echo "<td bgcolor=".$row['text'].">&nbsp;</td></tr>";
				echo "<tr><td class=subcat>$lang_link $lang_color:</td><td class=back2><input type=text name=link value='".$row['link']."'></td>";
				echo "<td bgcolor=".$row['link'].">&nbsp;</td></tr>";
				echo "<tr><td class=subcat>$lang_tableborder $lang_color:</td><td class=back2><input type=text name=border value='".$row['table_border']."'></td>";
				echo "<td bgcolor=".$row['table_border'].">&nbsp;</td></tr>";
				echo "<tr><td class=subcat>$lang_bg $lang_color 1:</td><td class=back2><input type=text name=bg1 value='".$row['bg1']."'></td>";
				echo "<td bgcolor=".$row['bg1'].">&nbsp;</td></tr>";
				echo "<tr><td class=subcat>$lang_bg $lang_color 2:</td><td class=back2><input type=text name=bg2 value='".$row['bg2']."'></td>";
				echo "<td bgcolor=".$row['bg2'].">&nbsp;</td></tr>";
				echo "<tr><td class=subcat>$lang_header $lang_bg $lang_color:</td><td class=back2><input type=text name=header_bg value='".$row['header_bg']."'></td>";
				echo "<td bgcolor=".$row['header_bg'].">&nbsp;</td></tr>";
				echo "<tr><td class=subcat>$lang_header $lang_text $lang_color:</td><td class=back2><input type=text name=header_text value='".$row['header_text']."'></td>";
				echo "<td bgcolor=".$row['header_text'].">&nbsp;</td></tr>";

				echo "<tr><td class=subcat>$lang_info $lang_bg $lang_color:</td><td class=back2><input type=text name=info_bg value='".$row['info_bg']."'></td>";
				echo "<td bgcolor=".$row['info_bg'].">&nbsp;</td></tr>";

				echo "<tr><td class=subcat>$lang_info $lang_text $lang_color:</td><td class=back2><input type=text name=info_text value='".$row['info_text']."'></td>";
				echo "<td bgcolor=".$row['info_text'].">&nbsp;</td></tr>";

				echo "<tr><td class=subcat>$lang_category $lang_color:</td><td class=back2><input type=text name=cat value='".$row['category']."'></td>";
				echo "<td bgcolor=".$row['category'].">&nbsp;</td></tr>";

				echo "<tr><td class=subcat>$lang_subcat $lang_color:</td><td class=back2><input type=text name=subcat value='".$row['subcategory']."'></td>";
				echo "<td bgcolor=".$row['subcategory'].">&nbsp;</td></tr>";

				echo "<tr><td class=subcat>$lang_font:</td><td class=back2 colspan=2><input type=text name=font value='".$row['font']."'></td></tr>";

				echo "<tr><td class=subcat>$lang_font $lang_size:</td><td class=back2 colspan=2><input type=text name=size value='".$row['font_size']."'></td></tr>";

				echo "<tr><td class=subcat>$lang_tablewidth:</td><td class=back2 colspan=2><input type=text name=width value='".$row['width']."'></td></tr>";

				echo "<tr><td class=subcat>$lang_hdlogo:</td><td class=back2 colspan=2><input type=text name=logo_path value='".$row['logo_path']."'></td></tr>";

				echo "<tr><td class=subcat>$lang_imagesdir:</td><td class=back2 colspan=2><input type=text name=image_dir value='".$row['image_dir']."'></td></tr>";

			endTable();
			echo "<input type=hidden name=details value=change>";
			echo "<input type=hidden name=modify value=yes>";
			echo "<center><input type=\"submit\" name=\"update\" value=\"$lang_submitchanges\"></form></center><br>";

		}	//end if $details
		else{
			$here = 1;
			//delete themes
			for($i=0; $i<$count; $i++){
				$del = "del" . $i;
				$theme_name = "name" . $i;
				$tid = "tid" . $i;
				$old_name = "oldtheme" . $i;

				if($$del == 'on'){
					if($$theme_name == $default_theme){
						printerror("$lang_themedelerror");
						echo "<br>";
					}
					else{
						$sql = "delete from $mysql_themes_table where name='".$$theme_name."'";
						$db->query($sql);
						//modify users themes to reflect changes.
						$sql = "update $mysql_users_table set theme='default' where theme='".$$theme_name."'";
						$db->query($sql);
					}
				}	//end delete if statement
				else{
					//special case:  editing the default theme name
					//must update the name of the default theme in the settings table.
					if($$old_name == $default_theme){
						$sql = "UPDATE $mysql_settings_table set default_theme='".$$theme_name."'";
						$db->query($sql);
					}
					//now update the themes table
					$sql = "update $mysql_themes_table set name='".$$theme_name."' where id=".$$tid;
					$db->query($sql);
					$sql = "update $mysql_users_table set theme='".$$theme_name."' where theme='".$$old_name."'";
					$db->query($sql);
				}	//end else
				
			}	//end for loop
		}	//end else	
	}	//end if
	else{

		echo "<form action=\"control.php?t=theme\" method=get>";
			startTable("$lang_themes", "left", "60%", 2);
				echo "<tr><td class=cat width=20>$lang_delete?</td>
					  <td class=cat width=100%>$lang_themename:</td></tr>";
			
				$sql = "select id, name from $mysql_themes_table";
				$result = $db->query($sql);
				$i=0;
				while($row = $db->fetch_array($result)){
					echo "<tr><td class=subcat align=center><input class=box type=checkbox name=del".$i."></td>";
					echo "<td class=back2><input type=text name=name".$i." value='".$row['name']."'>";
					echo "<input type=hidden name=tid".$i." value=".$row['id'].">";
					echo "<input type=hidden name=oldtheme".$i." value='".$row['name']."'>";
					echo "&nbsp;&nbsp;&nbsp;<a href=\"control.php?tid=".$row['id']."&modify=yes&t=theme&details=change\">$lang_modify</a></td></tr>";
					$i++;
				}
			echo "<tr><td class=cat colspan=2><a href=\"control.php?t=theme&modify=yes&details=add\">$lang_addtheme</a></td></tr>";
			echo "<input type=hidden name=count value=".$i.">";

			endTable();
				
			echo "<center><input type=\"submit\" name=\"modify\" value=\"$lang_submitchanges\"></form></center><br>";
	}	//end else


if($here == 1)
	echo "</td></tr></table>";

?>