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
        	<h1>Trailer Information</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <?php if($plan_type == "BASIC" && (count($result) < 1)) : ?>
                            <a href="<?php echo site_url("app/vehicle/trailer/add"); ?>" class="btn btn-success ">Add Trailer</a>
                        <?php elseif($plan_type == "STANDARD" &&  $totalvehicle < 10) : ?>
                            <a href="<?php echo site_url("app/vehicle/trailer/add"); ?>" class="btn btn-success ">Add Trailer</a>
                        <?php elseif($plan_type == "TRIAL" || $plan_type == "PREMIUM") : ?>
                            <a href="<?php echo site_url("app/vehicle/trailer/add"); ?>" class="btn btn-success ">Add Trailer</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url('app/vehicle/trailer');?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_name">Trailer number</label>
                                    <input type="text" name="trailer_number" class="form-control" id="s_name" placeholder="Trailer Number" value="<?php echo $this->input->get("trailer_number"); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_product_type">Status</label>
                                    <select class="form-control" name="status" id="s_product_type">
                                        <option value="">- Select Status -</option>
                                        <option value="1" <?php echo ($this->input->get("status") == "active") ? "selected" : "" ; ?> >Active</option>
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
                        <th width="30%">Trailer Number</th>
                        <th width="20%">Description</th>
                        <th width="20%">Status</th>
                        <th width="30%"></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach($result as $row) : ?>
                        <tr class="customer-row" style="cursor: default;">
                            <td>
                                <span><strong><a href="<?php echo site_url("app/report/?trailer_number=$row->trailer_number"); ?>" class="link-style"><?php echo $row->trailer_number; ?></a></strong></span>
                            </td>
                            <td><span><?php echo $row->description; ?></span></td>
                            <td><span><?php echo $row->status; ?></span></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?php echo site_url("app/vehicle/trailer/edit/").$this->hash->encrypt($row->trailer_id); ?>" class="btn btn-link" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("app/vehicle/trailer/delete/").$this->hash->encrypt($row->trailer_id); ?>" class="btn btn-link btn-delete" title="Remove Vehicle"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?> 
                </tbody>
            </table>
        </div>
    </div>
</div>