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

    $(document).on("submit", "#search-filter", function(){
        $('form#search-filter').submit();
    });

    $(document).on("click" , ".btn-update" , function(){
        var url = $(this).data("href");        
        var id = $(this).data('id');
        var update_url = "<?php echo site_url('app/report/update/');?>";
        
        $.ajax({
            url : url ,
            method : "POST" ,
            success : function(response){
              var json = jQuery.parseJSON(response);
              if(json.status){
                var report_id = json.data.report_id;
                $("#update-form").trigger('reset');
                $("#update-form").find("#status").val(json.data.status);  
                $("#update-form").attr("action",update_url+report_id);
              }
            }
        });
        var modal = $('#updateModal').modal("show");
        modal.find(".modal-title").html("Report #"+id);
        
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).on("click" , ".update-status" , function(){
      var note = $("#notes").val();
      if(note == ""){
        alert("Note is required");
      }else{
        var c = confirm("Are you sure?");
        if(c == true){

          $("#update-form").submit();
        }else{
          return false;
        }
      }
    });

    $(document).on("change", ".datepicker", function(){      

      var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sept","Oct","Nov","Dec"];

      var selected = $(this).val();
      var d = new Date(selected);

      d.setDate(d.getDate() +parseInt(6));
      var enddate = d.getDate() + ' ' + months[d.getMonth()]+ ' ' + d.getFullYear();   


      if($('div.selectedDate').length){
        $("div.selectedDate").html('<span>'+selected+ ' - ' +enddate+ '</span>');
        $("#date-range").text(selected+ ' - ' +enddate);
      }else{
        $("div.daterangepicker_input").before('<div class="selectedDate text-center"><span>'+selected+'</span></div>');
        $("#date-range").text(selected+ ' - ' +enddate);
      }
      
    });

    
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container" >
        	<h1>Weekly Reports</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                    <?php if(!empty($result)) :?>
                      <a href="<?php echo site_url('app/report/pdf_weekly/?').$_SERVER['QUERY_STRING']; ?>" target="_blank" class="btn btn-primary ">Print</a>
                    <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("app/report/weekly"); ?>" method="GET" id="search-filter">
                        <div class="row">
                            <div class="col-lg-8">
                              <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                  <label for="s_name">Driver Name</label>
                                  <select class="form-control" name="driver" value="<?php echo set_value('driver');?>">
                                    <option value="">- Select Driver -</option>
                                    <?php foreach($driver_list as $row) :?>
                                      <option value="<?php echo $row->user_id; ?>" <?php echo ($this->input->get("driver") == $row->user_id) ? "selected" : "" ;?> ><?php echo $row->display_name; ?></option>
                                    <?php endforeach; ?>     
                                  </select>
                                </div>
                              </div>
                              
                              <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                  <label for="s_name">Checklist Type</label>
                                  <select class="form-control" name="checklist_type" value="<?php echo set_value('checklist_type');?>">
                                    <option value="">- Select Checklist Type -</option>
                                    <?php foreach($checklist_type as $row) :?>
                                      <?php if($row->checklist_name != "Trailer Checklist") : ?>
                                      <option value="<?php echo $row->checklist_id; ?>" <?php echo ($this->input->get("checklist_type") == $row->checklist_id) ? "selected" : "" ;?> ><?php echo $row->checklist_name; ?></option>
                                      <?php endif; ?>
                                    <?php endforeach; ?>     
                                  </select>
                                </div>
                              </div>
                              <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                  <label for="s_name">Vehicle Registration Number</label>
                                  <select class="form-control" name="vehicle" value="<?php echo set_value('vehicle');?>">
                                      <option value="">- Select Vehicle -</option>
                                      <?php foreach($vehicle_list as $row) :?>
                                        <option value="<?php echo $row->vehicle_registration_number; ?>" <?php echo ($this->input->get("vehicle") == $row->vehicle_registration_number) ? "selected" : "" ;?> ><?php echo $row->vehicle_registration_number; ?></option>
                                      <?php endforeach; ?>     
                                  </select>
                                </div>
                              </div>

                              <div class="col-xs-12 col-lg-6">
                                <div class="form-group">

                                    <label for="s_name">Start Date Report ( <small class="text-right" id="date-range"></small> )</label>
                                    <input type="text" name="date" class="form-control datepicker" autocomplete="off" value="<?php echo $this->input->get("date"); ?>" placeholder="Select Report Start Date" required="true">
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="text-right">
                                <div class="btn-group">
                                  <input type="submit" name="submit" value="View Weekly Report" class="btn btn-primary btn-vertical-center btn-same-size">
                                </div>
                              </div>
                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
        <div class="container">

        <?php if(isset($result['header'][0])) :?>
            <h1><?php echo $result['header'][0]->store_name; ?></h1>
            <h4><?php echo $result['header'][0]->address; ?>
            <h3><?php echo $result['header'][0]->checklist_name; ?></h3>
            <div class="row" style="margin-top: 20px;">
              <div class="col-md-6 text-left">
                <label>WEEK START DATE:<span style="margin-left: 20px;"><?php echo $result['header'][0]->startrange; ?></span></label>                
              </div>
              <div class="col-md-6 text-left">
                <label>WEEK FINISH DATE:<span style="margin-left: 20px;"><?php echo $result['header'][0]->endrange; ?></span></label>
              </div>          
            </div>

            <div class="panel panel-default">
              <div class="panel-body">
                <table class="table" style="margin-bottom: 30px;">
                  <thead>
                    <tr>
                      <th>DRIVERS NAME</th>
                      <th>VEHICLE REG</th>
                      <th>VEHICLE TYPE</th>
                      <th>DATE</th>
                      <th>START MILEAGE</th>
                      <th>FINISH MILEAGE</th>
                      <th>DRIVERS SIGNATURE</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $headercount = 0;?>
                    <?php foreach($result['header'] as $row) : ?>
                      <?php $headercount++;?>
                      <?php if($headercount <= 7) : ?>
                      <tr class="customer-row">
                        <td><span><?php echo $row->display_name; ?></span></td>
                        <td><span><?php echo $row->vehicle_registration_number; ?></span></td>
                        <td><span><?php echo $row->type; ?></span></td>
                        <td><span><?php echo $row->created;?></span></td>
                        <td><span><?php echo $row->start_mileage?></span></td>
                        <td><span><?php echo $row->end_mileage?></span></td>
                        <td><span>
                          <?php if(isset($row->signature)) : ?>
                          <img src="<?php echo $row->signature; ?>" style="height: 40px;">  
                          <?php endif;?>                        
                        </span></td>
                      </tr>
                    <?php endif; ?>
                    <?php endforeach;?>
                  </tbody>              
                </table>
              </div>
            </div>

            <div class="panel panel-default">
              <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th><span style="display: block;">CHECKLIST ITEMS</span></th>
                            <th>DAY 1</th>
                            <th>DAY 2</th>
                            <th>DAY 3</th>
                            <th>DAY 4</th>
                            <th>DAY 5</th>
                            <th>DAY 6</th>
                            <th>DAY 7</th>
                        </tr>
                        <tr>
                        <?php $countdays = 0;?> 
                            <th>DATE</th>
                            <?php foreach($result['header'] as $row) : ?>
                              <?php $countdays++;?>
                              <?php if($countdays <= 7): ?>                              
                                <td><span><small><?php echo $row->status_created; ?></small></span></td>
                              <?php endif;?>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>                          
                      <?php foreach($result['checklist'] as $key => $value) : ?>
                        <tr class="customer-row" style="cursor: default;">
                          <td><span><?php echo $value['item_name'];?></span></td>
                          <?php if($value['day1'] != '') : ?>
                          <td><span><?php if($value['day1'] == 1){ echo 'DEFECT'; } elseif($value['day1'] == 2){echo 'RUNNING DEFECT';} else{ echo 'RUNNING DEFECT'; }?></span></td>
                          <?php endif; ?>
                          <?php if($value['day2'] != '') : ?>
                          <td><span><?php if($value['day2'] == 1){ echo 'DEFECT'; } elseif($value['day2'] == 2){echo 'RUNNING DEFECT';} else{ echo 'GOOD'; }?></span></td>
                          <?php endif; ?>
                          <?php if($value['day3'] != '') : ?>
                          <td><span><?php if($value['day3'] == 1){ echo 'DEFECT'; } elseif($value['day3'] == 2){echo 'RUNNING DEFECT';} else{ echo 'GOOD'; }?></span></td>
                          <?php endif; ?>
                          <?php if($value['day4'] != '') : ?>
                          <td><span><?php if($value['day4'] == 1){ echo 'DEFECT'; } elseif($value['day4'] == 2){echo 'RUNNING DEFECT';} else{ echo 'GOOD'; }?></span></td>
                          <?php endif; ?>
                          <?php if($value['day5'] != '') : ?>
                          <td><span><?php if($value['day5'] == 1){ echo 'DEFECT'; } elseif($value['day5'] == 2){echo 'RUNNING DEFECT';} else{ echo 'GOOD'; }?></span></td>
                          <?php endif; ?>
                          <?php if($value['day6'] != '') : ?>
                          <td><span><?php if($value['day6'] == 1){ echo 'DEFECT'; } elseif($value['day6'] == 2){echo 'RUNNING DEFECT';} else{ echo 'GOOD'; }?></span></td>
                          <?php endif; ?>
                          <?php if($value['day7'] != '') : ?>
                          <td><span><?php if($value['day7'] == 1){ echo 'DEFECT'; } elseif($value['day7'] == 2){echo 'RUNNING DEFECT';} else{ echo 'GOOD'; }?></span></td>
                          <?php endif; ?>
                          
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                </table>
              </div>
            </div>
        <?php else :?>
          <div class="row bg-warning">
            <div class="text-center">
              <h2  style="margin:30px !important;color:#929292;"><strong>No Report Available</strong></h2>
            </div>
          </div>
        <?php endif;?>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <form action="" method="POST" id="update-form">
          <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="status" id="status">
              <option value="0">No Defect</option>
              <option value="1">Open</option>
              <option value="2">On Maintenance</option>
              <option value="3">Fixed</option>
            </select>
          </div>
          <div class="form-group">
            <label>Note</label>
            <textarea class="form-control" name="notes" id="notes" required="true"></textarea>
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