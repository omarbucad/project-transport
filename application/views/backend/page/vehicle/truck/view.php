<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
        var filter = "<?php echo ($this->input->get('submit') != 'Search') ? 'hidden' : 'show'; ?>";
        if(filter == 'hidden'){          
          $('.more-filter').data("value" , "hidden");
          $('#view_advance_search').addClass("hide");
          $('.more-filter').text("More Filter");
          $('#_advance_search_value').val("false");
        }else{
          $('.more-filter').data("value" , "show");
          $('#view_advance_search').removeClass("hide");
          $('.more-filter').text("Less Filter");
          $('#_advance_search_value').val("true");
        }
        $('#type').val('<?php echo $this->input->get("type");?>');
        ('#driver_seat').val('<?php echo $this->input->get("driver_seat");?>');
        $('#availability').val('<?php echo $this->input->get("availability");?>');
        $('#status').val('<?php echo $this->input->get("status");?>');
    });
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

    $(document).on("click" , ".btn-add" , function(){

        $("#addvehicle").modal("show");
    });

    $(document).on("click" , ".add-vehicle" , function(){

        var form = $("#add-form");
        var reg = form.find("input[name='registration_number']").val();  
        var type = form.find("#type").val();  
        var c = confirm("Are you sure?");

        if(c == true){
            if(reg == '' || type == ''){
                alert("registration number is required");
                return true;
            }else{
                form.submit();
            }
        }
    });

    $(document).on("click" , ".btn-edit" , function(){

        $("#editvehicle").modal("show");

        var form = $('#edit-form');
        var url = $(this).data("href");
        var id = $(this).data("id");
        var editurl = "<?php echo site_url('app/vehicle/edit/')?>" + id;
        form.attr("action",editurl);

        $.ajax({
            url : url ,
            method : "POST" ,
            success : function(response){
              var json = jQuery.parseJSON(response);
              if(json.status){
                
                form.trigger('reset');
                console.log(json.data);
                form.find("#edit_avail").attr("value",json.data.availability);  
                form.find("#edit_type").attr("value",json.data.vehicle_type_id);  
                form.find("input[name='vehicle_registration_number']").val(json.data.vehicle_registration_number);  
                
              }
            }
        });
    });

    $(document).on("click" , ".edit-vehicle" , function(){

        var form = $("#edit-form");
        var reg = form.find("input[name='vehicle_registration_number']").val();  
        var c = confirm("Are you sure?");

        if(c == true){
            if(reg == ''){
                alert("registration number is required");
                return true;
            }else{
                form.submit();
            }
        }
    });

    $(document).on("click", ".input-group-addon",function(){
      $(".daterange").focus();
    });

    $(document).on("click","#clear",function(){
        $('input[name="created"]').val("");
    });
</script>
<style type="text/css">
  .input-group-addon, .datepicker{
    cursor: pointer;
  }
