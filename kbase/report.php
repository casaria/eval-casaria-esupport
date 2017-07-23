<?php

/***************************************************************************************************
**
**	file:	report.php
**
**		This file takes care of reporting the broken knowledge base entry to the admins.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	02/04/02
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

if($enable_smtp == 'off'){
	printError("$lang_smtpofferror");
	exit;
}


if(isset($report)){
	//gather the information so we can send it along with the user entered info to the admins.
	$sql = "SELECT * from $mysql_kbase_table where id=$id";
	$result = $db->query($sql);
	$row = $db->fetch_array($result);

	if($user_email == ''){
		$user_email = "$lang_nobody";
	}

	//$msg .= "/n/n/n http://$supporter_site_url/index.php?t=kbase&act=kedit&id=$id /n/n";
	$sql = "SELECT template from $mysql_templates_table where name='email_kbase_report'";
	$result = $db->query($sql);
	$template = $db->fetch_array($result);
	$template=str_replace("\\'","'",$template[0]);
	eval("\$msg = \"$template\";");
	$msg = stripslashes($msg);

	//send the information to the admins.
	if($enable_smtp == 'lin'){
			sendmail($admin_email, $helpdesk_name, $user_email, $id, $msg);
			$flag = 1;
		}
	if($enable_smtp == 'win'){
			mail($admin_email, "$lang_kbase $lang_entry #$id", $msg, "From: ".$helpdesk_name ."<".$user_email.">\nReply-To: ".$user_email."\n");
			$flag = 1;
		}
	//if the system is unable to send the email, let the user know and provide a link to the admins email.
	if($enable_smtp == 'Off'){
		printError("$lang_smtpofferror");
	}

	//if sent, let the user know.
	if($flag == 1){
		printSuccess("$lang_reportsuccess");
	}


}
else{
	//begin the display so the user knows where to input his/her problem with the entry
	startTable("$lang_report $lang_entry ($lang_id #$id)", "center");
	echo "<form method=post>";

		echo "<tr><td class=cat> <br>$lang_describeproblem<br><br>";
		echo "</td></tr>";
		echo "<tr><td class=back2><b>$lang_emailaddy: </b><input type=text name=user_email>";
		echo "<font size=1> ( $lang_emailaddy2 )</font></td></tr>";

		echo "<tr><td class=back2><br><b>$lang_briefdesc:</b></td></tr>";
		echo "<tr><td align=center class=back><textarea name=msg cols=100% rows=10></textarea><br><br>";
		echo "<input type=submit name=report value=\"$lang_reportthis\"></td></tr>";
	echo "</form>";
	endTable();


}




?>