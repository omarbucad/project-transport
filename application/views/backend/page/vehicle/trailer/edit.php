<div class="container margin-bottom">
    <div class="side-body padding-top">
        <ol class="breadcrumb">
            <li><a href="<?php echo site_url('app/vehicle/trailer'); ?>">Trailer List</a></li>
            <li class="active">Edit Trailer</li>
        </ol>
        <form class="form-horizontal" action="<?php echo site_url("app/vehicle/trailer/edit/").$this->hash->encrypt($result->trailer_id); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $csrf_token_name; ?>" value="<?php echo $csrf_hash; ?>">
            <!-- STORE SETTINGS -->
            <div class="card margin-bottom">
                <div class="card-header">
                    <div class="card-title">
                        <div class="title">Edit Trailer</div>
                    </div>
                </div>
                <div class="card-body">
                    <dl class="dl-horizontal text-left">
                        <dt>Trailer Number</dt>
                        <dd>
                            <div class="form-group">
                                <input type="text" name="trailer_number" class="form-control" value="<?php echo $result->trailer_number;?>">
                            </div>
                        </dd>
                        <dt>Description</dt>
                        <dd>
                            <div class="form-group">
                                <textarea name="description" class="textarea"><?php echo $result->description;?></textarea>
                            </div>
                        </dd>
                        <dt>Status</dt>
                        <dd>
                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option <?php echo ($result->status == 1) ? "selected" : ""; ?> value="1">Active</option>
                                    <option <?php echo ($result->status == 0) ? "selected" : ""; ?> value="0">Inactive</option>
                                </select>
                            </div>
                        </dd>
                    </dl>
    
                </div>
            </div>

            <div class="text-right margin-bottom">
                <a href="<?php echo site_url('app/vehicle/trailer');?>" class="btn btn-default">Cancel</a>
                <input type="submit" name="submit" value="Save" class="btn btn-success">
            </div>
        </form>
    </div>
</div>