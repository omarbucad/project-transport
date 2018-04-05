<!DOCTYPE html>
<html>

<head>
    <title><?php echo $website_title; ?></title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/img/favicon.png') ?>" type="image/x-icon">
    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->

    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/font-awesome.min.css?version='.$version) ?>">

    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/style.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/my-style.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/themes/flat-green.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/progress-wizard.min.css?version='.$version) ?>">

</head>

<body class="flat-green landing-page">
    
    <nav class="navbar navbar-inverse navbar-fixed-top  navbar-affix" role="navigation" data-spy="affix" data-offset-top="60">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo site_url("welcome");?>">
                    <div class="icon fa fa-paper-plane"></div>
                    <div class="title"><?php echo $application_name; ?></div>
                </a>
            </div>
            <div id="navbar" class="navbar-collapse collapse " aria-expanded="true">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="#">Help</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="<?php echo site_url("login"); ?>">Login</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>

    <div class="jumbotron app-header">
        <div class="container">
            <h2 class="text-center"><div class="color-white">Start your free 30 day trial now.</div></h2>
            <p class="text-center color-white app-description">No credit card. No commitment. Just a few quick questions to set up your trial.</p>
        </div>
    </div>
    <div class="container card">
        <div class="margin-top-bottom">
            <?php if(validation_errors()) : ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <?php echo validation_errors(); ?>
                </div>
            <?php endif; ?>
            
            <div class="row text-center margin-top-bottom">
                <h2>Nearly there!<br>A few last details to set up your account.</h2>
                <div class="text-left form-container">
                    <form action="<?php echo site_url("welcome/register"); ?>" method="POST">
                        <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
                        <input type="hidden" name="retail_type" id="_retail_type">
                        <input type="hidden" name="store_quantity" id="_store_quantity">
                        <div class="form-group">
                            <label for="_store_name">Store Name</label>
                            <input type="text" name="store_name" id="_store_name" class="form-control" value="<?php echo set_value('store_name'); ?>">
                        </div>
                        <div class="form-group no-margin-bottom">
                            <div class="row">
                                <div class="col-xs-6 no-margin-bottom">
                                    <div class="form-group">
                                        <label>First name</label>
                                        <input type="text" name="first_name" class="form-control" value="<?php echo set_value('first_name'); ?>">
                                    </div>
                                </div>
                                <div class="col-xs-6 no-margin-bottom">
                                    <div class="form-group">
                                        <label>Last name</label>
                                        <input type="text" name="last_name" class="form-control" value="<?php echo set_value('last_name'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="_email_address">Email address</label>
                            <input type="email" name="email_address" id="_email_address" class="form-control" value="<?php echo set_value('email_address'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="_username">Username</label>
                            <input type="text" name="username" id="_username" class="form-control" value="<?php echo set_value('username'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="_password">Password</label>
                            <input type="password" name="password" id="_password" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label for="_phone">Phone</label>
                            <input type="text" name="phone" id="_phone" class="form-control" value="<?php echo set_value('phone'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="_city">City</label>
                            <input type="text" name="city" id="_city" class="form-control" value="<?php echo set_value('city'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="_country">Country</label>
                            <select class="form-control" name="country" id="_country">
                                <?php foreach($countries_list as $code =>  $country) : ?>
                                    <option value="<?php echo $code; ?>" <?php echo ($code == $this->input->post("country")) ? "selected" : "" ; ?>><?php echo $country?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="submit" name="submit" value="Start Checklisting with <?php echo $application_name; ?>" class="btn btn-primary btn-block text-uppercase">
                        <div class="text-center agree_terms">
                            <p>By creating your account you agree to the <br><a href="#">terms of use</a> and <a href="#">privacy policy</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /END THE FEATURETTES -->
    <!-- FOOTER -->
    <?php $this->load->view("frontend/common/footer"); ?>
    <!-- Javascript Libs -->

    <script type="text/javascript" src="<?php echo site_url('public/lib/js/jquery.min.js?version='.$version) ?>"></script>
    <script type="text/javascript" src="<?php echo site_url('public/lib/js/bootstrap.min.js?version='.$version) ?>"></script>
</body>

</html>
