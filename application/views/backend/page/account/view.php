<script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->

<script type="text/javascript">
    
    $(document).ready(function(){      
       
        var activediv = $('div.no-gap').find('div.green');
        var id = activediv.attr("id");

        if(id == "div-basic"){
            $('#plan-selected').text("Basic Plan");
        }else if(id == "div-standard"){
            $('#plan-selected').text("Standard Plan");
        }else{
            $('#plan-selected').text("Premium Plan");
        }
    });
    $(document).on("click", "#btn-basic", function(){
        if(!$('#div-basic').hasClass("green")){

            $('#div-basic').addClass("green");
            $('#btn-basic').addClass("btn-success");
            $('#btn-basic').text("Selected Plan");
            $('#plan-selected').text("Basic Plan");

            $('#btn-basic').removeClass("btn-primary");
            $('#div-basic').removeClass("dark-blue");

            if($('#div-standard').hasClass("green")){
                $('#div-standard').removeClass("green");
                $('#btn-standard').removeClass("btn-success");
                $('#btn-standard').text("Select Plan");

                $('#div-standard').addClass("dark-blue");
                $('#btn-standard').addClass("btn-primary");
            }

            if($('#div-premium').hasClass("green")){
                $('#div-premium').removeClass("green");
                $('#btn-premium').removeClass("btn-success");
                $('#btn-premium').text("Select Plan");

                $('#div-premium').addClass("dark-blue");
                $('#btn-premium').addClass("btn-primary");
            }
        }
    });
    $(document).on("click", "#btn-standard", function(){
        if(!$('#div-standard').hasClass("green")){

            $('#div-standard').addClass("green");
            $('#btn-standard').addClass("btn-success");
            $('#btn-standard').text("Selected Plan");
            $('#plan-selected').text("Standard Plan");
            
            $('#btn-standard').removeClass("btn-primary");
            $('#div-standard').removeClass("dark-blue");

            if($('#div-basic').hasClass("green")){
                $('#div-basic').removeClass("green");
                $('#btn-basic').removeClass("btn-success");
                $('#btn-basic').text("Select Plan");

                $('#div-basic').addClass("dark-blue");
                $('#btn-basic').addClass("btn-primary");
            }

            if($('#div-premium').hasClass("green")){
                $('#div-premium').removeClass("green");
                $('#btn-premium').removeClass("btn-success");
                $('#btn-premium').text("Select Plan");

                $('#div-premium').addClass("dark-blue");
                $('#btn-premium').addClass("btn-primary");
            }
        }
    });
    $(document).on("click", "#btn-premium", function(){
        if(!$('#div-premium').hasClass("green")){

            $('#div-premium').addClass("green");
            $('#btn-premium').addClass("btn-success");
            $('#btn-premium').text("Selected Plan");
            $('#plan-selected').text("Premium Plan");
            
            $('#btn-premium').removeClass("btn-primary");
            $('#div-premium').removeClass("dark-blue");

            if($('#div-standard').hasClass("green")){
                $('#div-standard').removeClass("green");
                $('#btn-standard').removeClass("btn-success");
                $('#btn-standard').text("Select Plan");

                $('#div-standard').addClass("dark-blue");
                $('#btn-standard').addClass("btn-primary");
            }

            if($('#div-basic').hasClass("green")){
                $('#div-basic').removeClass("green");
                $('#btn-basic').removeClass("btn-success");
                $('#btn-basic').text("Select Plan");

                $('#div-basic').addClass("dark-blue");
                $('#btn-basic').addClass("btn-primary");
            }
        }
    });

    $(document).on("click","div.annually", function(){
        if(!$("div.annually").hasClass("active")){
            $("div.annually").addClass("active");
            $("div.monthly").removeClass("active");
        }
    });
    $(document).on("click","div.monthly", function(){
        if(!$("div.monthly").hasClass("active")){
            $("div.monthly").addClass("active");
            $("div.annually").removeClass("active");
        }
    });


     $(document).on('click' , '.view_invoice_pdf' , function(){

        var modal = $('#invoice_pdf_modal').modal("show");

        var id = $(this).data("id");

        var a = $("<a>" , {href : $(this).data("pdf") , text:$(this).data("pdf") });
        var object = '<object data="'+$(this).data("pdf") +'" , type="application/pdf" style="width:100%;height:800px;">'+a+'</object>';

        $('#_pdfViewer').html(object);  
    });

    // $.ajax({
    //     url: "<?php //echo site_url("app/setup/get_client_token"); ?>",
    //     type: "GET",
    //     dataType: "json",
    //     success: function(data){
    //         braintree.setup(data, 'dropin', {container : 'dropin-container'});
    //     }
    //  });

     $(document).on('click', '.btn-proceed', function(){
        var modal = $('#payment_modal').modal("show");
        var body = modal.find('.modal-body');
        body.html("");
        body.html("<form action='<?php echo site_url('app/setup/pay'); ?>' method='POST' class='payment-form text-center'><input type='hidden' name='planId' value=''><div id='dropin-container'></div><button type='submit' class='btn btn-info'>Checkout</button></form>");

        $.ajax({
            url: "<?php echo site_url("app/setup/get_client_token"); ?>",
            type: "GET",
            dataType: "json",
            success: function(data){
                braintree.setup(data, 'dropin', {container : 'dropin-container'});
            }
         });
        
        modal.find("input[type='hidden']").val($(this).data("desc"));          
        
     });


     
