<div class="container margin-bottom">
    <div class="side-body padding-top">
    	<ol class="breadcrumb">
    		<li><a href="<?php echo site_url('app/vehicle/'); ?>">Vehicle List</a></li>
    		<li class="active">Add Vehicle</li>
    	</ol>	
    	<h3>Vehicle Information</h3>
    	<form class="form-horizontal" action="<?php echo site_url("app/vehicle/add"); ?>" method="POST" enctype="multipart/form-data">
    		<input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
    		<!-- STORE SETTINGS -->
    		<div class="card margin-bottom">
	    		<div class="card-header">
	    			<div class="card-title">
	    				<div class="title">Details</div>
	    			</div>
	    		</div>
	    		<div class="card-body">
	    			<dl class="dl-horizontal text-left">
	    				<dt>Registration Number</dt>
	    				<dd>
	    					<div class="form-group">
	    						<input type="text" name="registration_number" value="<?php echo set_value("registration_number"); ?>" class="form-control" placeholder="Registration Number">
	    					</div>
	    				</dd>

	    				<dt>Type</dt>
	    				<dd>
	    					<div class="form-group">
	    						<select class="form-control" name="type" id="type">
                                    <option value="">- Select Vehicle Type -</option>
                                    <?php foreach($types as $key => $val) :?>
                                        <option value="<?php echo $val->vehicle_type_id;?>" <?php echo ($this->input->get("type") == "<?php echo $val->vehicle_type_id;?>") ? "selected" : "" ; ?> ><?php echo $val->type;?></option>
                                    <?php endforeach; ?>                                    
                                </select>
	    					</div>
	    				</dd>
	    				
	    				<dt>Description</dt>
	    				<dd>
	    					<div class="form-group">
	    						<textarea class="textarea" name="description"> <?php echo set_value("description"); ?> </textarea>
	    					</div>
	    				</dd>

	    			</dl>
	
	    		</div>
	    	</div>



	    	<div class="text-right margin-bottom">
	    		<a href="<?php echo site_url('app/vehicle');?>"  class="btn btn-default">Cancel</a>
	    		<input type="submit" name="submit" value="Save" class="btn btn-success">
	    	</div>
    	</form>
    </div>
</div>