</style>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container" >
        	<h1>Manage Vehicles</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <!-- <?php if($plan_type == "Basic" && (count($result) < $this->session->userdata('user')->no_accounts)) : ?>
                            <a href="javascript:void(0);" data-href="<?php echo site_url("app/vehicle/add"); ?>" class="btn btn-success btn-add">Add Vehicle</a>
                        <?php elseif($plan_type == "Standard" &&  $totalvehicle < $this->session->userdata('user')->no_accounts) : ?>
                            <a href="javascript:void(0);" data-href="<?php echo site_url("app/vehicle/add"); ?>" class="btn btn-success ">Add Vehicle</a>
                        <?php elseif($plan_type == "Trial" || $plan_type == "Premium") : ?> -->
                            <a href="javascript:void(0);" data-href="<?php echo site_url("app/vehicle/add"); ?>" class="btn btn-success btn-add">Add Vehicle</a>
                        <!-- <?php endif; ?> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url('app/vehicle')?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_name">Registration Number</label>
                                    <input type="text" name="registration_number" class="form-control" id="s_name" value="<?php echo $this->input->get("registration_number"); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="type">Vehicle Type</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="">- Select Vehicle Type -</option>
                                        <?php foreach($types as $key => $val) :?>
                                            <option value="<?php echo $val->vehicle_type_id;?>" <?php echo ($this->input->get("type") == "<?php echo $val->vehicle_type_id;?>") ? "selected" : "" ; ?> ><?php echo $val->type;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="availability">Availability</label>
                                    <select class="form-control" name="availability" id="availability">
                                        <option value="">- Select Vehicle Availability -</option>
                                        <option value="1" <?php echo ($this->input->get("availability") == "1") ? "selected" : "" ; ?>>Available</option>
                                        <option value="0" <?php echo ($this->input->get("availability") == "0") ? "selected" : "" ; ?>>Unavailable</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 text-right">
                              <div class="btn-group">
                                <button type="button" class="btn btn-link btn-vertical-center btn-same-size more-filter" data-value="hidden">More filter</button>
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                              </div>
                            </div>
                        </div>
                        <div class="row hide" id="view_advance_search">

                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">- Select Vehicle Status -</option>
                                        <option value="0">Initial</option>
                                        <option value="1">Checking</option>
                                        <option value="2">Checked</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="last_checked">Last Checked Date</label>
                                    <div class='input-group'>
                                        <input type="text" name="last_checked" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("last_checked"); ?>" placeholder="Select Date"  onkeydown="return false">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="driver_seat">Driver Seat Position</label>
                                    <select class="form-control" name="driver_seat" id="driver_seat">
                                        <option value="">- Select Driver Seat Position -</option>
                                        <option value="0">Left</option>
                                        <option value="1">Right</option>
                                        <option value="2">Not set</option>
                                    </select>
                                </div>
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
                        <th width="20%">Registration Number</th>
                        <th width="10%">Type</th>
                        <th width="10%">Availability</th>
                        <th width="10%">Status</th>
                        <th width="10%">Driver Seat Position</th>
                        <th width="20%"></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach($result as $row) : ?>
                        <tr class="customer-row" style="cursor: default;">
                            <td>
                                <div class="row">
                                    <div class="col-xs-6 col-lg-3 no-margin-bottom" style="padding-right: 0;">
                                        <img src="<?php echo $row->type_img;?>" class="img img-responsive thumbnail no-margin-bottom" style="height:50px; width:55px;" alt="Vehicle Type Image">
                                    </div>
                                    <div class="col-xs-6 col-lg-9 no-margin-bottom" style="padding-top: 15px;">
                                        <span><strong><?php echo $row->vehicle_registration_number; ?></strong><span class="help-block"><?php echo $row->vehicle_model." - ".$row->year_model;?></span><span class="help-block">Axles: <?php echo ($row->axle == 0)? "Not set" : $row->axle;?></span></span>
                                    </div>
                                </div>
                            </td>
                            <td><span><?php echo $row->type;?></span></td>
                            <td><span><?php echo $row->availability;?></span></td>
                            <td><span><?php echo $row->status;?></span> 
                                <small class="help-block">Last checked: 
                                <?php if($row->last_checked != '') : ?>
                                    <?php echo $row->last_checked; ?>
                                <?php endif; ?>
                                </small>
                            </td>
                            <td><?php echo $row->axle; ?></td>
                            <td><?php echo $row->driver_seat_position; ?></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("app/vehicle/get_vehicle_info/").$this->hash->encrypt($row->vehicle_id); ?>" data-id="<?php echo $this->hash->encrypt($row->vehicle_id); ?>" class="btn btn-link btn-edit" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("app/vehicle/delete/").$this->hash->encrypt($row->vehicle_id); ?>" class="btn btn-link btn-delete" title="Remove Vehicle"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
<div class="modal fade" id="addvehicle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Vehicle</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo site_url('app/vehicle/add');?>" method="POST" id="add-form">
            <div class="form-group">
                <label for="vehicle_registration_number">Registration Number</label>
                <input type="text" name="vehicle_registration_number" class="form-control" placeholder="Registration Number" required="true">
            </div>
            <div class="form-group">
                <label for="type">Vehicle Type</label>
                <select class="form-control" name="type" id="type">
                    <option value="">- Select Vehicle Type -</option>
                    <?php foreach($types as $key => $val) :?>
                        <option value="<?php echo $val->vehicle_type_id;?>" <?php echo ($this->input->get("type") == "<?php echo $val->vehicle_type_id;?>") ? "selected" : "" ; ?> ><?php echo $val->type;?></option>
                    <?php endforeach; ?>                    
                </select>
            </div>
             <div class="form-group">
                <label for="status">Availability</label>
                <select class="form-control" name="availability">
                    <option value="1">Available</option>
                    <option value="0">Unavailable</option> 
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="javascript:void(0);" class="btn btn-primary add-vehicle">Confirm</a>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editvehicle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Vehicle</h4>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id="edit-form">
            <div class="form-group">
                <label for="vehicle_registration_number">Registration Number</label>
                <input type="text" name="vehicle_registration_number" class="form-control" placeholder="Registration Number" required="true" readonly="true">
            </div>
            <div class="form-group">
                <label for="type">Vehicle Type</label>
                <select class="form-control" name="type" id="edit_type">
                    <option value="">- Select Vehicle Type -</option>
                    <?php foreach($types as $key => $val) :?>
                        <option value="<?php echo $val->vehicle_type_id;?>" <?php echo ($this->input->get("type") == "<?php echo $val->vehicle_type_id;?>") ? "selected" : "" ; ?> ><?php echo $val->type;?></option>
                    <?php endforeach; ?>                    
                </select>
            </div>
            <div class="form-group">
                <label for="status">Availability</label>
                <select class="form-control" name="availability" value="" id="edit_avail">
                    <option value="1">Available</option>
                    <option value="0">Unavailable</option> 
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="javascript:void(0);" class="btn btn-primary edit-vehicle">Confirm</a>
      </div>
    </div>
  </div>
</div>