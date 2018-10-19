<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('error_reporting', E_ALL); 

ini_set('session.gc_maxlifetime', 86400);

include("inc/db.php");
require_once("inc/sessions.php");
$sess = new SessionManager();
session_start();

include("inc/global_vars.php");
include("inc/functions.php");

$a = $_GET['a'];

switch ($a)
{
	case "test":
		test();
		break;

	case "accept_terms":
		accept_terms();
		break;
	
	case "my_account_update":
		my_account_update();
		break;
		
	case "my_account_update_photo":
		my_account_update_photo();
		break;

	case "get_prices":
		get_prices();
		break;

	case "get_price_plain":
		get_price_plain();
		break;
		
	// sites
	case "site_add":
		site_add();
		break;
		
	case "site_delete":
		site_delete();
		break;

	case "site_update":
		site_update();
		break;

	// ip ranges
	case "ip_range_add":
		ip_range_add();
		break;

	case "ip_range_update":
		ip_range_update();
		break;
		
	case "ip_range_delete":
		ip_range_delete();
		break;

	case "ajax_get_ip_ranges":
		ajax_get_ip_ranges();
		break;

	// jobs
	case "job_add":
		job_add();
		break;
	
	// pools
	case "pool_add":
		pool_add();
		break;
	
	case "pool_update":
		pool_update();
		break;
	
	case "pool_delete":
		pool_delete();
		break;
		
	case "pool_profile_add":
		pool_profile_add();
		break;
		
	case "pool_profile_update":
		pool_profile_update();
		break;
		
	case "pool_profile_delete":
		pool_profile_delete();
		break;
	
	case "default_pool_update":
		default_pool_update();
		break;

	// miners
	case "miner_update":
		miner_update();
		break;

	case "miner_delete":
		miner_delete();
		break;

	case "miner_update_owner":
		miner_update_owner();
		break;
	
	case "miner_pause_unpause":
		miner_pause_unpause();
		break;

	case "pause_unpause_all_miners":
		pause_unpause_all_miners();
		break;

	case "ajax_show_site_summary":
		ajax_show_site_summary();
		break;
	
	case "ajax_show_miners":
		ajax_show_miners();
		break;

	case "ajax_show_miner":
		ajax_show_miner();
		break;

	case "ajax_show_miners_customer":
		ajax_show_miners_customer();
		break;

	case "miner_update_multi":
		miner_update_multi();
		break;
		
	// other
	case "set_status_message":
		set_status_message();
		break;
		
	case "ping_host":
		ping_host();
		break;
		
	case "get_client_details":
		external_get_client_details();
		break;

	case "add_order":
		add_order();
		break;

	// customers
	case "customer_add":
		customer_add();
		break;

	case "customer_update":
		customer_update();
		break;

	case "customer_delete":
		customer_delete();
		break;

	// ethos
	case "ethos_add":
		ethos_add();
		break;

	case "ethos_update":
		ethos_update();
		break;

	case "ethos_delete":
		ethos_delete();
		break;


// default
				
	default:
		home();
		break;
}

function home()
{
	die('access denied to function name ' . $_GET['a']);
}

function ping_host()
{
	$data['ip'] = $_GET['ip'];
	$ping = ping($_GET['ip']);
	if($ping == ''){
		$data['status'] = 'offline';
	}else{
		$data['status'] = 'online';
	}
	
	echo json_encode($data);
}

function test()
{
	echo '<h3>$_SESSION</h3>';
	echo '<pre>';
	print_r($_SESSION);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_POST</h3>';
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
	echo '<hr>';
	echo '<h3>$_GET</h3>';
	echo '<pre>';
	print_r($_GET);
	echo '</pre>';
	echo '<hr>';
}

function accept_terms()
{
	$uid					= $_SESSION['account']['id'];
	$time					= time();
	$ip_address				= $_SERVER['REMOTE_ADDR'];

	mysql_query("UPDATE `users` SET `accepted_terms` = 'yes' WHERE `id` = '".$uid."' ") or die(mysql_error());
	mysql_query("UPDATE `users` SET `accepted_terms_on` = '".$time."' WHERE `id` = '".$uid."' ") or die(mysql_error());
	mysql_query("UPDATE `users` SET `accepted_terms_ip` = '".$ip_address."' WHERE `id` = '".$uid."' ") or die(mysql_error());
	
	go($_SERVER['HTTP_REFERER']);
}

