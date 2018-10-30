
<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 

        var locations = jQuery.parseJSON('<?php echo $result->locations;?>');
        if(locations.length != 0){
             $.getScript("https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>")
          .done(function( script, textStatus ) {


                var map = new google.maps.Map(document.getElementById('_map'), {
                  zoom: 10,
                  center: new google.maps.LatLng(locations[0][1], locations[0][2]),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var infowindow = new google.maps.InfoWindow();


                var marker, i;

                for (i = 0; i < locations.length; i++) {  
                  marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    label : locations[i][0]
                  });

                  google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                      infowindow.setContent(locations[i][0]);
                      infowindow.open(map, marker);
                    }
                  })(marker, i));

                }
            }).fail(function( jqxhr, settings, exception ) {
               
            });
        }else{
            $.getScript("https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>")
          .done(function( script, textStatus ) {


                var map = new google.maps.Map(document.getElementById('_map'), {
                  zoom: 5,
                  center: new google.maps.LatLng(0, 0),
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                // var infowindow = new google.maps.InfoWindow();


                // var marker, i;

                // for (i = 0; i < locations.length; i++) {  
                //   marker = new google.maps.Marker({
                //     position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                //     map: map,
                //     label : locations[i][0]
                //   });

                //   google.maps.event.addListener(marker, 'click', (function(marker, i) {
                //     return function() {
                //       infowindow.setContent(locations[i][0]);
                //       infowindow.open(map, marker);
                //     }
                //   })(marker, i));

                // }
            }).fail(function( jqxhr, settings, exception ) {
               
            });
        }
       
	});

	$(document).on("click" , ".btn-delete" , function(){

        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });  

    $(document).ready(function() {
        $('.animated-thumbnail').lightGallery({
            thumbnail:true
        });
    });

</script>
<style type="text/css">
	dd {
		display: block;
	}
	.span-success{
		padding: 2px 6px;
	    background-color: #1abc9c;
	    border-radius: 100%;
	    color: white;
	}
	.span-danger{
		padding: 2px 6px;
	    background-color: #ff5555e3;
	    border-radius: 100%;
	    color: white;
	}
    .daterangepicker.dropdown-menu {
        z-index: 100001 !important;
    }
    .lg-backdrop{
        z-index: 999999!important;
    }
    .lg-outer{
        z-index: 999999!important;
    }
