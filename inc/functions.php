<?php

include('db.php');
include('global_vars.php');

function get_nicehash_balance($api_id, $api_key)
{
	$data = @file_get_contents('https://api.nicehash.com/api?method=balance&id='. $api_id . '&key=' . $api_key);
	$data = json_decode($data, true);

	if(is_array($data))
	{
		return $data;
	}else{
		return $data['status'] = 'error';
	}
}

function show_twitter($username) 
{
 	$no_of_tweets = 5;
 	$feed = "http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $no_of_tweets;
 	$xml = simplexml_load_file($feed);
	foreach($xml->children() as $child) {
		foreach ($child as $value) {
			if($value->getName() == "link") $link = $value['href'];
			if($value->getName() == "content") {
				$content = $value . "";
		echo '<p class="twit">'.$content.' <a class="twt" href="'.$link.'" title="">&nbsp; </a></p>';
			}	
		}
	}	
}

function get_products()
{
	global $whmcs;

	// lets get client details
	$postfields["username"] 			= $whmcs['username'];
	$postfields["password"] 			= $whmcs['password'];
	$postfields["responsetype"] 		= "json";
	$postfields["action"] 				= "GetProducts";
	$postfields["gid"] 					= '19';
	
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

	return $data;
}

function address_to_gps($address)
{
	$address = urlencode($address);
	$url = "https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDpMWtXLvl-a6YsAAB2HBQvK-_c0_zDtXg&address=" . $address;
	$bits = file_get_contents($url);
	$bits = json_decode($bits, true);

	$data['url']			= $url;
	$data['lat'] 	= $bits['results'][0]['geometry']['location']['lat'];
	$data['lng'] 	= $bits['results'][0]['geometry']['location']['lng'];

	return $data;
}

function ip_to_gps()
{
	$client_ip 				= $_SERVER['REMOTE_ADDR'];
	$bits 					= file_get_contents('http://api.ipstack.com/'.$client_ip.'?access_key=209136c920b48f85bf446ff732ddd3c2');
	$bits 					= json_decode($bits, true);

	return '';
}

function miner_performance($miner_id, $records)
{
	// 7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6

	$query = "SELECT `added`, `power`, `hashrate_1`,`hashrate_2`,`hashrate_3`,`pcb_temp_1` FROM `site_miners_stats` WHERE `miner_id` = '".$miner_id."' ORDER BY `added` DESC LIMIT ".$records;
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$date[]				= date('d/m/y G:i', $row['added']);
		$hashrate[]			= $row['hashrate_1'] + $row['hashrate_2'] + $row['hashrate_3'];
		$temp[]				= $row['pcb_temp_1'];
		$power[]			= $row['power'];
		$count++;
	}

	$date 					= array_reverse($date);
	$hashrate 				= array_reverse($hashrate);
	$temp 					= array_reverse($temp);
	$power 					= array_reverse($power);

	$data['dates']			= "'".implode("', '", $date)."'";
	$data['hashrate']		= implode(', ', $hashrate);
	$data['temp'] 			= implode(', ', $temp);
	$data['powert'] 		= implode(', ', $power);

	return $data;
}

function calc_day_to_month($value)
{
	$value = $value * 365 / 12;
	return $value;
}

function get_weather($city, $country)
{
	$data_raw = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".$city.",".$country."&appid=ef00633adc161887ec1c6b55ee61d39f");
	$data = json_decode($data_raw, true);
	
	$weather['icon'] 							= 'http://openweathermap.org/img/w/'.$data['weather'][0]['icon'].'.png';
	$weather['text'] 							= $data['weather'][0]['main'];
	$weather['temp']['c'] 						= k_to_c($data['main']['temp']);
	$weather['temp']['f'] 						= k_to_f($data['main']['temp']);
	$weather['wind']['speed'] 					= $data['wind']['speed'];
	$weather['wind']['direction_degrees'] 		= $data['wind']['deg'];
	
	if($data['wind']['deg']>0){$weather['wind']['direction']='N';}
	// if($data['wind']['deg']>22.5){$weather['wind']['direction']=='NNE';}
	if($data['wind']['deg']>45){$weather['wind']['direction']='NE';}
	// if($data['wind']['deg']>67.5){$weather['wind']['direction']=='ENE';}
	if($data['wind']['deg']>90){$weather['wind']['direction']='E';}
	// if($data['wind']['deg']>112.5){$weather['wind']['direction']=='ESE';}
	if($data['wind']['deg']>135){$weather['wind']['direction']='SE';}
	// if($data['wind']['deg']>157.5){$weather['wind']['direction']=='SSE';}
	if($data['wind']['deg']>180){$weather['wind']['direction']='S';}
	// if($data['wind']['deg']>202.5){$weather['wind']['direction']=='SSW';}
	if($data['wind']['deg']>225){$weather['wind']['direction']='SW';}
	// if($data['wind']['deg']>247.5){$weather['wind']['direction']=='WSW';}
	if($data['wind']['deg']>270){$weather['wind']['direction']='W';}
	// if($data['wind']['deg']>292.5){$weather['wind']['direction']=='WNW';}
	if($data['wind']['deg']>315){$weather['wind']['direction']='NW';}
	// if($data['wind']['deg']>237.5){$weather['wind']['direction']=='NNW';}
	// if($data['wind']['deg']>270){$weather['wind']['direction']=='W';}
	// if($data['wind']['deg']>270){$weather['wind']['direction']=='W';}
	// if($data['wind']['deg']>270){$weather['wind']['direction']=='W';}
	
	$weather['all']						= $data;
	
	return $weather;
}

function k_to_f($temp)
{
    if ( !is_numeric($temp) ) { return false; }
    return round((($temp - 273.15) * 1.8) + 32);
}

function k_to_c($temp)
{
	if ( !is_numeric($temp) ) { return false; }
	return round(($temp - 273.15));
}

function c_to_f($temp)
{
    $fahrenheit=$temp*9/5+32;
    return $fahrenheit ;
}

function f_to_c($temp)
{
    // $fahrenheit=$temp*9/5+32;
    $cen = ($temp - 32) / 1.8;
    return $cen ;
}

function getcontents($href)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_URL, $href);
	curl_setopt($curl, CURLOPT_REFERER, $href);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/533.4 (KHTML, like Gecko) Chrome/5.0.375.125 Safari/533.4");
	$str = curl_exec($curl);
	curl_close($curl);

	$dom = new simple_html_dom();

	$dom->load($str);
	return $dom;
}

function console_output($data)
{
	$timestamp = date("Y-m-d H:i:s", time());
	echo "[" . $timestamp . "] - " . $data . "\n";
}

function json_output($data)
{
	// $data['timestamp']		= time();
	$data 					= json_encode($data);
	echo $data;
	die();
}

function getsock($addr, $port)
{
	$socket = null;
 	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
 	if ($socket === false || $socket === null)
 	{
    	$error = socket_strerror(socket_last_error());
    	$msg = "socket create(TCP) failed";
    	echo "ERR: $msg '$error'\n";
    	return null;
 	}

 	$res = socket_connect($socket, $addr, $port);
 	if ($res === false)
 	{
    	$error = socket_strerror(socket_last_error());
    	$msg = "socket connect($addr,$port) failed";
    	echo "ERR: $msg '$error'\n";
    	socket_close($socket);
    	return null;
 	}
 	return $socket;
}

function readsockline($socket)
{
	$line = '';
	while (true)
	{
    	$byte = socket_read($socket, 1);
    	if ($byte === false || $byte === '')
        	break;
    	if ($byte === "\0")
        	break;
    	$line .= $byte;
	}
 	return $line;
}

function request($ip, $cmd)
{
 $socket = getsock($ip, 4028);
 if ($socket != null)
 {
    socket_write($socket, $cmd, strlen($cmd));
    $line = readsockline($socket);
    socket_close($socket);

    if (strlen($line) == 0)
    {
        echo "WARN: '$cmd' returned nothing\n";
        return $line;
    }

    // print "$cmd returned '$line'\n";

    if (substr($line,0,1) == '{')
        return json_decode($line, true);

    $data = array();

    $objs = explode('|', $line);
    foreach ($objs as $obj)
    {
        if (strlen($obj) > 0)
        {
            $items = explode(',', $obj);
            $item = $items[0];
            $id = explode('=', $items[0], 2);
            if (count($id) == 1 or !ctype_digit($id[1]))
                $name = $id[0];
            else
                $name = $id[0].$id[1];

            if (strlen($name) == 0)
                $name = 'null';

            if (isset($data[$name]))
            {
                $num = 1;
                while (isset($data[$name.$num]))
                    $num++;
                $name .= $num;
            }

            $counter = 0;
            foreach ($items as $item)
            {
                $id = explode('=', $item, 2);
                if (count($id) == 2)
                    $data[$name][$id[0]] = $id[1];
                else
                    $data[$name][$counter] = $id[0];

                $counter++;
            }
        }
    }

    return $data;
 }

 return null;
}

function ping($host)
{
		exec(sprintf('ping -c 5 -W 5 %s', escapeshellarg($host)), $res, $rval);
		return $rval === 0;
}

function cidr_to_range($cidr)
{
  	$range = array();
  	$cidr = explode('/', $cidr);
  	$range[0] = long2ip((ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1]))));
  	$range[1] = long2ip((ip2long($cidr[0])) + pow(2, (32 - (int)$cidr[1])) - 1);
  	return $range;
}

function show_mining_calc()
{
	$query = "SELECT * FROM `miner_profit` ORDER BY 'hardware' ASC";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){

		$profit['daily']			= str_replace(' /day', '', $row['profit']);
		$profit['daily']			= str_replace('$', '', $profit['daily']);
		$profit['yearly']			= $profit['daily'] * 365;
		$profit['monthly']			= $profit['yearly'] / 12;

		if (strpos($row['profit'], '-') !== false) {
		    $css = 'row_red';
		}else{
			$css = 'row_green';
		}
		echo '
				<tr>
					<td class="'.$css.'">
						'.$row['hardware'].'
					</td>
					<td class="'.$css.' hidden-xs">
						'.$row['release'].'
					</td>
					<td class="'.$css.'">
						'.$row['hashrate'].'
					</td>
					<td class="'.$css.' hidden-xs">
						'.$row['power'].'
					</td>
					<td class="'.$css.' hidden-xs">
						'.$row['noise'].'
					</td>
					<td class="'.$css.'">
						$'.number_format($profit['daily'], 2).' / $'.number_format($profit['monthly'], 2).' / $'.number_format($profit['yearly'], 2).'
					</td>
				</tr>
			';
	}
}

function get_user_details($id)
{
	$query = "SELECT * FROM `users` WHERE id = '".$id."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['type']					= $row['type'];
		$data['firstname']				= $row['first_name'];
		$data['lastname']				= $row['last_name'];
		$data['fullname']				= $row['first_name'].' '.$row['last_name'];
		$data['avatar']					= $row['avatar'];
		$data['email']					= $row['email'];
		$data['notification_email']		= $row['notification_email'];
		$data['notification_tel']		= $row['notification_tel'];

		return $data;
	}
}

function account_details($id)
{
	$query = "SELECT * FROM users WHERE id = '".$id."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['type']					= $row['type'];
		$data['firstname']				= $row['first_name'];
		$data['lastname']				= $row['last_name'];
		$data['avatar']					= $row['avatar'];
		$data['email']					= $row['email'];
		$data['accepted_terms']			= $row['accepted_terms'];
		$data['temp_setting']			= $row['temp_setting'];

		$data['notification_email']		= $row['notification_email'];
		$data['notification_tel']		= $row['notification_tel'];

		if($data['temp_setting'] == 'c'){
			$data['temp_symbol'] = ' °C';
		}else{
			$data['temp_symbol'] = ' °F';
		}

		$data['ip_address']				= $_SERVER['REMOTE_ADDR'];
		$data['location']				= ip_to_gps();

		if(empty($data['location']['latitude'])){
			$data['location']['latitude'] = '41.850033';
		}
		if(empty($data['location']['longitude'])){
			$data['location']['longitude'] = '-87.6500523';
		}

		// $data['admin']					= get_user_details($row['admin_id']);

		// gui_settings
		$data['gui_settings']['show_site_summary']			= $row['show_site_summary'];
		$data['gui_settings']['show_dashboard_summary']		= $row['show_dashboard_summary'];
	}
	return $data;
}

function get_gravatar($email)  
{  
    $image = 'http://www.gravatar.com/avatar.php?gravatar_id='.md5($email);

    return $image;
}

function percentage($val1, $val2, $precision)
{
	$division = $val1 / $val2;
	$res = $division * 100;
	$res = round($res, $precision);
	return $res;
}

function clean_string($value)
{
    if ( get_magic_quotes_gpc() ){
         $value = stripslashes( $value );
    }
	// $value = str_replace('%','',$value);
    return mysql_real_escape_string($value);
}

function go($link = '')
{
	header("Location: " . $link);
	die();
}

function url($url = '')
{
	$host = $_SERVER['HTTP_HOST'];
	$host = !preg_match('/^http/', $host) ? 'http://' . $host : $host;
	$path = preg_replace('/\w+\.php/', '', $_SERVER['REQUEST_URI']);
	$path = preg_replace('/\?.*$/', '', $path);
	$path = !preg_match('/\/$/', $path) ? $path . '/' : $path;
	if ( preg_match('/http:/', $host) && is_ssl() ) {
		$host = preg_replace('/http:/', 'https:', $host);
	}
	if ( preg_match('/https:/', $host) && !is_ssl() ) {
		$host = preg_replace('/https:/', 'http:', $host);
	}
	return $host . $path . $url;
}

function post($key = null)
{
	if ( is_null($key) ) {
		return $_POST;
	}
	$post = isset($_POST[$key]) ? $_POST[$key] : null;
	if ( is_string($post) ) {
		$post = trim($post);
	}
	return $post;
}

function get($key = null)
{
	if ( is_null($key) ) {
		return $_GET;
	}
	$get = isset($_GET[$key]) ? $_GET[$key] : null;
	if ( is_string($get) ) {
		$get = trim($get);
	}
	return $get;
}

function debug($input)
{
	$output = '<pre>';
	if ( is_array($input) || is_object($input) ) {
		$output .= print_r($input, true);
	} else {
		$output .= $input;
	}
	$output .= '</pre>';
	echo $output;
}

function mysql_disconnect()
{
	global $connection;
	mysql_close($connection);
}

function status_message($status, $message)
{
	$_SESSION['alert']['status']			= $status;
	$_SESSION['alert']['message']			= $message;
}

// totals
function show_total($var)
{
	$query = "SELECT `id` FROM ".$var." ";
	$result = mysql_query($query) or die(mysql_error());
	$records = mysql_num_rows($result);
	return $records;
}

// pending job for a miner
function job_search_miner($miner_id)
{
	$query = "SELECT `id` FROM `site_jobs` WHERE `miner_id` = '".$miner_id."' AND `status` = 'pending';";
	$result = mysql_query($query) or die(mysql_error());
	$records = mysql_num_rows($result);
	return $records;
}