function my_account_update()
{
	global $whmcs, $site;
	
	$user_id 						= $_SESSION['account']['id'];
	
	$firstname 						= clean_string(addslashes($_POST['firstname']));
	$lastname 						= clean_string(addslashes($_POST['lastname']));
	$companyname 					= clean_string(addslashes($_POST['companyname']));
	$email 							= clean_string(addslashes($_POST['email']));
	$phonenumber 					= clean_string(addslashes($_POST['phonenumber']));
	$address_1 						= clean_string(addslashes($_POST['address1']));
	$address_2 						= clean_string(addslashes($_POST['address2']));
	$address_city 					= clean_string(addslashes($_POST['city']));
	$address_state 					= clean_string(addslashes($_POST['state']));
	$address_zip 					= clean_string(addslashes($_POST['postcode']));
	$address_country 				= clean_string(addslashes($_POST['country']));

	$notification_email				= clean_string(addslashes($_POST['notification_email']));
	mysql_query("UPDATE `users` SET `notification_email` = '".$notification_email."' WHERE `id` = '".$user_id."' ") or die(mysql_error());		

	$notification_tel				= clean_string(addslashes($_POST['notification_tel']));
	mysql_query("UPDATE `users` SET `notification_tel` = '".$notification_tel."' WHERE `id` = '".$user_id."' ") or die(mysql_error());	

	$gui_settings['show_site_summary']					= clean_string(addslashes($_POST['show_site_summary']));
	mysql_query("UPDATE `users` SET `show_site_summary` = '".$gui_settings['show_site_summary']."' WHERE `id` = '".$user_id."' ") or die(mysql_error());
	
	$gui_settings['show_dashboard_summary']					= clean_string(addslashes($_POST['show_dashboard_summary']));
	mysql_query("UPDATE `users` SET `show_dashboard_summary` = '".$gui_settings['show_dashboard_summary']."' WHERE `id` = '".$user_id."' ") or die(mysql_error());	

	$postfields["username"] 		= $whmcs['username'];
	$postfields["password"] 		= $whmcs['password'];
	
	$postfields["action"] 			= "updateclient";
	$postfields["clientid"] 		= $user_id;
	$postfields["firstname"] 		= $firstname;
	$postfields["lastname"] 		= $lastname;
	$postfields["companyname"] 		= $companyname;
	$postfields["email"] 			= $email;
	$postfields["phonenumber"] 		= $phonenumber;
	$postfields["address1"] 		= $address_1;
	$postfields["address2"] 		= $address_2;
	$postfields["city"] 			= $address_city;
	$postfields["state"] 			= $address_state;
	$postfields["postcode"] 		= $address_zip;
	$postfields["country"] 			= $address_country;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $whmcs['url']);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	$data = curl_exec($ch);
	curl_close($ch);
	
	$data = explode(";",$data);
	foreach ($data AS $temp) {
		$temp = explode("=",$temp);
	  	$results[$temp[0]] = $temp[1];
	}
		
	if($results["result"]=="success") {
		status_message('success', 'Your account details have been updated.');
	}else{
		status_message('danger', 'There was an error updating your account details.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function my_account_update_photo()
{
	global $whmcs, $site;
	$user_id 					= $_SESSION['account']['id'];

	$fileName = $_FILES["file1"]["name"]; // The file name
	
	$fileName = str_replace('"', '', $fileName);
	$fileName = str_replace("'", '', $fileName);
	$fileName = str_replace(' ', '_', $fileName);
	$fileName = str_replace(array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '+', ';', ':', '\\', '|', '~', '`', ',', '<', '>', '/', '?', '§', '±',), '', $fileName);
	// $fileName = $fileName . '.' . $fileExt;
	
	$fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
	$fileType = $_FILES["file1"]["type"]; // The type of file it is
	$fileSize = $_FILES["file1"]["size"]; // File size in bytes
	$fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true
	if (!$fileTmpLoc) { // if file not chosen
		echo "Please select a photo to upload first.";
		exit();
	}
	
	// check if folder exists for customer, if not create it and continue
	if (!file_exists('uploads/'.$user_id) && !is_dir('uploads/'.$user_id)) {
		mkdir('uploads/'.$user_id);
	} 
	
	// handle the uploaded file
	if(move_uploaded_file($fileTmpLoc, "uploads/".$user_id."/".$fileName)){
		
		// insert into the database
		mysql_query("UPDATE users SET `avatar` = '".$site['url']."/uploads/".$user_id."/".$fileName."' WHERE `id` = '".$user_id."' ") or die(mysql_error());		
		
		// report
		echo "<font color='#18B117'><b>Upload Complete</b></font>";
		
	}else{
		echo "ERROR: Oops, something went very wrong. Please try again or contact support for more help.";
		exit();
	}	
}

function set_status_message()
{
	$status 				= $_GET['status'];
	$message				= $_GET['message'];
	
	status_message($status, $message);
}

// sites
function site_add()
{
	$uid					= $_SESSION['account']['id'];
	$name					= clean_string($_POST['name']);
	$location				= clean_string($_POST['location']);
	$city					= clean_string($_POST['city']);
	$country				= clean_string($_POST['country']);
	$power_cost				= clean_string($_POST['power_cost']);
	$voltage				= clean_string($_POST['voltage']);
	$voltage				= str_replace(array('v','volt','volts','voltage'), '', $voltage);
	$max_amps				= clean_string($_POST['max_amps']);
	$max_kilowatts			= clean_string($_POST['max_kilowatts']);
	$api_key				= md5(rand(00000,99999) . time());
	
	$input = mysql_query("INSERT INTO `sites` 
		(`user_id`, `name`, `location`, `city`, `country`, `power_cost`, `api_key`, `max_amps`, `max_kilowatts`, `voltage`)
		VALUE
		('".$uid."', '".$name."', '".$location."', '".$city."', '".$country."', '".$power_cost."', '".$api_key."', '".$max_amps."', '".$max_kilowatts."', '".$voltage."' )") or die(mysql_error());
	
	$insert_id = mysql_insert_id();
	
	if($input) {
		status_message('success', 'Site has been created.');
	}else{
		status_message('danger', 'There was an error creating your site, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function site_update()
{
	$uid							= $_SESSION['account']['id'];
	$site_id						= clean_string($_GET['site_id']);
	
	$data['name']					= clean_string($_POST['name']);
	$data['location']				= clean_string($_POST['location']);
	$data['city']					= clean_string($_POST['city']);
	$data['country']				= clean_string($_POST['country']);

	$data['power_cost']				= clean_string($_POST['power_cost']);
	$data['max_amps']				= clean_string($_POST['max_amps']);
	$data['max_kilowatts']			= clean_string($_POST['max_kilowatts']);
	$data['voltage']				= str_replace(array('v','volt','volts','voltage'), '', $voltage);

	foreach($data as $key => $value)
	{
		mysql_query("UPDATE `sites` SET `".$key."` = '".$value."' WHERE `user_id` = '".$uid."' AND `id` = '".$site_id."' ") or die(mysql_error());
	}

	status_message('success', 'Site settings have been updated.');
	
	go($_SERVER['HTTP_REFERER']);
}

function site_delete()
{
	$uid					= $_SESSION['account']['id'];
	$site_id				= clean_string($_GET['site_id']);
	
	$owner					= check_owner($uid, $site_id);
	
	if($owner > 0)
	{
		$query = mysql_query("DELETE FROM `site_groups` WHERE `site_id` = '".$site_id."' ") or die(mysql_error());
		$query = mysql_query("DELETE FROM `site_jobs` WHERE `site_id` = '".$site_id."' ") or die(mysql_error());
		$query = mysql_query("DELETE FROM `site_ip_ranges` WHERE `site_id` = '".$site_id."' ") or die(mysql_error());
		$query = mysql_query("DELETE FROM `sites` WHERE `id` = '".$site_id."' AND `user_id` = '".$uid."' ") or die(mysql_error());
	
		if($query)
		{
			status_message('success', 'Site and miners have been deleted.');
		}else{
			status_message('danger', 'There was an error, please try again.');
		}
	}else{
		status_message('danger', 'You do not own this asset and this security breach has been reported.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

// jobs
function job_add()
{
	$uid					= $_SESSION['account']['id'];
	$site_id				= clean_string($_GET['site_id']);
	$miner_id				= clean_string($_GET['miner_id']);
	$job					= clean_string($_GET['job']);

	$input = mysql_query("INSERT INTO `site_jobs` 
		(`time`, `site_id`, `miner_id`, `job`)
		VALUE
		('".time()."', '".$site_id."', '".$miner_id."', '".$job."')") or die(mysql_error());
	
	$insert_id = mysql_insert_id();
	
	if($input) {
		if($job == 'network_scan')
		{
			status_message('success', 'Network Scan job added. Newly found miners will be added to your site shortly.');
		}elseif($job == 'reboot_miner'){
			status_message('success', 'Miner Reboot job added.');
		}else{
			status_message('success', 'Job has been added.');
		}
	}else{
		status_message('danger', 'There was an error adding the job, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

// pools
function pool_add()
{
	$uid					= $_SESSION['account']['id'];
	$name					= clean_string($_POST['name']);
	$url					= clean_string($_POST['url']);
	$port					= clean_string($_POST['port']);
	$username				= clean_string($_POST['username']);
	$password				= clean_string($_POST['password']);
	$coin_id				= clean_string($_POST['coin_id']);

	$input = mysql_query("INSERT INTO `pools` 
		(`user_id`, `name`, `url`, `port`, `username`, `password`, `coin_id`)
		VALUE
		('".$uid."', '".$name."','".$url."', '".$port."', '".$username."', '".$password."', '".$coin_id."')") or die(mysql_error());
	
	$insert_id = mysql_insert_id();
	
	if($input) {
		status_message('success', 'Pool has been added.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function pool_update()
{
	$uid					= $_SESSION['account']['id'];
	$pool_id				= clean_string($_POST['pool_id']);
	$name					= clean_string($_POST['name']);
	$url					= clean_string($_POST['url']);
	$port					= clean_string($_POST['port']);
	$username				= clean_string($_POST['username']);
	$password				= clean_string($_POST['password']);
	$nicehash_api_id		= clean_string($_POST['nicehash_api_id']);
	$nicehash_api_key		= clean_string($_POST['nicehash_api_key']);
	$coin_id				= clean_string($_POST['coin_id']);
	$coin_id				= clean_string($_POST['coin_id']);


	mysql_query("UPDATE `pools` SET `name` = '".$name."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pools` SET `url` = '".$url."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pools` SET `port` = '".$port."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pools` SET `username` = '".$username."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pools` SET `password` = '".$password."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pools` SET `nicehash_api_id` = '".$nicehash_api_id."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pools` SET `nicehash_api_key` = '".$nicehash_api_key."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pools` SET `coin_id` = '".$coin_id."' WHERE `user_id` = '".$uid."' AND `id` = '".$pool_id."' ") or die(mysql_error());
	
	status_message('success', 'Pool has been updated.');
	
	go($_SERVER['HTTP_REFERER']);
}

function pool_delete()
{
	$uid					= $_SESSION['account']['id'];
	$pool_id				= clean_string($_GET['pool_id']);

	$query = mysql_query("DELETE FROM `pools` WHERE `id` = '".$pool_id."' AND `user_id` = '".$uid."' ") or die(mysql_error());
	
	if($query) {
		status_message('success', 'Pool has been deleted.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function default_pool_update()
{
	$uid					= $_SESSION['account']['id'];
	$algorithm				= clean_string($_POST['algorithm']);
	$pool_0					= clean_string($_POST['pool_0']);
	$pool_1					= clean_string($_POST['pool_1']);
	$pool_2					= clean_string($_POST['pool_2']);

	$query = "SELECT * FROM `site_default_pools` WHERE `user_id` = '".$uid."' AND `algorithm` = '".$algorithm."' ";	
	$result = mysql_query($query) or die(mysql_error());
	$found = mysql_num_rows($result);

	if($found == 0)
	{
		$input = mysql_query("INSERT INTO `site_default_pools` 
		(`user_id`, `pool_0`, `pool_1`, `pool_2`, `algorithm`)
		VALUE
		('".$uid."', '".$pool_0."', '".$pool_1."', '".$pool_2."', '".$algorithm."')") or die(mysql_error());
	}else{
		$update = mysql_query("UPDATE `site_default_pools` SET `pool_0` = '".$pool_0."' WHERE `user_id` = '".$uid."' AND `algorithm` = '".$algorithm."' ") or die(mysql_error());
		$update = mysql_query("UPDATE `site_default_pools` SET `pool_1` = '".$pool_1."' WHERE `user_id` = '".$uid."' AND `algorithm` = '".$algorithm."' ") or die(mysql_error());
		$update = mysql_query("UPDATE `site_default_pools` SET `pool_2` = '".$pool_2."' WHERE `user_id` = '".$uid."' AND `algorithm` = '".$algorithm."' ") or die(mysql_error());
	}

	build_default_config_file($algorithm);
	
	if($update || $input) {
		status_message('success', 'Default Pool has been updated.');
	}else{
		status_message('danger', 'There was an error updating the Default Pool, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function pool_profile_add()
{
	$uid					= $_SESSION['account']['id'];
	$name					= clean_string($_POST['name']);

	$input = mysql_query("INSERT INTO `pool_profiles` 
		(`user_id`, `name`)
		VALUE
		('".$uid."', '".$name."')") or die(mysql_error());
	
	$insert_id = mysql_insert_id();
	
	if($input) {
		status_message('success', 'Pool Profile has been added.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go("dashboard?c=pool_profile&profile_id=".$insert_id);
}

function pool_profile_update()
{
	$uid					= $_SESSION['account']['id'];
	$profile_id				= clean_string($_GET['profile_id']);
	$name					= clean_string($_POST['name']);
	$pool_0					= clean_string($_POST['pool_0']);
	$pool_1					= clean_string($_POST['pool_1']);
	$pool_2					= clean_string($_POST['pool_2']);

	mysql_query("UPDATE `pool_profiles` SET `name` = '".$name."' WHERE `user_id` = '".$uid."' AND `id` = '".$profile_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pool_profiles` SET `pool_0` = '".$pool_0."' WHERE `user_id` = '".$uid."' AND `id` = '".$profile_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pool_profiles` SET `pool_1` = '".$pool_1."' WHERE `user_id` = '".$uid."' AND `id` = '".$profile_id."' ") or die(mysql_error());
	mysql_query("UPDATE `pool_profiles` SET `pool_2` = '".$pool_2."' WHERE `user_id` = '".$uid."' AND `id` = '".$profile_id."' ") or die(mysql_error());
	
	$query 			= "SELECT `id` FROM `site_miners` WHERE `pool_profile_id` = '".$profile_id."' ";
	$result 		= mysql_query($query) or die(mysql_error());
	$records		= mysql_num_rows($result);
	if($records > 0)
	{
		while($row = mysql_fetch_array($result)){

		}
	}

	status_message('success', 'Pool Profile and all linked miners have been updated.');
	
	go($_SERVER['HTTP_REFERER']);
}

function pool_profile_delete()
{
	$uid					= $_SESSION['account']['id'];
	$profile_id				= clean_string($_GET['profile_id']);

	$query = mysql_query("DELETE FROM `pool_profiles` WHERE `id` = '".$profile_id."' AND `user_id` = '".$uid."' ") or die(mysql_error());
	
	// mysql_query("UPDATE `site_miners` SET `pool_profile_id` = '0' WHERE `pool_profile` = '".$profile_id."' ") ro die(mysql_error());

	if($query) {
		status_message('success', 'Pool Profile has been deleted.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go("dashboard?c=pools");
}

// ip ranges
function ip_range_add()
{
	$uid					= $_SESSION['account']['id'];
	$site_id				= clean_string($_GET['site_id']);
	$name					= clean_string($_POST['name']);
	$ip_range				= clean_string($_POST['ip_range']);
	$ip_range 				= str_replace(' ', '', $ip_range);
	
	if (!filter_var($ip_range, FILTER_VALIDATE_IP)) {
	    //IP and prefix pair is not valid
	    status_message('danger', 'There was an error adding the IP range, please try again.');
	}else{
		$input = mysql_query("INSERT INTO `site_ip_ranges` 
		(`site_id`, `name`, `ip_range`)
		VALUE
		('".$site_id."', '".$name."', '".$ip_range."')") or die(mysql_error());
	
		$insert_id = mysql_insert_id();

		status_message('success', 'IP range has been added.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function ip_range_update()
{
	$uid					= $_SESSION['account']['id'];
	$site_id				= clean_string($_GET['site_id']);
	$ip_range_id			= clean_string($_GET['ip_range_id']);
	$name					= clean_string($_POST['name']);
	$ip_range				= clean_string($_POST['ip_range']);
	$ip_range 				= str_replace(' ', '', $ip_range);
	
	if (!filter_var($ip_range, FILTER_VALIDATE_IP)) {
	    //IP and prefix pair is not valid
	    status_message('danger', 'There was an error updating the IP range, please try again.');
	}else{
		mysql_query("UPDATE `site_ip_ranges` SET `name` = '".$name."' WHERE `id` = '".$ip_range_id."' AND `site_id` = '".$site_id."' ") or die(mysql_error());
		mysql_query("UPDATE `site_ip_ranges` SET `ip_range` = '".$ip_range."' WHERE `id` = '".$ip_range_id."' AND `site_id` = '".$site_id."' ") or die(mysql_error());

		status_message('success', 'IP range has been updated.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function ip_range_delete()
{
	$uid					= $_SESSION['account']['id'];
	$ip_range_id			= clean_string($_GET['ip_range_id']);
	$site_id				= clean_string($_GET['site_id']);
	$query = mysql_query("DELETE FROM `site_ip_ranges` WHERE `id` = '".$ip_range_id."' AND `site_id` = '".$site_id."' ") or die(mysql_error());
	
	if($query) {
		status_message('success', 'IP range has been deleted.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

// miners
function miner_update()
{
	$uid						= $_SESSION['account']['id'];
	$site_id					= clean_string($_GET['site_id']);
	$miner_id					= clean_string($_GET['miner_id']);
	$miner 						= get_miner($miner_id, $uid);

	$name						= clean_string($_POST['name']);
	$name 						= str_replace(' ', '', $name);
	
	$worker_name				= clean_string($_POST['worker_name']);
	$worker_name 				= str_replace(' ', '', $worker_name);

	$ip_address					= clean_string($_POST['ip_address']);
	$ip_address					= str_replace(' ', '', $ip_address);

	$manual_fan_speed			= $_POST['manual_fan_speed'];
	$manual_freq				= $_POST['manual_freq'];

	$pool_profile_id			= clean_string($_POST['pool_profile_id']);
	$pool_0						= clean_string($_POST['pool_0']);
	$pool_1						= clean_string($_POST['pool_1']);
	$pool_2						= clean_string($_POST['pool_2']);

	$location_row				= clean_string($_POST['location_row']);
	$location_rack				= clean_string($_POST['location_rack']);
	$location_shelf				= clean_string($_POST['location_shelf']);
	$location_position			= clean_string($_POST['location_position']);

	$username					= clean_string($_POST['username']);
	$password					= clean_string($_POST['password']);

	$pool_0_url					= clean_string($_POST['pool_0_url']);
	$pool_0_user				= clean_string($_POST['pool_0_user']);
	
	$gpu_miner_id				= clean_string($_POST['gpu_miner_software']);
	$gpu_miner 					= get_gpu_miner($gpu_miner_id);

	error_log($gpu_miner['folder']);
	error_log($gpu_miner['app']);
	error_log($gpu_miner['user_options']);
	error_log($gpu_miner['system_options']);

	if(empty($name)){
		status_message('danger', 'Miner configuration ERROR. Please give your miner a name.');
		go($_SERVER['HTTP_REFERER']);
	}
	if(empty($worker_name)){
		status_message('danger', 'Miner configuration ERROR. Please give your miner a worker name.');
		go($_SERVER['HTTP_REFERER']);
	}
	if(empty($ip_address)){
		status_message('danger', 'Miner configuration ERROR. Please give your miner an IP address.');
		go($_SERVER['HTTP_REFERER']);
	}
	if(!filter_var($ip_address, FILTER_VALIDATE_IP) !== false) {
    	status_message('danger', 'Miner configuration ERROR. The IP \''.$ip_address.'\' is invalid.');
		go($_SERVER['HTTP_REFERER']);
	}

	mysql_query("UPDATE `site_miners` SET `name` = '".$name."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	mysql_query("UPDATE `site_miners` SET `worker_name` = '".$worker_name."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	mysql_query("UPDATE `site_miners` SET `ip_address` = '".$ip_address."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());

	mysql_query("UPDATE `site_miners` SET `location_row` = '".$location_row."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	mysql_query("UPDATE `site_miners` SET `location_rack` = '".$location_rack."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	mysql_query("UPDATE `site_miners` SET `location_shelf` = '".$location_shelf."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	mysql_query("UPDATE `site_miners` SET `location_position` = '".$location_position."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	mysql_query("UPDATE `site_miners` SET `manual_fan_speed` = '".$manual_fan_speed."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());

	if($miner['type'] == 'asic'){
		mysql_query("UPDATE `site_miners` SET `username` = '".$username."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		mysql_query("UPDATE `site_miners` SET `password` = '".$password."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		mysql_query("UPDATE `site_miners` SET `manual_freq` = '".$manual_freq."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		mysql_query("UPDATE `site_miners` SET `pool_profile_id` = '".$pool_profile_id."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());

		if($pool_profile_id == 0)
		{
			mysql_query("UPDATE `site_miners` SET `pool_0_id` = '".$pool_0."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
			mysql_query("UPDATE `site_miners` SET `pool_1_id` = '".$pool_1."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
			mysql_query("UPDATE `site_miners` SET `pool_2_id` = '".$pool_2."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		}else{
			$pool_profile = get_pool_profile($pool_profile_id);
			
			if(!isset($pool_profile['pool'][0]['id'])){
				$pool_profile['pool'][0]['id'] = 0;
			}
			mysql_query("UPDATE `site_miners` SET `pool_0_id` = '".$pool_profile['pool'][0]['id']."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
			
			if(!isset($pool_profile['pool'][1]['id'])){
				$pool_profile['pool'][1]['id'] = 0;
			}
			mysql_query("UPDATE `site_miners` SET `pool_1_id` = '".$pool_profile['pool'][1]['id']."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
			
			if(!isset($pool_profile['pool'][2]['id'])){
				$pool_profile['pool'][2]['id'] = 0;
			}
			mysql_query("UPDATE `site_miners` SET `pool_2_id` = '".$pool_profile['pool'][2]['id']."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		}
	}

	if($miner['type'] == 'gpu'){
		if(!empty($pool_0_url)){
			mysql_query("UPDATE `site_miners` SET `pool_0_url` = '".$pool_0_url."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		}
		if(!empty($pool_0_user)){
			mysql_query("UPDATE `site_miners` SET `pool_0_user` = '".$pool_0_user."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		}

		mysql_query("UPDATE `site_miners` SET `software_version` = '".$gpu_miner['folder']."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		mysql_query("UPDATE `site_miners` SET `gpu_miner_software_folder` = '".$gpu_miner['folder']."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		mysql_query("UPDATE `site_miners` SET `gpu_miner_software_binary` = '".$gpu_miner['app']."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		mysql_query("UPDATE `site_miners` SET `gpu_miner_vars` = '".$gpu_miner['user_options']." ".$gpu_miner['system_options']."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	}

	build_miner_config_file($miner_id);
	
	system_job_add($_SESSION['account']['id'], $site_id, $miner_id, 'update_config_file', 'User updated the miner settings.');
	
	status_message('success', 'Miner configuration has been updated and will take effect shortly.');
	
	go($_SERVER['HTTP_REFERER']);
}

function miner_delete()
{
	$uid					= $_SESSION['account']['id'];
	$miner_id				= clean_string($_GET['miner_id']);
	$site_id				= clean_string($_GET['site_id']);

	$query = mysql_query("DELETE FROM `site_jobs` WHERE `miner_id` = '".$miner_id."' AND `site_id` = '".$site_id."' ") or die(mysql_error());
	$query = mysql_query("DELETE FROM `site_miners_stats` WHERE `miner_id` = '".$miner_id."' ") or die(mysql_error());
	$query = mysql_query("DELETE FROM `site_miners` WHERE `id` = '".$miner_id."' AND `site_id` = '".$site_id."' ") or die(mysql_error());
	
	if($query) {
		status_message('success', 'Miner has been deleted.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go('dashboard?c=site&site_id='.$site_id);
}

function miner_update_owner()
{
	$uid						= $_SESSION['account']['id'];
	$site_id					= clean_string($_GET['site_id']);
	$miner_id					= clean_string($_GET['miner_id']);

	$customer_id				= clean_string($_POST['customer_id']);

	mysql_query("UPDATE `site_miners` SET `customer_id` = '".$customer_id."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
	
	status_message('success', 'Miner configuration has been updated.');
	
	go($_SERVER['HTTP_REFERER']);
}

function miner_update_multi()
{
	$uid						= $_SESSION['account']['id'];
	$site_id					= clean_string($_GET['site_id']);
	$action 					= clean_string($_POST['multi_options_action']);
	$miners 					= $_POST['miner_select'];
	$pool_id 					= $_POST['set_pool_id'];
	$customer_id 				= $_POST['set_customer_id'];
	$fan_speed 					= $_POST['set_fan_speed'];

	$dump['session']			= $_SESSION;
	$dump['get']				= $_GET;
	$dump['post']				= $_POST;

	$input = mysql_query("INSERT INTO `dump` 
		(`data`)
		VALUE
		('".json_encode($dump)."')") or die(mysql_error());

	if($action == 'reboot')
	{
		foreach($miners as $miner_id)
		{
			mysql_query("UPDATE `site_miners` SET `paused` = 'no' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
			
			system_job_add($uid, $site_id, $miner_id, 'reboot_miner', 'User issued bulk miner reboot.');
		}

		status_message('success', 'Selected miners will be rebooted shortly.');
	}elseif($action == 'update')
	{
		foreach($miners as $miner_id)
		{
			build_miner_config_file($miner_id);
			system_job_add($uid, $site_id, $miner_id, 'update_config_file', 'User issued bulk miner update.');
		}
		
		status_message('success', 'Selected miners will be updated to latest configuration shortly.');
	}elseif($action == 'set_pool')
	{
		foreach($miners as $miner_id)
		{
			mysql_query("UPDATE `site_miners` SET `pool_0_id` = '".$pool_id."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());

			build_miner_config_file($miner_id);
			system_job_add($uid, $site_id, $miner_id, 'update_config_file', 'User issued bulk miner update.');
		}
		
		status_message('success', 'Selected miners will be updated to new configuration shortly.');
	}elseif($action == 'set_owner')
	{
		foreach($miners as $miner_id)
		{
			mysql_query("UPDATE `site_miners` SET `customer_id` = '".$customer_id."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		}
		
		status_message('success', 'Ownership of selected miners have been updated.');
	}elseif($action == 'pause')
	{
		foreach($miners as $miner_id)
		{
			mysql_query("UPDATE `site_miners` SET `paused` = 'yes' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
			system_job_add($uid, $site_id, $miner_id, 'pause_miner', 'User has paused mining.');
		}

		status_message('success', 'Selected miners will be paused shortly.');
	}elseif($action == 'unpause')
	{
		foreach($miners as $miner_id)
		{
			$miner_data = get_miner($miner_id, $uid);

			if($miner_data['type'] == 'asic'){
				system_job_add($uid, $site_id, $miner_id, 'reboot_miner', 'User has unpaused mining.');
			}else{
				system_job_add($uid, $site_id, $miner_id, 'unpause_miner', 'User has unpaused mining.');
			}
			mysql_query("UPDATE `site_miners` SET `paused` = 'no' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		}

		status_message('success', 'Selected miners will be unpaused shortly.');
	}elseif($action == 'set_fan_speed')
	{
		foreach($miners as $miner_id)
		{
			mysql_query("UPDATE `site_miners` SET `manual_fan_speed` = '".$fan_speed."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());

			build_miner_config_file($miner_id);
			system_job_add($uid, $site_id, $miner_id, 'update_config_file', 'User issued bulk miner update.');
		}
		
		status_message('success', 'Selected miners will be updated to new configuration shortly.');
	}elseif($action == 'upgrade_s9')
	{
		foreach($miners as $miner_id)
		{
			// mysql_query("UPDATE `site_miners` SET `manual_fan_speed` = '".$fan_speed."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());

			// build_miner_config_file($miner_id);
			system_job_add($uid, $site_id, $miner_id, 'upgrade_s9', 'User issued bulk miner Antminer S9 Inline Binary upgrade.');
		}
		
		status_message('success', 'Selected miners will be updated to new configuration shortly.');
	}elseif($action == 'downgrade_s9')
	{
		foreach($miners as $miner_id)
		{
			// mysql_query("UPDATE `site_miners` SET `manual_fan_speed` = '".$fan_speed."' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());

			// build_miner_config_file($miner_id);
			system_job_add($uid, $site_id, $miner_id, 'downgrade_s9', 'User issued bulk miner Antminer S9 Inline Binary downgrade.');
		}
		
		status_message('success', 'Selected miners will be updated to new configuration shortly.');
	}
		
	go($_SERVER['HTTP_REFERER']);
}

function miner_pause_unpause()
{
	$uid						= $_SESSION['account']['id'];
	$site_id					= clean_string($_GET['site_id']);
	$miner_id					= clean_string($_GET['miner_id']);
	$action 					= clean_string($_GET['action']);

	if($action == 'pause_miner')
	{
		mysql_query("UPDATE `site_miners` SET `paused` = 'yes' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		system_job_add($uid, $site_id, $miner_id, 'pause_miner', 'User has paused mining.');

		status_message('success', 'Selected miners will be paused shortly.');
	}

	if($action == 'unpause_miner')
	{
		mysql_query("UPDATE `site_miners` SET `paused` = 'no' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner_id."' ") or die(mysql_error());
		system_job_add($uid, $site_id, $miner_id, 'unpause_miner', 'User has started mining.');

		status_message('success', 'Selected miners will be unpaused shortly.');
	}
		
	go($_SERVER['HTTP_REFERER']);
}

function pause_unpause_all_miners()
{
	$uid						= $_SESSION['account']['id'];
	$site_id					= clean_string($_GET['site_id']);
	$miners					= get_miners($site_id);
	$action 					= clean_string($_GET['action']);

	if($action == 'pause')
	{
		foreach($miners as $miner)
		{
			mysql_query("UPDATE `site_miners` SET `paused` = 'yes' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner['id']."' ") or die(mysql_error());
			system_job_add($uid, $site_id, $miner['id'], 'pause_miner', 'User has paused mining.');
		}

		status_message('success', 'All miners will be paused shortly.');
	}

	if($action == 'unpause')
	{
		foreach($miners as $miner)
		{
			mysql_query("UPDATE `site_miners` SET `paused` = 'no' WHERE `site_id` = '".$site_id."' AND `id` = '".$miner['id']."' ") or die(mysql_error());
			system_job_add($uid, $site_id, $miner['id'], 'unpause_miner', 'User has unpaused mining.');
		}

		status_message('success', 'Selected miners will be unpaused shortly.');
	}
		
	go($_SERVER['HTTP_REFERER']);
}

function ajax_show_site_summary()
{
	$uid				= $_SESSION['account']['id'];
	$site_id			= $_GET['site_id'];
	
	header("Content-Type:application/json; charset=utf-8");

	$data 				= get_site($site_id);
	
	json_output($data);
}


function ajax_show_miners()
{
	$uid				= $_SESSION['account']['id'];
	$site_id			= get('site_id');
	$type				= get('type');
	
	header("Content-Type:application/json; charset=utf-8");
	
	// $miners 			= get_miners($site_id, $uid, $type);
	$miners 			= get_miners($site_id, $uid, '');

	json_output($miners);
}

function ajax_show_miner()
{
	$uid				= $_SESSION['account']['id'];
	$miner_id			= get('miner_id');
	$type				= get('type');

	// error_log("UID: " . $uid . " | Miner ID: " . $miner_id);
	
	header("Content-Type:application/json; charset=utf-8");
	
	// $miner['dev']		= 'Polling DB for miner id: ' $miner_id;
	$miner 				= get_miner($miner_id, $uid);

	// error_log(print_r($miner));

	json_output($miner);
}

function ajax_get_ip_ranges()
{
	$uid				= $_SESSION['account']['id'];
	$site_id			= get('site_id');
	
	header("Content-Type:application/json; charset=utf-8");

	$data 				= get_ip_ranges($site_id);
	
	json_output($data);
}

function ajax_show_miners_customer()
{
	$uid				= $_SESSION['account']['id'];
	$customer_id		= get('customer_id');
	
	header("Content-Type:application/json; charset=utf-8");

	echo json_encode(get_customer_miners($customer_id), true);
}

function get_prices()
{
	$data['BTC'] 		= get_crypto_prices('BTC');
	$data['BCH'] 		= get_crypto_prices('BCH');
	$data['DASH'] 		= get_crypto_prices('DASH');

	json_output($data);
}

function get_price_plain()
{
	$query 		= "SELECT * FROM `crypto_prices` WHERE `currency` = 'BTC' ";
	$result 	= mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		echo $row['usd'];
	}
}

// customers
function customer_add()
{
	$uid					= $_SESSION['account']['id'];
	$first_name				= clean_string($_POST['first_name']);
	$last_name				= clean_string($_POST['last_name']);
	$password				= clean_string($_POST['password']);
	$email					= clean_string($_POST['email']);
	
	$input = mysql_query("INSERT INTO `users` 
	(`added`, `first_name`, `last_name`, `password`, `email`, `admin_id`)
	VALUE
	('".time()."', '".$first_name."', '".$last_name."', '".$password."', '".$email."', '".$uid."')") or die(mysql_error());

	$insert_id = mysql_insert_id();

	status_message('success', 'Customer has been added.');
	
	go($_SERVER['HTTP_REFERER']);
}

function customer_update()
{
	$uid					= $_SESSION['account']['id'];
	$customer_id			= clean_string($_GET['customer_id']);
	$first_name				= clean_string($_POST['first_name']);
	$last_name				= clean_string($_POST['last_name']);
	$password				= clean_string($_POST['password']);
	$email					= clean_string($_POST['email']);
	
	mysql_query("UPDATE `users` SET `first_name` = '".$first_name."' WHERE `id` = '".$customer_id."' AND `admin_id` = '".$uid."' ") or die(mysql_error());
	mysql_query("UPDATE `users` SET `last_name` = '".$last_name."' WHERE `id` = '".$customer_id."' AND `admin_id` = '".$uid."' ") or die(mysql_error());
	mysql_query("UPDATE `users` SET `password` = '".$password."' WHERE `id` = '".$customer_id."' AND `admin_id` = '".$uid."' ") or die(mysql_error());
	mysql_query("UPDATE `users` SET `email` = '".$email."' WHERE `id` = '".$customer_id."' AND `admin_id` = '".$uid."' ") or die(mysql_error());

	status_message('success', 'Customer has been updated.');
	
	go($_SERVER['HTTP_REFERER']);
}

function customer_delete()
{
	$uid					= $_SESSION['account']['id'];
	$customer_id			= clean_string($_GET['customer_id']);

	mysql_query("UPDATE `site_miners` SET `customer_id` = '0' WHERE `customer_id` = '".$customer_id."' ") or die(mysql_error());
	$query = mysql_query("DELETE FROM `users` WHERE `id` = '".$customer_id."' AND `admin_id` = '".$uid."' ") or die(mysql_error());
	
	if($query) {
		status_message('success', 'Customer has been deleted.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function add_order()
{
	global $whmcs;

	$product_id				= get('product_id');

	// lets get client details
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["responsetype"] 		= "json";
	$postfields["action"] 				= "AddOrder";
	$postfields["clientid"] 			= $_SESSION['account']['id'];
	$postfields["pid"] 					= $product_id;
	$postfields["paymentmethod"] 		= 'paypal';
	
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

	if($data['result'] == 'success'){
		$invoice_id = $data['invoiceid'];
		
		$whmcsurl 			= "https://clients.deltacolo.com/dologin.php";
		$autoauthkey 		= "admin1372";
		$email 				= $email;
		
		$timestamp 			= time(); 
		$goto 				= "viewinvoice.php?id=".$invoice_id;
		
		$hash 				= sha1($_SESSION['account']['email'].$timestamp.$autoauthkey);
		
		$url 				= $whmcsurl."?email=".$_SESSION['account']['email']."&timestamp=".$timestamp."&hash=".$hash."&goto=".urlencode($goto);
		go($url);
	}else{
		status_message('danger', 'There was an error, please try again. <br>' . $data['message']);
		go($_SERVER['HTTP_REFERER']);
	}
}

// ethos
function ethos_add()
{
	$uid					= $_SESSION['account']['id'];
	$site_id				= clean_string($_GET['site_id']);
	$name					= clean_string($_POST['name']);
	$panel_url				= clean_string($_POST['panel_url']);

	$panel_url				= str_replace(array('http://','https://','.','/','\\'), '', $panel_url);

	// check if panel is already added to an account
	$query = "SELECT `id` FROM `site_ethos_portals` WHERE `panel_url` = '".$panel_url."' ";
	$result = mysql_query($query) or die(mysql_error());
	$match = mysql_num_rows($result);

	if($match == 0)
	{
		$input = mysql_query("INSERT INTO `site_ethos_portals` 
		(`site_id`, `user_id`, `name`, `panel_url`)
		VALUE
		('".$site_id."', '".$uid."', '".$name."', '".$panel_url."')") or die("Insert Error: <hr>" . mysql_error());

		$insert_id = mysql_insert_id();

		status_message('success', 'ethOS panel has been added.');
	}else{
		status_message('danger', 'Unable to add ethOS Panel called "'.$panel_url.'", it has already been added by you or someone else.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

function ethos_update()
{
	$uid					= $_SESSION['account']['id'];
	$site_id				= clean_string($_GET['site_id']);
	$panel_id				= clean_string($_GET['panel_id']);
	$name					= clean_string($_POST['name']);
	$panel_url				= clean_string($_POST['panel_url']);

	$panel_url				= str_replace(array('http://','https://','.','/','\\'), '', $panel_url);

	mysql_query("UPDATE `site_ethos_portals` SET `name` = '".$name."' WHERE `id` = '".$panel_id."' AND `site_id` = '".$site_id."' ") or die(mysql_error());
	mysql_query("UPDATE `site_ethos_portals` SET `panel_url` = '".$panel_url."' WHERE `id` = '".$panel_id."' AND `site_id` = '".$site_id."' ") or die(mysql_error());
	
	status_message('success', 'ethOS Panel has been added.');
	
	go($_SERVER['HTTP_REFERER']);
}

function ethos_delete()
{
	$uid					= $_SESSION['account']['id'];
	$site_id				= clean_string($_GET['site_id']);
	$panel_id				= clean_string($_GET['panel_id']);

	$query = mysql_query("DELETE FROM `site_ethos_portals` WHERE `id` = '".$panel_id."' AND `user_id` = '".$uid."' AND `site_id` = '".$site_id."' ") or die(mysql_error());
	
	if($query) {
		status_message('success', 'ethOS Panel has been deleted.');
	}else{
		status_message('danger', 'There was an error, please try again.');
	}
	
	go($_SERVER['HTTP_REFERER']);
}

?>