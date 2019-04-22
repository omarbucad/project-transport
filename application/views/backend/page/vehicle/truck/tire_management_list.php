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
                form.find("select[name='availability']").val(json.data.availability);  
                form.find("#type").val(json.data.vehicle_type_id);  
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
        	<h1>Tyre Management Reports</h1>
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
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="created">Created</label>
                                    <div class='input-group'>
                                        <input type="text" name="created" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("created"); ?>" placeholder="Select Date"  onkeydown="return false">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 text-right">
                              <div class="btn-group">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                              </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <div class='input-group'>
                                        <input type="text" name="status" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("status"); ?>" placeholder="Select Date"  onkeydown="return false">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                    
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
                        <th width="30%">Registration Number</th>
                        <th width="10%">Type</th>
                        <th width="20%">Report Status</th>                        
                        <th width="20%">Created</th>
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
                                        <span><strong><?php echo $row->vehicle_registration_number; ?></strong><span></span></span>
                                    </div>
                                </div>
                            </td>
                            <td><span><?php echo $row->type;?></span></td>
                            <td><span><?php echo $row->status;?></span></td>
                            <td><span><?php echo $row->created;?></span></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?php echo $this->config->site_url('app/vehicle/pdf/').$this->hash->encrypt($row->tire_report_id);?>" class="btn btn-link btn-print" style="padding: 3px 6px;margin:0;" title="View PDF" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?> 
                </tbody>
            </table>
        </div>
    </div>
</div>