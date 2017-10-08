<?php 
/**************************************************************************************************
**	file:	index.php
**
**		This is the index file that provides access to the rest of the site basically.  Mostly html
**	code.  This file consists mainly of links to other parts of the helpdesk.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/19/01
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

if($SERVER_PORT == 80 && $enable_ssl == 'On' && (!isset($cookie_name) || $cookie_name == '')){
	$site = eregi_replace("http", "https", $supporter_site_url);
	header("Location: $site");
}

require "../common/login.php";

$language = getLanguage($cookie_name);
if($language == '')
	require_once "../lang/$default_language.lang.php";
else
	require_once "../lang/$language.lang.php";

require_once "../common/style.php";

if($enable_helpdesk == 'Off'){
	printerror($on_off_reason);
	exit;
}

if($pubpriv == "Private"){	//generate a list of people waiting to be approved.
	$sql = "SELECT * from $mysql_users_table where user=0";
	$result = $db->query($sql);
	$i=0;
	while($row = $db->fetch_array($result)){
		$awaiting_approval[$i] = "<a href=\"$admin_site_url/control.php?t=users&act=uedit&id=$row[id]\">$row[user_name]</a>";
		$i++;
	}
}
$time_offset = getTimeOffset($cookie_name);
if(isset($cookie_name)){
	//update the lastactive time in the database.
	$sql = "UPDATE $mysql_users_table set lastactive='".time()."' where user_name='".$cookie_name."'";
	$db->query($sql);
}
$last_active = getLastActiveTime($cookie_name);

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
          <TD class=hf align=right>
			<?php echo "$lang_loggedinas <b>$cookie_name</b> (<A class=hf href=\"../common/logout.php\">$lang_logout</a>)";	?>
		 </TD>
        </TR>
        <TR> 
          <TD class=back align=left> <IMG SRC="../<?php echo $theme['image_dir'].$theme['logo_path']; ?>">
          
            <TABLE width="100%">
              <TBODY> 
              <TR> 
                <TD class=back vAlign=top align=right></TD>
              </TR>
              </TBODY> 
            </TABLE>
          
            <TABLE width="100%" align=center border=0>
              <TBODY> 
              <TR> 
                <TD vAlign=top width="200"> 
                  <TABLE class=border cellSpacing=0 cellPadding=0 width="100%" 
                  align=center border=0>
                    <TBODY> 
                    <TR> 
                      <TD> 
                        <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
                          <TBODY> 
                          <TR> 
                            <TD class=info align=center><B><?php echo $lang_Supporter . " " . $lang_options; ?></B></TD>
                          </TR>
                          <TR> 
                            <TD class=cat><B><?php echo $lang_ticket . " " . $lang_options; ?></B></TD>
                          </TR>
                          <TR> 
                            <TD class=subcat> 
                              <LI><A href="index.php?t=tcre"><?php echo $lang_create . " " . $lang_ticket; ?></A> 
                              <LI><A href="index.php?t=tmop"><?php echo $lang_myopen; ?></A></LI>
                              <LI><A href="index.php?t=tmgo"><?php echo $lang_mygroups; ?></A></LI> 
                              <LI><A href="index.php?t=tmre"><?php echo $lang_myrecent; ?></A></LI> 
                              <LI><A href="index.php?t=tsrc"><?php echo $lang_searchforticket; ?></A></LI>
                              <br><form name=formTicketSearch action="index.php" method=get>
                                    <input type=hidden name=t value=tupd>
                                    <?php echo $lang_ticket; ?> # : <input type=text name=id size=5>
                                    <a href="#" onClick="submit()"> <?php echo $lang_go; ?>!</a>
                                    <script>document.formTicketSearch.id.focus();</script>
                                  </form>
                            </TD>
                          </TR>

                          <?php
                              if($enable_kbase == 'On'){
                                  echo '<TR> 
                                           <TD class=cat><B>'.$lang_faqopts.'</B></TD>
                                        </TR>
                                        <TR> 
                                           <TD class=subcat> 
                                               <LI><A href="index.php?t=kbase">'.$lang_kbase.'</A></LI>
                                               <LI><A href="index.php?t=kbase&supkb=yes">'.$lang_Supporter . ' ' . $lang_kbase.'</A></LI>
                                               <LI><A href="index.php?t=kbase&act=kadd">'.$lang_addtokb.'</A></LI>
                                               <LI><A href="index.php?t=kbase&act=ksta">'.$lang_kbstats.'</A></LI>
                                           </TD>
                                        </TR>';
                              }
			  ?>
                          <TR> 
                            <TD class=cat><B><?php echo $lang_Supporter . " " . $lang_options; ?></B></TD>
                          </TR>
                          <TR> 
                            <TD class=subcat> 
                              <LI><A href="index.php?t=epro"><?php echo $lang_editprofile?></A></LI>
                              <LI><A href="index.php?t=sgrp"><?php echo $lang_viewgroups;?></A></LI>
                            </TD>
                          </TR>

                          <?php
                            if (isAdministrator($cookie_name)){
                                echo "<TR> 
                                        <TD class=cat><B>".$lang_reporting."</B></TD>
                                      </TR>
                                      <TR> 
                                        <TD class=subcat> 
                                        <LI><A href=\"index.php?t=tstats\">".$lang_ticketstats."</A></LI> 
                                        <LI><A href=\"index.php?t=slist\">".$lang_supporterstats."</A></LI>";

                                if($enable_ratings == 'On'){
                                     echo '<LI><A href="index.php?t=tsur">$lang_surveystats</A></LI>';
                                }

                                echo "<LI><A href=\"index.php?t=gstats\">".$lang_groupstats."</A></LI> ";

                                if($enable_time_tracking == 'On'){
                                     echo '<LI><A href="index.php?t=time">'.$lang_timetracking.'</A></LI>';
                                }
                            }
                                     echo "</TR>";

                          ?>


                            </TD>
                          </TR>
                         </TBODY> 
                        </TABLE>
                      </TD>
                    </TR>
                    </TBODY> 
                  </TABLE>

<?php 
//this is ugly, but it works...i'll clean it up later.
//if the admin is logged in, display a list of people/users who are awaiting approval.
if (isAdministrator($cookie_name) && $awaiting_approval){

?>	<br><TABLE class=border cellSpacing=0 cellPadding=0 width="100%" 
                  align=center border=0>
                    <TBODY> 
                    <TR> 
                      <TD> 
                        <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
                          <TBODY> 
                          <TR> 
                            <TD class=info align=center><B><?php echo $lang_awaitingapproval; ?></B></TD>
                          </TR>
                          <TR> 
                            <TD class=back2><B>
							<?php
								for($i=0; $i<sizeof($awaiting_approval); $i++){
									echo $awaiting_approval[$i] . "<br>";
								}
							?></B></TD>
						  </tr>
					  </table>
					  </td></tr></table>
<?php }  ?>



                </TD>
                <TD vAlign=top>
<?php
					switch($t){
						case ("tcre"):
							require "tcreate.php";
							break;
						case ("tmop"):
							require "myopen.php";
							break;
						case ("tmgo"):
							require "mygroupopen.php";
							break;
						case ("tmre"):
							require "myrecent.php";
							break;
						case ("tsrc"):
							require "tsearch.php";
							break;
						case ("tupd"):
							require "tupdate.php";
							break;
						case ("epro"):
							require "../editprofile.php";
							break;
						case ("sgrp"):
							require "sgroups.php";
							break;
						case ("kbase"):
							switch($act){
								case("kedit"):
									require "../kbase/edit.php";
									break;
								case("kadd"):
									require "../kbase/add.php";
									break;
								case("kans"):
									require "../answer.php";
									break;
								case("ksta"):
									require "../kbase/kbstats.php";
									break;
								default:
									require "../kbase.php";
									break;
							}
							break;
						case("terr"):
							printError("$lang_missing_info");
							break;
						case ("memb"):
							require "member.php";
							break;
						case ("tstats"):
							require "../admin/tstats.php";
							break;
						case ("tsur"):
							require "../admin/sstats.php";
							break;
						case ("gstats"):
							require "../admin/gstats.php";
							break;
						case ("slist"):
							require "../admin/slist.php";
							break;
						case ("time"):
							require "../admin/time.php";
							break;
						case ("uedit"):
							require "../admin/uedit.php";
							break;
						case("repo"):
							require "../kbase/report.php";
							break;
						default:
							require "announce.php";
							break;
					}
		  
						  
					?>
				
              </TR>
              </TBODY> 
            </TABLE>
            <BR>
          </TD>
        </TR>
		<?php
		
		if($enable_whosonline == 'On'){
			echo "<TR>
				<TD class=cat>";
					require_once "../common/whosonline.php";
			echo "</td>
				</tr>";
		}
		
		?>
        <TR> 
          <TD class=hf align=center>
		  <?php

		  echo '
            <div align="center">
			<A class=hf href="'.$supporter_site_url.'/index.php">'.$lang_home.'</A> |&nbsp;';
			if(isAdministrator($cookie_name)){
				echo '<A class=hf href="'.$admin_site_url.'/control.php">'.$lang_cp.'</a> |&nbsp;';
			}
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
