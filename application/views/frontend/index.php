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
                    <div class="icon"><img src="<?php echo site_url('public/img/vehicle-checklist.png') ?>" height="30px" width="auto"></div>
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
            <h2 class="text-center"><img class="app-logo" src="<?php echo site_url('public/img/vehicle-checklist.png') ?>" height="100px" width="auto"><div class="color-white"><?php echo $application_name; ?></div></h2>
            <p class="text-center color-white app-description">Complete your defect checklist in minutes.</p>
            <!-- <p class="text-center"><a class="btn btn-primary btn-lg app-btn text-uppercase" href="<?php //echo site_url("welcome/register"); ?>" role="button">TRY PREMIUM FOR FREE</a></p> -->
        </div>
    </div>
    <div class="container-fluid app-content-a">
        <div class="container">        
            <div class="row text-center">
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-4x">
                      <i class="fa fa-bolt fa-stack-1x"></i>
                    </span>
                    <h2>Go Digital</h2>
                    <p>Increase efficiency in paperless checklist reports management.</p>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-4x">
                      <i class="fa fa-pencil fa-stack-1x"></i>
                    </span>
                    <h2>Signature Capture</h2>
                    <p>Requires a driver signature at the end of report.</p>
                </div>
                <!-- /.col-lg-4 -->
                <!-- <div class="col-md-3 col-sm-6">
                    <span class="fa-stack fa-lg fa-4x">
                      <i class="fa fa-cloud fa-stack-1x"></i>
                    </span>
                    <h2>Secured Data</h2>
                    <p>Keep your data safe and secured with our cloud storage</p>
                </div> -->
                <!-- /.col-lg-4 -->
                <div class="col-md-4 col-sm-6">
                    <span class="fa-stack fa-lg fa-4x">
                      <i class="fa fa-cloud fa-stack-1x"></i>
                    </span>
                    <h2>Secured Data</h2>
                    <p>Keep your data safe and secured with our cloud storage</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid app-content-b feature-1">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-6">
                </div>
                <div class="col-md-5 col-sm-6 text-right color-white">
                    <h2 class="featurette-heading">Vehicle Defect Report Management</h2>
                    <p class="lead">Vehicle Checklist is a robust vehicle defect monitoring system with a fully traceable route from defect to report to repair available in your desktop and smartphone.</p>
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
                <div class="col-md-8 col-md-offset-2">
                    <div class="row no-margin no-gap">
                        <div class="col-md-6 col-sm-6">
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
                                        <li><?php echo $user_plans[0]->no_vehicle; ?> Vehicle/Trailer</li>
                                        <li><?php echo $user_plans[0]->no_reports; ?> Reports / Month</li>
                                        <li><?php echo $user_plans[0]->description; ?></li>
                                        <li>No Export</li>
                                    </ul>
                                </div>
                                <!-- <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Buy Now</button>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
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
                                        <li>Unlimited Vehicles & Trailers</li>
                                        <li>Unlimited Reports</li>
                                        <li><?php echo $user_plans[2]->description; ?></li>
                                        <li>1 month Free Trial</li>
                                    </ul>
                                </div>
<!--                                 <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Buy Now</button>
                                    <div id="paypal-button-container"></div>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
// Render the PayPal button
paypal.Button.render({
// Set your environment
env: 'sandbox', // sandbox | production
locale: 'en_US',

// Specify the style of the button
style: {
  layout: 'horizontal',  // horizontal | vertical
  size:   'small',    // medium | large | responsive
  shape:  'pill',      // pill | rect
  color:  'gold'       // gold | blue | silver | white | black
},

// Specify allowed and disallowed funding sources
//
// Options:
// - paypal.FUNDING.CARD
// - paypal.FUNDING.CREDIT
// - paypal.FUNDING.ELV
funding: {
  allowed: [
    paypal.FUNDING.CARD,
    paypal.FUNDING.CREDIT
  ],
  disallowed: []
},

// PayPal Client IDs - replace with your own
// Create a PayPal app: https://developer.paypal.com/developer/applications/create
client: {
  sandbox: 'AZSn55ni7EDdn8QF6MwtylirO5YxaS6qTbcxIMsLtgwKY8vttQwu3flnq2QlTZGjcEDzeFPBWaSf9PlN',
  production: '<insert production client id>'
},

// Set up a payment
payment: function(data, actions) {
  return actions.payment.create({
    transactions: [{
      amount: {
        total: '50.01',
        currency: 'USD',
        details: {
          subtotal: '50.00',
          tax: '0.01',
          shipping: '0.00'
          //handling_fee: '1.00',
         //shipping_discount: '-1.00',
          //insurance: '0.01'
        }
      },
      description: 'The payment transaction description.',
      custom: '90048630024435',
      //invoice_number: '12345', Insert a unique invoice number
      payment_options: {
        allowed_payment_method: 'INSTANT_FUNDING_SOURCE'
      },
      soft_descriptor: 'ECHI5786786',
      item_list: {
        items: [
        {
          name: 'Standard Plan',
          description: 'Plan',
          quantity: '1',
          price: '50',
          tax: '0.01',
          sku: '2',
          currency: 'USD'
        }
        ]// ],
        // shipping_address: {
        //   recipient_name: 'Test',
        //   line1: '4th Floor',
        //   line2: 'Unit #34',
        //   city: 'San Jose',
        //   country_code: 'US',
        //   postal_code: '95131',
        //   phone: '011862212345678',
        //   state: 'CA'
        // }
      }
    }],
    note_to_payer: 'Contact us for any questions on your subscription.'
  });
},
onAuthorize: function (data, actions) {
    console.log(actions);
  return actions.payment.execute()
    .then(function () {
      window.alert('Payment Complete!');
    });
}
}, '#paypal-button-container');
</script> 
                                </div>-->
                            </div>
                        </div>
                        <!-- <div class="col-md-4 col-sm-6">
                            <div class="pricing-table  dark-blue">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing"><?php //echo "$".$user_plans[2]->plan_price; ?></div>
                                        <div class="pricing-type">per month</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4><?php //echo $user_plans[2]->title ." Plan"; ?></h4>
                                    <ul class="plan-detail">
                                        <li>Unlimited Vehicles & Trailers</li>
                                        <li>Unlimited Reports</li>
                                        <li><?php //echo $user_plans[2]->description; ?></li>
                                        <li>&nbsp;</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Buy Now</button>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
           <!--  <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <p class="text-center app-content-description">Plan offers doesn't fit your needs? Click <a href="#" class="text-danger">here</a> to request a Quote.</p>
                </div>
            </div> -->
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
