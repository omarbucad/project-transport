<script type="text/javascript">
	$(document).on('change' , '#profile_image' , function(){
        readURL(this , ".image-preview" , 'background');
    });

    $(document).ready(function(){
    	$('#country').val('<?php echo $result->country;?>');
    });
    
</script>
<div class="container margin-bottom">
    <div class="side-body padding-top">
    	<ol class="breadcrumb">
    		<li><a href="<?php echo site_url('app/dashboard'); ?>">Dashboard</a></li>
    		<li class="active">Profile</li>
    	</ol>	
    	<h3>Profile Setup</h3>

    	<form class="form-horizontal" action="<?php echo site_url("app/setup/profile/"); ?>" method="POST" id="profile-form" enctype="multipart/form-data">

    		<input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
    		<input type="hidden" name="address_id" value="<?php echo $result->address_id; ?>">
    		<input type="hidden" name="store_id" value="<?php echo $result->store_id; ?>">
    		<!-- STORE SETTINGS -->
    		<div class="card margin-bottom">
	    		<div class="card-body">
	    			<div class="row no-margin-bottom">
	    				<div class="col-xs-12 col-lg-6">
	    					<dl class="dl-horizontal text-left">
	    						<dt>First Name</dt>
	    						<dd>
    								<input type="text" name="firstname" class="form-control" value="<?php echo $result->firstname; ?>" required="true">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Last Name</dt>
	    						<dd>
    								<input type="text" name="lastname" class="form-control" value="<?php echo $result->lastname; ?>" required="true">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Display Name</dt>
	    						<dd>
    								<input type="text" name="display_name" class="form-control" value="<?php echo $result->display_name; ?>" required="true">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Username</dt>
	    						<dd>
    								<span><?php echo $result->username; ?></span>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Email Address</dt>
	    						<dd>
    								<span><?php echo $result->email_address; ?></span>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Phone</dt>
	    						<dd>
    								<input type="text" name="phone" class="form-control" value="<?php echo $result->phone; ?>" required="true">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Role</dt>
	    						<dd>
	    							<span><?php echo $result->role; ?></span>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Status</dt>
	    						<dd>
    								<?php echo $result->status; ?>
	    						</dd>
	    					</dl>
	    				</div>
	    				<div class="col-xs-12 col-lg-6">
	    					<dl class="dl-horizontal">
	    						<dt>Profile Image</dt>
	    						<dd>
    								<div class="preview-image">
	                                    <img src="<?php echo site_url("thumbs/images/user/$result->image_path/150/150/$result->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom" alt="Profile image">
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
    								<input type="text" name="store_name" class="form-control" value="<?php echo $result->store_name; ?>">
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Company Logo</dt>
	    						<dd>
	                                <div class="image">
	                                    <img src="<?php echo $result->logo; ?>" class="img img-responsive thumbnail no-margin-bottom" alt="Company logo" style="width: 90px; height: auto;">
	                                </div>
	                                <input type="file" name="logo" id="company_logo" class="btn btn-default">
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
	    				<div class="title">Address</div>
	    			</div>
	    		</div>
	    		<div class="card-body">
	  
	    			<div class="row no-margin-bottom">
	    				<div class="col-xs-12">
	    					<dl class="dl-horizontal text-left">
	    						<dt>Street 1</dt>
	    						<dd>
	    							<input type="text" name="physical[street1]" class="form-control" value="<?php echo $result->street1; ?>" required="true">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Street 2</dt>
	    						<dd>
	    							<input type="text" name="physical[street2]" class="form-control" value="<?php echo $result->street2; ?>">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Suburb</dt>
	    						<dd>
	    							<input type="text" name="physical[suburb]" class="form-control" value="<?php echo $result->suburb; ?>">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>City</dt>
	    						<dd>
	    							<input type="text" name="physical[city]" class="form-control" value="<?php echo $result->city; ?>" required="true">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Postcode</dt>
	    						<dd>
	    							<input type="text" name="physical[postcode]" class="form-control" value="<?php echo $result->postcode; ?>">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>State</dt>
	    						<dd>
	    							<input type="text" name="physical[state]" class="form-control" value="<?php echo $result->state; ?>" required="true">
	    						</dd>
	    					</dl>

	    					<dl class="dl-horizontal text-left">
	    						<dt>Country</dt>
	    						<dd>
	    							<select class="form-control" name="physical[country]" id="country" required="true" value="<?php echo $result->country; ?>">
		                                <?php foreach($countries_list as $code =>  $country) : ?>
		                                    <option value="<?php echo $code; ?>" <?php echo ($code == $result->country) ? "selected" : "" ; ?>><?php echo $country?></option>
		                                <?php endforeach; ?>
		                            </select>
	    						</dd>
	    					</dl>
	    					<dl class="dl-horizontal text-left">
	    						<dt>Country Code</dt>
	    						<dd>
	    							<input type="text" name="physical[countryCode]" class="form-control" value="<?php echo $result->countryCode;?>">
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

	    	<div class="text-right margin-bottom">
	    		<a href="<?php echo site_url('app/dashboard');?>" class="btn btn-default">Cancel</a>
	    		<input type="submit" name="submit" value="Save" class="btn btn-success">
	    	</div>
    	</form>
    </div>
</div>