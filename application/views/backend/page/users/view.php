<script type="text/javascript">
    $(document).ready(function(){
        $('#status').val('<?php echo $this->input->get("status");?>');
    });
    $(document).on("click","#clear",function(){
        $('input[name="created"]').val("");
    });
     $(document).on("click", ".input-group-addon",function(){
      $(".daterange").focus();
    });
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
        	<h1>Manage Drivers</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <a href="<?php echo site_url("app/accounts/add"); ?>" class="btn btn-success ">Add Driver</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("app/accounts"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="name">Name or Username</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get("name")?>" id="name">
                                </div>
                            </div>
                            <!-- <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="roles">Roles</label>
                                    <select class="form-control" id="roles" name="roles">
                                        <option value="">All Roles</option>
                                        <option value="MECHANIC" <?php //echo ($this->input->get("roles") == "MECHANIC") ? "selected" : ""; ?>>Mechanic</option>
                                        <option value="DRIVER" <?php //echo ($this->input->get("roles") == "DRIVER") ? "selected" : ""; ?>>Driver</option>
                                        <option value="MANAGER" <?php //echo ($this->input->get("roles") == "MANAGER") ? "selected" : ""; ?>>Manager</option> -->
                                        <!-- <?php //if($this->session->userdata('user')->role == "ADMIN") : ?>
                                        <option value="ADMIN" <?php //echo ($this->input->get("roles") == "ADMIN") ? "selected" : ""; ?>>Admin</option>
                                        <?php //endif; ?>
                                    </select>
                                </div>
                            </div> -->
                             <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">- Select Status-</option>
                                        <option value="1" <?php echo ($this->input->get("status") == "1") ? "selected" : ""; ?>>Active</option>
                                        <option value="0" <?php echo ($this->input->get("status") == "0") ? "selected" : ""; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group" >
                                    <label for="created">Created</label>
                                    <div class='input-group'>
                                        <input type="text" name="created" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("created"); ?>" placeholder="Select Date" onkeydown="return false">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                                <span id="clear"><a class="btn btn-xs btn-default" type="button" style="border-color:#cccccc;margin-top:0;" id="clear">clear</a></span>
                            </div>
                            <div class="col-xs-12 col-lg-3 text-right">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
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
                        <th width="20%">Status</th>                        
                        <th width="25%">Created</th>
                        <th width="20%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-6 col-lg-2 no-margin-bottom">
                                            <img src="<?php echo site_url("thumbs/images/user/$row->image_path/80/80/$row->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom" alt="profile image">
                                        </div>
                                        <div class="col-xs-6 col-lg-10 no-margin-bottom">
                                            <?php echo $row->username; ?> ( <?php echo $row->display_name; ?> )<br>
                                            <small class="help-block"><?php echo $row->email_address; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span ><?php echo $row->status; ?></span></td>
                                <td><span ><?php echo $row->created; ?></span></td>
                                <td>
                                    <?php if($row->role != 'SUPER ADMIN') : ?>
                                    <div class="btn-group" role="group" aria-label="...">
                                    <?php if($row->role == "ADMIN") : ?>
                                        <a href="<?php echo site_url("app/setup/profile/").$this->hash->encrypt($row->user_id); ?>" class="btn btn-link" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <?php else : ?>
                                        <a href="<?php echo site_url("app/accounts/edit/").$this->hash->encrypt($row->user_id); ?>" class="btn btn-link" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <?php endif; ?>

                                    <?php if($row->role != "ADMIN" || $row->role != "SUPER ADMIN") : ?>
                                    <a href="<?php echo site_url("app/accounts/delete/").$this->hash->encrypt($row->user_id); ?>" class="btn btn-link btn-delete" title="Delete User"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    <?php endif; ?>
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
            
        </div>
    </div>
</div>
