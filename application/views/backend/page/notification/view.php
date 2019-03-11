<script type="text/javascript">
    
</script>
<script type="text/javascript">
	
	$(document).on('click' , 'a.read-notif' , function(){
        var url = '<?php echo site_url("app/setup/read_notif"); ?>';
        var href = $(this).data("href");
        var id = $(this).data("id");
        $.ajax({
            url : url ,
            data: {id: id},
            method : "POST",
            success : function(response){
                window.location.href = href;
            }
        });
    });
	$(document).on("click" , ".set-read" , function(){
        var url = $(this).data("href");
        var c = confirm("Are you sure?");
        if(c == true){
        	$.ajax({
	            url : url ,
	            method : "POST",
	            success : function(){
	            }
	        });
        }        
    });
</script>

<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
        <div class="container" >
        	<h1>Notification</h1>
        </div>
        <div class="grey-bg ">
        	<div class="row"><span class='pull-right' style="padding-right: 30px;"><a href="<?php echo site_url('app/setup/mark_all_read'); ?>" class="btn btn-sm btn-info set-read" style="display: inline;">Mark All as Read</a></span></div>
        </div>

        <?php if(count($result) > 0) : ?>
	        <div class="container">
	        	<div class="panel-body">

				<table class="table">
					<tbody>
						<?php foreach($result->notifications as $key => $value) :?>
							<tr class="bg <?php echo ($value->isread == '1') ? 'bg-default' : 'bg-warning'; ?>">
								<td colspan="2">
									<a href="javascript:void(0);" data-href="<?php echo $value->url;?>" class="read-notif" data-id="<?php echo $value->id; ?>"><b><?php echo $value->name;?></b> <?php echo $value->description; ?> </a>
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>			
				</div>
	        </div>   
			<div class="row"><span class="pull-right" style="padding-right: 30px;"><a href="<?php echo site_url('app/setup/mark_all_read'); ?>" class="btn btn-sm btn-info set-read" style="display: inline;">Mark All as Read</a></span></div>
		<?php else: ?>
			<div class="row bg-warning" style="margin: 0;">
              <div class="text-center">
                <h2  style="color:#929292;"><strong>No available notification</strong></h2>
              </div>
            </div>
		<?php endif; ?>     
    </div>
</div>