<script type="text/javascript">
    $(document).on('change' , '#profile_image' , function(){
        readURL(this , ".image-preview" , 'background');
    });
    $(document).on('change', '#user_role', function(){
        var role = $(this).find(":selected").val();

        if(role != "ADMIN"){
            $('#checklist_section').removeClass("hidden");
        }
        else{
            $('#checklist_section').addClass("hidden");   
        }
    });

     $(document).on('click' , '.view_invoice_pdf' , function(){

        var modal = $('#invoice_pdf_modal').modal("show");

        var id = $(this).data("id");

        var a = $("<a>" , {href : $(this).data("pdf") , text:$(this).data("pdf") });
        var object = '<object data="'+$(this).data("pdf") +'" , type="application/pdf" style="width:100%;height:800px;">'+a+'</object>';

        $('#_pdfViewer').html(object);  
    });
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

    	<ol class="breadcrumb">
            <li><a href="<?php echo site_url('admin/accounts'); ?>">Accounts</a></li>
            <li class="active">User Details</li>
        </ol>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="container ">
            <form action="<?php echo site_url("admin/accounts/view/").$result->user_id;?>" method="post" enctype="multipart/form-data" id="form_users">
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
                                <label for="display_name">Display Name</label>
                                <input type="text" name="display_name" id="display_name" value="<?php echo $result->display_name; ?>"  class="form-control" placeholder="Display Name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email"  value="<?php echo $result->email_address; ?>"  class="form-control" placeholder="name@email.com" readonly="true">
                            </div>
                            <?php print_r_die($role);?>
                            <?php if($role != "ADMIN")?>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input type="text" name="role" value="<?php echo $result->role; ?>"  class="form-control" readonly="true">
                                </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status"  class="form-control" value="<?php echo $result->status; ?>">
                                    <option <?php echo ($result->status == 1) ? "selected" : "" ; ?> value="1">Active</option>
                                    <option <?php echo ($result->status == 0) ? "selected" : "" ; ?> value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Profile Image</label>
                                <div class="image-preview">
                                    <img src="<?php echo site_url("thumbs/images/user/$result->image_path/150/150/$result->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom"  style="height: 100%;object-fit: cover;">
                                </div>
                                <input type="file" name="file" id="profile_image" class="btn btn-default">
                            </div>
                        </div>
                    </div>
                </section>
               
                <section class="sec_border_bottom" id="checklist_section">
                    <h3>User Plan</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p>List of user plan subscription.</p>
                        </div>
                        <div class="col-xs-12 col-lg-8">
                            <table class="table">
                                <tr>
                                    <th>Plan</th>
                                    <th>Billing Type</th>
                                    <th>Created</th>
                                    <th>Expiration</th>
                                    <th>Action</th>
                                </tr>
                                <?php foreach($result->subscription as $key => $value) : ?>

                                    <tr>
                                        <td><span><?php echo $value->title; ?></span></td>
                                        <td><span><?php echo $value->billing_type; ?></span></td>
                                        <td><span><?php echo $value->plan_created; ?></span></td>
                                        <td><span><?php echo $value->plan_expiration; ?></span></td>
                                        <td><a href="javascript:void(0);" data-pdf="<?php echo site_url().$value->invoice_pdf;?>" data-id="<?php echo $value->invoice_id; ?>" class="btn btn-link view_invoice_pdf" title="View Invoice"><i class="fa fa-search" aria-hidden="true"></i></a></td>
                                    </tr>                                    
                                <?php endforeach; ?>  
                            </table>                         
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
                                        <label for="enter_password">Enter Password</label>
                                        <input type="password" name="password" class="form-control" id="enter_password">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="confirm_password">Repeat Password</label>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success btn-same-size submit-form" data-form="#form_users">Save</a>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="invoice_pdf_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="defaultModalLabel">Invoice Information</h4>
            </div>
            <div class="modal-body" id="_pdfViewer">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>