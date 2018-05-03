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

    $(document).on("click" , ".checklist-modal" , function(){
       var modal = $("#addchecklist").modal("show");
    });


    $(document).on("click" , ".view-list" , function(){
           var url = $(this).data("href");
           var checklist_id = $(this).data("id");
           var name = $(this).data("name");
           $.ajax({
            url : url ,
            method : "GET" ,
            success : function(response){
                var json = jQuery.parseJSON(response);
                var modal = $("#checklistModal").modal("show");
                
                modal.find(".checklist-list").html(" ");
                modal.find(".modal-title").html(name);
                $.each(json.data , function(k , v){
                    var list = $("<li>");
                    list.append(v.item_name);
                    modal.find(".checklist-list").append(list);
                });

            }
        });
    });

    $(document).on("click" , ".add-checklist" , function(){
      $(this).closest(".modal").find("form").submit();
    });

    $(document).on("change" , '#checklist_for' , function(){
      if($(this).val() == "DRIVER"){
        $("#reminder_group").addClass("hide");
      }else{
        $("#reminder_group").removeClass("hide");
      }

      update_accounts_list();
    });

    $(document).ready(function(){
       update_accounts_list();
    });

    function update_accounts_list(){
      var cf = $('#checklist_for').val();

      var fs = $('#fs').find(".checkbox3");

          fs.each(function(k , v){

              if($(v).data("type") == cf){
                $(v).removeClass("hide");
                $(v).find("input").prop("checked" , true);
              }else{
                $(v).addClass("hide");
                $(v).find("input").prop("checked" , false);
              }
          });


    }
</script>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container" >
        	<h1>Checklist Information</h1>
        </div>
        <div class="grey-bg ">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-12 col-lg-8 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                        <a href="javascript:void(0);" class="btn btn-success checklist-modal">Add Checklist</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url('app/setup/checklist')?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-6 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_name">Checklist Name</label>
                                    <input type="text" name="checklist_name" class="form-control" id="s_name" placeholder="Checklist Name" value="<?php echo $this->input->get("checklist_name"); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-3 no-margin-bottom">
                                <div class="form-group">
                                    <label for="s_product_type">Status</label>
                                    <select class="form-control" name="status" id="s_product_type">
                                        <option value="">- Select Status -</option>
                                        <option value="1" <?php echo ($this->input->get("status") == "1") ? "selected" : "" ; ?>>Active</option>
                                        <option value="0" <?php echo ($this->input->get("status") == "0") ? "selected" : "" ; ?>>Inactive</option>
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
                        <th width="30%">Checklist Name</th>
                        <th width="20%">Description</th>
                        <th width="20%">Vehicle Type</th>
                        <th width="20%">Status</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach($result as $row) : ?>
                        <tr class="customer-row" style="cursor: default;">
                            <td>
                                <span><strong><a href="javascript:void(0);" class="link-style view-list" data-name="<?php echo $row->checklist_name; ?>" data-id="<?php echo $row->checklist_id; ?>" data-href="<?php echo site_url('app/setup/checklist/view/').$this->hash->encrypt($row->checklist_id);?>"><?php echo $row->checklist_name; ?></a></strong></span>
                            </td>
                            <td><span><?php echo $row->description; ?></span></td>
                            <td><span><?php echo $row->vehicle_type; ?></span></td>
                            <td><span><?php echo $row->status; ?></span></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="...">
                                    <a href="<?php echo site_url("app/setup/checklist/edit/").$this->hash->encrypt($row->checklist_id); ?>" class="btn btn-link" title="Edit Information"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" data-href="<?php echo site_url("app/setup/checklist/delete/").$this->hash->encrypt($row->checklist_id); ?>" class="btn btn-link btn-delete" title="Remove Checklist"><i class="fa fa-trash" aria-hidden="true"></i></a>
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
<div class="modal fade" id="addchecklist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add New Checklist</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo site_url('app/setup/checklist/add');?>" method="POST" id="checklist-form">
          <div class="form-group">
            <label for="checklist_name">Checklist Name</label>
            <input type="text" name="checklist_name" id="checklist_name" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="vehicle">Vehicle</label>
            <select name="vehicle" id="vehicle_type" class="form-control">
              <option value="TRUCK">Truck</option>
              <option value="TRAILER">Trailer</option>
              <option value="BOTH">Both Truck & Trailer</option>
            </select>
          </div>
          <div class="form-group">
            <label for="checklist_for">Checklist For</label>
            <select name="checklist_for" id="checklist_for" class="form-control">
              <option value="DRIVER">Driver</option>
              <option value="MECHANIC">Mechanic</option>
            </select>
          </div>
          <div class="form-group hide" id="reminder_group">
            <label for="reminder">Reminder</label>
            <select name="reminder" id="reminder" class="form-control">
              <option value="1 MONTH">1 Month</option>
              <option value="2 MONTHS">2 Months</option>
              <option value="3 MONTHS">3 Months</option>
              <option value="6 MONTHS">6 Months</option>
            </select>
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="textarea"></textarea>
          </div>
          <fieldset id="fs">
            <legend>Accounts</legend>
            <?php foreach($accounts_list as $key => $row) : ?>
              <div class="checkbox3 checkbox-check checkbox-light" data-type="<?php echo $row->role; ?>">
                <input type="checkbox" id="checkbox-fa-light-<?php echo $key; ?>" name="account[]" value="<?php echo $row->user_id; ?>" checked="">
                <label for="checkbox-fa-light-<?php echo $key; ?>">
                  <?php echo $row->display_name; ?>
                </label>
              </div>
            <?php endforeach; ?>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <a href="javascript:void(0);" class="btn btn-primary add-checklist">Confirm</a>
      </div>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="checklistModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
          <div class="row">
              <ol class="checklist-list">
                  
              </ol>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>