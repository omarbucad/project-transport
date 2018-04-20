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
        				<h1>You're currently on the <?php echo $result->plan_type; ?> PLAN</h1>
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
        					<h4 style="margin-top: 0px;">Basic Plan</h4>
        					<nav>
        						<ul>
        							<li><span>1 Driver</span></li>
                                    <li><span> Vehicle</span></li>
                                    <li><span>1 Trailer</span></li>
                                    <li><span>50 Reports / Month</span></li>
                                    <li><span>Reports Viewable 1 Week Before Month Ends</span></li>
                                    <li><span>No Export</span></li>
        						</ul>
        					</nav>
        				</div>
        			</div>
        		</div>
        		<div class="col-xs-12">
                    <div class="trial-time">
                        <?php if($result->trial_left != 0) : ?>
                            <span>Trial Expiration: <h4 class='help-block text-danger'><?php echo $result->trial_left; ?> day(s) left</h4></span>
                        <?php else : ?>
                            <span><h4 class='help-block text-danger'>Trial Already Expired</h4></span>
                        <?php endif;?>
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
                            <div class="pricing-table green">
                                <div class="pt-header">
                                    <div class="plan-pricing">
                                        <div class="pricing">Free</div>
                                        <div class="pricing-type">until trial ends</div>
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
                                    <button type="button" class="btn btn-success">Selected Plan</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table dark-blue">
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
                                        <li>With Export and Reportting</li>
                                    </ul>
                                </div>
                                <div class="pt-footer">
                                    <button type="button" class="btn btn-primary">Select Plan</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="pricing-table dark-blue">
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
                                    <button type="button" class="btn btn-primary">Select Plan</button>
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
                    <h3>You've selected the <?php echo $result->plan_type;?> plan.</h3>
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
                    <a href="javascript:void(0);" class="btn btn-success btn-lg">Switch Plan</a>
                </div>
            </div>

        </div>
    	<?php endif; ?>
    </div>
</div>