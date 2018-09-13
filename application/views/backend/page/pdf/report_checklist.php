<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		.invoice-box{
			max-width:100%;
			margin-top:5px;
		}
		td{
			padding:2px;
		}
		.tr_header{
			background-color:#e0e0e0;
			padding:5px;
			font-weight: bold;
		}
		td{
			word-wrap: break-word;
			word-break: break-all;
		}

	</style>
</head>
<body>

	<div class="invoice-box">
		<table style="width:100%;">

			<tr>
				<td style="width:20%"></td>
				<td style="width:30%"></td>
				<td style="width:20%"></td>
				<td style="width:30%"></td>
			</tr>

			<tr>
				<th>Report #</th>
				<td><?php echo $report_number; ?></td>
				<th>Created</th>
				<td><?php echo $created; ?></td>
			</tr>								
			<tr>
				<th>Start Mileage</th>
				<td><?php echo $start_mileage; ?></td>
				<th>End Mileage</th>
				<td><?php echo $end_mileage; ?></td>
			</tr>
			<tr>
				<th>Registration No.</th>
				<td><?php echo $vehicle_registration_number; ?></td>				
				<th>Vehicle Type</th>
				<td><?php echo $type; ?></td>	
			</tr>
			<?php if($trailer_number != '' && $type == 1) : ?>   
			<tr>
				<th>Trailer Number</th>
				<td><?php echo $trailer_number; ?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<th >Report By</th>
				<td><?php echo $display_name; ?></td>
			</tr>
			<tr>
				<th>Report Notes</th>
				<td><?php echo $report_notes; ?></td>
			</tr>
			<tr>
				<td colspan="4" style="padding:10px;"></td>
			</tr>
			<tr >
				<td colspan="2" class="tr_header"><?php echo $checklist_name; ?></td>
				<td class="tr_header">Status</td>
				<td class="tr_header">Remarks</td>
			</tr>

			<?php foreach($report_checklist as $key => $checklist) : ?>

				<tr style="margin-bottom: 30px;">
					<td colspan="2" style="width:300px;padding-right: 10px;"><?php echo ($key+1).". ".$checklist->item_name; ?></td>
					<td>
						<?php if($checklist->checklist_ischeck == 1) : ?>
							<?php echo "Defect"; ?>
						<?php elseif($checklist->checklist_ischeck == 2) : ?>
							<?php echo "Rechecked"; ?>
						<?php elseif($checklist->checklist_ischeck == 3) : ?>
							<?php echo "N/A"; ?>
						<?php else : ?>
							<?php echo "Good"; ?>
						<?php endif; ?>
					</td>
					<td><?php echo $checklist->checklist_value; ?></td>
				</tr>

			<?php endforeach; ?>
			
			<tr>
				<td colspan="4" class="tr_header" style="padding:10px;"></td>
			</tr>
			<tr>
				<td colspan="4" style="padding:5px;">
					<img src="<?php echo $report_statuses[0]->signature;?>" style="height:50px;"><br>
					<?php echo $report_statuses[0]->display_name;?><br>
					<span>__________________________</span><br>
					<span>Signature over printed name</span>
				</td>
			</tr>
		</table>

	</div>

</body>
</html>
