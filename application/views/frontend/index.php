<!DOCTYPE html>
<html>

<head>
    <title><?php echo $website_title; ?></title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Favicon-->
    <link rel="icon" href="<?php echo site_url('public/img/vehicle-checklist.png') ?>" type="image/x-icon">
    <!-- Fonts -->
   <!--  <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700,900' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'> -->
    <!-- CSS Libs -->

    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap.min.css?version='.$version) ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/font-awesome.min.css?version='.$version) ?>">


<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/animate.min.css?version='.$version) ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/sweetalert2.min.css?version='.$version) ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap-switch.min.css?version='.$version) ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/select2.min.css?version='.$version) ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/bootstrap-tagsinput.css?version='.$version) ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/checkbox3.min.css?version='.$version) ?>">

<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/multi-select.css?version='.$version) ?>">

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/lib/css/lightgallery.min.css?version='.$version) ?>">


<script type="text/javascript" src="<?php echo site_url('public/lib/js/jquery.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="<?php echo site_url('public/lib/js/bootstrap.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="<?php echo site_url('public/lib/js/sweetalert2.all.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="<?php echo site_url('public/lib/js/bootstrap-switch.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="<?php echo site_url('public/lib/js/select2.full.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="<?php echo site_url('public/lib/js/bootstrap-tagsinput.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

<script type="text/javascript" src="<?php echo site_url('public/lib/tinymce/tinymce.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="<?php echo site_url('public/lib/js/lightgallery-all.min.js?version='.$version) ?>"></script>


<script type="text/javascript" src="<?php echo site_url('public/js/notify.min.js?version='.$version) ?>"></script>
<script type="text/javascript" src="<?php echo site_url('public/js/jquery.multi-select.js?version='.$version) ?>"></script>

<script type="text/javascript" src="<?php echo site_url('public/js/app.js?version='.$version) ?>"></script>


<!-- CSS App -->
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/style.css?version='.$version) ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/my-style.css?version='.$version) ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('public/css/themes/flat-green.css?version='.$version) ?>">



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
        position: relative;
        top: -35px;
        right: 0;
        left: 32.5%;
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
    <?php if($this->session->flashdata("status")) : ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $.notify("<?php echo $this->session->flashdata("message"); ?>" , { className:  "<?php echo $this->session->flashdata("status"); ?>" , position : "top center"});
            });
        </script>
    <?php endif; ?>
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
                    <div class="icon"><img src="<?php echo site_url('public/img/vehicle-checklist.png') ?>" height="25px" width="auto" alt="Vehicle Checklist Logo"></div>
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

            <script type="text/javascript">
                $(document).on("click" , "#contact-submit" , function(){
                    var form = $("#contactForm");
                    $.ajax({
                        url : "<?php echo site_url('welcome/emailus'); ?>",
                        method : "POST",
                        data : form.serialize() ,
                        success : function(response){
                            var json = jQuery.parseJSON(response);
                            $.notify(json.message , { className:  json.status , position : "top center"});
                            setTimeout(function () { 
                                  location.reload();
                                }, 2000);
                        }
                        
                    });
                });
            </script>
            
            <div class="jumbotron-content">
                <img class="app-logo pull-right" src="<?php echo site_url('public/img/website-layout/transpo-04.png') ?>" alt="Section 1 background">
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
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-07.png') ?>" alt="Organized Filing icon">
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Organized Filing</span>
                                </div>                         
                                 
                            </div>
                            <div class="row">
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-06.png') ?>" alt="Unlimited access to clouds icon">
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Unlimited access to clouds</span>
                                </div>   
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-6">
                            <div class="row"> 
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-08.png') ?>" alt="Timekeeping icon">
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Timekeeping! know when your vehicles started working</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-09.png') ?>" alt="Paperless icon">
                                </div>  
                                <div class="col-lg-11 col-xs-10 feature-description">
                                    <span>Go Paperless</span>
                                </div>                                 
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-xs-6 no-padding-right">
                            <div class="row">
                                <div class="col-lg-1 col-xs-2"  style="padding: 0;">
                                    <img class="feature-icon" src="<?php echo site_url('public/img/website-layout/website-transpo-10.png') ?>"  style="position: relative;left: -30px;" alt="Search icon">
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
                <h1 style="color: #2196f3; font-weight: 500;font-size: 50px;margin-bottom: 60px;text-align: center;"><strong style="font-family: 'Roboto' !important;">Subscription pricing</strong></h1>
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
                        <hr>
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
                          <p style="margin-bottom: 10px;">Price depends on number of vehicles</p>
                      </div>

                      <div class="card-body card-2-body">                      
                        <div class="card-2-arrow-selected"></div>
                            <h3 style="color:#2196f3; text-align: center; font-size: 18px;padding-top: 10px;">10% discount if Yearly</h3>
                            <h3 style="color:#2196f3; text-align: center; font-weight: 300; font-size: 18px;padding-top: 30px; padding-bottom:15px;">Monthly / Yearly</h3>

                            <h3 class="card-title" style="padding-top: 10px; padding-bottom: 6px;">$2 / vehicle</h3>
                            <hr>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Unlimited Drivers</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">5 Vehicles</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Unlimited Reports</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Mobile App Access</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Multiple Download Report</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;font-weight: bold;">Search and Filter Functions</p>
                            <p class="text-center" style="margin: 30px 0 30px 0; color: #656565;padding: 0 20px 0 20px;font-weight: bold;">Add Company Profile and Company name on Report</p>
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
                        <h3 style="color:#2196f3; text-align: center; font-size: 18px;padding-top: 3px;">250+ vehicles</h3>
                        <h3 style="color:#2196f3; text-align: center; font-weight: 300; font-size: 18px;padding-top: 23px; padding-bottom:15px;">Monthly</h3>

                        <h3 class="card-title" style="margin-bottom: 27px;">POA</h3>
                        <hr>
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
                <div class="col-md-6 col-xs-12" style="padding-right: 30px;padding-left: 25px;">
                    <h2 class="color-white contact-us-header">Contact Us</h2>
                    <p class="color-white contact-us-description"><?php echo $application_name; ?> Team is here to provide you with more information and answer any questions. Please don't hesitate to get in touch with us.</p>
                </div>
                <div class="col-md-6 col-xs-12" style="padding:0!important;">
                    <form action="" method="POST" id="contactForm">
                            <?php if($this->session->flashdata("status")) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <p><?php echo $this->session->flashdata("message"); ?></p>
                                </div>
                            <?php endif;?>
                            <?php $this->session->sess_destroy(); ?>
                        <div class="row">
                            <div class="col-sm-6"><input id="fullname" name="fullname" type="text" class="form-control" placeholder="Full Name" required="true"></div>
                            <div class="col-sm-6"><input id="email" name="email" type="email" class="form-control" placeholder="Email address" required="true"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" style="margin-bottom: 10px;"><textarea id="message" name="message" class="form-control" placeholder="Your Message" rows="3" required="true"></textarea></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <a id="contact-submit" class="btn btn-success pull-right" style="padding: 4px 55px;font-size: 12px;font-family: 'Roboto';">Submit</a>
                            </div>
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

    <!-- 
    <script type="text/javascript" src="<?php //echo site_url('public/lib/js/jquery.min.js?version='.$version) ?>"></script>
    <script type="text/javascript" src="<?php// echo site_url('public/lib/js/bootstrap.min.js?version='.$version) ?>"></script> -->


</body>

</html>
