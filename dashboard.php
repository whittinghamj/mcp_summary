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
                    
                    <?php if($account_details['type'] == 'admin'){ ?>
						<?php if($_GET['c'] == 'sites'){ ?>
							<li class="active">
						<?php }else{ ?>
							<li>
						<?php } ?>
							<a href="<?php echo $site['url']; ?>/dashboard?c=sites">
								<i class="fa fa-university"></i> 
								<span>Sites</span>
							</a>
						</li>

						<?php if($_GET['c'] == 'pools' || $_GET['c'] == 'pool'){ ?>
	                    	<li class="active">
	                    <?php }else{ ?>
	                    	<li>
	                    <?php } ?>
	                    	<a href="<?php echo $site['url']; ?>/dashboard?c=pools">
	                        	<i class="fa fa-server"></i> 
	                        	<span>Pools</span>
	                        </a>
	                    </li>

	                    <?php if($_GET['c'] == 'customers' || $_GET['c'] == 'customer'){ ?>
	                    	<li class="active">
	                    <?php }else{ ?>
	                    	<li>
	                    <?php } ?>
	                    	<a href="<?php echo $site['url']; ?>/dashboard?c=customers">
	                        	<i class="fa fa-users"></i> 
	                        	<span>Customers</span>
	                        </a>
	                    </li>
					<?php } ?>

					<?php if($account_details['type'] == 'customer'){ ?>
						<?php if($_GET['c'] == 'miners'){ ?>
							<li class="active">
						<?php }else{ ?>
							<li>
						<?php } ?>
							<a href="<?php echo $site['url']; ?>/dashboard?c=customer_miners">
								<i class="fa fa-boxes"></i> 
								<span>My Miners</span>
							</a>
						</li>
					<?php } ?>
                    
                   	<!--
                   	<?php if($_GET['c'] == 'miners' || $_GET['c'] == 'miner'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
                    	<a href="<?php echo $site['url']; ?>/dashboard?c=sites">
                        	<i class="fa fa-desktop"></i> 
                        	<span>Miners</span>
                        </a>
                    </li>
                    -->

                    <!--
					<?php if($_GET['c'] == 'invoices' || $_GET['c'] == 'invoice' || $_GET['c'] == 'orders' || $_GET['c'] == 'order'){ ?>
						<li class="treeview active">
					<?php }else{ ?>
                    	<li class="treeview">
                    <?php } ?>
                        <a href="#"><i class="fa fa-link"></i> <span>Billing</span> <i class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <?php if($_GET['c'] == 'invoices' || $_GET['c'] == 'invoice'){ ?>
                            	<li class="active">
                            <?php }else{ ?>
                            	<li>
                            <?php } ?>
                            	<a href="<?php echo $site['url']; ?>/dashboard?c=invoices">Invoices</a>
                            </li>
                            <?php if($_GET['c'] == 'orders' || $_GET['c'] == 'order'){ ?>
                            	<li class="active">
                            <?php }else{ ?>
                            	<li>
                            <?php } ?>
                            	<a href="<?php echo $site['url']; ?>/dashboard?c=orders">Orders</a>
                            </li>
                        </ul>
                    </li>
                	-->

                	<?php if($_GET['c'] == 'profit_calc'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
						<a href="<?php echo $site['url']; ?>/dashboard?c=profit_calc">
                        	<i class="fa fa-calculator"></i> 
                        	<span>Profit Calculators</span>
                        </a>
                    </li>

                    <?php if($_GET['c'] == 'hardware_calc'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
						<a href="<?php echo $site['url']; ?>/dashboard?c=hardware_calc">
                        	<i class="fa fa-calculator"></i> 
                        	<span>Mining Hardware Profits</span>
                        </a>
                    </li>

                	<?php if($_GET['c'] == 'buy_miners'){ ?>
						<li class="active">
					<?php }else{ ?>
						<li>
					<?php } ?>
						<a href="<?php echo $site['url']; ?>/dashboard?c=store">
							<i class="fa fa-shopping-basket"></i> 
							<span>Store</span>
						</a>
					</li>
                    
                   	<?php if($_GET['c'] == 'my_account'){ ?>
                    	<li class="active">
                    <?php }else{ ?>
                    	<li>
                    <?php } ?>
                    	<a href="<?php echo $site['url']; ?>/dashboard?c=my_account">
                        	<i class="fa fa-user"></i> 
                        	<span>My Account</span>
                        </a>
                    </li>

                    <script>
                    	$(document).ready(function(){
						    $("#discord").click(function(){
						    	$('#modal-discord-chat').modal('toggle');
						    	evt.preventDefault(); 
        						return false;
						    });
						});
                    </script>

                    <li>
                    	<!-- <button id="discord" class="btn btn-default">Support</button> -->
                    	<a id="discord" href="#">
                        	<i class="fa fa-desktop"></i> 
                        	<span>Support</span>
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
		
        <?php
			$c = $_GET['c'];
			switch ($c){
				// test
				case "test":
					test();
					break;
					
				// sites
				case "sites":
					sites();
					break;
					
				case "site":
					site_test();
					break;

				case "site_test":
					site_test();
					break;
				
				// miners
				case "miners":
					miners();
					break;
					
				case "miner":
					miner();
					break;
					
				// pools
				case "pools":
					pools();
					break;
					
				case "pool":
					pool();
					break;
					
				case "pool_profile":
					pool_profile();
					break;
					
				// my account
				case "my_account":
					my_account();
					break;

				// customers
				case "customers":
					customers();
					break;

				// customer > miners
				case "customer_miners":
					customer_miners();
					break;

				// profit calcs
				case "profit_calc":
					profit_calc();
					break;

				case "hardware_calc":
					hardware_calc();
					break;

				// invoices
				case "invoices":
					invoices();
					break;

				case "invoice":
					invoice();
					break;

				// other
				case "store":
					store();
					break;
					
				// home
				default:
					home();
					break;
			}
		?>
        
        <?php function home(){ ?>
        	<?php global $account_details, $site; ?>

        	<?php $sites = get_sites(); ?>

        	<div id="step_add_site" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Next Step</h4>
						</div>
						<div class="modal-body">
						<p>You need to add a site to get things started. Please keep in mind that each site needs it's own controller installed and configured for MCP to work.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>

            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Dashboard <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li class="active"><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <!-- <li class="active">Here</li> -->
                    </ol>
                </section>
    
				<?php if($account_details['type'] == 'admin'){ ?>
					<section class="content">
	                	<div class="row">
							<div class="col-md-12">
							  	<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
								  		<li class="active"><a href="#tab_1" data-toggle="tab">Sites</a></li>
								  		<li><a href="#tab_2" data-toggle="tab">Add Site</a></li>
									</ul>
									<div class="tab-content">
								  		<div class="tab-pane active" id="tab_1">
											<table id="sites" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>Site</th>
														<th>Controller</th>
														<th>Miners</th>
														<th>Power</th>
														<th>Financial</th>
														<th width="60px"></th>
													</tr>
												</thead>
												<tbody>
													<?php show_sites(); ?>
												</tbody>
											</table>
								  		</div>
								  		<div class="tab-pane" id="tab_2">
											<form action="actions.php?a=site_add" method="post" class="form-horizontal">
												<div class="row">
													<div class="form-group col-lg-12">
														<label for="name" class="col-lg-2 control-label">Name</label>
														<div class="col-lg-10">
															<input type="text" name="name" id="name" class="form-control" placeholder="Site X" required>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-lg-12">
														<label for="location" class="col-lg-2 control-label">Full Address</label>
														<div class="col-lg-10">
															<input type="text" name="location" id="location" class="form-control" placeholder="70 Monty Drive, Savannah, TN, 38372, United States">
														</div>
													</div>
												</div>
												
												<div class="row">
													<div class="form-group col-lg-12">
														<label for="city" class="col-lg-2 control-label">Weather City</label>
														<div class="col-lg-10">
															<input type="text" name="city" id="city" class="form-control" placeholder="Savannah" required>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-lg-12">
														<label for="country" class="col-lg-2 control-label">Weather Country</label>
														<div class="col-lg-10">
															<select name="country" id="country" class="form-control" placeholder="United States" required>
																<option value="" <?php if($site['country']==''){echo'selected';}?>>Select a Country</option>
																<option value="AF" <?php if($site['country']=='AF'){echo'selected';}?>>Afghanistan</option>
																<option value="AL" <?php if($site['country']=='AL'){echo'selected';}?>>Albania</option>
																<option value="DZ" <?php if($site['country']=='DZ'){echo'selected';}?>>Algeria</option>
																<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>American Samoa</option>
																<option value="AD" <?php if($site['country']=='AD'){echo'selected';}?>>Andorra</option>
																<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Angola</option>
																<option value="AI" <?php if($site['country']=='AI'){echo'selected';}?>>Anguilla</option>
																<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Antigua &amp; Barbuda</option>
																<option value="AR" <?php if($site['country']=='AR'){echo'selected';}?>>Argentina</option>
																<option value="AA" <?php if($site['country']=='AA'){echo'selected';}?>>Armenia</option>
																<option value="AW" <?php if($site['country']=='AW'){echo'selected';}?>>Aruba</option>
																<option value="AU" <?php if($site['country']=='AU'){echo'selected';}?>>Australia</option>
																<option value="AT" <?php if($site['country']=='AT'){echo'selected';}?>>Austria</option>
																<option value="AZ" <?php if($site['country']=='AZ'){echo'selected';}?>>Azerbaijan</option>
																<option value="BS" <?php if($site['country']=='BS'){echo'selected';}?>>Bahamas</option>
																<option value="BH" <?php if($site['country']=='BH'){echo'selected';}?>>Bahrain</option>
																<option value="BD" <?php if($site['country']=='BD'){echo'selected';}?>>Bangladesh</option>
																<option value="BB" <?php if($site['country']=='BB'){echo'selected';}?>>Barbados</option>
																<option value="BY" <?php if($site['country']=='BY'){echo'selected';}?>>Belarus</option>
																<option value="BE" <?php if($site['country']=='BE'){echo'selected';}?>>Belgium</option>
																<option value="BZ" <?php if($site['country']=='BZ'){echo'selected';}?>>Belize</option>
																<option value="BJ" <?php if($site['country']=='BJ'){echo'selected';}?>>Benin</option>
																<option value="BM" <?php if($site['country']=='BM'){echo'selected';}?>>Bermuda</option>
																<option value="BT" <?php if($site['country']=='BT'){echo'selected';}?>>Bhutan</option>
																<option value="BO" <?php if($site['country']=='BO'){echo'selected';}?>>Bolivia</option>
																<option value="BL" <?php if($site['country']=='BL'){echo'selected';}?>>Bonaire</option>
																<option value="BA" <?php if($site['country']=='BA'){echo'selected';}?>>Bosnia &amp; Herzegovina</option>
																<option value="BW" <?php if($site['country']=='BW'){echo'selected';}?>>Botswana</option>
																<option value="BR" <?php if($site['country']=='BR'){echo'selected';}?>>Brazil</option>
																<option value="BC" <?php if($site['country']=='BC'){echo'selected';}?>>British Indian Ocean Ter</option>
																<option value="BN" <?php if($site['country']=='BN'){echo'selected';}?>>Brunei</option>
																<option value="BG" <?php if($site['country']=='BG'){echo'selected';}?>>Bulgaria</option>
																<option value="BF" <?php if($site['country']=='BF'){echo'selected';}?>>Burkina Faso</option>
																<option value="BI" <?php if($site['country']=='BI'){echo'selected';}?>>Burundi</option>
																<option value="KH" <?php if($site['country']=='KH'){echo'selected';}?>>Cambodia</option>
																<option value="CM" <?php if($site['country']=='CM'){echo'selected';}?>>Cameroon</option>
																<option value="CA" <?php if($site['country']=='CA'){echo'selected';}?>>Canada</option>
																<option value="IC" <?php if($site['country']=='IC'){echo'selected';}?>>Canary Islands</option>
																<option value="CV" <?php if($site['country']=='CV'){echo'selected';}?>>Cape Verde</option>
																<option value="KY" <?php if($site['country']=='KY'){echo'selected';}?>>Cayman Islands</option>
																<option value="CF" <?php if($site['country']=='CF'){echo'selected';}?>>Central African Republic</option>
																<option value="TD" <?php if($site['country']=='TD'){echo'selected';}?>>Chad</option>
																<option value="CD" <?php if($site['country']=='CD'){echo'selected';}?>>Channel Islands</option>
																<option value="CL" <?php if($site['country']=='CL'){echo'selected';}?>>Chile</option>
																<option value="CN" <?php if($site['country']=='CN'){echo'selected';}?>>China</option>
																<option value="CI" <?php if($site['country']=='CI'){echo'selected';}?>>Christmas Island</option>
																<option value="CS" <?php if($site['country']=='CS'){echo'selected';}?>>Cocos Island</option>
																<option value="CO" <?php if($site['country']=='CO'){echo'selected';}?>>Colombia</option>
																<option value="CC" <?php if($site['country']=='CC'){echo'selected';}?>>Comoros</option>
																<option value="CG" <?php if($site['country']=='CG'){echo'selected';}?>>Congo</option>
																<option value="CK" <?php if($site['country']=='CK'){echo'selected';}?>>Cook Islands</option>
																<option value="CR" <?php if($site['country']=='CR'){echo'selected';}?>>Costa Rica</option>
																<option value="CT" <?php if($site['country']=='CT'){echo'selected';}?>>Cote D'Ivoire</option>
																<option value="HR" <?php if($site['country']=='HR'){echo'selected';}?>>Croatia</option>
																<option value="CU" <?php if($site['country']=='CU'){echo'selected';}?>>Cuba</option>
																<option value="CB" <?php if($site['country']=='CB'){echo'selected';}?>>Curacao</option>
																<option value="CY" <?php if($site['country']=='CY'){echo'selected';}?>>Cyprus</option>
																<option value="CZ" <?php if($site['country']=='CZ'){echo'selected';}?>>Czech Republic</option>
																<option value="DK" <?php if($site['country']=='DK'){echo'selected';}?>>Denmark</option>
																<option value="DJ" <?php if($site['country']=='DJ'){echo'selected';}?>>Djibouti</option>
																<option value="DM" <?php if($site['country']=='DM'){echo'selected';}?>>Dominica</option>
																<option value="DO" <?php if($site['country']=='DO'){echo'selected';}?>>Dominican Republic</option>
																<option value="TM" <?php if($site['country']=='TM'){echo'selected';}?>>East Timor</option>
																<option value="EC" <?php if($site['country']=='EC'){echo'selected';}?>>Ecuador</option>
																<option value="EG" <?php if($site['country']=='EG'){echo'selected';}?>>Egypt</option>
																<option value="SV" <?php if($site['country']=='SV'){echo'selected';}?>>El Salvador</option>
																<option value="GQ" <?php if($site['country']=='GQ'){echo'selected';}?>>Equatorial Guinea</option>
																<option value="ER" <?php if($site['country']=='ER'){echo'selected';}?>>Eritrea</option>
																<option value="EE" <?php if($site['country']=='EE'){echo'selected';}?>>Estonia</option>
																<option value="ET" <?php if($site['country']=='ET'){echo'selected';}?>>Ethiopia</option>
																<option value="FA" <?php if($site['country']=='FA'){echo'selected';}?>>Falkland Islands</option>
																<option value="FO" <?php if($site['country']=='DO'){echo'selected';}?>>Faroe Islands</option>
																<option value="FJ" <?php if($site['country']=='FJ'){echo'selected';}?>>Fiji</option>
																<option value="FI" <?php if($site['country']=='FI'){echo'selected';}?>>Finland</option>
																<option value="FR" <?php if($site['country']=='FR'){echo'selected';}?>>France</option>
																<option value="GF" <?php if($site['country']=='GF'){echo'selected';}?>>French Guiana</option>
																<option value="PF" <?php if($site['country']=='PF'){echo'selected';}?>>French Polynesia</option>
																<option value="FS" <?php if($site['country']=='FS'){echo'selected';}?>>French Southern Ter</option>
																<option value="GA" <?php if($site['country']=='GA'){echo'selected';}?>>Gabon</option>
																<option value="GM" <?php if($site['country']=='GM'){echo'selected';}?>>Gambia</option>
																<option value="GE" <?php if($site['country']=='GE'){echo'selected';}?>>Georgia</option>
																<option value="DE" <?php if($site['country']=='DE'){echo'selected';}?>>Germany</option>
																<option value="GH" <?php if($site['country']=='GH'){echo'selected';}?>>Ghana</option>
																<option value="GI" <?php if($site['country']=='GI'){echo'selected';}?>>Gibraltar</option>
																<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>Great Britain</option>
																<option value="GR" <?php if($site['country']=='GR'){echo'selected';}?>>Greece</option>
																<option value="GL" <?php if($site['country']=='GL'){echo'selected';}?>>Greenland</option>
																<option value="GD" <?php if($site['country']=='GD'){echo'selected';}?>>Grenada</option>
																<option value="GP" <?php if($site['country']=='GP'){echo'selected';}?>>Guadeloupe</option>
																<option value="GU" <?php if($site['country']=='GU'){echo'selected';}?>>Guam</option>
																<option value="GT" <?php if($site['country']=='GT'){echo'selected';}?>>Guatemala</option>
																<option value="GN" <?php if($site['country']=='GN'){echo'selected';}?>>Guinea</option>
																<option value="GY" <?php if($site['country']=='GY'){echo'selected';}?>>Guyana</option>
																<option value="HT" <?php if($site['country']=='HT'){echo'selected';}?>>Haiti</option>
																<option value="HW" <?php if($site['country']=='HW'){echo'selected';}?>>Hawaii</option>
																<option value="HN" <?php if($site['country']=='HN'){echo'selected';}?>>Honduras</option>
																<option value="HK" <?php if($site['country']=='HK'){echo'selected';}?>>Hong Kong</option>
																<option value="HU" <?php if($site['country']=='HU'){echo'selected';}?>>Hungary</option>
																<option value="IS" <?php if($site['country']=='IS'){echo'selected';}?>>Iceland</option>
																<option value="IN" <?php if($site['country']=='IN'){echo'selected';}?>>India</option>
																<option value="ID" <?php if($site['country']=='ID'){echo'selected';}?>>Indonesia</option>
																<option value="IA" <?php if($site['country']=='IA'){echo'selected';}?>>Iran</option>
																<option value="IQ" <?php if($site['country']=='IQ'){echo'selected';}?>>Iraq</option>
																<option value="IR" <?php if($site['country']=='IR'){echo'selected';}?>>Ireland</option>
																<option value="IM" <?php if($site['country']=='IM'){echo'selected';}?>>Isle of Man</option>
																<option value="IL" <?php if($site['country']=='IL'){echo'selected';}?>>Israel</option>
																<option value="IT" <?php if($site['country']=='IT'){echo'selected';}?>>Italy</option>
																<option value="JM" <?php if($site['country']=='JM'){echo'selected';}?>>Jamaica</option>
																<option value="JP" <?php if($site['country']=='JP'){echo'selected';}?>>Japan</option>
																<option value="JO" <?php if($site['country']=='JO'){echo'selected';}?>>Jordan</option>
																<option value="KZ" <?php if($site['country']=='KZ'){echo'selected';}?>>Kazakhstan</option>
																<option value="KE" <?php if($site['country']=='KE'){echo'selected';}?>>Kenya</option>
																<option value="KI" <?php if($site['country']=='KI'){echo'selected';}?>>Kiribati</option>
																<option value="NK" <?php if($site['country']=='NK'){echo'selected';}?>>Korea North</option>
																<option value="KS" <?php if($site['country']=='KS'){echo'selected';}?>>Korea South</option>
																<option value="KW" <?php if($site['country']=='KW'){echo'selected';}?>>Kuwait</option>
																<option value="KG" <?php if($site['country']=='KG'){echo'selected';}?>>Kyrgyzstan</option>
																<option value="LA" <?php if($site['country']=='LA'){echo'selected';}?>>Laos</option>
																<option value="LV" <?php if($site['country']=='LV'){echo'selected';}?>>Latvia</option>
																<option value="LB" <?php if($site['country']=='LB'){echo'selected';}?>>Lebanon</option>
																<option value="LS" <?php if($site['country']=='LS'){echo'selected';}?>>Lesotho</option>
																<option value="LR" <?php if($site['country']=='LR'){echo'selected';}?>>Liberia</option>
																<option value="LY" <?php if($site['country']=='LY'){echo'selected';}?>>Libya</option>
																<option value="LI" <?php if($site['country']=='LI'){echo'selected';}?>>Liechtenstein</option>
																<option value="LT" <?php if($site['country']=='LT'){echo'selected';}?>>Lithuania</option>
																<option value="LU" <?php if($site['country']=='LU'){echo'selected';}?>>Luxembourg</option>
																<option value="MO" <?php if($site['country']=='MO'){echo'selected';}?>>Macau</option>
																<option value="MK" <?php if($site['country']=='MK'){echo'selected';}?>>Macedonia</option>
																<option value="MG" <?php if($site['country']=='MG'){echo'selected';}?>>Madagascar</option>
																<option value="MY" <?php if($site['country']=='MY'){echo'selected';}?>>Malaysia</option>
																<option value="MW" <?php if($site['country']=='MW'){echo'selected';}?>>Malawi</option>
																<option value="MV" <?php if($site['country']=='MV'){echo'selected';}?>>Maldives</option>
																<option value="ML" <?php if($site['country']=='ML'){echo'selected';}?>>Mali</option>
																<option value="MT" <?php if($site['country']=='MT'){echo'selected';}?>>Malta</option>
																<option value="MH" <?php if($site['country']=='MH'){echo'selected';}?>>Marshall Islands</option>
																<option value="MQ" <?php if($site['country']=='MQ'){echo'selected';}?>>Martinique</option>
																<option value="MR" <?php if($site['country']=='MR'){echo'selected';}?>>Mauritania</option>
																<option value="MU" <?php if($site['country']=='MU'){echo'selected';}?>>Mauritius</option>
																<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Mayotte</option>
																<option value="MX" <?php if($site['country']=='MX'){echo'selected';}?>>Mexico</option>
																<option value="MI" <?php if($site['country']=='MI'){echo'selected';}?>>Midway Islands</option>
																<option value="MD" <?php if($site['country']=='MD'){echo'selected';}?>>Moldova</option>
																<option value="MC" <?php if($site['country']=='MC'){echo'selected';}?>>Monaco</option>
																<option value="MN" <?php if($site['country']=='MN'){echo'selected';}?>>Mongolia</option>
																<option value="MS" <?php if($site['country']=='MS'){echo'selected';}?>>Montserrat</option>
																<option value="MA" <?php if($site['country']=='MA'){echo'selected';}?>>Morocco</option>
																<option value="MZ" <?php if($site['country']=='MZ'){echo'selected';}?>>Mozambique</option>
																<option value="MM" <?php if($site['country']=='MM'){echo'selected';}?>>Myanmar</option>
																<option value="NA" <?php if($site['country']=='NA'){echo'selected';}?>>Nambia</option>
																<option value="NU" <?php if($site['country']=='NU'){echo'selected';}?>>Nauru</option>
																<option value="NP" <?php if($site['country']=='NP'){echo'selected';}?>>Nepal</option>
																<option value="AN" <?php if($site['country']=='AN'){echo'selected';}?>>Netherland Antilles</option>
																<option value="NL" <?php if($site['country']=='NL'){echo'selected';}?>>Netherlands (Holland, Europe)</option>
																<option value="NV" <?php if($site['country']=='NV'){echo'selected';}?>>Nevis</option>
																<option value="NC" <?php if($site['country']=='NC'){echo'selected';}?>>New Caledonia</option>
																<option value="NZ" <?php if($site['country']=='NZ'){echo'selected';}?>>New Zealand</option>
																<option value="NI" <?php if($site['country']=='NI'){echo'selected';}?>>Nicaragua</option>
																<option value="NE" <?php if($site['country']=='NE'){echo'selected';}?>>Niger</option>
																<option value="NG" <?php if($site['country']=='NG'){echo'selected';}?>>Nigeria</option>
																<option value="NW" <?php if($site['country']=='NW'){echo'selected';}?>>Niue</option>
																<option value="NF" <?php if($site['country']=='NF'){echo'selected';}?>>Norfolk Island</option>
																<option value="NO" <?php if($site['country']=='NO'){echo'selected';}?>>Norway</option>
																<option value="OM" <?php if($site['country']=='OM'){echo'selected';}?>>Oman</option>
																<option value="PK" <?php if($site['country']=='PK'){echo'selected';}?>>Pakistan</option>
																<option value="PW" <?php if($site['country']=='PW'){echo'selected';}?>>Palau Island</option>
																<option value="PS" <?php if($site['country']=='PS'){echo'selected';}?>>Palestine</option>
																<option value="PA" <?php if($site['country']=='PA'){echo'selected';}?>>Panama</option>
																<option value="PG" <?php if($site['country']=='PG'){echo'selected';}?>>Papua New Guinea</option>
																<option value="PY" <?php if($site['country']=='PY'){echo'selected';}?>>Paraguay</option>
																<option value="PE" <?php if($site['country']=='PE'){echo'selected';}?>>Peru</option>
																<option value="PH" <?php if($site['country']=='PH'){echo'selected';}?>>Philippines</option>
																<option value="PO" <?php if($site['country']=='PO'){echo'selected';}?>>Pitcairn Island</option>
																<option value="PL" <?php if($site['country']=='PL'){echo'selected';}?>>Poland</option>
																<option value="PT" <?php if($site['country']=='PT'){echo'selected';}?>>Portugal</option>
																<option value="PR" <?php if($site['country']=='PR'){echo'selected';}?>>Puerto Rico</option>
																<option value="QA" <?php if($site['country']=='WA'){echo'selected';}?>>Qatar</option>
																<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Republic of Montenegro</option>
																<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Republic of Serbia</option>
																<option value="RE" <?php if($site['country']=='RE'){echo'selected';}?>>Reunion</option>
																<option value="RO" <?php if($site['country']=='RO'){echo'selected';}?>>Romania</option>
																<option value="RU" <?php if($site['country']=='RU'){echo'selected';}?>>Russia</option>
																<option value="RW" <?php if($site['country']=='RW'){echo'selected';}?>>Rwanda</option>
																<option value="NT" <?php if($site['country']=='NT'){echo'selected';}?>>St Barthelemy</option>
																<option value="EU" <?php if($site['country']=='EU'){echo'selected';}?>>St Eustatius</option>
																<option value="HE" <?php if($site['country']=='HE'){echo'selected';}?>>St Helena</option>
																<option value="KN" <?php if($site['country']=='KN'){echo'selected';}?>>St Kitts-Nevis</option>
																<option value="LC" <?php if($site['country']=='LC'){echo'selected';}?>>St Lucia</option>
																<option value="MB" <?php if($site['country']=='MB'){echo'selected';}?>>St Maarten</option>
																<option value="PM" <?php if($site['country']=='PM'){echo'selected';}?>>St Pierre &amp; Miquelon</option>
																<option value="VC" <?php if($site['country']=='VC'){echo'selected';}?>>St Vincent &amp; Grenadines</option>
																<option value="SP" <?php if($site['country']=='SP'){echo'selected';}?>>Saipan</option>
																<option value="SO" <?php if($site['country']=='SO'){echo'selected';}?>>Samoa</option>
																<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>Samoa American</option>
																<option value="SM" <?php if($site['country']=='SM'){echo'selected';}?>>San Marino</option>
																<option value="ST" <?php if($site['country']=='ST'){echo'selected';}?>>Sao Tome &amp; Principe</option>
																<option value="SA" <?php if($site['country']=='SA'){echo'selected';}?>>Saudi Arabia</option>
																<option value="SN" <?php if($site['country']=='SN'){echo'selected';}?>>Senegal</option>
																<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Serbia</option>
																<option value="SC" <?php if($site['country']=='SC'){echo'selected';}?>>Seychelles</option>
																<option value="SL" <?php if($site['country']=='SL'){echo'selected';}?>>Sierra Leone</option>
																<option value="SG" <?php if($site['country']=='SG'){echo'selected';}?>>Singapore</option>
																<option value="SK" <?php if($site['country']=='SK'){echo'selected';}?>>Slovakia</option>
																<option value="SI" <?php if($site['country']=='SI'){echo'selected';}?>>Slovenia</option>
																<option value="SB" <?php if($site['country']=='SB'){echo'selected';}?>>Solomon Islands</option>
																<option value="OI" <?php if($site['country']=='OI'){echo'selected';}?>>Somalia</option>
																<option value="ZA" <?php if($site['country']=='ZA'){echo'selected';}?>>South Africa</option>
																<option value="ES" <?php if($site['country']=='ES'){echo'selected';}?>>Spain</option>
																<option value="LK" <?php if($site['country']=='LK'){echo'selected';}?>>Sri Lanka</option>
																<option value="SD" <?php if($site['country']=='SD'){echo'selected';}?>>Sudan</option>
																<option value="SR" <?php if($site['country']=='SR'){echo'selected';}?>>Suriname</option>
																<option value="SZ" <?php if($site['country']=='SZ'){echo'selected';}?>>Swaziland</option>
																<option value="SE" <?php if($site['country']=='SE'){echo'selected';}?>>Sweden</option>
																<option value="CH" <?php if($site['country']=='CH'){echo'selected';}?>>Switzerland</option>
																<option value="SY" <?php if($site['country']=='SY'){echo'selected';}?>>Syria</option>
																<option value="TA" <?php if($site['country']=='TA'){echo'selected';}?>>Tahiti</option>
																<option value="TW" <?php if($site['country']=='TW'){echo'selected';}?>>Taiwan</option>
																<option value="TJ" <?php if($site['country']=='TJ'){echo'selected';}?>>Tajikistan</option>
																<option value="TZ" <?php if($site['country']=='TZ'){echo'selected';}?>>Tanzania</option>
																<option value="TH" <?php if($site['country']=='TH'){echo'selected';}?>>Thailand</option>
																<option value="TG" <?php if($site['country']=='TG'){echo'selected';}?>>Togo</option>
																<option value="TK" <?php if($site['country']=='TK'){echo'selected';}?>>Tokelau</option>
																<option value="TO" <?php if($site['country']=='TO'){echo'selected';}?>>Tonga</option>
																<option value="TT" <?php if($site['country']=='TT'){echo'selected';}?>>Trinidad &amp; Tobago</option>
																<option value="TN" <?php if($site['country']=='TN'){echo'selected';}?>>Tunisia</option>
																<option value="TR" <?php if($site['country']=='TR'){echo'selected';}?>>Turkey</option>
																<option value="TU" <?php if($site['country']=='TU'){echo'selected';}?>>Turkmenistan</option>
																<option value="TC" <?php if($site['country']=='TC'){echo'selected';}?>>Turks &amp; Caicos Is</option>
																<option value="TV" <?php if($site['country']=='TV'){echo'selected';}?>>Tuvalu</option>
																<option value="UG" <?php if($site['country']=='UG'){echo'selected';}?>>Uganda</option>
																<option value="UA" <?php if($site['country']=='UA'){echo'selected';}?>>Ukraine</option>
																<option value="AE" <?php if($site['country']=='AE'){echo'selected';}?>>United Arab Emirates</option>
																<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>United Kingdom</option>
																<option value="US" <?php if($site['country']=='US'){echo'selected';}?>>United States of America</option>
																<option value="UY" <?php if($site['country']=='UY'){echo'selected';}?>>Uruguay</option>
																<option value="UZ" <?php if($site['country']=='UZ'){echo'selected';}?>>Uzbekistan</option>
																<option value="VU" <?php if($site['country']=='VU'){echo'selected';}?>>Vanuatu</option>
																<option value="VS" <?php if($site['country']=='VS'){echo'selected';}?>>Vatican City State</option>
																<option value="VE" <?php if($site['country']=='VE'){echo'selected';}?>>Venezuela</option>
																<option value="VN" <?php if($site['country']=='VN'){echo'selected';}?>>Vietnam</option>
																<option value="VB" <?php if($site['country']=='VB'){echo'selected';}?>>Virgin Islands (Brit)</option>
																<option value="VA" <?php if($site['country']=='VA'){echo'selected';}?>>Virgin Islands (USA)</option>
																<option value="WK" <?php if($site['country']=='WK'){echo'selected';}?>>Wake Island</option>
																<option value="WF" <?php if($site['country']=='WF'){echo'selected';}?>>Wallis &amp; Futana Is</option>
																<option value="YE" <?php if($site['country']=='YE'){echo'selected';}?>>Yemen</option>
																<option value="ZR" <?php if($site['country']=='ZR'){echo'selected';}?>>Zaire</option>
																<option value="ZM" <?php if($site['country']=='ZM'){echo'selected';}?>>Zambia</option>
																<option value="ZW" <?php if($site['country']=='ZW'){echo'selected';}?>>Zimbabwe</option>
															</select>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-lg-6">
														<label for="power_cost" class="col-lg-4 control-label">Power Cost</label>
														<div class="col-lg-6">
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>
																<input type="text" name="power_cost" id="power_cost" class="form-control" placeholder="0.10" required>
																<span class="input-group-addon">per kWh</span>
															</div>
														</div>
													</div>

													<div class="form-group col-lg-6">
														<label for="max_amps" class="col-lg-4 control-label">Max AMPs</label>
														<div class="col-lg-6">
															<div class="input-group">
																<input type="text" name="max_amps" id="max_amps" class="form-control" placeholder="20" required>
																<span class="input-group-addon">Max AMP @ 80% load</span>
															</div>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-lg-6">
														<label for="max_kilowatts" class="col-lg-4 control-label">Max kW</label>
														<div class="col-lg-6">
															<div class="input-group">
																<input type="text" name="max_kilowatts" id="max_kilowatts" class="form-control" placeholder="40" required>
																<span class="input-group-addon">Max kW @ 80% load</span>
															</div>
														</div>
													</div>

													<div class="form-group col-lg-6">
														<label for="voltage" class="col-lg-4 control-label">Voltage</label>
														<div class="col-lg-6">
															<div class="input-group">
																<input type="text" name="voltage" id="voltage" class="form-control" value="<?php echo $site['voltage']; ?>" placeholder="110" required>
																<span class="input-group-addon">v</span>
															</div>
														</div>
													</div>
												</div>
										
												<div class="row">
													<div class="form-group col-lg-12">
														<div class="pull-right">
															<button type="submit" class="btn btn-success">Save</button>
														</div>
													</div>
												</div>
											</form>
								  		</div>
								  		<div class="tab-pane" id="tab_3">

								  		</div>
									</div>
							  	</div>
							</div>
	          			</div> 
	                </section>
				<?php }else{ ?>
					<section class="content">
						<div class="row">
							<div class="col-md-12">
							<?php $customer_miners = get_customer_miners(); ?>

				           	<?php 
				           		$total_profit = 0;
				           		$total_cost = 0;
				           		$total_hashrate = 0;
				           		foreach($customer_miners as $customer_miner)
				           		{
				           			$total_cost 					= $total_cost + $customer_miner['cost'];
				           			$total_profit 					= $total_profit + $customer_miner['profit'];
				           			$total_hashrate_bits 			= explode(' ', $customer_miner['hashrate']);

				           			if($customer_miner['hardware_raw'] == 'antminer-d3')
				           			{
				           				$total_hashrate 				= $total_hashrate + $total_hashrate_bits[0];
				           				$total_hashrate 				= $total_hashrate / 1000;
				           			}else{
				           				$total_hashrate 				= $total_hashrate + $total_hashrate_bits[0];
				           			}
				           		}
				           	?>
							<div class="col-md-3">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Customer Miners</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<?php echo count($customer_miners); ?>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Hashrate</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<?php echo number_format($total_hashrate, 2); ?> THs
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Projected Monthly Cost</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											$<?php echo number_format($total_cost, 2); ?>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Projected Monthly Profit</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											$<?php echo number_format($total_profit, 2); ?>
										</h1>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">

							<style>
								#map-canvas {
								height: 600px;
								margin: 0px;
								padding: 0px;
								width: 100%;
								}
						 	</style>
							
							<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpMWtXLvl-a6YsAAB2HBQvK-_c0_zDtXg&v=3"></script>
							
							<script>
								var map;
								function initialize() {
									var myLatlng = new google.maps.LatLng(-25.363882,131.044922);

									var mapOptions = {
										zoom: 3,
										center: new google.maps.LatLng(<?php echo $account_details['location']['latitude']; ?>, <?php echo $account_details['location']['longitude']; ?>)
									};
									map = new google.maps.Map(document.getElementById('map-canvas'),
									mapOptions);

									<?php foreach($customer_miners as $customer_miner){ ?>
										add_markers('<?php echo $customer_miner['gps']['latitude']; ?>', '<?php echo $customer_miner['gps']['longitude']; ?>');
									<?php } ?>
								}

								function add_marker(location) {
							        marker = new google.maps.Marker({
							            position: location,
							            map: map
							        });
							    }

							    // Testing the addMarker function
							    function add_markers(lat, lng) {
							           marker = new google.maps.LatLng(lat, lng);
							           add_marker(marker);
							    }

								google.maps.event.addDomListener(window, 'load', initialize);

							</script>
							
							<div class="col-md-12">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Miner Locations</h3>
									</div>
									<div class="box-body">
										<div id="map-canvas"></div>
									</div>
								</div>
							</div>
	                	</div>
	                </section>
	            <?php } ?>
            </div>
        <?php } ?>
        
        <?php function my_account(){ ?>
        	<?php global $account_details, $site; ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>My Account <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">My Account</li>
                    </ol>
                </section>
    
                <section class="content">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                        	<?php if(empty($_GET['tab']) || $_GET['tab']==1){ ?>
                            	<li class="active"><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=1">Profile Details</a></li>
                            <?php }else{ ?>
                            	<li><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=1">Profile Details</a></li>
                            <?php } ?>
                            
                            <?php if($_GET['tab']==2){ ?>
                            	<li class="active"><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=2">Profile Photo</a></li>
                            <?php }else{ ?>
                            	<li><a href="<?php echo $site['url']; ?>/dashboard?c=my_account&tab=2">Profile Photo</a></li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <?php if(empty($_GET['tab']) || $_GET['tab']==1){ ?>
                            	<div class="active tab-pane" id="profile_settings">
                            <?php }else{ ?>
                            	<div class="tab-pane" id="profile_settings">
                            <?php } ?>
                                <form action="actions.php?a=my_account_update" method="post" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="firstname" class="col-sm-2 control-label">Firstname</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo $account_details['firstname']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="lastname" class="col-sm-2 control-label">Lastname</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo $account_details['lastname']; ?>">
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="form-group">
                                        <label for="email" class="col-sm-2 control-label">Email <small>(used to login to MCP)</small></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" id="email" class="form-control" value="<?php echo $account_details['email']; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-sm-2 control-label">Notification Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="notification_email" id="email" class="form-control" value="<?php echo $account_details['notification_email']; ?>">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="phonenumber" class="col-sm-2 control-label">Notification Tel</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="notification_tel" id="phonenumber" class="form-control" value="<?php echo $account_details['notification_tel']; ?>">
                                        </div>
                                    </div>
                                    
                                    <hr>

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Show / Hide Stats</label>
										<div class="col-sm-10">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="show_site_summary" <?php if($account_details['gui_settings']['show_site_summary']=='on'){echo'checked';} ?>>
													Show Site Summary Stats
												</label>
											</div>
											<div class="checkbox">
												<label>
													<input type="checkbox" name="show_dashboard_summary" <?php if($account_details['gui_settings']['show_dashboard_summary']=='on'){echo'checked';} ?>>
													Show Dashboard Summary
												</label>
											</div>
										</div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <?php if($_GET['tab']==2){ ?>
                            	<div class="active tab-pane" id="profile_avatar">
                            <?php }else{ ?>
                            	<div class="tab-pane" id="profile_avatar">
                            <?php } ?>
                                <form name="upload_form" id="upload_form" enctype="multipart/form-data" method="post">
                                To upload a profile photo, simple select the file you wish to upload and click the upload button.<br><br>
                                    <input type="hidden" name="uid" id="uid" value="<?php echo $account_details['account']['id']; ?>">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="col-lg-6 col-sm-6 col-12">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <span class="btn btn-primary btn-file">
                                                                Browse&hellip; <input type="file" name="file1" id="file1" accept="image/*">
                                                            </span>
                                                        </span>
                                                        <input type="text" class="form-control" readonly>
                                                    </div>
                                                    <br>
                                                    <center>
                                                        <progress id="progressBar" value="0" max="100" style="width:100%;"></progress>
                                                        <span id="loaded_n_total"></span> <span id="status"></span>
                                                    </center>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <br>
                                    
                                    <div class="row">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <input type="button" class="btn btn-success" value="Upload File" onclick="uploadFile()">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        <?php } ?>
        
        <?php function sites(){ ?>
        	<?php global $account_details, $site; ?>

            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Sites <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Sites</li>
                    </ol>
                </section>
                
                <section class="content">
                	<div class="row">
						<div class="col-md-12">
						  	<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
							  		<li class="active"><a href="#tab_1" data-toggle="tab">Sites</a></li>
							  		<li><a href="#tab_2" data-toggle="tab">Add Site</a></li>
								</ul>
								<div class="tab-content">
							  		<div class="tab-pane active" id="tab_1">
										<table id="sites" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Site</th>
													<th>Controller</th>
													<th>Miners</th>
													<th>Power</th>
													<th>Financial</th>
													<th width="60px"></th>
												</tr>
											</thead>
											<tbody>
												<?php show_sites(); ?>
											</tbody>
										</table>
							  		</div>
							  		<div class="tab-pane" id="tab_2">
										<form action="actions.php?a=site_add" method="post" class="form-horizontal">
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="name" class="col-lg-2 control-label">Name</label>
													<div class="col-lg-10">
														<input type="text" name="name" id="name" class="form-control" placeholder="Site X" required>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<label for="location" class="col-lg-2 control-label">Full Address</label>
													<div class="col-lg-10">
														<input type="text" name="location" id="location" class="form-control" placeholder="70 Monty Drive, Savannah, TN, 38372, United States">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="city" class="col-lg-2 control-label">Weather City</label>
													<div class="col-lg-10">
														<input type="text" name="city" id="city" class="form-control" placeholder="Savannah" required>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<label for="country" class="col-lg-2 control-label">Weather Country</label>
													<div class="col-lg-10">
														<select name="country" id="country" class="form-control" placeholder="United States" required>
															<option value="" <?php if($site['country']==''){echo'selected';}?>>Select a Country</option>
															<option value="AF" <?php if($site['country']=='AF'){echo'selected';}?>>Afghanistan</option>
															<option value="AL" <?php if($site['country']=='AL'){echo'selected';}?>>Albania</option>
															<option value="DZ" <?php if($site['country']=='DZ'){echo'selected';}?>>Algeria</option>
															<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>American Samoa</option>
															<option value="AD" <?php if($site['country']=='AD'){echo'selected';}?>>Andorra</option>
															<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Angola</option>
															<option value="AI" <?php if($site['country']=='AI'){echo'selected';}?>>Anguilla</option>
															<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Antigua &amp; Barbuda</option>
															<option value="AR" <?php if($site['country']=='AR'){echo'selected';}?>>Argentina</option>
															<option value="AA" <?php if($site['country']=='AA'){echo'selected';}?>>Armenia</option>
															<option value="AW" <?php if($site['country']=='AW'){echo'selected';}?>>Aruba</option>
															<option value="AU" <?php if($site['country']=='AU'){echo'selected';}?>>Australia</option>
															<option value="AT" <?php if($site['country']=='AT'){echo'selected';}?>>Austria</option>
															<option value="AZ" <?php if($site['country']=='AZ'){echo'selected';}?>>Azerbaijan</option>
															<option value="BS" <?php if($site['country']=='BS'){echo'selected';}?>>Bahamas</option>
															<option value="BH" <?php if($site['country']=='BH'){echo'selected';}?>>Bahrain</option>
															<option value="BD" <?php if($site['country']=='BD'){echo'selected';}?>>Bangladesh</option>
															<option value="BB" <?php if($site['country']=='BB'){echo'selected';}?>>Barbados</option>
															<option value="BY" <?php if($site['country']=='BY'){echo'selected';}?>>Belarus</option>
															<option value="BE" <?php if($site['country']=='BE'){echo'selected';}?>>Belgium</option>
															<option value="BZ" <?php if($site['country']=='BZ'){echo'selected';}?>>Belize</option>
															<option value="BJ" <?php if($site['country']=='BJ'){echo'selected';}?>>Benin</option>
															<option value="BM" <?php if($site['country']=='BM'){echo'selected';}?>>Bermuda</option>
															<option value="BT" <?php if($site['country']=='BT'){echo'selected';}?>>Bhutan</option>
															<option value="BO" <?php if($site['country']=='BO'){echo'selected';}?>>Bolivia</option>
															<option value="BL" <?php if($site['country']=='BL'){echo'selected';}?>>Bonaire</option>
															<option value="BA" <?php if($site['country']=='BA'){echo'selected';}?>>Bosnia &amp; Herzegovina</option>
															<option value="BW" <?php if($site['country']=='BW'){echo'selected';}?>>Botswana</option>
															<option value="BR" <?php if($site['country']=='BR'){echo'selected';}?>>Brazil</option>
															<option value="BC" <?php if($site['country']=='BC'){echo'selected';}?>>British Indian Ocean Ter</option>
															<option value="BN" <?php if($site['country']=='BN'){echo'selected';}?>>Brunei</option>
															<option value="BG" <?php if($site['country']=='BG'){echo'selected';}?>>Bulgaria</option>
															<option value="BF" <?php if($site['country']=='BF'){echo'selected';}?>>Burkina Faso</option>
															<option value="BI" <?php if($site['country']=='BI'){echo'selected';}?>>Burundi</option>
															<option value="KH" <?php if($site['country']=='KH'){echo'selected';}?>>Cambodia</option>
															<option value="CM" <?php if($site['country']=='CM'){echo'selected';}?>>Cameroon</option>
															<option value="CA" <?php if($site['country']=='CA'){echo'selected';}?>>Canada</option>
															<option value="IC" <?php if($site['country']=='IC'){echo'selected';}?>>Canary Islands</option>
															<option value="CV" <?php if($site['country']=='CV'){echo'selected';}?>>Cape Verde</option>
															<option value="KY" <?php if($site['country']=='KY'){echo'selected';}?>>Cayman Islands</option>
															<option value="CF" <?php if($site['country']=='CF'){echo'selected';}?>>Central African Republic</option>
															<option value="TD" <?php if($site['country']=='TD'){echo'selected';}?>>Chad</option>
															<option value="CD" <?php if($site['country']=='CD'){echo'selected';}?>>Channel Islands</option>
															<option value="CL" <?php if($site['country']=='CL'){echo'selected';}?>>Chile</option>
															<option value="CN" <?php if($site['country']=='CN'){echo'selected';}?>>China</option>
															<option value="CI" <?php if($site['country']=='CI'){echo'selected';}?>>Christmas Island</option>
															<option value="CS" <?php if($site['country']=='CS'){echo'selected';}?>>Cocos Island</option>
															<option value="CO" <?php if($site['country']=='CO'){echo'selected';}?>>Colombia</option>
															<option value="CC" <?php if($site['country']=='CC'){echo'selected';}?>>Comoros</option>
															<option value="CG" <?php if($site['country']=='CG'){echo'selected';}?>>Congo</option>
															<option value="CK" <?php if($site['country']=='CK'){echo'selected';}?>>Cook Islands</option>
															<option value="CR" <?php if($site['country']=='CR'){echo'selected';}?>>Costa Rica</option>
															<option value="CT" <?php if($site['country']=='CT'){echo'selected';}?>>Cote D'Ivoire</option>
															<option value="HR" <?php if($site['country']=='HR'){echo'selected';}?>>Croatia</option>
															<option value="CU" <?php if($site['country']=='CU'){echo'selected';}?>>Cuba</option>
															<option value="CB" <?php if($site['country']=='CB'){echo'selected';}?>>Curacao</option>
															<option value="CY" <?php if($site['country']=='CY'){echo'selected';}?>>Cyprus</option>
															<option value="CZ" <?php if($site['country']=='CZ'){echo'selected';}?>>Czech Republic</option>
															<option value="DK" <?php if($site['country']=='DK'){echo'selected';}?>>Denmark</option>
															<option value="DJ" <?php if($site['country']=='DJ'){echo'selected';}?>>Djibouti</option>
															<option value="DM" <?php if($site['country']=='DM'){echo'selected';}?>>Dominica</option>
															<option value="DO" <?php if($site['country']=='DO'){echo'selected';}?>>Dominican Republic</option>
															<option value="TM" <?php if($site['country']=='TM'){echo'selected';}?>>East Timor</option>
															<option value="EC" <?php if($site['country']=='EC'){echo'selected';}?>>Ecuador</option>
															<option value="EG" <?php if($site['country']=='EG'){echo'selected';}?>>Egypt</option>
															<option value="SV" <?php if($site['country']=='SV'){echo'selected';}?>>El Salvador</option>
															<option value="GQ" <?php if($site['country']=='GQ'){echo'selected';}?>>Equatorial Guinea</option>
															<option value="ER" <?php if($site['country']=='ER'){echo'selected';}?>>Eritrea</option>
															<option value="EE" <?php if($site['country']=='EE'){echo'selected';}?>>Estonia</option>
															<option value="ET" <?php if($site['country']=='ET'){echo'selected';}?>>Ethiopia</option>
															<option value="FA" <?php if($site['country']=='FA'){echo'selected';}?>>Falkland Islands</option>
															<option value="FO" <?php if($site['country']=='DO'){echo'selected';}?>>Faroe Islands</option>
															<option value="FJ" <?php if($site['country']=='FJ'){echo'selected';}?>>Fiji</option>
															<option value="FI" <?php if($site['country']=='FI'){echo'selected';}?>>Finland</option>
															<option value="FR" <?php if($site['country']=='FR'){echo'selected';}?>>France</option>
															<option value="GF" <?php if($site['country']=='GF'){echo'selected';}?>>French Guiana</option>
															<option value="PF" <?php if($site['country']=='PF'){echo'selected';}?>>French Polynesia</option>
															<option value="FS" <?php if($site['country']=='FS'){echo'selected';}?>>French Southern Ter</option>
															<option value="GA" <?php if($site['country']=='GA'){echo'selected';}?>>Gabon</option>
															<option value="GM" <?php if($site['country']=='GM'){echo'selected';}?>>Gambia</option>
															<option value="GE" <?php if($site['country']=='GE'){echo'selected';}?>>Georgia</option>
															<option value="DE" <?php if($site['country']=='DE'){echo'selected';}?>>Germany</option>
															<option value="GH" <?php if($site['country']=='GH'){echo'selected';}?>>Ghana</option>
															<option value="GI" <?php if($site['country']=='GI'){echo'selected';}?>>Gibraltar</option>
															<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>Great Britain</option>
															<option value="GR" <?php if($site['country']=='GR'){echo'selected';}?>>Greece</option>
															<option value="GL" <?php if($site['country']=='GL'){echo'selected';}?>>Greenland</option>
															<option value="GD" <?php if($site['country']=='GD'){echo'selected';}?>>Grenada</option>
															<option value="GP" <?php if($site['country']=='GP'){echo'selected';}?>>Guadeloupe</option>
															<option value="GU" <?php if($site['country']=='GU'){echo'selected';}?>>Guam</option>
															<option value="GT" <?php if($site['country']=='GT'){echo'selected';}?>>Guatemala</option>
															<option value="GN" <?php if($site['country']=='GN'){echo'selected';}?>>Guinea</option>
															<option value="GY" <?php if($site['country']=='GY'){echo'selected';}?>>Guyana</option>
															<option value="HT" <?php if($site['country']=='HT'){echo'selected';}?>>Haiti</option>
															<option value="HW" <?php if($site['country']=='HW'){echo'selected';}?>>Hawaii</option>
															<option value="HN" <?php if($site['country']=='HN'){echo'selected';}?>>Honduras</option>
															<option value="HK" <?php if($site['country']=='HK'){echo'selected';}?>>Hong Kong</option>
															<option value="HU" <?php if($site['country']=='HU'){echo'selected';}?>>Hungary</option>
															<option value="IS" <?php if($site['country']=='IS'){echo'selected';}?>>Iceland</option>
															<option value="IN" <?php if($site['country']=='IN'){echo'selected';}?>>India</option>
															<option value="ID" <?php if($site['country']=='ID'){echo'selected';}?>>Indonesia</option>
															<option value="IA" <?php if($site['country']=='IA'){echo'selected';}?>>Iran</option>
															<option value="IQ" <?php if($site['country']=='IQ'){echo'selected';}?>>Iraq</option>
															<option value="IR" <?php if($site['country']=='IR'){echo'selected';}?>>Ireland</option>
															<option value="IM" <?php if($site['country']=='IM'){echo'selected';}?>>Isle of Man</option>
															<option value="IL" <?php if($site['country']=='IL'){echo'selected';}?>>Israel</option>
															<option value="IT" <?php if($site['country']=='IT'){echo'selected';}?>>Italy</option>
															<option value="JM" <?php if($site['country']=='JM'){echo'selected';}?>>Jamaica</option>
															<option value="JP" <?php if($site['country']=='JP'){echo'selected';}?>>Japan</option>
															<option value="JO" <?php if($site['country']=='JO'){echo'selected';}?>>Jordan</option>
															<option value="KZ" <?php if($site['country']=='KZ'){echo'selected';}?>>Kazakhstan</option>
															<option value="KE" <?php if($site['country']=='KE'){echo'selected';}?>>Kenya</option>
															<option value="KI" <?php if($site['country']=='KI'){echo'selected';}?>>Kiribati</option>
															<option value="NK" <?php if($site['country']=='NK'){echo'selected';}?>>Korea North</option>
															<option value="KS" <?php if($site['country']=='KS'){echo'selected';}?>>Korea South</option>
															<option value="KW" <?php if($site['country']=='KW'){echo'selected';}?>>Kuwait</option>
															<option value="KG" <?php if($site['country']=='KG'){echo'selected';}?>>Kyrgyzstan</option>
															<option value="LA" <?php if($site['country']=='LA'){echo'selected';}?>>Laos</option>
															<option value="LV" <?php if($site['country']=='LV'){echo'selected';}?>>Latvia</option>
															<option value="LB" <?php if($site['country']=='LB'){echo'selected';}?>>Lebanon</option>
															<option value="LS" <?php if($site['country']=='LS'){echo'selected';}?>>Lesotho</option>
															<option value="LR" <?php if($site['country']=='LR'){echo'selected';}?>>Liberia</option>
															<option value="LY" <?php if($site['country']=='LY'){echo'selected';}?>>Libya</option>
															<option value="LI" <?php if($site['country']=='LI'){echo'selected';}?>>Liechtenstein</option>
															<option value="LT" <?php if($site['country']=='LT'){echo'selected';}?>>Lithuania</option>
															<option value="LU" <?php if($site['country']=='LU'){echo'selected';}?>>Luxembourg</option>
															<option value="MO" <?php if($site['country']=='MO'){echo'selected';}?>>Macau</option>
															<option value="MK" <?php if($site['country']=='MK'){echo'selected';}?>>Macedonia</option>
															<option value="MG" <?php if($site['country']=='MG'){echo'selected';}?>>Madagascar</option>
															<option value="MY" <?php if($site['country']=='MY'){echo'selected';}?>>Malaysia</option>
															<option value="MW" <?php if($site['country']=='MW'){echo'selected';}?>>Malawi</option>
															<option value="MV" <?php if($site['country']=='MV'){echo'selected';}?>>Maldives</option>
															<option value="ML" <?php if($site['country']=='ML'){echo'selected';}?>>Mali</option>
															<option value="MT" <?php if($site['country']=='MT'){echo'selected';}?>>Malta</option>
															<option value="MH" <?php if($site['country']=='MH'){echo'selected';}?>>Marshall Islands</option>
															<option value="MQ" <?php if($site['country']=='MQ'){echo'selected';}?>>Martinique</option>
															<option value="MR" <?php if($site['country']=='MR'){echo'selected';}?>>Mauritania</option>
															<option value="MU" <?php if($site['country']=='MU'){echo'selected';}?>>Mauritius</option>
															<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Mayotte</option>
															<option value="MX" <?php if($site['country']=='MX'){echo'selected';}?>>Mexico</option>
															<option value="MI" <?php if($site['country']=='MI'){echo'selected';}?>>Midway Islands</option>
															<option value="MD" <?php if($site['country']=='MD'){echo'selected';}?>>Moldova</option>
															<option value="MC" <?php if($site['country']=='MC'){echo'selected';}?>>Monaco</option>
															<option value="MN" <?php if($site['country']=='MN'){echo'selected';}?>>Mongolia</option>
															<option value="MS" <?php if($site['country']=='MS'){echo'selected';}?>>Montserrat</option>
															<option value="MA" <?php if($site['country']=='MA'){echo'selected';}?>>Morocco</option>
															<option value="MZ" <?php if($site['country']=='MZ'){echo'selected';}?>>Mozambique</option>
															<option value="MM" <?php if($site['country']=='MM'){echo'selected';}?>>Myanmar</option>
															<option value="NA" <?php if($site['country']=='NA'){echo'selected';}?>>Nambia</option>
															<option value="NU" <?php if($site['country']=='NU'){echo'selected';}?>>Nauru</option>
															<option value="NP" <?php if($site['country']=='NP'){echo'selected';}?>>Nepal</option>
															<option value="AN" <?php if($site['country']=='AN'){echo'selected';}?>>Netherland Antilles</option>
															<option value="NL" <?php if($site['country']=='NL'){echo'selected';}?>>Netherlands (Holland, Europe)</option>
															<option value="NV" <?php if($site['country']=='NV'){echo'selected';}?>>Nevis</option>
															<option value="NC" <?php if($site['country']=='NC'){echo'selected';}?>>New Caledonia</option>
															<option value="NZ" <?php if($site['country']=='NZ'){echo'selected';}?>>New Zealand</option>
															<option value="NI" <?php if($site['country']=='NI'){echo'selected';}?>>Nicaragua</option>
															<option value="NE" <?php if($site['country']=='NE'){echo'selected';}?>>Niger</option>
															<option value="NG" <?php if($site['country']=='NG'){echo'selected';}?>>Nigeria</option>
															<option value="NW" <?php if($site['country']=='NW'){echo'selected';}?>>Niue</option>
															<option value="NF" <?php if($site['country']=='NF'){echo'selected';}?>>Norfolk Island</option>
															<option value="NO" <?php if($site['country']=='NO'){echo'selected';}?>>Norway</option>
															<option value="OM" <?php if($site['country']=='OM'){echo'selected';}?>>Oman</option>
															<option value="PK" <?php if($site['country']=='PK'){echo'selected';}?>>Pakistan</option>
															<option value="PW" <?php if($site['country']=='PW'){echo'selected';}?>>Palau Island</option>
															<option value="PS" <?php if($site['country']=='PS'){echo'selected';}?>>Palestine</option>
															<option value="PA" <?php if($site['country']=='PA'){echo'selected';}?>>Panama</option>
															<option value="PG" <?php if($site['country']=='PG'){echo'selected';}?>>Papua New Guinea</option>
															<option value="PY" <?php if($site['country']=='PY'){echo'selected';}?>>Paraguay</option>
															<option value="PE" <?php if($site['country']=='PE'){echo'selected';}?>>Peru</option>
															<option value="PH" <?php if($site['country']=='PH'){echo'selected';}?>>Philippines</option>
															<option value="PO" <?php if($site['country']=='PO'){echo'selected';}?>>Pitcairn Island</option>
															<option value="PL" <?php if($site['country']=='PL'){echo'selected';}?>>Poland</option>
															<option value="PT" <?php if($site['country']=='PT'){echo'selected';}?>>Portugal</option>
															<option value="PR" <?php if($site['country']=='PR'){echo'selected';}?>>Puerto Rico</option>
															<option value="QA" <?php if($site['country']=='WA'){echo'selected';}?>>Qatar</option>
															<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Republic of Montenegro</option>
															<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Republic of Serbia</option>
															<option value="RE" <?php if($site['country']=='RE'){echo'selected';}?>>Reunion</option>
															<option value="RO" <?php if($site['country']=='RO'){echo'selected';}?>>Romania</option>
															<option value="RU" <?php if($site['country']=='RU'){echo'selected';}?>>Russia</option>
															<option value="RW" <?php if($site['country']=='RW'){echo'selected';}?>>Rwanda</option>
															<option value="NT" <?php if($site['country']=='NT'){echo'selected';}?>>St Barthelemy</option>
															<option value="EU" <?php if($site['country']=='EU'){echo'selected';}?>>St Eustatius</option>
															<option value="HE" <?php if($site['country']=='HE'){echo'selected';}?>>St Helena</option>
															<option value="KN" <?php if($site['country']=='KN'){echo'selected';}?>>St Kitts-Nevis</option>
															<option value="LC" <?php if($site['country']=='LC'){echo'selected';}?>>St Lucia</option>
															<option value="MB" <?php if($site['country']=='MB'){echo'selected';}?>>St Maarten</option>
															<option value="PM" <?php if($site['country']=='PM'){echo'selected';}?>>St Pierre &amp; Miquelon</option>
															<option value="VC" <?php if($site['country']=='VC'){echo'selected';}?>>St Vincent &amp; Grenadines</option>
															<option value="SP" <?php if($site['country']=='SP'){echo'selected';}?>>Saipan</option>
															<option value="SO" <?php if($site['country']=='SO'){echo'selected';}?>>Samoa</option>
															<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>Samoa American</option>
															<option value="SM" <?php if($site['country']=='SM'){echo'selected';}?>>San Marino</option>
															<option value="ST" <?php if($site['country']=='ST'){echo'selected';}?>>Sao Tome &amp; Principe</option>
															<option value="SA" <?php if($site['country']=='SA'){echo'selected';}?>>Saudi Arabia</option>
															<option value="SN" <?php if($site['country']=='SN'){echo'selected';}?>>Senegal</option>
															<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Serbia</option>
															<option value="SC" <?php if($site['country']=='SC'){echo'selected';}?>>Seychelles</option>
															<option value="SL" <?php if($site['country']=='SL'){echo'selected';}?>>Sierra Leone</option>
															<option value="SG" <?php if($site['country']=='SG'){echo'selected';}?>>Singapore</option>
															<option value="SK" <?php if($site['country']=='SK'){echo'selected';}?>>Slovakia</option>
															<option value="SI" <?php if($site['country']=='SI'){echo'selected';}?>>Slovenia</option>
															<option value="SB" <?php if($site['country']=='SB'){echo'selected';}?>>Solomon Islands</option>
															<option value="OI" <?php if($site['country']=='OI'){echo'selected';}?>>Somalia</option>
															<option value="ZA" <?php if($site['country']=='ZA'){echo'selected';}?>>South Africa</option>
															<option value="ES" <?php if($site['country']=='ES'){echo'selected';}?>>Spain</option>
															<option value="LK" <?php if($site['country']=='LK'){echo'selected';}?>>Sri Lanka</option>
															<option value="SD" <?php if($site['country']=='SD'){echo'selected';}?>>Sudan</option>
															<option value="SR" <?php if($site['country']=='SR'){echo'selected';}?>>Suriname</option>
															<option value="SZ" <?php if($site['country']=='SZ'){echo'selected';}?>>Swaziland</option>
															<option value="SE" <?php if($site['country']=='SE'){echo'selected';}?>>Sweden</option>
															<option value="CH" <?php if($site['country']=='CH'){echo'selected';}?>>Switzerland</option>
															<option value="SY" <?php if($site['country']=='SY'){echo'selected';}?>>Syria</option>
															<option value="TA" <?php if($site['country']=='TA'){echo'selected';}?>>Tahiti</option>
															<option value="TW" <?php if($site['country']=='TW'){echo'selected';}?>>Taiwan</option>
															<option value="TJ" <?php if($site['country']=='TJ'){echo'selected';}?>>Tajikistan</option>
															<option value="TZ" <?php if($site['country']=='TZ'){echo'selected';}?>>Tanzania</option>
															<option value="TH" <?php if($site['country']=='TH'){echo'selected';}?>>Thailand</option>
															<option value="TG" <?php if($site['country']=='TG'){echo'selected';}?>>Togo</option>
															<option value="TK" <?php if($site['country']=='TK'){echo'selected';}?>>Tokelau</option>
															<option value="TO" <?php if($site['country']=='TO'){echo'selected';}?>>Tonga</option>
															<option value="TT" <?php if($site['country']=='TT'){echo'selected';}?>>Trinidad &amp; Tobago</option>
															<option value="TN" <?php if($site['country']=='TN'){echo'selected';}?>>Tunisia</option>
															<option value="TR" <?php if($site['country']=='TR'){echo'selected';}?>>Turkey</option>
															<option value="TU" <?php if($site['country']=='TU'){echo'selected';}?>>Turkmenistan</option>
															<option value="TC" <?php if($site['country']=='TC'){echo'selected';}?>>Turks &amp; Caicos Is</option>
															<option value="TV" <?php if($site['country']=='TV'){echo'selected';}?>>Tuvalu</option>
															<option value="UG" <?php if($site['country']=='UG'){echo'selected';}?>>Uganda</option>
															<option value="UA" <?php if($site['country']=='UA'){echo'selected';}?>>Ukraine</option>
															<option value="AE" <?php if($site['country']=='AE'){echo'selected';}?>>United Arab Emirates</option>
															<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>United Kingdom</option>
															<option value="US" <?php if($site['country']=='US'){echo'selected';}?>>United States of America</option>
															<option value="UY" <?php if($site['country']=='UY'){echo'selected';}?>>Uruguay</option>
															<option value="UZ" <?php if($site['country']=='UZ'){echo'selected';}?>>Uzbekistan</option>
															<option value="VU" <?php if($site['country']=='VU'){echo'selected';}?>>Vanuatu</option>
															<option value="VS" <?php if($site['country']=='VS'){echo'selected';}?>>Vatican City State</option>
															<option value="VE" <?php if($site['country']=='VE'){echo'selected';}?>>Venezuela</option>
															<option value="VN" <?php if($site['country']=='VN'){echo'selected';}?>>Vietnam</option>
															<option value="VB" <?php if($site['country']=='VB'){echo'selected';}?>>Virgin Islands (Brit)</option>
															<option value="VA" <?php if($site['country']=='VA'){echo'selected';}?>>Virgin Islands (USA)</option>
															<option value="WK" <?php if($site['country']=='WK'){echo'selected';}?>>Wake Island</option>
															<option value="WF" <?php if($site['country']=='WF'){echo'selected';}?>>Wallis &amp; Futana Is</option>
															<option value="YE" <?php if($site['country']=='YE'){echo'selected';}?>>Yemen</option>
															<option value="ZR" <?php if($site['country']=='ZR'){echo'selected';}?>>Zaire</option>
															<option value="ZM" <?php if($site['country']=='ZM'){echo'selected';}?>>Zambia</option>
															<option value="ZW" <?php if($site['country']=='ZW'){echo'selected';}?>>Zimbabwe</option>
														</select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-6">
													<label for="power_cost" class="col-lg-4 control-label">Power Cost</label>
													<div class="col-lg-6">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>
															<input type="text" name="power_cost" id="power_cost" class="form-control" placeholder="0.10" required>
															<span class="input-group-addon">per kWh</span>
														</div>
													</div>
												</div>

												<div class="form-group col-lg-6">
													<label for="max_amps" class="col-lg-4 control-label">Max AMPs</label>
													<div class="col-lg-6">
														<div class="input-group">
															<input type="text" name="max_amps" id="max_amps" class="form-control" placeholder="20" required>
															<span class="input-group-addon">Max AMP @ 80% load</span>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-6">
													<label for="max_kilowatts" class="col-lg-4 control-label">Max kW</label>
													<div class="col-lg-6">
														<div class="input-group">
															<input type="text" name="max_kilowatts" id="max_kilowatts" class="form-control" placeholder="40" required>
															<span class="input-group-addon">Max kW @ 80% load</span>
														</div>
													</div>
												</div>

												<div class="form-group col-lg-6">
													<label for="voltage" class="col-lg-4 control-label">Voltage</label>
													<div class="col-lg-6">
														<div class="input-group">
															<input type="text" name="voltage" id="voltage" class="form-control" value="<?php echo $site['voltage']; ?>" placeholder="110" required>
															<span class="input-group-addon">v</span>
														</div>
													</div>
												</div>
											</div>
									
											<div class="row">
												<div class="form-group col-lg-12">
													<div class="pull-right">
														<button type="submit" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</form>
							  		</div>
							  		<div class="tab-pane" id="tab_3">

							  		</div>
								</div>
						  	</div>
						</div>
          			</div> 
                </section>
            </div>
        <?php } ?>
        
        <?php function site(){ ?>
        	<?php global $account_details, $site; ?>
           	<?php $site_id = get('site_id'); ?>
           	<?php $view = get('view'); ?>
           	<?php $site = get_site($site_id); ?>
           	<?php $heatmap = build_heatmap_array($site_id); ?>
           	
           	<!-- <meta http-equiv="refresh" content="30" > -->
           	
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1><?php echo $site['name']; ?> <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li><a href="<?php echo $site['url']; ?>/dashboard?c=sites">Sites</a></li>
                        <li class="active"><?php echo $site['name']; ?></li>
                    </ol>
                </section>
                
                <section class="content">
                	<div class="row">
						<div class="col-md-3">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Miners</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php echo $site['total_online_miners']; ?> 
										<?php 
										if($site['total_offline_miners'] > 0)
										{ 
											echo ' / <font color="red">'.$site['total_offline_miners'].'</font>';
										}
										?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Hashrate</h3>
								</div>
								<div class="box-body">
									<center>
										<h1>
											<?php $show_hashrate = show_hashrate($site['id']); ?>
											<?php if($show_hashrate < 1.0){ ?>
												<?php echo $show_hashrate * 1000; ?> GHs
											<?php }else{ ?>
												<?php echo number_format($show_hashrate, 2); ?> THs
											<?php } ?>
										</h1>
									</center>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Avg Temp (PCB)</h3>
								</div>
								<div class="box-body">
									<center><h1>
										<?php if($account_details['temp_setting'] == 'c'){ ?>
											<?php echo $site['average_temps']['pcb']; ?> °C
										<?php }else{ ?>
											<?php echo c_to_f($site['average_temps']['pcb']); ?> °F
										<?php } ?>
									</h1></center>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Projected Monthly Profit</h3>
								</div>
								<div class="box-body">
									<center><h1>$<?php echo show_monthly_profit($site['id']) ?></h1></center>
								</div>
							</div>
						</div>
					</div>
                	<div class="row">
						<div class="col-md-12">
						  	<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
							  		<li class="active"><a href="#tab_1" data-toggle="tab">Miners</a></li>
							  		<li><a href="#tab_2" data-toggle="tab">Heatmap</a></li>
							  		<li><a href="#tab_4" data-toggle="tab">IP Ranges</a></li>
							  		<li><a href="#tab_3" data-toggle="tab">Settings</a></li>
							  		<?php if(isset($_GET['dev']) && $_GET['dev'] == 'yes'){ ?>
							  			<li><a href="#tab_5" data-toggle="tab">Dev</a></li>
							  		<?php } ?>
								</ul>
								<div class="tab-content">
							  		<div class="tab-pane active" id="tab_1">
										<form action="actions.php?a=miner_update_multi&site_id=<?php echo $site_id; ?>" method="post">
											<div class="row">
												<div class="col-md-4">
													<span id="multi_options_show" class="hidden">
														<div class="col-md-10">
															<select id="multi_options_action" name="multi_options_action" class="form-control" >
																<option value="reboot">Reboot Selected Miners</option>
																<option value="update">Update Selected Miners</option>
															</select>
														</div>
														<div class="col-md-2">
															<button type="submit" class="btn btn-success">GO</button>
														</div>
													</span>
												</div>

												<div class="col-md-8">
													<a href="actions.php?a=job_add&site_id=<?php echo $_GET['site_id']; ?>&miner_id=0&job=network_scan" class="btn btn-primary">Network Scan</a>

													<!--
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>" class="btn btn-default">All</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=unknown" class="btn btn-info">Unknown</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=pending" class="btn btn-primary">Pending</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=offline" class="btn btn-warning">Offline</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=disconnected" class="btn btn-danger">Disconnected</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=mining" class="btn btn-success">Mining</a>
													-->
												</div>
											</div>

											<table id="miners" class="table table-bordered table-striped">
												<thead>
													<tr>
														<th><input type="checkbox" id="checkAll" /></th> <!-- // colum 1 -->
														<th>Description</th> <!-- // colum 2 -->
														<th>Status</th> <!-- // colum 3 -->
														<th>Progress</th> <!-- // colum 4 -->
														<th>Speed</th> <!-- // colum 5 -->
														<th>Coin / Pool</th> <!-- // colum 6 -->
														<th>Money</th> <!-- // colum 7 -->
														<th>Alerts</th> <!-- // colum 8 -->
														<th style="min-width: 10px;"></th> <!-- // colum 9 -->
													</tr>
												</thead>
												<tbody>
													<?php // show_miners_full($site_id); ?>
													<?php show_miners_ajax_template($site_id); ?>
												</tbody>
											</table>
										</form>
							  		</div>
							  		<div class="tab-pane" id="tab_2">
										<?php

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
																	<td width="50px" align="center" valign="middle">
																		<ul id="test2" style="display: table; width: 100%;" id="miner_'.$position['miner_id'].'">
																			<li style="width: 100%; list-style-type: none; display:inline-block;" data-hist="'.$position['miner_temp'].'">
																				<u>'.$position['miner_name'].'</u> <br>
																				<small>'.$position['miner_status'].'</small>
																			</li>
																		</ul>
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
							  		<div class="tab-pane" id="tab_3">
							  			<form action="actions.php?a=site_update&site_id=<?php echo $site['id']; ?>" method="post" class="form-horizontal">
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="api_key" class="col-lg-2 control-label">API Key</label>
													<div class="col-lg-10">
														<div class="input-group">
															<input type="text" name="api_key" id="api_key" class="form-control" value="<?php echo $site['api_key']; ?>" readonly onClick="this.select();" >
															<span class="input-group-addon">Copy & Paste</span>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<label for="name" class="col-lg-2 control-label">Name</label>
													<div class="col-lg-10">
														<input type="text" name="name" id="name" class="form-control" value="<?php echo $site['name']; ?>" required>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="location" class="col-lg-2 control-label">Full Address</label>
													<div class="col-lg-10">
														<input type="text" name="location" id="location" class="form-control" value="<?php echo $site['location']['address']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group col-lg-6">
													<label for="city" class="col-lg-4 control-label">Weather City</label>
													<div class="col-lg-8">
														<input type="text" name="city" id="city" class="form-control" value="<?php echo $site['city']; ?>" required>
													</div>
												</div>
											
												<div class="form-group col-lg-6">
													<label for="country" class="col-lg-4 control-label">Weather Country</label>
													<div class="col-lg-8">
														<select name="country" required class="form-control">
															<option value="" <?php if($site['country']==''){echo'selected';}?>>Select a Country</option>
															<option value="AF" <?php if($site['country']=='AF'){echo'selected';}?>>Afghanistan</option>
															<option value="AL" <?php if($site['country']=='AL'){echo'selected';}?>>Albania</option>
															<option value="DZ" <?php if($site['country']=='DZ'){echo'selected';}?>>Algeria</option>
															<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>American Samoa</option>
															<option value="AD" <?php if($site['country']=='AD'){echo'selected';}?>>Andorra</option>
															<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Angola</option>
															<option value="AI" <?php if($site['country']=='AI'){echo'selected';}?>>Anguilla</option>
															<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Antigua &amp; Barbuda</option>
															<option value="AR" <?php if($site['country']=='AR'){echo'selected';}?>>Argentina</option>
															<option value="AA" <?php if($site['country']=='AA'){echo'selected';}?>>Armenia</option>
															<option value="AW" <?php if($site['country']=='AW'){echo'selected';}?>>Aruba</option>
															<option value="AU" <?php if($site['country']=='AU'){echo'selected';}?>>Australia</option>
															<option value="AT" <?php if($site['country']=='AT'){echo'selected';}?>>Austria</option>
															<option value="AZ" <?php if($site['country']=='AZ'){echo'selected';}?>>Azerbaijan</option>
															<option value="BS" <?php if($site['country']=='BS'){echo'selected';}?>>Bahamas</option>
															<option value="BH" <?php if($site['country']=='BH'){echo'selected';}?>>Bahrain</option>
															<option value="BD" <?php if($site['country']=='BD'){echo'selected';}?>>Bangladesh</option>
															<option value="BB" <?php if($site['country']=='BB'){echo'selected';}?>>Barbados</option>
															<option value="BY" <?php if($site['country']=='BY'){echo'selected';}?>>Belarus</option>
															<option value="BE" <?php if($site['country']=='BE'){echo'selected';}?>>Belgium</option>
															<option value="BZ" <?php if($site['country']=='BZ'){echo'selected';}?>>Belize</option>
															<option value="BJ" <?php if($site['country']=='BJ'){echo'selected';}?>>Benin</option>
															<option value="BM" <?php if($site['country']=='BM'){echo'selected';}?>>Bermuda</option>
															<option value="BT" <?php if($site['country']=='BT'){echo'selected';}?>>Bhutan</option>
															<option value="BO" <?php if($site['country']=='BO'){echo'selected';}?>>Bolivia</option>
															<option value="BL" <?php if($site['country']=='BL'){echo'selected';}?>>Bonaire</option>
															<option value="BA" <?php if($site['country']=='BA'){echo'selected';}?>>Bosnia &amp; Herzegovina</option>
															<option value="BW" <?php if($site['country']=='BW'){echo'selected';}?>>Botswana</option>
															<option value="BR" <?php if($site['country']=='BR'){echo'selected';}?>>Brazil</option>
															<option value="BC" <?php if($site['country']=='BC'){echo'selected';}?>>British Indian Ocean Ter</option>
															<option value="BN" <?php if($site['country']=='BN'){echo'selected';}?>>Brunei</option>
															<option value="BG" <?php if($site['country']=='BG'){echo'selected';}?>>Bulgaria</option>
															<option value="BF" <?php if($site['country']=='BF'){echo'selected';}?>>Burkina Faso</option>
															<option value="BI" <?php if($site['country']=='BI'){echo'selected';}?>>Burundi</option>
															<option value="KH" <?php if($site['country']=='KH'){echo'selected';}?>>Cambodia</option>
															<option value="CM" <?php if($site['country']=='CM'){echo'selected';}?>>Cameroon</option>
															<option value="CA" <?php if($site['country']=='CA'){echo'selected';}?>>Canada</option>
															<option value="IC" <?php if($site['country']=='IC'){echo'selected';}?>>Canary Islands</option>
															<option value="CV" <?php if($site['country']=='CV'){echo'selected';}?>>Cape Verde</option>
															<option value="KY" <?php if($site['country']=='KY'){echo'selected';}?>>Cayman Islands</option>
															<option value="CF" <?php if($site['country']=='CF'){echo'selected';}?>>Central African Republic</option>
															<option value="TD" <?php if($site['country']=='TD'){echo'selected';}?>>Chad</option>
															<option value="CD" <?php if($site['country']=='CD'){echo'selected';}?>>Channel Islands</option>
															<option value="CL" <?php if($site['country']=='CL'){echo'selected';}?>>Chile</option>
															<option value="CN" <?php if($site['country']=='CN'){echo'selected';}?>>China</option>
															<option value="CI" <?php if($site['country']=='CI'){echo'selected';}?>>Christmas Island</option>
															<option value="CS" <?php if($site['country']=='CS'){echo'selected';}?>>Cocos Island</option>
															<option value="CO" <?php if($site['country']=='CO'){echo'selected';}?>>Colombia</option>
															<option value="CC" <?php if($site['country']=='CC'){echo'selected';}?>>Comoros</option>
															<option value="CG" <?php if($site['country']=='CG'){echo'selected';}?>>Congo</option>
															<option value="CK" <?php if($site['country']=='CK'){echo'selected';}?>>Cook Islands</option>
															<option value="CR" <?php if($site['country']=='CR'){echo'selected';}?>>Costa Rica</option>
															<option value="CT" <?php if($site['country']=='CT'){echo'selected';}?>>Cote D'Ivoire</option>
															<option value="HR" <?php if($site['country']=='HR'){echo'selected';}?>>Croatia</option>
															<option value="CU" <?php if($site['country']=='CU'){echo'selected';}?>>Cuba</option>
															<option value="CB" <?php if($site['country']=='CB'){echo'selected';}?>>Curacao</option>
															<option value="CY" <?php if($site['country']=='CY'){echo'selected';}?>>Cyprus</option>
															<option value="CZ" <?php if($site['country']=='CZ'){echo'selected';}?>>Czech Republic</option>
															<option value="DK" <?php if($site['country']=='DK'){echo'selected';}?>>Denmark</option>
															<option value="DJ" <?php if($site['country']=='DJ'){echo'selected';}?>>Djibouti</option>
															<option value="DM" <?php if($site['country']=='DM'){echo'selected';}?>>Dominica</option>
															<option value="DO" <?php if($site['country']=='DO'){echo'selected';}?>>Dominican Republic</option>
															<option value="TM" <?php if($site['country']=='TM'){echo'selected';}?>>East Timor</option>
															<option value="EC" <?php if($site['country']=='EC'){echo'selected';}?>>Ecuador</option>
															<option value="EG" <?php if($site['country']=='EG'){echo'selected';}?>>Egypt</option>
															<option value="SV" <?php if($site['country']=='SV'){echo'selected';}?>>El Salvador</option>
															<option value="GQ" <?php if($site['country']=='GQ'){echo'selected';}?>>Equatorial Guinea</option>
															<option value="ER" <?php if($site['country']=='ER'){echo'selected';}?>>Eritrea</option>
															<option value="EE" <?php if($site['country']=='EE'){echo'selected';}?>>Estonia</option>
															<option value="ET" <?php if($site['country']=='ET'){echo'selected';}?>>Ethiopia</option>
															<option value="FA" <?php if($site['country']=='FA'){echo'selected';}?>>Falkland Islands</option>
															<option value="FO" <?php if($site['country']=='DO'){echo'selected';}?>>Faroe Islands</option>
															<option value="FJ" <?php if($site['country']=='FJ'){echo'selected';}?>>Fiji</option>
															<option value="FI" <?php if($site['country']=='FI'){echo'selected';}?>>Finland</option>
															<option value="FR" <?php if($site['country']=='FR'){echo'selected';}?>>France</option>
															<option value="GF" <?php if($site['country']=='GF'){echo'selected';}?>>French Guiana</option>
															<option value="PF" <?php if($site['country']=='PF'){echo'selected';}?>>French Polynesia</option>
															<option value="FS" <?php if($site['country']=='FS'){echo'selected';}?>>French Southern Ter</option>
															<option value="GA" <?php if($site['country']=='GA'){echo'selected';}?>>Gabon</option>
															<option value="GM" <?php if($site['country']=='GM'){echo'selected';}?>>Gambia</option>
															<option value="GE" <?php if($site['country']=='GE'){echo'selected';}?>>Georgia</option>
															<option value="DE" <?php if($site['country']=='DE'){echo'selected';}?>>Germany</option>
															<option value="GH" <?php if($site['country']=='GH'){echo'selected';}?>>Ghana</option>
															<option value="GI" <?php if($site['country']=='GI'){echo'selected';}?>>Gibraltar</option>
															<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>Great Britain</option>
															<option value="GR" <?php if($site['country']=='GR'){echo'selected';}?>>Greece</option>
															<option value="GL" <?php if($site['country']=='GL'){echo'selected';}?>>Greenland</option>
															<option value="GD" <?php if($site['country']=='GD'){echo'selected';}?>>Grenada</option>
															<option value="GP" <?php if($site['country']=='GP'){echo'selected';}?>>Guadeloupe</option>
															<option value="GU" <?php if($site['country']=='GU'){echo'selected';}?>>Guam</option>
															<option value="GT" <?php if($site['country']=='GT'){echo'selected';}?>>Guatemala</option>
															<option value="GN" <?php if($site['country']=='GN'){echo'selected';}?>>Guinea</option>
															<option value="GY" <?php if($site['country']=='GY'){echo'selected';}?>>Guyana</option>
															<option value="HT" <?php if($site['country']=='HT'){echo'selected';}?>>Haiti</option>
															<option value="HW" <?php if($site['country']=='HW'){echo'selected';}?>>Hawaii</option>
															<option value="HN" <?php if($site['country']=='HN'){echo'selected';}?>>Honduras</option>
															<option value="HK" <?php if($site['country']=='HK'){echo'selected';}?>>Hong Kong</option>
															<option value="HU" <?php if($site['country']=='HU'){echo'selected';}?>>Hungary</option>
															<option value="IS" <?php if($site['country']=='IS'){echo'selected';}?>>Iceland</option>
															<option value="IN" <?php if($site['country']=='IN'){echo'selected';}?>>India</option>
															<option value="ID" <?php if($site['country']=='ID'){echo'selected';}?>>Indonesia</option>
															<option value="IA" <?php if($site['country']=='IA'){echo'selected';}?>>Iran</option>
															<option value="IQ" <?php if($site['country']=='IQ'){echo'selected';}?>>Iraq</option>
															<option value="IR" <?php if($site['country']=='IR'){echo'selected';}?>>Ireland</option>
															<option value="IM" <?php if($site['country']=='IM'){echo'selected';}?>>Isle of Man</option>
															<option value="IL" <?php if($site['country']=='IL'){echo'selected';}?>>Israel</option>
															<option value="IT" <?php if($site['country']=='IT'){echo'selected';}?>>Italy</option>
															<option value="JM" <?php if($site['country']=='JM'){echo'selected';}?>>Jamaica</option>
															<option value="JP" <?php if($site['country']=='JP'){echo'selected';}?>>Japan</option>
															<option value="JO" <?php if($site['country']=='JO'){echo'selected';}?>>Jordan</option>
															<option value="KZ" <?php if($site['country']=='KZ'){echo'selected';}?>>Kazakhstan</option>
															<option value="KE" <?php if($site['country']=='KE'){echo'selected';}?>>Kenya</option>
															<option value="KI" <?php if($site['country']=='KI'){echo'selected';}?>>Kiribati</option>
															<option value="NK" <?php if($site['country']=='NK'){echo'selected';}?>>Korea North</option>
															<option value="KS" <?php if($site['country']=='KS'){echo'selected';}?>>Korea South</option>
															<option value="KW" <?php if($site['country']=='KW'){echo'selected';}?>>Kuwait</option>
															<option value="KG" <?php if($site['country']=='KG'){echo'selected';}?>>Kyrgyzstan</option>
															<option value="LA" <?php if($site['country']=='LA'){echo'selected';}?>>Laos</option>
															<option value="LV" <?php if($site['country']=='LV'){echo'selected';}?>>Latvia</option>
															<option value="LB" <?php if($site['country']=='LB'){echo'selected';}?>>Lebanon</option>
															<option value="LS" <?php if($site['country']=='LS'){echo'selected';}?>>Lesotho</option>
															<option value="LR" <?php if($site['country']=='LR'){echo'selected';}?>>Liberia</option>
															<option value="LY" <?php if($site['country']=='LY'){echo'selected';}?>>Libya</option>
															<option value="LI" <?php if($site['country']=='LI'){echo'selected';}?>>Liechtenstein</option>
															<option value="LT" <?php if($site['country']=='LT'){echo'selected';}?>>Lithuania</option>
															<option value="LU" <?php if($site['country']=='LU'){echo'selected';}?>>Luxembourg</option>
															<option value="MO" <?php if($site['country']=='MO'){echo'selected';}?>>Macau</option>
															<option value="MK" <?php if($site['country']=='MK'){echo'selected';}?>>Macedonia</option>
															<option value="MG" <?php if($site['country']=='MG'){echo'selected';}?>>Madagascar</option>
															<option value="MY" <?php if($site['country']=='MY'){echo'selected';}?>>Malaysia</option>
															<option value="MW" <?php if($site['country']=='MW'){echo'selected';}?>>Malawi</option>
															<option value="MV" <?php if($site['country']=='MV'){echo'selected';}?>>Maldives</option>
															<option value="ML" <?php if($site['country']=='ML'){echo'selected';}?>>Mali</option>
															<option value="MT" <?php if($site['country']=='MT'){echo'selected';}?>>Malta</option>
															<option value="MH" <?php if($site['country']=='MH'){echo'selected';}?>>Marshall Islands</option>
															<option value="MQ" <?php if($site['country']=='MQ'){echo'selected';}?>>Martinique</option>
															<option value="MR" <?php if($site['country']=='MR'){echo'selected';}?>>Mauritania</option>
															<option value="MU" <?php if($site['country']=='MU'){echo'selected';}?>>Mauritius</option>
															<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Mayotte</option>
															<option value="MX" <?php if($site['country']=='MX'){echo'selected';}?>>Mexico</option>
															<option value="MI" <?php if($site['country']=='MI'){echo'selected';}?>>Midway Islands</option>
															<option value="MD" <?php if($site['country']=='MD'){echo'selected';}?>>Moldova</option>
															<option value="MC" <?php if($site['country']=='MC'){echo'selected';}?>>Monaco</option>
															<option value="MN" <?php if($site['country']=='MN'){echo'selected';}?>>Mongolia</option>
															<option value="MS" <?php if($site['country']=='MS'){echo'selected';}?>>Montserrat</option>
															<option value="MA" <?php if($site['country']=='MA'){echo'selected';}?>>Morocco</option>
															<option value="MZ" <?php if($site['country']=='MZ'){echo'selected';}?>>Mozambique</option>
															<option value="MM" <?php if($site['country']=='MM'){echo'selected';}?>>Myanmar</option>
															<option value="NA" <?php if($site['country']=='NA'){echo'selected';}?>>Nambia</option>
															<option value="NU" <?php if($site['country']=='NU'){echo'selected';}?>>Nauru</option>
															<option value="NP" <?php if($site['country']=='NP'){echo'selected';}?>>Nepal</option>
															<option value="AN" <?php if($site['country']=='AN'){echo'selected';}?>>Netherland Antilles</option>
															<option value="NL" <?php if($site['country']=='NL'){echo'selected';}?>>Netherlands (Holland, Europe)</option>
															<option value="NV" <?php if($site['country']=='NV'){echo'selected';}?>>Nevis</option>
															<option value="NC" <?php if($site['country']=='NC'){echo'selected';}?>>New Caledonia</option>
															<option value="NZ" <?php if($site['country']=='NZ'){echo'selected';}?>>New Zealand</option>
															<option value="NI" <?php if($site['country']=='NI'){echo'selected';}?>>Nicaragua</option>
															<option value="NE" <?php if($site['country']=='NE'){echo'selected';}?>>Niger</option>
															<option value="NG" <?php if($site['country']=='NG'){echo'selected';}?>>Nigeria</option>
															<option value="NW" <?php if($site['country']=='NW'){echo'selected';}?>>Niue</option>
															<option value="NF" <?php if($site['country']=='NF'){echo'selected';}?>>Norfolk Island</option>
															<option value="NO" <?php if($site['country']=='NO'){echo'selected';}?>>Norway</option>
															<option value="OM" <?php if($site['country']=='OM'){echo'selected';}?>>Oman</option>
															<option value="PK" <?php if($site['country']=='PK'){echo'selected';}?>>Pakistan</option>
															<option value="PW" <?php if($site['country']=='PW'){echo'selected';}?>>Palau Island</option>
															<option value="PS" <?php if($site['country']=='PS'){echo'selected';}?>>Palestine</option>
															<option value="PA" <?php if($site['country']=='PA'){echo'selected';}?>>Panama</option>
															<option value="PG" <?php if($site['country']=='PG'){echo'selected';}?>>Papua New Guinea</option>
															<option value="PY" <?php if($site['country']=='PY'){echo'selected';}?>>Paraguay</option>
															<option value="PE" <?php if($site['country']=='PE'){echo'selected';}?>>Peru</option>
															<option value="PH" <?php if($site['country']=='PH'){echo'selected';}?>>Philippines</option>
															<option value="PO" <?php if($site['country']=='PO'){echo'selected';}?>>Pitcairn Island</option>
															<option value="PL" <?php if($site['country']=='PL'){echo'selected';}?>>Poland</option>
															<option value="PT" <?php if($site['country']=='PT'){echo'selected';}?>>Portugal</option>
															<option value="PR" <?php if($site['country']=='PR'){echo'selected';}?>>Puerto Rico</option>
															<option value="QA" <?php if($site['country']=='WA'){echo'selected';}?>>Qatar</option>
															<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Republic of Montenegro</option>
															<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Republic of Serbia</option>
															<option value="RE" <?php if($site['country']=='RE'){echo'selected';}?>>Reunion</option>
															<option value="RO" <?php if($site['country']=='RO'){echo'selected';}?>>Romania</option>
															<option value="RU" <?php if($site['country']=='RU'){echo'selected';}?>>Russia</option>
															<option value="RW" <?php if($site['country']=='RW'){echo'selected';}?>>Rwanda</option>
															<option value="NT" <?php if($site['country']=='NT'){echo'selected';}?>>St Barthelemy</option>
															<option value="EU" <?php if($site['country']=='EU'){echo'selected';}?>>St Eustatius</option>
															<option value="HE" <?php if($site['country']=='HE'){echo'selected';}?>>St Helena</option>
															<option value="KN" <?php if($site['country']=='KN'){echo'selected';}?>>St Kitts-Nevis</option>
															<option value="LC" <?php if($site['country']=='LC'){echo'selected';}?>>St Lucia</option>
															<option value="MB" <?php if($site['country']=='MB'){echo'selected';}?>>St Maarten</option>
															<option value="PM" <?php if($site['country']=='PM'){echo'selected';}?>>St Pierre &amp; Miquelon</option>
															<option value="VC" <?php if($site['country']=='VC'){echo'selected';}?>>St Vincent &amp; Grenadines</option>
															<option value="SP" <?php if($site['country']=='SP'){echo'selected';}?>>Saipan</option>
															<option value="SO" <?php if($site['country']=='SO'){echo'selected';}?>>Samoa</option>
															<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>Samoa American</option>
															<option value="SM" <?php if($site['country']=='SM'){echo'selected';}?>>San Marino</option>
															<option value="ST" <?php if($site['country']=='ST'){echo'selected';}?>>Sao Tome &amp; Principe</option>
															<option value="SA" <?php if($site['country']=='SA'){echo'selected';}?>>Saudi Arabia</option>
															<option value="SN" <?php if($site['country']=='SN'){echo'selected';}?>>Senegal</option>
															<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Serbia</option>
															<option value="SC" <?php if($site['country']=='SC'){echo'selected';}?>>Seychelles</option>
															<option value="SL" <?php if($site['country']=='SL'){echo'selected';}?>>Sierra Leone</option>
															<option value="SG" <?php if($site['country']=='SG'){echo'selected';}?>>Singapore</option>
															<option value="SK" <?php if($site['country']=='SK'){echo'selected';}?>>Slovakia</option>
															<option value="SI" <?php if($site['country']=='SI'){echo'selected';}?>>Slovenia</option>
															<option value="SB" <?php if($site['country']=='SB'){echo'selected';}?>>Solomon Islands</option>
															<option value="OI" <?php if($site['country']=='OI'){echo'selected';}?>>Somalia</option>
															<option value="ZA" <?php if($site['country']=='ZA'){echo'selected';}?>>South Africa</option>
															<option value="ES" <?php if($site['country']=='ES'){echo'selected';}?>>Spain</option>
															<option value="LK" <?php if($site['country']=='LK'){echo'selected';}?>>Sri Lanka</option>
															<option value="SD" <?php if($site['country']=='SD'){echo'selected';}?>>Sudan</option>
															<option value="SR" <?php if($site['country']=='SR'){echo'selected';}?>>Suriname</option>
															<option value="SZ" <?php if($site['country']=='SZ'){echo'selected';}?>>Swaziland</option>
															<option value="SE" <?php if($site['country']=='SE'){echo'selected';}?>>Sweden</option>
															<option value="CH" <?php if($site['country']=='CH'){echo'selected';}?>>Switzerland</option>
															<option value="SY" <?php if($site['country']=='SY'){echo'selected';}?>>Syria</option>
															<option value="TA" <?php if($site['country']=='TA'){echo'selected';}?>>Tahiti</option>
															<option value="TW" <?php if($site['country']=='TW'){echo'selected';}?>>Taiwan</option>
															<option value="TJ" <?php if($site['country']=='TJ'){echo'selected';}?>>Tajikistan</option>
															<option value="TZ" <?php if($site['country']=='TZ'){echo'selected';}?>>Tanzania</option>
															<option value="TH" <?php if($site['country']=='TH'){echo'selected';}?>>Thailand</option>
															<option value="TG" <?php if($site['country']=='TG'){echo'selected';}?>>Togo</option>
															<option value="TK" <?php if($site['country']=='TK'){echo'selected';}?>>Tokelau</option>
															<option value="TO" <?php if($site['country']=='TO'){echo'selected';}?>>Tonga</option>
															<option value="TT" <?php if($site['country']=='TT'){echo'selected';}?>>Trinidad &amp; Tobago</option>
															<option value="TN" <?php if($site['country']=='TN'){echo'selected';}?>>Tunisia</option>
															<option value="TR" <?php if($site['country']=='TR'){echo'selected';}?>>Turkey</option>
															<option value="TU" <?php if($site['country']=='TU'){echo'selected';}?>>Turkmenistan</option>
															<option value="TC" <?php if($site['country']=='TC'){echo'selected';}?>>Turks &amp; Caicos Is</option>
															<option value="TV" <?php if($site['country']=='TV'){echo'selected';}?>>Tuvalu</option>
															<option value="UG" <?php if($site['country']=='UG'){echo'selected';}?>>Uganda</option>
															<option value="UA" <?php if($site['country']=='UA'){echo'selected';}?>>Ukraine</option>
															<option value="AE" <?php if($site['country']=='AE'){echo'selected';}?>>United Arab Emirates</option>
															<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>United Kingdom</option>
															<option value="US" <?php if($site['country']=='US'){echo'selected';}?>>United States of America</option>
															<option value="UY" <?php if($site['country']=='UY'){echo'selected';}?>>Uruguay</option>
															<option value="UZ" <?php if($site['country']=='UZ'){echo'selected';}?>>Uzbekistan</option>
															<option value="VU" <?php if($site['country']=='VU'){echo'selected';}?>>Vanuatu</option>
															<option value="VS" <?php if($site['country']=='VS'){echo'selected';}?>>Vatican City State</option>
															<option value="VE" <?php if($site['country']=='VE'){echo'selected';}?>>Venezuela</option>
															<option value="VN" <?php if($site['country']=='VN'){echo'selected';}?>>Vietnam</option>
															<option value="VB" <?php if($site['country']=='VB'){echo'selected';}?>>Virgin Islands (Brit)</option>
															<option value="VA" <?php if($site['country']=='VA'){echo'selected';}?>>Virgin Islands (USA)</option>
															<option value="WK" <?php if($site['country']=='WK'){echo'selected';}?>>Wake Island</option>
															<option value="WF" <?php if($site['country']=='WF'){echo'selected';}?>>Wallis &amp; Futana Is</option>
															<option value="YE" <?php if($site['country']=='YE'){echo'selected';}?>>Yemen</option>
															<option value="ZR" <?php if($site['country']=='ZR'){echo'selected';}?>>Zaire</option>
															<option value="ZM" <?php if($site['country']=='ZM'){echo'selected';}?>>Zambia</option>
															<option value="ZW" <?php if($site['country']=='ZW'){echo'selected';}?>>Zimbabwe</option>
															</select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-4">
													<label for="power_cost" class="col-lg-2 control-label">Power Cost</label>
													<div class="col-lg-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>
															<input type="text" name="power_cost" id="power_cost" class="form-control" value="<?php echo $site['power_cost']; ?>" placeholder="0.10" required>
															<span class="input-group-addon">per kWh</span>
														</div>
													</div>
												</div>
											
												<div class="form-group col-lg-4">
													<label for="max_amps" class="col-lg-4 control-label">Max AMPs</label>
													<div class="col-lg-8">
														<div class="input-group">
															<input type="text" name="max_amps" id="max_amps" class="form-control" value="<?php echo $site['max_amps']; ?>" placeholder="20" required>
															<span class="input-group-addon">Max AMP @ 80% load</span>
														</div>
													</div>
												</div>

												<div class="form-group col-lg-4">
													<label for="max_kilowatts" class="col-lg-4 control-label">Max kW</label>
													<div class="col-lg-8">
														<div class="input-group">
															<input type="text" name="max_kilowatts" id="max_kilowatts" class="form-control" value="<?php echo $site['max_amps']; ?>" placeholder="40" required>
															<span class="input-group-addon">Max kW @ 80% load</span>
														</div>
													</div>
												</div>
											</div>
											
											
											<div class="row">
												<div class="form-group col-lg-12">										
													<div class="pull-right">
														<button type="submit" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</form>
							  		</div>
							  		<div class="tab-pane" id="tab_4">
							  			<div class="row">
											<div class="col-lg-12">
												<div class="box box-primary">
													<div class="box-header with-border">
														<h3 class="box-title">Add IP Range</h3>
													</div><!-- /.box-header -->
													<div class="box-body">
														<form id="ip_range_add" action="actions.php?a=ip_range_add&site_id=<?php echo $site_id; ?>" method="post" class="form-horizontal">
															<div class="row">
																<div class="form-group col-lg-12">
																	<label for="name" class="col-lg-2 control-label">Name</label>
																	<div class="col-lg-10">
																		<input type="text" name="name" id="name" class="form-control" placeholder="Range 1" required>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="form-group col-lg-12">
																	<label for="ip_range" class="col-lg-2 control-label">IP Range</label>
																	<div class="col-lg-10">
																		<input type="text" name="ip_range" id="ip_range" class="form-control" placeholder="192.168.1.1" required>
																		<small><strong>Note:</strong> IP range should be in the following format. 192.168.1.1 or 23.92.223.1. This will instruct your controller to scan the entire range. If you enter 192.168.1.1 then it will scn 192.168.1.1 to 192.168.1.254 inclusive.</small>
																	</div>
																</div>
															</div>
															
															<div class="row">
																<div class="form-group col-lg-12 text-right">										
																	<button type="submit" class="btn btn-success">Add</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>												
										<div class="row">
											<div class="col-lg-12">
												<div class="box box-primary">
													<div class="box-header with-border">
														<h3 class="box-title">Existing IP Ranges</h3>
													</div><!-- /.box-header -->
													<div class="box-body">
														<?php if(is_array($site['ip_ranges'])){
															foreach($site['ip_ranges'] as $ip_range){ ?>	
																<form action="actions.php?a=ip_range_update&site_id=<?php echo $site_id; ?>&ip_range_id=<?php echo $ip_range['id']; ?>" method="post" class="form-horizontal">
																	<div class="form-group col-lg-12">
																		<div class="col-lg-7">
																			<input type="text" name="name" id="name" class="form-control" value="<?php echo $ip_range['name']; ?>" required>
																		</div>
																		<div class="col-lg-3">
																			<input type="text" name="ip_range" id="ip_range" class="form-control" value="<?php echo $ip_range['ip_range']; ?>" required>
																		</div>
																		<div class="text-right">
																			<button onclick="return confirm('If you changed the IP range then this may render some miners unavailable or report incorrect details. \n\nAre you sure?')" type="submit" class="btn btn-success pull-right">Save</button> &nbsp;
																			<a href="actions.php?a=ip_range_delete&site_id=<?php echo $site_id; ?>&ip_range_id=<?php echo $ip_range['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger pull-right">Delete</a>
																		</div>
																	</div>
																</form>
															<?php } ?>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
							  		</div>
							  		<div class="tab-pane" id="tab_5">
							  		</div>
								</div>
						  	</div>
						</div>
          			</div>
                </section>
            </div>
        <?php } ?>

        <?php function site_test(){ ?>
        	<?php global $account_details, $site; ?>
           	<?php $site_id 					= get('site_id'); ?>
           	<?php $view 					= get('view'); ?>
           	<?php $site 					= get_site($site_id); ?>
           	<?php $heatmap 					= build_heatmap_array($site_id); ?>
           	<?php $data['pools'] 			= get_pools(); ?>
           	<?php $customers 				= get_customers(); ?>

           	<div id="submitting_changes" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Saving Changes</h4>
						</div>
						<div class="modal-body text-center">
							<p>Your changes are being saved. Please stand by.</p>
						</div>
					</div>
				</div>
			</div>

           	<div id="step_add_ip_range" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Next Step</h4>
						</div>
						<div class="modal-body">
						<p>You need to add at least one IP range. Please add your IP ranges so that your controller knows where to look for your miners.</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
           	
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1><?php echo $site['name']; ?></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li><a href="<?php echo $site['url']; ?>/dashboard?c=sites">Sites</a></li>
                        <li class="active"><?php echo $site['name']; ?></li>
                    </ol>
                </section>
                
                <section class="content">
                	<?php if($account_details['gui_settings']['show_site_summary'] == 'yes'){ ?>
	                	<div class="row">
	                		<div class="col-md-2 col-md-offset-1 col-xs-6 col-xs-offset-0">
								<div class="box box-primary box-solid" id="projected_hashrate_sha256_box">
									<div class="box-header with-border">
										<h3 class="box-title">SHA256 Hashrate</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_hashrate_sha256"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="box box-primary box-solid" id="projected_hashrate_x11_box">
									<div class="box-header with-border">
										<h3 class="box-title">X11 Hashrate</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_hashrate_x11"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="box box-primary box-solid" id="projected_hashrate_scrypt_box">
									<div class="box-header with-border">
										<h3 class="box-title">SCRYPT Hashrate</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_hashrate_scrypt"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="box box-primary box-solid" id="projected_hashrate_eth_box">
									<div class="box-header with-border">
										<h3 class="box-title">ETH Hashrate</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_hashrate_eth"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-6">
								<div class="box box-primary box-solid" id="projected_hashrate_blake2b_box">
									<div class="box-header with-border">
										<h3 class="box-title">BLAKE2B Hashrate</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_hashrate_blake2b"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
	                	</div>
	                	<div class="row">
							<div class="col-md-4">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Power @ <?php echo $site['voltage']; ?>v</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_power_usage"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-2">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Avg Temp (PCB)</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_pcb_temp"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>

							<div class="col-md-2 col-xs-12">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Projected Revenue /mo</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_monthly_revenue"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Projected Power Cost /mo</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_monthly_power_cost"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title">Projected Profit /mo</h3>
									</div>
									<div class="box-body text-center">
										<h1>
											<span id="projected_monthly_profit"><img src="img/loading.gif" height="50px"></span>
										</h1>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
                	<div class="row">
						<div class="col-md-12">
						  	<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
							  		<li class="active"><a href="#tab_1" data-toggle="tab">Miners</a></li>
							  		<li><a href="#tab_2" data-toggle="tab">Heatmap</a></li>
							  		<li><a href="#tab_4" data-toggle="tab">IP Ranges</a></li>
							  		<!-- <li><a href="#tab_6" data-toggle="tab">ethOS Panels</a></li> -->
							  		<li><a href="#tab_3" data-toggle="tab">Settings</a></li>
							  		<?php if(isset($_GET['dev']) && $_GET['dev'] == 'yes'){ ?>
							  			<li><a href="#tab_5" data-toggle="tab">Dev</a></li>
							  		<?php } ?>
								</ul>
								<div class="tab-content">
							  		<div class="tab-pane active" id="tab_1">
										<form id="miner_update_multi" action="actions.php?a=miner_update_multi&site_id=<?php echo $site_id; ?>" method="post">
											<div class="row">
												<div class="col-md-4">
													<span id="multi_options_show" class="hidden">
														<div class="col-md-10">
															<select id="multi_options_action" name="multi_options_action" class="form-control" onchange="show_additional_options(this)">
																<optgroup>
																	<option value="reboot">Reboot Miners</option>
																	<option value="update">Update Miners</option>
																</optgroup>

																<optgroup>
																	<option value="pause">Pause Miners</option>
																	<option value="unpause">UN-Pause Miners</option>
																</optgroup>

																<optgroup>
																	<option value="set_fan_speed">Change Fan Speeds</option>
																</optgroup>

																<optgroup>
																	<option value="upgrade_s9">Install Antminer S9 MCP Firmware</option>
																	<option value="downgrade_s9">Remove Antminer S9 MCP Firmware</option>
																</optgroup>

																<optgroup>
																	<option value="set_pool">Change Pool</option>
																	<option value="set_owner">Change Owner</option>
																</optgroup>
															</select>
															<div id="dynamic_set_fan_speed" class="hidden">
																<select id="set_fan_speed" name="set_fan_speed" class="form-control">
																	<option value="10">10%</option>
																	<option value="20">20%</option>
																	<option value="30">30%</option>
																	<option value="40">40%</option>
																	<option value="50">50%</option>
																	<option value="60">60%</option>
																	<option value="70" selected="selected">70% (default)</option>
																	<option value="80">80%</option>
																	<option value="90">90%</option>
																	<option value="100">100%</option>
																</select>
															</div>
															<div id="dynamic_set_pool" class="hidden">
																<select id="set_pool_id" name="set_pool_id" class="form-control">
																	<?php 
																		foreach($data['pools'] as $pool)
																		{
																			echo '<option value="'.$pool['id'].'" '.($pool['id']==$data['miner']['active_pools']['0'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																		}
																	?>
																</select>
															</div>
															<div id="dynamic_set_owner" class="hidden">
																<?php if(is_array($customers)){ ?>
																	<select id="set_customer_id" name="set_customer_id" class="form-control" >
																		<option value="0">Dont assign to client</option>
																		<?php foreach($customers as $customer){ ?>
																			<option value="<?php echo $customer['id']; ?>"><?php echo $customer['first_name'].' '.$customer['last_name'].' ('.$customer['email'].')'; ?></option>
																		<?php } ?>
																	</select>
																<?php }else{ ?>
																	No customers added yet.
																<?php } ?>
															</div>
														</div>
														<div class="col-md-2">
															<!--
															<button type="submit" onclick="return confirm('This may take a little while. Don\'t close the browser or click anything until the confirmation alert is shown.')" class="btn btn-success">Go</button>
															-->
															<button type="submit" class="btn btn-success">Go</button>
														</div>
													</span>
												</div>

												<div class="col-md-8">
													<div class="col-md-8">

														<!-- <a href="<?php echo $site['url']; ?>/dashboard?c=site&site_id=13&type=asic" class="btn btn-success">ASIC Miners</a> -->
														<!-- <a href="<?php echo $site['url']; ?>/dashboard?c=site&site_id=13&type=gpu" class="btn btn-warning">GPU Miners</a> -->
													</div>
													<div class="col-md-4 text-right">
														<a href="actions.php?a=job_add&site_id=<?php echo $_GET['site_id']; ?>&miner_id=0&job=network_scan" class="btn btn-primary">Network Scan</a>
													</div>

													<!--
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>" class="btn btn-default">All</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=unknown" class="btn btn-info">Unknown</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=pending" class="btn btn-primary">Pending</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=offline" class="btn btn-warning">Offline</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=disconnected" class="btn btn-danger">Disconnected</a>
													<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=mining" class="btn btn-success">Mining</a>
													-->
												</div>
											</div>

											<div class="row"><hr></div>

											<div id="asic_miners">
												<style type="text/css">
													@media only screen and (max-width: 1000px) {
														.mobile_hidden{
															display: none;
														}
													}
												</style>
												<table id="asic_miners_table" class="table table-bordered table-striped">
													<thead>
														<tr>
															<th style="width: 5px;"><input type="checkbox" id="checkAll" /></th> 	<!-- // colum 1 -->
															<th class="mobile_hidden" style="width: 80px;">IP</th> 					<!-- // colum 2 -->
															<th style="width: 100px;">Name</th> 									<!-- // colum 3 -->
															<th class="mobile_hidden" style="width: 190;">Type</th> 				<!-- // colum 4 -->
															<th style="width: 60px;">Hash</th> 										<!-- // colum 5 -->
															<th class="mobile_hidden" style="width: 35px;">Temp</th> 				<!-- // colum 6 -->
															<th class="mobile_hidden" style="width: 200px;">Pool</th> 				<!-- // colum 7 -->
															<th>Status</th> 														<!-- // colum 8 -->
															<th class="mobile_hidden">Customer</th> 								<!-- // colum 9 -->
															<th class="mobile_hidden" style="width: 100px;">Updated</th> 			<!-- // colum 10 -->
															<th style="width: 20px;"></th> 						   					<!-- // colum 11 -->
														</tr>
													</thead>
													<tbody>
														<?php // show_miners_full($site_id); ?>
														<?php show_miners_ajax_template_test($site_id, 'all'); ?>
													</tbody>
												</table>
											</div>
										</form>
							  		</div>
							  		<div class="tab-pane" id="tab_2">
							  			<style>
							  				.grayout {
												/* display: none; */
												background-color: gray;
												opacity: .3;
											}
							  			</style>
							  			<script>
											function filter_heatmap_by_customer() {
											    var x = document.getElementById("heatmap_customer_id").value;
											    // document.getElementById("demo").innerHTML = "Customer ID: " + x;
											    // $(".miner_heatmap").hide();
											    // $(".miner_customer_id_" + x).show();

											    if(x == 'show_all'){
											    	$(".miner_heatmap").removeClass('grayout');
											    }else{
											    	$(".miner_heatmap").addClass('grayout');
											    	$(".miner_customer_id_" + x).removeClass('grayout');
											    }
											    
											    // $("#miner_customer_id_" + x).show();
											    // $("#miner_customer_id_" + x).removeClass('grayout');
											    // alert('miner_customer_id_' + x + ' has been selected.');
											}
										</script>
							  			<?php if(is_array($customers)){ ?>

							  				<div class="form-group">
                                        		<label for="heatmap_customer_id" class="col-sm-2 control-label">Select Customer</label>
                                        		<div class="col-sm-4">

													<select id="heatmap_customer_id" name="heatmap_customer_id" class="form-control" onchange="filter_heatmap_by_customer()">
														<option value="show_all">Show all miners</option>
														<?php foreach($customers as $customer){ ?>
															<option value="<?php echo $customer['id']; ?>"><?php echo $customer['first_name'].' '.$customer['last_name'].' ('.$customer['email'].')'; ?></option>
														<?php } ?>
													</select>
												</div>
                                    		</div>
										<?php }else{ ?>
											No customers added yet.
										<?php } ?>
										
										<p id="demo"></p>
										
										<br>
										<br>
										<hr>

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
																						<u><a href="?c=miner&miner_id='.$position['miner_id'].'">'.$position['miner_location'].'</a></u>
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
							  		<div class="tab-pane" id="tab_3">
							  			<form action="actions.php?a=site_update&site_id=<?php echo $site['id']; ?>" method="post" class="form-horizontal">
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="api_key" class="col-lg-2 control-label">API Key</label>
													<div class="col-lg-10">
														<div class="input-group">
															<input type="text" name="api_key" id="api_key" class="form-control" value="<?php echo $site['api_key']; ?>" onClick="this.select();" readonly>
															<span class="input-group-addon">Copy & Paste</span>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<label for="name" class="col-lg-2 control-label">Name</label>
													<div class="col-lg-10">
														<input type="text" name="name" id="name" class="form-control" value="<?php echo $site['name']; ?>" placeholder="Site X" required>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<label for="location" class="col-lg-2 control-label">Full Address</label>
													<div class="col-lg-10">
														<input type="text" name="location" id="location" class="form-control" value="<?php echo $site['location']['address']; ?>" placeholder="70 Monty Drive, Savannah, TN, 38372, United States">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="city" class="col-lg-2 control-label">Weather City</label>
													<div class="col-lg-10">
														<input type="text" name="city" id="city" class="form-control" value="<?php echo $site['city']; ?>" placeholder="Savannah" required>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<label for="country" class="col-lg-2 control-label">Weather Country</label>
													<div class="col-lg-10">
														<select name="country" id="country" class="form-control" placeholder="United States" required>
															<option value="" <?php if($site['country']==''){echo'selected';}?>>Select a Country</option>
															<option value="AF" <?php if($site['country']=='AF'){echo'selected';}?>>Afghanistan</option>
															<option value="AL" <?php if($site['country']=='AL'){echo'selected';}?>>Albania</option>
															<option value="DZ" <?php if($site['country']=='DZ'){echo'selected';}?>>Algeria</option>
															<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>American Samoa</option>
															<option value="AD" <?php if($site['country']=='AD'){echo'selected';}?>>Andorra</option>
															<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Angola</option>
															<option value="AI" <?php if($site['country']=='AI'){echo'selected';}?>>Anguilla</option>
															<option value="AG" <?php if($site['country']=='AG'){echo'selected';}?>>Antigua &amp; Barbuda</option>
															<option value="AR" <?php if($site['country']=='AR'){echo'selected';}?>>Argentina</option>
															<option value="AA" <?php if($site['country']=='AA'){echo'selected';}?>>Armenia</option>
															<option value="AW" <?php if($site['country']=='AW'){echo'selected';}?>>Aruba</option>
															<option value="AU" <?php if($site['country']=='AU'){echo'selected';}?>>Australia</option>
															<option value="AT" <?php if($site['country']=='AT'){echo'selected';}?>>Austria</option>
															<option value="AZ" <?php if($site['country']=='AZ'){echo'selected';}?>>Azerbaijan</option>
															<option value="BS" <?php if($site['country']=='BS'){echo'selected';}?>>Bahamas</option>
															<option value="BH" <?php if($site['country']=='BH'){echo'selected';}?>>Bahrain</option>
															<option value="BD" <?php if($site['country']=='BD'){echo'selected';}?>>Bangladesh</option>
															<option value="BB" <?php if($site['country']=='BB'){echo'selected';}?>>Barbados</option>
															<option value="BY" <?php if($site['country']=='BY'){echo'selected';}?>>Belarus</option>
															<option value="BE" <?php if($site['country']=='BE'){echo'selected';}?>>Belgium</option>
															<option value="BZ" <?php if($site['country']=='BZ'){echo'selected';}?>>Belize</option>
															<option value="BJ" <?php if($site['country']=='BJ'){echo'selected';}?>>Benin</option>
															<option value="BM" <?php if($site['country']=='BM'){echo'selected';}?>>Bermuda</option>
															<option value="BT" <?php if($site['country']=='BT'){echo'selected';}?>>Bhutan</option>
															<option value="BO" <?php if($site['country']=='BO'){echo'selected';}?>>Bolivia</option>
															<option value="BL" <?php if($site['country']=='BL'){echo'selected';}?>>Bonaire</option>
															<option value="BA" <?php if($site['country']=='BA'){echo'selected';}?>>Bosnia &amp; Herzegovina</option>
															<option value="BW" <?php if($site['country']=='BW'){echo'selected';}?>>Botswana</option>
															<option value="BR" <?php if($site['country']=='BR'){echo'selected';}?>>Brazil</option>
															<option value="BC" <?php if($site['country']=='BC'){echo'selected';}?>>British Indian Ocean Ter</option>
															<option value="BN" <?php if($site['country']=='BN'){echo'selected';}?>>Brunei</option>
															<option value="BG" <?php if($site['country']=='BG'){echo'selected';}?>>Bulgaria</option>
															<option value="BF" <?php if($site['country']=='BF'){echo'selected';}?>>Burkina Faso</option>
															<option value="BI" <?php if($site['country']=='BI'){echo'selected';}?>>Burundi</option>
															<option value="KH" <?php if($site['country']=='KH'){echo'selected';}?>>Cambodia</option>
															<option value="CM" <?php if($site['country']=='CM'){echo'selected';}?>>Cameroon</option>
															<option value="CA" <?php if($site['country']=='CA'){echo'selected';}?>>Canada</option>
															<option value="IC" <?php if($site['country']=='IC'){echo'selected';}?>>Canary Islands</option>
															<option value="CV" <?php if($site['country']=='CV'){echo'selected';}?>>Cape Verde</option>
															<option value="KY" <?php if($site['country']=='KY'){echo'selected';}?>>Cayman Islands</option>
															<option value="CF" <?php if($site['country']=='CF'){echo'selected';}?>>Central African Republic</option>
															<option value="TD" <?php if($site['country']=='TD'){echo'selected';}?>>Chad</option>
															<option value="CD" <?php if($site['country']=='CD'){echo'selected';}?>>Channel Islands</option>
															<option value="CL" <?php if($site['country']=='CL'){echo'selected';}?>>Chile</option>
															<option value="CN" <?php if($site['country']=='CN'){echo'selected';}?>>China</option>
															<option value="CI" <?php if($site['country']=='CI'){echo'selected';}?>>Christmas Island</option>
															<option value="CS" <?php if($site['country']=='CS'){echo'selected';}?>>Cocos Island</option>
															<option value="CO" <?php if($site['country']=='CO'){echo'selected';}?>>Colombia</option>
															<option value="CC" <?php if($site['country']=='CC'){echo'selected';}?>>Comoros</option>
															<option value="CG" <?php if($site['country']=='CG'){echo'selected';}?>>Congo</option>
															<option value="CK" <?php if($site['country']=='CK'){echo'selected';}?>>Cook Islands</option>
															<option value="CR" <?php if($site['country']=='CR'){echo'selected';}?>>Costa Rica</option>
															<option value="CT" <?php if($site['country']=='CT'){echo'selected';}?>>Cote D'Ivoire</option>
															<option value="HR" <?php if($site['country']=='HR'){echo'selected';}?>>Croatia</option>
															<option value="CU" <?php if($site['country']=='CU'){echo'selected';}?>>Cuba</option>
															<option value="CB" <?php if($site['country']=='CB'){echo'selected';}?>>Curacao</option>
															<option value="CY" <?php if($site['country']=='CY'){echo'selected';}?>>Cyprus</option>
															<option value="CZ" <?php if($site['country']=='CZ'){echo'selected';}?>>Czech Republic</option>
															<option value="DK" <?php if($site['country']=='DK'){echo'selected';}?>>Denmark</option>
															<option value="DJ" <?php if($site['country']=='DJ'){echo'selected';}?>>Djibouti</option>
															<option value="DM" <?php if($site['country']=='DM'){echo'selected';}?>>Dominica</option>
															<option value="DO" <?php if($site['country']=='DO'){echo'selected';}?>>Dominican Republic</option>
															<option value="TM" <?php if($site['country']=='TM'){echo'selected';}?>>East Timor</option>
															<option value="EC" <?php if($site['country']=='EC'){echo'selected';}?>>Ecuador</option>
															<option value="EG" <?php if($site['country']=='EG'){echo'selected';}?>>Egypt</option>
															<option value="SV" <?php if($site['country']=='SV'){echo'selected';}?>>El Salvador</option>
															<option value="GQ" <?php if($site['country']=='GQ'){echo'selected';}?>>Equatorial Guinea</option>
															<option value="ER" <?php if($site['country']=='ER'){echo'selected';}?>>Eritrea</option>
															<option value="EE" <?php if($site['country']=='EE'){echo'selected';}?>>Estonia</option>
															<option value="ET" <?php if($site['country']=='ET'){echo'selected';}?>>Ethiopia</option>
															<option value="FA" <?php if($site['country']=='FA'){echo'selected';}?>>Falkland Islands</option>
															<option value="FO" <?php if($site['country']=='DO'){echo'selected';}?>>Faroe Islands</option>
															<option value="FJ" <?php if($site['country']=='FJ'){echo'selected';}?>>Fiji</option>
															<option value="FI" <?php if($site['country']=='FI'){echo'selected';}?>>Finland</option>
															<option value="FR" <?php if($site['country']=='FR'){echo'selected';}?>>France</option>
															<option value="GF" <?php if($site['country']=='GF'){echo'selected';}?>>French Guiana</option>
															<option value="PF" <?php if($site['country']=='PF'){echo'selected';}?>>French Polynesia</option>
															<option value="FS" <?php if($site['country']=='FS'){echo'selected';}?>>French Southern Ter</option>
															<option value="GA" <?php if($site['country']=='GA'){echo'selected';}?>>Gabon</option>
															<option value="GM" <?php if($site['country']=='GM'){echo'selected';}?>>Gambia</option>
															<option value="GE" <?php if($site['country']=='GE'){echo'selected';}?>>Georgia</option>
															<option value="DE" <?php if($site['country']=='DE'){echo'selected';}?>>Germany</option>
															<option value="GH" <?php if($site['country']=='GH'){echo'selected';}?>>Ghana</option>
															<option value="GI" <?php if($site['country']=='GI'){echo'selected';}?>>Gibraltar</option>
															<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>Great Britain</option>
															<option value="GR" <?php if($site['country']=='GR'){echo'selected';}?>>Greece</option>
															<option value="GL" <?php if($site['country']=='GL'){echo'selected';}?>>Greenland</option>
															<option value="GD" <?php if($site['country']=='GD'){echo'selected';}?>>Grenada</option>
															<option value="GP" <?php if($site['country']=='GP'){echo'selected';}?>>Guadeloupe</option>
															<option value="GU" <?php if($site['country']=='GU'){echo'selected';}?>>Guam</option>
															<option value="GT" <?php if($site['country']=='GT'){echo'selected';}?>>Guatemala</option>
															<option value="GN" <?php if($site['country']=='GN'){echo'selected';}?>>Guinea</option>
															<option value="GY" <?php if($site['country']=='GY'){echo'selected';}?>>Guyana</option>
															<option value="HT" <?php if($site['country']=='HT'){echo'selected';}?>>Haiti</option>
															<option value="HW" <?php if($site['country']=='HW'){echo'selected';}?>>Hawaii</option>
															<option value="HN" <?php if($site['country']=='HN'){echo'selected';}?>>Honduras</option>
															<option value="HK" <?php if($site['country']=='HK'){echo'selected';}?>>Hong Kong</option>
															<option value="HU" <?php if($site['country']=='HU'){echo'selected';}?>>Hungary</option>
															<option value="IS" <?php if($site['country']=='IS'){echo'selected';}?>>Iceland</option>
															<option value="IN" <?php if($site['country']=='IN'){echo'selected';}?>>India</option>
															<option value="ID" <?php if($site['country']=='ID'){echo'selected';}?>>Indonesia</option>
															<option value="IA" <?php if($site['country']=='IA'){echo'selected';}?>>Iran</option>
															<option value="IQ" <?php if($site['country']=='IQ'){echo'selected';}?>>Iraq</option>
															<option value="IR" <?php if($site['country']=='IR'){echo'selected';}?>>Ireland</option>
															<option value="IM" <?php if($site['country']=='IM'){echo'selected';}?>>Isle of Man</option>
															<option value="IL" <?php if($site['country']=='IL'){echo'selected';}?>>Israel</option>
															<option value="IT" <?php if($site['country']=='IT'){echo'selected';}?>>Italy</option>
															<option value="JM" <?php if($site['country']=='JM'){echo'selected';}?>>Jamaica</option>
															<option value="JP" <?php if($site['country']=='JP'){echo'selected';}?>>Japan</option>
															<option value="JO" <?php if($site['country']=='JO'){echo'selected';}?>>Jordan</option>
															<option value="KZ" <?php if($site['country']=='KZ'){echo'selected';}?>>Kazakhstan</option>
															<option value="KE" <?php if($site['country']=='KE'){echo'selected';}?>>Kenya</option>
															<option value="KI" <?php if($site['country']=='KI'){echo'selected';}?>>Kiribati</option>
															<option value="NK" <?php if($site['country']=='NK'){echo'selected';}?>>Korea North</option>
															<option value="KS" <?php if($site['country']=='KS'){echo'selected';}?>>Korea South</option>
															<option value="KW" <?php if($site['country']=='KW'){echo'selected';}?>>Kuwait</option>
															<option value="KG" <?php if($site['country']=='KG'){echo'selected';}?>>Kyrgyzstan</option>
															<option value="LA" <?php if($site['country']=='LA'){echo'selected';}?>>Laos</option>
															<option value="LV" <?php if($site['country']=='LV'){echo'selected';}?>>Latvia</option>
															<option value="LB" <?php if($site['country']=='LB'){echo'selected';}?>>Lebanon</option>
															<option value="LS" <?php if($site['country']=='LS'){echo'selected';}?>>Lesotho</option>
															<option value="LR" <?php if($site['country']=='LR'){echo'selected';}?>>Liberia</option>
															<option value="LY" <?php if($site['country']=='LY'){echo'selected';}?>>Libya</option>
															<option value="LI" <?php if($site['country']=='LI'){echo'selected';}?>>Liechtenstein</option>
															<option value="LT" <?php if($site['country']=='LT'){echo'selected';}?>>Lithuania</option>
															<option value="LU" <?php if($site['country']=='LU'){echo'selected';}?>>Luxembourg</option>
															<option value="MO" <?php if($site['country']=='MO'){echo'selected';}?>>Macau</option>
															<option value="MK" <?php if($site['country']=='MK'){echo'selected';}?>>Macedonia</option>
															<option value="MG" <?php if($site['country']=='MG'){echo'selected';}?>>Madagascar</option>
															<option value="MY" <?php if($site['country']=='MY'){echo'selected';}?>>Malaysia</option>
															<option value="MW" <?php if($site['country']=='MW'){echo'selected';}?>>Malawi</option>
															<option value="MV" <?php if($site['country']=='MV'){echo'selected';}?>>Maldives</option>
															<option value="ML" <?php if($site['country']=='ML'){echo'selected';}?>>Mali</option>
															<option value="MT" <?php if($site['country']=='MT'){echo'selected';}?>>Malta</option>
															<option value="MH" <?php if($site['country']=='MH'){echo'selected';}?>>Marshall Islands</option>
															<option value="MQ" <?php if($site['country']=='MQ'){echo'selected';}?>>Martinique</option>
															<option value="MR" <?php if($site['country']=='MR'){echo'selected';}?>>Mauritania</option>
															<option value="MU" <?php if($site['country']=='MU'){echo'selected';}?>>Mauritius</option>
															<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Mayotte</option>
															<option value="MX" <?php if($site['country']=='MX'){echo'selected';}?>>Mexico</option>
															<option value="MI" <?php if($site['country']=='MI'){echo'selected';}?>>Midway Islands</option>
															<option value="MD" <?php if($site['country']=='MD'){echo'selected';}?>>Moldova</option>
															<option value="MC" <?php if($site['country']=='MC'){echo'selected';}?>>Monaco</option>
															<option value="MN" <?php if($site['country']=='MN'){echo'selected';}?>>Mongolia</option>
															<option value="MS" <?php if($site['country']=='MS'){echo'selected';}?>>Montserrat</option>
															<option value="MA" <?php if($site['country']=='MA'){echo'selected';}?>>Morocco</option>
															<option value="MZ" <?php if($site['country']=='MZ'){echo'selected';}?>>Mozambique</option>
															<option value="MM" <?php if($site['country']=='MM'){echo'selected';}?>>Myanmar</option>
															<option value="NA" <?php if($site['country']=='NA'){echo'selected';}?>>Nambia</option>
															<option value="NU" <?php if($site['country']=='NU'){echo'selected';}?>>Nauru</option>
															<option value="NP" <?php if($site['country']=='NP'){echo'selected';}?>>Nepal</option>
															<option value="AN" <?php if($site['country']=='AN'){echo'selected';}?>>Netherland Antilles</option>
															<option value="NL" <?php if($site['country']=='NL'){echo'selected';}?>>Netherlands (Holland, Europe)</option>
															<option value="NV" <?php if($site['country']=='NV'){echo'selected';}?>>Nevis</option>
															<option value="NC" <?php if($site['country']=='NC'){echo'selected';}?>>New Caledonia</option>
															<option value="NZ" <?php if($site['country']=='NZ'){echo'selected';}?>>New Zealand</option>
															<option value="NI" <?php if($site['country']=='NI'){echo'selected';}?>>Nicaragua</option>
															<option value="NE" <?php if($site['country']=='NE'){echo'selected';}?>>Niger</option>
															<option value="NG" <?php if($site['country']=='NG'){echo'selected';}?>>Nigeria</option>
															<option value="NW" <?php if($site['country']=='NW'){echo'selected';}?>>Niue</option>
															<option value="NF" <?php if($site['country']=='NF'){echo'selected';}?>>Norfolk Island</option>
															<option value="NO" <?php if($site['country']=='NO'){echo'selected';}?>>Norway</option>
															<option value="OM" <?php if($site['country']=='OM'){echo'selected';}?>>Oman</option>
															<option value="PK" <?php if($site['country']=='PK'){echo'selected';}?>>Pakistan</option>
															<option value="PW" <?php if($site['country']=='PW'){echo'selected';}?>>Palau Island</option>
															<option value="PS" <?php if($site['country']=='PS'){echo'selected';}?>>Palestine</option>
															<option value="PA" <?php if($site['country']=='PA'){echo'selected';}?>>Panama</option>
															<option value="PG" <?php if($site['country']=='PG'){echo'selected';}?>>Papua New Guinea</option>
															<option value="PY" <?php if($site['country']=='PY'){echo'selected';}?>>Paraguay</option>
															<option value="PE" <?php if($site['country']=='PE'){echo'selected';}?>>Peru</option>
															<option value="PH" <?php if($site['country']=='PH'){echo'selected';}?>>Philippines</option>
															<option value="PO" <?php if($site['country']=='PO'){echo'selected';}?>>Pitcairn Island</option>
															<option value="PL" <?php if($site['country']=='PL'){echo'selected';}?>>Poland</option>
															<option value="PT" <?php if($site['country']=='PT'){echo'selected';}?>>Portugal</option>
															<option value="PR" <?php if($site['country']=='PR'){echo'selected';}?>>Puerto Rico</option>
															<option value="QA" <?php if($site['country']=='WA'){echo'selected';}?>>Qatar</option>
															<option value="ME" <?php if($site['country']=='ME'){echo'selected';}?>>Republic of Montenegro</option>
															<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Republic of Serbia</option>
															<option value="RE" <?php if($site['country']=='RE'){echo'selected';}?>>Reunion</option>
															<option value="RO" <?php if($site['country']=='RO'){echo'selected';}?>>Romania</option>
															<option value="RU" <?php if($site['country']=='RU'){echo'selected';}?>>Russia</option>
															<option value="RW" <?php if($site['country']=='RW'){echo'selected';}?>>Rwanda</option>
															<option value="NT" <?php if($site['country']=='NT'){echo'selected';}?>>St Barthelemy</option>
															<option value="EU" <?php if($site['country']=='EU'){echo'selected';}?>>St Eustatius</option>
															<option value="HE" <?php if($site['country']=='HE'){echo'selected';}?>>St Helena</option>
															<option value="KN" <?php if($site['country']=='KN'){echo'selected';}?>>St Kitts-Nevis</option>
															<option value="LC" <?php if($site['country']=='LC'){echo'selected';}?>>St Lucia</option>
															<option value="MB" <?php if($site['country']=='MB'){echo'selected';}?>>St Maarten</option>
															<option value="PM" <?php if($site['country']=='PM'){echo'selected';}?>>St Pierre &amp; Miquelon</option>
															<option value="VC" <?php if($site['country']=='VC'){echo'selected';}?>>St Vincent &amp; Grenadines</option>
															<option value="SP" <?php if($site['country']=='SP'){echo'selected';}?>>Saipan</option>
															<option value="SO" <?php if($site['country']=='SO'){echo'selected';}?>>Samoa</option>
															<option value="AS" <?php if($site['country']=='AS'){echo'selected';}?>>Samoa American</option>
															<option value="SM" <?php if($site['country']=='SM'){echo'selected';}?>>San Marino</option>
															<option value="ST" <?php if($site['country']=='ST'){echo'selected';}?>>Sao Tome &amp; Principe</option>
															<option value="SA" <?php if($site['country']=='SA'){echo'selected';}?>>Saudi Arabia</option>
															<option value="SN" <?php if($site['country']=='SN'){echo'selected';}?>>Senegal</option>
															<option value="RS" <?php if($site['country']=='RS'){echo'selected';}?>>Serbia</option>
															<option value="SC" <?php if($site['country']=='SC'){echo'selected';}?>>Seychelles</option>
															<option value="SL" <?php if($site['country']=='SL'){echo'selected';}?>>Sierra Leone</option>
															<option value="SG" <?php if($site['country']=='SG'){echo'selected';}?>>Singapore</option>
															<option value="SK" <?php if($site['country']=='SK'){echo'selected';}?>>Slovakia</option>
															<option value="SI" <?php if($site['country']=='SI'){echo'selected';}?>>Slovenia</option>
															<option value="SB" <?php if($site['country']=='SB'){echo'selected';}?>>Solomon Islands</option>
															<option value="OI" <?php if($site['country']=='OI'){echo'selected';}?>>Somalia</option>
															<option value="ZA" <?php if($site['country']=='ZA'){echo'selected';}?>>South Africa</option>
															<option value="ES" <?php if($site['country']=='ES'){echo'selected';}?>>Spain</option>
															<option value="LK" <?php if($site['country']=='LK'){echo'selected';}?>>Sri Lanka</option>
															<option value="SD" <?php if($site['country']=='SD'){echo'selected';}?>>Sudan</option>
															<option value="SR" <?php if($site['country']=='SR'){echo'selected';}?>>Suriname</option>
															<option value="SZ" <?php if($site['country']=='SZ'){echo'selected';}?>>Swaziland</option>
															<option value="SE" <?php if($site['country']=='SE'){echo'selected';}?>>Sweden</option>
															<option value="CH" <?php if($site['country']=='CH'){echo'selected';}?>>Switzerland</option>
															<option value="SY" <?php if($site['country']=='SY'){echo'selected';}?>>Syria</option>
															<option value="TA" <?php if($site['country']=='TA'){echo'selected';}?>>Tahiti</option>
															<option value="TW" <?php if($site['country']=='TW'){echo'selected';}?>>Taiwan</option>
															<option value="TJ" <?php if($site['country']=='TJ'){echo'selected';}?>>Tajikistan</option>
															<option value="TZ" <?php if($site['country']=='TZ'){echo'selected';}?>>Tanzania</option>
															<option value="TH" <?php if($site['country']=='TH'){echo'selected';}?>>Thailand</option>
															<option value="TG" <?php if($site['country']=='TG'){echo'selected';}?>>Togo</option>
															<option value="TK" <?php if($site['country']=='TK'){echo'selected';}?>>Tokelau</option>
															<option value="TO" <?php if($site['country']=='TO'){echo'selected';}?>>Tonga</option>
															<option value="TT" <?php if($site['country']=='TT'){echo'selected';}?>>Trinidad &amp; Tobago</option>
															<option value="TN" <?php if($site['country']=='TN'){echo'selected';}?>>Tunisia</option>
															<option value="TR" <?php if($site['country']=='TR'){echo'selected';}?>>Turkey</option>
															<option value="TU" <?php if($site['country']=='TU'){echo'selected';}?>>Turkmenistan</option>
															<option value="TC" <?php if($site['country']=='TC'){echo'selected';}?>>Turks &amp; Caicos Is</option>
															<option value="TV" <?php if($site['country']=='TV'){echo'selected';}?>>Tuvalu</option>
															<option value="UG" <?php if($site['country']=='UG'){echo'selected';}?>>Uganda</option>
															<option value="UA" <?php if($site['country']=='UA'){echo'selected';}?>>Ukraine</option>
															<option value="AE" <?php if($site['country']=='AE'){echo'selected';}?>>United Arab Emirates</option>
															<option value="GB" <?php if($site['country']=='GB'){echo'selected';}?>>United Kingdom</option>
															<option value="US" <?php if($site['country']=='US'){echo'selected';}?>>United States of America</option>
															<option value="UY" <?php if($site['country']=='UY'){echo'selected';}?>>Uruguay</option>
															<option value="UZ" <?php if($site['country']=='UZ'){echo'selected';}?>>Uzbekistan</option>
															<option value="VU" <?php if($site['country']=='VU'){echo'selected';}?>>Vanuatu</option>
															<option value="VS" <?php if($site['country']=='VS'){echo'selected';}?>>Vatican City State</option>
															<option value="VE" <?php if($site['country']=='VE'){echo'selected';}?>>Venezuela</option>
															<option value="VN" <?php if($site['country']=='VN'){echo'selected';}?>>Vietnam</option>
															<option value="VB" <?php if($site['country']=='VB'){echo'selected';}?>>Virgin Islands (Brit)</option>
															<option value="VA" <?php if($site['country']=='VA'){echo'selected';}?>>Virgin Islands (USA)</option>
															<option value="WK" <?php if($site['country']=='WK'){echo'selected';}?>>Wake Island</option>
															<option value="WF" <?php if($site['country']=='WF'){echo'selected';}?>>Wallis &amp; Futana Is</option>
															<option value="YE" <?php if($site['country']=='YE'){echo'selected';}?>>Yemen</option>
															<option value="ZR" <?php if($site['country']=='ZR'){echo'selected';}?>>Zaire</option>
															<option value="ZM" <?php if($site['country']=='ZM'){echo'selected';}?>>Zambia</option>
															<option value="ZW" <?php if($site['country']=='ZW'){echo'selected';}?>>Zimbabwe</option>
														</select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-6">
													<label for="power_cost" class="col-lg-4 control-label">Power Cost</label>
													<div class="col-lg-6">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-dollar-sign"></i></span>
															<input type="text" name="power_cost" id="power_cost" class="form-control" value="<?php echo $site['power_cost']; ?>" placeholder="0.10" required>
															<span class="input-group-addon">per kWh</span>
														</div>
													</div>
												</div>

												<div class="form-group col-lg-6">
													<label for="max_amps" class="col-lg-4 control-label">Max AMPs</label>
													<div class="col-lg-6">
														<div class="input-group">
															<input type="text" name="max_amps" id="max_amps" class="form-control" value="<?php echo $site['max_amps']; ?>" placeholder="20" required>
															<span class="input-group-addon">Max AMP @ 80% load</span>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-6">
													<label for="max_kilowatts" class="col-lg-4 control-label">Max kW</label>
													<div class="col-lg-6">
														<div class="input-group">
															<input type="text" name="max_kilowatts" id="max_kilowatts" class="form-control" value="<?php echo $site['max_kilowatts']; ?>" placeholder="40" required>
															<span class="input-group-addon">Max kW @ 80% load</span>
														</div>
													</div>
												</div>

												<div class="form-group col-lg-6">
													<label for="voltage" class="col-lg-4 control-label">Voltage</label>
													<div class="col-lg-6">
														<div class="input-group">
															<input type="text" name="voltage" id="voltage" class="form-control" value="<?php echo $site['voltage']; ?>" placeholder="110" required>
															<span class="input-group-addon">v</span>
														</div>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">										
													<div class="pull-right">
														<button type="submit" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</form>
							  		</div>
							  		<div class="tab-pane" id="tab_4">
							  			<div class="row">
											<div class="col-lg-12">
												<div class="box box-primary">
													<div class="box-header with-border">
														<h3 class="box-title">Add IP Range</h3>
													</div><!-- /.box-header -->
													<div class="box-body">
														<form id="ip_range_add" action="actions.php?a=ip_range_add&site_id=<?php echo $site_id; ?>" method="post" class="form-horizontal">
															<div class="row">
																<div class="form-group col-lg-12">
																	<label for="name" class="col-lg-2 control-label">Name</label>
																	<div class="col-lg-10">
																		<input type="text" name="name" id="name" class="form-control" placeholder="Range 1" required>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="form-group col-lg-12">
																	<label for="ip_range" class="col-lg-2 control-label">IP Range</label>
																	<div class="col-lg-10">
																		<input type="text" name="ip_range" id="ip_range" class="form-control" placeholder="192.168.1.1" required>
																		<small><strong>Note:</strong> IP range should be in the following format. 192.168.1.1 or 23.92.223.1. This will instruct your controller to scan the entire range. If you enter 192.168.1.1 then it will scn 192.168.1.1 to 192.168.1.254 inclusive.</small>
																	</div>
																</div>
															</div>
															
															<div class="row">
																<div class="form-group col-lg-12 text-right">										
																	<button type="submit" class="btn btn-success">Add</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>												
										<div class="row">
											<div class="col-lg-12">
												<div class="box box-primary">
													<div class="box-header with-border">
														<h3 class="box-title">Existing IP Ranges</h3>
													</div><!-- /.box-header -->
													<div class="box-body">
														<?php if(is_array($site['ip_ranges'])){
															foreach($site['ip_ranges'] as $ip_range){ ?>	
																<form action="actions.php?a=ip_range_update&site_id=<?php echo $site_id; ?>&ip_range_id=<?php echo $ip_range['id']; ?>" method="post" class="form-horizontal">
																	<div class="form-group col-lg-12">
																		<div class="col-lg-7">
																			<input type="text" name="name" id="name" class="form-control" value="<?php echo $ip_range['name']; ?>" required>
																		</div>
																		<div class="col-lg-3">
																			<input type="text" name="ip_range" id="ip_range" class="form-control" value="<?php echo $ip_range['ip_range']; ?>" required data-inputmask="'alias': 'ip'" data-mask>
																		</div>
																		<div class="text-right">
																			<button onclick="return confirm('If you changed the IP range then this may render some miners unavailable or report incorrect details. \n\nAre you sure?')" type="submit" class="btn btn-success pull-right">Save</button> &nbsp;
																			<a href="actions.php?a=ip_range_delete&site_id=<?php echo $site_id; ?>&ip_range_id=<?php echo $ip_range['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger pull-right">Delete</a>
																		</div>
																	</div>
																</form>
															<?php } ?>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
							  		</div>
							  		<div class="tab-pane" id="tab_6">
							  			<div class="row">
											<div class="col-lg-12">
												<div class="box box-primary">
													<div class="box-header with-border">
														<h3 class="box-title">Add New ethOS Panel</h3>
													</div><!-- /.box-header -->
													<div class="box-body">
														<form id="ethos_add" action="actions.php?a=ethos_add&site_id=<?php echo $site_id; ?>" method="post" class="form-horizontal">
															<div class="form-group col-lg-12">
																<div class="form-group col-lg-4">
																	<label for="name" class="col-lg-2 control-label">Name</label>
																	<div class="col-lg-10">
																		<input type="text" name="name" id="name" class="form-control" placeholder="Site 1" required>
																	</div>
																</div>
																<div class="form-group col-lg-6">
																	<label for="panel_url" class="col-lg-2 control-label">Panel URL</label>
																	<div class="col-lg-10">
																		<div class="input-group">
																			<span class="input-group-addon">http://</span>
																			<input type="text" name="panel_url" id="panel_url" class="form-control" placeholder="panel_name" required>
																			<span class="input-group-addon">.ethosdistro.com</span>
																		</div>
																	</div>
																</div>
															
																<div class="col-lg-2 text-right">
																	<button type="submit" class="btn btn-success">Add</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
										</div>												
										<div class="row">
											<div class="col-lg-12">
												<div class="box box-primary">
													<div class="box-header with-border">
														<h3 class="box-title">Existing ethOS Panels</h3>
													</div><!-- /.box-header -->
													<div class="box-body">
														<?php if(is_array($site['ethos'])){
															foreach($site['ethos'] as $ethos){ ?>	
																<form action="actions.php?a=ethos_update&site_id=<?php echo $site_id; ?>&panel_id=<?php echo $ethos['id']; ?>" method="post" class="form-horizontal">
																	<div class="form-group col-lg-12">
																		<div class="form-group col-lg-4">
																			<label for="name" class="col-lg-2 control-label">Name</label>
																			<div class="col-lg-10">
																				<input type="text" name="name" id="name" class="form-control" placeholder="site 1" value="<?php echo $ethos['name']; ?>" required>
																			</div>
																		</div>

																		<div class="form-group col-lg-6">
																			<label for="panel_url" class="col-lg-2 control-label">Panel URL</label>
																			<div class="col-lg-10">
																				<div class="input-group">
																					<span class="input-group-addon">http://</span>
																					<input type="text" name="panel_url" id="panel_url" class="form-control" placeholder="panel_name" value="<?php echo $ethos['panel_url']; ?>" required>
																					<span class="input-group-addon">.ethosdistro.com</span>
																				</div>
																			</div>
																		</div>

																		<div class="col-lg-2 text-right">
																			<button type="submit" class="btn btn-success">Save</button> &nbsp;
																			<a href="actions.php?a=ethos_delete&site_id=<?php echo $site_id; ?>&panel_id=<?php echo $ethos['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
																		</div>
																	</div>
																</form>
															<?php } ?>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
							  		</div>
							  		<div class="tab-pane" id="tab_5">
							  		</div>
								</div>
						  	</div>
						</div>
          			</div>
                </section>
            </div>
        <?php } ?>
        
        <?php function miner(){ ?>
			<?php global $account_details, $site; ?>
           	<?php $data['coins'] 			= get_coins(); ?>
           	<?php $miner_id 				= get('miner_id'); ?>
           	<?php $data['miner']			= get_miner($miner_id, $_SESSION['account']['id']); ?>
           	<?php $data['site']				= get_site_short($data['miner']['site_id']); ?>
           	<?php $data['pool_profiles'] 	= get_pool_profiles(); ?>
           	<?php $data['pools'] 			= get_pools(); ?>
           	<?php $data['gpu_miners'] 		= get_gpu_miners(); ?>
           	<?php $customers 				= get_customers(); ?>

           	<?php $data['miner']['amps'] = number_format($data['miner']['watts'] / $data['site']['voltage'], 2); ?>

           	<?php 
				$config_file_url = 'miner_config_files/'.$miner_id.'.conf';
				$config_file = file_get_contents($config_file_url);
			?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Miner <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li><a href="<?php echo $site['url']; ?>/dashboard?c=sites">Sites</a></li>
                        <li><a href="<?php echo $site['url']; ?>/dashboard?c=site&site_id=<?php echo $data['site']['id']; ?>"><?php echo $data['site']['name']; ?></a></li>
                        <li class="active">Miner: <?php echo $data['miner']['name'] .' ('.$data['miner']['ip_address'].')'; ?></li>
                    </ol>
                </section>
                
                <section class="content">
                	<div class="row">
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Name</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php echo $data['miner']['name']; ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Hashrate</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php if($data['miner']['paused']=='no'){ ?>
											<?php echo $data['miner']['hashrate'] ?>
										<?php }else{ ?>
											Miner Paused
										<?php } ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Temp (PCB)</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php if($data['miner']['paused']=='no'){ ?>
											<?php if($account_details['temp_setting'] == 'c'){ ?>
												<?php echo $data['miner']['pcb_temp']; ?>
											<?php }else{ ?>
												<?php echo c_to_f($data['miner']['pcb_temp']); ?>
											<?php } ?>
										<?php }else{ ?>
											Miner Paused
										<?php } ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Projected Monthly Cost</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php if($data['miner']['paused']=='no'){ ?>
											$<?php echo $data['miner']['cost']; ?>
										<?php }else{ ?>
											Miner Paused
										<?php } ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Projected Monthly Profit</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php if($data['miner']['paused']=='no'){ ?>
											$<?php echo $data['miner']['profit']; ?>
										<?php }else{ ?>
											Miner Paused
										<?php } ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Actions</h3>
								</div>
								<div class="box-body text-center">
									<div class="row">
										<div class="col-md-6">
											<?php if($data['miner']['paused']=='no'){ ?>
												<a class="btn btn-danger full-width" href="actions.php?a=miner_pause_unpause&site_id=<?php echo $data['miner']['site_id']; ?>&miner_id=<?php echo $miner_id; ?>&action=pause_miner">Stop Mining</a>
											<?php }else{ ?>
												<a class="btn btn-success full-width" href="actions.php?a=miner_pause_unpause&site_id=<?php echo $data['miner']['site_id']; ?>&miner_id=<?php echo $miner_id; ?>&action=unpause_miner">Start Mining</a>
											<?php } ?>
										</div>
										<div class="col-md-6">
											<a class="btn btn-danger full-width" href="actions.php?a=job_add&site_id=<?php echo $data['miner']['site_id']; ?>&miner_id=<?php echo $miner_id; ?>&job=reboot_miner">Reboot</a>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<a class="btn btn-warning full-width" href="actions.php?a=miner_reset&site_id=<?php echo $data['miner']['site_id']; ?>&miner_id=<?php echo $miner_id; ?>" onclick="return confirm('Are you sure?\n\nThis CANNOT be undone and all configuration options will be reset to factory defaults.')">Reset</a>
										</div>
										<div class="col-md-6">
											<a class="btn btn-danger full-width" href="actions.php?a=miner_delete&site_id=<?php echo $data['miner']['site_id']; ?>&miner_id=<?php echo $miner_id; ?>" onclick="return confirm('Are you sure?\n\nThis CANNOT be undone and all related data will be removed. To add this miner again, you will have to perform a network scan.')">Delete</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
						  	<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
							  		<li class="active"><a href="#tab_1" data-toggle="tab">Overview</a></li>
							  		<!-- <li><a href="#tab_2" data-toggle="tab">Performance</a></li> -->
							  		<?php if($_SESSION['account']['type'] == 'admin'){ ?>
							  			<li><a href="#tab_3" data-toggle="tab">Settings</a></li>
							  			<?php if($data['miner']['type'] == 'asic'){ ?>
							  				<li><a href="#tab_4" data-toggle="tab">Config File</a></li>
							  				<li><a href="#tab_6" data-toggle="tab">Kernel Log</a></li>
							  			<?php } ?>
							  		<?php } ?>
							  		<?php if(isset($_GET['dev']) && $_GET['dev'] == 'yes'){ ?>
							  			<li><a href="#tab_5" data-toggle="tab">Dev</a></li>
							  		<?php } ?>
								</ul>
								<div class="tab-content">

							  		<div class="tab-pane active" id="tab_1">
							  			<div class="row">
							  				<?php if($data['miner']['paused']=='no'){ ?>
												<div class="col-md-3">
													<div class="box box-solid">
														<div class="box-header with-border">
															<i class="fa fa-text-width"></i>
															<h3 class="box-title">Overview</h3>
														</div><!-- /.box-header -->
														<div class="box-body">
															<dl class="dl-horizontal">
																<dt>Hardware</dt>
																<dd><?php echo $data['miner']['hardware']; ?></dd>
																
																<dt>Software</dt>
																<dd><?php echo $data['miner']['software_version']; ?></dd>

																<dt>IP Address</dt>
																<dd><?php echo $data['miner']['ip_address']; ?></dd>

																<dt>Frequency</dt>
																<dd><?php echo $data['miner']['frequency']; ?></dd>

																<dt>Power</dt>
																<dd><?php echo $data['miner']['kilowatts']; ?> kW</dd>
																<dd><?php echo $data['miner']['amps']; ?> AMPs</dd>
															</dl>
														</div>
													</div>
												</div>

												<div class="col-md-3">
													<div class="box box-solid">
														<div class="box-header with-border">
															<i class="fa fa-text-width"></i>
															<h3 class="box-title">Financial</h3>
														</div><!-- /.box-header -->
														<div class="box-body">
															<dl class="dl-horizontal">
																<dt>Revenue</dt>
																<dd>$<?php echo number_format($data['miner']['revenue'], 2); ?></dd>
																
																<dt>Power Cost</dt>
																<dd>$<?php echo number_format( $data['miner']['cost'], 2); ?></dd>

																<dt>Profit</dt>
																<dd>$<?php echo number_format( $data['miner']['profit'], 2); ?></dd>
															</dl>
														</div>
													</div>
												</div>

												<div class="col-md-3">
													<div class="box box-solid">
														<div class="box-header with-border">
															<i class="fa fa-text-width"></i>
															<h3 class="box-title">Temps</h3>
														</div><!-- /.box-header -->
														<div class="box-body">
															<dl class="dl-horizontal">
																<dt>PCB</dt>
																<dd>
																<?php if($account_details['temp_setting'] == 'c'){ ?>
																	<?php echo $data['miner']['pcb_temp_1']; ?> °C
																<?php }else{ ?>
																	<?php echo c_to_f($data['miner']['pcb_temp_1']); ?> °F
																<?php } ?> / 
																<?php if($account_details['temp_setting'] == 'c'){ ?>
																	<?php echo $data['miner']['pcb_temp_2']; ?> °C
																<?php }else{ ?>
																	<?php echo c_to_f($data['miner']['pcb_temp_2']); ?> °F
																<?php } ?> / 
																<?php if($account_details['temp_setting'] == 'c'){ ?>
																	<?php echo $data['miner']['pcb_temp_3']; ?> °C
																<?php }else{ ?>
																	<?php echo c_to_f($data['miner']['pcb_temp_3']); ?> °F
																<?php } ?>
																</dd>

																<dt>Chip</dt>
																<dd>
																<?php if($account_details['temp_setting'] == 'c'){ ?>
																	<?php echo $data['miner']['chip_temp_1']; ?> °C
																<?php }else{ ?>
																	<?php echo c_to_f($data['miner']['chip_temp_1']); ?> °F
																<?php } ?> / 
																<?php if($account_details['temp_setting'] == 'c'){ ?>
																	<?php echo $data['miner']['chip_temp_2']; ?> °C
																<?php }else{ ?>
																	<?php echo c_to_f($data['miner']['chip_temp_2']); ?> °F
																<?php } ?> / 
																<?php if($account_details['temp_setting'] == 'c'){ ?>
																	<?php echo $data['miner']['chip_temp_3']; ?> °C
																<?php }else{ ?>
																	<?php echo c_to_f($data['miner']['chip_temp_3']); ?> °F
																<?php } ?>
																</dd>

																<dt>Front Fan</dt>
																<dd>
																	<?php echo number_format($data['miner']['fan_1_speed']); ?> RPM
																</dd>
																<dt>Rear Fan</dt>
																<dd>
																	<?php echo number_format($data['miner']['fan_2_speed']); ?> RPM
																</dd>
															</dl>
														</div>
													</div>
												</div>

												<div class="col-md-3">
													<div class="box box-solid">
														<div class="box-header with-border">
															<i class="fa fa-text-width"></i>
															<h3 class="box-title">Mining Stats</h3>
														</div><!-- /.box-header -->
														<div class="box-body">
															<dl class="dl-horizontal">
																<dt>Currently Mining</dt>
																<dd><?php if(isset($data['miner']['coin'])){echo $data['miner']['coin'];}else{echo "Not Set";} ?></dd>

																<dt>Algorithm</dt>
																<dd><?php echo $data['miner']['algorithm']; ?></dd>
																
																<dt>Accepted</dt>
																<dd><?php echo number_format($data['miner']['accepted'], 0); ?></dd>

																<dt>Rejected</dt>
																<dd><?php echo number_format($data['miner']['rejected'], 0); ?></dd>

																<dt>Hardware Errors</dt>
																<dd><?php echo number_format($data['miner']['hardware_errors'], 0); ?></dd>
															</dl>
														</div>
													</div>
												</div>
											<?php }else{ ?>
												<div class="col-md-12 text-center">
													Miner Paused
												</div>
											<?php } ?>
							  			</div>
							  		</div>

							  		<div class="tab-pane" id="tab_2">
							  			<div class="col-md-12">
							  				<center>
												<a href="dashboard?c=miner&miner_id=<?php echo $miner_id; ?>" class="btn btn-primary">10 Minutes</a>
												<a href="dashboard?c=miner&miner_id=<?php echo $miner_id; ?>&miner_performance_minutes=60" class="btn btn-primary">1 Hour</a>
												<a href="dashboard?c=miner&miner_id=<?php echo $miner_id; ?>&miner_performance_minutes=720" class="btn btn-primary">12 Hours</a>
												<a href="dashboard?c=miner&miner_id=<?php echo $miner_id; ?>&miner_performance_minutes=1440" class="btn btn-primary">1 Day</a>
											</center>
										</div>

										<div id="miner_performance_history" style="min-width: 310px; height:100% !important; margin: 0 auto"></div>

										<div id="miner_performance_history_test" style="min-width: 310px; height:100% !important; margin: 0 auto"></div>

										<?php
											if(!isset($_GET['miner_performance_minutes']))
											{
												$miner_performance_minutes = 10;
											}else{
												$miner_performance_minutes = $_GET['miner_performance_minutes'];
											}

											if(!isset($_GET['miner_performance_minutes']))
											{
												$miner_performance_records = 0;
											}elseif($_GET['miner_performance_minutes'] == 60){
												$miner_performance_records = 10;
											}elseif($_GET['miner_performance_minutes'] == 720){
												$miner_performance_records = 60;
											}else{
												$miner_performance_records = 120;
											}

										?>
										<?php // $chart_data = miner_performance($miner_id, $miner_performance_minutes); ?>

										<script>
											Highcharts.chart('miner_performance_history', {
											    chart: {
											        type: 'area'
											    },
											    title: {
											        text: 'Hashrate History'
											    },
											    subtitle: {
											        // text: 'Source: WorldClimate.com'
											    },
											    xAxis: {
											        categories: [<?php echo $chart_data['dates']; ?>],
											        labels:{
											        	rotation: 45,
										                step: <?php echo $miner_performance_records; ?> // this will show every second label
										            }
											    },
											    yAxis: {
											        title: {
											            text: ''
											        },
											        labels: {
											            formatter: function () {
											                return this.value + '';
											            }
											        }
											    },
											    tooltip: {
											        crosshairs: true,
											        shared: true
											    },
											    plotOptions: {
											        spline: {
											            marker: {
											                radius: 4,
											                lineColor: '#666666',
											                lineWidth: 1
											            }
											        }
											    },
											    series: [{
											        name: 'Hashrate',
											        data: [<?php echo $chart_data['hashrate']; ?>]
											    },
											    {
											        name: 'Temp',
											        data: [<?php echo $chart_data['temp']; ?>]
											    }]
											});
										</script>
							  		</div>

							  		<div class="tab-pane" id="tab_3">
							  			<form action="actions.php?a=miner_update_owner&site_id=<?php echo $data['site']['id']; ?>&miner_id=<?php echo $miner_id; ?>" method="post" class="form-horizontal">
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="customer_id" class="col-lg-2 control-label">Assign to Customer</label>
													<div class="col-lg-10">
														<?php if(is_array($customers)){ ?>
															<select id="customer_id" name="customer_id" class="form-control" >
																<option value="0">Dont assign to client</option>
																<?php foreach($customers as $customer){ ?>
																	<option value="<?php echo $customer['id']; ?>" <?php if($customer['id']==$data['miner']['customer_id']){echo 'selected';} ?>><?php echo $customer['first_name'].' '.$customer['last_name'].' ('.$customer['email'].')'; ?></option>
																<?php } ?>
															</select>
														<?php }else{ ?>
															No customers added yet.
														<?php } ?>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">										
													<div class="pull-right">
														<button type="submit" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</form>

										<hr>

										<form action="actions.php?a=miner_update&site_id=<?php echo $data['site']['id']; ?>&miner_id=<?php echo $miner_id; ?>" method="post" class="form-horizontal">
											<div class="row">
												<div class="form-group col-lg-4">
													<label for="name" class="col-lg-3 control-label">Name</label>
													<div class="col-lg-9">
														<input type="text" name="name" id="name" class="form-control" value="<?php echo $data['miner']['name']; ?>">
													</div>
												</div>

												<div class="form-group col-lg-4">
													<label for="worker_name" class="col-lg-3 control-label">Worker Name</label>
													<div class="col-lg-9">
														<input type="text" name="worker_name" id="worker_name" class="form-control" value="<?php echo $data['miner']['worker_name']; ?>">
													</div>
												</div>
											
												<div class="form-group col-lg-4">
													<label for="ip_address" class="col-lg-3 control-label">IP Address</label>
													<div class="col-lg-9">
														<input type="text" name="ip_address" id="ip_address" class="form-control" value="<?php echo $data['miner']['ip_address']; ?>" required data-inputmask="'alias': 'ip'" data-mask>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-6">
													<label for="username" class="col-lg-2 control-label">Username</label>
													<div class="col-lg-10">
														<input type="text" name="username" id="username" class="form-control" value="<?php echo $data['miner']['username']; ?>" readonly>
													</div>
												</div>
											
												<div class="form-group col-lg-6">
													<label for="password" class="col-lg-2 control-label">Password</label>
													<div class="col-lg-10">
														<input type="text" name="password" id="password" class="form-control" value="<?php echo $data['miner']['password']; ?>" required>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-3">
													<label for="location_row" class="col-lg-4 control-label">Location > Row</label>
													<div class="col-lg-8">
														<input type="text" name="location_row" id="location_row" class="form-control" value="<?php echo $data['miner']['location_row']; ?>" placeholder="0" required>
													</div>
												</div>
											
												<div class="form-group col-lg-3">
													<label for="location_rack" class="col-lg-4 control-label">Rack</label>
													<div class="col-lg-8">
														<input type="text" name="location_rack" id="location_rack" class="form-control" value="<?php echo $data['miner']['location_rack']; ?>" placeholder="0" required>
													</div>
												</div>
											
												<div class="form-group col-lg-3">
													<label for="location_shelf" class="col-lg-4 control-label">Shelf</label>
													<div class="col-lg-8">
														<input type="text" name="location_shelf" id="location_shelf" class="form-control" value="<?php echo $data['miner']['location_shelf']; ?>" placeholder="0" required>
													</div>
												</div>
											
												<div class="form-group col-lg-3">
													<label for="location_position" class="col-lg-4 control-label">Position</label>
													<div class="col-lg-8">
														<input type="text" name="location_position" id="location_position" class="form-control" value="<?php echo $data['miner']['location_position']; ?>" placeholder="0" required>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-6">
													<label for="manual_fan_speed" class="col-lg-2 control-label">Fan Speed</label>
													<div class="col-lg-10">
														<select id="manual_fan_speed" name="manual_fan_speed" class="form-control" >
															<?php foreach(range(0, 100) as $fan_speed){
																echo '<option value="'.$fan_speed.'" '.($data['miner']['manual_fan_speed'] == $fan_speed ? 'selected' : '').'>'.$fan_speed.'%</option>';
															} ?>
														</select>
													</div>
												</div>

												<?php if($data['miner']['type'] == 'asic'){ ?>
													<div class="form-group col-lg-6">
														<label for="manual_freq" class="col-lg-1 control-label">Frequency</label>
														<div class="col-lg-11">
															<input type="text" name="manual_freq" id="manual_freq" class="form-control" value="<?php echo $data['miner']['manual_freq']; ?>" placeholder="0 = default">
														</div>
													</div>
												<?php } ?>
											</div>
											
											<?php if($data['miner']['type'] == 'asic'){ ?>
												<div class="row">
													<div class="form-group col-lg-12">
														<label for="pool_profile_id" class="col-lg-1 control-label">Pool Profile</label>
														<div class="col-lg-11">
															<?php if($data['pool_profiles']['status']=='success'){ ?>
																<select id="pool_profile_id" name="pool_profile_id" class="form-control" >
																	<option value="0">Select a Pool Profile / No Pool Profile</option>
																	<?php 
																		foreach($data['pool_profiles']['data'] as $pool_profile)
																		{
																			echo '<option value="'.$pool_profile['id'].'" '.(($pool_profile['id']==$data['miner']['pool_profile_id']) ? 'selected="selected"' : '').'>'.$pool_profile['name'].'</option>';
																		}
																	?>
																</select>
															<?php }else{ ?>
																No Pool Profiles added yet.
															<?php } ?>
														</div>
													</div>
												</div>
												
												<?php if($data['miner']['pool_profile_id'] != 0){ ?>
													<div class="row">
														<div class="form-group col-lg-12">
															<label for="pool_2" class="col-lg-1 control-label"></label>
															<div class="col-lg-11">
																The following pools are configured via the Pool Profile option above and are therefor disabled at this time. To set the pools one by one, then disable the above Pool Profile.
															</div>
														</div>
													</div>	
												<?php } ?>
												
												<div class="row">
													<div class="form-group col-lg-12">
														<label for="pool_0" class="col-lg-1 control-label">Pool 1</label>
														<div class="col-lg-11">
															<select id="pool_0" name="pool_0" class="form-control" <?php if($data['miner']['pool_profile_id'] != 0){ echo 'readonly';} ?>>
																<option value="0">No Pool Selected</option>
																<?php 
																	foreach($data['pools'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$data['miner']['active_pools']['0'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-lg-12">
														<label for="pool_1" class="col-lg-1 control-label">Pool 2</label>
														<div class="col-lg-11">
															<select id="pool_1" name="pool_1" class="form-control" <?php if($data['miner']['pool_profile_id'] != 0){ echo 'readonly';} ?>>>
																<option value="0">No Pool Selected</option>
																<?php 
																	foreach($data['pools'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$data['miner']['active_pools']['1'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>
												</div>

												<div class="row">
													<div class="form-group col-lg-12">
														<label for="pool_2" class="col-lg-1 control-label">Pool 3</label>
														<div class="col-lg-11">
															<select id="pool_2" name="pool_2" class="form-control" <?php if($data['miner']['pool_profile_id'] != 0){ echo 'readonly';} ?>>>
																<option value="0">No Pool Selected</option>
																<?php 
																	foreach($data['pools'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$data['miner']['active_pools']['2'] ? 'selected' : '').' >'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>
												</div>
											<?php }else{ ?>
												<div class="row">
													<div class="form-group col-lg-12">
														<label for="gpu_miner_software" class="col-lg-1 control-label">Miner Software</label>
														<div class="col-lg-11">
															<select id="gpu_miner_software" name="gpu_miner_software" class="form-control" >
																<?php 
																	foreach($data['gpu_miners'] as $gpu_miner)
																	{
																		echo '<option value="'.$gpu_miner['id'].'" '.(($gpu_miner['folder']==$data['miner']['software_version']) ? 'selected="selected"' : '').'>'.$gpu_miner['name'].' ('.$gpu_miner['folder'].')</option>';
																	}
																?>
															</select>
														</div>
													</div>
													<div class="form-group col-lg-12">
														<label for="pool_0_url" class="col-lg-1 control-label">Pool Server</label>
														<div class="col-lg-11">
															<input type="text" name="pool_0_url" id="pool_0_url" class="form-control" value="<?php echo $data['miner']['pools'][0]['url']; ?>" placeholder="pool.server.com:3333">
														</div>
													</div>
													<div class="form-group col-lg-12">
														<label for="pool_0_user" class="col-lg-1 control-label">Pool Username</label>
														<div class="col-lg-11">
															<input type="text" name="pool_0_user" id="pool_0_user" class="form-control" value="<?php echo $data['miner']['pools'][0]['user']; ?>" placeholder="username or crypto wallet address">
														</div>
													</div>
												</div>
											<?php } ?>

											
											<div class="row">
												<div class="form-group col-lg-12">										
													<div class="pull-right">
														<button type="submit" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</form>
							  		</div>

							  		<div class="tab-pane" id="tab_4">
										<?php if(!empty($config_file)){ ?>
											<pre><?php echo $config_file; ?></pre>
										<?php } ?>
							  		</div>
							  		<div class="tab-pane" id="tab_5">
							  			<?php debug($data); ?>
							  		</div>
							  		<div class="tab-pane" id="tab_6">
										<?php if(!empty($data['miner']['kernel_log'])){ ?>
											<pre><?php echo $data['miner']['kernel_log']; ?></pre>
										<?php } ?>
							  		</div>
							  	</div>
							</div>
						</div>
					</div>
				</section>
            </div>
        <?php } ?>
        
        <?php function pools(){ ?>
			<?php global $account_details, $site; ?>
           	<?php $coins 					= get_coins(); ?>
           	<?php $pools['sha256'] 			= get_pools_default_pool('sha256'); ?>
           	<?php $pools['x11'] 			= get_pools_default_pool('x11'); ?>
           	<?php $pools['scrupt'] 			= get_pools_default_pool('scrypt'); ?>
           	<?php $pools['blake2b'] 		= get_pools_default_pool('blake2b'); ?>

           	<?php $default_pool['sha256']	= get_default_pools('sha256'); ?>
           	<?php $default_pool['x11']		= get_default_pools('x11'); ?>
           	<?php $default_pool['scrypt']	= get_default_pools('scrypt'); ?>
           	<?php $default_pool['blake2b']	= get_default_pools('blake2b'); ?>

            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Pools <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Pools</li>
                    </ol>
                </section>
                
                <section class="content">
                	<div class="row">
						<div class="col-md-12">
					  		<table class="invalid" border="0">
					  			<tr>
					  				<td></td>
					  			</tr>
					  		</table>
						  	<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
							  		<li class="active"><a href="#tab_1" data-toggle="tab">Pools</a></li>
							  		<li><a href="#tab_2" data-toggle="tab">Pool Profiles</a></li>
							  		<li><a href="#tab_3" data-toggle="tab">Antminer Default Pools</a></li>
							  		<li><a href="#tab_4" data-toggle="tab">Add Pool / Profile</a></li>
								</ul>
								<div class="tab-content">
							  		<div class="tab-pane active" id="tab_1">
										<table id="pools" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Name</th>
													<th>Url</th>
													<th>Username</th>
													<th></th>
													<th width="150px"></th>
												</tr>
											</thead>
											<tbody>
												<?php show_pools(); ?>
											</tbody>
										</table>
							  		</div>
							  		
							  		<div class="tab-pane" id="tab_2">
										<table id="pool_profiles" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Name</th>
													<th>Pool 1</th>
													<th>Pool 2</th>
													<th>Pool 3</th>
													<th width="150px"></th>
												</tr>
											</thead>
											<tbody>
												<?php show_pool_profiles(); ?>
											</tbody>
										</table>
							  		</div>
							  		
							  		<div class="tab-pane" id="tab_3">
								  		<h4>Antminer Default Pools</h4>
								  		<p>The following are default pools which will be used when a new miner is detected. This helps to auto provision miners quickly and effectively.</p>
								  		<div class="row">
											<form action="actions.php?a=default_pool_update" method="post" class="form-horizontal">
												<input type="hidden" name="algorithm" value="sha256">
												<div class="form-group col-lg-12">
													<div class="form-group col-lg-2">
														<label for="sha256" class="col-lg-12 control-label">SHA256 Miners</label>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_0" class="col-lg-5 control-label">Pool 1</label>
														<div class="col-lg-7">
															<select id="pool_0" name="pool_0" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['sha256'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['sha256']['pool_0'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_1" class="col-lg-5 control-label">Pool 2</label>
														<div class="col-lg-7">
															<select id="pool_1" name="pool_1" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['sha256'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['sha256']['pool_1'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_2" class="col-lg-5 control-label">Pool 3</label>
														<div class="col-lg-7">
															<select id="pool_3" name="pool_2" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['sha256'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['sha256']['pool_2'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="text-right">
														<button type="submit" class="btn btn-success">Save</button> &nbsp;
														<a href="actions.php?a=customer_delete&customer_id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
													</div>
												</div>
											</form>
										</div>

										<div class="row">
											<form action="actions.php?a=default_pool_update" method="post" class="form-horizontal">
												<input type="hidden" name="algorithm" value="x11">
												<div class="form-group col-lg-12">
													<div class="form-group col-lg-2">
														<label for="sha256" class="col-lg-12 control-label">X11 Miners</label>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_0" class="col-lg-5 control-label">Pool 1</label>
														<div class="col-lg-7">
															<select id="pool_0" name="pool_0" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['x11'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['x11']['pool_0'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_1" class="col-lg-5 control-label">Pool 2</label>
														<div class="col-lg-7">
															<select id="pool_1" name="pool_1" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['x11'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['x11']['pool_1'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_2" class="col-lg-5 control-label">Pool 3</label>
														<div class="col-lg-7">
															<select id="pool_2" name="pool_2" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['x11'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['x11']['pool_2'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="text-right">
														<button type="submit" class="btn btn-success">Save</button> &nbsp;
														<a href="actions.php?a=customer_delete&customer_id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
													</div>
												</div>
											</form>
										</div>

										<div class="row">
											<form action="actions.php?a=default_pool_update" method="post" class="form-horizontal">
												<input type="hidden" name="algorithm" value="x11">
												<div class="form-group col-lg-12">
													<div class="form-group col-lg-2">
														<label for="sha256" class="col-lg-12 control-label">Scrypt Miners</label>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_0" class="col-lg-5 control-label">Pool 1</label>
														<div class="col-lg-7">
															<select id="pool_0" name="pool_0" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['scrypt'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['scrypt']['pool_0'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_1" class="col-lg-5 control-label">Pool 2</label>
														<div class="col-lg-7">
															<select id="pool_1" name="pool_1" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['scrypt'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['scrypt']['pool_1'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_2" class="col-lg-5 control-label">Pool 3</label>
														<div class="col-lg-7">
															<select id="pool_2" name="pool_2" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['scrypt'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['scrypt']['pool_2'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="text-right">
														<button type="submit" class="btn btn-success">Save</button> &nbsp;
														<a href="actions.php?a=customer_delete&customer_id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
													</div>
												</div>
											</form>
										</div>

										<div class="row">
											<form action="actions.php?a=default_pool_update" method="post" class="form-horizontal">
												<input type="hidden" name="algorithm" value="blake2b">
												<div class="form-group col-lg-12">
													<div class="form-group col-lg-2">
														<label for="sha256" class="col-lg-12 control-label">Blake2b Miners</label>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_0" class="col-lg-5 control-label">Pool 1</label>
														<div class="col-lg-7">
															<select id="pool_0" name="pool_0" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['blake2b'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['blake2b']['pool_0'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_1" class="col-lg-5 control-label">Pool 2</label>
														<div class="col-lg-7">
															<select id="pool_1" name="pool_1" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['blake2b'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['blake2b']['pool_1'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="pool_2" class="col-lg-5 control-label">Pool 3</label>
														<div class="col-lg-7">
															<select id="pool_2" name="pool_2" class="form-control">
																<option value="0">Non / Select a Pool</option>
																<?php 
																	foreach($pools['blake2b'] as $pool)
																	{
																		echo '<option value="'.$pool['id'].'" '.($pool['id']==$default_pool['blake2b']['pool_2'] ? 'selected' : '').'>'.$pool['name'].'</option>';
																	}
																?>
															</select>
														</div>
													</div>

													<div class="text-right">
														<button type="submit" class="btn btn-success">Save</button> &nbsp;
														<a href="actions.php?a=customer_delete&customer_id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
													</div>
												</div>
											</form>
										</div>
							  		</div>
							  		
							  		<div class="tab-pane" id="tab_4">
							  			<h4>Add Pool</h4>
										<form action="actions.php?a=pool_add" method="post" class="form-horizontal">
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="name" class="col-lg-3 control-label">Name</label>
													<div class="col-lg-9">
														<input type="text" name="name" id="name" class="form-control" placeholder="Awesome Pool" required>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="pre_set_pool" class="col-lg-3 control-label">Popular Pools</label>
													<div class="col-lg-9">
														<select id="pre_set_pool" name="pre_set_pool" class="form-control" onchange="func()">
															<option value="poolserver.com:3333">Custom</option>
															<optgroup label="SHA256">
																<option value="stratum.antpool.com:3333">AntPool.com - Port 3333</option>
																<option disabled="disabled">----</option>
																<option value="eu.ss.btc.com:1800">BTC.com (EU) - Port 1800</option>
																<option value="us.ss.btc.com:1800">BTC.com (US) - Port 1800</option>
																<option disabled="disabled">----</option>
																<option value="sha256.br.nicehash.com:3334#xnsub">Nicehash.com (Brazil) - Port 3334</option>
																<option value="sha256.eu.nicehash.com:3334#xnsub">Nicehash.com (EU) - Port 3334</option>
																<option value="sha256.hk.nicehash.com:3334#xnsub">Nicehash.com (China) - Port 3334</option>
																<option value="sha256.jp.nicehash.com:3334#xnsub">Nicehash.com (Japan) - Port 3334</option>
																<option value="sha256.usa.nicehash.com:3334#xnsub">Nicehash.com (USA) - Port 3334</option>
																<option disabled="disabled">----</option>
																<option value="btc.viabtc.com:3333">BTC @ ViaBTC.com - Port 3333</option>
																<option value="btc.viabtc.com:443">BTC @ ViaBTC.com - Port 443</option>
																<option value="btc.viabtc.com:25">BTC @ ViaBTC.com - Port 25</option>
																<option disabled="disabled">----</option>
																<option value="bch.viabtc.com:3333">BCH @ ViaBTC.com - Port 3333</option>
																<option value="bch.viabtc.com:443">BCH @ ViaBTC.com - Port 443</option>
																<option value="bch.viabtc.com:25">BCH @ ViaBTC.com - Port 25</option>
															</optgroup>
															<optgroup label="Scrypt">
																<option value="stratum-dash.antpool.com:6099">AntPool.com - Port 6099</option>
																<option disabled="disabled">----</option>
																<option value="scrypt.br.nicehash.com:3334#xnsub">Nicehash.com (Brazil) - Port 3334</option>
																<option value="scrypt.eu.nicehash.com:3334#xnsub">Nicehash.com (EU) - Port 3334</option>
																<option value="scrypt.hk.nicehash.com:3334#xnsub">Nicehash.com (China) - Port 3334</option>
																<option value="scrypt.jp.nicehash.com:3334#xnsub">Nicehash.com (Japan) - Port 3334</option>
																<option value="scrypt.usa.nicehash.com:3334#xnsub">Nicehash.com (USA) - Port 3334</option>
																<option disabled="disabled">----</option>
																<option value="ltc.viabtc.com:3333">ViaBTC.com - Port 3333</option>
																<option value="ltc.viabtc.com:443">ViaBTC.com - Port 443</option>
																<option value="ltc.viabtc.com:25">ViaBTC.com - Port 25</option>
															</optgroup>
															<optgroup label="X11">
																<option value="stratum-dash.antpool.com:6099">AntPool.com - Port 6099</option>
																<option disabled="disabled">----</option>
																<option value="x11.br.nicehash.com:3336#xnsub">Nicehash.com (Brazil) - Port 3336</option>
																<option value="x11.eu.nicehash.com:3336#xnsub">Nicehash.com (EU) - Port 3336</option>
																<option value="x11.hk.nicehash.com:3336#xnsub">Nicehash.com (China) - Port 3336</option>
																<option value="x11.jp.nicehash.com:3336#xnsub">Nicehash.com (Japan) - Port 3336</option>
																<option value="x11.usa.nicehash.com:3336#xnsub">Nicehash.com (USA) - Port 3336</option>
																<option disabled="disabled">----</option>
																<option value="dash.viabtc.com:3333">ViaBTC.com - Port 3333</option>
																<option value="dash.viabtc.com:443">ViaBTC.com - Port 443</option>
																<option value="dash.viabtc.com:25">ViaBTC.com - Port 25</option>
															</optgroup>
															<optgroup label="Blake2b">
																<option value="stratum-sc.antpool.com:7777">AntPool.com - Port 7777</option>
																<option value="stratum-sc.antpool.com:443">AntPool.com - Port 443</option>
																<option value="stratum-sc.antpool.com:25">AntPool.com - Port 25</option>
																<option disabled="disabled">----</option>
																<option value="asia.siamining.com:3333">SiaMining.com (Asia/Pacific) - Port 3333</option>
																<option value="eu.luxor.tech:3333">Luxar.com (EU) - Port 3333</option>
																<option value="us-east.luxor.tech:3333">Luxar.com (US East) - Port 3333</option>
																<option value="us-west.luxor.tech:3333">Luxar.com (US West) - Port 3333</option>
																<option disabled="disabled">----</option>
																<option value="asia.siamining.com:3333">SiaMining.com (Asia/Pacific) - Port 3333</option>
																<option value="us-west.siamining.com:3333">SiaMining.com (EU) - Port 3333</option>
																<option value="us-east.siamining.com:3333">SiaMining.com (US East) - Port 3333</option>
																<option value="us-west.siamining.com:3333">SiaMining.com (US West) - Port 3333</option>
															</optgroup>
														</select>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="url" class="col-lg-3 control-label">Pool Server URL</label>
													<div class="col-lg-9">
														<input type="text" name="url" id="url" class="form-control" placeholder="stratum.pool.com" required>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="port" class="col-lg-3 control-label">Pool Server Port</label>
													<div class="col-lg-9">
														<input type="text" name="port" id="port" class="form-control" placeholder="3334" required>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="username" class="col-lg-3 control-label">Pool Username</label>
													<div class="col-lg-9">
														<input type="text" name="username" id="username" class="form-control" placeholder="my_account_name" required>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="password" class="col-lg-3 control-label">Pool Password</label>
													<div class="col-lg-9">
														<input type="text" name="password" id="password" class="form-control" placeholder="123">
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="coin_id" class="col-lg-3 control-label">Currency</label>
													<div class="col-lg-9">
														<select id="coin_id" name="coin_id" class="form-control" onchange="func()">
															<?php 
							   									foreach($coins as $coin)
																	{
																		echo '<option value="'.$coin['id'].'">'.$coin['name'].'</option>';
																	}
							   								?>
														</select>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<div class="pull-right">
														<button type="submit" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</form>
										
										<hr>
										<h4>Add PoolProfile</h4>
										<form action="actions.php?a=pool_profile_add" method="post" class="form-horizontal">
											<div class="row">
												<div class="form-group col-lg-12">
													<label for="name" class="col-lg-3 control-label">Name</label>
													<div class="col-lg-9">
														<input type="text" name="name" id="name" class="form-control" placeholder="Awesome Pool" required>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="form-group col-lg-12">
													<div class="pull-right">
														<button type="submit" class="btn btn-success">Save</button>
													</div>
												</div>
											</div>
										</form>
										
										<script>
											function func(){
												var dropdown = document.getElementById("pre_set_pool");
												var selection = dropdown.value;
												console.log(selection);
												var pool_url_field = document.getElementById("url");
												var pool_port_field = document.getElementById("port");
												
												var pool_url_bits = selection.split(":");
												
												pool_url_field.value = pool_url_bits[0];
												pool_port_field.value = pool_url_bits[1];
											}
										</script>
							  		</div>
								</div>
						  	</div>
						</div>
          			</div> 
                </section>
            </div>
        <?php } ?>
        
        <?php function pool_profile(){ ?>
			<?php global $account_details, $site; ?>
           	<?php $coins = get_coins(); ?>
           	<?php $profile_id = get('profile_id'); ?>
           	<?php $pool_profile = get_pool_profile($profile_id); ?>
           	<?php $pools = get_pools(); ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Pool Profile <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li><a href="<?php echo $site['url']; ?>/dashboard?c=pools">Pools</a></li>
                        <li class="active">Pool Profile</li>
                    </ol>
                </section>
                
                <section class="content">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Pool Profile</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<form action="actions.php?a=pool_profile_update&profile_id=<?php echo $profile_id; ?>" method="post" class="form-horizontal">
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="name" class="col-lg-2 control-label">Name</label>
										<div class="col-lg-10">
											<input type="text" name="name" id="name" class="form-control" value="<?php echo $pool_profile['name']; ?>" required>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="pool_0" class="col-lg-2 control-label">Pool 1</label>
										<div class="col-lg-10">
											<select id="pool_0" name="pool_0" class="form-control">
												<option value="0">Select a Pool / No Pool Needed</option>
												<?php 
													foreach($pools as $pool)
													{
														echo '<option value="'.$pool['id'].'" '.($pool['id']==$pool_profile['pool'][0]['id'] ? 'selected' : '').'>'.$pool['name'].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="pool_1" class="col-lg-2 control-label">Pool 2</label>
										<div class="col-lg-10">
											<select id="pool_1" name="pool_1" class="form-control">
												<option value="0">Select a Pool / No Pool Needed</option>
												<?php 
													foreach($pools as $pool)
													{
														echo '<option value="'.$pool['id'].'" '.($pool['id']==$pool_profile['pool'][1]['id'] ? 'selected' : '').'>'.$pool['name'].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="pool_2" class="col-lg-2 control-label">Pool 3</label>
										<div class="col-lg-10">
											<select id="pool_2" name="pool_2" class="form-control">
												<option value="0">Select a Pool / No Pool Needed</option>
												<?php 
													foreach($pools as $pool)
													{
														echo '<option value="'.$pool['id'].'" '.($pool['id']==$pool_profile['pool'][2]['id'] ? 'selected' : '').'>'.$pool['name'].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">										
										<div class="pull-right">
											<a href="<?php echo $site['url']; ?>/dashboard?c=pools" type="submit" class="btn btn-default">Back</a>
											<a href="actions.php?a=pool_profile_delete&profile_id=<?php echo $profile_id; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
											<button type="submit" class="btn btn-success">Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</section>
            </div>
        <?php } ?>

        <?php function pool(){ ?>
			<?php global $account_details, $site; ?>
           	<?php $coins = get_coins(); ?>
           	<?php $pool_id = get('pool_id'); ?>
           	<?php $pool = get_pool($pool_id); ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Pool <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li><a href="<?php echo $site['url']; ?>/dashboard?c=pools">Pools</a></li>
                        <li class="active">Pool</li>
                    </ol>
                </section>
                
                <section class="content">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Pool</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<form action="actions.php?a=pool_update" method="post" class="form-horizontal">
								<input type="hidden" name="pool_id" value="<?php echo $pool_id; ?>">
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="name" class="col-lg-3 control-label">Name</label>
										<div class="col-lg-9">
											<input type="text" name="name" id="name" class="form-control" placeholder="Awesome Pool" value="<?php echo $pool['name']; ?>" required>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="url" class="col-lg-3 control-label">Pool Server URL</label>
										<div class="col-lg-9">
											<input type="text" name="url" id="url" class="form-control" placeholder="stratum.pool.com" value="<?php echo $pool['url']; ?>" required>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="port" class="col-lg-3 control-label">Pool Server Port</label>
										<div class="col-lg-9">
											<input type="text" name="port" id="port" class="form-control" placeholder="3334" value="<?php echo $pool['port']; ?>" required>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="username" class="col-lg-3 control-label">Pool Username</label>
										<div class="col-lg-9">
											<input type="text" name="username" id="username" class="form-control" placeholder="my_account_name" value="<?php echo $pool['username']; ?>" required>
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="password" class="col-lg-3 control-label">Pool Password</label>
										<div class="col-lg-9">
											<input type="text" name="password" id="password" class="form-control" placeholder="123" value="<?php echo $pool['password']; ?>">
										</div>
									</div>
								</div>

								<?php if (strpos($pool['url'], 'nicehash') !== false){ ?>
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="nicehash_api_id" class="col-lg-3 control-label">Nicehas API ID</label>
										<div class="col-lg-9">
											<input type="text" name="nicehash_api_id" id="nicehash_api_id" class="form-control" placeholder="3334" value="<?php echo $pool['nicehash_api_id']; ?>">
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="nicehash_api_key" class="col-lg-3 control-label">Nicehash API Key</label>
										<div class="col-lg-9">
											<input type="text" name="username" id="nicehash_api_key" class="form-control" placeholder="nicehash_api_key" value="<?php echo $pool['nicehash_api_key']; ?>">
										</div>
									</div>
								</div>
								<?php } ?>
								
								<div class="row">
									<div class="form-group col-lg-12">
										<label for="coin_id" class="col-lg-3 control-label">Coin</label>
										<div class="col-lg-9">
											<select id="coin_id" name="coin_id" class="form-control" onchange="func()">
												<?php 
													foreach($coins as $coin)
													{
														echo '<option value="'.$coin['id'].'" '.(($pool['coin_id'] == $coin['id']) ? 'selected' : '').'>'.$coin['name'].'</option>';
													}
												?>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="form-group col-lg-12">
										<div class="pull-right">
											<a href="<?php echo $site['url']; ?>/dashboard?c=pools" type="submit" class="btn btn-default">Back</a>
											<a href="actions.php?a=pool_delete&pool_id=<?php echo $pool_id; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
											<button type="submit" class="btn btn-success">Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</section>
            </div>
        <?php } ?>

        <?php function customers(){ ?>
			<?php global $account_details, $site; ?>
			<?php $customers = get_customers(); ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Customers <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Customers</li>
                    </ol>
                </section>
                
                <section class="content">
                	<div class="row">
						<div class="col-lg-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Add New Customer</h3>
								</div><!-- /.box-header -->
								<div class="box-body">
									<form id="customer_add" action="actions.php?a=customer_add" method="post" class="form-horizontal">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="first_name" class="col-lg-2 control-label">First Name</label>
												<div class="col-lg-10">
													<input type="text" name="first_name" id="first_name" class="form-control" placeholder="John" required>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<label for="last_name" class="col-lg-2 control-label">Last Name</label>
												<div class="col-lg-10">
													<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Smith" required>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-lg-6">
												<label for="email" class="col-lg-2 control-label">Email</label>
												<div class="col-lg-10">
													<input type="text" name="email" id="email" class="form-control" placeholder="john.smith@gmail.com" required>
												</div>
											</div>
											<div class="form-group col-lg-6">
												<label for="password" class="col-lg-2 control-label">Password</label>
												<div class="col-lg-10">
													<input type="text" name="password" id="password" class="form-control" placeholder="********" required>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="form-group col-lg-12 text-right">										
												<button type="submit" class="btn btn-success">Add</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>												
					<div class="row">
						<div class="col-lg-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Existing Customers</h3>
								</div><!-- /.box-header -->
								<div class="box-body">
									<?php if(is_array($customers)){
										foreach($customers as $customer){ ?>	
											<form action="actions.php?a=customer_update&customer_id=<?php echo $customer['id']; ?>" method="post" class="form-horizontal">
												<div class="form-group col-lg-12">
													
													<div class="form-group col-lg-3">
														<label for="first_name" class="col-lg-5 control-label">First Name</label>
														<div class="col-lg-7">
															<input type="text" name="first_name" id="first_name" class="form-control" placeholder="John" value="<?php echo $customer['first_name']; ?>" required>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="last_name" class="col-lg-5 control-label">Last Name</label>
														<div class="col-lg-7">
															<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Smith" value="<?php echo $customer['last_name']; ?>" required>
														</div>
													</div>

													<div class="form-group col-lg-3">
														<label for="email" class="col-lg-5 control-label">Email</label>
														<div class="col-lg-7">
															<input type="text" name="email" id="email" class="form-control" placeholder="john.smith@gmail.com" value="<?php echo $customer['email']; ?>" required>
														</div>
													</div>

													<div class="form-group col-lg-2">
														<label for="password" class="col-lg-5 control-label">Password</label>
														<div class="col-lg-7">
															<input type="text" name="password" id="password" class="form-control" placeholder="********" value="<?php echo $customer['password']; ?>" required>
														</div>
													</div>

													<div class="text-right">
														<button type="submit" class="btn btn-success">Save</button> &nbsp;
														<a href="actions.php?a=customer_delete&customer_id=<?php echo $customer['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</a>
													</div>
												</div>
											</form>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
                </section>
            </div>
        <?php } ?>

        <?php function customer_miners(){ ?>
        	<?php global $account_details, $site; ?>
           	<?php $customer_miners = get_customer_miners(); ?>

           	<?php 
           		$total_revenue = 0;
           		$total_profit = 0;
           		$total_cost = 0;
           		$total_hashrate = 0;
           		foreach($customer_miners as $customer_miner)
           		{
           			if(
           				$customer_miner['status_raw'] == 'mining' || 
           				$customer_miner['status_raw'] == 'autorebooted' || 
           				$customer_miner['status_raw'] == 'stuck_miners' || 
           				$customer_miner['status_raw'] == 'overheat'
           			)
           			{
           				$total_cost 					= $total_cost + $customer_miner['cost'];
	           			$total_profit 					= $total_profit + $customer_miner['profit'];
	           			$total_revenue 					= $total_revenue + $customer_miner['revenue'];
	           			$total_hashrate_bits 			= explode(' ', $customer_miner['hashrate']);

	           			if($customer_miner['hardwae'] == 'antminer-d3')
	           			{
	           				$total_hashrate 				= $total_hashrate + $total_hashrate_bits[0];
	           				$total_hashrate 				= $total_hashrate / 1000;
	           			}else{
	           				$total_hashrate 				= $total_hashrate + $total_hashrate_bits[0];
	           			}
           			}
           		}
           	?>
           	           	
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>My Miners</h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">My Miners</li>
                    </ol>
                </section>
                
                <section class="content">
                	<div class="row">
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">My Miners</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php echo count($customer_miners); ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Hashrate</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										<?php echo $total_hashrate; ?> THs
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Projected Monthly Revenue</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										$<?php echo number_format($total_revenue, 2); ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Projected Monthly Cost</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										$<?php echo number_format($total_cost, 2); ?>
									</h1>
								</div>
							</div>
						</div>
						<div class="col-md-2">
							<div class="box box-primary box-solid">
								<div class="box-header with-border">
									<h3 class="box-title">Projected Monthly Profit</h3>
								</div>
								<div class="box-body text-center">
									<h1>
										$<?php echo number_format($total_profit, 2); ?>
									</h1>
								</div>
							</div>
						</div>
					</div>
                	
                	<div class="row">
						<div class="col-lg-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Customer Miners</h3>
								</div><!-- /.box-header -->
								<div class="box-body">
									<form action="actions.php?a=miner_update_multi&site_id=<?php echo $site_id; ?>" method="post">
										<div class="row">
											<div class="col-md-4">
												<span id="multi_options_show" class="hidden">
													<div class="col-md-10">
														<select id="multi_options_action" name="multi_options_action" class="form-control" >
															<option value="reboot">Reboot Selected Miners</option>
															<!-- <option value="update">Update Selected Miners</option> -->
														</select>
													</div>
													<div class="col-md-2">
														<button type="submit" class="btn btn-success">GO</button>
													</div>
												</span>
											</div>

											<div class="col-md-8">
												<!-- <a href="actions.php?a=job_add&site_id=<?php echo $_GET['site_id']; ?>&miner_id=0&job=network_scan" class="btn btn-primary">Network Scan</a> -->

												<!--
												<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>" class="btn btn-default">All</a>
												<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=unknown" class="btn btn-info">Unknown</a>
												<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=pending" class="btn btn-primary">Pending</a>
												<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=offline" class="btn btn-warning">Offline</a>
												<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=disconnected" class="btn btn-danger">Disconnected</a>
												<a href="?c=<?php echo $_GET['c']; ?>&site_id=<?php echo $_GET['site_id']; ?>&search=mining" class="btn btn-success">Mining</a>
												-->

											</div>
										</div>

										<table id="customer_miners" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th><input type="checkbox" id="checkAll" /></th>
													<th>Description</th>
													<th>Status</th>
													<th>Progress</th>
													<th>Speed</th>
													<th>Coin / Pool</th>
													<th>Money</th>
													<th>Alerts</th>
													<th style="min-width: 10px;"></th>
												</tr>
											</thead>
											<tbody>
												<?php show_miners_ajax_template_customer(); ?>
											</tbody>
										</table>
									</form>
								</div>
							</div>
						</div>
					</div>
                </section>
            </div>
        <?php } ?>

        <?php function store(){ ?>
        	<?php global $account_details, $site;?>

        	<?php $products = get_products(); ?>
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1>Store</h1>
                    <ol class="breadcrumb">
                        <li class="active"><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Store</li>
                    </ol>
                </section>
    
                <section class="content">
					<div class="row">
						<div class="col-md-12">

						<?php foreach($products['products']['product'] as $product){ ?>
							<div class="col-md-3">
								<div class="box box-primary box-solid">
									<div class="box-header with-border">
										<h3 class="box-title"><?php echo $product['name']; ?> - $<?php echo number_format($product['pricing']['USD']['monthly'], 2); ?></h3>
									</div>
									<div class="box-body text-left">
										<center>
											<img src="img/products/<?php echo $product['pid']; ?>.png" width="200px" height="200px">
										</center>
										<hr>
										<?php echo $product['description']; ?>
									</div>
									<div class="box-footer text-center">
										<a href="actions.php?a=add_order&product_id=<?php echo $product['pid']; ?>" class="btn btn-success">Order Now</a>
									</div>
								</div>
							</div>
						<?php } ?>

                	</div>
                </section>
            </div>
        <?php } ?>

        <?php function profit_calc(){ ?>
        	<?php global $account_details, $site; ?>
           	           	
            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1><?php echo $site['name']; ?> <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Profit Calculator</li>
                    </ol>
                </section>
                
                <section class="content">						
                	<div class="row">
						<div class="col-md-12">
						  	<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
							  		<li class="active"><a href="#tab_1" data-toggle="tab">Bitcoin</a></li>
							  		<li><a href="#tab_2" data-toggle="tab">Bitcoin Cash</a></li>
							  		<li><a href="#tab_3" data-toggle="tab">DASH</a></li>
							  		<li><a href="#tab_4" data-toggle="tab">Litecoin</a></li>
							  		<li><a href="#tab_5" data-toggle="tab">Siacoin</a></li>
							  		<li><a href="#tab_6" data-toggle="tab">ZCash</a></li>
							  		<li><a href="#tab_7" data-toggle="tab">Monero</a></li>
							  		<li><a href="#tab_8" data-toggle="tab">Ethereum</a></li>
								</ul>
								<div class="tab-content">
							  		<div class="tab-pane active" id="tab_1">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by DeltaColo</a>
										<script type="text/javascript">
										    showCalc('bitcoin', '320', true, '0', 'FF9933', '006DCC', '006DCC', '4E9F15', '3DCCB9', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
							  		<div class="tab-pane" id="tab_2">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by deltaColo</a>
										<script type="text/javascript">
										    showCalc('bitcoingold', '320', true, '0', '006DCC', '006DCC', '006DCC', '4E9F15', '0099CC', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
							  		<div class="tab-pane" id="tab_3">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by deltaColo</a>
										<script type="text/javascript">
										    showCalc('dash', '320', true, '0', '006DCC', '006DCC', '006DCC', '4E9F15', '0099CC', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
							  		<div class="tab-pane" id="tab_4">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by deltaColo</a>
										<script type="text/javascript">
										    showCalc('litecoin', '320', true, '0', '006DCC', '006DCC', '006DCC', '4E9F15', '0099CC', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
							  		<div class="tab-pane" id="tab_5">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by deltaColo</a>
										<script type="text/javascript">
										    showCalc('siacoin', '320', true, '0', '006DCC', '006DCC', '006DCC', '4E9F15', '0099CC', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
							  		<div class="tab-pane" id="tab_6">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by deltaColo</a>
										<script type="text/javascript">
										    showCalc('zcash', '320', true, '0', '006DCC', '006DCC', '006DCC', '4E9F15', '0099CC', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
							  		<div class="tab-pane" id="tab_7">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by deltaColo</a>
										<script type="text/javascript">
										    showCalc('monero', '320', true, '0', '006DCC', '006DCC', '006DCC', '4E9F15', '0099CC', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
							  		<div class="tab-pane" id="tab_8">
										<script type="text/javascript" src="https://static.cryptorival.com/js/calcwidget.js"></script>
										<a id="cr-copyright" href="https://cryptorival.com/" target="_blank" rel="nofollow">Powered by deltaColo</a>
										<script type="text/javascript">
										    showCalc('ethereum', '320', true, '0', '006DCC', '006DCC', '006DCC', '4E9F15', '0099CC', 'F0AD4E', 'D9534F', 'F5F5F5', 'EEEEEE');
										</script>
							  		</div>
								</div>
						  	</div>
						</div>
          			</div>
                </section>
            </div>
        <?php } ?>

        <?php function hardware_calc(){ ?>
        	<?php global $account_details, $site; ?>

            <div class="content-wrapper">
				
                <div id="status_message"></div>
                            	
                <section class="content-header">
                    <h1><?php echo $site['name']; ?> <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Mining Hardware Profits</li>
                    </ol>
                </section>
                
                <section class="content">						
                	<div class="row">
						<div class="col-lg-12">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Mining Hardware Profits</h3>
								</div><!-- /.box-header -->
								<div class="box-body">
									<p><strong>NOTE:</strong> Figures shown are based upon $0.10 kWh pricing.</p>
								  	<table id="hardware_calc" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>Hardware</th>
												<th class="hidden-xs">Release</th>
												<th>Hashrate</th>
												<th class="hidden-xs">Power</th>
												<th class="hidden-xs">Noise</th>
												<th>Profit Day / Month / Year</th>
											</tr>
										</thead>
										<tbody>
											<?php show_mining_calc(); ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
          			</div>
                </section>
            </div>
        <?php } ?>
        
        <?php  function test(){ ?>
        	<?php global $account_details, $site, $crypto_prices; ?>
           
           	<style>
				
			</style>
            <div class="content-wrapper">
            
            	<div id="status_message"></div>
                
                <section class="content-header">
                    <h1>Test Page <!-- <small>Optional description</small> --></h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo $site['url']; ?>/dashboard">Dashboard</a></li>
                        <li class="active">Test Page</li>
                    </ol>
                </section>
    
                <section class="content">
                    <h4><strong>test_build_heatmap_array()</strong></h4>
                    	<?php $heatmap = build_heatmap_array($_GET['site_id']); ?>
                    	<?php debug($heatmap['table']); ?>
                    	
                    	<section class="content">
							<div class="box box-primary">
								<div class="box-header with-border">
									<h3 class="box-title">Sites</h3>
								</div><!-- /.box-header -->
								<div class="box-body">
									
									<?php
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
																<td width="50px" align="center" valign="middle">
																	<ul id="test2" style="display: table; width: 100%;">
																		<li style="width: 100%; list-style-type: none; display:inline-block;" data-hist="'.$position['miner_temp'].'">
																			<u>'.$position['miner_name'].'</u> <br>
																			<small>'.$position['miner_hashrate'].'</small>
																		</li>
																	</ul>
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
									?>
								
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
						</section>
                    	
                    <h4><strong>$_GET</strong></h4>
                    	<?php debug($_GET); ?>
                    	
                    <h4><strong>$_POST</strong></h4>
                        <?php debug($_POST); ?>
                        
                    <h4><strong>$_SESSION</strong></h4>
                        <?php debug($_SESSION); ?>
                        
                    <h4><strong>$account_details</strong></h4>
                        <?php debug($account_details); ?>
                    
                </section>
            </div>
        <?php } ?>

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
		        <!-- 
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
		    	-->
		    	<h3 class="control-sidebar-heading">Function Coming Soon</h3>
		    	This is a placeholder for future deployment.
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
    
    <?php if(isset($_GET['c']) && $_GET['c'] == 'my_account'){ ?>
    	<script>
			$(document).on('change', '.btn-file :file', function() {
			  var input = $(this),
				  numFiles = input.get(0).files ? input.get(0).files.length : 1,
				  label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			  input.trigger('fileselect', [numFiles, label]);
			});
			
			$(document).ready( function() {
				$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
					
					var input = $(this).parents('.input-group').find(':text'),
						log = numFiles > 1 ? numFiles + ' files selected' : label;
					
					if( input.length ) {
						input.val(log);
					} else {
						if( log ) alert(log);
					}
					
				});
			});
		
			function _(el){
				return document.getElementById(el);
			}
			function uploadFile(){
				var file = _("file1").files[0];
				var uid = _("uid").value;
				// alert(file.name+" | "+file.size+" | "+file.type);
				var formdata = new FormData();
				formdata.append("file1", file);
				formdata.append("uid", uid);
				var ajax = new XMLHttpRequest();
				ajax.upload.addEventListener("progress", progressHandler, false);
				ajax.addEventListener("load", completeHandler, false);
				ajax.addEventListener("error", errorHandler, false);
				ajax.addEventListener("abort", abortHandler, false);
				ajax.open("POST", "actions.php?a=my_account_update_photo");
				ajax.send(formdata);
			}
			function progressHandler(event){
				_("loaded_n_total").innerHTML = "Uploaded "+event.loaded+" bytes of "+event.total;
				var percent = (event.loaded / event.total) * 100;
				_("progressBar").value = Math.round(percent);
				_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
			}
			function completeHandler(event){
				_("status").innerHTML = event.target.responseText;
				_("progressBar").value = 0;
				setTimeout(function() {
					set_status_message('success', 'Your profile photo has been updated.');
					window.location = window.location;
				}, 3000);
			}
			function errorHandler(event){
				_("status").innerHTML = "Upload Failed";
				setTimeout(function() {
					$('#status').fadeOut('fast');
				}, 10000);
			}
			function abortHandler(event){
				_("status").innerHTML = "Upload Aborted";
				setTimeout(function() {
					$('#status').fadeOut('fast');
				}, 10000);
			}
		</script>
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

	<?php if(isset($_GET['c']) && $_GET['c'] == 'site'){ ?>
		<script src="mousetrap/mousetrap.min.js"></script>

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
	
       		$("#miner_update_multi").submit(function(event)
	        {
	        	// alert('we caught the event');
	            
	            $('#submitting_changes').modal('show');

	            /* stop form from submitting normally */
	            event.preventDefault();

	            /* get the action attribute from the <form action=""> element */
	            var $form = $( this ),
	            url = $form.attr( 'action' );

				var miner_ids = [];
		        $(':checkbox:checked').each(function(i){
		        	miner_ids[i] = $(this).val();
		        });

	            /* Send the data using post with element id name and name2*/
	            var posting = $.post(url, { 
	                multi_options_action: $('#multi_options_action').val(), 
	                set_fan_speed: $('#set_fan_speed').val(), 
	                set_pool_id: $('#set_pool_id').val(), 
	                set_customer_id: $('#set_customer_id').val(), 
	                miner_select: miner_ids,
	            });

	            /* Alerts the results */
	            posting.done(function( data )
	            {
	            	$('input:checkbox').removeAttr('checked');
	            	$('#multi_options_show').addClass("hidden");
					$('#dynamic_set_pool').addClass("hidden");
					$('#dynamic_set_fan_speed').addClass("hidden");
					$('#dynamic_set_owner').addClass("hidden");
	                $('#submitting_changes').modal('hide');
	            });
	        });

			$.ajax({
				cache: false,
				type: "GET",
				url: "https://dashboard.miningcontrolpanel.com/actions.php?a=ajax_get_ip_ranges&site_id=<?php echo get('site_id'); ?>",
				success: function(ip_ranges) {
					if( !$.isArray(ip_ranges) ||  !ip_ranges.length ) {
						$('#step_add_ip_range').modal('show');
					}

					for (i in ip_range)
					{

					}
				}
			});

		    // combinations
		    Mousetrap.bind('h+e+l+p', function()
		    {
		    	alert("Key combinations: \n\n'command + shift + p' will pause all miners. \n'command + shift + u' will unpause all miners.");
		    });

		    Mousetrap.bind('command+shift+p', function()
		    {
		    	if (confirm("This will pause ALL miners in this site, continue?"))
		        {
		            window.location.href= 'actions.php?a=pause_unpause_all_miners&action=pause&site_id=<?php echo $_GET['site_id']; ?>'; 
		        }else{
		           // window.location.href = 'intern.php?act=account'; 
		        }
		        return false;
		    });

		    Mousetrap.bind('command+shift+u', function()
		    {
		    	if (confirm("This will unpause ALL miners in this site, continue?"))
		        {
		            window.location.href= 'actions.php?a=pause_unpause_all_miners&action=unpause&site_id=<?php echo $_GET['site_id']; ?>'; 
		        }else{
		           // window.location.href = 'intern.php?act=account'; 
		        }
		        return false;
		    });

		    // map multiple combinations to the same callback
		    Mousetrap.bind(['command+k', 'ctrl+k'], function() {
		        return false;
		    });

		    // gmail style sequences
		    Mousetrap.bind('g i', function() { console.log('go to inbox'); });
		    Mousetrap.bind('* a', function() { console.log('select all'); });

		    // konami code!
		    Mousetrap.bind('up up down down left right left right b a enter', function() {
		        console.log('konami code');
		    });
		
			function multi_options(){
				$('#multi_options_show').removeClass("hidden");
			}

			function show_additional_options(elem){
			   if(elem.value == 'set_pool'){
			   		$('#dynamic_set_pool').removeClass("hidden");
			   }else{
			   		$('#dynamic_set_pool').addClass("hidden");
			   }

			   if(elem.value == 'set_fan_speed'){
			      $('#dynamic_set_fan_speed').removeClass("hidden");
			   }else{
			   		$('#dynamic_set_fan_speed').addClass("hidden");
			   }

			   if(elem.value == 'set_owner'){
			      $('#dynamic_set_owner').removeClass("hidden");
			   }else{
			   		$('#dynamic_set_owner').addClass("hidden");
			   }
			}

			function set_table_class(miner_id, new_class)
			{
				var table_ids = ["1", "2", "3", "4", "5", "6", "7", "8", "9"]; 

				for ( var key in table_ids ) {
					var table_id = table_ids[key];

					$('#'+miner_id+'_td_'+table_id).removeClass('row_green');
					$('#'+miner_id+'_td_'+table_id).removeClass('row_yellow');
					$('#'+miner_id+'_td_'+table_id).removeClass('row_red');

					$('#'+miner_id+'_td_'+table_id).addClass(new_class);
					// console.log('Changing #'+miner_id+'_td_'+table_id + ' to ' + new_class);
				}
			}

			var refreshTime = 30000; // every 30 seconds in milliseconds

			// get miners
			window.setInterval( function() {
				$.ajax({
					cache: false,
					type: "GET",
					// url: "actions.php?a=ajax_show_miners&site_id=<?php echo get('site_id'); ?>&type=asic",
					url: "actions.php?a=ajax_show_miners&site_id=<?php echo get('site_id'); ?>&type=any",
					success: function(miners) {
						
						for (i in miners)
						{
							// remove all classes example
							// $('#'+miners[i].id+'_td_1').removeAttr('class');
							// $('#'+miners[i].id+'_td_1').attr('class', '');
							// $('#'+miners[i].id+'_td_1')[0].className = '';

							if (miners[i].status_raw == 'mining')
							{
								set_table_class('asic_' + miners[i].id, 'row_green');

								miners[i].status_html = '<b><font color="green">'+miners[i].status+'</font></b>';
							}

							if (miners[i].status_raw == 'offline' || miners[i].status_raw == 'unreachable')
							{
								set_table_class('asic_' + miners[i].id, 'row_red');

								miners[i].status_html = '<b><font color="red">'+miners[i].status+'</font></b>';
							}

							if (miners[i].paused == 'yes')
							{
								set_table_class('asic_' + miners[i].id, 'row_red');

								miners[i].status_html = '<b><font color="red">'+miners[i].status+'</font></b>';
							}

							if (miners[i].status_raw == 'not_mining' || miners[i].status_raw == 'no_hash' || miners[i].status_raw == 'autorebooted' || miners[i].status_raw == 'unreachable')
							{
								set_table_class('asic_' + miners[i].id, 'row_red');

								miners[i].status_html = '<b><font color="red">'+miners[i].status+'</font></b>';
							}

							if (miners[i].status_raw == 'throttle')
							{
								set_table_class('asic_' + miners[i].id, 'row_yellow');

								miners[i].status_html = '<b><font color="green">'+miners[i].status+'</font></b>';
							}

							if (miners[i].status_raw == 'stuck_miners' || miners[i].status_raw == 'overheat' || miners[i].status_raw == 'throttle')
							{
								set_table_class('asic_' + miners[i].id, 'row_yellow');

								miners[i].status_html = '<b><font color="red">'+miners[i].status+'</font></b>';
							}

							if (miners[i].status_raw == 'new')
							{
								set_table_class('asic_' + miners[i].id, 'row_yellow');

								miners[i].status_html = '<b><font color="blue">'+miners[i].status+'</font></b>';
							}

							if(miners[i].pending_jobs != 0)
							{
								set_table_class('asic_' + miners[i].id, 'row_yellow');

								miners[i].status_html = '<b><font color="blue">Pending Job</font></b>';
							}
							
							if (miners[i].warning == 'yes')
							{
								
								if(miners[i].warning_text == 'High PCB Temp'){
									tts("WARNING, high temp alarm");
								}

								set_table_class('asic_' + miners[i].id, 'row_yellow');
							}

							// alert(miners[i].pools[0].user);
							
							// console.log('Miner ID: ' + miners[i].id);
							// console.log('Customer: ' + miners[i].customer.fullname);

							// colum 0 - check box

							// colum 1 - ip address
							// document.getElementById('asic_' + miners[i].id + '_col_1').innerHTML = miners[i].ip_address;

							
							// colum 2 - name
							document.getElementById('asic_' + miners[i].id + '_col_2').innerHTML = miners[i].name;

							// colum 3 - type
							// document.getElementById('asic_' + miners[i].id + '_col_3').innerHTML = miners[i].hardware;

							// colum 4 - hash rate
							document.getElementById('asic_' + miners[i].id + '_col_4').innerHTML = '<span data-html="true" data-toggle="tooltip" data-placement="top" title="<strong>ASICs:</strong> '+miners[i].asics_1+' / '+miners[i].asics_2+' / '+miners[i].asics_3+'<br><strong>Fan Speed:</strong> '+miners[i].fan_1_speed+' RPM / '+miners[i].fan_2_speed+' RPM<br><strong>Frequency:</strong> '+miners[i].frequency+'<br><strong>Hardware Errors:</strong> '+miners[i].hardware_errors+'">' + miners[i].hashrate + '</span>';

							// colum 5 - temp
							document.getElementById('asic_' + miners[i].id + '_col_5').innerHTML = '<span data-html="true" data-toggle="tooltip" data-placement="top" title="<strong>PCB Temps:</strong> '+miners[i].pcb_temp_1+' / '+miners[i].pcb_temp_2+ ' / '+miners[i].pcb_temp_3+'<br><strong>Chip Temps:</strong> '+miners[i].chip_temp_1+' / '+miners[i].chip_temp_2+ ' / '+miners[i].chip_temp_3+'">' + miners[i].pcb_temp + '</span>';
							
							// colum 6 - pool
							document.getElementById('asic_' + miners[i].id + '_col_6').innerHTML = '<span data-html="true" data-toggle="tooltip" data-placement="top" title="<strong>Pool URL:</strong> '+miners[i].pool_details.url+'<br><strong>Pool Username:</strong> '+miners[i].pool_details.user+'.'+miners[i].pool_details.worker+'">' + miners[i].pool_data + '</span>';
							if(miners[i].pool_profile.name != null) {
								// document.getElementById('asic_' + miners[i].id + '_col_6').innerHTML += ' / '+miners[i].pool_profile.name;
							}

							// colum 7 - status
							if(miners[i].warning_text == '')
							{
								document.getElementById('asic_' + miners[i].id + '_col_7').innerHTML = miners[i].status_html;
							}else{
								document.getElementById('asic_' + miners[i].id + '_col_7').innerHTML = miners[i].status_html + ' / ' + miners[i].warning_text;
							}

							// colum 8 - customer
							document.getElementById('asic_' + miners[i].id + '_col_8').innerHTML = '<span data-html="true" data-toggle="tooltip" data-placement="top" title="<strong>Email:</strong> '+miners[i].customer.email+'<br><strong>Notification Email:</strong> '+miners[i].customer.notification_email+'<br><strong>Notification Tel:</strong> '+miners[i].customer.notification_tel+'">' + miners[i].customer.fullname + '</span>';

							// colum 9 - last updated
							document.getElementById('asic_' + miners[i].id + '_col_9').innerHTML = miners[i].updated;
							
							// colum 10 - actions
							document.getElementById('asic_' + miners[i].id + '_col_10').innerHTML = '<a title="Settings" href="?c=miner&miner_id='+miners[i].id+'"><i class="fa fa-cog" aria-hidden="true"></i></a> &nbsp <a title="Reboot" href=""><i class="fa fa-refresh" aria-hidden="true"></i></a>';
						}
						
						// document.getElementById("ajax_miner_stats").innerHTML = data;
					}
				});
			}, refreshTime );

			$(document).ready(function(){
			    $('[data-toggle="tooltip"]').tooltip(); 
			});
			
			/*
			window.setInterval( function() {
				$.ajax({
					cache: false,
					type: "GET",
					url: "actions.php?a=ajax_show_site_summary&site_id=<?php echo get('site_id'); ?>",
					success: function(site_stats) {
						document.getElementById('projected_power_usage').innerHTML = site_stats.power.kilowatts + ' kW / ' + site_stats.power.amps + ' AMPs';
						
						document.getElementById('projected_hashrate_sha256').innerHTML = '';
						document.getElementById('projected_hashrate_x11').innerHTML = '';
						document.getElementById('projected_hashrate_scrypt').innerHTML = '';
						document.getElementById('projected_hashrate_eth').innerHTML = '';
						document.getElementById('projected_hashrate_blake2b').innerHTML = '';

						$("#projected_hashrate_sha256").append( '0 H/s' );
						$("#projected_hashrate_x11").append( '0 H/s' );
						$("#projected_hashrate_scrypt").append( '0 H/s' );
						$("#projected_hashrate_blake2b").append( '0 H/s' );
						$("#projected_hashrate_eth").append( '0 H/s' );

						for (i in site_stats.hashrate)
						{
							if(i == 'sha256'){
								$("#projected_hashrate_sha256").html( site_stats.hashrate[i] );
								$("#projected_hashrate_sha256_box").removeClass( 'box-primary' );
								$("#projected_hashrate_sha256_box").addClass( 'box-success' );
							}

							if(i == 'x11'){
								$("#projected_hashrate_x11").html( site_stats.hashrate[i] );
								$("#projected_hashrate_x11_box").removeClass( 'box-primary' );
								$("#projected_hashrate_x11_box").addClass( 'box-success' );
							}

							if(i == 'scrypt'){
								$("#projected_hashrate_scrypt").html( site_stats.hashrate[i] );
								$("#projected_hashrate_scrypt_box").removeClass( 'box-primary' );
								$("#projected_hashrate_scrypt_box").addClass( 'box-success' );
							}

							if(i == 'blake2b'){
								$("#projected_hashrate_blake2b").html( site_stats.hashrate[i] );
								$("#projected_hashrate_blake2b_box").removeClass( 'box-primary' );
								$("#projected_hashrate_blake2b_box").addClass( 'box-success' );
							}

							if(i == 'eth'){
								$("#projected_hashrate_eth").html( site_stats.hashrate[i] );
								$("#projected_hashrate_eth_box").removeClass( 'box-primary' );
								$("#projected_hashrate_eth_box").addClass( 'box-success' );
							}
						}
						
						if(site_stats.average_temps.pcb == undefined){
							document.getElementById('projected_pcb_temp').innerHTML = '0';
						}else{
							document.getElementById('projected_pcb_temp').innerHTML = site_stats.average_temps.pcb;
						}
						
						document.getElementById('projected_monthly_profit').innerHTML = '$' + site_stats.monthly_profit;
						document.getElementById('projected_monthly_power_cost').innerHTML = '$' + site_stats.monthly_power_cost;
						document.getElementById('projected_monthly_revenue').innerHTML = '$' + site_stats.monthly_revenue;
					}
				});
			}, refreshTime );
			*/

			$('#checkAll').change(function () {
			    $('.chk').prop('checked', this.checked);
			    $('#multi_options_show').removeClass("hidden");
			});

			$(".chk").change(function () {
			    if ($(".chk:checked").length == $(".chk").length) {
			        $('#checkAll').prop('checked', 'checked');
			    } else {
			        $('#checkAll').prop('checked', false);
			    }
			});

			function show_miners(data)
			{
				alert('changing view = ' + data);
				e.preventDefault();
				e.stopImmediatePropagation();

				$('#asic_miners').hide();
				$('#gpu_miners').hide();

				if(data == 'asic_miners'){
					$('#show_asic_link').removeClass("btn-warning");
					$('#show_asic_link').removeClass("btn-success");

					$('#show_gpu_link').removeClass("btn-warning");
					$('#show_gpu_link').removeClass("btn-success");
					
					$('#show_gpu_link').addClass("btn-warning");
					$('#show_asic_link').addClass("btn-success");
				}

				if(data == 'gpu_miners'){
					$('#show_asic_link').removeClass("btn-warning");
					$('#show_asic_link').removeClass("btn-success");

					$('#show_gpu_link').removeClass("btn-warning");
					$('#show_gpu_link').removeClass("btn-success");
					
					$('#show_asic_link').addClass("btn-warning");
					$('#show_gpu_link').addClass("btn-success");
				}

				$('#'+data).show();
			}
		</script>
	<?php } ?>

	<?php if(isset($_GET['c']) && $_GET['c'] == 'customer_miners'){ ?>
		<script>
			function multi_options(){
				$('#multi_options_show').removeClass("hidden");
				multi_options_show
			}
			var refreshTime = 5000; // every 5 seconds in milliseconds
			window.setInterval( function() {
				$.ajax({
					cache: false,
					type: "GET",
					url: "actions.php?a=ajax_show_miners_customer&customer_id=<?php echo $_SESSION['account']['id']; ?>",
					success: function(miners) {
 						// var x = miners[0].id;
						// console.log('Miner ID: ' + x);
						
						for (i in miners)
						{

							if (miners[i].status_raw == 'mining') {
								miners[i].status_html = '<b><font color="green">'+miners[i].status+'</font></b>';
							} else {
								miners[i].status_html = '<b><font color="red">'+miners[i].status+'</font></b>';
							}

							if(miners[i].pending_jobs != 0){
								$('#'+miners[i].id+'_td_0').addClass("row_warning");
								$('#'+miners[i].id+'_td_1').addClass("row_warning");
								$('#'+miners[i].id+'_td_2').addClass("row_warning");
								$('#'+miners[i].id+'_td_3').addClass("row_warning");
								$('#'+miners[i].id+'_td_4').addClass("row_warning");
								$('#'+miners[i].id+'_td_5').addClass("row_warning");
								$('#'+miners[i].id+'_td_6').addClass("row_warning");
								$('#'+miners[i].id+'_td_7').addClass("row_warning");
								$('#'+miners[i].id+'_td_8').addClass("row_warning");
							}else{
								$('#'+miners[i].id+'_td_0').removeClass("row_warning");
								$('#'+miners[i].id+'_td_1').removeClass("row_warning");
								$('#'+miners[i].id+'_td_2').removeClass("row_warning");
								$('#'+miners[i].id+'_td_3').removeClass("row_warning");
								$('#'+miners[i].id+'_td_4').removeClass("row_warning");
								$('#'+miners[i].id+'_td_5').removeClass("row_warning");
								$('#'+miners[i].id+'_td_6').removeClass("row_warning");
								$('#'+miners[i].id+'_td_7').removeClass("row_warning");
								$('#'+miners[i].id+'_td_8').removeClass("row_warning");
							}
							
							if (miners[i].warning == 'yes'){
								
								if(miners[i].warning_text == 'High PCB Temp'){
									tts("WARNING, high PCB temp alarm");
								}

								$('#'+miners[i].id+'_td_0').addClass("row_error");
								$('#'+miners[i].id+'_td_1').addClass("row_error");
								$('#'+miners[i].id+'_td_2').addClass("row_error");
								$('#'+miners[i].id+'_td_3').addClass("row_error");
								$('#'+miners[i].id+'_td_4').addClass("row_error");
								$('#'+miners[i].id+'_td_5').addClass("row_error");
								$('#'+miners[i].id+'_td_6').addClass("row_error");
								$('#'+miners[i].id+'_td_7').addClass("row_error");
								$('#'+miners[i].id+'_td_8').addClass("row_error");
							}else{
								$('#'+miners[i].id+'_td_0').removeClass("row_error");
								$('#'+miners[i].id+'_td_1').removeClass("row_error");
								$('#'+miners[i].id+'_td_2').removeClass("row_error");
								$('#'+miners[i].id+'_td_3').removeClass("row_error");
								$('#'+miners[i].id+'_td_4').removeClass("row_error");
								$('#'+miners[i].id+'_td_5').removeClass("row_error");
								$('#'+miners[i].id+'_td_6').removeClass("row_error");
								$('#'+miners[i].id+'_td_7').removeClass("row_error");
								$('#'+miners[i].id+'_td_8').removeClass("row_error");
							}

							if (miners[i].status_raw == 'not_mining') {
								$('#'+miners[i].id+'_td_0').addClass("row_warning");
								$('#'+miners[i].id+'_td_1').addClass("row_warning");
								$('#'+miners[i].id+'_td_2').addClass("row_warning");
								$('#'+miners[i].id+'_td_3').addClass("row_warning");
								$('#'+miners[i].id+'_td_4').addClass("row_warning");
								$('#'+miners[i].id+'_td_5').addClass("row_warning");
								$('#'+miners[i].id+'_td_6').addClass("row_warning");
								$('#'+miners[i].id+'_td_7').addClass("row_warning");
								$('#'+miners[i].id+'_td_8').addClass("row_warning");
								$('#'+miners[i].id+'_td_0').removeClass("row_error");
								$('#'+miners[i].id+'_td_1').removeClass("row_error");
								$('#'+miners[i].id+'_td_2').removeClass("row_error");
								$('#'+miners[i].id+'_td_3').removeClass("row_error");
								$('#'+miners[i].id+'_td_4').removeClass("row_error");
								$('#'+miners[i].id+'_td_5').removeClass("row_error");
								$('#'+miners[i].id+'_td_6').removeClass("row_error");
								$('#'+miners[i].id+'_td_7').removeClass("row_error");
								$('#'+miners[i].id+'_td_8').removeClass("row_error");

								$('#'+miners[i].id+'_td_0').removeClass("row_green");
								$('#'+miners[i].id+'_td_1').removeClass("row_green");
								$('#'+miners[i].id+'_td_2').removeClass("row_green");
								$('#'+miners[i].id+'_td_3').removeClass("row_green");
								$('#'+miners[i].id+'_td_4').removeClass("row_green");
								$('#'+miners[i].id+'_td_5').removeClass("row_green");
								$('#'+miners[i].id+'_td_6').removeClass("row_green");
								$('#'+miners[i].id+'_td_7').removeClass("row_green");
								$('#'+miners[i].id+'_td_8').removeClass("row_green");
							}
							
							console.log('Miner ID: ' + miners[i].id);
							
							// colum 0
							// document.getElementById(miners[i].id + '_col_0').innerHTML = '';

							// colum 1
							document.getElementById(miners[i].id + '_col_1').innerHTML = '<strong>'+miners[i].name+'</strong> ';

							if(miners[i].worker_name != ''){
								document.getElementById(miners[i].id + '_col_1').innerHTML += '('+miners[i].worker_name+')';
							}

							document.getElementById(miners[i].id + '_col_1').innerHTML += ' <br>';

							document.getElementById(miners[i].id + "_col_1").innerHTML += miners[i].ip_address+' <br>';
							document.getElementById(miners[i].id + "_col_1").innerHTML += '<small>'+miners[i].hardware+'</small> <br>';
							// document.getElementById(miners[i].id + "_col_1").innerHTML += '<small>'+miners[i].location+'</small>';
							
							if (miners[i].status_raw == 'mining') {
								// colum 2
								document.getElementById(miners[i].id + '_col_2').innerHTML = miners[i].status_html+' <br>';
								document.getElementById(miners[i].id + "_col_2").innerHTML += '<b>PCB Temps <small>(<?php echo $account_details['temp_symbol']; ?>)</small>:</b> '+miners[i].pcb_temp_1+' / '+miners[i].pcb_temp_2+' / '+miners[i].pcb_temp_3+' <br>';
								document.getElementById(miners[i].id + "_col_2").innerHTML += '<b>Chip Temps <small>(<?php echo $account_details['temp_symbol']; ?>)</small>:</b> '+miners[i].chip_temp_1+' / '+miners[i].chip_temp_2+' / '+miners[i].chip_temp_3+'';
								// document.getElementById(miners[i].id + "_col_2").innerHTML += '<b>Avg Temps:</b> '+miners[i].pcb_temp+' / '+miners[i].chip_temp_2+' / '+miners[i].chip_temp_3+' °C<br>';

								// colum 3
								document.getElementById(miners[i].id + '_col_3').innerHTML = '<b>Accepted:</b> '+miners[i].accepted+' <br>';
								document.getElementById(miners[i].id + '_col_3').innerHTML += '<b>Rejected:</b> '+miners[i].rejected+' <br>';
								document.getElementById(miners[i].id + '_col_3').innerHTML += '<b>HW Errors:</b> '+miners[i].hardware_errors+'';

								// colum 4
								document.getElementById(miners[i].id + '_col_4').innerHTML = '<b>Hashrate:</b> '+miners[i].hashrate+' <br>';
								document.getElementById(miners[i].id + '_col_4').innerHTML += '<b>ASIC:</b> '+miners[i].asics_1+' / '+miners[i].asics_2+' / '+miners[i].asics_3+'';

								// colum 5
								document.getElementById(miners[i].id + '_col_5').innerHTML = '<b>Algorithm:</b> '+miners[i].algorithm+' <br>';
								document.getElementById(miners[i].id + '_col_5').innerHTML += '<b>Pool:</b> '+miners[i].pool_data+' <br>';
								
								if(miners[i].pool_profile.name != null) {
									document.getElementById(miners[i].id + '_col_5').innerHTML += '<b>Profile:</b> '+miners[i].pool_profile.name+'';
								}

								// colum 6
								document.getElementById(miners[i].id + '_col_6').innerHTML = '<b>Rev:</b> $'+miners[i].revenue+' <br>';
								document.getElementById(miners[i].id + '_col_6').innerHTML += '<b>Cost:</b> $'+miners[i].cost+' <br>';
								document.getElementById(miners[i].id + '_col_6').innerHTML += '<b>Profit:</b> $'+miners[i].profit+' <br>';

								// colum 7
								document.getElementById(miners[i].id + '_col_7').innerHTML = miners[i].warning_text+'';
							}else{
								// colum 2
								document.getElementById(miners[i].id + '_col_2').innerHTML = miners[i].status_html+'';
								
								// colum 3
								document.getElementById(miners[i].id + '_col_3').innerHTML = '';
								
								// colum 4
								document.getElementById(miners[i].id + '_col_4').innerHTML = '';
								
								// colum 5
								document.getElementById(miners[i].id + '_col_5').innerHTML = '';
								
								// colum 6
								document.getElementById(miners[i].id + '_col_6').innerHTML = '';
								
								// colum 7
								document.getElementById(miners[i].id + '_col_7').innerHTML = '';
							}

							// colum 8
							document.getElementById(miners[i].id + '_col_8').innerHTML = '<a title="Overview" class="btn btn-primary btn-flat" href="?c=miner&miner_id='+miners[i].id+'"><i class="fa fa-globe"></i></a>';
						}
						
						// document.getElementById("ajax_miner_stats").innerHTML = data;
					}
				});
			}, refreshTime );

		$('#checkAll').change(function () {
		    $('.chk').prop('checked', this.checked);
		    $('#multi_options_show').removeClass("hidden");
		});

		$(".chk").change(function () {
		    if ($(".chk:checked").length == $(".chk").length) {
		        $('#checkAll').prop('checked', 'checked');
		    } else {
		        $('#checkAll').prop('checked', false);
		    }
		});
		</script>
	<?php } ?>
	
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