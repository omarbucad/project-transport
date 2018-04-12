<script type="text/javascript">
    $(document).on("click" , ".btn-add-more" , function(){
        var clone = $(this).parent().prev().clone();
        clone.find("input").val("");
        $(this).parent().before(clone);
        $(this).parent().prev().find("input").trigger("click");
        $(this).parent().prev().find("button").attr("data-id",0);

    });

    $(document).on("click" , ".btn-remove-item" , function(){
        var count = $(this).closest(".card-body").find(".form-group").length;
        if($(this).data("id") != 0){
            var c = confirm("Delete this Checklist Item?");

            if(c == true){
                $.ajax({
                    url : $(this).data("href") ,
                    success : function(response){
                        var json = jQuery.parseJSON(response);
                        if(json.status){
                            $.notify(json.message , { className:  "success" , position : "top center"});
                        }else{
                            $.notify(json.message , { className:  "error" , position : "top center"});
                        }
                    }
                });
            }
        }


        if(count != 1){
            $(this).closest(".form-group").remove();
        }else{
            $(this).closest(".form-group").find("input").val("");
        }
    });
</script>

<div class="container margin-bottom">
    <div class="side-body padding-top">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/setup/checklist'); ?>">Checklist</a></li>
            <li class="active">Edit Checklist Information</li>
        </ol>   
        <form class="form-horizontal" action="<?php echo site_url("app/setup/checklist/edit/").$this->hash->encrypt($result->checklist_id); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
            <input type="hidden" name="checklist_id" value="<?php echo $result->checklist_id; ?>">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Checklist Details</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Checklist Name</label>
                        <input type="text" name="checklist_name" class="form-control" value="<?php echo $result->checklist_name; ?>" required="true">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option <?php echo ($result->status == "1") ? "selected" : ""; ?> value="1">Active</option>
                            <option <?php echo ($result->status == "0") ? "selected" : ""; ?> value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="textarea" value="<?php echo $result->description; ?>"><?php echo $result->description?></textarea>
                    </div>
                </div>

                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Checklist Items</div>
                    </div>
                </div>

                <div class="card-body">
                    <?php foreach($checklist_items as $item => $value) :?>
                    <div class="form-group">
                        <input type="hidden" name="items[id][]" value="<?php echo ($value['id'] == '') ? 0 : $value['id'];?>">
                        <div class="row">
                            <div class="col-lg-8 no-margin-bottom">
                                <label>Item Name</label>
                                <input type="text" name="items[name][]" class="form-control item-name" required="true" value="<?php echo $value['item_name'];?>">
                            </div>
                            <div class="col-lg-2 no-margin-bottom"> 
                                <label>Item Type</label>
                                <select name="items[item_type][]" class="form-control">
                                    <option <?php echo ($value['item_type'] == "TEXTBOX") ? "selected" : ""; ?> value="TEXTBOX">TEXTBOX</option>
                                    <option <?php echo ($value['item_type'] == "CHECKBOX") ? "selected" : ""; ?> value="CHECKBOX">CHECKBOX</option>
                                </select>
                            </div>
                            <div class="col-lg-2 no-margin-bottom"> 
                                <label>Item Position</label>
                                <div class="input-group">
                                    <input type="text" name="items[position][]" class="form-control item-position" required="true" value="<?php echo $value['item_position'];?>">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default btn-remove-item" type="button" style="margin:0px;" data-id="<?php echo $value['id']; ?>" data-href="<?php echo site_url('app/setup/checklist/item/delete/').$value['id'];?>">x</button>
                                    </span>
                                </div>
                                    
                            </div>
                        </div>                        
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="row text-right">
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-add-more">Add More</a>
                    </div>    
                </div>
            </div>

            <div class="text-right margin-bottom">
                <a href="<?php echo site_url('app/setup/checklist');?>"  class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
    </div>
</div>