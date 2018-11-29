<!DOCTYPE html>
<html>

<head>
    <title><?php echo $website_title; ?></title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/img/vehicle-checklist.png') ?>" type="image/x-icon">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
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
            background: url("<?php echo site_url('public/img/banner3.jpg') ?>") no-repeat center center fixed;
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
                    <div class="col-xs-12 col-lg-5 no-margin-bottom">
                        <div class="row">
                            <div class="col-xs-12 col-lg-8 no-margin-bottom text-center" style="width: 100%;">
                                <a href="#"><img src="<?php echo site_url("public/img/vehicle-checklist.png"); ?>" class="img img-responsive" style="width: 65%; margin-left: 15%;margin-top:10px"></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="container-fluid text-center">
                                <h4 >Vehicle Checklist</h4>                                
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-7 no-margin-bottom">

                            <div class="row">
                                <div class="container-fluid">
                                    <h2 class="text-center">Change Password</h2>
                                </div>
                            </div>
                        <?php if(validation_errors()) : ?>
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" id="closed_alert"><span aria-hidden="true">×</span></button>
                                <?php echo validation_errors(); ?>
                            </div>
                        <?php endif; ?>
  
                        <?php if($this->session->flashdata("message") != "") : ?>
                        <div class="alert alert-<?php echo ($this->session->flashdata('status')=='success') ? 'success' : 'danger'; ?>" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <p><?php echo $this->session->flashdata("message"); ?></p>
                        </div>
                        <?php $this->session->sess_destroy(); ?>
                        <?php endif; ?>

  
                            <form action="<?php echo site_url("login/change_password/").$code; ?>" method="POST">
                                <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="email" value="<?php echo $customer_email; ?>" readonly="true">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" id="enter_password" placeholder="New Password" required="true">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" required="true">
                                </div>
                                <input type="submit" class="btn btn-primary btn-block" value="Submit">
   
                            </form>
                    </div>
                    <div class="login-footer">
                        <span class="text-right"><a href="<?php echo site_url("login"); ?>" style="color:#333;margin-right:15px;">Back to Login</a></span>
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
