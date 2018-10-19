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

// check is account->id is set, if not then assume user is not logged in correctly and redirect to login page
if(empty($_SESSION['account']['id'])){
	status_message('danger', 'Login Session Timeout');
	go($site['url'].'/index?c=session_timeout');
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $site['title']; ?></title>

    <link rel="icon" type="image/png" href="img/favicon.ico?v=2" sizes="32x32" />

    <link rel="apple-touch-icon" sizes="256x256" href="/img/favicon-256.png">

	<link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/img/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet"> -->
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/series-label.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>

	<!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

	<style>
		#map {
			height: 100%;
		}

		.center-navbar{
			display: block; 
			text-align: center; 
			color: white; 
			padding: 15px; 
			/* adjust based on your layout */
			margin-left: 50px; 
			margin-right: 300px;
		}
	
		img.b_to_w {
			filter: grayscale(100%);
		}
		
		@-webkit-keyframes invalid {
		  	from { background-color: #FFB6C1; }
		  	to { background-color: inherit; }
		}
		@-moz-keyframes invalid {
		  	from { background-color: #FFB6C1; }
		  	to { background-color: inherit; }
		}
		@-o-keyframes invalid {
		  	from { background-color: #FFB6C1; }
		  	to { background-color: inherit; }
		}
		@keyframes invalid {
		  	from { background-color: #FFB6C1; }
		  	to { background-color: inherit; }
		}
		.invalid {
		  	-webkit-animation: invalid 5s infinite; /* Safari 4+ */
		  	-moz-animation:    invalid 5s infinite; /* Fx 5+ */
		  	-o-animation:      invalid 5s infinite; /* Opera 12+ */
		  	animation:         invalid 5s infinite; /* IE 10+ */
		}
		
		.row_red {
			background-color: #f9d6d4;
		}

		.row_yellow {
			background-color: #f9eed4;
		}

		.row_green {
			background-color: #dcf4de;
		}

		.full-width {
			width: 100%;
		}

		td {
			padding: 0em;
		}
		
		.textshadow .blurry-text {
		   color: transparent;
		   text-shadow: 0 0 5px rgba(0,0,0,0.5);
		}
		
		.example-modal .modal {
			position: relative;
			top: auto;
			bottom: auto;
			right: auto;
			left: auto;
			display: block;
			z-index: 1;
		}

		.example-modal .modal {
			background: transparent !important;
		}
		
		.modal-header {
			background-color: #337AB7;
			padding:16px 16px;
			color:#FFF;
			border-bottom:2px dashed #337AB7;
		 }

		 .tooltip-inner {
		    max-width: 450px;
		    /* If max-width does not work, try using width instead */
		    
		}
	</style>
</head>

<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition skin-purple fixed sidebar-collapse">  
    <div class="wrapper">
        <header class="main-header">
            <a href="<?php echo $site['url']; ?>/dashboard" class="logo">
                <!-- <img src="img/logo_2.png" height="50px"> -->

                <span class="logo-mini"><?php echo $site['name_short']; ?></span>
                <span class="logo-lg"><?php echo $site['name_long']; ?></span>

            </a>

            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
                
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                    	<li class="dropdown user user-menu">
                            
                        </li>
                       
                        <li class="dropdown user user-menu">
                            
                        </li>
                        <!-- Control Sidebar Toggle Button -->
						<li>
							<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
						</li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu">
                	<?php if(empty($_GET['c']) || $_GET['c'] == '' || $_GET['c'] == 'home'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
                    	<a href="<?php echo $site['url']; ?>/dashboard">
                        	<i class="fa fa-home"></i> 
                        	<span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                    	<a href="<?php echo $site['url']; ?>/logout.php">
                        	<i class="fa fa-times"></i> 
                        	<span>Log Out</span>
                        </a>
                    </li>
                </ul>
            </section>

            <div class="user-panel">
            	
			</div>
        </aside>
        
    	<?php $site 					= get_site($_SESSION['account']['id']); ?>
    	<?php $heatmap 					= build_heatmap_array($_SESSION['account']['id']); ?>

    	<?php
    		$total_miners 				= $site['total_offline_miners'] + $site['total_online_miners'];
    		$total_online_miners		= $site['total_online_miners'];
    		// $total_power = $site['power'];
    		$total_watts				= number_format($site['power']['watts'], 2);
    		$total_kilowatts			= number_format($site['power']['kilowatts'], 2);
    		$total_amps					= number_format($site['power']['amps'], 2);

    		$avg_temp					= $site['average_temps']['average_pcb'];
    	?>

        <div class="content-wrapper">
			
            <div id="status_message"></div>
                        	
            <section class="content-header">
                <h1>Dashboard <!-- <small>Optional description</small> --></h1>
                <ol class="breadcrumb">
                    <li class="active"><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                    <!-- <li class="active">Here</li> -->
                </ol>
            </section>

			<section class="content">
				<?php if(isset($_GET['dev'])){ ?>
					<pre>
						 source<?php print_r($site); ?>
					</pre>
				<?php } ?>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="col-md-1 col-xs-6">
							<div class="box box-primary">
                                <div class="box-body">
                                    <h3>
                                    	<strong>Miners:</strong> <br>
                                    	<span id="total_miners">
                                    		<?php echo $total_online_miners.' / '.$total_miners; ?>
                                    	</span>
                                    </h3>
                                </div>
                            </div>
						</div>
						<div class="col-md-1 col-xs-6">
							<div class="box box-primary">
                                <div class="box-body">
                                    <h3>
                                    	<strong>Hashrate:</strong> <br>
                                    	<span id="total_hashrate">
                                    		<?php echo number_format($total_hashrate, 2); ?> THs
                                    	</span>
                                    </h3>
                                </div>
                            </div>
						</div>
						<div class="col-md-2 col-xs-6">
							<div class="box box-primary">
                                <div class="box-body">
                                    <h3>
                                    	<strong>Avg Power:</strong> <br>
                                    	<span id="total_kilowatts">
                                    		<?php echo $total_kilowatts; ?> kWs
                                    	</span>
                                    	 / 
                                    	<span id="total_amps">
                                    		<?php echo $total_amps; ?> AMPs
                                    	</span>
                                    </h3>
                                </div>
                            </div>
						</div>
						<div class="col-md-2 col-xs-6">
							<div class="box box-primary">
                                <div class="box-body">
                                    <h3>
                                    	<strong>Avg Temp:</strong> <br>
                                    	<span id="total_amps">
                                    		<?php echo f_to_c($avg_temp).'°C / '.$avg_temp.'°F'; ?>
                                    	</span>
                                    </h3>
                                </div>
                            </div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12 col-xs-12">
						<div class="col-md-12 col-xs-12">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Heatmap</h3>
								</div>
								<div class="box-body text-center">
									<style>
										.grayout {
										/* display: none; */
										background-color: gray;
										opacity: .3;
									}
									</style>

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
																				<li style="width: 100%; list-style-type: none; display:inline-block;" data-hist="'.$position['miner_temp'].'">
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
											<td align="center" valign="middle" style="font-weight: bolder">
												<ul id="test2" style="display: table; width: 100%;">
													<li style="width: 100%; list-style-type: none; display:inline-block;" data-hist="0">0</li>
												</ul>
											</td>
											<td align="center" valign="middle" style="font-weight: bolder">
												<ul id="test2" style="display: table; width: 100%;">
													<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="10">10</li>
												</ul>
											</td>
											<td align="center" valign="middle" style="font-weight: bolder">
												<ul id="test2" style="display: table; width: 100%;">
													<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="20">20</li>
												</ul>
											</td>
											<td align="center" valign="middle" style="font-weight: bolder">
												<ul id="test2" style="display: table; width: 100%;">
													<li style="width: 100%; list-style-type: none; display: table-cell;" data-hist="30">30</li>
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
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
            </section>
        </div>

        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <!-- Anything you want -->
            </div>
            <strong>Copyright &copy; <?php echo date("Y", time()); ?> <a href="<?php echo $config['url']; ?>"><?php echo $config['title']; ?></a>.</strong> All rights reserved.
        </footer>

        <!-- Create the tabs -->
        <aside class="control-sidebar control-sidebar-dark">
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        	<li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-globe"></i></a></li>
        </ul>
        
        <!-- Tab panes -->
		<div class="tab-content">
		    <div class="tab-pane active" id="control-sidebar-settings-tab">
		        
		        <form method="post">
		            <h3 class="control-sidebar-heading">Settings</h3>
		            <div class="form-group">
		                <label class="control-sidebar-subheading">
		                    Report panel usage
		                    <input type="checkbox" class="pull-right" checked>
		                </label>
		                <p>
		                    Some information about this general settings option
		                </p>
		            </div>

		            <div class="form-group">
		                <label class="control-sidebar-subheading">
		                    Allow mail redirect
		                    <input type="checkbox" class="pull-right" checked>
		                </label>
		                <p>
		                    Other sets of options are available
		                </p>
		            </div>

		            <div class="form-group">
		                <label class="control-sidebar-subheading">
		                    Expose author name in posts
		                    <input type="checkbox" class="pull-right" checked>
		                </label>
		                <p>
		                    Allow the user to show his name in blog posts
		                </p>
		            </div>

		            <h3 class="control-sidebar-heading">Chat Settings</h3>

		            <div class="form-group">
		                <label class="control-sidebar-subheading">
		                    Show me as online
		                    <input type="checkbox" class="pull-right" checked>
		                </label>
		            </div>

		            <div class="form-group">
		                <label class="control-sidebar-subheading">
		                    Turn off notifications
		                    <input type="checkbox" class="pull-right">
		                </label>
		            </div>

		            <div class="form-group">
		                <label class="control-sidebar-subheading">
		                    Delete chat history
		                    <a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
		                </label>
		            </div>
		        </form>
		    	
		    	<h3 class="control-sidebar-heading">Function Coming Soon</h3>
		    	<div class="col-md-12 col-xs-12">
                	<strong>Miners:</strong> <br>
                	<span id="total_miners"><?php echo $total_online_miners.' / '.$total_miners; ?></span>
				</div>
				<br>

				<div class="col-md-1 col-xs-6">
                	<strong>Hashrate:</strong> <br>
                	<span id="total_hashrate"><?php echo number_format($total_hashrate, 2); ?> THs</span>
				</div>
				<br>

				<div class="col-md-2 col-xs-6">
					<strong>Avg Power:</strong> <br>
                	<span id="total_kilowatts"><?php echo $total_kilowatts; ?> kWs</span> / <span id="total_amps"><?php echo $total_amps; ?> AMPs</span>
				</div>
				<br>

				<div class="col-md-2 col-xs-6">
					<strong>Avg Temp:</strong> <br>
                	<span id="total_amps"><?php echo f_to_c($avg_temp).'°C / '.$avg_temp.'°F'; ?></span>
				</div>
		    </div>
		</div>
      </aside>

      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div>

    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
   
    <?php if(!empty($_SESSION['alert']['status'])){ ?>
    	<script>
			document.getElementById('status_message').innerHTML = '<div class="callout callout-<?php echo $_SESSION['alert']['status']; ?> lead"><p><?php echo $_SESSION['alert']['message']; ?></p></div>';
			setTimeout(function() {
				$('#status_message').fadeOut('fast');
			}, 5000);
        </script>
        <?php unset($_SESSION['alert']); ?>
    <?php } ?>
    
    <script>
		function set_status_message(status, message){
			$.ajax({
				cache: false,
				type: "GET",
				url: "actions.php?a=set_status_message&status=" + status + "&message=" + message,
				success: function(data) {
					
				}
			});	
		}

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
	
		$(function () {
			$('#dashboard_sites').DataTable({
		  		"paging": false,
		  		"lengthChange": false,
		  		"searching": false,
		  		"ordering": false,
		  		"info": false,
		  		"autoWidth": false,
				"iDisplayLength": 20,
			});
	  	});

	  	$(function () {
			$('#sites').DataTable({
		  		"paging": true,
		  		"lengthChange": false,
		  		"searching": true,
		  		"ordering": false,
		  		"info": true,
		  		"autoWidth": false,
				"iDisplayLength": 100,
			});
	  	});
		
		$(function () {
			$('#asic_miners_table').DataTable({
				"order": [[ 2, "asc" ]],
		  		"paging": true,
		  		"lengthChange": false,
		  		"searching": true,
		  		"ordering": false,
		  		"info": true,
		  		"autoWidth": false,
				"iDisplayLength": 254,
				search: {
				   search: '<?php echo $_GET['search']; ?>'
				}
			});
	  	});

	  	$(function () {
			$('#gpu_miners_table').DataTable({
				"order": [[ 2, "asc" ]],
		  		"paging": true,
		  		"lengthChange": false,
		  		"searching": true,
		  		"ordering": false,
		  		"info": true,
		  		"autoWidth": false,
				"iDisplayLength": 254,
				search: {
				   search: '<?php echo $_GET['search']; ?>'
				}
			});
	  	});
		
		$(function () {
			$('#pools').DataTable({
				"order": [[ 1, "asc" ]],
		  		"paging": true,
		  		"lengthChange": false,
		  		"searching": true,
		  		"ordering": false,
		  		"info": true,
		  		"autoWidth": false,
				"iDisplayLength": 254,
				search: {
				   search: '<?php echo $_GET['search']; ?>'
				}
			});
	  	});
		
		$(function () {
			$('#pool_profiles').DataTable({
				"order": [[ 1, "asc" ]],
		  		"paging": true,
		  		"lengthChange": false,
		  		"searching": true,
		  		"ordering": false,
		  		"info": true,
		  		"autoWidth": false,
				"iDisplayLength": 254,
				search: {
				   search: '<?php echo $_GET['search']; ?>'
				}
			});
	  	});

	  	$(function () {
			$('#invoices').DataTable({
				"order": [[ 1, "desc" ]],
		  		"paging": true,
		  		"lengthChange": false,
		  		"searching": true,
		  		"ordering": false,
		  		"info": true,
		  		"autoWidth": false,
				"iDisplayLength": 254,
				search: {
				   search: '<?php echo $_GET['search']; ?>'
				}
			});
	  	});

	  	$(function () {
			$('#hardware_calc').DataTable({
				"order": [[ 0, "asc" ]],
		  		"paging": true,
		  		"lengthChange": false,
		  		"searching": true,
		  		"ordering": true,
		  		"info": true,
		  		"autoWidth": false,
				"iDisplayLength": 254,
				search: {
				   search: '<?php echo $_GET['search']; ?>'
				}
			});
	  	});
	</script>
	
	<script src="dist/js/jquery.hottie.js"></script>

	<script>
		function tts(text){
	    	var msg = new SpeechSynthesisUtterance(text);
	    	window.speechSynthesis.speak(msg);
	    }
	
	    <?php if($account_details['accepted_terms'] == 'no'){ ?>
		    $(window).on('load',function(){
		        $('#modal-terms').modal({backdrop: 'static', keyboard: false});
		    });
		<?php } ?>
	</script>

</body>
</html>