function show_total_power($site_id, $voltage = '')
{
	$query = "SELECT `power` FROM `site_miners` WHERE `site_id` = ".$site_id." AND `status` = 'Mining' ";
	$result = mysql_query($query) or die(mysql_error());
	$power_bits = 0;
	while($row = mysql_fetch_array($result)){
		$power_bits						= $power_bits + $row['power'];
	}

	$query = "SELECT `voltage` FROM `sites` WHERE `id` = ".$site_id." ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$voltage						= $row['voltage'];
	}	
	
	$power['watts'] 		= $power_bits;
	
	$power['kilowatts'] 	= number_format($power['watts'] / 1000, 2);
	
	$power['amps'] 			= number_format($power['watts'] / $voltage, 2);
	
	return $power;
}

function check_owner($user_id, $site_id)
{
	$query = "SELECT `id` FROM `sites` WHERE `id` = ".$site_id." AND `user_id` = '".$user_id."' ";
	$result = mysql_query($query) or die(mysql_error());
	$records = mysql_num_rows($result);
	/*
	while($row = mysql_fetch_array($result)){
		$power_bits						= $power_bits + $row['power'];
	}	
	*/
	
	return $records;
}

function search_pool($pool_url, $pool_username, $account_id)
{
	if(!empty($pool_url))
	{
		$pool_url 		= explode(':', $pool_url);
		$pool_url		= $pool_url[0];

		$pool_username 	= explode('.', $pool_username);
		if(is_array($pool_username))
		{
			$pool_username 	= $pool_username[0];
			$pool_worker	= $pool_username[1];
		}else{
			$pool_username;
		}
	}
	
	if(empty($account_id))
	{
		$account_id = $_SESSION['account']['id'];
	}
	
	$query = "SELECT * FROM `pools` WHERE `user_id` = '".$account_id."' AND `url` LIKE '%".$pool_url."%' AND `username` = '".$pool_username."' ";	
	$data['query_1'] = $query;
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['name']					= stripslashes($row['name']);
		$data['url']					= stripslashes($row['url']);
		$data['port']					= stripslashes($row['port']);
		$data['username']				= stripslashes($row['username']);
		$data['password']				= stripslashes($row['password']);
		$data['xnsub']					= $row['xnsub'];
		$data['coin']['id']				= $row['coin_id'];
	}
	
	$query = "SELECT * FROM `coins` WHERE `id` = '".$row['coin_id']."' ";	
	$data['query_2'] = $query;
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['coin']['name']						= $row['name'];
		$data['coin']['short_name']					= $row['short_name'];
		$data['coin']['algorithm']					= $row['algorithm'];
	}
	
	return $data;
}

function get_pools_default_pool($algorithm)
{
	
	$query = "SELECT * FROM `pools` WHERE `user_id` = '".$_SESSION['account']['id']."' AND `algorithm` = '".$algorithm."' ORDER BY `name` ASC";	
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['name']					= stripslashes($row['name']);
		$data[$count]['url']					= stripslashes($row['url']);
		$data[$count]['port']					= stripslashes($row['port']);
		$data[$count]['username']				= stripslashes($row['username']);
		$data[$count]['password']				= stripslashes($row['password']);
		$data[$count]['xnsub']					= $row['xnsub'];
		$data[$count]['coin']['id']				= $row['coin_id'];
		
		$query_1 = "SELECT * FROM `coins` WHERE `id` = '".$row['coin_id']."' ";	
		$result_1 = mysql_query($query_1) or die(mysql_error());
		while($row_1 = mysql_fetch_array($result_1)){
			$data[$count]['coin']['name']						= $row_1['name'];
			$data[$count]['coin']['short_name']					= $row_1['short_name'];
			$data[$count]['coin']['algorithm']					= $row_1['algorithm'];
		}

		$count++;
	}
	
	return $data;
}

function get_default_pools($algorithm)
{
	
	$query = "SELECT * FROM `site_default_pools` WHERE `user_id` = '".$_SESSION['account']['id']."' AND `algorithm` = '".$algorithm."' ";	
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']							= $row['id'];
		$data['pool_0']						= $row['pool_0'];
		$data['pool_1']						= $row['pool_1'];
		$data['pool_2']						= $row['pool_2'];
	}
	
	return $data;
}

function show_hashrate($site, $algorithm = '')
{
	/*
	// get eth hasrate
	if($algorithm == 'eth')
	{
		$query = "SELECT `hash` FROM `site_miners` WHERE 
		`site_id` = '".$site."' AND `status` = 'mining' AND `type` = 'gpu' OR 
		`site_id` = '".$site."' AND `status` = 'autorebooted' AND `type` = 'gpu' OR 
		`site_id` = '".$site."' AND `status` = 'stuck_miners' AND `type` = 'gpu' OR  
		`site_id` = '".$site."' AND `status` = 'overheat' AND `type` = 'gpu' ";
		$result = mysql_query($query) or die(mysql_error());
		$hashrate['eth'] = 0;
		while($row = mysql_fetch_array($result)){
			$hashrate['eth'] 					= $hashrate['eth'] + $row['hash'];
		}

		$hashrate 								= $hashrate['eth'];
		
		if($hashrate > 1000)
		{
			$hashrate 							= number_format($hashrate / 1000, 2) . ' GH/s';
		}else{
			$hashrate 							= number_format($hashrate, 2) . ' MH/s';
		}

		$hashrate 								= number_format($hashrate / 1000, 2);
	}

	// get blake2b hasrate
	if($algorithm == 'blake2b')
	{
		$query = "SELECT `hashrate_1`, `hashrate_2`, `hashrate_3`, `hashrate_4` FROM `site_miners` WHERE 
		`site_id` = '".$site."' AND `status` = 'mining' AND `algorithm` = 'blake2b' ";
		$result = mysql_query($query) or die(mysql_error());
		$hashrate['blake2b'] = 0;
		while($row = mysql_fetch_array($result)){
			$temp_hashrate						= $row['hashrate_1'] + $row['hashrate_2'] + $row['hashrate_3'] + $row['hashrate_4'];	
			$hashrate['blake2b'] 				= $hashrate['blake2b'] + $temp_hashrate;
		}

		$hashrate 								= $hashrate['blake2b'];

		if($hashrate > 1000)
		{
			$hashrate 							= number_format($hashrate / 1000, 2) . ' TH/s';
		}else{
			$hashrate 							= number_format($hashrate, 2) . ' GH/s';
		}
	}

	// get x11 hasrate
	if($algorithm == 'x11')
	{
		$query = "SELECT `hashrate_1`, `hashrate_2`, `hashrate_3`, `hashrate_4` FROM `site_miners` WHERE 
		`site_id` = '".$site."' AND `status` = 'mining' AND `algorithm` = 'x11' ";		
		$result = mysql_query($query) or die(mysql_error());
		$hashrate['x11'] = 0;
		while($row = mysql_fetch_array($result)){
			$temp_hashrate						= $row['hashrate_1']+$row['hashrate_2']+$row['hashrate_3']+$row['hashrate_4'];
			$hashrate['x11'] 					= $hashrate['x11'] + $temp_hashrate;
		}

		$hashrate		 						= $hashrate['x11'] / 1000;
		$hashrate 								= number_format($hashrate, 2) . ' GH/s';
	}

	// scrypt
	if($algorithm == 'scrypt')
	{
		$query = "SELECT `hashrate_1`, `hashrate_2`, `hashrate_3`, `hashrate_4` FROM `site_miners` WHERE 
		`site_id` = '".$site."' AND `status` = 'mining' AND `algorithm` = 'scrypt' ";
		$result = mysql_query($query) or die(mysql_error());
		$hashrate['scrypt'] = 0;
		while($row = mysql_fetch_array($result)){
			$temp_hashrate						= $row['hashrate_1']+$row['hashrate_2']+$row['hashrate_3']+$row['hashrate_4'];		
			$hashrate['scrypt'] 				= $hashrate['scrypt'] + $temp_hashrate;
		}

		$hashrate		 						= $hashrate['scrypt'];
		if($hashrate > 1000){
			$hashrate 							= $hashrate / 1000;
			$hashrate 							= number_format($hashrate, 2) . ' GH/s';
		}else{
			$hashrate 							= number_format($hashrate, 2) . ' MH/s';
		}
	}
	
	// get sha265 hasrate
	if($algorithm == 'sha256')
	{
		$query = "SELECT `hashrate_1`, `hashrate_2`, `hashrate_3`, `hashrate_4` FROM `site_miners` WHERE `site_id` = '".$site."' AND `status` = 'mining' AND `algorithm` = 'sha256' ";
		$result = mysql_query($query) or die(mysql_error());
		$hashrate['sha256'] = 0;
		while($row = mysql_fetch_array($result)){
			$temp_hashrate						= $row['hashrate_1']+$row['hashrate_2']+$row['hashrate_3']+$row['hashrate_4'];		
			$hashrate['sha256'] 				= $hashrate['sha256'] + $temp_hashrate;
		}

		$hashrate 								= $hashrate['sha256'] / 1000;
		$hashrate 								= number_format($hashrate, 2) . ' TH/s';
	}
	*/

	$query = "SELECT `type`, `hashrate_1`, `hashrate_2`, `hashrate_3`, `hashrate_4`, `hash` FROM `site_miners` WHERE `site_id` = '".$site."' AND `status` = 'mining' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		if($row['type'] == 'asic'){
			$hashrate						= $row['hashrate_1']+$row['hashrate_2']+$row['hashrate_3']+$row['hashrate_4'];		
		}else{
			$hashrate						= $row['hash'];		
		}
		
	}

	return $hashrate;
	// return round($hashrate['x11'] + $hashrate['sha256'], 4);
	// return $hashrate['x11'];
}

function show_daily_revenue($site)
{
	// $query = "SELECT `revenue` FROM `site_miners` WHERE `site_id` = ".$site." AND `status` = 'Mining' ";
	$result = mysql_query($query) or die(mysql_error());
	$revenue = 0;
	while($row = mysql_fetch_array($result)){
		$revenue = $revenue + $row['revenue'];
	}	

	// $revenue = $revenue / 1000;
	return number_format($revenue, 2);	
}

function show_daily_profit($site)
{
	// $query = "SELECT `profit` FROM `site_miners` WHERE `site_id` = ".$site." AND `status` = 'Mining' ";
	$result = mysql_query($query) or die(mysql_error());
	$profit = 0;
	while($row = mysql_fetch_array($result)){
		$profit = $profit + $row['profit'];
	}	

	// $revenue = $revenue / 1000;
	return number_format($profit, 2);	
}

function show_monthly_revenue($site)
{
	$query = "SELECT `revenue` FROM `site_miners` WHERE 
		`site_id` = '".$site."' AND `status` = 'mining' OR 
		`site_id` = '".$site."' AND `status` = 'autorebooted' OR 
		`site_id` = '".$site."' AND `status` = 'stuck_miners' OR  
		`site_id` = '".$site."' AND `status` = 'overheat' ";
	// $query = "SELECT `revenue` FROM `site_miners` WHERE `site_id` = ".$site." AND `status` = 'mining' ";
	$result = mysql_query($query) or die(mysql_error());
	$revenue = 0;
	while($row = mysql_fetch_array($result)){
		$revenue = $revenue + $row['revenue'];
	}	

	// $revenue = $revenue / 1000;
	return $revenue;	
}

function show_monthly_profit($site)
{
	$query = "SELECT `profit` FROM `site_miners` WHERE 
		`site_id` = '".$site."' AND `status` = 'mining' OR 
		`site_id` = '".$site."' AND `status` = 'autorebooted' OR 
		`site_id` = '".$site."' AND `status` = 'stuck_miners' OR  
		`site_id` = '".$site."' AND `status` = 'overheat' ";

	// $query = "SELECT `profit` FROM `site_miners` WHERE `site_id` = ".$site." AND `status` = 'mining' ";
	$result = mysql_query($query) or die(mysql_error());
	$profit = 0;
	while($row = mysql_fetch_array($result)){
		$profit = $profit + $row['profit'];
	}

	return $profit;
}

function show_monthly_power_cost($site)
{
	$query = "SELECT `cost` FROM `site_miners` WHERE 
		`site_id` = '".$site."' AND `status` = 'mining' OR 
		`site_id` = '".$site."' AND `status` = 'autorebooted' OR 
		`site_id` = '".$site."' AND `status` = 'stuck_miners' OR  
		`site_id` = '".$site."' AND `status` = 'overheat' ";
	// $query = "SELECT `cost` FROM `site_miners` WHERE `site_id` = ".$site." AND `status` = 'mining' ";
	$result = mysql_query($query) or die(mysql_error());
	$cost = 0;
	while($row = mysql_fetch_array($result)){
		$cost = $cost + $row['cost'];
	}	

	// $revenue = $revenue / 1000;
	return $cost;
}

