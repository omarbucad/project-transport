<script type="text/javascript">
	// trucks
	$(document).on("click" , "#total-trucks" , function(){
    	$(".panel-trucks").toggle();

    	$(".panel-active-trucks").attr("style", "display: none");
    	
        $(".panel-active-trucks").parent().addClass("hidden");

        if($(".panel-trucks").parent().hasClass("hidden")){
            $(".panel-trucks").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#active-trucks" , function(){
    	$(".panel-active-trucks").toggle();

    	$(".panel-trucks").attr("style", "display: none");
    	
        $(".panel-trucks").parent().addClass("hidden");

        if($(".panel-active-trucks").parent().hasClass("hidden")){
            $(".panel-active-trucks").parent().removeClass("hidden");
        }
    });

    // trailers
    $(document).on("click" , "#total-trailers" , function(){
    	$(".panel-trailers").toggle();

    	$(".panel-active-trailers").attr("style", "display: none");
    	
        $(".panel-active-trailers").parent().addClass("hidden");

        if($(".panel-trailers").parent().hasClass("hidden")){
            $(".panel-trailers").parent().removeClass("hidden");
        }
    });
    $(document).on("click" , "#active-trailers" , function(){
    	$(".panel-active-trailers").toggle();

    	$(".panel-trailers").attr("style", "display: none");
    	
        $(".panel-trailers").parent().addClass("hidden");

        if($(".panel-active-trailers").parent().hasClass("hidden")){
            $(".panel-active-trailers").parent().removeClass("hidden");
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
	
</script>
<div class="container-fluid">
    <div class="side-body padding-top">
        <div class="container">
        	<h1 class="text-left">Welcome, <?php echo $this->data['session_data']->display_name;?>!</h1>
        	<?php if($this->session->userdata("user")->expired  == false): ?>
        		<!-- <div class="card">
	    		<div class="card-body">
	    			
				    <div class="row">
			            <ul class="nav nav-tabs">
			            	<?php //if($session_data->role != "MECHANIC") : ?>
							<li class="active"><a data-toggle="tab" href="#truck">Truck</a></li>
							<li><a data-toggle="tab" href="#trailer">Trailer</a></li>
							<?php //endif;?>
							<li class="<?php// echo ($session_data->role != 'MECHANIC') ? '': 'active'; ?>"><a data-toggle="tab" href="#report">Report</a></li>
						</ul>
						<div class="tab-content">
							<?php //if($session_data->role != "MECHANIC") : ?>
							<div id="truck" class="tab-pane fade in active">
								<div class="row">
									<div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="total-trucks">
						                    <div class="card green summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title"><?php //echo count($trucks); ?></div>
						                                <div class="sub-title">Total Trucks</div>
						                                
						                                <span class="pull-right sub"><small>Click to View List</small></span>
						                            </div>
						                            <div class="clear-both"></div>
						                        </div>
						                    </div>
						                </a>
						            </div>
						            <div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="active-trucks">
						                    <div class="card blue summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title"><?php// echo count($active_trucks); ?></div>
						                                <div class="sub-title">Active Trucks</div>
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
				                                        <?php //foreach($trucks as $row) : ?>
				                                            <tr>
				                                                <td><span><?php //echo $row->vehicle_registration_number; ?></span></td>
				                                                <td><span><?php// echo $row->description; ?></span></td>
				                                                <td><span><?php// echo $row->status; ?></span></td>
				                                            </tr>
				                                        <?php// endforeach; ?>
				                                    </tbody>    
				                                </table>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="col-lg-12 col-xs-12">
				                        <div class="panel panel-info panel-active-trucks" style="display: none;">
				                            <div class="panel-heading">Active Trucks</div>
				                            <div class="panel-body">
				                                <table class="table">
				                                    <thead>
				                                        <tr>
				                                            <th>Vehicle Registration Number</th>
				                                            <th>Description</th>
				                                        </tr>
				                                    </thead>
				                                    <tbody>
				                                        <?php //foreach($active_trucks as $row) : ?>
				                                            <tr>
				                                                <td><span><?php// echo $row->vehicle_registration_number; ?></span></td>
				                                                <td><span><?php //echo $row->description; ?></span></td>
				                                            </tr>
				                                        <?php// endforeach; ?>
				                                    </tbody>    
				                                </table>
				                            </div>
				                        </div>
				                    </div>
								</div>		
						    </div>
						    <div id="trailer" class="tab-pane fade">
						    	<div class="row">
						    		<div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="total-trailers">
						                    <div class="card green summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title"><?php// echo count($trailers); ?></div>
						                                <div class="sub-title">Total Trailers</div>
						                                <span class="pull-right sub"><small>Click to View List</small></span>
						                            </div>
						                            <div class="clear-both"></div>
						                        </div>
						                    </div>
						                </a>
						            </div>
							    	<div class="col-lg-6 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="active-trailers">
						                    <div class="card blue summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title"><?php //echo count($active_trailers); ?></div>
						                                <div class="sub-title">Active Trailers</div>
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
										   				<?php //foreach($trailers as $row) : ?>
										   					<tr>
										   						<td>
										   							<a href="javascript:void(0);"><?php //echo $row->trailer_number; ?></a><br>
										   						</td>
										   						<td>
										   							<span><?php //echo $row->description; ?></span>
										   						</td>
										   						<td>
										   							<span><?php //echo $row->status; ?></span>
										   						</td>
										   					</tr>
										   				<?php //endforeach; ?>
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
														<?php// foreach($active_trailers as $row) : ?>
										   					<tr>
											   					<td><span><?php //echo $row->trailer_number; ?></span></td>
											   					<td><span><?php //echo $row->description; ?></span></td>
											   				</tr>
										   				<?php// endforeach; ?>
										   			</tbody>	
									   			</table>
										   	</div>
										</div>
					    			</div>	
						    	</div>		      
						    </div>
						    <div id="report" class="tab-pane fade <?php //echo ($session_data->role != 'MECHANIC') ? '': 'in active'; ?>">
						    	<div class="row">
						    		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						                <a href="javascript:void(0);" id="today-reports">
						                    <div class="card blue summary-inline">
						                        <div class="card-body">
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title"><?php //echo count($reports_today); ?></div>
						                                <div class="sub-title">Reports Today</div>
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
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title"><?php //echo count($defects_under_maintenance); ?></div>
						                                <div class="sub-title">Defects and Under Maintenance</div>
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
						                            <i class="icon fa fa-truck fa-4x"></i>
						                            <div class="content">
						                                <div class="title"><?php// echo count($fixed_today); ?></div>
						                                <div class="sub-title">Fixed Today</div>
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
														<?php //foreach($reports_today as $row) : ?>
										   					<tr>
											   					<td valign="top">
											   						<span>
												   						<strong><a href="<?php //echo site_url('app/report/view/').$this->hash->encrypt($row->report_id);?>" class="link-style"><?php //echo $row->report_number; ?></a></strong> 
	                                  
										                                <small class="help-block"><strong>Report by</strong>: <?php //echo $row->display_name; ?></small>
										                                <?php //if($row->vehicle_registration_number) : ?>
										                                   <small class="help-block"><strong>Vehicle</strong>: <?php// echo $row->vehicle_registration_number; ?></small>
										                                <?php //endif; ?>
										                                <?php //if($row->trailer_number) : ?>
										                                  <small class="help-block"><strong>Trailer</strong>: <?php// echo $row->trailer_number; ?></small>
										                                <?php //endif; ?>
									                              	</span>
								                          		</td>
											   					<td valign="top"><span><?php //echo $row->checklist_name; ?></span></td>
											   					<td valign="top">
											   						<?php //if($row->report_notes != ""): ?>
											   							<span class="label label-success" data-toggle="tooltip" title="<?php //echo $row->report_notes;?>">Yes</span>
                              										<?php// else: ?>
                                										<span class="label label-danger">No</span>
                              										<?php //endif; ?>
											   					</td>
											   					<td valign="top">
									                              	<span data-toggle="tooltip" title="<?php //echo $row->updated_by;?>"><?php //echo $row->status; ?></span>
									                              	<?php //if($row->status_created != "<small class='help-block'>0</small>") : ?>
										                              <small class="help-block"><?php //echo $row->status_created; ?></small>
										                            <?php //endif; ?>
									                            </td>
											   				</tr>
										   				<?php //endforeach; ?>
										   			</tbody>	
									   			</table>
										   	</div>
										</div>
									</div>
									<div class="col-lg-12 col-xs-12">
					    				<div class="panel panel-danger panel-def-und" style="display: none;">
											<div class="panel-heading">Defects and On Maintenance Reports</div>
										   	<div class="panel-body">
										   		<table class="table">
										   			<thead>
										   				<tr>
										   					<th width="25%">Report No.</th>
										   					<th width="25%">Type</th>
										   					<th width="25%">Notes</th>
										   					<th width="25%">Status</th>
										   				</tr>
										   			</thead>
										   			<tbody>
														<?php //foreach($defects_under_maintenance as $row) : ?>
										   					<tr>
											   					<td valign="top">
											   						<span>
												   						<strong><a href="<?php //echo site_url('app/report/view/').$this->hash->encrypt($row->report_id);?>" class="link-style"><?php //echo $row->report_number; ?></a></strong> 
	                                  
										                                <small class="help-block"><strong>Report by</strong>: <?php //echo $row->display_name; ?></small>
										                                <?php //if($row->vehicle_registration_number) : ?>
										                                   <small class="help-block"><strong>Vehicle</strong>: <?php// echo $row->vehicle_registration_number; ?></small>
										                                <?php //endif; ?>
										                                <?php// if($row->trailer_number) : ?>
										                                  <small class="help-block"><strong>Trailer</strong>: <?php //echo $row->trailer_number; ?></small>
										                                <?php// endif; ?>
									                              	</span>
								                          		</td>
											   					<td valign="top"><span><?php //echo $row->checklist_name; ?></span></td>
											   					<td valign="top"><span><?php //echo $row->report_notes;?></span></td>
											   					<td valign="top">
									                              	<span data-toggle="tooltip" title="<?php //echo $row->updated_by;?>"><?php // echo $row->status; ?></span>
									                              	<?php //if($row->status_created != "<small class='help-block'>0</small>") : ?>
										                              <small class="help-block"><?php// echo $row->status_created; ?></small>
										                            <?php //endif; ?>
									                            </td>
											   				</tr>
										   				<?php// endforeach; ?>
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
														<?php //foreach($fixed_today as $row) : ?>
										   					<tr>
											   					<td valign="top">
											   						<span>
												   						<strong><a href="<?php //echo site_url('app/report/view/').$this->hash->encrypt($row->report_id);?>" class="link-style"><?php //echo $row->report_number; ?></a></strong> 
	                                  
										                                <small class="help-block"><strong>Report by</strong>: <?php// echo $row->display_name; ?></small>
										                                <?php// if($row->vehicle_registration_number) : ?>
										                                   <small class="help-block"><strong>Vehicle</strong>: <?php// echo $row->vehicle_registration_number; ?></small>
										                                <?php //endif; ?>
										                                <?php// if($row->trailer_number) : ?>
										                                  <small class="help-block"><strong>Trailer</strong>: <?php //echo $row->trailer_number; ?></small>
										                                <?php //endif; ?>
									                              	</span>
								                          		</td>
											   					<td valign="top"><span><?php //echo $row->checklist_name; ?></span></td>
											   					<td valign="top"><span><?php //echo $row->report_notes;?></span></td>
											   				</tr>
										   				<?php //endforeach; ?>
										   			</tbody>	
									   			</table>
										   	</div>
										</div>
									</div>
						    	</div>
						    </div>
						</div>
	    			<?php// endif; ?>
	    		</div>
	    		</div> -->
	    	<?php else : ?>
	    		<div class="card">
	    			<a href="<?php echo ($this->session->userdata("user")->role == 'ADMIN') ? site_url('app/setup/account/pricing') : '#'; ?>" >
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
        <div class="text-center">
			<p class="help-block">For help with setting up <?php echo $application_name; ?>, check out the <a href="#" class="link-style"> Getting Started Guide</a></p>
		</div>
    </div>
</div>