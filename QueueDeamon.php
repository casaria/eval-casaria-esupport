<?php
require_once "common/config.php";
require_once "common/$database.class.php";
require_once "common/common.php";

if (!CheckforPrivacy(time()))
  {
		 echo "<br>Sending email is fair game!<br>";  	
  	 ProcessFiles($MailQueuePath);
  	 
  }
  else echo "<br> Sending email restricted due to GMT Privacy!";

function ProcessFiles($dir)
{
    global $admin_email, $sendmail_path ;
       
    $mailprog = $sendmail_path . "sendmail -r '$admin_email' -t";
    $count =0;
    if( $dh = opendir($dir))
    {
        while( false !== ($file = readdir($dh)))
        {
            // Skip '.' and '..'
            if( $file == '.' || $file == '..')
                continue;
            $path = $dir . '/' . $file;
            if( is_dir($path) ) continue;
          
            $fd = popen($mailprog,"w");
           	$fs = fopen($path, "r+");  
           	echo "Delivering ". $path. " via sendmail...<br>";
           	$count++;
           	fwrite ($fd, fread($fs, filesize($path)));
           	pclose ($fd);
           	fclose ($fs);
           	unlink ($path);  //delete mail file
        }
        echo $count. " emails processed<br>";
        closedir($dh);
    }
}

function CheckForPrivacy($time) {
	 global $db, $mysql_settings_table;
	 $sql = 'SELECT `GMTPrivacyStart`, `PrivacyDuration` FROM `settings` WHERE 1'; 
	 $result = $db->query($sql);
	 
	 if ($row = $db->fetch_array($result)){
    	$gmthr=gmstrftime("%H", $time) + (gmstrftime("%M", $time)*10/600);
    	//echo $gmthr;
    	echo "GMT:   ".gmstrftime("%H:%M", $time)." (".$gmthr." decimal)<br>";
    	echo "Local: ".strftime("%H:%M", $time)."<br>";
    	echo "EmailPrivacy begins at [GMT]: ".$row['GMTPrivacyStart'].":00  Duration :".$row['PrivacyDuration']." hrs<br>";
    	
    	if ( ($gmthr >= $row['GMTPrivacyStart']) and ($gmthr <= ( $row['PrivacyDuration']) + $row['GMTPrivacyStart'] ) ) 
    	  return true;
	 }
	 return false;
}
?>