// sites
function show_dashboard_sites()
{
	global $account_details;

	$query = "SELECT * FROM `sites` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	// $data['query'] = $query;
	// echo print_r($data);
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['name']					= stripslashes($row['name']);
		$data['ip_address']				= stripslashes($row['ip_address']);
		$data['location']				= stripslashes($row['location']);
		$data['city']					= stripslashes($row['city']);
		$data['country']				= stripslashes($row['country']);
		
		$data['weather']				= get_weather($data['city'], $data['country']);
		
		$data['monthly_revenue']		= show_monthly_revenue($data['id']);
		
		$data['monthly_profit']			= show_monthly_profit($data['id']);
		
		// $data['monthly_revenue']		= calc_day_to_month($data['daily_revenue']);
		// $data['monthly_profit']		= calc_day_to_month($data['daily_profit']);
		
		$data['power']					= show_total_power($data['id']);
		
		$data['power']['power_cost']	= $row['power_cost'];
		$data['power']['max_kilowatts']	= $row['max_kilowatts'];
		$data['power']['max_amps']		= $row['max_amps'];
		
		// $data['daily_power_cost']		= $data['power']['kilowatts'] * 24 * $data['power']['power_cost'];
		// $data['monthly_power_cost']		= $data['daily_power_cost'];
		
		$data['monthly_power_cost']		= show_monthly_power_cost($data['id']);
		
		$data['miners']					= get_miners($data['id']);
		
		$data['total_miners']			= count($data['miners']);
		
		$data['total_online_miners'] = 0;
		$data['total_offline_miners'] = 0;
		$data['average_temps'] = array();
		$data['average_temps']['total_pcb'] = 0;
		foreach($data['miners'] as $miner){
			if($miner['status_raw'] == 'mining'){$data['total_online_miners']++;}
			if($miner['status_raw'] != 'mining'){$data['total_offline_miners']++;}
			
			$data['average_temps']['pcb'] 			= $miner['pcb_temp'];
			$data['average_temps']['total_pcb'] 	= $data['average_temps']['total_pcb'] + $miner['pcb_temp'];
			
			$data['average_temps']['chip'] = $miner['chip_temp'];
			/*
			if($miner['temp']['average_pcb_temp'] != 0){
				$data['average_temps']['pcb'][] = $miner['temp']['average_pcb_temp'];
				$data['average_temps']['total_pcb'] = $data['average_temps']['total_pcb'] + $miner['temp']['average_pcb_temp'];
			}
			if($miner['temp']['average_chip_temp'] != 0){
				$data['average_temps']['chip'][] = $miner['temp']['average_chip_temp'];
				$data['average_temps']['total_chip'] = $data['average_temps']['total_chip'] + $miner['temp']['average_chip_temp'];
			}
			*/
		}
		
		if(empty($data['average_temps']['average_pcb']) || !isset($data['average_temps']['average_pcb'])){
			$data['average_temps']['average_pcb'] = 0;
		}
		$data['average_temps']['average_pcb'] = number_format($data['average_temps']['total_pcb'] / $data['total_online_miners']);
		
		if($account_details['temp_setting'] == 'c'){
			$data['weather']['temp'] = $data['weather']['temp']['c'] . $account_details['temp_symbol'];
		}else{
			$data['weather']['temp'] = $data['weather']['temp']['f'] . $account_details['temp_symbol'];
		}

		if($account_details['temp_setting'] == 'c'){
			$data['average_temps']['average_pcb'] = $data['average_temps']['average_pcb'] . $account_details['temp_symbol'];
		}else{
			$data['average_temps']['average_pcb'] = c_to_f($data['average_temps']['average_pcb']) . $account_details['temp_symbol'];
		}

		$data['controller']['version']						= $row['controller_version'];
		$data['controller']['ip_address']					= $row['controller_ip'];
		$data['controller']['mac_address']					= $row['controller_mac'];
		$data['controller']['last_checkin']					= $row['controller_last_checkin'];
		if($account_details['temp_setting'] == 'c'){
			$data['controller']['cpu_temp']						= $row['controller_cpu_temp'] . $account_details['temp_symbol'];
		}else{
			$data['controller']['cpu_temp']						= c_to_f($row['controller_cpu_temp']) . $account_details['temp_symbol'];
		}

		$now = time();
		$time_diff = $now - $data['controller']['last_checkin'];

		if($time_diff > 500)
		{
			$data['controller']['status_raw'] = 'offline';
			$data['controller']['status'] = '<b><font color="red">Offline</font></b>';
		}else{
			$data['controller']['status_raw'] = 'online';
			$data['controller']['status'] = '<b><font color="green">Online</font></b>';
		}

		if($data['controller']['status_raw'] == 'online')
		{
			$data['controller']['status_class'] = 'success';
		}else{
			$data['controller']['status_class'] = 'danger';
		}

		echo '

			<div class="row">
				<div class="col-lg-12">
					<div class="box box-'.$data['controller']['status_class'].' box-solid">
						<div class="box-body">
							<div class="row">
								<div class="col-lg-2">
									'.$data['name'].' <br>
									'.$data['weather']['text'].', '.$data['weather']['temp'].'
								</div>
								<div class="col-lg-2">
									<b>Miners:</b> '.number_format($data['total_online_miners']).' / '.number_format($data['total_miners']).'
								</div>
								<div class="col-lg-2">
									<b>Hashrate:</b> '.show_hashrate($data['id']).' TH/s
								</div>
								<div class="col-lg-2">
									<b>Temp:</b> '.$data['average_temps']['average_pcb'].'
								</div>
								<div class="col-lg-3">
									<b>Power:</b> '.number_format($data['power']['kilowatts'], 2).' kW / 
									'.number_format($data['power']['amps'], 2).' AMP
								</div>
								<div class="col-lg-1">
									<a title="Overview" class="btn btn-primary btn-flat" href="?c=site&site_id='.$data['id'].'">
										<small>
											<i class="fa fa-globe"></i>
										</small>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		';
		
		unset($data);
	}
}

function show_sites()
{
	global $account_details;

	$query = "SELECT * FROM `sites` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	// $data['query'] = $query;
	// echo print_r($data);
	while($row = mysql_fetch_array($result)){
		$data['id']							= $row['id'];
		$data['name']						= stripslashes($row['name']);
		$data['ip_address']					= stripslashes($row['ip_address']);
		$data['location']					= stripslashes($row['location']);
		$data['location']['latitude']		= $row['location_lat'];
		$data['location']['longitude']		= $row['location_lng'];
		$data['city']						= stripslashes($row['city']);
		$data['country']					= stripslashes($row['country']);
	
		$data['weather']					= ''; // get_weather($data['city'], $data['country']);
		
		$data['monthly_revenue']			= show_monthly_revenue($data['id']);
		
		$data['monthly_profit']				= show_monthly_profit($data['id']);
		
		// $data['monthly_revenue']			= calc_day_to_month($data['daily_revenue']);
		// $data['monthly_profit']			= calc_day_to_month($data['daily_profit']);
		
		$data['power']						= show_total_power($data['id']);
		
		$data['power']['power_cost']		= $row['power_cost'];
		$data['power']['max_kilowatts']		= $row['max_kilowatts'];
		$data['power']['max_amps']			= $row['max_amps'];
		
		// $data['daily_power_cost']		= $data['power']['kilowatts'] * 24 * $data['power']['power_cost'];
		// $data['monthly_power_cost']		= $data['daily_power_cost'];

		$data['monthly_power_cost']			= show_monthly_power_cost($data['id']);
		
		$data['miners']						= get_miners($data['id']);
		
		$data['total_miners']				= count($data['miners']);
		
		$data['total_online_miners'] = 0;
		$data['total_offline_miners'] = 0;
		$data['average_temps'] = array();
		$data['average_temps']['total_pcb'] = 0;
		
		foreach($data['miners'] as $miner){
			if($miner['status_raw'] == 'mining'){$data['total_online_miners']++;}
			if($miner['status_raw'] != 'mining'){$data['total_offline_miners']++;}
			
			if($miner['status_raw'] == 'mining')
			{
				$data['average_temps']['pcb'] 			= $miner['pcb_temp'];
				$data['average_temps']['total_pcb'] 	= $data['average_temps']['total_pcb'] + $miner['pcb_temp'];
				
				$data['average_temps']['chip'] = $miner['chip_temp'];
				/*
				if($miner['temp']['average_pcb_temp'] != 0){
					$data['average_temps']['pcb'][] = $miner['temp']['average_pcb_temp'];
					$data['average_temps']['total_pcb'] = $data['average_temps']['total_pcb'] + $miner['temp']['average_pcb_temp'];
				}
				if($miner['temp']['average_chip_temp'] != 0){
					$data['average_temps']['chip'][] = $miner['temp']['average_chip_temp'];
					$data['average_temps']['total_chip'] = $data['average_temps']['total_chip'] + $miner['temp']['average_chip_temp'];
				}
				*/
			}
		}
		
		if(empty($data['average_temps']['average_pcb']) || !isset($data['average_temps']['average_pcb'])){
			$data['average_temps']['average_pcb'] = 0;
		}
		$data['average_temps']['average_pcb'] = number_format($data['average_temps']['total_pcb'] / $data['total_online_miners']);
		
		if($account_details['temp_setting'] == 'c'){
			$data['weather']['temp'] = $data['weather']['temp']['c'] . $account_details['temp_symbol'];
		}else{
			$data['weather']['temp'] = $data['weather']['temp']['f'] . $account_details['temp_symbol'];
		}

		if($account_details['temp_setting'] == 'c'){
			$data['average_temps']['average_pcb'] = $data['average_temps']['average_pcb'] . $account_details['temp_symbol'];
		}else{
			$data['average_temps']['average_pcb'] = c_to_f($data['average_temps']['average_pcb']) . $account_details['temp_symbol'];
		}

		$data['controller']['version']						= $row['controller_version'];
		$data['controller']['ip_address']					= $row['controller_ip'];
		$data['controller']['mac_address']					= $row['controller_mac'];
		$data['controller']['last_checkin']					= $row['controller_last_checkin'];
		if($account_details['temp_setting'] == 'c'){
			$data['controller']['cpu_temp']					= $row['controller_cpu_temp'] . $account_details['temp_symbol'];
		}else{
			$data['controller']['cpu_temp']					= c_to_f($row['controller_cpu_temp']) . $account_details['temp_symbol'];
		}

		$now = time();
		$time_diff = $now - $data['controller']['last_checkin'];

		if($time_diff > 500)
		{
			$data['controller']['status_raw'] = 'offline';
			$data['controller']['status'] = '<b><font color="red">Offline</font></b>';
		}else{
			$data['controller']['status_raw'] = 'online';
			$data['controller']['status'] = '<b><font color="green">Online</font></b>';
		}

		if($data['controller']['status_raw'] == 'online')
		{
			echo '
				<tr>
					<th>
						'.$data['name'].' <br>
						<span style="font-weight:normal;">
							'.$data['city'].', '.$data['country'].' <br>
							'.$data['weather']['text'].', '.$data['weather']['temp'].'
						</span>
					</th>
					<th>
						'.$data['controller']['status'].' <span style="font-weight:normal;"><small>('.$data['controller']['version'].')</small></font><br>
						<span style="font-weight:normal;">
							<b>IP:</b> '.$data['controller']['ip_address'].' <br>
							<b>CPU Temp:</b> '.$data['controller']['cpu_temp'].'
						</span>
					</th>
					<th>
						Miners: <span style="font-weight:normal;">'.number_format($data['total_online_miners']).' / '.number_format($data['total_miners']).'</span> <br>
						<!-- Hashrate: <span style="font-weight:normal;">'.number_format(show_hashrate($data['id'], 2)).' TH/s</span> <br> -->
						Avg Temp: <span style="font-weight:normal;">'.$data['average_temps']['average_pcb'].' </span> <br>
					</th>
					<th>
						kW: <span style="font-weight:normal;">'.number_format($data['power']['kilowatts'], 2).'</span> <br>
						AMP: <span style="font-weight:normal;">'.number_format($data['power']['amps'], 2).'</span>
					</th>
					<th>
						Revenue: <span style="font-weight:normal;">
							$'.($data['monthly_revenue']<0 ? '<font color="red">'.number_format($data['monthly_revenue'], 2).'</font>' : number_format($data['monthly_revenue'], 2)).'</span> <br>
						Pwr Cost: <span style="font-weight:normal;">
							$'.number_format($data['monthly_power_cost'], 2).'</span> <br>
						Profit: <span style="font-weight:normal;">
							$'.($data['monthly_profit']<0 ? '<font color="red">'.number_format($data['monthly_profit'], 2).'</font>' : number_format($data['monthly_profit'], 2)).'</span><br>
					</th>
					<td>
						<a title="Overview" class="btn btn-primary btn-flat" href="?c=site&site_id='.$data['id'].'"><i class="fa fa-globe"></i></a>
						<a title="Delete Site" class="btn btn-danger btn-flat" onclick="return confirm(&#039;Are you sure you want to do this?&#039;);" href="actions.php?a=site_delete&site_id='.$data['id'].'"><i class="fa fa-times"></i></a>
                        
					</td>
				</tr>
			';
		}else{
			echo '
				<tr>
					<th>
						'.$data['name'].' <br>
						<span style="font-weight:normal;">
							'.$data['city'].', '.$data['country'].' <br>
							'.$data['weather']['text'].', '.$data['weather']['temp'].'
						</span>
					</th>
					<th>
						'.$data['controller']['status'].' <br>
					</th>
					<th>
						
					</th>
					<th>
						
					</th>
					
					<th>
					</th>
					<td>
						<!-- <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-'.$data['id'].'">V</button> -->
						<!-- <a href="?c=site&site_id='.$data['id'].'">V</a> -->
						<!-- <a href="?c=site&site_id='.$data['id'].'" class="btn btn-info">V</a> -->
						<!-- <a href="actions.php?a=site_delete&site_id='.$data['id'].'" onclick="return confirm(\'This will delete the site and all linked miners. This CANNOT be undone. \n\nAre you sure?\')" class="btn btn-danger">D</a> -->

						<a title="Overview" class="btn btn-primary btn-flat" href="?c=site&site_id='.$data['id'].'"><i class="fa fa-globe"></i></a>
						<a title="Delete Site" class="btn btn-danger btn-flat" onclick="return confirm(&#039;Are you sure you want to do this?&#039;);" href="actions.php?a=site_delete&site_id='.$data['id'].'"><i class="fa fa-times"></i></a>
					</td>
				</tr>
			';
		}
		
		echo '
			<div class="modal fade" id="modal-'.$data['id'].'">
				<div class="modal-dialog" style="width: 80%;">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Site: '.$data['name'].'</h4>
						</div>
						<div class="modal-body">
							<p>
								<div style="width:100%;">
									<div style="width:50%; float:left;">
										<h2>Details</h2>
										<strong>IP Address:</strong> <br>
										 - '.$data['ip_address'].' <br>

										<strong>Location:</strong> <br>
										 - '.$data['location'].' <br>
										
										<strong>Weather:</strong> <br>
										 - Forcast: '.$data['weather']['text'].' <br>
										 - Temp: '.$data['weather']['temp']['c'].'°C / '.$data['weather']['temp']['f'].'°F <br>
										 - Wind Speed: '.$data['weather']['wind']['speed'].' mph <br>
										 - Wind Direction: '.$data['weather']['wind']['direction'].' <br>

										<strong>Power:</strong> <br>
										 - '.number_format($data['power']['watts'], 0).' of '.number_format($data['power']['max_kilowatts'] * 1000, 0).' Watts <br>
										 - '.number_format($data['power']['kilowatts'], 2).' of '.number_format($data['power']['max_kilowatts'], 2).' kW <br>
										 - '.number_format($data['power']['amps'], 2).' of '.number_format($data['power']['max_amps'], 2).' AMPs <br>
										 - '.percentage(number_format($data['power']['watts'], 2), number_format($data['power']['max_kilowatts'] * 1000, 0)).'% capacity <br>

										<strong>Miners:</strong> <br>
										 - Total: '.$data['total_miners'].' <br>
										 - Online / Offline: '.$data['total_online_miners'].' / '.$data['total_offline_miners'].' <br>
										 - Hashrate: '.show_hashrate($data['id']).' TH/s <br>
									</div>
									<div style="width:50%; float:right;">
									<h2>Miners</h2>
			';

			// debug($data['miners']);
			if(isset($data['miners'][0])){
				foreach($data['miners'] as $miner)
				{
					if($miner['algorithm'] == 'SHA256'){
						$miner['total_hashrate'] 			= number_format($miner['hashrate']['total'] / 1000, 2) . ' TH/s';
					}
					if($miner['algorithm'] == 'X11'){
						$miner['total_hashrate'] 			= number_format($miner['hashrate']['total'] / 1000, 2) . ' GH/s';
					}

					if($miner['status_raw'] == 'mining'){
						echo '
						<div class="" style="width: 100%; border:1px solid black; padding-left: 5px; background-color: lightgreen;">
							'.$miner['name'].' / '.$miner['ip_address'].' / '. $miner['total_hashrate'] .' 
						</div>
						';
					}else{
						echo '
						<div class="" style="width: 100%; border:1px solid black; padding-left: 5px; background-color: pink;">
							'.$miner['name'].' / '.$miner['ip_address'].'  
						</div>
						';
					}
				}
			}else{
				echo 'No miners found.';
			}

			echo '
									</div>
								</div>
							<div style="width: 100%; clear: both;"></div>
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
							<a href="?c=site&site_id='.$data['id'].'" class="btn btn-success">Miners</a>
							<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
						</div>
					</div>
				</div>
			</div>
		';
		unset($data);
	}
}

function get_sites()
{
	global $account_details;

	$query = "SELECT `id` FROM `sites` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	// $data['query'] = $query;
	// echo print_r($data);
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['site']					= get_site($row['id']);
		$count++;
	}

	if(isset($data)){ return $data;}
}

