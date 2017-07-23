<?php


$sql = "DROP TABLE IF EXISTS $mysql_templates_table";
$db->query($sql);

$db->query("CREATE TABLE $mysql_templates_table(
			id int(11)NOT NULL auto_increment,
			name varchar(60) DEFAULT '' NOT NULL,
			template text,
			primary key(id),
			unique(name)
   		  );");


$table1 = '<table border=\\\"1\\\" width=\\\"80%\\\">
 <tr>
  <td>
   <table border=\\\"0\\\" width=\\\"100%\\\">
    <tr>
     <td valign=\\\"top\\\" width=\\\"50%\\\">

$lang_ticket $lang_id2:  # $ticket[id] <br>
$lang_createdon:    $create_date <br>
$lang_Supporter:    $ticket[supporter] <br>
$lang_status:   $ticket[status] <br>
$lang_priority:   $ticket[priority] <br>

</td><td valign=\\\"top\\\">

$lang_user:   $ticket[user] <br>
$lang_office:   $ticket[office] <br>
$lang_phoneext:   $ticket[phone] <br>

</td></tr></table>
</td></tr></table>

<br>

<table border=\\\"1\\\" width=\\\"80%\\\"><tr><td>
<table border=\\\"0\\\" width=\\\"100%\\\">
 <tr>
  <td valign=\\\"top\\\" width=\\\"50%\\\">
$lang_platform:   $ticket[platform]
</td><td valign=\\\"top\\\" width=\\\"50%\\\">
$lang_category:   $ticket[category]
</td></tr><tr><td colspan=\\\"2\\\">

$lang_shortdesc:   $ticket[short]

</td></tr></table><br>
<table border=\\\"0\\\" width=\\\"100%\\\"><tr><td valign=\\\"top\\\">
$lang_desc: <br>     $ticket[description] <br>
</td></tr></table>
</td></tr></table>

<br>

<table border=\\\"1\\\" width=\\\"80%\\\">
 <tr>
  <td>
   <table border=\\\"0\\\" width=\\\"100%\\\">
    <tr>
     <td valign=\\\"top\\\">

$lang_ulog: <br>
$update_log

</td></tr></table>
</td></tr></table>


<br>

  $lang_notes:';

$sql = "insert into templates values(null, 'supporter_ticket_printable', '$table1')";
$db->query($sql);

$table2 = '$lang_ticket $lang_created:
$supporter_site_url/index.php?t=tupd&id=$ticket[id]

$lang_ticketcreatedby: $ticket[user]
$lang_shortdesc: $ticket[short]
$lang_priority: $ticket[priority]
';
$sql = "insert into templates values(null, 'email_group_page', '$table2')";
$db->query($sql);


$table3 = '$site_url/index.php?t=tinf&id=$id

$lang_username: $username
$lang_shortdesc: $short
$lang_desc: $description';

$sql = "insert into templates values(null, 'email_supporter_change', '$table3')";
$db->query($sql);


$table4 = '$lang_ticket #$id.
$lang_username: $username
$lang_shortdesc: $short
$lang_update:

$email_msg';

$sql = "insert into templates values(null, 'email_from_ticket', '$table4')";
$db->query($sql);

$table5 = '$msg


$supporter_site_url/index.php?t=kbase&act=kedit&id=$id


';
$sql = "insert into templates values(null, 'email_kbase_report', '$table5')";
$db->query($sql);


$table6 = '$lang_ticketclosed
$site_url/index.php?t=tinf&id=$id';
$sql = "insert into templates values(null, 'email_ticket_closed', '$table6')";
$db->query($sql);


$table7='<TABLE class=\\\"border\\\" cellSpacing=\\\"0\\\" cellPadding=\\\"0\\\" width=\\\"100%\\\" align=\\\"center\\\" border=\\\"0\\\">
 <tr>
  <td>
   <TABLE cellSpacing=\\\"1\\\" cellPadding=\\\"5\\\" width=\\\"100%\\\" border=\\\"0\\\">
    <tr>
     <td class=\\\"info\\\" colspan=\\\"1\\\" align=\\\"center\\\">
       <B>$lang_ticket $lang_information</B>
     </td>
    </tr>
    <tr>
     <td class=\\\"back\\\">
      <br>
