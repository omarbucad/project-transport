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
                        <?php if($plan_type == "Basic" && $total_accounts < 1) : ?>
                            <a href="<?php echo site_url("app/accounts/add"); ?>" class="btn btn-success ">Add User</a>
                        <?php elseif($plan_type == "Standard" && $total_accounts < 5) : ?>
                            <a href="<?php echo site_url("app/accounts/add"); ?>" class="btn btn-success ">Add User</a>
                        <?php elseif(($plan_type == "Trial" || $plan_type == "PREMIUM")) : ?>
                            <a href="<?php echo site_url("app/accounts/add"); ?>" class="btn btn-success ">Add User</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("app/users/"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get("name")?>" id="s_name" placeholder="Search by username or name">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Roles</label>
                                    <select class="form-control" id="s_roles" name="roles">
                                        <option value="">All Roles</option>
                                        <option value="MECHANIC" <?php echo ($this->input->get("roles") == "MECHANIC") ? "selected" : ""; ?>>Mechanic</option>
                                        <option value="DRIVER" <?php echo ($this->input->get("roles") == "DRIVER") ? "selected" : ""; ?>>Driver</option>
                                        <option value="MANAGER" <?php echo ($this->input->get("roles") == "MANAGER") ? "selected" : ""; ?>>Manager</option>
                                        <?php if($this->session->userdata('user')->role == "ADMIN") : ?>
                                        <option value="ADMIN" <?php echo ($this->input->get("roles") == "ADMIN") ? "selected" : ""; ?>>Admin</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                             <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Status</label>
                                    <select class="form-control" id="s_roles" name="status">
                                        <option value="">- Select Status-</option>
                                        <option value="ACTIVE" <?php echo ($this->input->get("status") == "ACTIVE") ? "selected" : ""; ?>>Active</option>
                                        <option value="INACTIVE" <?php echo ($this->input->get("status") == "INACTIVE") ? "selected" : ""; ?>>Inactive</option>
                                    </select>
                                </div>
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
                        <th width="45%">Name</th>
                        <th width="10%">Role</th>
                        <th width="10%">Status</th>
                        <th width="35%"></th>
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
                                            <?php echo $row->username; ?> ( <?php echo $row->display_name; ?> )<br>
                                            <small class="help-block"><?php echo $row->email_address; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span ><?php echo $row->role; ?></span></td>
                                <td><span ><?php echo $row->status; ?></span></td>
                                <td>
                                    <?php if($row->role != 'SUPER ADMIN') : ?>
                                    <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?php echo site_url("app/accounts/edit/").$this->hash->encrypt($row->user_id); ?>" class="btn btn-link" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("app/accounts/delete/").$this->hash->encrypt($row->user_id); ?>" class="btn btn-link btn-delete" title="Delete User"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