function get_site($site_id, $type ='')
{
	// get asic miners
	$query = "SELECT * FROM `sites` WHERE `id` = '".$site_id."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']							= $row['id'];
		$data['user_id']					= $row['user_id'];
		$data['name']						= stripslashes($row['name']);
		$data['ip_address']					= stripslashes($row['ip_address']);

		$data['location']['address']		= stripslashes($row['location']);
		$data['location']['latitude']		= $row['location_lat'];
		$data['location']['longitude']		= $row['location_lng'];

		$data['city']						= stripslashes($row['city']);
		$data['country']					= stripslashes($row['country']);
		$data['api_key']					= $row['api_key'];
		$data['power_cost']					= stripslashes($row['power_cost']);
		$data['max_amps']					= stripslashes($row['max_amps']);
		$data['max_kilowatts']				= stripslashes($row['max_kilowatts']);
		$data['voltage']					= stripslashes($row['voltage']);
		
		$data['power']						= show_total_power($row['id'], $row['voltage']);
		
		// profit
		$data['monthly_profit']				= show_monthly_profit($row['id']);
		$data['daily_profit']				= $data['monthly_profit'] / 30;

		$data['monthly_profit']				= number_format($data['monthly_profit'], 2);
		$data['daily_profit']				= number_format($data['daily_profit'], 2);

		// revenue
		$data['monthly_revenue']			= show_monthly_revenue($row['id']);
		$data['daily_revenue']				= $data['monthly_revenue'] / 30;

		$data['monthly_revenue']			= number_format($data['monthly_revenue'], 2);
		$data['daily_revenue']				= number_format($data['daily_revenue'], 2);

		// power cost
		$data['monthly_power_cost']			= show_monthly_power_cost($data['id']);
		$data['daily_power_cost']			= $data['monthly_power_cost'] / 30;

		$data['monthly_power_cost']			= number_format($data['monthly_power_cost'], 2);
		$data['daily_power_cost']			= number_format($data['daily_power_cost'], 2);

		$data['controller_ip']				= $row['controller_ip'];
		$data['controller_mac']				= $row['controller_mac'];
		$data['controller_last_checkin']	= $row['controller_last_checkin'];
		$data['controller_version']			= $row['controller_version'];
		
		$data['miners']						= get_miners($row['id'], $row['user_id'], $type);

		$data['ip_ranges']					= get_ip_ranges($row['id']);

		$data['total_online_miners'] = 0;
		$data['total_offline_miners'] = 0;

		// get all algo's for this site
		$query_1 = "SELECT `algorithm` FROM `site_miners` WHERE `site_id` = '".$site_id."' GROUP BY `algorithm` ";
		$result_1 = mysql_query($query_1) or die(mysql_error());
		while($row_1 = mysql_fetch_array($result_1)){
			$data['algorithms'][]					= $row_1['algorithm'];
		}

		foreach($data['algorithms'] as $algorithm)
		{
			$data['hashrate']["$algorithm"]  		= show_hashrate($row['id'], $algorithm);
		}

		$data['average_temps']['total_pcb'] = 0;
		foreach($data['miners'] as $miner){
			if($miner['status_raw'] == 'mining'){$data['total_online_miners']++;}
			if($miner['status_raw'] != 'mining'){$data['total_offline_miners']++;}
			
			$data['average_temps']['pcb'] 			= $miner['pcb_temp'];
			$data['average_temps']['total_pcb'] 	= $data['average_temps']['total_pcb'] + $miner['pcb_temp'];
			
			$data['average_temps']['chip'] = $miner['chip_temp'];
		}
		
		if(empty($data['average_temps']['average_pcb']) || !isset($data['average_temps']['average_pcb'])){
			$data['average_temps']['average_pcb'] = 0;
		}

		if($data['total_online_miners'] != 0){
			$data['average_temps']['average_pcb'] = number_format($data['average_temps']['total_pcb'] / $data['total_online_miners']);
		}else{
			$data['average_temps']['average_pcb'] = 0;
		}
		
		if($account_details['temp_setting'] == 'c'){
			$data['weather']['temp'] = $data['weather']['temp']['c'] . $account_details['temp_symbol'];
		}else{
			$data['weather']['temp'] = $data['weather']['temp']['f'] . $account_details['temp_symbol'];
		}

		if($account_details['temp_setting'] == 'c'){
			$data['average_temps']['average_pcb'] = $data['average_temps']['average_pcb'] . $account_details['temp_symbol'];
		}else{
			$data['average_temps']['average_pcb'] = c_to_f($data['average_temps']['average_pcb']) . $account_details['temp_symbol'];
		}

		// get ethOS panels
		$query_2 = "SELECT * FROM `site_ethos_portals` WHERE `site_id` = '".$site_id."' ";
		$result_2 = mysql_query($query_2) or die(mysql_error());
		$count_2 = 0;
		while($row_2 = mysql_fetch_array($result_2)){
			$data['ethos'][$count_2]['id']					= $row_2['id'];
			$data['ethos'][$count_2]['name']				= $row_2['name'];
			$data['ethos'][$count_2]['panel_url']			= $row_2['panel_url'];

			$count_2++;
		}
	}
	
	return $data;
}

function get_site_short($site_id)
{
	$query = "SELECT * FROM `sites` WHERE `id` = '".$site_id."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']							= $row['id'];
		$data['name']						= stripslashes($row['name']);
		$data['ip_address']					= stripslashes($row['ip_address']);
		$data['location']					= stripslashes($row['location']);
		$data['location_lat']				= $row['location_lat'];
		$data['location_lng']				= $row['location_lng'];
		$data['api_key']					= $row['api_key'];
		$data['power_cost']					= stripslashes($row['power_cost']);
		$data['voltage']					= $row['voltage'];

	}
	
	return $data;
}

function get_ip_ranges($site_id)
{
	$data =array();

	$query = "SELECT * FROM `site_ip_ranges` WHERE `site_id` = '".$site_id."' ORDER BY INET_ATON(ip_range) ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['name']					= stripslashes($row['name']);
		$data[$count]['ip_range']				= stripslashes($row['ip_range']);
		$count++;
	}
	
	return $data;
}

// coins
function get_coins()
{
	$query = "SELECT * FROM `coins` ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['name']					= stripslashes($row['name']);
		$data[$count]['short_name']				= stripslashes($row['short_name']);
		$data[$count]['algorithm']				= stripslashes($row['algorithm']);
		$count++;
	}
	
	return $data;
}

// miners > asic
function get_miners($site_id, $account_id = '', $type = '')
{
	if(empty($type)){
		$query = "SELECT `id` FROM `site_miners` WHERE `site_id` = '".$site_id."' ORDER BY `name`,INET_ATON(ip_address) ASC";
	}else{
		$query = "SELECT `id` FROM `site_miners` WHERE `site_id` = '".$site_id."' AND `type` = '".$type."' ORDER BY `name`,INET_ATON(ip_address) ASC";
	}
	
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	$data = array();
	while($row = mysql_fetch_array($result)){
		$data[$count]							= get_miner($row['id'], $account_id, $type);
		$count++;
	}

	$data['dev'][0]['message'] = 'get_miners for site id: ' . $site_id;
	$data['dev'][0]['query'] = $query;
	// error_log($data['dev'][0]['message']);

	return $data;
}

