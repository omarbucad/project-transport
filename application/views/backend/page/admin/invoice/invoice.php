<script type="text/javascript">
    
    $(document).on("click" , ".resend-invoice" , function(){
        var url = $(this).data("href");
        var c = confirm("Resend Email Invoice?");

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
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">

        <div class="container">
            <h1>Invoices</h1>
        </div>
        <div class="grey-bg">
            <div class="container ">
                <div class="row no-margin-bottom">
                    <div class="col-xs-8 col-lg-6 no-margin-bottom">
                        <span></span>
                    </div>
                    <div class="col-xs-4 col-lg-6 text-right no-margin-bottom">
                        <span>&nbsp;</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card margin-bottom">
            <div class="container">
                <div class="card-body no-padding-left no-padding-right">
                    <form action="<?php echo site_url("admin/accounts/"); ?>" method="GET">
                        <div class="row">
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_name">Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get("name")?>" id="s_name" placeholder="Search by username or name">
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_roles">User Plan</label>
                                    <select class="form-control" id="s_roles" name="plan">
                                        <option value="">All Plans</option>
                                        <option value="4" <?php echo ($this->input->get("plan") == "4") ? "selected" : ""; ?>>TRIAL</option>
                                        <option value="1" <?php echo ($this->input->get("plan") == "1") ? "selected" : ""; ?>>BASIC</option>
                                        <option value="2" <?php echo ($this->input->get("plan") == "2") ? "selected" : ""; ?>>STANDARD</option>
                                        <option value="3" <?php echo ($this->input->get("plan") == "3") ? "selected" : ""; ?>>PREMIUM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-4 text-right">
                                <input type="submit" name="submit" value="Search" class="btn btn-primary btn-vertical-center btn-same-size">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_roles">Billing Type</label>
                                    <select class="form-control" id="s_roles" name="type">
                                        <option value="">-Select Billing Type-</option>
                                        <option value="ANNUAL" <?php echo ($this->input->get("type") == "ANNUAL") ? "selected" : ""; ?>>Annual</option>
                                        <option value="MONTHLY" <?php echo ($this->input->get("type") == "MONTHLY") ? "selected" : ""; ?>>Monthly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-lg-4">
                                <div class="form-group">
                                    <label for="s_roles">Status</label>
                                    <select class="form-control" id="s_roles" name="status">
                                        <option value="">- Select Status-</option>
                                        <option value="ACTIVE" <?php echo ($this->input->get("status") == "ACTIVE") ? "selected" : ""; ?>>Active</option>
                                        <option value="INACTIVE" <?php echo ($this->input->get("status") == "INACTIVE") ? "selected" : ""; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                    </form>
                </div>
            </div>
        </div>
        <div class="container ">

            <table class="table my-table">
                <thead>
                    <tr>
                        <th width="15%">Invoice #</th> 
                        <th width="20%">Name</th>      
                        <th width="20%">Plan</th>
                        <th>Billing Type</th>
                        <th>Amount</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result) : ?>
                        <?php foreach($result as $key => $row) : ?>
                            <tr>
                                <td>
                                    <?php echo $row->invoice_no; ?>
                                </td>
                                <td>
                                    <span><?php echo $row->username; ?> ( <?php echo $row->display_name; ?> )</span><br>
                                    <small class="help-block"><?php echo $row->email_address; ?></small>
                                </td>                                
                                <td>
                                    <div class="no-margin-bottom">
                                        <?php echo $row->title; ?> - 
                                        <small><?php echo $row->active; ?></small> - 
                                        <small class="text-danger help-block"><strong> Left: <span class="timeleft"><?php echo $row->timeleft; ?></span></strong></small>
                                        <span class='label label-success help-block' title="Created"><?php echo $row->plan_created; ?></span>
                                        <span class='label label-danger help-block' title="Expiration"><?php echo $row->plan_expiration; ?></span>
                                        <small class="help-block"><strong>Updated By: </strong> <?php echo $row->updated_by; ?></small>
                                    </div>                                    
                                </td>
                                <td><span><?php echo $row->billing_type; ?></span></td>
                                <td><span><?php echo $row->price; ?></span></td>
                                <td><span><?php echo $row->created; ?></span></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="...">
                                        <a href="javascript:void(0);" data-pdf="<?php echo site_url().$row->invoice_pdf;?>" data-id="<?php echo $row->invoice_id; ?>" class="btn btn-link view_invoice_pdf" title="View Invoice"><i class="fa fa-search" aria-hidden="true"></i></a>
                                        <a href="javascript:void(0);" data-href="<?php echo site_url('admin/invoice/resend/').$row->user_id;?>" class="btn btn-link resend-invoice" title="Resend Invoice"><i class="fa fa-share-square-o" aria-hidden="true"></i></a>
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
            <div class="customer-table-showing margin-bottom">
                <span class="pull-left">
                    <?php 
                        $x = 1;

                        if( $this->input->get("per_page") ){
                            $x = $this->input->get("per_page") + 1;
                        }

                    ?>
                    <small>Displaying <?php echo $x; ?> – <?php echo ($x-1) + count($result) ; ?> of <?php echo $config['total_rows']; ?></small>
                </span>
                <div class="pull-right">
                    <nav aria-label="Page navigation">
                      <?php echo $links; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="invoice_pdf_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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