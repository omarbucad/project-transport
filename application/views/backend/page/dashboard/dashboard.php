<script type="text/javascript">
	$(document).on("click" , "#total-trucks" , function(){
    	$(".panel-trucks").toggle();

    	$(".panel-active-trucks").attr("style", "display: none");
    	$(".panel-inactive-trucks").attr("style", "display: none");
    	$(".panel-active-trailers").attr("style", "display: none");
    	$(".panel-trailers").attr("style", "display: none");
    	$(".panel-inactive-trailers").attr("style", "display: none");

    	$(".panel-active-trucks").parent().addClass("hidden");
        $(".panel-inactive-trucks").parent().addClass("hidden");
        $(".panel-active-trailers").parent().addClass("hidden");
        $(".panel-trailers").parent().addClass("hidden");
        $(".panel-inactive-trailers").parent().addClass("hidden");

        if($(".panel-trucks").parent().hasClass("hidden")){
            $(".panel-trucks").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#active-trucks" , function(){
    	$(".panel-active-trucks").toggle();

    	$(".panel-trucks").attr("style", "display: none");
    	$(".panel-inactive-trucks").attr("style", "display: none");
    	$(".panel-active-trailers").attr("style", "display: none");
    	$(".panel-trailers").attr("style", "display: none");
    	$(".panel-inactive-trailers").attr("style", "display: none");

    	$(".panel-trucks").parent().addClass("hidden");
        $(".panel-inactive-trucks").parent().addClass("hidden");
        $(".panel-active-trailers").parent().addClass("hidden");
        $(".panel-trailers").parent().addClass("hidden");
        $(".panel-inactive-trailers").parent().addClass("hidden");

        if($(".panel-active-trucks").parent().hasClass("hidden")){
            $(".panel-active-trucks").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#inactive-trucks" , function(){
    	$(".panel-inactive-trucks").toggle();

    	$(".panel-trucks").attr("style", "display: none");
    	$(".panel-active-trucks").attr("style", "display: none");
    	$(".panel-active-trailers").attr("style", "display: none");
    	$(".panel-trailers").attr("style", "display: none");
    	$(".panel-inactive-trailers").attr("style", "display: none");

    	$(".panel-trucks").parent().addClass("hidden");
        $(".panel-active-trucks").parent().addClass("hidden");
        $(".panel-active-trailers").parent().addClass("hidden");
        $(".panel-trailers").parent().addClass("hidden");
        $(".panel-inactive-trailers").parent().addClass("hidden");

        if($(".panel-inactive-trucks").parent().hasClass("hidden")){
            $(".panel-inactive-trucks").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#total-trailers" , function(){
    	$(".panel-trailers").toggle();

    	$(".panel-trucks").attr("style", "display: none");
    	$(".panel-active-trucks").attr("style", "display: none");
    	$(".panel-inactive-trucks").attr("style", "display: none");
    	$(".panel-active-trailers").attr("style", "display: none");
    	$(".panel-inactive-trailers").attr("style", "display: none");

    	$(".panel-trucks").parent().addClass("hidden");
        $(".panel-active-trucks").parent().addClass("hidden");
        $(".panel-inactive-trucks").parent().addClass("hidden");
        $(".panel-active-trailers").parent().addClass("hidden");
        $(".panel-inactive-trailers").parent().addClass("hidden");

        if($(".panel-trailers").parent().hasClass("hidden")){
            $(".panel-trailers").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#active-trailers" , function(){
    	$(".panel-active-trailers").toggle();

    	$(".panel-trucks").attr("style", "display: none");
    	$(".panel-active-trucks").attr("style", "display: none");
    	$(".panel-inactive-trucks").attr("style", "display: none");
    	$(".panel-trailers").attr("style", "display: none");
    	$(".panel-inactive-trailers").attr("style", "display: none");

    	$(".panel-trucks").parent().addClass("hidden");
        $(".panel-active-trucks").parent().addClass("hidden");
        $(".panel-inactive-trucks").parent().addClass("hidden");
        $(".panel-trailers").parent().addClass("hidden");
        $(".panel-inactive-trailers").parent().addClass("hidden");

        if($(".panel-active-trailers").parent().hasClass("hidden")){
            $(".panel-active-trailers").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#inactive-trailers" , function(){
    	$(".panel-inactive-trailers").toggle();

    	$(".panel-trucks").attr("style", "display: none");
    	$(".panel-active-trucks").attr("style", "display: none");
    	$(".panel-inactive-trucks").attr("style", "display: none");
    	$(".panel-active-trailers").attr("style", "display: none");
    	$(".panel-trailers").attr("style", "display: none");

    	$(".panel-trucks").parent().addClass("hidden");
        $(".panel-active-trucks").parent().addClass("hidden");
        $(".panel-inactive-trucks").parent().addClass("hidden");
        $(".panel-active-trailers").parent().addClass("hidden");
        $(".panel-trailers").parent().addClass("hidden");

        if($(".panel-inactive-trailers").parent().hasClass("hidden")){
            $(".panel-inactive-trailers").parent().removeClass("hidden");
        }
    });
</script>
<div class="container-fluid">
    <div class="side-body padding-top">
        <div class="container">
        	<h1 class="text-center">Welcome, <?php echo $this->data['session_data']->display_name;?>!</h1>
        	<div class="card">
	    		<div class="card-body">
	    			<?php if((count($drivers) == 0) && (count($trucks == 0))) : ?>
	    			<div class="container-fluid text-center margin-bottom">
	    				<h2>Start Checking for damage with <?php echo $application_name; ?>.<br>
	    					<small>Get to know the basics in three easy ways.</small>
	    				</h2>
	    			</div>
	    			<div class="row">
	    				<div class="col-xs-12 col-lg-4">
	    					<div class="text-center">
	    						<img src="<?php echo site_url("public/img/tag.png"); ?>" width="150px">
	    						<h3>Add a Vehicle</h3>
	    						<p class="help-block">Explore the Sell screen, and learn how to make your first sale in seconds.</p>
	    						<a href="<?php echo site_url("app/vehicle/truck/add"); ?>" class="btn btn-success btn-lg <?php echo (count($trucks) == 0) ? '': 'disabled'; ?>">Add a Vehicle</a>
	    					</div>
	    				</div>
	    				<div class="col-xs-12 col-lg-4">
	    					<div class="text-center">
	    						<img src="<?php echo site_url("public/img/3d-printer.png"); ?>" width="150px">
	    						<h3>Register Driver</h3>
	    						<p class="help-block">Manage your products easily, whether you want to add one or import one thousand.</p>
	    						<a href="<?php echo site_url('app/accounts/add'); ?>" class="btn btn-success btn-lg <?php echo (count($drivers) == 0) ? '': 'disabled'; ?>">Add a Driver</a>
	    					</div>
	    				</div>
	    				<div class="col-xs-12 col-lg-4">
	    					<div class="text-center">
	    						<img src="<?php echo site_url("public/img/rating.png"); ?>" width="150px">
	    						<h3>Download the App</h3>
	    						<p class="help-block">Start a customer base to grow repeat business or simply upload existing customers.</p>
	    						<a href="<?php echo site_url("app/customer/add-customer"); ?>" class="btn btn-success btn-lg disabled">You've added a customer!</a>
	    					</div>
	    				</div>
	    			</div>
	    			<?php else: ?>
				    <div class="row">
			            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			                <a href="javascript:void(0);" id="total-trucks">
			                    <div class="card green summary-inline">
			                        <div class="card-body">
			                            <i class="icon fa fa-truck fa-4x"></i>
			                            <div class="content">
			                                <div class="title"><?php echo count($trucks); ?></div>
			                                <div class="sub-title">Total Trucks</div>
			                                
			                                <span class="pull-right sub"><small>Click to View List</small></span>
			                            </div>
			                            <div class="clear-both"></div>
			                        </div>
			                    </div>
			                </a>
			            </div>
			            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			                <a href="javascript:void(0);" id="active-trucks">
			                    <div class="card blue summary-inline">
			                        <div class="card-body">
			                            <i class="icon fa fa-truck fa-4x"></i>
			                            <div class="content">
			                                <div class="title"><?php echo count($active_trucks); ?></div>
			                                <div class="sub-title">Active Trucks</div>
			                                <span class="pull-right sub"><small>Click to View List</small></span>
			                            </div>
			                            <div class="clear-both"></div>
			                        </div>
			                    </div>
			                </a>
			            </div>
			            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			                <a href="javascript:void(0);" id="total-trailers">
			                    <div class="card green summary-inline">
			                        <div class="card-body">
			                            <i class="icon fa fa-truck fa-4x"></i>
			                            <div class="content">
			                                <div class="title"><?php echo count($trailers); ?></div>
			                                <div class="sub-title">Total Defects</div>
			                                <span class="pull-right sub"><small>Click to View List</small></span>
			                            </div>
			                            <div class="clear-both"></div>
			                        </div>
			                    </div>
			                </a>
			            </div>
			            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			                <a href="javascript:void(0);" id="total-trailers">
			                    <div class="card green summary-inline">
			                        <div class="card-body">
			                            <i class="icon fa fa-truck fa-4x"></i>
			                            <div class="content">
			                                <div class="title"><?php echo count($trailers); ?></div>
			                                <div class="sub-title">Total Trailers</div>
			                                <span class="pull-right sub"><small>Click to View List</small></span>
			                            </div>
			                            <div class="clear-both"></div>
			                        </div>
			                    </div>
			                </a>
			            </div>
			            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			                <a href="javascript:void(0);" id="active-trailers">
			                    <div class="card blue summary-inline">
			                        <div class="card-body">
			                            <i class="icon fa fa-truck fa-4x"></i>
			                            <div class="content">
			                                <div class="title"><?php echo count($active_trailers); ?></div>
			                                <div class="sub-title">Active Trailers</div>
			                                <span class="pull-right sub"><small>Click to View List</small></span>
			                            </div>
			                            <div class="clear-both"></div>
			                        </div>
			                    </div>
			                </a>
			            </div>
			            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			                <a href="javascript:void(0);" id="total-trailers">
			                    <div class="card green summary-inline">
			                        <div class="card-body">
			                            <i class="icon fa fa-truck fa-4x"></i>
			                            <div class="content">
			                                <div class="title"><?php echo count($trailers); ?></div>
			                                <div class="sub-title">Reports Today</div>
			                                <span class="pull-right sub"><small>Click to View List</small></span>
			                            </div>
			                            <div class="clear-both"></div>
			                        </div>
			                    </div>
			                </a>
			            </div>
			        </div>
			        <div class="row">
	    				<div class="col-lg-12 col-xs-12">
		    				<div class="panel panel-success panel-trailers" style="display: none;">
							  	<div class="panel-heading">Trailers</div>
							  	<div class="panel-body">
							  		<table class="table">
							   			<thead>
							   				<tr>
							   					<th>Trailer Number</th>
							   					<th>Description</th>
							   					<th>Status</th>
							   				</tr>
							   			</thead>
							   			<tbody>
							   				<?php foreach($trailers as $row) : ?>
							   					<tr>
							   						<td>
							   							<a href="javascript:void(0);"><?php echo $row->trailer_number; ?></a><br>
							   						</td>
							   						<td>
							   							<span><?php echo $row->description; ?></span>
							   						</td>
							   						<td>
							   							<span><?php echo $row->status; ?></span>
							   						</td>
							   					</tr>
							   				<?php endforeach; ?>
							   			</tbody>	
							   	  	</table>
							  	</div>
							</div>
		    			</div>
		    			<div class="col-lg-12 col-xs-12">
		    				<div class="panel panel-info panel-active-trailers" style="display: none;">
							   	<div class="panel-heading">Active Trailers</div>
							   	<div class="panel-body">
							   		<table class="table">
							   			<thead>
							   				<tr>
							   					<th>Trailer Number</th>
							   					<th>Description</th>
							   				</tr>
							   			</thead>
							   			<tbody>
											<?php foreach($active_trailers as $row) : ?>
							   					<tr>
								   					<td><span><?php echo $row->trailer_number; ?></span></td>
								   					<td><span><?php echo $row->description; ?></span></td>
								   				</tr>
							   				<?php endforeach; ?>
							   			</tbody>	
						   			</table>
							   	</div>
							</div>
		    			</div>
	                    <div class="col-lg-12 col-xs-12">
	                        <div class="panel panel-success panel-trucks" style="display: none;">
	                            <div class="panel-heading">Trucks</div>
	                            <div class="panel-body">
	                                <table class="table">
	                                    <thead>
	                                        <tr>
	                                            <th>Vehicle Registration Number</th>
	                                            <th>Description</th>
	                                            <th>Status</th>
	                                            <th></th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <?php foreach($trucks as $row) : ?>
	                                            <tr>
	                                                <td><span><?php echo $row->vehicle_registration_number; ?></span></td>
	                                                <td><span><?php echo $row->description; ?></span></td>
	                                                <td><span><?php echo $row->status; ?></span></td>
	                                            </tr>
	                                        <?php endforeach; ?>
	                                    </tbody>    
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-lg-12 col-xs-12">
	                        <div class="panel panel-info panel-active-trucks"  style="display: none;">
	                            <div class="panel-heading">Active Trucks</span></div>
	                            <div class="panel-body">
	                                <table class="table">
	                                    <thead>
	                                        <tr>
	                                            <th>Vehicle Registration Number</th>
	                                            <th>Description</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <?php foreach($active_trucks as $row) : ?>
	                                            <tr>
	                                                <td><span><?php echo $row->vehicle_registration_number; ?></span></td>
	                                                <td><span><?php echo $row->description; ?></span></td>
	                                            </tr>
	                                        <?php endforeach; ?>
	                                    </tbody>    
	                                </table>
	                            </div>
	                        </div>
	                    </div>
	    			</div>
	    			<div class="text-center">
	    				<p class="help-block">For help with setting up <?php echo $application_name; ?>, check out the <a href="#" class="link-style"> Getting Started Guide</a></p>
	    			</div>
	    			<?php endif; ?>
	    		</div>
	    	</div>
        </div>
    </div>
</div>