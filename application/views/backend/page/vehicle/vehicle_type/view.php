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

    $(document).on("click" , ".add-type" , function(){
       var modal = $("#addvehicletype").modal("show");
    });

    $(document).on("click" , ".btn-edit" , function(){
       var modal = $("#editvehicletype").modal("show");
       var url = $(this).data("href");
       var id = $(this).data("id");
       $("#edit-type-form").attr("action","<?php echo site_url('admin/vehicle/edit/')?>"+id);


        $.ajax({
            url : url,
            success : function(response){
                var json = jQuery.parseJSON(response);
                $("#edit-type-form").find(("input[name='name']")).attr("value",json.data.type);
                $("#edit-type-form").find("select").val(json.data.status);

            }
        });
    });

    $(document).on("click" , ".add-vehicletype" , function(){
        var c = confirm("Are you sure?");

        if(c == true){
            if($('#type-name').val() == ''){
                alert("type is required.");
            }else{
                $('#type-form').submit();
            }
        }
       
    });

    $(document).on("click" , ".edit-vehicletype" , function(){
        var c = confirm("Are you sure?");

        if(c == true){
            if($('#edit-type-name').val() == ''){
                alert("type is required.");
            }else if($('#edit-status').val() == ''){
                alert("status is required");
            }else{
                $('#edit-type-form').submit();
            }
        }
       
    });


</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container" >
        	<h1>Vehicle Type</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success add-type">Add Vehicle Type</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url('admin/vehicle/type');?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_name">Vehicle Type</label>
                                    <input type="text" name="type_name" class="form-control" id="s_name" placeholder="Vehicle Type" value="<?php echo $this->input->get("type_name"); ?>">
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
                        <th width="30%">Type</th>
                        <th width="20%">Status</th>
                        <th width="30%"></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach($result as $row) : ?>
                        <tr class="customer-row" style="cursor: default;">
                            <td>
                                <span><strong><a href="<?php echo site_url("app/vehicle/type/?type_id=$row->vehicle_type_id"); ?>" class="link-style"><?php echo $row->type; ?></a></strong></span>
                            </td>
                            <td><span><?php echo $row->status; ?></span></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("admin/vehicle/get_type_info/").$this->hash->encrypt($row->vehicle_type_id); ?>" data-id="<?php echo $row->vehicle_type_id?>" class="btn btn-link btn-edit" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("admin/vehicle/delete/").$this->hash->encrypt($row->vehicle_type_id); ?>" class="btn btn-link btn-delete" title="Remove Vehicle Type"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?> 
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addvehicletype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Vehicle Type</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo site_url('admin/vehicle/add');?>" method="POST" id="type-form">
          <div class="form-group">
            <label for="name">Type</label>
            <input type="text" name="name" class="form-control" placeholder="Vehicle Type" required="true" id="type-name">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="javascript:void(0);" class="btn btn-primary add-vehicletype">Confirm</a>
      </div>
    </div>
  </div>
</div>
<!-- edit Modal -->
<div class="modal fade" id="editvehicletype" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Vehicle Type</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo site_url('app/vehicle/edit');?>" method="POST" id="edit-type-form">
          <div class="form-group">
            <label for="name">Type</label>
            <input type="text" name="name" class="form-control" placeholder="Vehicle Type" required="true" id="edit-type-name">
          </div>
          <div class="form-group">
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status" id="edit-status" value="">
                    <option value="">- Select Status -</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="javascript:void(0);" class="btn btn-primary edit-vehicletype">Confirm</a>
      </div>
    </div>
  </div>
</div>

