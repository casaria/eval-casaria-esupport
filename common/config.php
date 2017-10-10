
<?php
/***********************************************************************************************************
**
**	file:	config.php
**
**	This file contains all variables associated with mysql.
**
************************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/24/01
	**
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

/**********************************************************************************************************/
/****************************	 Variables	***********************************************************/

$database = "mysql";					//database (mysql is the only one available
$server_gmt_offset = -5; //Timezone GMT -5
$db_host='localhost';
$db_user = 'casaria_hdesk1';
$db_pwd = '5XwoR]B';
$db_name = 'casaria_hdesk1';
$uploaddir = '/home/casaria/public_html/support/uploads/';
$session_time = 14400;
$session_name = 'CasariaIncSupport';
$MailQueuePath = "/home/casaria/public_html/support/DelayedMail/";

/*********	You shouldn't need to change anything below here.	***********************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

$mysql_crmsettings_table = "crmsettings";
$mysql_tequipment_table = "tequipment";
$mysql_tBStatus_table = "tBillingStatus";
$mysql_announcement_table = "announcements";	//mysql announcement table name
$mysql_tcategories_table = "tcategories";		//mysql ticket categories table
$mysql_tpriorities_table = "tpriorities";		//mysql ticket priority table
$mysql_tstatus_table = "tstatus";				//mysql ticket status table\
$mysql_tratings_table = "tratings";				//mysql ticket rating table
$mysql_users_table = "users";					//mysql users table
$mysql_sgroups_table = "sgroups";				//mysql supporter group table
$mysql_ugroups_table = "ugroups";				//mysql users group table
$mysql_faqcat_table = "faqcategories";			//mysql faq categories table
$mysql_faqsubcat_table = "faqsubcategories";	//mysql faq sub-categories table
$mysql_faqs_table = "faqs";						//mysql faq question and answer table
$mysql_platforms_table = "platforms";			//mysql platforms table
$mysql_tickets_table = "tickets";				//mysql tickets table
$mysql_settings_table = "settings";				//mysql settings table
$mysql_themes_table = "themes";					//mysql themes table
$mysql_time_table = "time_track";				//mysql table for keeping track of time spent on a ticket
$mysql_whosonline_table = "whosonline";			//mysql whosonline table
$mysql_survey_table = "survey";					//mysql survey table
$mysql_kcategories_table = "kcategories";		//mysql knowledge base categories table
$mysql_kbase_table = "kbase";					//mysql knowledge base table that holds q & a's
$mysql_attachments_table = "attachments";		//mysql attachments table
$mysql_templates_table = "templates";			//mysql templates table
$mysql_kb_queries_table = "kb_queries";			//mysql knowledge base queries table

/**********************************************************************************************************/
/**********************************************************************************************************/


/**********************************************************************************************************/
/**********************************************************************************************************/
/**		This takes care of the global variables being set to off by default in php 4.2.0 and above.		***/
/**********************************************************************************************************/
/**********************************************************************************************************/

if(phpversion() >= "4.2.0"){
	if(is_array($_SERVER)){
		extract($_SERVER, EXTR_PREFIX_SAME, "server");
	}
	if(is_array($_GET)){
		extract($_GET, EXTR_PREFIX_SAME, "get");
	}
	if(is_array($_POST)){
		extract($_POST, EXTR_PREFIX_SAME, "post");
	}
	if(is_array($_COOKIE)){
		extract($_COOKIE, EXTR_PREFIX_SAME, "cookie");
	}
	if(is_array($_FILES)){
		extract($_FILES, EXTR_PREFIX_SAME, "file");
	}
	if(is_array($_ENV)){
		extract($_ENV, EXTR_PREFIX_SAME, "env");
	}
	if(is_array($_REQUEST)){
		extract($_REQUEST, EXTR_PREFIX_SAME, "request");
	}
/*	if(is_array($_SESSION)){
		extract($_SESSION, EXTR_PREFIX_SAME, "session");

	}
	*/
}


