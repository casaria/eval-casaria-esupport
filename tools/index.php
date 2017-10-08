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


//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];


require_once "../common/config.php";
require_once "../common/$database.class.php";
require_once "../common/common.php";

$cookie_name = $_POST['phorrack'];
//session_register("cookie_name");
$_SESSION ["cookie_name"] = $cookie_name;
$enc_pwd = md5($_POST['b']);
//session_register("enc_pwd");
$_SESSION ["enc_pwd"] = $enc_pwd;
$referer = $HTTP_REFERER;

if($enable_helpdesk == 'Off'){
	printerror($on_off_reason);
	exit;
}

if($reg == 'yes'){
	require_once "../lang/$default_language.lang.php";
	require "../register.php";
	exit;
}

require_once "../common/style.php";
require_once "../lang/English.lang.php";
/*
RewindSession();
$language = getLanguage($_SESSION['cookie_name']);
if($language == '')
	require_once "../lang/$default_language.lang.php";
else
	require_once "../lang/$language.lang.php";

//if(isSupporter($_SESSION['cookie_name']))
	//nov14 header("Location: $supporter_site_url/index.php");

require_once "../common/style.php";

$time_offset = getTimeOffset($_SESSION['cookie_name']);



$enable_CloudControl = getCloudControlUserSetting($_SESSION['cookie_name']);

$last_active = getLastActiveTime($_SESSION['cookie_name']);


        	//get the users group (+++ uses only the first group as ticket group.
        	$user_id = getUserID($cookie_name);
        	$groups =  getUsersGroupList($user_id);
        	
        	if (sizeof ($groups) == 0) {
        		$group_id = 0;	 
        	} else {
         		$group_id =  eregi_replace("ugroup", "", $groups[0]);
        	}

          $user_id = getUserID($cookie_name);
        	$groups = getUsersGroupList($user_id);
        	for ($i=0; $i< sizeof ($groups); $i++) {
        		 $group_id =  eregi_replace("ugroup", "", $groups[$i]);
        		 $groupname = getuGroup($group_id);
        	}
        	
        	if($sg == ''){
		        $sg = getDefaultSupporterGroupID($group_id);
	        }
*/
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
	  <?php
		if($pubpriv == 'Public'){
			echo "<a class=hf href=\"$supporter_site_url/index.php\">";
			echo $lang_Supporter . " / " . $lang_admin;
			echo "</a>";
		}
		else{
			echo $lang_loggedinas." <b>".$_SESSION['cookie_name']."</b> ( <a class=hf href=\"common/logout.php\"> ".$lang_logout."</a> )";
		}
	  ?>
	  </TD>
        </TR>
        <TR>
          <TD class=back> <IMG SRC="<?php echo "../$theme[image_dir]$theme[logo_path]"; ?>" ALT="logo">
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
            			<TD vAlign=top width="23%">
            				<TABLE class=border cellSpacing=0 cellPadding=0 width="100%"
            					align=center border=0>
            					<TBODY>
            						<TR>
            							<TD>
            								<TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
            									<TBODY>
            										<TR>
            											<TD class=info align=center><B><?php echo $lang_user . " " . $lang_options; ?></B></TD>
            										</TR>
            										<TR>
            											<TD class=cat><B><?php echo $lang_ticket . " " . $lang_options; ?></B></TD>
            										</TR>
            										<TR>
            											<TD class=subcat>
            												<LI><A href="index.php?t=t1">    <?php echo $lang_GenerateClosedDate; ?></A></LI>
            													<LI><A href="index.php?t=t2"><?php echo "T-2"; ?></A></LI>
            														<LI><A href="index.php?t=t3"><?php echo "T-3"; ?></A></LI>
            														<LI><A href="index.php?t=t4"><?php echo "T-4"; ?></A></LI>
            														<LI><A href="index.php?t=t5"><?php echo "T-5"; ?></A></LI>
            														<br><form name=formTicketSearch action="index.php" method=get>
            															<input type=hidden name=t value=tinf>
            															<?php echo $lang_ticket; ?> # : <input type=text name=id size=5>
            															<a href="#" onClick="document.formTicketSearch.submit();"> <?php echo $lang_go; ?>!</a>
            
            														</form>
            													</TD>
            												</TR>
            												<?php
            
            												if($enable_kbase == 'On'){
            													echo '<TR>
            													<TD class=cat><B>' . $lang_faqopts . '</B></TD>
            													</TR>
            													<TR>
            													<TD class=subcat>
            													<LI><A
            													href="index.php?t=kbase">' . $lang_kbase . '</A>
            													</LI>
            
            													</TD>
            													</TR>';
            												}

            												
            												if(isCookieSet($_SESSION['cookie_name'], $_SESSION['enc_pwd'])){
            													echo '<TR>
            													<TD class=cat><B>' . $lang_useroptions . '</B></TD>
            													</TR>
            													<TR>
            													<TD class=subcat>
            													<LI><A
            													href="index.php?t=epro">' . $lang_editprofile . '</A>
            													</LI>
            
            													</TD>
            													</TR>';
            												}
            
            
            												?>
            
            											</TBODY>
            										</TABLE>
            									</TD>
            								</TR>
            							</TBODY>
            						</TABLE>
            					</TD>
					<TD vAlign=top>

					<?php

					switch($t){
						case ("t1"):
							require "UpdateClosedDate.php";
							break;
						case ("t2"):
							require "dummy.php";
							break;
						case ("t3"):
							$opentickets = TRUE;
							require "dummy.php";
							break;
						case ("t4"):
							$opentickets = FALSE;
							require "dummy.php";
							break;
						case ("t5"):
							require "dummy.php";
							break;
						case ("t6"):
							require "dummy.php";
							break;
						case("terr"):
							printError($lang_missing_info);
							break;
						case ("tsuc"):
							startTable("$lang_ticket $lang_submitted", "left");
								echo "<tr><td class=back2><br><br> $lang_ticket <b>#" . str_pad($id, 5, "0", STR_PAD_LEFT) . " </b>$lang_submitted_succ.";
								echo "<br><br><br></td></tr>";
							endTable();
							break;
						case ("kbase"):
							if($act == "kans")
								require "answer.php";
							else
								require "kbase.php";							
							break;

						case("answ"):
							require "answer.php";
							break;
						case("repo"):
							require "kbase/report.php"; 	
							break;
						case("epro"):
							require "editprofile.php";
							break;
						case("cccheater"):
							require "CCC/jheater.php";
							break;														
						default:
							require "../announce.php";
							break;
					}
					?>
              </TR>
              </TBODY>
            </TABLE>
            <BR>
          </TD>
		
		</TR>

        <TR>
          <TD class=hf align=center>
		  <?php

		  echo '
            <div align="center">
			<A class=hf href="index.php">'.$lang_home.'</A> ';
			if($enable_forum == 'On'){
				echo '|&nbsp; <A class=hf href="'.$forum_site_url.'" target=_blank>'.$lang_forum.'</A>';
			}
			if($pubpriv == "Private" || isCookieSet()){
				echo ' |
					<A class=hf href="common/logout.php';
					if($enable_ssl == 'On'){
						echo "?ssl=1";
					}
				echo '">'.$lang_logout.'</A>';
			}
		  echo '</div>';
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


<script type="text/javascript" src="//cdn.jsdelivr.net/particle-api-js/5/particle.min.js">
</script>




</BODY>

</HTML>