<TABLE class=\\\"border\\\" cellSpacing=\\\"0\\\" cellPadding=\\\"0\\\" width=\\\"100%\\\" align=\\\"center\\\" border=\\\"0\\\">
 <tr>
  <td>
   <TABLE cellSpacing=1 cellPadding=5 width=\\\"100%\\\" border=0>
    <tr>
     <td class=\\\"info\\\" colspan=\\\"2\\\" align=\\\"left\\\">
      <B>$lang_ticket #$padded_id</B>
     </td>
     </tr>
     <tr>
     <td class=\\\"back2\\\" width=\\\"27%\\\"> $lang_ticket $lang_opened: </td>
     <td class=\\\"back\\\"> $ticket[create_date] </td>
    </tr>
    <tr><td class=\\\"back2\\\" width=\\\"27%\\\"> $lang_ticket $lang_category:
    </td>
    <td class=\\\"back\\\">$ticket[category]</td>
    </tr>
    <tr>
     <td class=\\\"back2\\\" width=\\\"27%\\\"> $lang_Supporter: </td>
     <td class=\\\"back\\\">$ticket[supporter]</td>
    </tr>
    <tr>
     <td class=\\\"back2\\\" width=\\\"27%\\\"> $lang_ticket $lang_status: </td>
     <td class=\\\"back\\\"><b>$ticket[status]</b></td>
   </tr>
   <tr>
    <td class=\\\"back2\\\" width=\\\"27%\\\"> $lang_lastupdate: </td>
    <td class=\\\"back\\\"> $ticket[lastupdate]</td>
   </tr>
  </table>
 </td>
</tr>
</table><br>

<TABLE class=\\\"border\\\" cellSpacing=\\\"0\\\" cellPadding=\\\"0\\\" width=\\\"100%\\\" align=\\\"center\\\" border=\\\"0\\\">
 <tr>
  <td>
   <TABLE cellSpacing=\\\"1\\\" cellPadding=\\\"5\\\" width=\\\"100%\\\" border=\\\"0\\\">
    <tr>
     <td class=\\\"info\\\" colspan=\\\"2\\\" align=\\\"left\\\">
      <B>$lang_ticket $lang_desc:</B></td>
    </tr>
    <tr>
     <td class=\\\"back2\\\" width=\\\"27%\\\"> $lang_shortdesc: </td>
     <td class=\\\"back\\\"> $ticket[short]</td>
    </tr>
    <tr>
     <td class=\\\"back2\\\" width=\\\"27%\\\"> $lang_desc: </td>
     <td class=\\\"back\\\"> $ticket[description] </td>
    </tr>
    <tr>
     <td class=\\\"back2\\\" width=\\\"27%\\\" valign=\\\"top\\\"> $lang_attachments:
     </td>
     <td class=\\\"back\\\">  $attachments
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<br>

<TABLE class=\\\"border\\\" cellSpacing=\\\"0\\\" cellPadding=\\\"0\\\" width=\\\"100%\\\" align=\\\"center\\\" border=\\\"0\\\">
 <tr>
  <td>
   <TABLE cellSpacing=\\\"1\\\" cellPadding=\\\"5\\\" width=\\\"100%\\\" border=\\\"0\\\">
    <tr>
     <td class=\\\"info\\\" colspan=\\\"2\\\" align=\\\"left\\\">
      <B>$lang_updates:</B></td>
    </tr>

   $user_update_log

  </table>
 </td>
</tr>
</table>

<br>

<TABLE class=\\\"border\\\" cellSpacing=\\\"0\\\" cellPadding=\\\"0\\\" width=\\\"100%\\\" align=\\\"center\\\" border=\\\"0\\\">
 <TR>
  <TD>
  <TABLE cellSpacing=\\\"1\\\" cellPadding=\\\"5\\\" width=\\\"100%\\\" border=\\\"0\\\">
   <TR>
    <TD class=\\\"info\\\" colspan=\\\"2\\\" align=\\\"left\\\">
<B>$lang_addupdatetoticket:</B>
    </td>
   </TR>

<form method=\\\"post\\\" enctype=\\\"multipart/form-data\\\"><tr><td class=\\\"back\\\" colspan=\\\"2\\\"><textarea name=\\\"email_msg\\\" cols=\\\"100%\\\" rows=\\\"4\\\"></textarea><br>
   $attachment_area
<tr><td class=\\\"back\\\" colspan=\\\"2\\\"><input type=\\\"submit\\\" name=\\\"send_mail\\\" value=\\\"Submit\\\"></td></tr>
                </table>
                        </td>
                        </tr>
                </table><br></td></tr>
                </table>
                        </td>
                        </tr>
                </table><br>';
$sql = "insert into templates values(null, 'user_ticket_info', '$table7')";
$db->query($sql);

$table8 = '$first $last ($user) $lang_hasregistered

$lang_formoreinfo:
$admin_site_url/control.php?t=users&act=uedit&id=$uid';

$sql = "insert into templates values(null, 'email_new_user', '$table8')";
$db->query($sql);


$table9 = '$lang_accountregistered

$lang_username: $user_name

$lang_login:  $site_url/index.php';

$sql = "insert into templates values(null, 'email_activated_account', '$table9')";
$db->query($sql);
