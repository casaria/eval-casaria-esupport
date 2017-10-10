<?php

/**************************************************************************************************
**	file:	control.php
**
**		This is the control panel.  All settings to the helpdesk software are done through here.
**	This file takes care of updating the setting table and the interface to do so.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	11/19/01
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

//since this isn't called from the index.php file, we have to make sure that the user is logged in.
if($SERVER_PORT == 80 && (!isset($cookie_name) || $cookie_name == '') && $enable_ssl == 'On'){
	$site = eregi_replace("http", "https", $admin_site_url);
	header("Location: $site");
}
require_once "../lang/$default_language.lang.php";
require "../common/login.php";

$language = getLanguage($cookie_name);
if($language == '')
	require_once "../lang/$default_language.lang.php";
else
	require_once "../lang/$language.lang.php";


$time_offset = getTimeOffset($cookie_name);

if(isset($cookie_name)){
	//update the lastactive time in the database.
	$sql = "UPDATE $mysql_users_table set lastactive='".time()."' where user_name='".$cookie_name."'";
	$db->query($sql);
}

$last_active = getLastActiveTime($cookie_name);

	if(isset($change_settings) && $t=='settings'){
		if($interval == '' || !isset($interval)){
			$interval = $rating_interval;
		}

		$sql = "update $mysql_settings_table set admin_site_url='$admin_url', admin_email='$email', name='$helpdesk',
				a_name='$aname', a_street1='$astreet1',a_street2='$astreet2', a_city='$acity', a_state='$astate',
				a_zip='$azip', users_per='$users_per', announcements_per='$announcements_per', ratings='$ratings', stats='$stats',
				supporter_site_url='$supporter_url', ticket_interval='$interval', socket='$socket', forum='$forum', 
				forum_site='$forum_site', smtp='$smtp', sendmail_path='$mail_path', on_off='$on_off', reason='$reason',
				whosonline='$whosonline', default_theme='$theme_default', time_tracking='$time_tracking', kbase='$kbase',
				pubpriv='$public', default_language='$langfile', tattachments='$tattachments', kattachments='$kattachments',
				kpurge='$kpurge_level', uattachments='$uattachments',
				GMTPrivacyStart='$GMTprivacy', PrivacyDuration='$PrivacyDuration'";

		$db->query($sql);
		$location = $admin_site_url . "/control.php";
		header("Location: $location");
	}

	if(isset($change_settings) && $t=='pager'){
		$sql = "update $mysql_settings_table set pager='$pager', pager_rank_low='$low_rank'";
		$db->query($sql);
		$location = $admin_site_url . "/control.php";
		header("Location: $location");
	}

	if(isset($change_settings) && $t=='upgrade'){
		$up = explode(";", $upgrade);
		for($i=0;$i<sizeof($up);$i++) {
			$up[$i]=stripslashes($up[$i]);
			if($up[$i] != "") {
				$db->query($up[$i]);
			}
		}
		$location = $admin_site_url . "/control.php?t=success";
		header("Location: $location");
	}

	if(isset($change_settings) && $t='kbase'){

		//this is where changes to the knowledge base go.

	}


require_once "../common/style.php";		//required way down here so headers don't get sent yet.
?>

<BODY class=body>
<TABLE class=border cellSpacing=0 cellPadding=0 width="<?php echo $theme['width']; ?>" align=center 
border=0>
  <TBODY> 
  <TR> 
    <TD> 
      <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
        <TBODY> 
        <TR> 
          <TD colspan=1 class=hf align=left>&nbsp;</TD>
		  <tr><td colspan=1 class=cat align=center>
			<a href="control.php?t=settings"><?php echo $lang_settings; ?></a> - 
			<a href="control.php?t=topts"><?php echo $lang_ticketoptions; ?></a> -
			<a href="control.php?t=users"><?php echo $lang_useroptions; ?></a> -
			<a href="control.php?t=theme"><?php echo $lang_themes; ?></a> - 
			<a href="control.php?t=pager"><?php echo $lang_pagergateway; ?><a/> 
			
			<?php
				if($enable_kbase == 'On'){
					echo '- <a href="control.php?t=kbase">'.$lang_kbase.'</a> ';
				}
			?>
			<br>
			<?php
				if($enable_tattachments == 'On' || $enable_kattachments == 'On'){
					echo '<a href="control.php?t=attachments">'.$lang_attachments.'</a> -';
				}
			?>
			<a href="control.php?t=templates"><?php echo $lang_templates; ?></a> -
			<a href="control.php?t=upgrade"><?php echo $lang_upgrade; ?></a></td></tr>
        </TR>
        <TR> 
          <TD class=back> 
            <TABLE width="100%">
              <TBODY> 
              <TR> 
                <TD class=back vAlign=top align=right></TD>
              </TR>
			  
              </TBODY> 
            </TABLE>
            <BR>
            <TABLE width="100%" cellspacing=2 cellpadding=6 align=center border=0>
					
				<?php


					//this is where all of the code for the options goes.
					

if($t == 'settings'){

echo "<form action=control.php method=get>";
echo "<tr><td width=60% height=40 class=cat> $lang_setting: </td> <td class=cat> $lang_value: </td></tr>";

		//helpdesk name
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_hdname: </td>";
			echo "<td class=back2> <input type=text size=40 name=helpdesk value='$helpdesk_name'></td>";
		echo "</tr>";

		//address_name
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_aname: </td>";
			echo "<td class=back2> <input type=text size=40 name=aname value='$address_name'></td>";
		echo "</tr>";
		
		//street1
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_street1: </td>";
			echo "<td class=back2> <input type=text size=40 name=astreet1 value='$address_street1'></td>";
		echo "</tr>";
		
		//street2
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_street2: </td>";
			echo "<td class=back2> <input type=text size=40 name=astreet2 value='$address_street2'></td>";
		echo "</tr>";
		
		//city
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_city: </td>";
			echo "<td class=back2> <input type=text size=40 name=acity value='$address_city'></td>";
		echo "</tr>";
		
		//state
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_state: </td>";
			echo "<td class=back2> <input type=text size=40 name=astate value='$address_state'></td>";
		echo "</tr>";
		
		//zip
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_zip: </td>";
			echo "<td class=back2> <input type=text size=40 name=azip value='$address_zip'></td>";
		echo "</tr>";
				
		

		//full site url
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_supporterurl: </td>";
			echo "<td class=back2> <input type=text size=40 name=supporter_url ";
				if(!isset($supporter_site_url))
					echo "value=\"http://\"></td>";
				else
					echo "value='$supporter_site_url'></td>";
		echo "</tr>";

		//full admin site url
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_adminurl: </td>";
			echo "<td class=back2> <input type=text size=40 name=admin_url ";
				if(!isset($admin_site_url))
					echo "value=\"http://\"></td>";
				else
					echo "value='$admin_site_url'></td>";
		echo "</tr>";

		//administrator email
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_adminemail: </td>";
			echo "<td class=back2> <input type=text name=email value='$admin_email'></td>";
		echo "</tr>";

		//helpdesk public/private
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_pubpriv: </td>";
			echo "<td class=back2> <select name=public>
					<option value='Public' "; if($pubpriv == 'Public') echo "selected"; echo ">$lang_public</option>
					<option value='Private'  "; if($pubpriv == 'Private') echo "selected"; echo ">$lang_private</option>
				</select></td>";
		echo "</tr>";

		//helpdesk on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_hdonoff: </td>";
			echo "<td class=back2> <select name=on_off>
					<option value='On' "; if($enable_helpdesk == '$lang_on') echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_helpdesk == '$lang_off') echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//helpdesk on/off reason
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_offreason: </td>";
			echo "<td class=back2> <textarea name=reason rows=5 cols=50>$on_off_reason</textarea>
					</td>";
		echo "</tr>";

		// default theme selection
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_defaulttheme: </td>";
			echo "<td class=back2> <select name=theme_default>";
				createThemeMenu(1);
			echo "</select></td>";
		echo "</tr>";

		// default language selection
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_defaultlanguage: </td>";
			echo "<td class=back2>";
				createLanguageMenu(2);
			echo "</td>";
		echo "</tr>";

		//forum on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_Forum: </td>";
			echo "<td class=back2> <select name=forum>
					<option value='On' "; if($enable_forum == 'On') echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_forum == 'Off') echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//forum site url
			if($forum_site_url == ''){
				$forum_site_url = "http://";
			}
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_forumurl: </td>";
				echo "<td class=back2>
						<input type=text size=40 name=forum_site value=".$forum_site_url.">
					</select></td>";
			echo "</tr>";

		//list limit
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_usersperpage: </td>";
			echo "<td class=back2> <input size=3 type=text name=users_per ";
				if($users_limit == '')
					echo "value=5></td>";
				else
					echo "value='$users_limit'></td>";
		echo "</tr>";

		//announcement limit
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_numannouncements: </td>";
			echo "<td class=back2> <input size=3 type=text name=announcements_per ";
			if($announcements_limit == '')
					echo "value=5></td>";
				else
					echo "value='$announcements_limit'></td>";
		
		/*
		//allow ticket ratings?
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_ticketratings: </td>";
			echo "<td class=back2> <select name=ratings>
					<option value='$lang_on' "; if($enable_ratings == "$lang_on") echo "selected"; echo ">$lang_on</option>
					<option value='$lang_off'  "; if($enable_ratings == "$lang_off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//ticket intervals
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_ticketinterval: </td>";
			echo "<td class=back2>
					<input type=text size=3 name=interval value=".$rating_interval.">
				</select></td>";
		echo "</tr>";
		*/

		//time_tracking status
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_timetracking $lang_status: </td>";
			echo "<td class=back2> <select name=time_tracking>
					<option value='On' "; if($enable_time_tracking == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_time_tracking == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";
		
		//stats status
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_stats $lang_status: </td>";
			echo "<td class=back2> <select name=stats>
					<option value='On' "; if($enable_stats == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_stats == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//ssl on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_ssl $lang_status: </td>";
			echo "<td class=back2> <select name=socket>
					<option value='On' "; if($enable_ssl == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_ssl == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//smtp on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_smtp $lang_status: </td>";
			echo "<td class=back2> <select name=smtp>
                                        <option value='win' "; if($enable_smtp == "win") echo "selected"; echo ">PHP Config</option>
                                        <option value='lin' "; if($enable_smtp == "lin") echo "selected"; echo ">Sendmail</option>
                                        <option value='Off' "; if($enable_smtp == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//sendmail path
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_sendmailpath: &nbsp;&nbsp;&nbsp;<font size=1>$lang_sendmailex</font></td>";
				echo "<td class=back2>
						<input type=text size=40 name=mail_path value=".$sendmail_path.">
					</select></td>";
			echo "</tr>";

		//pager privacy
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_privacyEnable:</td>";
				echo "<td class=back2> <select name=privacyonoff>
				  <option value='On' "; if($enable_privacy == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_privacy == "Off") echo "selected"; echo ">$lang_off</option>
					</select></td>";
			echo "</tr>";

		//pager privacy GMTPrivacy 
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_GMTprivacy:</td>";
			echo "<td class=back2><input size=2 type=text name=GMTprivacy ";
			if(!isset($GMTprivacyHour ))
					echo "value=8>";
				else
					echo "value=".$GMTprivacyHour.">";				
				echo ":00 hrs [GMT]</td>";
			echo "</tr>";
	
	
	
	

		//knowledge base on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_kbase $lang_status: </td>";
			echo "<td class=back2> <select name=kbase>
					<option value='On' "; if($enable_kbase == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_kbase == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//ticket attachments on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_ticket $lang_attachments: </td>";
			echo "<td class=back2> <select name=tattachments>
					<option value='On' "; if($enable_tattachments == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_tattachments == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//user ticket attachments on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_user $lang_attachments: </td>";
			echo "<td class=back2> <select name=uattachments>
					<option value='On' "; if($enable_uattachments == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_uattachments == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//kbase attacments on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_kbase $lang_attachments: </td>";
			echo "<td class=back2> <select name=kattachments>
					<option value='On' "; if($enable_kattachments == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_kattachments == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//Kbase Purge Level
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_kbasepurgelevel: </td>\n";
				echo "<td class=back2> <select name=kpurge_level>\n";
						listPurgeLevels($kpurge);
				echo "</select></td>\n";
			echo "</tr>";

		//whois online on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_whosonline $lang_status: </td>";
			echo "<td class=back2> <select name=whosonline>
					<option value='On' "; if($enable_whosonline == "On") echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_whosonline == "Off") echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		echo "<input type=hidden name=t value=settings>";
		echo '<tr><td colspan=2 class=back align=center><input type=submit name=change_settings value="'.$lang_submitchanges.'"></form></td></tr>';

		echo "</TABLE></TD></TR>";

}

