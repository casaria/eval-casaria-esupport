<?php

/***************************************************************************************************
**	file: login.php
**
**	This file will check to see if the user is logged in already via a cookie...if not,
**	logged in, it will do the login script and set the cookie so the user can login.
**	The cookie will be checked against all of the remaining pages that require login.php.
**
**	Note:  This file needs to be required of all pages that require a user to be logged in.
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	08/10/01
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
//require_once "../common/common.php";
if(eregi("supporter", $PHP_SELF) || eregi("admin", $PHP_SELF))
	require_once "../lang/$default_language.lang.php";
else
	require_once "lang/$default_language.lang.php";

session_start();
//if submit has been hit, set the cookie and reload the page immediately so the cookie takes effect.
if(isset($login))
{
	//if admin is contained in the url, we need to make sure the user is an
	//admin before letting them login.
	if(ereg("/admin", $HTTP_REFERER)){
		//check the user name and password against the database.
		if(checkUser($HTTP_POST_VARS['user'], md5($HTTP_POST_VARS['password']))){
			if(isAdministrator($HTTP_POST_VARS['user'])){
				$cookie_name = $HTTP_POST_VARS['user'];
				session_register ("cookie_name");
				$enc_pwd = md5($HTTP_POST_VARS['password']);
				session_register ("enc_pwd");
				$referer = $HTTP_REFERER;
				header("Location: $referer");
			}
			else{
				echo $lang_notadmin;
				exit;
			}
		}
		else{
			echo $lang_wronglogin;
			exit;
		}

	}

	elseif ( (ereg("/supporter", $HTTP_REFERER))  ){
		//check the user name and password against the database.
		if(checkUser($HTTP_POST_VARS['user'], md5($HTTP_POST_VARS['password']))){
			if(isSupporter($HTTP_POST_VARS['user'])){
				$cookie_name = $HTTP_POST_VARS['user'];
				session_register("cookie_name");
				$enc_pwd = md5($HTTP_POST_VARS['password']);
				session_register("enc_pwd");
				$referer = $HTTP_REFERER;
				header("Location: $referer");
			}
			else{
				echo $lang_notsupporter;
				exit;
			}
		}
		else{
			echo $lang_wronglogin;
			exit;
		}

	}

	//otherwise, the user is not logging in to the admin site.
	else{
		//check the user name and password against the database.
		if(checkUser($HTTP_POST_VARS['user'], md5($HTTP_POST_VARS['password']))){
				$cookie_name = $HTTP_POST_VARS['user'];
				session_register ("cookie_name");
				$enc_pwd = md5($HTTP_POST_VARS['password']);
				session_register ("enc_pwd");
                                $referer = "$HTTP_REFERER";
				header("Location: $referer");
		}
		else{
			echo $lang_wronglogin;
			exit;
		}
	}

}

//check the cookie first.
if(!isCookieSet()){
	if(eregi("supporter", $PHP_SELF) || eregi("admin", $PHP_SELF))
		require_once "../common/style.php";
	else
		require_once "common/style.php";

echo '


<script language="JavaScript">
	function setfocus(){
		document.login.user.focus();
	}
</script>
</head>
<body bgcolor='.$theme['bgcolor'].' onload="setfocus()">
<form name=login method=post>
<TABLE class=border cellSpacing=0 cellPadding=0 width='.$theme['width'].' align=center border=0>
  <TR>
    <TD>
      <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
        <TR>
          <TD class=hf align=left>&nbsp;</TD>
        </TR>
        <TR>
          <TD class=back>
			<TABLE border=0 width="100%">
              <TR>
                <TD class=back vAlign=top><BR>


<TABLE class=border cellSpacing=0 cellPadding=0 width="30%" align=center border=0>
  <TR>
    <TD>
      <TABLE cellSpacing=1 cellPadding=5 width="100%" border=0>
        <TR>
          <TD class=info align=left><b>'.$helpdesk_name.' '.$lang_login.'</b></TD>
        </TR>
        <TR>
          <TD class=back2>
			<table width=100% border=0 cellspacing=0 cellpadding=6>
				<tr>
				 <td class=back2 align=right>'.$lang_username.':</td><td>
					<input type=text name=user size=12></td>
				</tr>
				<tr>
				 <td class=back2 align=right>'.$lang_password.':</td><td>
					<input type=password name=password size=12></td>
				</tr>
				<tr>
				 <td class=back2></td><td class=back2 align=center>
					<input type=submit name=login value="'.$lang_submit.'"></td>
				</tr>
			</table>


		  </TD>
		</TR>
	  </TABLE>
	 </TD>
	</TR>
</TABLE>';

if($pubpriv == 'Private'){
	echo '<br><div align=center>[ <a href="'.$site_url.'/index.php?reg=yes">'.$lang_registerforaccount.'</a> ]</div>';
}
echo '
<BR>
				
				</TD>
              </TR>
            </TABLE>
		  </TD>
		</TR>
	  </TABLE>
	 </TD>
	</TR>
</TABLE>
 </TD>
</TR>
</TABLE>
 </TD>
</TR>
</TABLE>
 </TD>
</TR>
</TABLE>
</form>

';

        if(eregi("supporter", $PHP_SELF) || eregi("admin", $PHP_SELF))
                require "../common/footer.php";
        else
                require "common/footer.php";

        exit;

}
else{
		
	//if submit has not been pressed, check the cookie against the database.
	if(ereg("/supporter", $PHP_SELF) && !isSupporter($cookie_name) && $cookie_name != ''){
		echo "$lang_notsupporter";
		exit;
	}

	if(ereg("/admin", $PHP_SELF) && !isAdministrator($cookie_name) && $cookie_name != ''){
		echo "$lang_notadmin";
		exit;
	}

}
//this returns back to the page that called it.

?>
