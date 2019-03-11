<script type="text/javascript">
	// trucks
	$(document).on("click" , "#total-vehicles" , function(){
    	$(".panel-total-vehicles").toggle();

    	$(".panel-available-vehicles").attr("style", "display: none");
    	$(".panel-unavailable-vehicles").attr("style", "display: none");
    	
        $(".panel-available-vehicles").parent().addClass("hidden");
        $(".panel-unavailable-vehicles").parent().addClass("hidden");

        if($(".panel-total-vehicles").parent().hasClass("hidden")){
            $(".panel-total-vehicles").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#available-vehicles" , function(){
    	$(".panel-available-vehicles").toggle();

    	$(".panel-total-vehicles").attr("style", "display: none");
    	$(".panel-unavailable-vehicles").attr("style", "display: none");
    	
        $(".panel-total-vehicles").parent().addClass("hidden");
        $(".panel-unavailable-vehicles").parent().addClass("hidden");

        if($(".panel-available-vehicles").parent().hasClass("hidden")){
            $(".panel-available-vehicles").parent().removeClass("hidden");
        }
    });

    $(document).on("click" , "#unavailable-vehicles" , function(){
    	$(".panel-unavailable-vehicles").toggle();

    	$(".panel-available-vehicles").attr("style", "display: none");
    	$(".panel-total-vehicles").attr("style", "display: none");
    	
        $(".panel-total-vehicles").parent().addClass("hidden");
        $(".panel-available-vehicles").parent().addClass("hidden");

        if($(".panel-unavailable-vehicles").parent().hasClass("hidden")){
            $(".panel-unavailable-vehicles").parent().removeClass("hidden");
        }
    });

    // reports
    $(document).on("click" , "#today-reports" , function(){
    	$(".panel-today-reports").toggle();

    	$(".panel-def-und").attr("style", "display: none");
    	$(".panel-fixed-today").attr("style", "display: none");
    	
        $(".panel-def-und").parent().addClass("hidden");
        $(".panel-fixed-today").parent().addClass("hidden");

        if($(".panel-today-reports").parent().hasClass("hidden")){
            $(".panel-today-reports").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#defects-under-maintenance" , function(){
    	$(".panel-def-und").toggle();

    	$(".panel-today-reports").attr("style", "display: none");
    	$(".panel-fixed-today").attr("style", "display: none");
    	
        $(".panel-today-reports").parent().addClass("hidden");
        $(".panel-fixed-today").parent().addClass("hidden");

        if($(".panel-def-und").parent().hasClass("hidden")){
            $(".panel-def-und").parent().removeClass("hidden");
        }
    });
     $(document).on("click" , "#fixed-reports" , function(){
    	$(".panel-fixed-today").toggle();

    	$(".panel-today-reports").attr("style", "display: none");
    	$(".panel-def-und").attr("style", "display: none");
    	
        $(".panel-today-reports").parent().addClass("hidden");
        $(".panel-def-und").parent().addClass("hidden");

        if($(".panel-fixed-today").parent().hasClass("hidden")){
            $(".panel-fixed-today").parent().removeClass("hidden");
        }
    });
    $(document).ready(function(){
    	$('.count-to').each(function () {
		    $(this).prop('Counter',0).animate({
		        Counter: $(this).text()
		    }, {
		        duration: 1000,
		        easing: 'swing',
		        step: function (now) {
		            $(this).text(Math.ceil(now));
		        }
		    });
		});
    });
    $(document).on("click","a.dashboard-a", function(){
    	$('.count-to').each(function () {
		    $(this).prop('Counter',0).animate({
		        Counter: $(this).text()
		    }, {
		        duration: 1000,
		        easing: 'swing',
		        step: function (now) {
		            $(this).text(Math.ceil(now));
		        }
		    });
		});
    });
	
</script>
<style type="text/css">
	.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover{
		color: #FFF;
	    cursor: default;
	    background-color: #2196f3ad;
	    border: 1px solid #2196f3ad;
	}
	a.dashboard-a{
		color: #FFF;
	}
</style>
<div class="container-fluid">
    <div class="side-body padding-top">
        <div class="container">
        	<h1 class="text-left">Welcome, <?php echo $this->data['session_data']->display_name;?>!</h1>
        	<?php if($this->session->userdata("user")->expired  == 0): ?>
        		<div class="card">
	    		<div class="card-body">
	    			
				    <div class="row">
			            <ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#truck" class="dashboard-a">Vehicles</a></li>
							<li><a data-toggle="tab" href="#report" class="dashboard-a">Reports</a></li>
						</ul>
						<div class="tab-content">
							<div id="truck" class="tab-pane fade in active">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="total-vehicles">
						                    <div class="card blue summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title count-to"><?php echo count($trucks); ?></div>
						                                <div class="sub-title">TOTAL VEHICLES</div>
						                                
						                                <span class="pull-right sub"><small>Click to View List</small></span>
						                            </div>
						                            <div class="clear-both"></div>
						                        </div>
						                    </div>
						                </a>
						            </div>
						            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="available-vehicles">
						                    <div class="card green summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title count-to"><?php echo count($available_trucks); ?></div>
						                                <div class="sub-title">AVAILABLE VEHICLES</div>
						                                <span class="pull-right sub"><small>Click to View List</small></span>
						                            </div>
						                            <div class="clear-both"></div>
						                        </div>
						                    </div>
						                </a>
						            </div>
						            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="unavailable-vehicles">
						                    <div class="card red summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title count-to"><?php echo count($unavailable_trucks); ?></div>
						                                <div class="sub-title">UNAVAILABLE VEHICLES</div>
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
				                        <div class="panel panel-info panel-total-vehicles" style="display: none;">
				                            <div class="panel-heading">TOTAL VEHICLES</div>
				                            <div class="panel-body">
				                                <table class="table">
				                                    <thead>
				                                        <tr>
				                                            <th>Vehicle Registration Number</th>
				                                            <th>Vehicle Type</th>
				                                            <th>Availablity</th>
				                                            <th>Last Checked</th>
				                                        </tr>
				                                    </thead>
				                                    <tbody>
				                                        <?php foreach($trucks as $row) : ?>
				                                            <tr>
				                                                <td><span><?php echo $row->vehicle_registration_number; ?></span></td>
				                                                <td><span><?php echo $row->type; ?></span></td>
				                                                <td><span><?php echo $row->availability; ?></span></td>
				                                                <td><span><?php echo $row->last_checked; ?></span></td>
				                                            </tr>
				                                        <?php endforeach; ?>
				                                    </tbody>    
				                                </table>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-lg-12 col-xs-12">
				                        <div class="panel panel-success panel-available-vehicles" style="display: none;">
				                            <div class="panel-heading">AVAILABLE VEHICLES</div>
				                            <div class="panel-body">
				                                <table class="table">
				                                    <thead>
				                                        <tr>
				                                            <th>Vehicle Registration Number</th>
				                                            <th>Vehicle Type</th>
				                                            <th>Last Checked</th>
				                                        </tr>
				                                    </thead>
				                                    <tbody>
				                                        <?php foreach($available_trucks as $row) : ?>
				                                            <tr>
				                                                <td><span><?php echo $row->vehicle_registration_number; ?></span></td>
				                                                <td><span><?php echo $row->type; ?></span></td>
				                                                <td><span><?php echo $row->last_checked; ?></span></td>
				                                            </tr>
				                                        <?php endforeach; ?>
				                                    </tbody>    
				                                </table>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-lg-12 col-xs-12">
				                        <div class="panel panel-danger panel-unavailable-vehicles" style="display: none;">
				                            <div class="panel-heading">UNAVAILABLE VEHICLES</div>
				                            <div class="panel-body">
				                                <table class="table">
				                                    <thead>
				                                        <tr>
				                                            <th>Vehicle Registration Number</th>
				                                            <th>Vehicle Type</th>
				                                            <th>Last Checked</th>
				                                        </tr>
				                                    </thead>
				                                    <tbody>
				                                        <?php foreach($unavailable_trucks as $row) : ?>
				                                            <tr>
				                                                <td><span><?php echo $row->vehicle_registration_number; ?></span></td>
				                                                <td><span><?php echo $row->type; ?></span></td>
				                                                <td><span><?php echo $row->last_checked; ?></span></td>
				                                            </tr>
				                                        <?php endforeach; ?>
				                                    </tbody>    
				                                </table>
				                            </div>
				                        </div>
				                    </div>
								</div>		
						    </div>
						   
						    <div id="report" class="tab-pane fade">
						    	<div class="row">
						    		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="today-reports">
						                    <div class="card blue summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-book fa-4x"></i>
						                            <div class="content">
						                                <div class="title count-to"><?php echo count($reports_today); ?></div>
						                                <div class="sub-title">TOTAL REPORTS TODAY</div>
						                                <span class="pull-right sub"><small>Click to View List</small></span>
						                            </div>
						                            <div class="clear-both"></div>
						                        </div>
						                    </div>
						                </a>
						            </div>
							    	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="defects-under-maintenance">
						                    <div class="card red summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-warning fa-4x"></i>
						                            <div class="content">
						                                <div class="title count-to"><?php echo count($defects_under_maintenance); ?></div>
						                                <div class="sub-title">DEFECT REPORTS</div>
						                                <span class="pull-right sub"><small>Click to View List</small></span>
						                            </div>
						                            <div class="clear-both"></div>
						                        </div>
						                    </div>
						                </a>
						            </div>
						            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="fixed-reports">
						                    <div class="card green summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-wrench fa-4x"></i>
						                            <div class="content">
						                                <div class="title count-to"><?php echo count($fixed_today); ?></div>
						                                <div class="sub-title">FIXED TODAY</div>
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
					    				<div class="panel panel-info panel-today-reports" style="display: none;">
											<div class="panel-heading">Reports Today</div>
										   	<div class="panel-body">
										   		<table class="table">
										   			<thead>
										   				<tr>
										   					<th>Report No.</th>
										   					<th>Type</th>
										   					<th>Notes</th>
										   					<th>Status</th>
										   				</tr>
										   			</thead>
										   			<tbody>
														<?php foreach($reports_today as $row) : ?>
										   					<tr>
											   					<td valign="top">
											   						<span>
												   						<strong><a href="<?php echo site_url('app/report/view/').$this->hash->encrypt($row->report_id);?>" class="link-style"><?php echo $row->report_number; ?></a></strong> 
	                                  
										                                <small class="help-block"><strong>Report by</strong>: <?php echo $row->display_name; ?></small>
										                                <?php if($row->vehicle_registration_number) : ?>
										                                   <small class="help-block"><strong>Vehicle</strong>: <?php echo $row->vehicle_registration_number; ?></small>
										                                <?php endif; ?>
										                                <?php if($row->trailer_number) : ?>
										                                  <small class="help-block"><strong>Trailer</strong>: <?php echo $row->trailer_number; ?></small>
										                                <?php endif; ?>
									                              	</span>
								                          		</td>
											   					<td valign="top"><span><?php echo $row->checklist_name; ?></span></td>
											   					<td valign="top">
											   						<?php if($row->report_notes != ""): ?>
											   							<span class="label label-success" data-toggle="tooltip" title="<?php echo $row->report_notes;?>">Yes</span>
                              										<?php else: ?>
                                										<span class="label label-danger">No</span>
                              										<?php endif; ?>
											   					</td>
											   					<td valign="top">
									                              	<span data-toggle="tooltip" title="<?php echo $row->updated_by;?>"><?php echo $row->status; ?></span>
									                              	<?php if($row->status_created != "<small class='help-block'>0</small>") : ?>
										                              <small class="help-block"><?php echo $row->status_created; ?></small>
										                            <?php endif; ?>
									                            </td>
											   				</tr>
										   				<?php endforeach; ?>
										   			</tbody>	
									   			</table>
										   	</div>
										</div>
									</div>
									<div class="col-lg-12 col-xs-12">
					    				<div class="panel panel-danger panel-def-und" style="display: none;">
											<div class="panel-heading">Defect Reports</div>
										   	<div class="panel-body">
										   		<table class="table">
										   			<thead>
										   				<tr>
										   					<th width="25%">Report No.</th>
										   					<th width="25%">Type</th>
										   					<th width="25%">Notes</th>
										   					<th width="25%">Created</th>
										   				</tr>
										   			</thead>
										   			<tbody>
														<?php foreach($defects_under_maintenance as $row) : ?>
										   					<tr>
											   					<td valign="top">
											   						<span>
												   						<strong><a href="<?php echo site_url('app/report/view/').$this->hash->encrypt($row->report_id);?>" class="link-style"><?php echo $row->report_number; ?></a></strong> 
	                                  
										                                <small class="help-block"><strong>Report by</strong>: <?php echo $row->display_name; ?></small>
										                                <?php if($row->vehicle_registration_number) : ?>
										                                   <small class="help-block"><strong>Vehicle</strong>: <?php echo $row->vehicle_registration_number; ?></small>
										                                <?php endif; ?>
										                                <?php if($row->trailer_number) : ?>
										                                  <small class="help-block"><strong>Trailer</strong>: <?php echo $row->trailer_number; ?></small>
										                                <?php endif; ?>
									                              	</span>
								                          		</td>
											   					<td valign="top"><span><?php echo $row->checklist_name; ?></span></td>
											   					<td valign="top"><span><?php echo $row->report_notes;?></span></td>
											   					<td valign="top">
									                              	<span><?php echo $row->created; ?></span>
									                            </td>
											   				</tr>
										   				<?php endforeach; ?>
										   			</tbody>	
									   			</table>
										   	</div>
										</div>
									</div>
									<div class="col-lg-12 col-xs-12">
					    				<div class="panel panel-success panel-fixed-today" style="display: none;">
											<div class="panel-heading">Fixed Reports Today</div>
										   	<div class="panel-body">
										   		<table class="table">
										   			<thead>
										   				<tr>
										   					<th>Report No.</th>
										   					<th>Type</th>
										   					<th>Notes</th>
										   					<th>Status</th>
										   				</tr>
										   			</thead>
										   			<tbody>
														<?php foreach($fixed_today as $row) : ?>
										   					<tr>
											   					<td valign="top">
											   						<span>
												   						<strong><a href="<?php echo site_url('app/report/view/').$this->hash->encrypt($row->report_id);?>" class="link-style"><?php echo $row->report_number; ?></a></strong> 
	                                  
										                                <small class="help-block"><strong>Report by</strong>: <?php echo $row->display_name; ?></small>
										                                <?php if($row->vehicle_registration_number) : ?>
										                                   <small class="help-block"><strong>Vehicle</strong>: <?php echo $row->vehicle_registration_number; ?></small>
										                                <?php endif; ?>
										                                <?php if($row->trailer_number) : ?>
										                                  <small class="help-block"><strong>Trailer</strong>: <?php echo $row->trailer_number; ?></small>
										                                <?php endif; ?>
									                              	</span>
								                          		</td>
											   					<td valign="top"><span><?php echo $row->checklist_name; ?></span></td>
											   					<td valign="top"><span><?php echo $row->report_notes;?></span></td>
											   				</tr>
										   				<?php endforeach; ?>
										   			</tbody>	
									   			</table>
										   	</div>
										</div>
									</div>
						    	</div>
						    </div>
						</div>
	    		</div>
	    		</div>
	    	<?php else : ?>
	    		<div class="card">
	    			<a href="<?php echo ($this->session->userdata("user")->role == 'ADMIN PREMIUM') ? site_url('app/setup/account/pricing') : '#'; ?>" >
	                    <div class="card summary-inline">
	                        <div class="card-body">
	                            <i class="icon fa fa-warning fa-3x text-danger" style="float: left"></i>
	                            <div class="content" style="float:left;margin-left: 10px;">
	                                <div class="title" style="font-size: 2em;">Plan Subscription Has Already Expired. </div>
	                                <div class="sub-title text-danger"style="text-align:left;padding-top:3px;">*Your data will be available after subscription has been renewed.</div>
	                                
	                            </div>
	                            <div class="clear-both"></div>
	                        </div>
	                    </div>
	                </a>
	    		</div>
	    	<?php endif; ?>
        </div>
    </div>
</div>