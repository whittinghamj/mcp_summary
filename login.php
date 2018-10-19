<?php

/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('inc/db.php');
include('inc/sessions.php');
$sess = new SessionManager();
session_start();

include('inc/error/error.php');
include('inc/global_vars.php');
include('inc/functions.php');

$ip 								= $_SERVER['REMOTE_ADDR'];
$user_agent     					= $_SERVER['HTTP_USER_AGENT'];
$now 								= time();
$username 							= post('username');
$password							= post('password');

$query = "SELECT `id` FROM `users` WHERE `username` = '".$username."' AND `password` = '".$password."' ";

$result = mysql_query($query) or die(mysql_error());
$found = mysql_num_rows($result);
if($found == 0){
	status_message('danger', 'Incorrect Login details');
	go($site['url']."");
}else{
	while($row = mysql_fetch_array($result)){
		$_SESSION['account']['id']				= $row['id'];
		$_SESSION['account']['type']			= $row['type'];
	}
	go($site['url']."/dashboard");
}
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('inc/db.php');
include('inc/sessions.php');
$sess = new SessionManager();
session_start();

// include('inc/error/error.php');
include('inc/global_vars.php');
include('inc/functions.php');

// debug($_POST);

$ip 							= $_SERVER['REMOTE_ADDR'];
$user_agent     				= $_SERVER['HTTP_USER_AGENT'];

$now = time();

$email 							= post('username');
$password 						= post('password');

$postfields["username"] 		= $whmcs['username']; 
$postfields["password"] 		= $whmcs['password'];
$postfields["action"] 			= "validatelogin";
$postfields["email"] 			= $email;
$postfields["password2"] 		= $password;
$postfields["responsetype"] 	= 'json';
$postfields['accesskey']		= $whmcs['accesskey'];

// check login for a customer, if fails then assume its a whmcs login attemp
$query = "SELECT * FROM `users` WHERE `email` = '".$email."' AND `password` = '".$password."' ";
$result = mysql_query($query) or die(mysql_error());
$found = mysql_num_rows($result);
if($found > 0){
	while($row = mysql_fetch_array($result)){
		$_SESSION['account']['id']				= $row['id'];
		$_SESSION['account']['type']			= $row['type'];
		$_SESSION['account']['email']			= $row['email'];
	}
	go($site['url']."/dashboard");
}

// ok this looks like a WHMCS login attemp, lets process it.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 300);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSLVERSION,3);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
$data = curl_exec($ch);
curl_close($ch);

$results = json_decode($data, true);

// debug($results);

if($results["result"]=="success"){
    // login confirmed
	
	$_SESSION['account']['id'] 		= $results['userid'];
	$_SESSION['account']['email'] 	= $email;
	$user_id 						= $results['userid'];

	// lets get client details
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["responsetype"] 		= "json";
	$postfields["action"] 				= "getclientsdetails";
	$postfields["clientid"] 			= $user_id;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$client_data = curl_exec($ch);
	curl_close($ch);

	$client_data = json_decode($client_data, true);

	// lets check their product status for late / non payment
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["responsetype"] 		= "json";
	$postfields["action"] 				= "getclientsproducts";
	$postfields["clientid"] 			= $user_id;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSLVERSION,3);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);

	$data = json_decode($data, true);

	// debug($data);

	foreach($data['products']['product'] as $product)
	{
		if (in_array($product['pid'], $product_ids)) {
		    // product match for this platform

		    if($product['status'] != 'Active'){
				// forward to billing area
				$whmcsurl 			= "https://clients.deltacolo.com/dologin.php";
				$autoauthkey 		= "admin1372";
				$email 				= $email;
				
				$timestamp 			= time(); 
				$goto 				= "clientarea.php";
				
				$hash 				= sha1($email.$timestamp.$autoauthkey);
				
				$url 				= $whmcsurl."?email=$email&timestamp=$timestamp&hash=$hash&goto=".urlencode($goto);
				go($url);
			}else{	
				$query = "SELECT * FROM `users` WHERE `id` = '".$user_id."' " ;
				$result = mysql_query($query) or die(mysql_error());
				$total_rows = mysql_num_rows($result);
				
				status_message('success', 'Login successful');

				$_SESSION['account']['type']			=  'admin';
				
				if($total_rows == 0){

					$insert_query = "INSERT INTO `users` 
					(`id`, `added`, `type`, `first_name`, `last_name`, `email`)
					VALUE
					('".$user_id."', '".time()."', 'admin', '".$client_data['firstname']."', '".$client_data['lastname']."', '".$client_data['email']."' )";
					
					// echo $insert_query . '<br>';

					$input = mysql_query($insert_query) or die(mysql_error());
					
					go($site['url'].'/dashboard');
				}else{
					mysql_query("UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$user_id."' ") or die(mysql_error());
					mysql_query("UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$user_id."' ") or die(mysql_error());
					mysql_query("UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$user_id."' ") or die(mysql_error());
					mysql_query("UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$user_id."' ") or die(mysql_error());
					
					go($site['url'].'/dashboard');
				}
			}
		}
	}
} else {
	// login rejected
	status_message('danger', 'Incorrect Login details');
	go($site['url'].'/index');
}