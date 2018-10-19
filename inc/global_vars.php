<?php

// site vars
$site['url']					= 'https://summary.miningcontrolpanel.com';
$config['url']					= $site['url'];
$site['title']					= 'MCP - Summary';
$config['title']				= $site['title'];

$site['copyright']				= 'Written by Jamie Whittingham.';
$config['copyright']			= $site['copyright'];

// logo name vars
$site['name_long']				= 'Mining<b>Control</b>Panel';
$site['name_short']				= '<b>MCP</b>';

$whmcs['url'] 					= "http://clients.deltacolo.com/includes/api.php"; # URL to WHMCS API file
$whmcs["username"] 				= "apiuser"; # Admin username goes here
$whmcs["password"] 				= md5("dje773jeidkdje773jeidk"); # Admin password goes here  
$whmcs['accesskey']				= 'admin1372';
// product details
$product_ids = array(
					52, // 5 miners - free account
					46, // 10 miners
					47, // 25 miners
					47, // 50 miners
					47, // 100 miners
					47, // 250 miners
					47, // 500 miners
					47, // 1000 miners
					47, // 2500 miners
					47, // 5000 miners
					);