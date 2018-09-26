<script type="text/javascript">
    $(document).on("click" , ".btn-add-more" , function(){

        var clone = $(".item-clone").last().clone().find("input:text").val("").end();
        clone.find("input.item-id").val("0");
        clone.find("textarea").text("");
        if(clone.find("div.div-image").hasClass("hidden")){
            clone.find("div.div-upload").removeClass("hidden");
        }else{
            clone.find("div.div-image").addClass("hidden");
            clone.find("div.div-upload").removeClass("hidden");
        }
         if(clone.find("div.div-help-image").hasClass("hidden")){
            clone.find("div.div-help-upload").removeClass("hidden");
        }else{
            clone.find("div.div-help-image").addClass("hidden");
            clone.find("div.div-help-upload").removeClass("hidden");
        }

        $(".item-clone").last().after(clone);

        $("html, body").animate({ scrollTop: $(document).height() }, 1000);

        $('.item-clone').each(function(k , v){
            $(v).find('.panel-heading > span').html(k+1);
        });
       
    });

    $(document).on("click" , ".btn-remove-item" , function(){
        var count = $('.item-clone').length;
        var url = $(this).data("href");


        if(count != 1){

            var c = confirm("Are you sure");
            if(c == true){

            $(this).closest(".item-clone").remove();
            $.ajax({
                url : url ,
                method : "GET" ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    
                    if(json.status){
                        $.notify("Successfully deleted image" , { className:  "success" , position : "top center"});

                    }
                }
            });

        }
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
    


    function update_accounts_list(){
      var cf = $('#checklist_for').val();

      var fs = $('#fs').find(".checkbox3");

        fs.each(function(k , v){

            if($(v).data("type") == cf){
              $(v).removeClass("hide");
            }else{
              $(v).addClass("hide");
              $(v).find("input").prop("checked" , false);
            }
        });
    }

    
    $(document).on("click" , ".delete-image" , function(){
        var c = confirm("Are you sure");
        var btn = $(this);
        var url = $(this).data('href') + $(this).data('id');
        if(c == true){
            $.ajax({
                url : url ,
                method : "GET" ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    
                    if(json.status){
                        btn.closest("div.col-lg-6").find("div.div-image").addClass("hidden");
                        btn.closest("div.col-lg-6").find("div.div-upload").removeClass("hidden");
                        $.notify("Successfully deleted image" , { className:  "success" , position : "top center"});

                        setTimeout(function () { 
                          location.reload();
                        }, 2000);
                    }
                }
            });
        }
    });

    $(document).on("click" , ".delete-help-image" , function(){
        var c = confirm("Are you sure");
        var btn = $(this);
        var url = $(this).data('href') + $(this).data('id');
        if(c == true){
            $.ajax({
                url : url ,
                method : "GET" ,
                success : function(response){
                    var json = jQuery.parseJSON(response);
                    
                    if(json.status){
                        btn.closest("div.col-lg-6").find("div.div-helpimage").addClass("hidden");
                        btn.closest("div.col-lg-6").find("div.div-helpupload").removeClass("hidden");
                        $.notify("Successfully deleted image" , { className:  "success" , position : "top center"});
                        setTimeout(function () { 
                          location.reload();
                        }, 2000);
                    }
                }
            });
        }
    });

    $(document).on("click" , "input[type='submit']" , function(){
        
        $(".has-image").each(function(index, item){
            var id = $(item).closest("div.panel-body").find("input.item-id").val();
            var v = $(item).closest("div.col-lg-6").find("input[type='file']").val();
            if(v == ""){
                $(item).val(0);
            }else{
                $(item).val(id);
            }

        });

        $(".has-helpimage").each(function(index, item){
            var id = $(item).closest("div.panel-body").find("input.item-id").val();
            var v = $(item).closest("div.col-lg-6").find("input[type='file']").val();
            if(v == ""){
                $(item).val(0);
            }else{
                $(item).val(id);
            }

        });

    });

</script>

<div class="container margin-bottom">
    <div class="side-body padding-top">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/setup/checklist'); ?>">Checklist</a></li>
            <li class="active">Add Checklist Item</li>
        </ol>   
        <form class="form-horizontal" action="<?php echo site_url('app/setup/checklist/edit/').$this->hash->encrypt($result->checklist_id); ?>" method="POST" enctype="multipart/form-data" id="form-edit">
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
                                <input type="text" name="checklist_name" class="form-control" value="<?php echo $result->checklist_name; ?>" required="true"  >
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="textarea"><?php echo $result->description?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="type">Vehicle Type</label>
                              <select class="form-control" name="type" id="type">
                                <option value="">- Select Vehicle Type -</option>
                                <?php foreach($types as $key => $val) :?>
                                    <option value="<?php echo $val->vehicle_type_id;?>" <?php echo ($result->vehicle_type_id == $val->vehicle_type_id) ? "selected" : "" ; ?> ><?php echo $val->type;?></option>
                                <?php endforeach; ?>                                    
                              </select>
                            </div>
                           <!--  <div class="form-group">
                                <label for="checklist_for">Checklist For</label>
                                <select name="checklist_for" id="checklist_for" class="form-control" value="<?php// echo $result->checklist_for; ?>">
                                  <option value="DRIVER" <?php// echo($result->checklist_for == 'DRIVER') ? 'selected' : ''; ?> >Driver</option>
                                  <option value="MECHANIC" <?php// echo($result->checklist_for == 'MECHANIC') ? 'selected' : ''; ?> >Mechanic</option>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status">
                                    <option value="">- Select Status -</option>
                                    <option value="1" <?php echo ($result->status == 1) ? "selected" : "" ; ?> >Active</option>
                                    <option value="0" <?php echo ($result->status == 0) ? "selected" : "" ; ?>>Inactive</option>
                                    
                                </select>
                            </div>
                            <div class="form-group hide" id="reminder_group">
                                <label for="reminder">Reminder</label>
                                <select name="reminder" id="reminder" class="form-control" value="<?php echo $result->reminder_every;?>">
                                  <option value="1 MONTH" <?php echo ($result->reminder_every == '1 MONTH') ? 'selected' : '' ;?>>1 Month</option>
                                  <option value="2 MONTHS" <?php echo ($result->reminder_every == '2 MONTHS') ? 'selected' : '' ;?>>2 Months</option>
                                  <option value="3 MONTHS" <?php echo ($result->reminder_every == '3 MONTHS') ? 'selected' : '' ;?>>3 Months</option>
                                  <option value="6 MONTHS" <?php echo ($result->reminder_every == '6 MONTHS') ? 'selected' : '' ;?>>6 Months</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- <section class="sec_border_bottom">
                    <h3>Users</h3>
                    <div class="row user-div">
                        <div class="col-xs-12 col-lg-4">
                            <p></p>
                        </div>
                        <div class="col-xs-12 col-lg-4">
                            <fieldset id="fs">
                            <?php// foreach($accounts_list as $key => $row) : ?>
                              <div class="checkbox3 checkbox-check checkbox-light" data-type="<?php //echo $row->role; ?>">
                                <input type="checkbox" id="checkbox-fa-light-<?php //echo $key; ?>" name="account[]" <?php //echo (isset($user_checklist[$row->user_id])) ? "checked" : ""; ?> value="<?php //echo $row->user_id; ?>" >
                                <label for="checkbox-fa-light-<?php// echo $key; ?>">
                                  <?php //echo $row->display_name; ?>
                                </label>
                              </div>
                            <?php //endforeach; ?>
                            </fieldset>
                        </div>
                    </div>
                </section> -->
                    
                </div>
            </div>

            <?php foreach($checklist_items as $key => $row) : ?>
            <div class="panel panel-primary item-clone">
                <div class="panel-heading">Item <!-- <span>1</span> --></div>
                <div class="panel-body">
                    <div style="padding:5px 15px;">
                        <input type="hidden" name="item[id][]" value="<?php echo ($row['id']) ? $row['id'] : '0'; ?>" class="item-id">
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" name="item[name][]" class="form-control item-name" required="true" value="<?php echo $row['item_name'];?>">
                        </div>
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" name="item[position][]" class="form-control item-name" required="true" value="<?php echo $row['item_position']; ?>">
                        </div>
                       
                        <div class="form-group">
                            <label>Help Note</label>
                            <textarea class="form-control item-help-text" name="item[help_text][]" value="<?php echo $row['help_text']; ?>"><?php echo $row['help_text']; ?></textarea>
                        </div>

                    </div> 
                    <div class="row">
                        <div class="col-lg-6 no-margin-bottom">
                            <div class="div-image <?php echo (isset($row['image_path']) && $row['image_path'] != '') ? '':'hidden'; ?>">
                                <div class="item-image">
                                    <img src="<?php echo site_url("thumbs/images/checklist/").$row['image_path']."/150/150/".$row['image_name']; ?>" class="img img-responsive thumbnail no-margin-bottom">
                                </div>
                                <a href="javascript:void(0);" class="delete-image btn btn-xs btn-danger" data-href="<?php echo site_url('app/setup/checklist/item/delete_image/');?>" data-id="<?php echo $row['id']; ?>">Delete Image</a>
                            </div>
                            <div class="div-upload <?php echo (isset($row['image_path']) && $row['image_path'] != '') ? 'hidden':''; ?>">
                                <input type="hidden" class="has-image" name="item[has_image][]" value="">
                                <input type="file" name="file[]" class="item-file" value="">
                                <p class="help-block">Image Only</p>
                            </div>
                        </div>
                        <div class="col-lg-6 no-margin-bottom">
                            <div class="div-help-image <?php echo (isset($row['help_image_path']) && $row['help_image_path'] != '') ? '':'hidden'; ?>">
                                <div class="item-image">
                                    <img src="<?php echo site_url("thumbs/images/checklist/").$row['help_image_path']."/150/150/".$row['help_image_name']; ?>" class="img img-responsive thumbnail no-margin-bottom">
                                </div>
                                <a href="javascript:void(0);" class="delete-help-image btn btn-xs btn-danger" data-href="<?php echo site_url('app/setup/checklist/item/delete_helpimage/');?>" data-id="<?php echo $row['id']; ?>">Delete Help Image</a>
                            </div>
                            <div class="div-help-upload <?php echo (isset($row['help_image_path']) && $row['help_image_path'] != '') ? 'hidden':''; ?>">
                                <input type="hidden" class="has-helpimage" name="item[has_help_image][]" value="">
                                <input type="file" name="help_img[]" class="item-helpfile" value="">
                                <p class="help-block">Help Image</p>
                            </div>
                        </div>
                        <div class="col-lg-12 text-right no-margin-bottom">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-remove-item" data-href="<?php echo site_url('app/setup/checklist/item/delete/').$row['id'];?>">Remove Item</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right margin-bottom">
                <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-add-more">Add More</a>
            </div> 
            <?php endforeach; ?>

            <div class="text-right margin-bottom">
                <a href="<?php echo site_url('app/setup/checklist');?>"  class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
    </div>
</div>