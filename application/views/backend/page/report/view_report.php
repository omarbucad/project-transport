<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
		$('#_map').toggle();

		setTimeout(function(){
            var latlng = {lat: parseFloat('<?php echo $result->center_marker[0]["latitude"];?>'), lng: parseFloat('<?php echo $result->center_marker[0]["longitude"];?>')};

            var map = new google.maps.Map(document.getElementById('_map'), {
              zoom: 14,
              center: latlng
            });


            var markers = [
            <?php foreach($result->status_markers as $key => $value) : ?>
                {   position: new google.maps.LatLng(parseFloat('<?php echo $value["latitude"]; ?>'), parseFloat('<?php echo $value["longitude"]; ?>'))
                },
            <?php endforeach; ?>

            ];

            markers.forEach(function(marker) {
              var marker = new google.maps.Marker({
                position: marker.position,
                map: map
              });
            });
           
        },500);
	});

	$(document).on("click" , ".btn-delete" , function(){

        var c = confirm("Are you sure?");

        if(c == true){
            window.location.href = $(this).data("href");
        }
    });  

	$(document).on("click", ".view-map", function(){
        $('#_map').toggle();
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
</style>
<div class="container-fluid margin-bottom">
    <div class="side-body padding-top">
    	<ol class="breadcrumb">
    		<li><a href="<?php echo site_url('app/report/'); ?>">Report</a></li>
    		<li class="active">View Report</li>
    	</ol>	
    	<h3>Report # <?php echo $result->report_number;?></h3>
    	<div class="grey-bg">
            <div class="row no-margin-bottom">
                <div class="col-xs-12 col-lg-8 no-margin-bottom">                
                </div> 
            	<div class="col-xs-12 col-lg-4 text-right no-margin-bottom">
                    <a href="<?php echo site_url('app/report/create_pdf/').$this->hash->encrypt($result->report_id);?>" class="btn btn-success btn-print" target="_blank">Print Report</a>
					<a href="<?php echo site_url('app/report/');?>" class="btn btn-primary"  style="margin-right:25px;">Back to Reports List</a>
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
    			<div class="no-margin-bottom">
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
    						<td><strong>Vehicle Registration Number</strong></td>
    						<td><?php echo $result->vehicle_registration_number;?></td>
    					</tr>
    					
    					<tr>
    						<td><strong>Trailer Number</strong></td>

    						<td><?php echo (isset($result->trailer_number)) ? $result->trailer_number : 'NA'; ?></td>
	    				</tr>
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
	    				<table class="table" style="margin-top: 9px;">
	    					<tr><td><strong>Map Location</strong> <span class="pull-right"><a href="javascript:void(0);" class="view-map"><small>View Map</small> <i class="fa fa-angle-double-down"></i></a></span></td></tr>
	    					<tr>
	    						<td>
			    					<div id="_map" style="width:100%;height:300px;">
			    					</div>
	    						</td>
	    					</tr>
	    				</table>
	    				
    				</div>
    				<div class="col-lg-6 no-margin-bottom">
    					<table class="table">
    						<tr>
    							<td><strong>Status</strong></td>
    							<td><strong>Updated By</strong></td>
    							<td><strong>Notes</strong></td>
    							<td><strong>Updated</strong></td>
    						</tr>
    						<?php foreach($result->report_statuses as $key => $row) :?>
	    					<tr>
	    						<td><?php echo $row->status; ?></td>
	    						<td><?php echo $row->display_name; ?></td>
	    						<td><?php echo $row->notes; ?></td>
	    						<td><?php echo $row->created; ?></td>	    						
	    					</tr>
	    					<?php endforeach; ?>
	    				</table>
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
    						<tr>
    							<td><strong>Item</strong></td>
    							<td></td>
    							<td><strong>Remarks</strong></td>
    							<td><strong>Image(s)</strong></td>
    						</tr>
    						<?php foreach($result->report_checklist as $key => $row) :?>
	    					<tr>
	    						<td><?php echo $row->item_name; ?></td>
	    						<td>
	    							<span class="<?php echo ($row->checklist_ischeck == '1') ? 'span-danger' : 'span-success';?>" data-toggle="tooltip" data-placement="right" title="<?php echo ($row->checklist_ischeck == '1') ? 'Defective' : 'Serviceable'; ?>" >
	    								<strong><?php echo ($row->checklist_ischeck == '1') ? '✖' : '✔'; ?></strong>
	    							</span>
	    						</td>
	    						<td><?php echo $row->checklist_value; ?></td>
	    						<td>&nbsp;</td>	    						
	    					</tr>
	    					<?php endforeach; ?>
	    				</table>
	    					
	    				</table>
    				</div>
    			</div>
    		</div>
    	</div>


    	<div class="text-right margin-bottom">
    		<a href="<?php echo site_url('app/report/');?>" class="btn btn-primary">Back to Reports List</a>
    	</div>
    </div>
</div>