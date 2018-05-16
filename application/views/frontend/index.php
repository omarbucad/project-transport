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
            <h2 class="text-center"><i class="app-logo fa fa-connectdevelop fa-5x color-white"></i><div class="color-white"><?php echo $application_name; ?></div></h2>
            <p class="text-center color-white app-description">This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
            <p class="text-center"><a class="btn btn-primary btn-lg app-btn text-uppercase" href="<?php echo site_url("welcome/register"); ?>" role="button">TRY <?php echo $application_name; ?> FOR FREE</a></p>
        </div>
    </div>
    <div class="container-fluid app-content-a">
        <div class="container">        
            <div class="row text-center">
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-5x">
                      <i class="fa fa-circle-thin fa-stack-2x"></i>
                      <i class="fa fa-twitter fa-stack-1x"></i>
                    </span>
                    <h2>Heading</h2>
                    <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-5x">
                      <i class="fa fa-circle-thin fa-stack-2x"></i>
                      <i class="fa fa-inbox fa-stack-1x"></i>
                    </span>
                    <h2>Heading</h2>
                    <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-5x">
                      <i class="fa fa-circle-thin fa-stack-2x"></i>
                      <i class="fa fa-comments-o fa-stack-1x"></i>
                    </span>
                    <h2>Heading</h2>
                    <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
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
                    <h2 class="featurette-heading">First featurette heading. It'll blow your mind.</h2>
                    <p class="lead">This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid app-content-a">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center app-content-header">Pricing</h2>
                    <p class="text-center app-content-description">This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
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
                                    <h4>Basic Plan</h4>
                                    <ul class="plan-detail">
                                        <li>1 Driver</li>
                                        <li>1 Vehicle</li>
                                        <li>1 Trailer</li>
                                        <li>50 Reports / Month</li>
                                        <li>Reports Viewable 1 Week Before Month Ends</li>
                                        <li>No Export</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table green">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing">$20</div>
                                        <div class="pricing-type">per month</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4>Standard Plan</h4>
                                    <ul class="plan-detail">
                                        <li>5 Drivers</li>
                                        <li>10 Vehicles</li>
                                        <li>10 Trailers</li>
                                        <li>1000 Reports / Month</li>
                                        <li>Data Stored for 6 Months</li>
                                        <li>With Export and Reporting</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-success">Buy Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table  dark-blue">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing">$50</div>
                                        <div class="pricing-type">per month</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4>Premium Plan</h4>
                                    <ul class="plan-detail">
                                        <li>Unlimited Drivers</li>
                                        <li>Unlimited Vehicles</li>
                                        <li>Unlimited Trailers</li>
                                        <li>Unlimited Reports</li>
                                        <li>Data Stored Forever</li>
                                        <li>With Free Trial</li>
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
        </div>
    </div>
    <div class="container-fluid app-content-b contact-us">
        <div class="container">
            <div class="row featurette">
                <div class="col-md-6"><h2 class="color-white contact-us-header">Contact Us</h2>
                <p class="color-white contact-us-description">This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p></div>
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
