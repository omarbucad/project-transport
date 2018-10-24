<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		body{
			padding:0;
		}
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
		td,p{
			word-wrap: break-word;
			word-break: break-all;
		}
		
	</style>
</head>
<body>
	<div class="row" style="width: 750px;background-color: #676767; color: #fff;padding-bottom: 5px;">
		<label style="margin-left: 20px;margin-top: 20px;"><strong>REPORT NO. <?php echo $report_number; ?> <span style="margin-left: 360px;"><?php echo $created; ?></span></strong></label>
	</div>

	<div class="row" style="margin: 30px,10px,20px,30px;">
		<table style="width: 100%;">
			<tr>
				<td style="width:350px;">
					<img src="<?php echo $company_logo;?>" style="height:30px;margin-bottom: 10px;margin-left: 15px; display: inline-block;margin-top: 10px;"><strong style="display: inline-block;"><span style="margin-top:-20px; font-size: 18px;margin-left: 8px;"><?php echo $store_name; ?></span></strong>

					<p style="width:100px; margin-left: 15px;margin-top:0;font-size: 12px;">Address: <?php echo $address; ?></p>

					<p style="width: 100px;margin-left: 15px;margin-bottom: 0;margin-top:10px;font-size: 12px;">Mobile Number: <?php echo $phone; ?></p>
					<p style="width: 100px;margin-left: 15px;margin-top: 0;font-size: 12px;">E-mail: <?php echo $email_address; ?></p>
				</td>
				<td style="width: 375px;">
					<div style="border: 1px solid #ddd;padding: 10px;margin-left:20px;margin-right: 10px;">
						<label>Vehicle Information</label>
						<table style="padding: 20px;">
							<tr>
								<td>Vehicle Type: </td>
								<td style="text-align: right;padding-left: 30px;"><?php echo $type; ?></td>
							</tr>
							<tr>
								<td>Registration No: </td>
								<td style="text-align: right;padding-left: 30px;"><?php echo $vehicle_registration_number; ?></td>
							</tr>
							<tr>
								<td>Trailer No: </td>
								<td style="text-align: right;padding-left: 30px;"><?php echo ($trailer_number == '')? "N/A" : $trailer_number; ?></td>
							</tr>
							<tr style="margin-top: 10px;">
								<td>Start Mileage: </td>
								<td style="text-align: right;padding-left: 30px;"><?php echo $start_mileage; ?></td>
							</tr>
							<tr>
								<td>End Mileage: </td>
								<td style="text-align: right;padding-left: 30px;"><?php echo ($end_mileage != '') ? $end_mileage : ""; ?></td>
							</tr>
						</table>
					</div>					
				</td>
			</tr>
		</table>
		<div class="row" style="margin-top: 20px; font-size: 12px;">
			<table style="width: 750px;margin-left: 10px; border-collapse: collapse;">
				<tr style="width: 723px;background-color:#676767;margin-right: 20px;font-size: 12px;">
					<td style="background-color:#676767;color: #fff; width: 5%; border: 1px solid #676767; padding: 5px 0 5px 0;">No.</td>
					<td style="background-color:#676767;color: #fff; width: 35%; border: 1px solid #676767; padding: 5px 0 5px 0;">Vehicle Parts</td>
					<td style="background-color:#676767;color: #fff; width: 10%; border: 1px solid #676767; padding: 5px 0 5px 0;">Status</td>
					<td style="background-color:#676767;color: #fff; width: 25%; border: 1px solid #676767; padding: 5px 0 5px 0;">Notes</td>
					<td style="background-color:#676767;color: #fff; width: 25%; border: 1px solid #676767; padding: 5px 0 5px 0;">Updates</td>
				</tr>
			<?php foreach($report_checklist as $key => $checklist) : ?>

				<tr>
					<td style="padding-left: 3px; background-color: <?php echo (($key+1) % 2 != 0) ? "#ddd": "#fff;";?>;"><?php echo ($key+1); ?></td>
					<td style="width: 100px; padding-right: 10px; background-color: <?php echo (($key+1) % 2 != 0) ? "#ddd": "#fff;";?>"><?php echo $checklist->item_name; ?></td>
					<td style="background-color: <?php echo (($key+1) % 2 != 0) ? "#ddd": "#fff;";?>">
						<?php if($checklist->checklist_ischeck == 1) : ?>
							<strong><?php echo "Defect"; ?></strong>
						<?php elseif($checklist->checklist_ischeck == 2) : ?>
							<strong><?php echo "Rechecked"; ?></strong>
						<?php elseif($checklist->checklist_ischeck == 3) : ?>
							<strong><?php echo "N/A"; ?></strong>
						<?php else : ?>
							<strong><?php echo "Good"; ?></strong>
						<?php endif; ?>
					</td>
					<td style="width: 100px; padding-right: 10px; background-color: <?php echo (($key+1) % 2 != 0) ? "#ddd": "#fff;";?>"><?php echo $checklist->checklist_value; ?></td>
					<td style="width: 100px; padding-right: 10px; background-color: <?php echo (($key+1) % 2 != 0) ? "#ddd": "#fff;";?>">
						<?php if(isset($checklist->updated_ischeck)) : ?>
                            <?php if($checklist->updated_ischeck == 1) : ?>
                                <strong><?php echo "Defect"; ?></strong>
                            <?php elseif($checklist->updated_ischeck == 2) : ?>
                                <strong><?php echo "Rechecked"; ?></strong>
                            <?php elseif($checklist->updated_ischeck == 3) : ?>
                                <strong><?php echo "N/A"; ?></strong>
                            <?php else : ?>
                                <strong><?php echo "Good"; ?></strong>
                            <?php endif; ?>

                            <?php if($checklist->updated_value != '') : ?>
                            	<br><small>Note: <?php echo $checklist->updated_value; ?></small><br>
                        	<?php endif; ?>

                            <small><?php echo $checklist->updated_timestamp; ?></small>
                        <?php else: ?>     
                            &nbsp;
                        <?php endif; ?>
					</td>
				</tr>

			<?php endforeach; ?>
			</table>
		</div>

		<div class="row" style="width: 724px;background-color: #676767; color: #fff;padding-bottom: 5px;padding-top: 5px; margin-top: 30px; border: 1px solid #676767; margin-left: 10px;">
			<label style="margin-left: 20px;font-size: 12px;">Assigned Checker</label>
		</div>
		<div class="row" style="width: 722px;padding-bottom: 5px;border: 1px solid #ddd; margin-left: 10px;">
			<table style="padding: 15px;">
				<tr>
					<td>Reported By: <?php echo $display_name;?></td>
					<td style="padding-left: 75px;">Role: <?php echo $role;?></td>
				</tr>
				<tr>
					<td colspan="2">Notes: <?php echo ($report_notes != '') ? $report_notes : ""; ?></td>
				</tr>
			</table>
			<div class="row" style="padding-left: 500px;padding-bottom: 30px;">				
				<?php if(!empty($report_statuses)) : ?>
				<img src="<?php echo $report_statuses[0]->signature;?>" style="height:50px;"><br>
				<?php echo $report_statuses[0]->display_name;?><br>
				<?php else: ?>
					<?php echo $display_name;?><br>
				<?php endif; ?>
				<span>__________________________</span><br>
				<span>Signature over printed name</span>
			</div>
		</div>
	</div>
</body>
</html>
