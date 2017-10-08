<?php
/***********************************************************************************************************
**
**	file:	database.class.php
**
**	This file contains the mysql database class.
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

class mysql {
	var $queries = 0;

	function connect($db_host="localhost", $db_user, $db_pwd, $db_name, $pconnect=0) {
		if($pconnect) {
			mysql_pconnect($db_host, $db_user, $db_pwd) or die(mysql_errno() . " : " . mysql_error());
		} else {
			mysql_connect($db_host, $db_user, $db_pwd) or die(mysql_errno() . " : " . mysql_error());
		}
		mysql_select_db($db_name);
	}

	function fetch_array($query) {
		$query = mysql_fetch_array($query);
		return $query;
	}

	function query($sql) {
		//echo $sql . "<br>";
		$query = mysql_query($sql) or die(mysql_error());
		$this->queries++;
		return $query;
	}

	function num_rows($query) {
		$query = mysql_num_rows($query);
		return $query;
	}

	function fetch_row($query) {
		$query = mysql_fetch_row($query);
		return $query;
	}

	function insert_id() {
		$query = mysql_insert_id();
		return $query;
	}       
	function num_fields($result) {
		$result = mysql_num_fields($result);
		return $result;
	}       

	function field_name($result, $index) {
		$result = mysql_field_name($result, $index);
		return $result;
	}
			
	function tablename($result, $index) {
		$result = mysql_tablename($result, $index);
		return $result;
	}

	function list_tables($dbase) {
		$result = mysql_list_tables($dbase);
		return $result;
	}
        
}               
                
?>