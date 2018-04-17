<script type="text/javascript">
    $(document).on("click" , ".btn-add-more" , function(){
        var clone = $(this).parent().prev().clone();
        clone.find("input").val("");
        $(this).parent().before(clone);
        $(this).parent().prev().find("input").trigger("click");
    });

    $(document).on("click" , ".btn-remove-item" , function(){
        var count = $(this).closest(".card-body").find(".form-group").length;

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
            <li class="active">Add Checklist Item</li>
        </ol>   
        <form class="form-horizontal" action="<?php echo site_url("app/setup/checklist/item/").$this->hash->encrypt($result->checklist_id); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
            <input type="hidden" name="checklist_id" value="<?php echo $result->checklist_id; ?>">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title"><?php echo $result->checklist_name; ?> <span class="btn-sm" style="top:-3px;position: relative;"> <?php echo $result->status; ?></span></div>
                        <span><?php echo $result->description; ?></span>
                    </div>

                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-8">
                                <label>Item Name</label>
                                <input type="text" name="item[name][]" class="form-control item-name" required="true">
                            </div>
                            
                            <div class="col-lg-4"> 
                                <label>Item Position</label>
                                <div class="input-group">
                                    <input type="text" name="item[position][]" class="form-control item-position" required="true">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default btn-remove-item" type="button" style="margin:0px;">x</button>
                                    </span>
                                </div>
                                    
                            </div>
                        </div>                        
                    </div>
                    
                    <div class="row text-right">
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-add-more">Add More</a>
                    </div>    
                </div>
            </div>



            <div class="text-right margin-bottom">
                <a href="<?php echo site_url('app/products');?>"  class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
    </div>
</div>