<script type="text/javascript">
    $(document).on("click" , ".plan-notif" , function(){
        var url = $(this).data("href");
        var c = confirm("Send Plan Notification Email?");

        if(c == true){
            $.ajax({
                url : url,
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
    });

    $(document).on('click' , '.view_invoice_pdf' , function(){

        var modal = $('#invoice_pdf_modal').modal("show");

        var id = $(this).data("id");

        var a = $("<a>" , {href : $(this).data("pdf") , text:$(this).data("pdf") });
        var object = '<object data="'+$(this).data("pdf") +'" , type="application/pdf" style="width:100%;height:800px;">'+a+'</object>';

        $('#_pdfViewer').html(object);  
    });
	
</script>
<div class="container-fluid">
    <div class="side-body padding-top">
        <div class="container">
        	<h1 class="text-left">Welcome, <?php echo $this->data['session_data']->display_name;?>!</h1>
        	<div class="card">
	    		<div class="card-body">
	    			<div class="panel panel-danger">
                        <div class="panel-heading">Subscriptions Expiring in 1 week <span class="pull-right"><strong><?php echo date("M d Y 00:00");?> - <?php echo date("M d Y 23:59",strtotime("+6 days"));?></strong></span></div>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                    <tr>
                        <th width="30%">Name</th>      
                        <th>Plan</th>
                        <th>Billing Type</th>
                        <th>Action</th>
                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($result) : ?>
                                        <?php foreach($result as $key => $row) : ?>
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-xs-6 col-lg-2 no-margin-bottom">
                                                            <img src="<?php echo site_url("thumbs/images/user/$row->image_path/80/80/$row->image_name"); ?>" class="img img-responsive thumbnail no-margin-bottom">
                                                        </div>
                                                        <div class="col-xs-6 col-lg-10 no-margin-bottom">
                                                            <a href="<?php echo site_url('admin/accounts/view/').$row->user_id; ?>"><?php echo $row->username; ?> ( <?php echo $row->display_name; ?> )</a><br>
                                                            <small class="help-block user-email"><?php echo $row->email_address; ?></small>
                                                            <small class="help-block"><?php echo $row->status; ?></small>
                                                        </div>
                                                    </div>
                                                </td>                                
                                                <td>
                                                    <span class="plan-type"><?php echo $row->title; ?></span> - 
                                                    <small class="text-danger"><strong> Left: <span class="timeleft"><?php echo $row->timeleft; ?></span></strong></small>
                                                    <div>                                        
                                                        <span class='label label-success' data-toggle="tooltip" title="Created"><?php echo $row->plan_created; ?></span>
                                                        <span class='label label-danger expiration' data-toggle="tooltip" title="Expiration"><?php echo $row->plan_expiration; ?></span>
                                                        <small class="help-block"><strong>Updated By: </strong> <?php echo $row->updated_name; ?></small>
                                                    </div>
                                                </td>
                                                <td><span><?php echo $row->billing_type; ?></span></td>
                                                <td>
                                                    <?php if($row->role != 'SUPER ADMIN') : ?>
                                                    <div class="btn-group" role="group" aria-label="...">
                                                        <a href="javascript:void(0);" data-href="<?php echo site_url('admin/accounts/send_plan_notice/').$row->user_id;?>" class="btn btn-link plan-notif" title="Plan Notification"><i class="fa fa-bell" aria-hidden="true"></i></a>
                                                        <?php if($row->invoice != "") : ?>
                                                         <a href="javascript:void(0);" data-pdf="<?php echo site_url().$row->invoice->invoice_pdf;?>" data-id="<?php echo $row->invoice->invoice_id; ?>" class="btn btn-link view_invoice_pdf" title="View Invoice"><i class="fa fa-search" aria-hidden="true"></i></a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr class="customer-row">
                                            <td colspan="4" class="text-center"><span>No Result</span></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>   
                            </table>
                        </div>
                    </div>
	    		</div>
	    	</div>
        </div>
        <div class="text-center">
			<p class="help-block">For help with setting up <?php echo $application_name; ?>, check out the <a href="#" class="link-style"> Getting Started Guide</a></p>
		</div>
    </div>
</div>
<!-- PDF VIEW -->
<div class="modal fade" id="invoice_pdf_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Invoice Information</h4>
            </div>
            <div class="modal-body" id="_pdfViewer">
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>