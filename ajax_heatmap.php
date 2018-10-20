<?php
if($_GET['dev'] == 'yes'){
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL);
}

include('inc/db.php');
include('inc/sessions.php');
$sess = new SessionManager();
session_start();

include('inc/global_vars.php');
include('inc/functions.php');

$heatmap 					= build_heatmap_array($_SESSION['account']['id']);

?>

<style>
	.grayout {
	/* display: none; */
	background-color: gray;
	opacity: .3;
}
</style>

<!-- <button class="class="btn btn-primary" onclick="change_temp()">Change Temp</button> -->

<?php

	if(is_array($heatmap['table']))
	{
		foreach ($heatmap['table'] as $key_rows => $rows){
			echo '
			<h4><strong>Row: '.$key_rows.'</strong></h4>

			<div id="heatmap" width="100%">
			';

			foreach($rows as $key_racks => $racks){
				echo '
					<table class="" border="1" style="display: inline-block;">
						<thead><tr><td colspan="5"><strong>Rack: '.$key_racks.'</strong></td></tr></thead>
						<tbody>
				';

					foreach($racks as $shelfs){
						echo '<tr>';

						foreach($shelfs as $position){
							echo '
								<td width="35px" align="center" valign="middle">
									<span class="miner_heatmap miner_customer_id_'.$position['miner_customer']['id'].'">
										<ul id="test2" style="display: table; width: 100%;">
											<li id="heatmap_'.$position['miner_id'].'" style="width: 100%; list-style-type: none; display:inline-block;" data-hist="'.$position['miner_temp'].'">
												<span data-html="true" data-toggle="tooltip" data-placement="top" title="<strong>Name:</strong> '.$position['miner_name'].'  <br> <strong>IP:</strong> '.$position['miner_ip'].'  <br> <strong>Hardware:</strong> '.$position['miner_hardware'].'<br> <strong>Hashrate:</strong> '.$position['miner_hashrate'].' <br> <strong>Customer:</strong> '.$position['miner_customer']['fullname'].'">
												<u>'.$position['miner_location'].'</u>
												</span>
												<br>
												<small>'.$position['miner_status'].'</small>
											</li>
										</ul>
									</span>
								</td>';
						}

						echo '</tr>';
					}

				echo '
					</tbody>
				</table>
				';
			}
			echo '</div>';
		}
	}
?>

<hr>

<table id="heatmap_index" width="100%" cellpadding="4px">
	<tr>
		<?php foreach (range(0,200,5) as $number){ ?>
			<td align="center" valign="middle" style="font-weight: bolder">
				<ul id="test2" style="display: table; width: 100%;">
					<li style="width: 100%; list-style-type: none; display:inline-block;" data-hist="<?php echo $number; ?>"><?php echo $number; ?></li>
				</ul>
			</td>
		<?php } ?>
		
		<!--
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display:inline-block;" data-hist="0">0</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display:inline-block;" data-hist="5">5</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="10">10</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="15">15</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="20">20</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="25">25</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="30">30</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="20">20</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="40">40</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="50">50</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="60">60</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="70">70</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="80">80</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="90">90</li>
			</ul>
		</td>
		<td align="center" valign="middle" style="font-weight: bolder">
			<ul id="test2" style="display: table; width: 100%;">
				<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="100">100</li>
			</ul>
		</td>
		-->
	</tr>
</table>

<script>
	$(function(){
		$("#heatmap td").hottie({
			
		});
		
		$("#heatmap_index td").hottie({
			
		});
		
		$("#test2 li").hottie({
			readValue : function(e) {
				return $(e).attr("data-hist");
		  	}
		});
	});
</script>