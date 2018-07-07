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
    $(document).on("change", "#selectlimit", function(){
        $('form#search-filter').find('input[type=submit]').click();
    });

    $(document).on("submit", "#search-filter", function(){
        $('#limit').attr("value",$("#selectlimit").find(":selected").val());
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
    
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container" >
        	<h1>Daily Reports</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                      <?php if($plan_type == "Standard" || $plan_type == "Trial" || $plan_type == "Premium") : ?>
                        <a href="<?php echo my_current_url("&export=true"); ?>" class="btn btn-primary ">Export</a>
                      <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("app/report/daily"); ?>" method="GET" id="search-filter">
                        <div class="row">
                            <div class="col-xs-12 col-lg-3">
                                <input type="hidden" id="limit" name="limit" value="<?php echo $this->input->get('rowlimit');?>">
                                <div class="form-group">
                                    <label for="s_name">Report No.</label>
                                    <input type="text" name="report_number" value="<?php echo $this->input->get("report_number"); ?>" class="form-control" placeholder="Search by Report No.">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Checklist Type</label>
                                    <input type="text" name="checklist_name" value="<?php echo $this->input->get("checklist_name"); ?>" class="form-control" placeholder="Search by Checklist Type">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_roles">Status</label>
                                    <select class="form-control" name="status" value="<?php echo set_value('status');?>">
                                        <option value="">- Select Status -</option>
                                        <option value="0" <?php echo ($this->input->get("status") == "0") ? "selected" : "" ;?> >No Defect</option>
                                        <option value="1" <?php echo ($this->input->get("status") == "1") ? "selected" : "" ;?>>Open</option>
                                        <option value="2" <?php echo ($this->input->get("status") == "2") ? "selected" : "" ;?>>On Maintenance</option>
                                        <option value="3" <?php echo ($this->input->get("status") == "3") ? "selected" : "" ;?>>Fixed</option>
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

                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Date Period</label>
                                    <input type="text" name="date" class="form-control daterange" autocomplete="off" value="<?php echo $this->input->get("date"); ?>" placeholder="Search by Date">
                                </div>
                            </div>

                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Vehicle Registration No.</label>
                                    <input type="text" name="vehicle_registration_number" value="<?php echo $this->input->get("vehicle_registration_number"); ?>" class="form-control"  placeholder="Search by Vehicle Registration No.">
                                </div>
                            </div>

                            <div class="col-xs-12 col-lg-3">
                                <div class="form-group">
                                    <label for="s_name">Trailer No.</label>
                                    <input type="text" name="trailer_number" value="<?php echo $this->input->get("trailer_number"); ?>" class="form-control"  placeholder="Search by Trailer No.">
                                </div>
                            </div>

                            <div class="col-xs-12 col-lg-3">
                                <div class="form-line">
                                <label for="report_by">Report By</label>
                                <input type="text" class="form-control" name="report_by" list="user" placeholder="Report By">
                            </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
        <div class="container">
            <table class="customer-table">
              <?php if($result) : ?>
                <thead>
                    <tr>
                        <th>Report No.</th>
                        <th>Type</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>
                          <span style="margin-right:10px;">Show</span>
                          <select name="rowlimit" id="selectlimit" value="<?php echo set_value('rowlimit');?>" >
                              <option value="0" <?php echo ($this->input->get('limit') == "all") ? "selected": ""; ?>>All</option>
                              <option value="10" <?php echo ($this->input->get('limit') == "10") ? "selected": ""; ?>>10</option>
                              <option value="25" <?php echo ($this->input->get('limit') == "25") ? "selected": ""; ?>>25</option>
                              <option value="50" <?php echo ($this->input->get('limit') == "50") ? "selected": ""; ?>>50</option>
                          </select>
                        </th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach($result as $row) : ?>
                        <tr class="customer-row" style="cursor: default;">
                            <td valign="top">
                              <span><strong><a href="<?php echo site_url('app/report/view/').$this->hash->encrypt($row->report_id);?>" class="link-style"><?php echo $row->report_number; ?></a></strong> 
                                  
                                <small class="help-block"><strong>Report by</strong>: <?php echo $row->display_name; ?></small>
                                <?php if($row->vehicle_registration_number) : ?>
                                   <small class="help-block"><strong>Vehicle</strong>: <?php echo $row->vehicle_registration_number; ?></small>
                                <?php endif; ?>
                                <?php if($row->trailer_number) : ?>
                                  <small class="help-block"><strong>Trailer</strong>: <?php echo $row->trailer_number; ?></small>
                                <?php endif; ?>
                              </span>
                                
                            </td>
                            <td valign="top"><span><?php echo $row->checklist_name; ?></span></td>
                            <td valign="top"><span>
                              <?php if($row->report_notes != ""): ?>
                                <span class="label label-success" data-toggle="tooltip" title="<?php echo $row->report_notes;?>">Yes</span>
                              <?php else: ?>
                                <span class="label label-danger">No</span>
                              <?php endif; ?>
                              </span>
                            </td>
                            <td valign="top">
                              <span data-toggle="tooltip" title="<?php echo $row->updated_by;?>"><?php echo $row->status; ?></span>
                              <?php if($row->status_created != "<small class='help-block'>0</small>") : ?>
                              <small class="help-block"><?php echo $row->status_created; ?></small>
                            <?php endif; ?>
                            </td>
                            <td valign="top"><span><?php echo $row->created; ?></span></td>
                            <td valign="top">
                                <?php if($row->status != '<span class="label label-success">No Defect</span>') : ?>
                                  <a href="javascript:void(0);" data-id="<?php echo $row->report_number;?>" data-href="<?php echo site_url('app/report/report_status/').$row->report_id;?>" class="btn btn-link btn-update" style="padding: 3px 6px;margin:0;" title="Update Status"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                  <a href="<?php echo site_url('app/report/pdf/').$this->hash->encrypt($row->report_id);?>" class="btn btn-link btn-print" style="padding: 3px 6px;margin:0;" title="Download PDF" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?> 
                </tbody>
              <?php else: ?>
                <div class="row bg-warning">
                  <div class="text-center">
                    <h2  style="margin:30px !important;color:#929292;"><strong>No Report Available</strong></h2>
                  </div>
                </div>
              <?php endif;?>
            </table>
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