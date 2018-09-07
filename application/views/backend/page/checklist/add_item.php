<script type="text/javascript">
    $(document).on("click" , ".btn-add-more" , function(){

        var clone = $(".item-clone").last().clone().find("input:text").val("").end();
        clone.find("textarea").val("");

        $(".item-clone").last().after(clone);

        $("html, body").animate({ scrollTop: $(document).height() }, 1000);

        $('.item-clone').each(function(k , v){
            $(v).find('.panel-heading > span').html(k+1);
        });
       
    });

    $(document).on("click" , ".btn-remove-item" , function(){
        var count = $('.item-clone').length;

        if(count != 1){
            $(this).closest(".item-clone").remove();
        }else{
            $(this).closest(".item-clone").find("input:text").val("");
        }

        $('.item-clone').each(function(k , v){
            $(v).find('.panel-heading > span').html(k+1);
        });
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

<div class="container margin-bottom">
    <div class="side-body padding-top">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/setup/checklist'); ?>">Checklist</a></li>
            <li class="active">Add Checklist Item</li>
        </ol>   
        <form class="form-horizontal" action="<?php echo site_url("app/setup/checklist/add") ?>" method="POST" enctype="multipart/form-data">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Checklist Information</div>
                    </div>
                </div>
                <div class="card-body">
                   
                    <section class="sec_border_bottom">
                    <h3>Checklist Details</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p></p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <div class="form-group">
                                <label for="username">Checklist Name</label>
                                <input type="text" name="checklist_name" class="form-control" value="<?php echo set_value('checklist_name'); ?>" required="true">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="textarea" ><?php echo $post['description']; ?></textarea>
                            </div>
                            <div class="form-group">

                                <label for="type">Vehicle Type</label>
                                <select class="form-control" name="type" id="type">
                                    <option value="">- Select Vehicle Type -</option>
                                    <?php foreach($types as $key => $val) :?>
                                        <option value="<?php echo $val->vehicle_type_id;?>" <?php echo ($post['type'] == $val->vehicle_type_id) ? "selected" : "" ; ?> ><?php echo $val->type;?></option>
                                    <?php endforeach; ?>                                    
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="checklist_for">Checklist For</label>
                                <select name="checklist_for" id="checklist_for" class="form-control" value="<?php echo set_value('checklist_for'); ?>">
                                  <option value="DRIVER" class="<?php echo($post['checklist_for'] == 'DRIVER') ? 'selected' : '';?>">Driver</option>
                                  <option value="MECHANIC" class="<?php echo($post['checklist_for'] == 'MECHANIC') ? 'selected' : '';?>">Mechanic</option>
                                </select>
                            </div>
                            <div class="form-group hide" id="reminder_group">
                                <label for="reminder">Reminder</label>
                                <select name="reminder" id="reminder" class="form-control" value="<?php echo set_value('reminder');?>">
                                  <option value="1 MONTH" class="<?php echo ($post['reminder'] == '1 MONTH') ? 'selected' : '' ;?>">1 Month</option>
                                  <option value="2 MONTHS" class="<?php echo ($post['reminder'] == '2 MONTHS') ? 'selected' : '' ;?>">2 Months</option>
                                  <option value="3 MONTHS" class="<?php echo ($post['reminder'] == '3 MONTHS') ? 'selected' : '' ;?>">3 Months</option>
                                  <option value="6 MONTHS" class="<?php echo ($post['reminder'] == '6 MONTHS') ? 'selected' : '' ;?>">6 Months</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="sec_border_bottom">
                    <h3>Users</h3>
                    <div class="row">
                        <div class="col-xs-12 col-lg-4">
                            <p></p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <fieldset id="fs">
                            <?php foreach($accounts_list as $key => $row) : ?>
                              <div class="checkbox3 checkbox-check checkbox-light" data-type="<?php echo $row->role; ?>">
                                <input type="checkbox" id="checkbox-fa-light-<?php echo $key; ?>" name="account[]" value="<?php echo $row->user_id; ?>" checked="">
                                <label for="checkbox-fa-light-<?php echo $key; ?>">
                                  <?php echo $row->display_name; ?>
                                </label>
                              </div>
                            <?php endforeach; ?>
                            </fieldset>
                        </div>
                    </div>
                </section>
                    
                </div>
            </div>

            <div class="panel panel-primary item-clone">
                <div class="panel-heading">Item <span>1</span></div>
                <div class="panel-body">
                    <div style="padding:5px 15px;">
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" name="item[name][]" class="form-control item-name" required="true">
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" name="item[position][]" class="form-control item-name" required="true">
                        </div>
                       
                        <div class="form-group">
                            <label>Help Note</label>
                            <textarea class="form-control item-help-text" name="item[help][]"></textarea>
                        </div>

                    </div> 
                    <div class="row">
                        <div class="col-lg-12">                            
                            <p class="help-block">Item Image</p>
                            <input type="file" name="file[]" class="item-file">
                        </div>
                        <div class="col-lg-12 no-margin-bottom">
                            <p class="help-block">Help Note Image</p>
                            <input type="file" name="help_img[]" class="help-file">
                        </div>
                        <div class="col-lg-12 text-right no-margin-bottom">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-remove-item">Remove Item</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-add-more">Add More</a>
            </div> 

            <div class="text-right margin-bottom">
                <a href="<?php echo site_url('app/setup/checklist');?>"  class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
    </div>
</div>