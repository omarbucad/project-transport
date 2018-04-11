<div class="container margin-bottom">
    <div class="side-body padding-top">
    	<ol class="breadcrumb">
    		<li><a href="<?php echo site_url('app/vehicle/trailer'); ?>">Trailer List</a></li>
    		<li class="active">Add Trailer</li>
    	</ol>	
    	<h3>New Trailer</h3>
    	<form class="form-horizontal" action="<?php echo site_url("app/vehicle/trailer/add"); ?>" method="POST" enctype="multipart/form-data">
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
	    				<dt>Trailer Number</dt>
	    				<dd>
	    					<div class="form-group">
	    						<input type="text" name="trailer_number" value="<?php echo set_value("trailer_number"); ?>" class="form-control">
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
	    		<a href="<?php echo site_url('app/vehicle/trailer');?>"  class="btn btn-default">Cancel</a>
	    		<input type="submit" name="submit" value="Save" class="btn btn-success">
	    	</div>
    	</form>
    </div>
</div>