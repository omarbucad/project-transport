<script type="text/javascript">
    $(document).on("click" , ".btn-add-more" , function(){

        var clone = $(".item-clone").last().clone().find("input:text").val("").end();

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
</script>

<div class="container margin-bottom">
    <div class="side-body padding-top">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/setup/checklist'); ?>">Checklist</a></li>
            <li class="active">Add Checklist Item</li>
        </ol>   
        <form class="form-horizontal" action="<?php echo site_url("app/setup/checklist/add") ?>" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
            <input type="hidden" name="checklist_name" value="<?php echo $post['checklist_name']; ?>">
            <input type="hidden" name="vehicle_type" value="<?php echo $post['vehicle_type']; ?>">
            <input type="hidden" name="checklist_for" value="<?php echo $post['checklist_for']; ?>">
            <input type="hidden" name="reminder" value="<?php echo ($post['reminder']) ? $post['reminder'] : ""; ?>">
            <input type="hidden" name="description" value="<?php echo $post['description']; ?>">

            <?php foreach($post['account'] as $row) : ?>
                <input type="hidden" name="account[]" value="<?php echo $row; ?>">
            <?php endforeach; ?>

            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title"><?php echo $post['checklist_name']; ?></div>
                        <span><?php echo $post['description']; ?></span>
                    </div>

                </div>
                <div class="card-body">

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
                            <div class="col-lg-6 no-margin-bottom">
                                <input type="file" name="file[]" class="item-file">
                                <p class="help-block">Image Only</p>
                            </div>
                            <div class="col-lg-6 text-right no-margin-bottom">
                                <a href="javascript:void(0);" class="btn btn-danger btn-sm btn-remove-item">Remove Item</a>
                            </div>
                        </div>
                      </div>
                    </div>

                    
                    <div class="text-right">
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