function get_miner($miner_id, $account_id)
{
	if(empty($account_id))
	{
		$account_id = $_SESSION['account']['id'];
	}

	$data['dev'][1]['message'] = ' -> get_miner for miner id: ' . $miner_id;
	// error_log($data['dev'][1]['message']);

	$account_details = account_details($account_id);

	$query = "SELECT * FROM `site_miners` WHERE `id` = '".$miner_id."' ";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		// error_log("MATCH miner id: " . $miner_id);

		// $data['raw']					= json_encode($row);
		$data['id']						= $row['id'];
		$data['name']					= stripslashes($row['name']);
		$data['type']					= stripslashes($row['type']);
		$data['worker_name']			= stripslashes($row['worker_name']);
		$data['site_id']				= stripslashes($row['site_id']);
		if(empty($data['name'])) {$data['name'] = $row['ip_address'];}
		$data['ip_address']				= stripslashes($row['ip_address']);
		$data['pool_profile_id']		= $row['pool_profile_id'];

		if($data['type'] == 'asic')
		{
			$data['hashrate_raw']			= $row['hashrate_1']+$row['hashrate_2']+$row['hashrate_3']+$row['hashrate_4'];
			$data['software_version']		= $row['software_version'];

			if($row['algorithm'] == 'sha256'){
				$data['hashrate'] 			= number_format($data['hashrate_raw'] / 1000, 2) . ' TH/s';
				$data['hashes']				= $data['hashrate'];
				$data['coin']				= 'Bitcoin';
			}
			
			if($row['algorithm'] == 'x11' || $row['algorithm'] == 'blake2b'){
				$data['hashrate'] 			= number_format($data['hashrate_raw'] / 1000, 2) . ' GH/s';
				$data['hashes']				= $data['hashrate'];
				$data['coin']				= 'Dash';
			}

			if($row['algorithm'] == 'blake2b'){
				$data['hashrate'] 			= number_format($data['hashrate_raw'], 2) . ' GH/s';
				$data['hashes']				= $data['hashrate'];
				$data['coin']				= 'Siacoin';
			}

			if($row['algorithm'] == 'scrypt'){
				$data['hashrate'] 			= number_format($data['hashrate_raw'], 2) . ' MH/s';
				$data['hashes']				= $data['hashrate'];
				$data['coin']				= 'LTC';
			}
			if($row['algorithm'] == 'ethereum'){
				$data['hashrate'] 			= number_format($data['hashrate_raw'], 2) . ' H/s';
				$data['hashes']				= $data['hashrate'];
				$data['coin']				= 'ETH';
			}
		}elseif($data['type'] == 'gpu'){
			$data['hashrate_raw']			= $row['hash'];
			$data['hashrate']				= $data['hashrate_raw'];
			$data['hashes']					= $data['hashrate'];
			$data['software_version']		= $row['gpu_miner_software_folder'];
		}

		// $data['hashrate']				= number_format($data['hashrate'] / 1000, 2);
		
		$data['username']				= $row['username'];
		$data['password']				= $row['password'];

		$data['hardware_raw']			= $row['hardware'];
		$data['hardware']				= strtoupper($row['hardware']);
		$data['hardware']				= str_replace(array("-","_")," ", $data['hardware']);
		
		$data['location']				= $row['location_row'].' / '.$row['location_rack'].' / '.$row['location_shelf'].' / '.$row['location_position'];
		
		// $data['financials']				= mining_calc($data['coin'], $data['hashrate']);
		$data['revenue']				= $row['revenue'];
		$data['cost']					= $row['cost'];
		$data['profit']					= $row['profit'];

		$data['location_row']			= $row['location_row'];
		$data['location_rack']			= $row['location_rack'];
		$data['location_shelf']			= $row['location_shelf'];
		$data['location_position']		= $row['location_position'];

		$data['status_raw']				= $row['status'];

		$now = time();
		$time_diff = $now - $row['time'];

		if($time_diff > 300)
		{
			$data['status_raw'] = 'offline';
		}

		if($data['status_raw'] == 'mining')			{$data['status'] = 'Mining';}
		if($data['status_raw'] == 'unreachable')	{$data['status'] = 'Offline';}
		if($data['status_raw'] == 'no_hash')		{$data['status'] = 'No Hash';}
		if($data['status_raw'] == 'not_mining')		{$data['status'] = 'Not Mining';}
		if($data['status_raw'] == 'new')			{$data['status'] = 'Pending Adoption';}
		if($data['status_raw'] == 'pending')		{$data['status'] = 'Pending Adoption';}
		if($data['status_raw'] == 'offline')		{$data['status'] = 'Offline';}
		if($data['status_raw'] == 'disconnected')	{$data['status'] = 'Disconnected';}
		if($data['status_raw'] == 'overheat')		{$data['status'] = 'Overheating';}
		if($data['status_raw'] == 'throttle')		{$data['status'] = 'Throttled';}
		if($data['status_raw'] == 'autorebooted')		{$data['status'] = 'Auto Rebooted';}
		if($data['status_raw'] == 'stuck_miners')		{$data['status'] = 'Stuck Miners';}

		$data['paused']					= $row['paused'];
		if($data['paused'] == 'yes'){$data['status'] = 'Paused';}

		if($account_details['temp_setting'] == 'c'){
			if($data['type'] == 'asic')
			{
				$data['pcb_temp_1']			= $row['pcb_temp_1'];
				$data['pcb_temp_2']			= $row['pcb_temp_2'];
				$data['pcb_temp_3']			= $row['pcb_temp_3'];
				$data['pcb_temp_4']			= $row['pcb_temp_4'];
				$temp_pcb_temp 				= array($row['pcb_temp_1'], $row['pcb_temp_2'], $row['pcb_temp_3']);
				$data['pcb_temp'] 			= number_format(array_sum($temp_pcb_temp) / count($temp_pcb_temp), 0);

				$data['chip_temp_1']		= $row['chip_temp_1'];
				$data['chip_temp_2']		= $row['chip_temp_2'];
				$data['chip_temp_3']		= $row['chip_temp_3'];
				$data['chip_temp_4']		= $row['chip_temp_4'];
				$temp_chip_temp 			= array($row['chip_temp_1'], $row['chip_temp_2'], $row['chip_temp_3']);
				$data['chip_temp'] 			= number_format(array_sum($temp_chip_temp) / count($temp_chip_temp), 0);
			}else{
				$temps 						= explode(' ', $row['temp']);
				if(count($temps == 1)){
					$data['pcb_temp'] = $temps[0];
					$data['chip_temp'] = $temps[0];
				}else{
					$data['pcb_temp'] 			= number_format(array_sum($temps) / count($temps), 0);
					$data['chip_temp'] 			= number_format(array_sum($temps) / count($temps), 0);
				}
			}
		}else{
			if($data['type'] == 'asic')
			{
				$data['pcb_temp_1']			= c_to_f($row['pcb_temp_1']);
				$data['pcb_temp_2']			= c_to_f($row['pcb_temp_2']);
				$data['pcb_temp_3']			= c_to_f($row['pcb_temp_3']);
				$data['pcb_temp_4']			= c_to_f($row['pcb_temp_4']);
				$temp_pcb_temp 				= array($row['pcb_temp_1'], $row['pcb_temp_2'], $row['pcb_temp_3']);
				$data['pcb_temp'] 			= number_format(array_sum($temp_pcb_temp) / count($temp_pcb_temp), 0);

				$data['chip_temp_1']		= c_to_f($row['chip_temp_1']);
				$data['chip_temp_2']		= c_to_f($row['chip_temp_2']);
				$data['chip_temp_3']		= c_to_f($row['chip_temp_3']);
				$data['chip_temp_4']		= c_to_f($row['chip_temp_4']);
				$temp_chip_temp 			= array($row['chip_temp_1'], $row['chip_temp_2'], $row['chip_temp_3']);
				$data['chip_temp'] 			= number_format(array_sum($temp_chip_temp) / count($temp_chip_temp), 0);
			}else{
				$temps 						= explode(' ', $row['temp']);
				if(count($temps == 1)){
					$data['pcb_temp'] = $temps[0];
					$data['chip_temp'] = $temps[0];
				}else{
					$data['pcb_temp'] 			= number_format(array_sum($temps) / count($temps), 0);
					$data['chip_temp'] 			= number_format(array_sum($temps) / count($temps), 0);
				}
				$data['pcb_temp']			= c_to_f($data['pcb_temp']);
				$data['chip_temp']			= c_to_f($data['chip_temp']);
			}
		}
		
		if($data['hardware'] == 'spondoolies' || $data['hardware'] == 'ebite9plus')
		{
			$data['asics_1']			= 'n/a';
			$data['asics_2']			= 'n/a';
			$data['asics_3']			= 'n/a';
			$data['asics_4']			= 'n/a';
		}else{
			$data['asics_1']			= $row['asics_1'];
			$data['asics_2']			= $row['asics_2'];
			$data['asics_3']			= $row['asics_3'];
			$data['asics_4']			= $row['asics_4'];
		}
		
		$data['hardware_errors']		= $row['hardware_errors'];
		
		$data['accepted']				= $row['accepted'];
		$data['rejected']				= $row['rejected'];
		
		$data['algorithm']				= strtoupper($row['algorithm']);
		
		$data['watts']					= $row['power'];
		$data['kilowatts']				= $row['power'] / 1000;
		
		$data['pending_jobs']			= job_search_miner($data['id']);
		
		$data['updated']				= date("d/m/Y H:i:s", $row['time']);
		
		$data['frequency']				= stripslashes($row['frequency']);

		$data['pools'][0]['status']		= $row['pool_0_status'];
		$data['pools'][1]['status']		= $row['pool_1_status'];
		$data['pools'][2]['status']		= $row['pool_2_status'];

		$data['pools'][0]['priority']	= $row['pool_0_priority'];
		$data['pools'][1]['priority']	= $row['pool_1_priority'];
		$data['pools'][2]['priority']	= $row['pool_2_priority'];

		$data['pools'][0]['url']		= str_replace("<br>", "", $row['pool_0_url']);
		$data['pools'][1]['url']		= str_replace("<br>", "", $row['pool_1_url']);
		$data['pools'][2]['url']		= str_replace("<br>", "", $row['pool_2_url']);
		
		$data['pools'][0]['user']		= str_replace("<br>", "", $row['pool_0_user']);
		$data['pools'][1]['user']		= str_replace("<br>", "", $row['pool_1_user']);
		$data['pools'][2]['user']		= str_replace("<br>", "", $row['pool_2_user']);
		

		if($data['pools'][0]['priority'] == 0){
			$data['pool']					= search_pool($data['pools'][0]['url'], $data['pools'][0]['user'], $account_id);
			$data['pool_details']['url']	= $row['pool_0_url'];
			$data['pool_details']['user']	= $row['pool_0_user'];
			$data['pool_details']['worker']	= $row['worker_name'];
		}elseif($data['pools'][1]['priority'] == 0){
			$data['pool']					= search_pool($data['pools'][1]['url'], $data['pools'][1]['user'], $account_id);
			$data['pool_details']['url']	= $row['pool_1_url'];
			$data['pool_details']['user']	= $row['pool_1_user'];
			$data['pool_details']['worker']	= $row['worker_name'];
		}elseif($data['pools'][2]['priority'] == 0){
			$data['pool']					= search_pool($data['pools'][2]['url'], $data['pools'][2]['user'], $account_id);
			$data['pool_details']['url']	= $row['pool_2_url'];
			$data['pool_details']['user']	= $row['pool_2_user'];
			$data['pool_details']['worker']	= $row['worker_name'];
		}else{
			$data['pool']					= search_pool($data['pools'][0]['url'], $data['pools'][0]['user'], $account_id);
			$data['pool_details']['url']	= $row['pool_0_url'];
			$data['pool_details']['user']	= $row['pool_0_user'];
			$data['pool_details']['worker']	= $row['worker_name'];
		}


		if(!empty($data['pool']['id']))
		{
			$data['pool_data'] = $data['pool']['name'];
		}else{
			if($data['pools'][0]['priority'] == 0){
				$data['pool_data'] = $data['pools'][0]['url'];
			}
			if($data['pools'][1]['priority'] == 0){
				$data['pool_data'] = $data['pools'][1]['url'];
			}
			if($data['pools'][2]['priority'] == 0){
				$data['pool_data'] = $data['pools'][2]['url'];
			}
		}

		if($data['type'] == 'gpu')
		{
			$data['pool_data'] = $row['pool_0_url'];
		}

		// if($row['hardware'] == 'antminer-d3'){
			$data['max_pcb_temp']		= '82';
			$data['max_chip_temp']		= '105';
		// }
		
		$data['warning']				= 'no';
		$data['warning_text']			= '';
		
		if($data['status_raw'] == 'mining' || $data['status_raw'] == 'autorebooted' || $data['status_raw'] == 'no_hash' || $data['status_raw'] == 'stuck_miners')
		{
			if($data['status_raw'] == 'autorebooted'){
				$data['status_raw']					= 'mining';
				$data['status']						= 'Mining';
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'Autorebooted';
			}

			if($data['status_raw'] == 'stuck_miners'){
				$data['status_raw']					= 'mining';
				$data['status']						= 'Mining';
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'Stuck Miners';
			}

			if($data['status_raw'] == 'no_hash'){
				$data['status_raw']					= 'mining';
				$data['status']						= 'Mining';
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'Not Hasing';
			}

			if($data['pcb_temp']>$data['max_chip_temp']){
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'High PCB Temp';
			}

			if($data['chip_temp']>$data['max_chip_temp']){
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'High Chip Temp';
			}

			if($data['profit'] < 0.00){
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'Low Revenue';
			}

			if($data['pools'][0]['user'] == 'antminer_1' || $data['pools'][0]['user'] == 'antminer.1')
			{
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'Bitmain Default Config';
			}
		}elseif($data['status_raw'] == 'overheat')
		{
			if($data['pcb_temp']>$data['max_chip_temp']){
				$data['warning']					= 'yes';
				$data['warning_text'][]				= 'High Temp';
			}
		}

		if(is_array($data['warning_text']))
		{
			$data['warning_text'] 					= implode("<br>", $data['warning_text']);
		}

		
		if($data['pool_profile_id'] != 0)
		{
			$data['pool_profile']						= get_pool_profile($data['pool_profile_id']);
		}else{
			$data['pool_profile']						= 'no_profile';
		}
		
		$data['active_pools']['0']						= $row['pool_0_id'];
		$data['active_pools']['1']						= $row['pool_1_id'];
		$data['active_pools']['2']						= $row['pool_2_id'];
		
		$data['user_id']								= $row['user_id'];
		$data['customer_id']							= $row['customer_id'];

		if($account_details['temp_setting'] == 'f')
		{
			$data['pcb_temp_1']							= c_to_f($data['pcb_temp_1']);
			$data['pcb_temp_2']							= c_to_f($data['pcb_temp_2']);
			$data['pcb_temp_3']							= c_to_f($data['pcb_temp_3']);
			$data['chip_temp_1']						= c_to_f($data['chip_temp_1']);
			$data['chip_temp_2']						= c_to_f($data['chip_temp_2']);
			$data['chip_temp_3']						= c_to_f($data['chip_temp_3']);
			$data['pcb_temp']							= c_to_f($data['pcb_temp']);
			$data['chip_temp']							= c_to_f($data['chip_temp']);
		}
			
		$data['pcb_temp_1']								= $data['pcb_temp_1'] . $account_details['temp_symbol'];
		$data['pcb_temp_2']								= $data['pcb_temp_2'] . $account_details['temp_symbol'];
		$data['pcb_temp_3']								= $data['pcb_temp_3'] . $account_details['temp_symbol'];
		$data['chip_temp_1']							= $data['chip_temp_1'] . $account_details['temp_symbol'];
		$data['chip_temp_2']							= $data['chip_temp_2'] . $account_details['temp_symbol'];
		$data['chip_temp_3']							= $data['chip_temp_3'] . $account_details['temp_symbol'];
		$data['pcb_temp']								= $data['pcb_temp'] . $account_details['temp_symbol'];
		$data['chip_temp']								= $data['chip_temp'] . $account_details['temp_symbol'];

		$data['customer']								= get_user_details($row['customer_id']);

		if(empty($data['customer']['firstname']))
		{
			$data['customer']['firstname'] 		= '';
			$data['customer']['lastname'] 		= '';
			$data['customer']['fullname'] 		= '';
		}

		$data['manual_fan_speed']						= $row['manual_fan_speed'];
		$data['fan_1_speed']							= $row['fan_1_speed'];
		$data['fan_2_speed']							= $row['fan_2_speed'];
		$data['manual_freq']							= $row['manual_freq'];
		$data['frequency']								= $row['frequency'];
		$data['kernel_log']								= base64_decode($row['kernel_log']);
	}

	$query = "SELECT `location_lat`, `location_lng` FROM `sites` WHERE `id` = '".$data['site_id']."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['gps']['latitude']		= $row['location_lat'];
		$data['gps']['longitude']		= $row['location_lng'];
	}
	
	return $data;
}

function get_miner_lite($miner_id, $account_id)
{
	if(empty($account_id))
	{
		$account_id = $_SESSION['account']['id'];
	}

	$account_details = account_details($account_id);

	$query = "SELECT * FROM `site_miners` WHERE `id` = '".$miner_id."' ";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['name']					= stripslashes($row['name']);
		$data['site_id']				= stripslashes($row['site_id']);
	}

	return $data;
}

function show_miners_full($site_id)
{
	$site = get_site($site_id);
	
	$query = "SELECT * FROM `site_miners` WHERE `site_id` = '".$site_id."' ORDER BY `name`,INET_ATON(ip_address) ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data							= get_miner($row['id']);
		
		if($data['status_raw'] == 'mining')	{$data['status'] = '<font color="green">Mining</font>';}
		if($data['status_raw'] == 'new')	{$data['status'] = '<font color="blue">Pending</font>';}
		if($data['status_raw'] == 'pending'){$data['status'] = '<font color="blue">Pending</font>';}
		if($data['status_raw'] == 'offline'){$data['status'] = '<font color="red">Offline</font>';}
		if($data['status_raw'] == 'disconnected'){$data['status'] = '<font color="red">Disconnected</font>';}
		
		if($data['pcb_temp']>$data['max_chip_temp']){
			$data['warning']					= 'yes';
			$data['warning_table'] 				= 'class="invalid"';
			$data['warning_text'][]				= 'High PCB Temp';
		}
		
		if($data['chip_temp']>$data['max_chip_temp']){
			$data['warning']					= 'yes';
			$data['warning_table'] 				= 'class="invalid"';
			$data['warning_text'][]				= 'High Chip Temp';
		}
		
		if($row['hardware'] != 'ebite9plus' && $row['hardware'] != 'spondoolies'){
			if($data['asics_1'] == 0 || $data['asics_2'] == 0 || $data['asics_3'] == 0){
				$data['warning']				= 'yes';
				$data['warning_table'] 			= 'class="invalid"';
				$data['warning_text'][]			= 'ASIC Count';
			}
		}
		
		if($data['profit'] < 0.00){
			$data['warning']					= 'yes';
			$data['warning_table'] 				= 'class="invalid"';
			$data['warning_text'][]				= 'Low Revenue';
		}
		
		$data['warning_text'] 					= implode("<br>", $data['warning_text']);
		
		if($row['status'] == 'mining')
		{
			if(empty($view) || $view == 'full')
			{
				echo '
					<tr>
						<th '.$data['warning_table'].'>
							'.$data['name'].' <br>
							<span style="font-weight:normal;">'.$data['ip_address'].'</span> <br>
							<span style="font-weight:normal;">'.$data['hardware'].'</span> <br>
							<span style="font-weight:normal;">'.$data['location'].'</span>
						</th>
						<th '.$data['warning_table'].'>
							'.$data['status'].'<br>

							PCB Temps: <span style="font-weight:normal;">'.$data['pcb_temp_1'].' / '.$data['pcb_temp_2']. ' / '.$data['pcb_temp_3'].'°C </span> <br>
							Chip Temps: <span style="font-weight:normal;">'.$data['chip_temp_1'].' / '.$data['chip_temp_2']. ' / '.$data['chip_temp_3'].'°C </span> <br>
							Avg Temps: <span style="font-weight:normal;">'.$data['pcb_temp'].' / '.$data['chip_temp'].'°C ('.c_to_f($data['pcb_temp']).' / '.c_to_f($data['chip_temp']).'°F)</span>
						</th>
						<th '.$data['warning_table'].'>
							Accepted: <span style="font-weight:normal;">'.$data['accepted'].' </span><br>
							Rejected: <span style="font-weight:normal;">'.$data['rejected'].' </span><br>
							HW Errors: <span style="font-weight:normal;">'.$data['hardware_errors'].' </span>
						</th>
						<th '.$data['warning_table'].'>
							Hash: <span style="font-weight:normal;">'.$data['hashrate'].' </span><br>
							ASICs: <span style="font-weight:normal;">'.$data['asics_1'].' / '.$data['asics_2'].' / '.$data['asics_3'].' </span><br>
						</th>
						<th '.$data['warning_table'].'>
							Algorithm: <span style="font-weight:normal;">'.$data['algorithm'].'</span> <br>
							Pool: <span style="font-weight:normal;">'.$data['pool_data'].'</span> <br>
							'.($data['pool_profile']['name']!='' ? 'Profile: '.$data['pool_profile']['name'] : '').'
						</th>
						<th '.$data['warning_table'].'>
							Rev Day/Mon: <span style="font-weight:normal;">$'.number_format($data['revenue'] / 30, 2) .' / $'.number_format($data['revenue'], 2) . '</span> <br>
							Cost Day/Mon: <span style="font-weight:normal;">$'.number_format($data['cost'] / 30 , 2) .' / $'.number_format($data['cost'], 2). '</span> <br>
							Profit Day/Mon: <span style="font-weight:normal;">$'.number_format($data['profit'] / 30, 2) .' / $'.number_format($data['profit'], 2) . '</span>
						</th>
						<th '.$data['warning_table'].'>
							'.$data['warning_text'].'
						</th>
						<th '.$data['warning_table'].' width="100px">
							<a href="?c=miner&miner_id='.$data['id'].'" class="btn btn-info">View</a>
							<a href="actions.php?a=job_add&site_id='.$site_id.'&miner_id='.$data['id'].'&job=reboot_miner" class="btn btn-warning" '.($data['pending_jobs']==0 ? '' : 'disabled').'>Reboot</a> <br>
							<small>'.$data['updated'].'</small>
						</th>
					</tr>
				';
			}else{
				echo '
					<tr>
						<th '.$data['warning_table'].'>
							'.$data['name'].' / <span style="font-weight:normal;">'.$data['ip_address'].'</span>
						</th>
						<th '.$data['warning_table'].'>
							'.$data['status'].' <span style="font-weight:normal;">('.$data['pcb_temp'].' / '.$data['chip_temp'].'°C)</span>
						</th>
						<th '.$data['warning_table'].'>
							A: <span style="font-weight:normal;">'.$data['accepted'].'</span> R: <span style="font-weight:normal;">'.$data['rejected'].'</span> HW: <span style="font-weight:normal;">'.$data['hardware_errors'].' </span>
						</th>
						<th '.$data['warning_table'].'>
							<span style="font-weight:normal;">'.$data['hashrate'].'</span>
						</th>
						<th '.$data['warning_table'].'>
							<span style="font-weight:normal;">'.$data['algorithm'].'</span>
						</th>
						<th '.$data['warning_table'].'>
							Profit Day: <span style="font-weight:normal;">$'.number_format($data['profit'] / 30, 2) .'</span>
						</th>
						<th '.$data['warning_table'].'>
							'.$data['warning_text'].'
						</th>
						<th '.$data['warning_table'].' width="100px">
							<a href="?c=miner&miner_id='.$data['id'].'" class="btn btn-info">View</a>
							<a href="actions.php?a=job_add&site_id='.$site_id.'&miner_id='.$data['id'].'&job=reboot_miner" class="btn btn-warning" '.($data['pending_jobs']==0 ? '' : 'disabled').'>Reboot</a> <br>
						</th>
					</tr>
				';
			}
		}else{
			if(empty($view) || $view == 'full')
			{
				echo '
					<tr>
						<th>
							'.$data['name'].' <br>
							<span style="font-weight:normal;">'.$data['ip_address'].'</span><br>
							<span style="font-weight:normal;">'.$data['hardware'].'</span><br>
							<span style="font-weight:normal;">'.$data['location'].'</span>						
						</th>
						<th>
							'.$data['status'].'
						</th>
						<th>

						</th>
						<th>

						</th>
						<th>

						</th>
						<th>

						</th>
						<th>

						</th>
						<th width="100px">
							<a href="?c=miner&miner_id='.$data['id'].'" class="btn btn-info">View</a>
							<a href="actions.php?a=job_add&site_id='.$site_id.'&miner_id='.$data['id'].'&job=reboot_miner" class="btn btn-warning" '.($data['pending_jobs']==0 ? '' : 'disabled').'>Reboot</a> <br>
							<small>'.$data['updated'].'</small>
						</th>
					</tr>
				';
			}else{
				echo '
					<tr>
						<th>
							'.$data['name'].' / <span style="font-weight:normal;">'.$data['ip_address'].'</span>
						</th>
						<th>
							'.$data['status'].'
						</th>
						<th>

						</th>
						<th>

						</th>
						<th>

						</th>
						<th>

						</th>
						<th>

						</th>
						<th width="100px">
							<a href="?c=miner&miner_id='.$data['id'].'" class="btn btn-info">View</a>
							<a href="actions.php?a=job_add&site_id='.$site_id.'&miner_id='.$data['id'].'&job=reboot_miner" class="btn btn-warning" '.($data['pending_jobs']==0 ? '' : 'disabled').'>Reboot</a>
						</th>
					</tr>
				';
			}
		}
		unset($data);
	}
	$count++;
}