if($t == 'pager'){

echo "<form action=control.php method=get>";
echo "<tr><td width=60% height=40 class=cat> $lang_setting: </td> <td class=cat> $lang_value: </td></tr>";

		//Pager Gateway on/off
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_pagergateway: </td>";
			echo "<td class=back2> <select name=pager>
					<option value='On' "; if($enable_pager == 'On') echo "selected"; echo ">$lang_on</option>
					<option value='Off'  "; if($enable_pager == 'Off') echo "selected"; echo ">$lang_off</option>
				</select></td>";
		echo "</tr>";

		//Default Ticket Priority
		echo "<tr>";
			echo "<td width=60% class=back2>$lang_lowestpri: </td>\n";
			echo "<td class=back2> <select name=low_rank>\n";
					listPriorities();
			echo "</select></td>\n";
		echo "</tr>";

		echo '<input type=hidden name=t value=pager>';
		echo '<tr><td colspan=2 class=back align=center><input type=submit name=change_settings value="'.$lang_submitchanges.'"></form></td></tr>';

		echo "</TABLE></TD></TR>";		

}


if($t == 'users'){
	require "users.php";
}

if($t == 'theme'){
	require "themes.php";	
}	


if($t == 'success'){

	echo '<tr><td class=cat align=center><b>'.$lang_upgradesuccess.'</b></td></tr>';
	echo "</TABLE><br><br></TD></TR>";		

}


