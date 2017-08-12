<?php

require_once "common/style.php";

if(isset($register)){

	if($HTTP_POST_VARS[user] == ''){
		printerror("$lang_usernotblank");
		exit;
	}

	//check username first
	$sql = "SELECT user_name from $mysql_users_table where user_name='$user'";
	$result = $db->query($sql);
	$numrows = $db->num_rows($result);

	if($numrows > 0){
		printerror($lang_usernametaken);
		exit;
	}

	//check passwords
	if($HTTP_POST_VARS[password] != $HTTP_POST_VARS[password2]){
		printerror($lang_passwordsnotmatch);
		exit;
	}
	else{
		$password = md5($HTTP_POST_VARS[password]);
	}

	//create user account, but do not activate it.
        $sql = "INSERT into $mysql_users_table values(NULL, '$HTTP_POST_VARS[first]', '$HTTP_POST_VARS[last]', '$HTTP_POST_VARS[user]', '$HTTP_POST_VARS[email]', NULL, '$password', '$HTTP_POST_VARS[office]', '$HTTP_POST_VARS[phone]', 0, 0, 0,'default', NULL, NULL, NULL, 0, '$default_language', 0)";
	$db->query($sql);
	$uid = $db->insert_id();

	//send notification to admin for approval
	$sql = "SELECT template from $mysql_templates_table where name='email_new_user'";
	$result = $db->query($sql);
	$template = $db->fetch_array($result);
	$template=str_replace("\\'","'",$template[0]);
	eval("\$email_msg = \"$template\";");

	if($enable_smtp == 'lin'){
		sendmail($admin_email, $helpdesk_name, $HTTP_POST_VARS[email], $uid, $email_msg);
	}
	if($enable_smtp == 'win'){
		mail($admin_email, "$lang_registerforaccount", $email_msg, "From: ".$helpdesk_name ."<".$HTTP_POST_VARS[email].">\nReply-To: ".$HTTP_POST_VARS[email]."\n");
	}
	//no other options...if enable_smtp is set to anything else, the email will not get sent.
	
	//print out the message so the user knows he/she is awaiting approval
	echo "$lang_messagetoadmin<br>";
	exit;
}

echo "<form method=post>";

startTable("$lang_register", "left", 80, 1);
	echo "<tr><td class=back><br>";


		startTable("$lang_register - $lang_required", "left", 80, 2);
			echo "<tr><td width=27% class=back2>$lang_username: </td>
				<td class=back> <input type=text size=30 name=user></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_firstname: </td>
				<td class=back> <input type=text size=30 name=first></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_lastname: </td>
				<td class=back><input type=text size=30 name=last></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_emailaddy: </td>
				<td class=back><input type=text size=30 name=email></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_password: </td>
				<td class=back> <input type=password size=30 name=password></td></tr>\n";
			echo "<tr><td width=27% class=back2>$lang_password $lang_again: </td>
				<td class=back> <input type=password size=30 name=password2></td></tr>\n";
			echo "<tr><td class=back2 align=left width=27%> $lang_office: </td>
				<td class=back><input type=text size=30 name=office></td></tr>";
			echo "<tr><td class=back2 align=left width=27%> $lang_phoneext: </td>
				<td class=back><input type=text size=30 name=phone></td></tr>";
		endTable();

	echo "<div align=\"center\"><input type=submit name=register value='$lang_register'></div>";
	echo "</form>";
	echo "</td></tr>";

endTable();



?>
