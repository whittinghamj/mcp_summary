<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../dashboard/inc/db.php');
include('../dashboard/inc/sessions.php');
$sess = new SessionManager();
session_start();

// include('inc/error/error.php');
include('inc/global_vars.php');
include('inc/functions.php');

// debug($_POST);

$ip 							= $_SERVER['REMOTE_ADDR'];
$user_agent     				= $_SERVER['HTTP_USER_AGENT'];

$now = time();

$passcode 						= post('passcode');

// reject login if passcode is empty
if(empty($passcode))
{
	status_message('danger', 'Passcode cannot be empty.');
	go($site['url'].'/index');
}


$query = "SELECT `id` FROM `sites` WHERE `summary_passcode` = '".$passcode."' ";
$result = mysql_query($query) or die(mysql_error());
$found = mysql_num_rows($result);
if($found > 0){
	while($row = mysql_fetch_array($result)){
		$_SESSION['account']['id']				= $row['id'];

		go($site['url']."/dashboard");
		die();
	}

}else{
	status_message('danger', 'Incorrect Login details.');
	go($site['url'].'/index');
}


// login rejected
status_message('danger', 'Something went wrong.');
go($site['url'].'/index');
