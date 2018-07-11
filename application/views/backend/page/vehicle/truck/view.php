<script type="text/javascript">
    $(document).on('click' , '.more-filter' , function(){
        var val = $(this).data('value');

        if(val == "hidden"){
            $(this).data("value" , "show");
            $('#view_advance_search').removeClass("hide");
            $(this).text("Less Filter");
            $('#_advance_search_value').val("true");
        }else{
            $(this).data("value" , "hidden");
            $('#view_advance_search').addClass("hide");
            $(this).text("More Filter");
            $('#_advance_search_value').val("false");
        }
    });

    $(document).on("click" , ".btn-delete" , function(){

        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });

</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container" >
        	<h1>Vehicle Information</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <?php if($plan_type == "Basic" && (count($result) < $this->session->userdata('user')->no_accounts)) : ?>
                            <a href="<?php echo site_url("app/vehicle/truck/add"); ?>" class="btn btn-success ">Add Vehicle</a>
                        <?php elseif($plan_type == "Standard" &&  $totalvehicle < $this->session->userdata('user')->no_accounts) : ?>
                            <a href="<?php echo site_url("app/vehicle/truck/add"); ?>" class="btn btn-success ">Add Vehicle</a>
                        <?php elseif($plan_type == "Trial" || $plan_type == "PREMIUM") : ?>
                            <a href="<?php echo site_url("app/vehicle/truck/add"); ?>" class="btn btn-success ">Add Vehicle</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url('app/vehicle/truck')?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_name">Registration number</label>
                                    <input type="text" name="registration_number" class="form-control" id="s_name" placeholder="Registration Number" value="<?php echo $this->input->get("registration_number"); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_product_type">Status</label>
                                    <select class="form-control" name="status" id="s_product_type">
                                        <option value="">- Select Status -</option>
                                        <option value="1" <?php echo ($this->input->get("status") == "Active") ? "selected" : "" ; ?> >Active</option>
                                        <option value="0" <?php echo ($this->input->get("status") == "inactive") ? "selected" : "" ; ?>>Inactive</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 text-right no-margin-bottom">
                                <input type="submit" name="submit" value="Apply Filter" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <table class="customer-table">
                <thead>
                    <tr>
                        <th width="30%">Registration Number</th>
                        <th width="20%">Description</th>
                        <th width="20%">Status</th>
                        <th width="30%"></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach($result as $row) : ?>
                        <tr class="customer-row" style="cursor: default;">
                            <td>
                                <span><strong><?php echo $row->vehicle_registration_number; ?></strong></span>
                            </td>
                            <td><span><?php echo $row->description; ?></span></td>
                            <td><span><?php echo $row->status; ?></span></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?php echo site_url("app/vehicle/truck/edit/").$this->hash->encrypt($row->vehicle_id); ?>" class="btn btn-link" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("app/vehicle/truck/delete/").$this->hash->encrypt($row->vehicle_id); ?>" class="btn btn-link btn-delete" title="Remove Vehicle"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?> 
                </tbody>
            </table>
        </div>
    </div>
</div>