function show_miners_ajax_template($site_id)
{
	$site = get_site($site_id);
	
	$query = "SELECT * FROM `site_miners` WHERE `site_id` = '".$site_id."' ORDER BY `name`,INET_ATON(ip_address) ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data							= get_miner($row['id'], $_SESSION['account']['id']);
		
		$data['warning']				= 'no';		
		$data['warning_table']			= '';
		$data['warning_text']			= '';
		
		if($data['status_raw'] == 'mining')
		{
			if($data['pcb_temp']>$data['max_chip_temp']){
				$data['warning']					= 'yes';
				$data['warning_table'] 				= 'class="invalid"';
				$data['warning_text'][]				= 'High PCB Temp';
			}

			if($data['chip_temp']>$data['max_chip_temp']){
				$data['warning']					= 'yes';
				$data['warning_table'] 				= 'class="invalid"';
				$data['warning_text'][]				= 'High Chip Temp';
			}

			if($row['hardware'] != 'ebite9plus' && $row['hardware'] != 'spondoolies'){
				if($data['asics_1'] == 0 || $data['asics_2'] == 0 || $data['asics_3'] == 0){
					$data['warning']				= 'yes';
					$data['warning_table'] 			= 'class="invalid"';
					$data['warning_text'][]			= 'ASIC Count';
				}
			}

			if($data['profit'] < 0.00){
				$data['warning']					= 'yes';
				$data['warning_table'] 				= 'class="invalid"';
				$data['warning_text'][]				= 'Low Revenue';
			}

			if(is_array($data['warning_text']))
			{
				$data['warning_text'] 					= implode("<br>", $data['warning_text']);
			}
		}
		
		echo '
			<tr>
				<td id="'.$data['id'].'_td_0" class="">
					<span id="'.$data['id'].'_col_0">
						<input type="checkbox" class="chk" name="miner_select[]" value="'.$data['id'].'" onclick="multi_options();">
					</span>
				</td>
				<td id="'.$data['id'].'_td_1" class="">
					<span id="'.$data['id'].'_col_1"></span>
				</td>
				<td id="'.$data['id'].'_td_2" class="">
					<span id="'.$data['id'].'_col_2"></span>
				</td>
				<td id="'.$data['id'].'_td_3" class="">
					<span id="'.$data['id'].'_col_3"></span>
				</td>
				<td id="'.$data['id'].'_td_4" class="">
					<span id="'.$data['id'].'_col_4"></span>
				</td>
				<td id="'.$data['id'].'_td_5" class="">
					<span id="'.$data['id'].'_col_5"></span>
				</td>
				<td id="'.$data['id'].'_td_6" class="">
					<span id="'.$data['id'].'_col_6"></span>
				</td>
				<td id="'.$data['id'].'_td_7" class="">
					<span id="'.$data['id'].'_col_7"></span>
				</td>
				<td id="'.$data['id'].'_td_8" class="">
					<span id="'.$data['id'].'_col_8"></span>
				</td>
			</tr>
		';
		
		unset($data);
	}
	$count++;
}

function show_miners_ajax_template_test($site_id, $type)
{
	$site = get_site($site_id);

	if($type == 'asic'){
		$query_type = "AND `type` = 'asic' ";
	}elseif($type == 'gpu'){
		$query_type = "AND `type` = 'gpu' ";
	}else{
		$query_type = '';
	}
	
	$query = "SELECT `id`,`hardware`,`ip_address`,`username`,`password`,`software_version`,`type`,`location_row`,`location_rack`,`location_shelf`,`location_position` FROM `site_miners` WHERE `site_id` = '".$site_id."' ".$query_type." ORDER BY `name`,INET_ATON(ip_address) ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$miner['id']							= $row['id'];
		$miner['hardware']						= strtoupper($row['hardware']);
		$miner['hardware']						= str_replace(array("-","_")," ", $miner['hardware']);
		$miner['ip_address']					= $row['ip_address'];
		$miner['username']						= $row['username'];
		$miner['password']						= $row['password'];
		$miner['software_version']				= $row['software_version'];

		$miner['location_row']					= $row['location_row'];
		$miner['location_rack']					= $row['location_rack'];
		$miner['location_shelf']				= $row['location_shelf'];
		$miner['location_position']				= $row['location_position'];


		if($row['type'] == 'asic'){
			$web_link = 'http://'.$miner['ip_address'];
		}else{
			$web_link = 'https://'.$miner['ip_address'].':4200';
		}
		
		$type = 'asic';

		echo '
			<tr>
				<td id="'.$type.'_'.$miner['id'].'_td_0" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_0">
						<input type="checkbox" class="chk" name="miner_select[]" value="'.$miner['id'].'" onclick="multi_options();">
					</span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_1" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_1">
						<span data-html="true" data-toggle="tooltip" data-placement="top" title="
						<strong>Miner Location:</strong> '.$miner['location_row'].'-'.$miner['location_rack'].'-'.$miner['location_shelf'].'-'.$miner['location_position'].' <br>
						<strong>Username:</strong> '.$miner['username'].' <br>
						<strong>Password:</strong> '.$miner['password'].'">
						<a href="'.$web_link.'" target="_blank">'.$miner['ip_address'].'</a>
						</span>
					</span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_2" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_2"></span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_3" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_3">
						<span data-html="true" data-toggle="tooltip" data-placement="top" title="<strong>Software Version:</strong> '.$miner['software_version'].'">
							'.$miner['hardware'].'
						</span>
					</span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_4" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_4"></span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_5" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_5"></span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_6" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_6">'.$miner['asics_1'].'</span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_7" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_7"></span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_8" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_8"></span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_9" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_9"></span>
				</td>
				<td id="'.$type.'_'.$miner['id'].'_td_10" class="">
					<span id="'.$type.'_'.$miner['id'].'_col_10"></span>
				</td>
				
			</tr>
		';
		
		unset($miner);
	}
	$count++;
}

function show_miners_ajax_template_customer()
{
	$customer_id = $_SESSION['account']['id'];
	
	$query = "SELECT * FROM `site_miners` WHERE `customer_id` = '".$customer_id."' ORDER BY `name`,INET_ATON(ip_address) ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data							= get_miner($row['id'], $_SESSION['account']['id']);
		
		$data['warning']				= 'no';		
		$data['warning_table']			= '';
		$data['warning_text']			= '';
		
		if($data['status_raw'] == 'mining')
		{
			if($data['pcb_temp']>$data['max_chip_temp']){
				$data['warning']					= 'yes';
				$data['warning_table'] 				= 'class="invalid"';
				$data['warning_text'][]				= 'High PCB Temp';
			}

			if($data['chip_temp']>$data['max_chip_temp']){
				$data['warning']					= 'yes';
				$data['warning_table'] 				= 'class="invalid"';
				$data['warning_text'][]				= 'High Chip Temp';
			}

			if($row['hardware'] != 'ebite9plus' && $row['hardware'] != 'spondoolies'){
				if($data['asics_1'] == 0 || $data['asics_2'] == 0 || $data['asics_3'] == 0){
					$data['warning']				= 'yes';
					$data['warning_table'] 			= 'class="invalid"';
					$data['warning_text'][]			= 'ASIC Count';
				}
			}

			if($data['profit'] < 0.00){
				$data['warning']					= 'yes';
				$data['warning_table'] 				= 'class="invalid"';
				$data['warning_text'][]				= 'Low Revenue';
			}

			if(is_array($data['warning_text']))
			{
				$data['warning_text'] 					= implode("<br>", $data['warning_text']);
			}
		}
		
		echo '
			<tr>
				<td id="'.$data['id'].'_td_0" class="">
					<span id="'.$data['id'].'_col_0">
						<input type="checkbox" class="chk" name="miner_select[]" value="'.$data['id'].'" onclick="multi_options();">
					</span>
				</td>
				<td id="'.$data['id'].'_td_1" class="">
					<span id="'.$data['id'].'_col_1"></span>
				</td>
				<td id="'.$data['id'].'_td_2" class="">
					<span id="'.$data['id'].'_col_2"></span>
				</td>
				<td id="'.$data['id'].'_td_3" class="">
					<span id="'.$data['id'].'_col_3"></span>
				</td>
				<td id="'.$data['id'].'_td_4" class="">
					<span id="'.$data['id'].'_col_4"></span>
				</td>
				<td id="'.$data['id'].'_td_5" class="">
					<span id="'.$data['id'].'_col_5"></span>
				</td>
				<td id="'.$data['id'].'_td_6" class="">
					<span id="'.$data['id'].'_col_6"></span>
				</td>
				<td id="'.$data['id'].'_td_7" class="">
					<span id="'.$data['id'].'_col_7"></span>
				</td>
				<td id="'.$data['id'].'_td_8" class="">
					<span id="'.$data['id'].'_col_8"></span>
				</td>
			</tr>
		';
		
		unset($data);
	}
	$count++;
}

function convert_currency($crypto)
{
	$query = "SELECT * FROM `crypto_prices` WHERE `currency` = '".$crypto."' ";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['usd']						= $row['usd'];
		$data['eur']						= $row['eur'];
		$data['gbp']						= $row['gbp'];
	}

	return $data;
}

// pools
function show_pools()
{
	$btc_price							= convert_currency('BTC');

	$query = "SELECT * FROM `pools` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['name']					= stripslashes($row['name']);
		$data['url']					= stripslashes($row['url']);
		$data['port']					= stripslashes($row['port']);
		$data['username']				= stripslashes($row['username']);
		$data['password']				= stripslashes($row['password']);
		
		if (strpos($data['url'], 'nicehash') !== false && !empty($row['nicehash_api_id']) && !empty($row['nicehash_api_key'])) {
		    $data['nicehash_balance']['bits']		= get_nicehash_balance($row['nicehash_api_id'], $row['nicehash_api_key']);
		    $data['nicehash_balance']['btc']		= $data['nicehash_balance']['bits']['result']['balance_confirmed'] + $data['nicehash_stats']['bits']['result']['balance_pending'];
		    $data['nicehash_balance']['usd']		= $btc_price['usd'] * $data['nicehash_balance']['btc'];
		    $data['nicehash_balance']['gbp']		= $btc_price['gbp'] * $data['nicehash_balance']['btc'];

		    $data['nicehash_balance']['balance']	= '$'.number_format($data['nicehash_balance']['usd'], 2);
		}else{
			unset($data['nicehash_balance']);
		}

		echo '
			<tr>
				<td>
					'.$data['name'].'
				</td>
				<td>
					'.$data['url'].'
				</td>
				<td>
					'.$data['username'].'
				</td>
				<td>
					'.$data['nicehash_balance']['balance'].'
				</td>
				<td width="100px">
					<a href="?c=pool&pool_id='.$data['id'].'" class="btn btn-info">View</a>
					<a href="actions.php?a=pool_delete&miners&pool_id='.$data['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-danger">Delete</a>
				</td>
			</tr>
		';
		unset($data);
	}
}

function get_pools()
{
	$query = "SELECT * FROM `pools` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['name']					= stripslashes($row['name']);
		$data[$count]['url']					= stripslashes($row['url']);
		$data[$count]['port']					= stripslashes($row['port']);
		$data[$count]['username']				= stripslashes($row['username']);
		$data[$count]['password']				= stripslashes($row['password']);
		$count++;
	}
	
	return $data;
}

function get_pool($pool_id)
{
	$query = "SELECT * FROM `pools` WHERE `id` = '".$pool_id."' ";
	$pool['query'] = $query;
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$pool['id']						= $row['id'];
		$pool['name']					= stripslashes($row['name']);
		$pool['url']					= stripslashes($row['url']);
		$pool['port']					= stripslashes($row['port']);
		$pool['username']				= stripslashes($row['username']);
		$pool['password']				= stripslashes($row['password']);
		$pool['coin_id']				= stripslashes($row['coin_id']);
		$pool['xnsub']					= stripslashes($row['xnsub']);
		$pool['nicehash_api_id']		= stripslashes($row['nicehash_api_id']);
		$pool['nicehash_api_key']		= stripslashes($row['nicehash_api_key']);
	}
	
	return $pool;
}

