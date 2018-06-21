<script type="text/javascript">
    $(document).on("click" , ".update-plan" , function(){

        var url = $(this).data("href");        
        var id = $(this).data("id");

        // $.ajax({
        //     url : url ,
        //     method : "GET" ,
        //     success : function(response){
                // var json = jQuery.parseJSON(response);
                var modal = $("#updateModal").modal("show");
                
                // modal.find(".checklist-list").html(" ");
                // modal.find(".modal-title").html(name);
                // $.each(json.data , function(k , v){
                //     var list = $("<li>");
                //     list.append(v.item_name);
                //     modal.find(".checklist-list").append(list);
                // });

        //     }
        // });
    });
</script>

<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
        	<h1>Accounts</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <?php if($plan_type == "BASIC" && $total_accounts < 1) : ?>
                            <a href="<?php echo site_url("admin/accounts/add"); ?>" class="btn btn-success ">Add User</a>
                        <?php elseif($plan_type == "STANDARD" && $total_accounts < 5) : ?>
                            <a href="<?php echo site_url("admin/accounts/add"); ?>" class="btn btn-success ">Add User</a>
                        <?php elseif(($plan_type == "TRIAL" || $plan_type == "PREMIUM") && $total_accounts < 5) : ?>
                            <a href="<?php echo site_url("admin/accounts/add"); ?>" class="btn btn-success ">Add User</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("admin/accounts/"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_name">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get("name")?>" id="s_name" placeholder="Search by name">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_roles">User Plan</label>
                                    <select class="form-control" id="s_roles" name="plan">
                                        <option value="">All Plans</option>
                                        <option value="TRIAL" <?php echo ($this->input->get("plan") == "TRIAL") ? "selected" : ""; ?>>TRIAL</option>
                                        <option value="BASIC" <?php echo ($this->input->get("plan") == "BASIC") ? "selected" : ""; ?>>BASIC</option>
                                        <option value="STANDARD" <?php echo ($this->input->get("plan") == "STANDARD") ? "selected" : ""; ?>>STANDARD</option>
                                        <option value="PREMIUM" <?php echo ($this->input->get("plan") == "PREMIUM") ? "selected" : ""; ?>>PREMIUM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-4 text-right">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_roles">Billing Type</label>
                                    <select class="form-control" id="s_roles" name="type">
                                        <option value="">-Select Billing Type-</option>
                                        <option value="ANNUAL" <?php echo ($this->input->get("type") == "ANNUAL") ? "selected" : ""; ?>>Annual</option>
                                        <option value="MONTHLY" <?php echo ($this->input->get("type") == "MONTHLY") ? "selected" : ""; ?>>Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_roles">Status</label>
                                    <select class="form-control" id="s_roles" name="status">
                                        <option value="">- Select Status-</option>
                                        <option value="ACTIVE" <?php echo ($this->input->get("status") == "ACTIVE") ? "selected" : ""; ?>>Active</option>
                                        <option value="INACTIVE" <?php echo ($this->input->get("status") == "INACTIVE") ? "selected" : ""; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="container ">

            <table class="table my-table">
                <thead>
                    <tr>
                        <th width="35%">Name</th>                        
                        <th width="15%">Role</th>
                        <th width="15%">Account Status</th>
                        <th width="15%">Plan</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-6 col-lg-2 no-margin-bottom">
                                            <img src="<?php echo site_url("thumbs/images/user/$row->image_path/80/80/$row->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom">
                                        </div>
                                        <div class="col-xs-6 col-lg-10 no-margin-bottom">
                                            <span><?php echo $row->username; ?> ( <?php echo $row->display_name; ?> )</span><br>
                                            <small class="help-block"><?php echo $row->email_address; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span ><?php echo $row->role; ?></span></td>
                                <td><span ><?php echo $row->status; ?></span></td>
                                <td><span ><?php echo $row->plan_type; ?></span></td>
                                <td>
                                    <?php if($row->role != 'DEV ADMIN') : ?>
                                    <div class="btn-group" role="group" aria-label="...">
                                    <a href="javascript:void(0);" data-id="<?php echo $row->user_id; ?>" data-href="<?php echo site_url('admin/accounts/user_plan/update');?>" class="btn btn-link update-plan" title="Update User Plan"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("admin/accounts/delete/").$this->hash->encrypt($row->user_id); ?>" class="btn btn-link btn-delete" title="Delete User"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="customer-row">
                            <td colspan="4" class="text-center"><span>No Result</span></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="customer-table-showing margin-bottom">
                <span class="pull-left">
                    <?php 
                        $x = 1;

                        if( $this->input->get("per_page") ){
                            $x = $this->input->get("per_page") + 1;
                        }

                    ?>
                    <small>Displaying <?php echo $x; ?> – <?php echo ($x-1) + count($result) ; ?> of <?php echo $config['total_rows']; ?></small>
                </span>
                <div class="pull-right">
                    <nav aria-label="Page navigation">
                      <?php echo $links; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update User Plan</h4>
      </div>
      <div class="modal-body">
        <label>Current: </label><span> PLAN</span>
        <form action="" method="POST" id="update-form">
          <div class="form-group">
            <label>User Plan</label>
            <select class="form-control" name="plan_type" id="plan">                
              <option value="TRIAL">TRIAL</option>
              <option value="BASIC">BASIC</option>
              <option value="STANDARD">STANDARD</option>
              <option value="PREMIUM">PREMIUM</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <a href="javascript:void(0);" class="btn btn-primary update-status">Confirm</a>
      </div>
    </div>
  </div>
</div>