if($t == 'upgrade'){
echo "<form action=control.php method=get>";
?>

	<TABLE class=border cellSpacing=0 cellPadding=0 width="70%" align=center 
	border=0> 
	  <TR> 
		<TD> 
		  <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
			
			<TR> 
			  <TD class=info align=left><b><?php echo $lang_upgrade; ?>:</b></TD>
			  <tr><td colspan=1 class=cat><b><?php echo $lang_upgradesql; ?>:</b><center>
				<textarea name=upgrade cols=80% rows=10></textarea></center>
				<input type=hidden name=t value=upgrade><br>
				<center><input type=submit name=change_settings value="<?php echo $lang_submitchanges; ?>"></form></center>
			</td></tr>
		</tr>
		</table>
		</td></tr>
		</table>
	<br><br>


<?php
		
}

if($t == 'kbase'){

	//all of the options for the knowledge base will go in here.
	//including updating questions/answers, creating new ones,

	echo "<table width=100% border=0><tr valign=top><td width=23%>";
		startTable("$lang_options", "left");
		//create the left hand menu
			echo "<tr><td class=back2><LI> <a href=\"control.php?t=kbase&act=plat\">$lang_platform $lang_options</a>";
			echo "<LI> <a href=\"control.php?t=kbase&act=cate\">$lang_category $lang_options</a>";
			echo "<LI> <a href=\"control.php?t=kbase&act=kadd\">$lang_addtokb</a>";
			echo "<LI> <a href=\"control.php?t=kbase&act=kedit\">$lang_editkb</a>";
			echo "</td></tr>";
		endTable();
	echo "</td><td class=back>";


	switch($act){

		case('plat'):
			require "../kbase/platforms.php";
			break;

		case('cate'):
			require "../kbase/categories.php";
			break;

		case('kadd'):
			require "../kbase/add.php";
			break;

		case('kedit'):
			require "../kbase/edit.php";
			break;

		case('kdel'):
			require "../kbase/delete.php";
			break;

		default:
			startTable("$lang_chooseoption", "center");
				echo "<tr><td class=back> &nbsp; </td></tr>";
			endTable();
			break;
	
	}

	echo "</td></tr>";


			//all table info, 


	//echo '<input type=hidden name=t value=kbase>';
	//echo '<tr><td colspan=2 class=back align=center><input type=submit name=change_settings value="Submit Changes"></form></td></tr>';

	echo "</TABLE><br></TD></TR>";		

}

