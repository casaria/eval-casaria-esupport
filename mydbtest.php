<?php

/***************************************************************************************************

****************************************************************************************************/
$default_language = "English";

require_once "common/config.php";
require_once "common/$database.class.php";
require_once "common/common.php";



//set the start time so we can calculate how long it takes to load the page.
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[0] + $mtime1[1];
//require_once "../common/common.php";
if(eregi("supporter", $PHP_SELF) || eregi("admin", $PHP_SELF)) {
   require_once "lang/$default_language.lang.php"; 
   $login_logo ="images/casariadefault/small-header-brown.gif";
}
else {
	require_once "lang/$default_language.lang.php";
	$login_logo = "images/casariadefault/small-header-brown.gif";
}


$cookieuser = '';
//common.php
startSession();

$cookie_name = $_SESSION['cookie_name'];
//echo "cookie_name = $cookie_name <br>";
//echo "session ID =" . session_id(). " <br>";
//if submit has been hit, set the cookie and reload the page immediately so the cookie takes effect.
if(isset($login))
{
	
	//if admin is contained in the url, we need to make sure the user is an
	//admin before letting them login.
	if(ereg("/admin", $HTTP_REFERER)){
		//check the user name and password against the database.
		if(checkUser($_POST['user'], md5($_POST['password']))){
			if(isAdministrator($HTTP_POST_VARS['user'])){
				$cookie_name = $HTTP_POST_VARS['user'];
				//session_register ("cookie_name");
				$_SESSION ["cookie_name"] = $cookie_name;
				$enc_pwd = md5($HTTP_POST_VARS['password']);
				//session_register ("enc_pwd");
				$_SESSION ["enc_pwd"] = $enc_pwd;
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
		if(checkUser($_POST['user'], md5($_POST['password']))){
			if(isSupporter($HTTP_POST_VARS['user'])){
				$cookie_name = $HTTP_POST_VARS['user'];
				//session_register("cookie_name");
				$_SESSION ["cookie_name"] = $cookie_name;
				$enc_pwd = md5($HTTP_POST_VARS['password']);
				//session_register("enc_pwd");
				$_SESSION ["enc_pwd"] = $enc_pwd;
				$referer = $HTTP_REFERER;
				header("Location: $referer");
				setcookie('supporter_usercookie', $cookie_name,  time()+ 60*60*24*7);
				setcookie('supporter_pwdcookie', $HTTP_POST_VARS['password'],  time()+ 60*60*24*7);
				
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
		
		if(checkUser($_POST['user'], md5($_POST['password']))){
				$cookie_name = $_POST['user'];
				//session_register ("cookie_name");
				$_SESSION ['cookie_name'] = $cookie_name;
				$enc_pwd = md5($HTTP_POST_VARS['password']);
				//session_register ("enc_pwd");
				$_SESSION ['enc_pwd'] = $enc_pwd;
        $referer = "$HTTP_REFERER";
				header("Location: $referer");
				//echo"<BR>$cookie_name $enc_pwd";
		}
		else{
			echo $lang_wronglogin;
			exit;
		}
	}

}

//check the cookie first.
if(!isSet($_SESSION ['cookie_name'])){
	if(eregi("supporter", $PHP_SELF) || eregi("admin", $PHP_SELF))
		require_once "../common/style.php";
	else
		require_once "common/style.php";

if (isset($_COOKIE['supporter_usercookie'])) 
   $cookieuser = $_COOKIE['supporter_usercookie'];
if (isset($_COOKIE['supporter_pwdcookie'])) 
   $cookiepwd = $_COOKIE['supporter_pwdcookie'];


echo 
'<script language="JavaScript">
	function setfocus(){
		document.login.user.focus();
	} </script>';

echo'
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
          <TD class=back Align=center> <IMG SRC='."$login_logo".' ALT="">
        <TR>
        </TR>
        <TR>
          <TD class=back2>
			<table width=100% border=0 cellspacing=0 cellpadding=6>
				<tr>
				 <td class=back2 align=right>'.$lang_username.':</td><td>
					<input type=text name=user size=12 value='."$cookieuser".'></td>
				</tr>
				<tr>
				 <td class=back2 align=right>'.$lang_password.':</td><td>
					<input type=password name=password size=12 value='."$cookiepwd".'></td>
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
//get some globals about the user
 $user_id = getUserId($cookie_name);
 $ugID_list = getUsersGroupIDList($user_id);

//this returns back to the page that called it.
?>
