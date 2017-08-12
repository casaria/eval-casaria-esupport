<?
$to = "phorrack@casaria.net";
$from = "casaria@casaria.net";
$sub = "My Email";
$msg = "Hello Emil, SMS Gateway test!";
mail($to, $sub, $msg, "From: $from");
?>
