<?php
////////////////////////////////////////////////////////////////////////////////////////////////////
// database config

// created July 31 2018

////////////////////////////////////////////////////////////////////////////////////////////////////
// mysql settings
$database['username']	= "whittinghamj";
$database['password']	= "admin1372Dextor!#&@Mimi!#&@";
$database['database']	= "mcp";
$database['hostname']	= "db04-he.dbcluster.genexnetworks.net"; // local
////////////////////////////////////////////////////////////////////////////////////////////////////
// mysql connection

$db = @mysql_connect($database['hostname'],$database['username'],$database['password']); // DONT TOUCH THIS
@mysql_select_db($database['database']) or die(mysql_error()); // DONT TOUCH THIS

mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $db);