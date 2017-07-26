<?php

/***************************************************************************************************
**	file:	UpdateClosedDate.php
**
**		T\
**\
****************************************************************************************************/

require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";
$highest_pri = getRPriority(getHighestRank($mysql_tpriorities_table));	//set the highest priority rating
$today = getdate();

  if(isset($search)) {
      //lets get the information ready to be passed to the displayTicket table.

      $sql = "select * from $mysql_tickets_table where 'status' LIKE '%CLOSED%'";

      $result = $db->query($sql);

      echo '<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
			<TR>
			<TD>
				<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>';
      echo ' <tr> ';
      $summary = displayTicket($result);
      echo "</tr>";

      endTable();

      echo " <TABLE class=border cellSpacing=0 cellPadding=0 width=\"100%\" align=center border=0>";
      echo "$lang_summary: $lang_recordcount $summary[recordcount] $summary[remarks]  <BR>";

      echo "<tr><td><td class=back>
							<input type=text size=52% name=csvlist value='$summary[tktlist]'>
		</td></td></tr>
      </TABLE>";
      endTable();
  }

			echo "<form method=post>";
			createHeader("Update Ticket closed dates bssed on LOG entries");

			echo '
				<TABLE class=border cellSpacing=0 cellPadding=0 width="100%" align=center border=0>
						<TR>
						<TD>
							<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
								<TR>
								<TD class=back2 align=right width=27%>'.$lang_searchtype.': </td>
								<td class=back>
									<select name=andor><option value=and selected>'.$lang_and.'</option><option value=or>'.$lang_or.'</option></select>
								</td>
								</tr>
								
								<TR>';

			echo '
					</table><br>
					
					<input type=submit value=\''.$lang_searchforticket.'\' name=search>
					<input type=hidden value='.$query.' name=query>
					
					</form>';




?>