</script>
<style type="text/css">
    th, td {
        padding-left: 0 !important;
    }
    a.view_invoice_pdf{
        padding: 0;
    }
    .app-content-b.feature-1 {
        background-image: url("<?php echo site_url('public/img/reset-pass.jpg');?>");
        background-repeat: no-repeat;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        background-position: bottom;
        margin-bottom: 0; 
        height: 190%;
    }
    label.heading {
        font-weight: 600;
    }
    .payment-form{
        width: 300px;
        margin-left: auto;
        margin-right: auto;
        padding: 10px;
        border: 1px #333 solid;
    }
</style>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container">
        	<h1>Plan Subscription</h1>
        	<div class="account_container_btn">
        		<a href="<?php echo site_url("app/setup/account/manage"); ?>" class="<?php echo ($setup_page == "manage") ? "active" : "" ;?>" >Manage My Account</a>
        		<a href="<?php echo site_url("app/setup/account/pricing"); ?>" class="<?php echo ($setup_page == "pricing") ? "active" : "" ;?>">View Pricing Plans</a>
        	</div>
        </div>
        
        <?php if($setup_page == "manage") : ?>
        <div class="grey-bg margin-bottom">
        	<div class="container ">
        		<span>Manage your account plan, and payment details.</span>
        	</div>
        </div>
        <div class="row ">
        	<div class="container">
        		<div class="card">
        			<div class="card-body text-center">
        				<h1>You're currently on the <?php echo $result->title; ?> Plan</h1>
        				<p>Whether you need to add a store, a register, or our most advanced features, <br>Trackerteer is on hand to upgrade your business.</p>
        				<a href="<?php echo site_url("app/setup/account/pricing"); ?>" class="btn btn-success btn-lg">View our Pricing plans</a>
        			</div>
        		</div>
        	</div>
        </div>
        <div class="container">
        	<div class="row">
        		<div class="col-xs-12">
        			<h4>What's in my plan</h4>
        		</div>
        		<div class="col-xs-12">
        			<div class="row">
        				<div class="col-xs-12 col-lg-3">
        					<span>View a breakdown of your current plan. To find out what more you could get, <a href="<?php echo site_url("app/setup/account/pricing"); ?>" class="text-underline">check out our other plans.</a></span>
        				</div>
        				<div class="col-xs-12 col-lg-9">
        					<h4 style="margin-top: 0px;"><?php echo $result->title ." Plan";?></h4>
        					<nav>
        						<ul>
        							
                                    <!-- <li><span><?php //echo ($result->no_vehicle == 0) ? "Unlimited": $result->no_vehicle;?> Vehicle / Trailer</span></li>
                                    <li><span><?php //echo ($result->no_vehicle == 0) ? "Unlimited": $result->no_vehicle;?> Trailer</span></li> 
                                    <li><span><?php //echo ($result->no_reports == 0) ? "Unlimited": $result->no_reports;?> Report(s)<?php //echo ($result->title == "Trial") ? "" : "/ Month"; ?></span></li>
                                    <li><span><?php //echo $result->description;?></span></li> -->
        						</ul>
        					</nav>
        				</div>
        			</div>
        		</div>
        		<div class="col-xs-12">
                    <div class="trial-time">
                        <?php if($result->title == 'Trial') : ?>
                            <?php if(!($result->trial_left < 0)) : ?>
                                <span>Trial Expiration: <h4 class='help-block text-danger'><?php echo $result->trial_left; ?></h4></span>
                            <?php else : ?>
                                <span><h4 class='help-block text-danger'>Trial Already Expired</h4></span>
                            <?php endif;?>
                        <?php else: ?>
                            <?php if(!($result->trial_left < 0)) : ?>
                                <span>Plan Expires in: <h4 class='help-block text-danger'><?php echo $result->trial_left; ?></h4></span>
                            <?php else : ?>
                                <span><h4 class='help-block text-danger'>Plan Already Expired</h4></span>
                            <?php endif;?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-xs-12">
                    <h4>User Plan</h4>
                </div>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-lg-3">
                            <span>List of user plan subscription.</span>
                        </div>
                        <div class="col-xs-12 col-lg-9">
                            <table class="table">
                                <tr>
                                    <th>Plan</th>
                                    <th>Billing Type</th>
                                    <th>Created</th>
                                    <th>Expiration</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                                <?php foreach($user_data->subscription as $key => $value) : ?>
                                    <tr>
                                        <td><span><?php echo $value->title; ?></span></td>
                                        <td><span><?php echo $value->billing_type; ?></span></td>
                                        <td><span><?php echo $value->plan_created; ?></span></td>
                                        <td><span><?php echo $value->plan_expiration; ?></span></td>
                                        <!-- <td><a href="javascript:void(0);" data-pdf="<?php //echo site_url().$value->invoice_pdf;?>" data-id="<?php //echo $value->invoice_id; ?>" class="btn btn-link view_invoice_pdf" title="View Invoice"><i class="fa fa-search" aria-hidden="true"></i> View Invoice</a></td> -->
                                    </tr>                                    
                                <?php endforeach; ?>  
                            </table>                         
                        </div>
                    </div>
                </div>
        	</div>
        </div>
    	<?php else : ?>
        <div class="grey-bg margin-bottom">
            <div class="container ">
                <span>Select a plan to get the best out of Vehicle Checklist App.</span>
            </div>
        </div>
        <div class="container">
            <div class="container-fluid app-content-b feature-1" >
                <div class="row">            
                    <h2 style="color: #8e97ab; font-weight: 300;font-size: 35px;text-align: center;font-family: 'Roboto',sans-serif !important;">Subscription pricing</h2>
                    <h1 style="color: #2196f3; font-weight: 500;font-size: 50px;margin-bottom: 60px;text-align: center;"><strong style="font-family: 'Roboto',sans-serif !important;">Upgrade your Plan now!</strong></h1>
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
                                <a href="javascript:void(0);" class="btn btn-info btn-proceed" data-desc="sandbox_free_trial">Proceed</a>
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
                                    <input type="number" name="no_vehicles" class="form-control">
                                    <!-- <select class="form-control" name="" >
                                        <option value="50">50 vehicles</option>
                                    </select> -->
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
                                    <a href="javascript:void(0);" class="btn btn-info btn-proceed" data-desc="sandbox_basic_plan_trial_3">Proceed</a>
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
                                <a href="javascript:void(0);" class="btn btn-info btn-proceed" data-desc="sandbox_basic_plan_trial_3">Proceed</a>
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
            <hr>
            <div class="row">
                <div class="col-xs-12 col-lg-4">
                    <h3>Billing</h3>
                </div>
                <div class="col-xs-12 col-lg-8">
                    <h3>You've selected the <span id="plan-selected"></span>.</h3>
                    <span class="help-block">How do you want to be billed?</span>
                    <hr>
                    <div class="billed_container">
                        <div class="annually active">
                            <h4 class="text-center">Annual Billing</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    Get your bill once a year.
                                </div>
                                <div class="col-xs-6"></div>
                            </div>
                        </div>
                        <div class="monthly">
                            <h4 class="text-center">Monthly Billing</h4>
                            <div class="row">
                                <div class="col-xs-6">
                                    Get your bill once a month.
                                </div>
                                <div class="col-xs-6"></div>
                            </div>
                        </div>
                    </div>
                    <form action="<?php echo ""; ?>" method="post" name="frmPayPal1">
                        <input type="hidden" name="first_name" value="Customer's First Name" />
                        <input type="hidden" name="last_name" value="Customer's Last Name" />
                        <input type="hidden" name="payer_email" value="customer@example.com" />
                        <input type="hidden" name="item_number" value="123456" / >
                        <?php if(!$this->session->userdata("user")->expired): ?>
                            <a href="javascript:void(0);" class="btn btn-success btn-lg">Switch Plan</a>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-success btn-lg">Buy Now</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

        </div>
    	<?php endif; ?>
    </div>
</div>

<div class="modal fade" id="invoice_pdf_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="defaultModalLabel">Invoice Information</h4>
            </div>
            <div class="modal-body" id="_pdfViewer">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payment_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="defaultModalLabel">Payment</h4>
                <small><strong style="color: red;">**Note: This will replace your current subscription. Any changes made can't reverted.</strong></small>
            </div>
             
            <div class="modal-body">

              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>