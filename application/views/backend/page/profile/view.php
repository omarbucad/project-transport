<script type="text/javascript">
	$(document).on('change' , '#profile_image' , function(){
        readURL(this , ".image-preview" , 'background');
    });
</script>
<div class="container margin-bottom">
    <div class="side-body padding-top">
    	<ol class="breadcrumb">
    		<li><a href="<?php echo site_url('app/setup/'); ?>">Setup</a></li>
    		<li class="active">Profile</li>
    	</ol>	
    	<h3>Profile Setup</h3>

    	<?php foreach($result as $key => $value) : ?>

    	<form class="form-horizontal" action="<?php echo site_url("app/setup/profile/"); ?>" method="POST" id="profile-form" enctype="multipart/form-data">

    		<input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
    		<input type="hidden" name="address_id" value="<?php echo $value->address_id; ?>">
    		<input type="hidden" name="contact_id" value="<?php echo $value->contact_id; ?>">
    		<input type="hidden" name="store_id" value="<?php echo $value->store_id; ?>">
    		<!-- STORE SETTINGS -->
    		<div class="card margin-bottom">
	    		<div class="card-header">
	    			<div class="card-title">
	    				<div class="title">Profile Information</div>
	    			</div>
	    		</div>
	    		<div class="card-body">
	    			<div class="row no-margin-bottom">
	    				<div class="col-xs-12 col-lg-6">
	    					<dl class="dl-horizontal text-left">
	    						<dt>Display Name</dt>
	    						<dd>
    								<input type="text" name="display_name" class="form-control" value="<?php echo $value->display_name; ?>" required="true">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Username</dt>
	    						<dd>
    								<span><?php echo $value->username; ?></span>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Email Address</dt>
	    						<dd>
    								<span><?php echo $value->email_address; ?></span>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Role</dt>
	    						<dd>
	    							<span><?php echo $value->role; ?></span>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Status</dt>
	    						<dd>
    								<?php echo $value->status; ?>
	    						</dd>
	    					</dl>
	    				</div>
	    				<div class="col-xs-12 col-lg-6">
	    					<dl class="dl-horizontal">
	    						<dt>Profile Image</dt>
	    						<dd>
    								<div class="preview-image">
	                                    <img src="<?php echo site_url("thumbs/images/user/$value->image_path/150/150/$value->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom" alt="Profile image">
	                                </div>
	                                <input type="file" name="file" id="profile_image" class="btn btn-default">
	    						</dd>
	    					</dl>
	    				</div>
	    			</div>
	    		</div>
	    	</div>

	    	<!-- CONTACT INFORMATION -->
	    	<div class="card margin-bottom">
	    		<div class="card-header">
	    			<div class="card-title">
	    				<div class="title">Company Information</div>
	    			</div>
	    		</div>
	    		<div class="card-body">	  
	    			<div class="row no-margin-bottom">
	    				<div class="col-xs-12">
	    					<dl class="dl-horizontal text-left">
	    						<dt>Company Name</dt>
	    						<dd class="form-horizontale">
    								<input type="text" name="store_name" class="form-control" value="<?php echo $value->store_name; ?>" readonly="true">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Contact Name</dt>
	    						<dd class="form-horizontale">
    								<div class="col-xs-6 no-padding-left">
    									<input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?php echo $value->first_name; ?>" required="true">
    								</div>
    								<div class="col-xs-6 no-padding-right">
    									<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?php echo $value->last_name; ?>" required="true">
    								</div>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Email</dt>
	    						<dd>
    								<input type="email" name="email" class="form-control" value="<?php echo $value->email_address; ?>" readonly="true">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Phone</dt>
	    						<dd>
    								<input type="phone" name="phone" class="form-control" value="<?php echo $value->phone; ?>" required="true">
	    						</dd>	    
	    					</dl>
	    				</div>
	    			</div>
	    		</div>
	    	</div>

	    	<!-- ADDRESS -->
	    	<div class="card margin-bottom">
	    		<div class="card-header">
	    			<div class="card-title">
	    				<div class="title">Company Address</div>
	    			</div>
	    		</div>
	    		<div class="card-body">
	  
	    			<div class="row no-margin-bottom">
	    				<div class="col-xs-12">
	    					<dl class="dl-horizontal text-left">
	    						<dt>Street 1</dt>
	    						<dd>
	    							<input type="text" name="physical[street1]" class="form-control" value="<?php echo $value->street1; ?>" required="true">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Street 2</dt>
	    						<dd>
	    							<input type="text" name="physical[street2]" class="form-control" value="<?php echo $value->street2; ?>">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Suburb</dt>
	    						<dd>
	    							<input type="text" name="physical[suburb]" class="form-control" value="<?php echo $value->suburb; ?>">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>City</dt>
	    						<dd>
	    							<input type="text" name="physical[city]" class="form-control" value="<?php echo $value->city; ?>" required="true">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Postcode</dt>
	    						<dd>
	    							<input type="text" name="physical[postcode]" class="form-control" value="<?php echo $value->postcode; ?>">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>State</dt>
	    						<dd>
	    							<input type="text" name="physical[state]" class="form-control" value="<?php echo $value->state; ?>" required="true">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Country</dt>
	    						<dd>
	    							<input type="text" name="physical[country]" class="form-control" value="<?php echo $value->country?>">
	    						</dd>
	    					</dl>
	    				</div>
	    			</div>
	    		</div>
	    	</div>

	    	<!-- ADDRESS -->
	    	<div class="card margin-bottom">
	    		<div class="card-header">
	    			<div class="card-title">
	    				<div class="title">Change Password</div>
	    			</div>
	    		</div>
	    		<div class="card-body">
	  
	    			<div class="row no-margin-bottom">
	    				<div class="col-xs-12">
	    					<dl class="dl-horizontal text-left">
	    						<dt>Password</dt>
	    						<dd>
	    							<input type="password" name="password" class="form-control" id="enter_password">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Confirm Password</dt>
	    						<dd>
	    							<input type="password" name="confirm_password" class="form-control" id="confirm_password">
	    						</dd>
	    					</dl>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    	<?php endforeach; ?>

	    	<div class="text-right margin-bottom">
	    		<a href="javascript:void(0);" class="btn btn-default">Cancel</a>
	    		<input type="submit" name="submit" value="Save" class="btn btn-success">
	    	</div>
    	</form>
    </div>
</div>