</style>
<div class="container margin-bottom">
    <div class="side-body padding-top">
    	<ol class="breadcrumb">
    		<li><a href="<?php echo site_url('app/report/daily'); ?>">Report</a></li>
    		<li class="active">View Report</li>
    	</ol>	
    	<h3>Report # <?php echo $result->report_number;?></h3>
    	<div class="grey-bg">
            <div class="row no-margin-bottom">
                <div class="col-xs-12 col-lg-8 no-margin-bottom">                
                </div> 
            	<div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                    <a href="<?php echo $this->config->site_url().$result->pdf_path.$result->pdf_file;?>" class="btn btn-success btn-print" target="_blank">Print Report</a>
					<a href="<?php echo site_url('app/report/daily');?>" class="btn btn-primary"  style="margin-right:25px;">Back to Reports List</a>
                </div>
                               
            </div>
        </div>
    		<!-- STORE SETTINGS -->
		<div class="card margin-bottom">
    		<div class="card-header">
    			<div class="card-title"> 
    				<div class="title">Report Details</div>
    			</div>
    		</div>
    		<div class="card-body">
    			<div class="row">
    				<div class="col-lg-6 no-margin-bottom">

    					<table class="table">
    					<tr>
    						<td><strong>Report By</strong></td>
    						<td><?php echo $result->display_name;?></td>
    					</tr>
    					<tr>
    						<td><strong>Created</strong></td>
    						<td><?php echo $result->created;?></td>
    					</tr>
    					<tr>
    						<td><strong>Vehicle</strong></td>
    						<td><?php echo $result->vehicle_registration_number;?></td>
    					</tr>
    					
                        <tr>
                            <td><strong>Vehicle Type</strong></td>
                            <td><?php echo $result->type; ?></td>
                        </tr>
                        <?php if($result->trailer_number != '') : ?>
                        <tr>
                            <td><strong>Trailer Number</strong></td>
                            <td><?php echo $result->trailer_number; ?></td>
                        </tr>
                        <?php endif; ?>
    					
    					<tr>
    						<td><strong>Start Mileage</strong></td>
    						<td><?php echo $result->start_mileage;?></td>
    					</tr>		
    					<tr>
    						<td><strong>End Mileage</strong></td>
    						<td><?php echo $result->end_mileage;?></td>
    					</tr>
    					<tr>
    						<td><strong>Report Notes</strong></td>
    						<td><?php echo $result->report_notes;?></td>
    					</tr>
	    				</table>

    				</div>
    				<div class="col-lg-6 no-margin-bottom">
    					<table class="table">
    						<tr>
    							<td><strong>Updated By</strong></td>
    							<td><strong>Notes</strong></td>
                                <td><strong>Status</strong></td>
    							<td><strong></strong></td>
    						</tr>
    						<?php foreach($result->report_statuses as $key => $row) :?>
	    					<tr>
	    						<td>
                                    <?php echo $row->display_name; ?>
                                </td>
	    						<td><?php echo $row->notes; ?></td>
	    						<td>
                                    <?php echo $row->status; ?><br>
                                    <small class="help-block"><?php echo $row->created; ?></small>
                                </td>	  
                                <td>
                                    <?php if(isset($row->signature)) : ?>
                                     <img src="<?php echo $row->signature; ?>" height="40px;">
                                    <?php endif; ?>
                                </td>  						
	    					</tr>
	    					<?php endforeach; ?>
	    				</table>
    				</div>
    			</div>
                <div class="row">
                    <div class="container-fluid">
                        <div id="_map" style="width:100%;height:300px;"></div>
                    </div>
                </div>
    		</div>
    	</div>

    	<!-- CHECKLIST REPORT -->
    	<div class="card margin-bottom">
    		<div class="card-header">
    			<div class="card-title">
    				<div class="title">Checklist</div>
    			</div>
    		</div>
    		<div class="card-body">	  
    			<div class="row no-margin-bottom">
    				<div class="col-xs-12">
    						<table class="table">
    						<tr style="background-color: #454545;color: #fff;">
    							<td width="25%"><strong>Item</strong></td>
    							<td width="25%"><strong>Initial</strong></td>
                                <td width="25%"><strong>Update</strong></td>
                                <td width="25%"><strong>Final Update</strong></td>
    						</tr>
    						<?php foreach($result->report_checklist as $key => $row) :?>
	    					<tr style="background-color: <?php echo (($key+1) % 2 != 0) ? "#ddd": "#fff;";?>;">
	    						<td><?php echo $row->item_name; ?></td>
	    						<td>
	    							<?php if($row->checklist_ischeck == 1) : ?>
                                        <?php echo "Defect"; ?>
                                    <?php elseif($row->checklist_ischeck == 2) : ?>
                                        <?php echo "Rechecked"; ?>
                                    <?php elseif($row->checklist_ischeck == 3) : ?>
                                        <?php echo "N/A"; ?>
                                    <?php else : ?>
                                        <?php echo "Good"; ?>
                                    <?php endif; ?>
                                    <small><?php echo ($row->timestamp != '') ?  " - ".$row->timestamp : "";?></small>
                                    <small class="help-block"><?php echo ($row->checklist_value != '') ?"Note: ".$row->checklist_value: ""; ?></small>

                                    <?php if(isset($row->fullpath)) : ?>
                                    <div class="animated-thumbnail">
                                        <?php foreach($row->fullpath as $k) : ?> 
                                        <a href="<?php echo $k; ?>">
                                         <img src="<?php echo $k; ?>" height='40px'>
                                        </a>
                                        <?php endforeach; ?>
                                    </div>                                    
                                    <?php endif;?>
	    						</td>
                                
                                <td>
                                    <?php if(isset($row->updated_ischeck)) : ?>
                                        <?php if($row->updated_ischeck == 1) : ?>
                                            <?php echo "Defect"; ?>
                                        <?php elseif($row->updated_ischeck == 2) : ?>
                                            <?php echo "Rechecked"; ?>
                                        <?php elseif($row->updated_ischeck == 3) : ?>
                                            <?php echo "N/A"; ?>
                                        <?php else : ?>
                                            <?php echo "Good"; ?>
                                        <?php endif; ?>
                                        <small><?php echo " - ".$row->updated_timestamp;?></small>
                                    <?php else: ?>     
                                        &nbsp;
                                    <?php endif; ?>
                                    <small class="help-block"><?php echo ($row->updated_value != '') ?"Note: ".$row->updated_value: ""; ?></small>
                                    <?php if(isset($row->updated_ischeck)) : ?>

                                        <?php if(isset($row->update_img_fullpath)) : ?>
                                        <div class="animated-thumbnail">
                                            <?php foreach($row->update_img_fullpath as $k) : ?> 
                                            <a href="<?php echo $k; ?>">
                                             <img src="<?php echo $k; ?>" height='40px'>
                                            </a>
                                            <?php endforeach; ?>
                                        </div>                                        
                                        <?php endif;?>
                                    <?php else: ?>     
                                        &nbsp;
                                    <?php endif; ?>
                                </td>	 	
                                <td>
                                    <?php if(isset($row->final_update_ischeck)) : ?>
                                        <?php if($row->final_update_ischeck == 1) : ?>
                                            <?php echo "Defect"; ?>
                                        <?php elseif($row->final_update_ischeck == 2) : ?>
                                            <?php echo "Rechecked"; ?>
                                        <?php elseif($row->final_update_ischeck == 3) : ?>
                                            <?php echo "N/A"; ?>
                                        <?php else : ?>
                                            <?php echo "Good"; ?>
                                        <?php endif; ?>
                                        <small><?php echo " - ".$row->final_update_timestamp;?></small>
                                    <?php else: ?>     
                                        &nbsp;
                                    <?php endif; ?>
                                    <small class="help-block"><?php echo ($row->final_update_value != '') ?"Note: ".$row->final_update_value: ""; ?></small>
                                    <?php if(isset($row->final_update_ischeck)) : ?>

                                        <?php if(isset($row->final_img_fullpath)) : ?>
                                        <div class="animated-thumbnail">
                                            <?php foreach($row->final_img_fullpath as $k) : ?> 
                                            <a href="<?php echo $k; ?>">
                                             <img src="<?php echo $k; ?>" height='40px'>
                                            </a>
                                            <?php endforeach; ?>
                                        </div>                                        
                                        <?php endif;?>
                                    <?php else: ?>     
                                        &nbsp;
                                    <?php endif; ?>
                                </td>			
	    					</tr>
	    					<?php endforeach; ?>
	    				</table>
	    					
	    				</table>
    				</div>
    			</div>
    		</div>
    	</div>


    	<div class="text-right margin-bottom">
    		<a href="<?php echo site_url('app/report/daily');?>" class="btn btn-primary">Back to Reports List</a>
    	</div>
    </div>
</div>

