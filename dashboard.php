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
        </header>

        
        
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

		function set_status_message(status, message){
			$.ajax({
				cache: false,
				type: "GET",
				url: "actions.php?a=set_status_message&status=" + status + "&message=" + message,
				success: function(data) {
					
				}
			});	
		}
	</script>


</body>
</html>