function show_pool_profiles()
{
	$query = "SELECT * FROM `pool_profiles` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['name']					= stripslashes($row['name']);
		$data['pool'][0]				= get_pool($row['pool_0']);
		$data['pool'][1]				= get_pool($row['pool_1']);
		$data['pool'][2]				= get_pool($row['pool_2']);
		
		echo '
			<tr>
				<td>
					'.$data['name'].'
				</td>
				<td>
					'.(isset($data['pool'][0]['name']) ? $data['pool'][0]['name'] : '').'
				</td>
				<td>
					'.(isset($data['pool'][1]['name']) ? $data['pool'][1]['name'] : '').'
				</td>
				<td>
					'.(isset($data['pool'][2]['name']) ? $data['pool'][2]['name'] : '').'
				</td>
				<td>
					<a href="?c=pool_profile&profile_id='.$data['id'].'" class="btn btn-info">View</a>
					<a href="actions.php?a=pool_profile_delete&profile_id='.$data['id'].'" onclick="return confirm(\'Are you sure?\')" class="btn btn-danger">Delete</a>
				</td>
			</tr>
		';
		unset($data);
	}
}

function get_pool_profiles()
{
	$query = "SELECT * FROM `pool_profiles` WHERE `user_id` = '".$_SESSION['account']['id']."' ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	if(mysql_num_rows($result) > 0)
	{
		$data['status'] = 'success';
	}else{
		$data['status'] = 'no_data';
	}
	while($row = mysql_fetch_array($result)){
		$data['data'][$count]['id']						= $row['id'];
		$data['data'][$count]['name']					= stripslashes($row['name']);
		$data['data'][$count]['pool'][0]				= get_pool($row['pool_0']);
		$data['data'][$count]['pool'][1]				= get_pool($row['pool_1']);
		$data['data'][$count]['pool'][2]				= get_pool($row['pool_2']);
		$count++;
	}
	
	return $data;
}

function get_pool_profile($id)
{
	if($id == 0){
		$pool_profile['name'] 	= 'No Profile';
		return $pool_profile;
	}else{
		$pool_profile = '';
		$query = "SELECT * FROM `pool_profiles` WHERE `id` = '".$id."' ";
		$result = mysql_query($query) or die(mysql_error());	
		while($row = mysql_fetch_array($result)){
			$pool_profile['id']						= $row['id'];
			$pool_profile['name']					= stripslashes($row['name']);
			$pool_profile['pool'][0]				= get_pool($row['pool_0']);
			$pool_profile['pool'][1]				= get_pool($row['pool_1']);
			$pool_profile['pool'][2]				= get_pool($row['pool_2']);
		}

		return $pool_profile;
	}
}

function get_crypto_prices($currency = '')
{
	$query 		= "SELECT * FROM `crypto_prices` ";
	$result 	= mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data[$row['currency']]['id']	= $row['id'];
		$data[$row['currency']]['usd']	= $row['usd'];
		$data[$row['currency']]['gbp']	= $row['gbp'];
		$data[$row['currency']]['eur']	= $row['eur'];
	}
	
	return $data;
}

function build_heatmap_array($site_id)
{
	$query = "SELECT * FROM `site_miners` WHERE `site_id` = '".$site_id."' ORDER BY `location_row`,`location_rack`,`location_shelf`,`location_position` ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	$data = array();
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['name']					= stripslashes($row['name']);
		$data[$count]['ip_address']				= stripslashes($row['ip_address']);
		
		$data[$count]['status']					= $row['status'];
		$data[$count]['paused']					= $row['paused'];

		$data[$count]['hardware']				= stripslashes($row['hardware']);
		$data[$count]['hardware']				= strtoupper($data[$count]['hardware']);
		$data[$count]['hardware']				= str_replace(array("-","_")," ", $data[$count]['hardware']);
		
		$data[$count]['location']['row']		= $row['location_row'];
		$data[$count]['location']['rack']		= $row['location_rack'];
		$data[$count]['location']['shelf']		= $row['location_shelf'];
		$data[$count]['location']['position']	= $row['location_position'];

		if($row['customer_id'] != ''){
			$data[$count]['customer']				= get_user_details($row['customer_id']);
		}else{
			$data[$count]['customer']['fullname']	= 'none';
		}
		
		
		$data[$count]['temp']['pcb_temp_1']				= $row['pcb_temp_1'];
		$data[$count]['temp']['pcb_temp_2']				= $row['pcb_temp_2'];
		$data[$count]['temp']['pcb_temp_3']				= $row['pcb_temp_3'];
		$temp_pcb_temp 									= array($row['pcb_temp_1'], $row['pcb_temp_2'], $row['pcb_temp_3']);
		$data[$count]['temp']['pcb_temp'] 				= number_format(array_sum($temp_pcb_temp) / count($temp_pcb_temp));
		
		if($row['hardware'] == 'antminer-d3')
		{
			$symbol = ' GH';
		}else{
			$symbol = ' TH';
		}

		$data[$count]['hashrate']						= $row['hashrate_1']+$row['hashrate_2']+$row['hashrate_3']+$row['hashrate_4'];
		$data[$count]['hashrate']						= number_format($data[$count]['hashrate'] / 1000, 1);
		
		$data[$count]['status']							= $data[$count]['temp']['pcb_temp'] . ' °C';

		if($row['status'] != 'mining')
		{
			$data[$count]['status']						= '<font color="red"><strong>OFF</strong></font>';
		}

		if($row['paused'] == 'yes')
		{
			$data[$count]['status']						= '<font color="red"><strong>PAU</strong></font>';
		}
		
		$data[$count]['hashrate']						= $data[$count]['hashrate'] . $symbol;
		
		// $data[$count]['temp']['chip_temp_1']			= $row['chip_temp_1'];
		// $data[$count]['temp']['chip_temp_2']			= $row['chip_temp_2'];
		// $data[$count]['temp']['chip_temp_3']			= $row['chip_temp_3'];
		// $temp_chip_temp 								= array($row['chip_temp_1'], $row['chip_temp_2'], $row['chip_temp_3']);
		// $data[$count]['temp']['average_chip_temp'] 		= number_format(array_sum($temp_chip_temp) / count($temp_chip_temp));
				
		$count++;
	}
	
	foreach($data as $miner){
		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_id'] = $miner['id'];

		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_name'] = $miner['name'];

		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_ip'] = $miner['ip_address'];

		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_location'] = $miner['location']['rack'].'-'.$miner['location']['shelf'].'-'.$miner['location']['position'];

		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_hardware'] = $miner['hardware'];

		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_customer'] = $miner['customer'];

		if(strip_tags($miner['status']) == 'OFF' || strip_tags($miner['status']) == 'PAU')
		{
			$data['table']
				[$miner['location']['row']]
					[$miner['location']['rack']]
						[$miner['location']['shelf']]
							[$miner['location']['position']]
								['miner_temp'] = 0;

			
		}else{
			$data['table']
				[$miner['location']['row']]
					[$miner['location']['rack']]
						[$miner['location']['shelf']]
							[$miner['location']['position']]
								['miner_temp'] = $miner['temp']['pcb_temp'];

		}

		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_status'] = $miner['status'];

		$data['table']
			[$miner['location']['row']]
				[$miner['location']['rack']]
					[$miner['location']['shelf']]
						[$miner['location']['position']]
							['miner_hashrate'] = $miner['hashrate'];

	}
	
	return $data;
}

function whattomine_coins($coin)
{
	$query = "SELECT * FROM `whattomine_coins` WHERE `name` = '".$coin."' ";
	$result = mysql_query($query) or die(mysql_error());
	$data['query'] = $query;
	while($row = mysql_fetch_array($result)){
		$data['name']					= $row['name'];
		$data['difficulty']				= number_format($row['difficulty']);
		$data['difficulty']				= str_replace(',', '', $data['difficulty']);
		$data['block_reward']			= number_format($row['block_reward']);
	}

	return $data;
}


function mining_calc($coin, $hashrate, $power_cost, $power)
{
	$coin_data = whattomine_coins($coin);

	if($coin == 'Bitcoin'){
		$hashrate 				= $hashrate * 1000000000;
		$coin_symbol			= "BTC";
		$data['difficulty']		= $coin_data['difficulty'];
		$data['block_reward']	= $coin_data['block_reward'];
		$data['power_watts']	= $power;
	}

	if($coin == 'Dash'){
		$hashrate 				= $hashrate * 1000000;
		$coin_symbol			= "DASH";
		$data['difficulty']		= $coin_data['difficulty'];
		$data['block_reward']	= $coin_data['block_reward'];
		$data['power_watts']	= $power;
	}

	if($coin == 'Litecoin'){
		$hashrate 				= $hashrate * 1000000;
		$coin_symbol			= "LTC";
		$data['difficulty']		= $coin_data['difficulty'];
		$data['block_reward']	= $coin_data['block_reward'];
		$data['power_watts']	= $power;
	}

	if($coin == 'Sia' || $coin == 'Siacoin'){
		$hashrate 				= $hashrate * 1000000;
		$coin_symbol			= "SC";
		$data['difficulty']		= number_format($coin_data['difficulty'], 0);
		$data['difficulty']		= str_replace(',', '', $data['difficulty']);
		$data['block_reward']	= $coin_data['block_reward'];
		$data['power_watts']	= $power;
	}

	if($coin == 'ETN' || $coin == 'electroneum'){
		$hashrate 				= $hashrate * 1000000;
		$coin_symbol			= "SC";
		$data['difficulty']		= number_format($coin_data['difficulty'], 0);
		$data['difficulty']		= str_replace(',', '', $data['difficulty']);
		$data['block_reward']	= $coin_data['block_reward'];
		$data['power_watts']	= $power;
	}

	if($coin == 'ETH' || $coin == 'eth' || $coin == 'Ethereum' || $coin == 'ethereum'){
		$hashrate 				= $hashrate * 1000000;
		$coin_symbol			= "ETH";
		$data['difficulty']		= number_format($coin_data['difficulty'], 0);
		$data['difficulty']		= str_replace(',', '', $data['difficulty']);
		$data['block_reward']	= $coin_data['block_reward'];
		$data['power_watts']	= $power;
	}

	$data['hashrate']			= $hashrate;

	$coin_raw					= file_get_contents("https://min-api.cryptocompare.com/data/price?fsym=".$coin_symbol."&tsyms=USD,EUR,GBP,BTC");
	$coin_bits					= json_decode($coin_raw, true);
	$data['usd_value']			= $coin_bits['USD'];
	$data['gbp_value']			= $coin_bits['GBP'];
	$data['eur_value']			= $coin_bits['EUR'];

	// mining calc
	$data['power_cost']			= $power_cost;

	$data['power_kilowatts']	= $data['power_watts'] / 1000;
	$data['kwh_month']			= $data['power_kilowatts']  * 24 * 30;
	$data['cost_per_month']		= $data['kwh_month'] * $data['power_cost'];
	$data['pool_fee']			= 1;
	$data['hashrate'] 			= $hashrate; // hashrate in hash/s
	$data['days'] 				= 30; // days in a month
	$data['seconds'] 			= 86400; // seconds in a day

	$data['coin_per_month'] 	= ($data['days']*$data['hashrate']*$data['block_reward']*$data['seconds'])/(4294967296*$data['difficulty']);
	$data['coin_per_month_1']	= number_format($data['coin_per_month'], 20);
	$data['usd_per_month']		= $data['usd_value'] * $data['coin_per_month'];

	$data['revenue']['usd']		= number_format($data['usd_value'] * $data['coin_per_month'], 2);

	$data['cost']['usd']		= number_format($data['cost_per_month'], 2);

	$data['profit']['usd']		= number_format($data['usd_value'] * $data['coin_per_month'] - $data['cost_per_month'], 2);

	return $data;
}