/***********************************************************************************************************
**
**	file:	config.php
**
**	This file contains all variables associated with mysql.
**
************************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	09/24/01
	**
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

/**********************************************************************************************************/
/****************************	 Variables	***********************************************************/

$database = "mysql";					//database (mysql is the only one available
$server_gmt_offset = -5; //Timezone GMT -5
$db_host='localhost';
$db_user = 'casaria_hdesk1';
$db_pwd = '5XwoR]B';
$db_name = 'casaria_hdesk2';
$uploaddir = '/var/www/casaria/support/uploads/';
$includeDir = '/var/www/casaria/support/common/';
$session_time = 14400;
$session_name = 'CasariaIncSupport';
$MailQueuePath = "/var/www/casaria/support/common/DelayedMail/";
$includePath = "/var/www/casaria/support/common/";

/*********	You shouldn't need to change anything below here.	***********************************/
/**********************************************************************************************************/
/**********************************************************************************************************/

$mysql_crmsettings_table = "crmsettings";
$mysql_tequipment_table = "tequipment";
$mysql_tBillingStatus = "tBillingStatus";

$mysql_announcement_table = "announcements";	//mysql announcement table name
$mysql_tcategories_table = "tcategories";		//mysql ticket categories table
$mysql_tpriorities_table = "tpriorities";		//mysql ticket priority table
$mysql_tstatus_table = "tstatus";				//mysql ticket status table
$mysql_tratings_table = "tratings";				//mysql ticket rating table
$mysql_users_table = "users";					//mysql users table
$mysql_sgroups_table = "sgroups";				//mysql supporter group table
$mysql_ugroups_table = "ugroups";				//mysql users group table
$mysql_faqcat_table = "faqcategories";			//mysql faq categories table
$mysql_faqsubcat_table = "faqsubcategories";	//mysql faq sub-categories table
$mysql_faqs_table = "faqs";						//mysql faq question and answer table
$mysql_platforms_table = "platforms";			//mysql platforms table
$mysql_tickets_table = "tickets";				//mysql tickets table
$mysql_settings_table = "settings";				//mysql settings table
$mysql_themes_table = "themes";					//mysql themes table
$mysql_time_table = "time_track";				//mysql table for keeping track of time spent on a ticket
$mysql_whosonline_table = "whosonline";			//mysql whosonline table
$mysql_survey_table = "survey";					//mysql survey table
$mysql_kcategories_table = "kcategories";		//mysql knowledge base categories table
$mysql_kbase_table = "kbase";					//mysql knowledge base table that holds q & a's
$mysql_attachments_table = "attachments";		//mysql attachments table
$mysql_templates_table = "templates";			//mysql templates table
$mysql_kb_queries_table = "kb_queries";			//mysql knowledge base queries table

/**********************************************************************************************************/
/**********************************************************************************************************/


/**********************************************************************************************************/
/**********************************************************************************************************/
/**		This takes care of the global variables being set to off by default in php 4.2.0 and above.		***/
/**********************************************************************************************************/
/**********************************************************************************************************/

if(phpversion() >= "4.2.0"){
	if(is_array($_SERVER)){
		extract($_SERVER, EXTR_PREFIX_SAME, "server");
	}
	if(is_array($_GET)){
		extract($_GET, EXTR_PREFIX_SAME, "get");
	}
	if(is_array($_POST)){
		extract($_POST, EXTR_PREFIX_SAME, "post");
	}
	if(is_array($_COOKIE)){
		extract($_COOKIE, EXTR_PREFIX_SAME, "cookie");
	}
	if(is_array($_FILES)){
		extract($_FILES, EXTR_PREFIX_SAME, "file");
	}
	if(is_array($_ENV)){
		extract($_ENV, EXTR_PREFIX_SAME, "env");
	}
	if(is_array($_REQUEST)){
		extract($_REQUEST, EXTR_PREFIX_SAME, "request");
	}
/*	if(is_array($_SESSION)){
		extract($_SESSION, EXTR_PREFIX_SAME, "session");

	}
	*/
}
?>