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
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container">
        	<h1>Account</h1>
        	<div class="account_container_btn">
        		<a href="<?php echo site_url("app/setup/account/manage"); ?>" class="<?php echo ($setup_page == "manage") ? "active" : "" ;?>" >Manage My Account</a>
        		<a href="<?php echo site_url("app/setup/account/pricing"); ?>" class="<?php echo ($setup_page == "pricing") ? "active" : "" ;?>">View Pricing Plans</a>
        	</div>
        </div>
        <?php if($setup_page == "manage") : ?>
        <div class="grey-bg margin-bottom">
        	<div class="container ">
        		<span>Manage your account plan, and payment details. <a href="#" class="text-underline">need help?</a></span>
        	</div>
        </div>
        <div class="row ">
        	<div class="container">
        		<div class="card">
        			<div class="card-body text-center">
        				<h1>You're currently on the <?php echo $result->title; ?> PLAN</h1>
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
        		<div class="col-xs-12 col-lg-6">
        			<div class="row">
        				<div class="col-xs-12 col-lg-6">
        					<span>View a breakdown of your current plan. To find out what more you could get, <a href="<?php echo site_url("app/setup/account/pricing"); ?>" class="text-underline">check out our other plans.</a></span>
        				</div>
        				<div class="col-xs-12 col-lg-6">
        					<h4 style="margin-top: 0px;"><?php echo $result->title ." Plan";?></h4>
        					<nav>
        						<ul>
        							<li><span><?php echo ($result->no_accounts == 0) ? "Unlimited": $result->no_accounts;?> Driver</span></li>
                                    <li><span><?php echo ($result->no_vehicle == 0) ? "Unlimited": $result->no_vehicle;?> Vehicle</span></li>
                                    <li><span><?php echo ($result->no_vehicle == 0) ? "Unlimited": $result->no_vehicle;?> Trailer</span></li>
                                    <li><span><?php echo ($result->no_reports == 0) ? "Unlimited": $result->no_reports;?> / Month</span></li>
                                    <li><span><?php echo $result->description;?></span></li>
        						</ul>
        					</nav>
        				</div>
        			</div>
        		</div>
        		<div class="col-xs-12">
                    <div class="trial-time">
                        <?php if($result->title == 'TRIAL') : ?>
                            <?php if(!($result->trial_left < 0)) : ?>
                                <span>Trial Expiration: <h4 class='help-block text-danger'><?php echo $result->trial_left; ?> day(s) left</h4></span>
                            <?php else : ?>
                                <span><h4 class='help-block text-danger'>Trial Already Expired</h4></span>
                            <?php endif;?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-xs-12">
                    <a href="#" class="text-danger">Delete Account</a>
                </div>
        	</div>
        </div>
    	<?php else : ?>
        <div class="grey-bg margin-bottom">
            <div class="container ">
                <span>Select a plan to get the best out of Transport App. <a href="#" class="text-underline">Compare plan details.</a></span>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row no-margin no-gap">
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table green" id="div-basic">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing">Free</div>
                                        <div class="pricing-type">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4><?php echo $result->title ." Plan"; ?></h4>
                                    <ul class="plan-detail">
                                        <li>1 Account</li>
                                        <li>1 Vehicle/Trailer</li>
                                        <li>50 Reports / Month</li>
                                        <li>Reports Viewable 1 Week Before Month Ends</li>
                                        <li>No Export</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-success" id="btn-basic">Selected Plan</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table dark-blue" id="div-standard">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing">$20</div>
                                        <div class="pricing-type">per month</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4>Standard Plan</h4>
                                    <ul class="plan-detail">
                                        <li>5 Accounts</li>
                                        <li>10 Vehicles & Trailers</li>
                                        <li>1000 Reports / Month</li>
                                        <li>Data Stored for 6 Months</li>
                                        <li>With Export and Reporting</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary" id="btn-standard">Select Plan</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table dark-blue" id="div-premium">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing">$50</div>
                                        <div class="pricing-type">per month</div>
                                    </div>
                                </div>
                                <div class="pt-body">
                                    <h4>Premium Plan</h4>
                                    <ul class="plan-detail">
                                        <li>Unlimited Accounts</li>
                                        <li>Unlimited Vehicles & Trailers</li>
                                        <li>Unlimited Reports</li>
                                        <li>Data Stored Forever</li>
                                        <li>With Free Trial</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary" id="btn-premium">Select Plan</button>
                                </div>
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
                        
                        <a href="javascript:void(0);" class="btn btn-success btn-lg">Switch Plan</a>
                    </form>
                </div>
            </div>

        </div>
    	<?php endif; ?>
    </div>
</div>