if($t == "topts"){

	echo "<table width=100% border=0><tr valign=top><td width=23%>";
		startTable("$lang_ticket $lang_options", "left");
		//create the left hand menu
			echo "<tr><td class=back2><LI> <a href=\"control.php?t=topts&act=tcat\">$lang_ticket $lang_categories</a>";
			echo "<LI> <a href=\"control.php?t=topts&act=tpri\">$lang_ticket $lang_priorities</a>";
			echo "<LI> <a href=\"control.php?t=topts&act=tsta\">$lang_ticket $lang_status</a>";
			echo "<LI> <a href=\"control.php?t=topts&act=tpla\">$lang_ticket $lang_platforms</a>";
			echo "</td></tr>";
		endTable();
	echo "</td><td class=back>";

	switch($act){

		case('tpla'):
			require "../admin/platforms.php";
			break;

		case('tcat'):
			require "../admin/tcategories.php";
			break;

		case('tsta'):
			require "../admin/tstatus.php";
			break;

		case('tpri'):
			require "../admin/tpriorities.php";
			break;

		default:
			startTable("$lang_chooseoption", "center");
				echo "<tr><td class=back> &nbsp; </td></tr>";
			endTable();
			break;
	
	}

	echo "</table></td></tr>";

}

if($t == 'templates'){

	if(isset($temp)){
		//update the database.
		$template_update = addslashes($template_update);
		//echo $template_update;
		$sql = "UPDATE $mysql_templates_table set template=\"$template_update\" where name='$tpl'";
		$db->query($sql);
		unset($tpl);
	}

	if($delete_tpl == 'Delete'){
		$sql = "DELETE from $mysql_templates_table where name='$tpl'";
		$db->query($sql);
		unset($tpl);
	}

	if(isset($create_tpl)){
		$sql = "INSERT into $mysql_templates_table VALUES(NULL, '$tpl', '')";
		$db->query($sql);
	}

	if(isset($restore_tpl)){
		require_once "templates_restore.php";
		$restored = 1;
		unset($tpl);
	}


	?>
	<script language="JavaScript">
	<!--
		function MM_jumpMenu(targ,selObj,restore){ //v3.0
		  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
		  if (restore) selObj.selectedIndex=0;
			}
	//--></script>
	<?php 
	if($tpl == ''){

		echo "
			<TABLE class=border cellSpacing=0 cellPadding=0 width=\"70%\" align=center 
			border=0> 
			  <TR> 
				<TD> 
				  <TABLE cellSpacing=1 cellPadding=5 width=\"100%\" border=0>
					
					<TR> 
					  <TD class=info align=left><b> $lang_templates </b></TD>";
						echo "<form name=temp2 action=control.php?t=templates method=post>";
						echo "<tr><td colspan=1 class=cat>";
						echo "<input type=text size=30 maxlength=60 name=tpl> &nbsp;&nbsp;&nbsp;";
						echo "<input type=submit name=create_tpl value=\"$lang_newtpl\">";
						echo "</td></tr>";
						echo "</form>";
					  
					  
					  echo "<tr><td colspan=1 class=cat> ";
							echo "<form name=temp action=\"control.php?t=templates\" method=get>";
							echo "<SELECT name=\"tpl\">";
							echo "<option> $lang_selecttemplate </option>";
								createTemplateDropdown(); 
							echo "</SELECT>";
							echo "&nbsp;&nbsp;&nbsp;";
							echo "<input type=hidden name=t value=\"templates\">";
							echo "<input type=submit name=edit_tpl value=\"$lang_edit\">";
							echo "&nbsp;&nbsp;&nbsp;";
							echo "<input type=submit name=delete_tpl value=\"$lang_delete\">";
							echo "&nbsp;&nbsp;&nbsp;";
							echo "<input type=submit name=restore_tpl value=\"$lang_restoretemplates\">";
							
						?> <center>

					</td></tr>
					</form>
				</tr>
				</table>
				</td></tr>
				</table>
				<?php  
					if($restored == 1)
						echo "<div align=\"center\"><font color=\"green\"><b>$lang_restored</b></font></div>";
				?>
			<br><br>
			<?php

	}
	else{
		//code for editing the chosen template file.
	$sql = "SELECT * from $mysql_templates_table where name='$tpl'";
	$result = $db->query($sql);
	$template = $db->fetch_array($result);
	$template[template] = stripslashes(stripslashes($template[template]));
	$template[template] = htmlspecialchars($template[template]);

	echo "<form action=\"control.php?t=templates&tpl=$tpl\" method=post>";
	echo "<TABLE class=border cellSpacing=0 cellPadding=0 width=\"70%\" align=center 
		border=0> 
		  <TR> 
			<TD> 
			  <TABLE cellSpacing=1 cellPadding=5 width=\"100%\" border=0>
				
				<TR> 
				  <TD class=info align=left><b>";
					echo $lang_templates; 
					echo ":</b></TD>
				  <tr><td colspan=1 class=cat>";

				  echo "<center>
					<textarea name=template_update cols=80% rows=10>".$template[template]."</textarea></center>
					<input type=hidden name=temp value=update><br>
					<input type=hidden name=t value=templates>
					<center><input type=submit name=temp value=\"$lang_submitchanges\"></form></center>
				</td></tr>
			</tr>
			</table>
			</td></tr>
			</table>
		<br><br>";

	}		
}

