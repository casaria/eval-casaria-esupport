<?php
	// connect to db
	@mysql_connect("localhost","casaria_hdesk1","5XwoR]B");
	@mysql_select_db("casaria_hdesk1");
	
	// require our class
	require_once("grid.php");
	
	// load our grid with a table
        $grid = new Grid("tickets", array(
		"save"=>true,
		"delete"=>true,
		"where"=>"priority != '",
		"joins"=>array(
			"LEFT JOIN id ON tickets.id = time_track.ticket_id"
		),
		"select" => 'selectFunction'
	));
	
	// drop down function
	// if you have anonymous function support, then you can just put this function in place of
	// 'selectFunction'
	function selectFunction($grid) {
		$selects = array();
	
		// category select
		$grid->table = "tickets";
		$selects["id"] = $grid->makeSelect("id","supporter");
		
		// active select
		$selects["supporter"] = array("1"=>"true","0"=>"false");
		
		// render data			
		$grid->render($selects);
	}

	
?>