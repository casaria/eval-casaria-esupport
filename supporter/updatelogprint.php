<?php session_start(); ?>
<script language="Javascript1.2">
<!--

function printWindow(){
browserVersion = parseInt(navigator.appVersion);
if (browserVersion >= 4) window.print()
}

printWindow();

// -->
</script> 




<?php


/***************************************************************************************************
**	file:	updatelog.php
**		This file generates the update log given a specified ticket number and displays it in its
**	own window.
**
****************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	10/17/01
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


//$cookie_name = $_SESSION['cookie_name'];

$language = getLanguage($cookie_name);
if($language == '')
	require_once "../lang/$default_language.lang.php";
else
	require_once "../lang/$language.lang.php";
require_once "../common/style.php";

$time_offset = getTimeOffset($cookie_name);
$time = time() + ($time_offset * 3600);


$sql = "select update_log from $mysql_tickets_table where id=$id";
$result = $db->query($sql);

$log = $db->fetch_row($result);

//put the contents of the update log in an array
$log = explode($delimiter, $log[0]);
$info = getTicketInfo($id);


?>
<BR> 
<TABLE class=border cellSpacing=0 cellPadding=5 width="100%" align=center border=0>
<TR>
<TD class=printback> 
   <IMG SRC="<?php echo "../".$theme['image_dir'].$theme['logosmall_path']; ?>" ALT="logo">
</TD>
<TD class=printback align=right valign=top> 
   <b>
   eSupport<br>
   STATUS SUMMARY</b><br>
   <?PHP echo date("M d, Y g:i a", $time);
   echo "<BR><B><font color='ff6600'>Ticket ID #".$id." ".$info[status]."</B></font>";
   ?>
</TD>
</TR>
</TABLE>
<BR>
<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR> 
			<TD> 
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
					<TR>
					<TD class=info> 
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr>

						
						
						<TD class=info align=left><B>
							<?php  
								echo $lang_ulog." Ticket ID #".$id."<br>"; 
								echo "$lang_equipment: $info[equipment]";
								echo " - ".$info[short]."<br>";
								echo "$lang_category: $info[category] / $lang_platform: $info[platform]";
							      	echo "<br>$lang_ticketcreatedby: $info[user] / $lang_supporter: $info[supporter] $platform $short";
				
							?>
						</td>
						</TR>
						</table>
					</td>
					</tr>

					<?php
								$description = "<b>DESCRIPTION:<BR></b>".$info[description];
								echo " <tr><td colspan=1 class=back2 align=left><font size=2>".$description."</font></td></tr>";

						if($s != "rev"){
							for($i=0; $i<sizeof($log)-1; $i++){
								$log[$i] = eregi_replace("\n", "<br>", $log[$i]);
								$log[$i] = eregi_replace("  ", "&nbsp;&nbsp;", $log[$i]);
								$log[$i] = stripslashes($log[$i]);

                                                                if(eregi("^[0-9]{8,11}", $log[$i])){            //if it contains just the timestamp, edit it.
									$date = substr(eregi_replace("lang(.*)", "", $log[$i]), 0, -2);
									$date = date("F j, Y, g:i a", $date);
									eval("\$log[$i] = \"$log[$i]\";");
									$log[$i] = eregi_replace("^[0-9]*", "$date ", $log[$i]);
								}
								if($i%2 == 0){
									echo "<tr><td colspan=2 class=cat align=left><font size=1><b>". $log[$i] ."</b></font></td></tr>";
								}
								else{
									if(eregi("[$]", $log[$i]))	//this accounts for older tickets which don't have variables in the update log.
										eval("\$log[$i] = \"$log[$i]\";");	//if the update log doesn't contain variables, this eval doesn't work.
									$log[$i] = stripslashes($log[$i]);

									echo "<tr><td colspan=2 class=printback align=left>&nbsp;&nbsp;&nbsp;&nbsp;". $log[$i] ."<br></td></tr>";
								}
							}
							unset($date);
						}
						else{
							for($i=sizeof($log)-2; $i>=0; $i--){
								$log[$i] = eregi_replace("\n", "<br>", $log[$i]);
								$log[$i] = eregi_replace("  ", "&nbsp;&nbsp;", $log[$i]);
								$log[$i] = stripslashes($log[$i]);

                                                                if(eregi("^[0-9]{8,11}", $log[$i-1])){          //if it contains just the timestamp, edit it.
									$date = substr(eregi_replace("lang(.*)", "", $log[$i-1]), 0, -2);
									$date = date("F j, Y, g:i a", $date);
									$line = $log[$i-1];
									eval("\$line = \"$line\";");
									$log[$i-1] = eregi_replace("^[0-9]*", "$date", $line);
								}
								
								$text = $log[$i+1];
								
								if($i%2 != 0){
									echo "<tr><td colspan=2 class=cat align=left><font size=1><b>". $log[$i-1] ."</b></font></td></tr>";
								}
								else{
									if(eregi("[$]", $log[$i+1]))	//this accounts for older tickets which don't have variables in the update log.
										eval("\$text = \"$text\";");	//if the update log doesn't contain variables, this eval doesn't work.
									$text = stripslashes($text);
									echo "<tr><td colspan=2 class=back2 align=left>&nbsp;&nbsp;&nbsp;&nbsp;". $text ."<br></td></tr>";
								}
							}
						}

					?>
					
				</table>

			</td>
			</tr>
		</table><br>
		
<?php

	
	startTable("$lang_timehistory", "left", 100, 4);

	$sql = "select trk.supporter_id, trk.work_date, trk.reference,  trk.minutes from tickets as tkt, time_track as trk where (tkt.id=trk.ticket_id AND tkt.id=$id)";
	$resultsupporters = $db->query($sql);


  while($row = $db->fetch_array($resultsupporters)){
    if ($row[minutes] != 0) {	
    	echo '<tr>
    		<td width=27% class=back2 align=right>';
    		if ($row['work_date'])
    		    echo date("F j, Y", $row[work_date]);
    		  else
    		    echo "- No Date -";
    		echo '</td>';
    	echo '<td width=25% class=back>';
    		  $sql = "select * from $mysql_users_table where id=$row[supporter_id]";
    		  $result = $db->query($sql);
    		  $sup_row = $db->fetch_array($result);
    			echo "$sup_row[user_name]"; 
    	echo '</td>';			
    	echo '<td width=25% class=back2>';
    			showFormattedTime($row[minutes] * 60, 1); 
    	echo '</td>';			
    	echo '<td class=back>';
    			echo "$row[reference]"; 
    	echo '</td>';				
	  }
	}
	
		
		
		
	// Calculates total time spent on the ticket in minutes
	
  echo '<tr><td width=24% class=back2 align=right><B>Total Time:</B>';
	echo '</td> <td class=back >';
	echo '</td> <td class=back colspan=2>';
				$results=getTicketTotalTime($id);
				$supporters = $results['supporters'];
				$supporters_after_hours = $results['supporters_after_hours'];
				$supporters_engineer_rate= $results['supporters_engineer_rate'];
				$minutes = $results['total_time'];

	echo'<B>';
	showFormattedTime($minutes * 60, 1);
	echo '</B></td>';

	endTable();
	DrawTableSupporterTotals(getTicketTotalTime($id), $id, $lang_time_totals);

?>
