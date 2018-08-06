<!DOCTYPE html>
<html>

<head>
    <title><?php echo $website_title; ?></title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/img/favicon.png') ?>" type="image/x-icon">
    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->

    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/font-awesome.min.css?version='.$version) ?>">

    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/style.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/my-style.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/themes/flat-green.css?version='.$version) ?>">

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
                <a class="navbar-brand" href="<?php echo site_url("welcome"); ?>">
                    <div class="icon"><img src="<?php echo site_url('public/img/favicon.png') ?>" height="30px" width="auto"></div>
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
            <h2 class="text-center"><img class="app-logo" src="<?php echo site_url('public/img/favicon.png') ?>"><div class="color-white"><?php echo $application_name; ?></div></h2>
            <p class="text-center color-white app-description">Complete your defect checklist in minutes.</p>
            <p class="text-center"><a class="btn btn-primary btn-lg app-btn text-uppercase" href="<?php echo site_url("welcome/register"); ?>" role="button">TRY PREMIUM FOR FREE</a></p>
        </div>
    </div>
    <div class="container-fluid app-content-a">
        <div class="container">        
            <div class="row text-center">
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-5x">
                      <i class="fa fa-bolt fa-stack-1x"></i>
                    </span>
                    <h2>Automated</h2>
                    <p>Increase efficiency in checklist report  management and vehicle maintenance.</p>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-5x">
                      <i class="fa fa-cogs fa-stack-1x"></i>
                    </span>
                    <h2>Customizable</h2>
                    <p>Start with the default checklist or create your own checklist</p>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-5x">
                      <i class="fa fa-mobile fa-stack-1x"></i>
                    </span>
                    <h2>Mobile App</h2>
                    <p>Access your data on the go.</p>
                </div>
                <!-- /.col-lg-4 -->
            </div>
        </div>
    </div>
    <div class="container-fluid app-content-b feature-1">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-6">
                </div>
                <div class="col-md-5 col-sm-6 text-right color-white">
                    <h2 class="featurette-heading">Manage your reports anywhere you go.</h2>
                    <p class="lead">Transport Checklist is a robust defect monitoring system with a fully traceable route from defect to report to repair available in your desktop and smartphone.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid app-content-a">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center app-content-header">Pricing</h2>
                    <p class="text-center app-content-description"></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row no-margin no-gap">
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table dark-blue">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing">Free</div>
                                        <div class="pricing-type">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4><?php echo $user_plans[0]->title ." Plan"; ?></h4>
                                    <ul class="plan-detail">
                                        <li><?php echo $user_plans[0]->no_accounts; ?> Account</li>
                                        <li><?php echo $user_plans[0]->no_vehicle; ?> Vehicle/Trailer</li>
                                        <li><?php echo $user_plans[0]->no_reports; ?> Reports / Month</li>
                                        <li><?php echo $user_plans[0]->description; ?></li>
                                        <li>No Export</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table dark-blue">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing"><?php echo "$".$user_plans[1]->plan_price; ?></div>
                                        <div class="pricing-type">per month</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4><?php echo $user_plans[1]->title ." Plan"; ?></h4>
                                    <ul class="plan-detail">
                                        <li><?php echo $user_plans[1]->no_accounts; ?> Accounts</li>
                                        <li><?php echo $user_plans[1]->no_vehicle; ?> Vehicles & Trailers</li>
                                        <li><?php echo $user_plans[1]->no_reports; ?> Reports / Month</li>
                                        <li><?php echo $user_plans[1]->description; ?></li>
                                        <li>With Export and Reporting</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table  dark-blue">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing"><?php echo "$".$user_plans[2]->plan_price; ?></div>
                                        <div class="pricing-type">per month</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4><?php echo $user_plans[2]->title ." Plan"; ?></h4>
                                    <ul class="plan-detail">
                                        <li>Unlimited Accounts</li>
                                        <li>Unlimited Vehicles & Trailers</li>
                                        <li>Unlimited Reports</li>
                                        <li><?php echo $user_plans[2]->description; ?></li>
                                        <li>&nbsp;</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Buy Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <p class="text-center app-content-description">Plan offers doesn't fit your needs? Click <a href="#" class="text-danger">here</a> to request a Quote.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid app-content-b contact-us">
        <div class="container">
            <div class="row featurette">
                <div class="col-md-6"><h2 class="color-white contact-us-header">Contact Us</h2>
                <p class="color-white contact-us-description">Trackerteer is here to provide you with more information and answer any questions. Please don't hesitate to get in touch with us.</p></div>
                <div class="col-md-6">
                    <form>

                        <div class="row">
                            <div class="col-sm-6"><input id="name" name="name" type="text" class="form-control" placeholder="Full Name"> </div>
                            <div class="col-sm-6"><input id="email" name="email" type="email" class="form-control" placeholder="Email address"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12"><textarea id="message" name="message" class="form-control" placeholder="Your Message" rows="5"></textarea></div>
                        </div>
                        <div>
                            <button id="contact-submit" type="submit" class="btn btn-success pull-right">Send</button>
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
