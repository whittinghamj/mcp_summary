<?php

include('inc/db.php');
include('inc/sessions.php');
$sess = new SessionManager();
session_start();

include('inc/global_vars.php');
include('inc/functions.php');

?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>
            <?php echo $site['title']; ?>
        </title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    </head>

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href=""><b><?php echo $site['title']; ?></b></a>
            </div>
            <div id="login_form">
                <div class="login-box-body">
                    <p class="login-box-msg">Sign in</p>
                    <div id="status_message"></div>
                    <form action="login.php" method="post">
                        <div class="form-group has-feedback">
                            <input type="number" class="form-control" placeholder="12345" name="passcode" id="passcode">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                            </div>
                        </div>
                    </form>

                    <!--
                    <div class="social-auth-links text-center">
                        <p>- OR -</p>
                        <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
                        <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
                    </div>
                    -->

                    <!-- <a href="#">I forgot my password</a> -->
                    
                    <!--
                    <br>
                    <a href="" onclick="switch_content('register');" class="text-center">Register a new membership</a>
					-->
                </div>
            </div>

            <div id="register_form" style="display: none">
                <div class="register-box-body">
                    <p class="login-box-msg">Register</p>
                    <form action="../../index.html" method="post">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Full name">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="email" class="form-control" placeholder="Email">
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="password" class="form-control" placeholder="Retype password">
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                        </div>
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="checkbox icheck">
                                    <label>
                                        <input type="checkbox"> I agree to the <a href="#">terms</a>
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>

                    <a href="#" onclick="switch_content('login');" class="text-center">I already have a membership</a>
                </div>
            </div>
        </div>

        <!-- jQuery 2.1.4 -->
        <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="plugins/iCheck/icheck.min.js"></script>
        <script>
            $(function() {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });

            function switch_content(div_name) {
                if (div_name == 'register') {
                    $("login").hide();
                    $("register").show();
                }
                if (div_name == 'login') {
                    $("register").hide();
                    $("login").show();
                }
            }
        </script>

        <?php if(!empty($_SESSION['alert']['status'])){ ?>
            <script>
                document.getElementById('status_message').innerHTML = '<div class="callout callout-<?php echo $_SESSION['alert']['status']; ?> lead"><p><?php echo $_SESSION['alert']['message']; ?></p></div>';
                setTimeout(function() {
                    $('#status_message').fadeOut('fast');
                }, 5000);
            </script>
        <?php unset($_SESSION['alert']); ?>
    <?php } ?>
    </body>

    </html>