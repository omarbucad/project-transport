<!DOCTYPE html>
<html>

<head>
    <title><?php echo $website_title; ?></title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/img/vehicle-checklist.png') ?>" type="image/x-icon">
    <!-- Fonts -->
   <!--  <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'> -->
    <!-- CSS Libs -->

    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/font-awesome.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/animate.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap-switch.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/checkbox3.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/select2.min.css?version='.$version) ?>">

    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/style.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/themes/flat-blue.css?version='.$version) ?>">
    <style type="text/css">
        body.login-page {
            background: url("<?php echo site_url('public/img/website-layout/transpo-03.png') ?>") no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .login-body > div > a{
            position: relative;
            top: 40px;
        }
        .new_in {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
    </style>
</head>

<body class="flat-blue login-page">
    <div class="container">
        <div class="login-box">
            <div class="login-form ">
                <div class="login-body row">
                    <div class="col-xs-12 col-lg-6 no-margin-bottom color-white">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6no-margin-bottom text-center">
                                <h2 style="margin-bottom: 0;"><a href="<?php echo site_url();?>"><img src="<?php echo site_url("public/img/vehicle-checklist.png"); ?>" class="img img-responsive" alt="Vehicle Checklist Logo" style="width: 39%; position: relative;left: 30%;margin-top:10px;margin-bottom: 10px;"></a>Vehicle Checklist</h2> 
                                <p style="color:#d4d4d4 !important;">Manage your defect checklist hassle free.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 no-margin-bottom">

                        <div class="row">
                            <div class="container-fluid color-white">
                                <h2 class="text-center">Sign in</h2>
                            </div>
                        </div>
  
                        <?php if($this->session->flashdata("status")) : ?>
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><?php echo $this->session->flashdata("message"); ?></p>
                        </div>
                        <?php $this->session->sess_destroy(); ?>
                        <?php endif; ?>

  
                            <form action="<?php echo site_url("login/do_login"); ?>" method="POST">
                                <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">

                                <div class="form-group">
                                    <input type="text" name="username" class="form-control" placeholder="Username or Email Address">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                                <input type="submit" class="btn btn-primary btn-block" value="Login">
   
                            </form>                       
                    </div>
                    <div class="login-footer">
                        <span class="text-right"><a href="<?php echo site_url("login/forgot_password"); ?>" style="margin-right:15px;color:#d4d4d4 !important;">Forgot password?</a></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-offset-5 col-lg-7">
                     <div style="color:white;font-size: 13px;margin-top: 10px;"><a href="#" style="color:white;margin-left: 10px;"><span style="padding:2px 0px;border-bottom:1px solid white;"></span></a></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript Libs -->

    <script type="text/javascript" src="<?php echo site_url('public/lib/js/jquery.min.js?version='.$version) ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('public/lib/js/bootstrap.min.js?version='.$version) ?>"></script>

</body>

</html>
