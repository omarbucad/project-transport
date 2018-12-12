<!DOCTYPE html>
<html>

<head>
    <title><?php echo $website_title; ?></title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/img/vehicle-checklist.png') ?>" type="image/x-icon">
    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->

    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/font-awesome.min.css?version='.$version) ?>">

    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/style.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/my-style.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/themes/flat-green.css?version='.$version) ?>">

</head>
<style type="text/css">
    body{
     font-family: 'Roboto' !important;
    }
    .container{
        padding-right: 165px;
        padding-left: 165px;
        margin-right: auto;
        margin-left: auto;
    }
    .title{
        color: #777;
        font-size: 14px;
    }
    .landing-page .navbar .navbar-header .navbar-brand .icon{
        width: 45px;
    }
    .jumbotron-content > img{
        height: 215px;
        width: auto;
        position: absolute;
        top: 57px;
        right: -15px;
    }
    .app-title {
        color: #FFF;
        position: absolute;
        top: 22%;
        left: 44%;
        /*transform: translate(-50%, -50%);*/
        font-size: 40px;
    }

</style>
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
                    <div class="icon"><img src="<?php echo site_url('public/img/vehicle-checklist.png') ?>" height="25px" width="auto"></div>
                    <div class="title" style="color:#777; "><?php echo $application_name; ?></div>
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
            <div class="jumbotron-content">
                <img class="app-logo pull-right" src="<?php echo site_url('public/img/website-layout/transpo-04.png') ?>" >
                <div class="color-white app-title"><?php echo $application_name; ?></div>
                <p class="text-center color-white app-description">Manage your defect checklist hassle free.</p>
            </div>

            <div class="feature-box">
                <div class="row">
                    <div class="feature-items">
                        <!-- <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="row">
                            </div>
                        </div> -->
                        <div class="col-lg-3 col-md-3 col-xs-6" style="margin-left: 25%;">
                            <div class="row">
                                <div class="col-lg-1 col-xs-2" style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-07.png') ?>" >
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Organized FIling</span>
                                </div>                         
                                 
                            </div>
                            <div class="row">
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-06.png') ?>" >
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Unlimited access to clouds</span>
                                </div>   
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="row"> 
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-08.png') ?>" >
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Timekeeping! know when your vehicles started working</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-09.png') ?>" >
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Go Paperless</span>
                                </div>                                 
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-6 no-padding-right">
                            <div class="row">
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-10.png') ?>"  style="position: relative;left: -30px;">
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description no-padding-right">
                                    <span  style="position: relative;left: -30px;">Search by truck, drivers, dates; and defects</span>
                                </div>                            
                                
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
                
            
            
            <!-- <p class="text-center"><a class="btn btn-primary btn-lg app-btn text-uppercase" href="<?php //echo site_url("welcome/register"); ?>" role="button">TRY PREMIUM FOR FREE</a></p> -->
        </div>
    </div>
    <div class="container-fluid app-content-a feature-1" style="padding-top: 0!important;padding-bottom: 0!important;">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6" style="padding-left: 48px;">
                    <h2 class="featurrette-heading" style="font-weight: 300;color: #ffffffc7;padding-top:40px;">Vehicle Defect Report Management</h2>
                    <p style="color: #ffffff8a;">Vehicle Checklist is a robust vehicle defect monitoring system with a fully traceable route from defect to report to repair available in your desktop and smartphone.</p>
                </div>

                <div class="col-md-6 col-sm-6" style="margin-bottom: 0;">
                    <img class="feature-image" src="<?php echo site_url('public/img/website-layout/transpo-05.png') ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid app-content-b feature-1" >
        <div class="container">
            <div class="row">            
                <h2 style="color: #8e97ab; font-weight: 300;font-size: 35px;text-align: center;">Subscription pricing</h2>
                <h1 style="color: #2196f3; font-weight: 500;font-size: 50px;margin-bottom: 60px;text-align: center;"><strong>Upgrade your Plan now!</strong></h1>
                <div class="col-md-4 col-sm-6" style="padding: 0;">
                    <div class="card card-1">
                      <div class="card-header card-1-header text-center">
                          <h2><strong>Free Plan</strong></h2>
                          <p>For fleets with 1-3 vehicles.</p>
                          <p>with limited access.</p>
                      </div>
                      <div class="card-body card-1-body  text-center">
                        <div class="card-1-arrow-selected"></div>
                        <h3 style="color:#2196f3; text-align: center; font-size: 18px;padding-top: 42px;">1 to 5 vehicles</h3>
                        <h3 style="color:#2196f3; text-align: center; font-weight: 300; font-size: 18px;padding-top: 20px; padding-bottom:15px;">Monthly</h3>

                        <h3 class="card-title">Free</h3>
                        <div class="form-group text-center">
                            <a href="#" class="btn btn-info">Proceed</a>
                        </div>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">1 Driver</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">5 Vehicles</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">7 Reports Visible</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Mobile App Access</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Single Download Report</p>
                      </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6" style="padding: 0;">
                    <div class="card card-2">
                      <div class="card-header card-2-header text-center">
                          <h2><strong>Premium Plan</strong></h2>
                          <p>For fleets up to 250 vehicles.</p>
                          <p style="margin-bottom: 10px;">Choose number of vehicles.</p>
                      </div>

                      <div class="card-body card-2-body">                      
                        <div class="card-2-arrow-selected"></div>

                        <form action="<?php echo site_url();?>" method="POST" id="premium-form">
                            <div class="form-group">
                                <label>Numbers of Vehicle</label>
                                <select class="form-control" name="no_vehicles" >
                                    <option value="50">50 vehicles</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Subscription</label>
                                <select class="form-control" name="subscription">
                                    <option value="Yearly">Yearly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>

                            <h3 class="card-title">$220</h3>
                            <p style="text-align: center!important;color: #989898;"><span style="text-decoration-line: line-through;">$320</span> 25% in a year </p>
                            <div class="form-group text-center">
                                <a href="#" class="btn btn-info">Proceed</a>
                            </div>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Unlimited Drivers</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">5 Vehicles</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Unlimited Reports</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Mobile App Access</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Multiple Download Report</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Search and Filter Functions</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;padding: 0 20px 0 20px;font-weight: bold;">Add Company Profile and Company name on Report</p>
                        </form>
                      </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6" style="padding: 0;">
                    <div class="card card-3">
                      <div class="card-header card-3-header text-center">
                          <h2><strong>Enterprise Plan</strong></h2>
                          <p>For fleets of over 251+ vehicles.</p>
                          <p style="margin-bottom: 18px;">All premium features and more.</p>
                      </div>
                      <div class="card-body card-3-body text-center">
                        <div class="card-1-arrow-selected"></div>
                        <h3 style="color:#2196f3; text-align: center; font-size: 18px;padding-top: 0;">250+ vehicles</h3>
                        <h3 style="color:#2196f3; text-align: center; font-weight: 300; font-size: 18px;padding-top: 20px; padding-bottom:15px;">Monthly</h3>

                        <h3 class="card-title">POA</h3>

                        <div class="form-group text-center">
                            <a href="#" class="btn btn-info">Proceed</a>
                        </div>

                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Unlimited Drivers 250+</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Unlimited Reports</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Mobile App Access</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Multiple Download Report</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Search and Filter Functions</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;padding: 0 20px 0 20px;font-weight: bold;">Add Company Profile and Company name on Report</p>
                        <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Company reports</p>
                      </div>
                    </div>
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