if($t=='attachments'){
	require_once "attachments.php";
	/*if($act == '' || !isset($act)){
		echo "<form action=control.php method=get>";
		echo "<tr><td width=60% height=40 class=cat> $lang_setting: </td> <td class=cat> $lang_value: </td></tr>";

			//Ticket Attachments on/off
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_enabletattachments: </td>";
				echo "<td class=back2> <select name=tattachments>
						<option value='On' "; if($enable_tattachments == 'On') echo "selected"; echo ">$lang_on</option>
						<option value='Off'  "; if($enable_tattachments == 'Off') echo "selected"; echo ">$lang_off</option>
					</select></td>";
			echo "</tr>";

			//Kbase Attachments on/off
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_enablekattachments: </td>";
				echo "<td class=back2> <select name=kattachments>
						<option value='On' "; if($enable_kattachments == 'On') echo "selected"; echo ">$lang_on</option>
						<option value='Off'  "; if($enable_kattachments == 'Off') echo "selected"; echo ">$lang_off</option>
					</select></td>";
			echo "</tr>";

			//Kbase Purge Level
			echo "<tr>";
				echo "<td width=60% class=back2>$lang_kbasepurgelevel: </td>\n";
				echo "<td class=back2> <select name=kpurge_level>\n";
						listPurgeLevels($kpurge);
				echo "</select></td>\n";
			echo "</tr>";

			echo '<input type=hidden name=t value=attachments>';
			echo '<tr><td colspan=2 class=back align=center><input type=submit name=change_settings value="'.$lang_submitchanges.'"></form></td></tr>';

			echo "</TABLE></TD></TR>";
	}*/
}




