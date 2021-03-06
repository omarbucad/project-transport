<script type="text/javascript">
    //$(document).ready(function(){
        // var role = $(this).find(":selected").val();

        // if(role != "ADMIN" && role != "MANAGER"){
        //     $('#checklist_section').removeClass("hidden");
        // }
        // else{
        //     $('#checklist_section').addClass("hidden");   
        // }
   // });
    $(document).on('change' , '#profile_image' , function(){
        readURL($(this) , ".image-preview" , 'background');
    });
    $(document).on('change', '#user_role', function(){
        var role = $(this).find(":selected").val();

        if(role != "ADMIN" && role != "MANAGER"){
            $('#checklist_section').removeClass("hidden");
        }
        else{
            $('#checklist_section').addClass("hidden");   
        }
    });
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <?php if($this->session->userdata('user')->role != "MECHANIC") : ?>
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/accounts'); ?>">Manage Drivers</a></li>
            <li class="active">Edit Driver Information</li>
        </ol>
        <?php endif; ?>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="<?php echo site_url('app/accounts');?>" class="btn btn-primary btn-same-size">Cancel</a>
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container ">
            <form action="<?php echo site_url("app/accounts/edit/").$this->hash->encrypt($result->user_id);?>" method="post" enctype="multipart/form-data" id="form_users">
                <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
                <section class="sec_border_bottom">
                    <h3>Profile</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Personal and contact information for this user.</p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" value="<?php echo $result->username; ?>"  class="form-control" placeholder="Username" readonly="true">
                            </div>
                             <div class="form-group">
                                <label for="display_name">First Name</label>
                                <input type="text" name="firstname" id="firstname" value="<?php echo $result->firstname; ?>"  class="form-control" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <label for="display_name">Last Name</label>
                                <input type="text" name="lastname" id="lastname" value="<?php echo $result->lastname; ?>"  class="form-control" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <label for="display_name">Display Name</label>
                                <input type="text" name="display_name" id="display_name" value="<?php echo $result->display_name; ?>"  class="form-control" placeholder="Display Name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email"  value="<?php echo $result->email_address; ?>"  class="form-control" placeholder="name@email.com" readonly="true">
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Profile Image</label>
                                <div class="image">
                                    <img src="<?php echo site_url("thumbs/images/user/$result->image_path/150/150/$result->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom" alt="Profile image">
                                </div>
                                <input type="file" name="file" id="profile_image" class="btn btn-default">
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="sec_border_bottom">
                    <h3>Security</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>Have a secure password to make sure your account stay safe.</p>
                        </div>
                        <div class="col-xs-12 col-lg-8">
                            <h4 style="margin-top: 0px;">CHANGE PASSWORD</h4>
                            <span>When you need to sign in to <?php echo $application_name; ?>, you will be asked to provide your pasword.</span>
                            <br>
                            <br>
                            <div class="row">
                                <div class="col-xs-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="enter_password">New Password</label>
                                        <input type="password" name="password" class="form-control" id="enter_password">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right margin-bottom">
                        <a href="<?php echo site_url('app/accounts');?>" class="btn btn-primary btn-same-size">Cancel</a>
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>