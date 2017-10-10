<?php

$path_parts = pathinfo($_SERVER['SCRIPT_FILENAME']);
echo 'Path to this file: '.$path_parts['dirname']."
";

?>