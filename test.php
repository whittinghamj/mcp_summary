<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL); 

include('inc/simple_html_dom.php');

function dlPage($href) {

	$postfields["currency"] 			= 'USD'; 
	$postfields["electricitycost"] 		= '0.10'; 

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_URL, $href);
    curl_setopt($curl, CURLOPT_REFERER, $href);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
    $str = curl_exec($curl);
    curl_close($curl);

    // Create a DOM object
    $dom = new simple_html_dom();
    // Load HTML from a string
    $dom->load($str);

    return $dom;
}

$url 	= 'https://www.asicminervalue.com/';
$html 	= dlPage($url);
// print_r($data);

// hardware
$count = 0;
$miner['hardware'] = $html->find('.table tr td[1]');  
foreach($miner['hardware'] as $result)
{
    $data[$count]['hardware']           = trim($result->plaintext);
    $count++;
}

// release
$count = 0;
$miner['release'] = $html->find('.table tr td[2]');  
foreach($miner['release'] as $result)
{
    $data[$count]['release']           = trim($result->plaintext);
    $count++;
}

// hashrate
$count = 0;
$miner['hashrate'] = $html->find('.table tr td[3]');  
foreach($miner['hashrate'] as $result)
{
    $data[$count]['hashrate']           = trim($result->plaintext);
    $count++;
}

// power
$count = 0;
$miner['power'] = $html->find('.table tr td[4]');  
foreach($miner['power'] as $result)
{
    $data[$count]['power']           = trim($result->plaintext);
    $count++;
}

// noise
$count = 0;
$miner['noise'] = $html->find('.table tr td[5]');  
foreach($miner['noise'] as $result)
{
    $data[$count]['noise']           = trim($result->plaintext);
    $count++;
}

// algo
$count = 0;
$miner['algo'] = $html->find('.table tr td[6]');  
foreach($miner['algo'] as $result)
{
    $data[$count]['algo']           = trim($result->plaintext);
    $count++;
}

// profit
$count = 0;
$miner['profit'] = $html->find('.table tr td[7]');  
foreach($miner['profit'] as $result)
{
    $data[$count]['profit']           = trim($result->plaintext);
    $count++;
}

include('inc/db.php');

// clear the DB
mysql_query("DELETE FROM `miner_profit` ") or die(mysql_error());

// insert into DB
foreach($data as $bits){   
    $input = mysql_query("INSERT INTO `miner_profit` 
        (`hardware`, `release`, `hashrate`, `power`, `noise`, `algo`, `profit`)
        VALUE
        ('".$bits['hardware']."', '".$bits['release']."', '".$bits['hashrate']."', '".$bits['power']."', '".$bits['noise']."', '".$bits['algo']."', '".$bits['profit']."' )") or die(mysql_error());
}

echo '<pre>';
print_r($data);