function system_alert_add($site_id, $miner_id, $user_id, $customer_id, $type)
{

	$miner = get_miner($miner_id, '');

	if(empty($user_id) || $user_id == 0){
		$user_id = $customer_id;
	}

	if($type == 'low_revenue'){
		$message = $miner['ip_address'] . ' is marked as low revenue.';
	}

	$input = mysql_query("INSERT IGNORE INTO `notifications` 
		(`added`, `status`, `site_id`, `miner_id`, `user_id`, `type`, `message`)
		VALUE
		('".time()."', 'pending', '".$site_id."', '".$miner_id."', '".$user_id."', '".$type."', '".$message."' )") or die(mysql_error());
	
	$insert_id = mysql_insert_id();
}

function system_job_add($uid, $site_id, $miner_id, $job, $reason)
{
	$input = mysql_query("INSERT IGNORE INTO `site_jobs` 
		(`time`, `site_id`, `miner_id`, `job`, `reason`)
		VALUE
		('".time()."', '".$site_id."', '".$miner_id."', '".$job."', '".addslashes($reason)."' )") or die(mysql_error());
	
	$insert_id = mysql_insert_id();
}

function build_miner_config_file($miner_id)
{
	$miner['id']		= $miner_id;

	$query 			= "SELECT * FROM `site_miners` WHERE `id` = '".$miner['id']."' ";
	$result 		= mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){	
		$site 								= get_site($row['site_id']);

		$temp['worker_name']				= $row['worker_name'];
		if(empty($temp['worker_name'])){
			$temp['worker_name']			= stripslashes($row['name']);
		}

		$data['pool_profile_id']			= $row['pool_profile_id'];
		$temp['bitmain-freq']				= $row['frequency'];
		$temp['hardware']					= $row['hardware'];

		$temp['manual_fan_speed']			= $row['manual_fan_speed'];
		$temp['manual_freq']				= $row['manual_freq'];
		
		if($row['pool_0_id'] != 0){
			$temp['pools'][0]['bits']				= get_pool($row['pool_0_id']);
			$config['pools'][0]['url']				= $temp['pools'][0]['bits']['url'].':'.$temp['pools'][0]['bits']['port'] . ($temp['pools'][0]['bits']['xnsub']=='yes' ? '#xnsub' : '');
			$config['pools'][0]['user']				= $temp['pools'][0]['bits']['username'] . (!empty($row['worker_name']) ? '.'.$row['worker_name']:'');
			$config['pools'][0]['pass']				= $temp['pools'][0]['bits']['password'];
		}else{
			$config['pools'][0]['url']				= '';
			$config['pools'][0]['user']				= '';
			$config['pools'][0]['pass']				= '';
		}
		
		if($row['pool_1_id'] != 0){
			$temp['pools'][1]['bits']				= get_pool($row['pool_1_id']);
			$config['pools'][1]['url']				= $temp['pools'][1]['bits']['url'].':'.$temp['pools'][1]['bits']['port'] . ($temp['pools'][1]['bits']['xnsub']=='yes' ? '#xnsub' : '');
			$config['pools'][1]['user']				= $temp['pools'][1]['bits']['username'] . (!empty($row['worker_name']) ? '.'.$row['worker_name']:'');
			$config['pools'][1]['pass']				= $temp['pools'][1]['bits']['password'];
		}else{
			$config['pools'][1]['url']				= '';
			$config['pools'][1]['user']				= '';
			$config['pools'][1]['pass']				= '';
		}
		
		if($row['pool_2_id'] != 0){
			$temp['pools'][2]['bits']				= get_pool($row['pool_2_id']);
			$config['pools'][2]['url']				= $temp['pools'][2]['bits']['url'].':'.$temp['pools'][2]['bits']['port'] . ($temp['pools'][2]['bits']['xnsub']=='yes' ? '#xnsub' : '');
			$config['pools'][2]['user']				= $temp['pools'][2]['bits']['username'] . (!empty($row['worker_name']) ? '.'.$row['worker_name']:'');
			$config['pools'][2]['pass']				= $temp['pools'][2]['bits']['password'];
		}
		else{
			$config['pools'][2]['url']				= '';
			$config['pools'][2]['user']				= '';
			$config['pools'][2]['pass']				= '';
		}
		
		$config['api-listen'] 					= 'true';
		$config['api-network']					= 'true';
		$config['api-groups']					= 'A:stats:pools:devs:summary:version';
		$config['api-allow']					= 'A:0/0,W:*';
		$config['bitmain-use-vil']				= 'true';
		$config['bitmain-freq']					= $temp['bitmain-freq'];

		$config['multi-version']	= '1';
	}

	if(isset($config) && $temp['hardware'] == 'ebite9plus')
	{
		$config = json_encode($config);
		file_put_contents('/home2/mcp/public_html/dashboard/miner_config_files/'.$miner['id'].'.txt', $config);
		
		
	}elseif(isset($config))
	{		
		// build cgminer.confg / bmminer.conf
		
		$file  = '{'."\n";
		$file .= '"pools" : ['."\n";
		$file .= '{'."\n";
		$file .= '"url" : "'.$config['pools'][0]['url'].'",'."\n";
		$file .= '"user" : "'.$config['pools'][0]['user'].'",'."\n";
		$file .= '"pass" : "'.$config['pools'][0]['pass'].'"'."\n";
		$file .= '},'."\n";
		$file .= '{'."\n";
		$file .= '"url" : "'.$config['pools'][1]['url'].'",'."\n";
		$file .= '"user" : "'.$config['pools'][1]['user'].'",'."\n";
		$file .= '"pass" : "'.$config['pools'][1]['pass'].'"'."\n";
		$file .= '},'."\n";
		$file .= '{'."\n";
		$file .= '"url" : "'.$config['pools'][2]['url'].'",'."\n";
		$file .= '"user" : "'.$config['pools'][2]['user'].'",'."\n";
		$file .= '"pass" : "'.$config['pools'][2]['pass'].'"'."\n";
		$file .= '}'."\n";
		$file .= ']'."\n";
		$file .= ','."\n";
		
		// if(strpos($temp['hardware'], 'antminer-s9') !== false)
		if($temp['hardware'] == 'antminer-s9')
		{
			$file .= '"api-listen" : true,'."\n";
			$file .= '"api-network" : true,'."\n";
			$file .= '"api-groups" : "A:stats:pools:devs:summary:version:noncenum:switchpool:addpool:poolpriority:enablepool:disablepool:removepool:privileged:coin:quit:restart:config",'."\n";
			$file .= '"api-allow" : "A:0/0,W:0/0",'."\n";
			// $file .= '"bitmain-fan-ctrl" : true,'."\n";
			// $file .= '"bitmain-fan-pwm" : "'.$temp['manual_fan_speed'].'",'."\n";
			$file .= '"bitmain-use-vil" : true,'."\n";
			// $file .= '"bitmain-voltage" : "0706",'."\n";
			if($temp['manual_freq'] == '' || $temp['manual_freq'] == '0' || $temp['manual_freq'] == 'default'){
				$file .= '"bitmain-freq" : "550",'."\n";
			}else{
				$file .= '"bitmain-freq" : "'.$temp['manual_freq'].'",'."\n";
			}
			// $file .= '"bitmain-voltage" : 0706,'."\n";
			$file .= '"multi-version" : "1"'."\n";
		}

		if($temp['hardware'] == 'antminer-s9j')
		{
			$file .= '"api-listen" : true,'."\n";
			$file .= '"api-network" : true,'."\n";
			$file .= '"api-groups" : "A:stats:pools:devs:summary:version:noncenum:switchpool:addpool:poolpriority:enablepool:disablepool:removepool:privileged:coin:quit:restart:config",'."\n";
			$file .= '"api-allow" : "A:0/0,W:0/0",'."\n";
			// $file .= '"bitmain-fan-ctrl" : true,'."\n";
			// $file .= '"bitmain-fan-pwm" : "'.$temp['manual_fan_speed'].'",'."\n";
			$file .= '"bitmain-use-vil" : true,'."\n";
			// $file .= '"bitmain-voltage" : "0706",'."\n";
			if($temp['manual_freq'] == '' || $temp['manual_freq'] == '0' || $temp['manual_freq'] == 'default'){
				$file .= '"bitmain-freq" : "550",'."\n";
			}else{
				$file .= '"bitmain-freq" : "'.$temp['manual_freq'].'",'."\n";
			}
			// $file .= '"bitmain-voltage" : 0706,'."\n";
			$file .= '"multi-version" : "1"'."\n";
		}

		// if(strpos($temp['hardware'], 'antminer-d3') !== false)
		if($temp['hardware'] == 'antminer-d3')
		{
			$file .= '"api-listen" : true,'."\n";
			$file .= '"api-network" : true,'."\n";
			$file .= '"api-groups" : "A:stats:pools:devs:summary:version:switchpool:addpool:poolpriority:enablepool:disablepool:removepool:quit:restart:config",'."\n";
			$file .= '"api-allow" : "A:0/0,W:*",'."\n";
			$file .= '"bitmain-fan-ctrl" : true,'."\n";
			$file .= '"bitmain-fan-pwm" : "'.$temp['manual_fan_speed'].'",'."\n";
			if($temp['manual_freq'] == '' || $temp['manual_freq'] == '0' || $temp['manual_freq'] == 'default'){
				$file .= '"bitmain-freq" : "0",'."\n";
			}else{
				$file .= '"bitmain-freq" : "'.$temp['manual_freq'].'",'."\n";
			}
			$file .= '"multi-version" : "1"'."\n";
		}

		// if(strpos($temp['hardware'], 'antminer-l3') !== false)
		if($temp['hardware'] == 'antminer-l3' || $temp['hardware'] == 'antminer-l3+' || $temp['hardware'] == 'antminer-l3++')
		{
			$file .= '"api-listen" : true,'."\n";
			$file .= '"api-network" : true,'."\n";
			$file .= '"api-groups" : "A:stats:pools:devs:summary:version:switchpool:addpool:poolpriority:enablepool:disablepool:removepool:quit:restart:config",'."\n";
			$file .= '"api-allow" : "A:0/0,W:*",'."\n";
			$file .= '"bitmain-fan-ctrl" : true,'."\n";
			$file .= '"bitmain-fan-pwm" : "'.$temp['manual_fan_speed'].'",'."\n";
			$file .= '"bitmain-use-vil" : true,'."\n";
			if($temp['manual_freq'] == '' || $temp['manual_freq'] == '0' || $temp['manual_freq'] == 'default'){
				$file .= '"bitmain-freq" : "0",'."\n";
			}else{
				$file .= '"bitmain-freq" : "'.$temp['manual_freq'].'",'."\n";
			}
			$file .= '"multi-version" : "1"'."\n";
		}

		$file .= '}'."\n";
		
		file_put_contents('/home2/mcp/public_html/dashboard/miner_config_files/'.$miner['id'].'.txt', $file);
		
		unset($file);

		// build network.conf file

		$file  = 'hostname='.$temp['worker_name']."\n";
		$file .= 'dhcp=true'."\n";

		file_put_contents('/home2/mcp/public_html/dashboard/miner_config_files/'.$miner['id'].'_network.txt', $file);


	}else{
		
	}	
}

function build_default_config_file($algorithm)
{

	$query 			= "SELECT * FROM `site_default_pools` WHERE `user_id` = '".$_SESSION['account']['id']."' AND `algorithm` = '".$algorithm."' ";
	$result 		= mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){	

		$default_pool['pool_0_id']						= $row['pool_0'];
		$default_pool['pool_1_id']						= $row['pool_1'];
		$default_pool['pool_2_id']						= $row['pool_2'];
		$default_pool['worker_name']					= 'default_worker';

		if($default_pool['pool_0_id'] != 0){
			$temp['pools'][0]['bits']				= get_pool($default_pool['pool_0_id']);
			$config['pools'][0]['url']				= $temp['pools'][0]['bits']['url'].':'.$temp['pools'][0]['bits']['port'] . ($temp['pools'][0]['bits']['xnsub']=='yes' ? '#xnsub' : '');
			$config['pools'][0]['user']				= $temp['pools'][0]['bits']['username'] . (!empty($default_pool['worker_name']) ? '.'.$default_pool['worker_name']:'');
			$config['pools'][0]['pass']				= $temp['pools'][0]['bits']['password'];
		}else{
			$config['pools'][0]['url']				= '';
			$config['pools'][0]['user']				= '';
			$config['pools'][0]['pass']				= '';
		}
		
		if($default_pool['pool_1_id'] != 0){
			$temp['pools'][1]['bits']				= get_pool($default_pool['pool_1_id']);
			$config['pools'][1]['url']				= $temp['pools'][1]['bits']['url'].':'.$temp['pools'][1]['bits']['port'] . ($temp['pools'][1]['bits']['xnsub']=='yes' ? '#xnsub' : '');
			$config['pools'][1]['user']				= $temp['pools'][1]['bits']['username'] . (!empty($default_pool['worker_name']) ? '.'.$default_pool['worker_name']:'');
			$config['pools'][1]['pass']				= $temp['pools'][1]['bits']['password'];
		}else{
			$config['pools'][1]['url']				= '';
			$config['pools'][1]['user']				= '';
			$config['pools'][1]['pass']				= '';
		}
		
		if($default_pool['pool_2_id'] != 0){
			$temp['pools'][2]['bits']				= get_pool($default_pool['pool_2_id']);
			$config['pools'][2]['url']				= $temp['pools'][2]['bits']['url'].':'.$temp['pools'][2]['bits']['port'] . ($temp['pools'][2]['bits']['xnsub']=='yes' ? '#xnsub' : '');
			$config['pools'][2]['user']				= $temp['pools'][2]['bits']['username'] . (!empty($default_pool['worker_name']) ? '.'.$default_pool['worker_name']:'');
			$config['pools'][2]['pass']				= $temp['pools'][2]['bits']['password'];
		}
		else{
			$config['pools'][2]['url']				= '';
			$config['pools'][2]['user']				= '';
			$config['pools'][2]['pass']				= '';
		}
	}

	// build cgminer.confg / bmminer.conf
	
	$file  = '{'."\n";
	$file .= '"pools" : ['."\n";
	$file .= '{'."\n";
	$file .= '"url" : "'.$config['pools'][0]['url'].'",'."\n";
	$file .= '"user" : "'.$config['pools'][0]['user'].'",'."\n";
	$file .= '"pass" : "'.$config['pools'][0]['pass'].'"'."\n";
	$file .= '},'."\n";
	$file .= '{'."\n";
	$file .= '"url" : "'.$config['pools'][1]['url'].'",'."\n";
	$file .= '"user" : "'.$config['pools'][1]['user'].'",'."\n";
	$file .= '"pass" : "'.$config['pools'][1]['pass'].'"'."\n";
	$file .= '},'."\n";
	$file .= '{'."\n";
	$file .= '"url" : "'.$config['pools'][2]['url'].'",'."\n";
	$file .= '"user" : "'.$config['pools'][2]['user'].'",'."\n";
	$file .= '"pass" : "'.$config['pools'][2]['pass'].'"'."\n";
	$file .= '}'."\n";
	$file .= ']'."\n";
	$file .= ','."\n";
	
	if($algorithm == 'sha256')
	{
		$file .= '"api-listen" : true,'."\n";
		$file .= '"api-network" : true,'."\n";
		$file .= '"api-groups" : "A:stats:pools:devs:summary:version:noncenum:switchpool:addpool:poolpriority:enablepool:disablepool:removepool:privileged:coin",'."\n";
		$file .= '"api-allow" : "A:0/0,W:0/0",'."\n";
		$file .= '"bitmain-fan-ctrl" : true,'."\n";
		$file .= '"bitmain-fan-pwm" : "70",'."\n";
		$file .= '"bitmain-use-vil" : true,'."\n";
		$file .= '"bitmain-voltage" : "0706",'."\n";
		$file .= '"bitmain-freq" : "550",'."\n";
		$file .= '"multi-version" : "1"'."\n";
	}

	if($algorithm == 'x11')
	{
		$file .= '"api-listen" : true,'."\n";
		$file .= '"api-network" : true,'."\n";
		$file .= '"api-groups" : "A:stats:pools:devs:summary:version:switchpool",'."\n";
		$file .= '"api-allow" : "A:0/0,W:*",'."\n";
		$file .= '"bitmain-fan-pwm" : "70",'."\n";
		$file .= '"bitmain-freq" : "0"'."\n";
	}

	$file .= '}'."\n";
	
	file_put_contents('/home2/mcp/public_html/dashboard/miner_config_files/default_'.$algorithm.'_'.$_SESSION['account']['id'].'.txt', $file);
	
	unset($file);
}

function get_customers()
{
	$uid = $_SESSION['account']['id'];
	$query = "SELECT * FROM `users` WHERE `admin_id` = ".$uid." ORDER BY `first_name`,`last_name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data[]			= $row;
	}	

	return $data;
}

function get_customer_miners()
{
	$uid = $_SESSION['account']['id'];
	$query = "SELECT * FROM `site_miners` WHERE `customer_id` = ".$uid." ORDER BY `name`,INET_ATON(ip_address) ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	$data = array();
	while($row = mysql_fetch_array($result)){
		$data[$count]							= get_miner($row['id'], $uid);
		$count++;
	}
	
	return $data;

	return $data;
}

function get_invoices()
{
	$uid = $_SESSION['account']['id'];

	$query = "SELECT * FROM `user_invoices` WHERE `user_id` = '".$uid."' ORDER BY `id` DESC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['status']					= $row['status'];
		$data[$count]['due_date']				= $row['due_date'];
		$data[$count]['amount']					= $row['amount'];

		$count++;
	}
	
	return $data;
}

function get_gpu_miners()
{
	$query = "SELECT * FROM `gpu_miners` ORDER BY `name` ASC";
	$result = mysql_query($query) or die(mysql_error());
	$count = 0;
	while($row = mysql_fetch_array($result)){
		$data[$count]['id']						= $row['id'];
		$data[$count]['name']					= stripslashes($row['name']);
		$data[$count]['folder']					= $row['folder'];
		$data[$count]['app']					= $row['app'];
		$data[$count]['user_options']			= $row['user_options'];
		$data[$count]['system_options']			= $row['system_options'];
		$data[$count]['supports_nvidia']		= $row['supports_nvidia'];
		$data[$count]['supports_amd']			= $row['supports_amd'];

		$count++;
	}
	
	return $data;
}

function get_gpu_miner($miner_id)
{
	$query = "SELECT * FROM `gpu_miners` WHERE `id` = '".$miner_id."' ";
	error_log($query);
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$data['id']						= $row['id'];
		$data['name']					= stripslashes($row['name']);
		$data['folder']					= $row['folder'];
		$data['app']					= $row['app'];
		$data['user_options']			= $row['user_options'];
		$data['system_options']			= $row['system_options'];
		$data['supports_nvidia']		= $row['supports_nvidia'];
		$data['supports_amd']			= $row['supports_amd'];		
	}
	
	return $data;
}