if(!isset($t)){
	echo "</TABLE></TD></TR>";
}


?>

				
       <TR> 
          <TD class=hf align=center> 
            
            <?php
			echo '
			<div align="center">
			<A class=hf href="'.$supporter_site_url.'/index.php">'.$lang_home.'</A> |&nbsp;
			<A class=hf href="'.$admin_site_url.'/control.php">'.$lang_cp.'</A> &nbsp;|&nbsp;';
			if($enable_forum == 'On'){
				echo '<A class=hf href="'.$forum_site_url.'" target=_blank>'.$lang_forum.'</A> |&nbsp;';
			}
			
			echo '
			<A class=hf href="../common/logout.php';
				if($enable_ssl == 'On'){
					echo "?ssl=1";
				}
				echo '">'.$lang_logout.'</A>
			</div>';
			?>

          </TD>
        </TR>
		</TBODY> 
      </TABLE>
  </TR>
  </TBODY> 
</TABLE>
<?php

require "../common/footer.php";

?>
</BODY>
</HTML>

<?php

function listPriorities()
{
	global $mysql_tpriorities_table, $pager_rank_low, $db;

	$sql = "select rank, priority from $mysql_tpriorities_table order by rank asc";
	$result = $db->query($sql);
	while($row = $db->fetch_array($result)){
		echo "<OPTION value=".$row['rank']." "; 
			if($pager_rank_low == $row['rank']) echo "selected"; echo ">". $row['priority'] ."</OPTION>\n";
	}

}

function listPurgeLevels($purge)
{
	//these are static so we don't need any database queries;

	echo "<OPTION value='Always'";
		if($purge == 'Always')
			echo " selected";
	echo ">Always</OPTION>";
	echo "<OPTION value='Never'";
		if($purge == 'Never')
			echo " selected";
	echo ">Never</OPTION>";
	echo "<OPTION value='Prompt'";
		if($purge == 'Prompt')
			echo " selected";
	echo ">Prompt</OPTION>";


}

function createTemplateDropdown()
{
	global $mysql_templates_table, $db, $admin_site_url;

	$sql = "SELECT name from $mysql_templates_table order by name asc";
	$result = $db->query($sql);
	while( $row = $db->fetch_array($result)){
		echo "<option value=\"$row[name]\">$row[name]</option><br>";
	}

}

