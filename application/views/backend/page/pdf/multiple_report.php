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
	<?php foreach($data as $d => $val) : ?>
	<div class="row" style="width: 750px;background-color: #676767; color: #fff;padding-bottom: 5px;">
		<label style="margin-left: 20px;margin-top: 20px;"><strong>REPORT NO. <?php echo $val->report_number; ?> <span style="margin-left: 360px;"><?php echo $val->created; ?></span></strong></label>
	</div>

	<div class="row" style="margin: 0,10px,10px,20px;">
		<table style="width: 100%;">
			<tr>
				<td style="width:350px;">
					<img src="<?php echo $company_logo;?>" style="height:30px;margin-bottom: 5px;margin-left: 15px; display: inline-block;"><strong style="display: inline-block;"><span style="margin-top:-10px; font-size: 18px;margin-left: 8px;"><?php echo $val->store_name; ?></span></strong>

					<p style="width:100px; margin-left: 15px;margin-top:0;font-size: 10px; margin-bottom: 10px;">Address: <?php echo $val->address; ?></p>

					<p style="width: 100px;margin-left: 15px;margin-bottom: 0;font-size: 10px; margin-top: 0;">Mobile Number: <?php echo $val->phone; ?></p>
					<p style="width: 100px;margin-left: 15px;margin-top: 0;font-size: 10px;">E-mail: <?php echo $val->email_address; ?></p>
				</td>
				<td style="width: 400px;">
					<div style="border: 1px solid #DCDBDB;padding: 10px;margin-left:20px;margin-right: 19px; font-size: 10px; background-color: #DCDBDB;">
						<label style="margin-left: 22px;">Vehicle Information</label>
						<table style="padding-left: 20px;padding-right: 20px;margin-top: 8px;">
							<tr>
								<td>Vehicle Type: </td>
								<td style="text-align: right;padding-left: 10px;"><?php echo $val->type; ?></td>
								<td style="padding-left: 50px;">Start Mileage: </td>
								<td style="text-align: right;padding-left: 10px;"><?php echo $val->start_mileage; ?></td>
							</tr>
							<tr>
								<td>Registration No: </td>
								<td style="text-align: right;padding-left: 10px;"><?php echo $val->vehicle_registration_number; ?></td>
								<td style="padding-left: 50px;">End Mileage: </td>
								<td style="text-align: right;padding-left: 10px;"><?php echo ($val->end_mileage != '') ? $val->end_mileage : ""; ?></td>
							</tr>
							<tr>
								<?php if(isset($val->trailer_number)) : ?>
								<td>Trailer No: </td>
								<td style="text-align: right;padding-left: 5px;"><?php echo ($val->trailer_number == '')? "N/A" : $val->trailer_number; ?></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<?php else: ?>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<?php endif;?>
							</tr>
						</table>
					</div>					
				</td>
			</tr>
		</table>
		<?php 
			$first_half = array_slice($val->report_checklist, 0, 27,true);
			$second_half = array_slice($val->report_checklist, 27,27,true);
		?>

		<?php if(count($val->report_checklist) > 28) : ?>
		<div class="row" style="margin-top: 10px; font-size: 9px; margin-left: -5px;">
			<table>
				<tr>
					<td>
						<table style="width: 350px; border-collapse: collapse;">
							<tr style="width: 350px;background-color:#676767;font-size: 9px;">
								<td style="background-color:#676767;color: #fff; width: 5%; border: 1px solid #676767; padding: 5px 0 5px 2px;">No.</td>
								<td style="background-color:#676767;color: #fff; width: 35%; border: 1px solid #676767; padding: 5px 0 5px 0;">Vehicle Parts</td>
								<td style="background-color:#676767;color: #fff; width: 30%; border: 1px solid #676767; padding: 5px 0 5px 0;">Status</td>
								<td style="background-color:#676767;color: #fff; width: 25%; border: 1px solid #676767; padding: 5px 0 5px 0;">Updates</td>
							</tr>
						<?php foreach($first_half as $key => $checklist) : ?>

							<tr>
								<td style="text-align: center;padding-left: 3px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>;"><?php echo ($key+1); ?></td>
								<td style="width: 100px; padding-right: 5px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>"><?php echo $checklist->item_name; ?></td>
								<td style="width: 100px; padding-right: 5px;background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>">
									<?php if($checklist->checklist_ischeck == 1) : ?>
										<strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php elseif($checklist->checklist_ischeck == 2) : ?>
										<strong  style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php elseif($checklist->checklist_ischeck == 3) : ?>
										<strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php else : ?>
										<strong style="color: #04B04F;"><?php echo "Good"; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php endif; ?>
									<?php echo ($checklist->checklist_value != '') ? "<br><span>Note: ".$checklist->checklist_value."</span>" : ""; ?>
								</td>
								
								<td style="width: 100px; padding-right: 5px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>">
									<?php if(isset($checklist->final_update_ischeck)) : ?>
										<?php if($checklist->final_update_ischeck == 1) : ?>
			                                <strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php elseif($checklist->final_update_ischeck == 2) : ?>
			                                <strong style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php elseif($checklist->final_update_ischeck == 3) : ?>
			                                <strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php else : ?>
			                                <strong style="color: #04B04F;"><?php echo "Good "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php endif; ?>

			                            <?php if($checklist->final_update_value != '') : ?>
			                            	<br><span>Note: <?php echo $checklist->final_update_value; ?></span><br>
			                        	<?php endif; ?>
									<?php endif;?>
									<?php if(isset($checklist->updated_ischeck)) : ?>
			                            <?php if($checklist->updated_ischeck == 1) : ?>
			                                <strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php elseif($checklist->updated_ischeck == 2) : ?>
			                                <strong style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php elseif($checklist->updated_ischeck == 3) : ?>
			                                <strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php else : ?>
			                                <strong style="color: #04B04F;"><?php echo "Good "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php endif; ?>

			                            <?php if($checklist->updated_value != '') : ?>
			                            	<br><span>Note: <?php echo $checklist->updated_value; ?></span><br>
			                        	<?php endif; ?>			                           
			                        <?php else: ?>     
			                            &nbsp;
			                        <?php endif; ?>
								</td>
							</tr>

						<?php endforeach; ?>
						</table>
					</td>
					<td>
						<table style="width: 345px;margin-left: 10px; border-collapse: collapse;">
							<tr style="width: 345px;background-color:#676767;font-size: 9px;">
								<td style="background-color:#676767;color: #fff; width: 5%; border: 1px solid #676767; padding: 5px 0 5px 2px;">No.</td>
								<td style="background-color:#676767;color: #fff; width: 35%; border: 1px solid #676767; padding: 5px 0 5px 0;">Vehicle Parts</td>
								<td style="background-color:#676767;color: #fff; width: 30%; border: 1px solid #676767; padding: 5px 0 5px 0;">Status</td>
								<td style="background-color:#676767;color: #fff; width: 25%; border: 1px solid #676767; padding: 5px 0 5px 0;">Updates</td>
							</tr>
						<?php foreach($second_half as $key => $checklist) : ?>

							<tr>
								<td style="text-align: center; padding-left: 3px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>;"><?php echo ($key+1); ?></td>
								<td style="width: 100px; padding-right: 5px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>"><?php echo $checklist->item_name; ?></td>
								<td style="width: 100px; padding-right: 5px;background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>">
									<?php if($checklist->checklist_ischeck == 1) : ?>
										<strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php elseif($checklist->checklist_ischeck == 2) : ?>
										<strong  style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php elseif($checklist->checklist_ischeck == 3) : ?>
										<strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php else : ?>
										<strong style="color: #04B04F;"><?php echo "Good"; ?></strong> <?php echo $checklist->timestamp; ?>
									<?php endif; ?>
									<?php echo ($checklist->checklist_value != '') ? "<br><span>Note: ".$checklist->checklist_value."</span>" : ""; ?>
								</td>
								<td style="width: 100px; padding-right: 5px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>">
									<?php if(isset($checklist->final_update_ischeck)) : ?>
										<?php if($checklist->final_update_ischeck == 1) : ?>
			                                <strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php elseif($checklist->final_update_ischeck == 2) : ?>
			                                <strong style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php elseif($checklist->final_update_ischeck == 3) : ?>
			                                <strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php else : ?>
			                                <strong style="color: #04B04F;"><?php echo "Good "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
			                            <?php endif; ?>

			                            <?php if($checklist->final_update_value != '') : ?>
			                            	<br><span>Note: <?php echo $checklist->final_update_value; ?></span><br>
			                        	<?php endif; ?>
									<?php endif;?>
									<?php if(isset($checklist->updated_ischeck)) : ?>
			                           <?php if($checklist->updated_ischeck == 1) : ?>
			                                <strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php elseif($checklist->updated_ischeck == 2) : ?>
			                                <strong style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php elseif($checklist->updated_ischeck == 3) : ?>
			                                <strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php else : ?>
			                                <strong style="color: #04B04F;"><?php echo "Good "; ?></strong> <?php echo $checklist->updated_timestamp;?>
			                            <?php endif; ?>
			                            <?php if($checklist->updated_value != '') : ?>
			                            	<br><span>Note: <?php echo $checklist->updated_value; ?></span><br>
			                        	<?php endif; ?>
			                        <?php else: ?>     
			                            &nbsp;
			                        <?php endif; ?>
								</td>
							</tr>

						<?php endforeach; ?>
						</table>
					</td>
				</tr>
			</table>			
		</div>
		<?php else: ?>
		<div class="row" style="margin-top: 10px; font-size: 9px;">
			<table style="width: 750px; border-collapse: collapse;">
				<tr style="width: 750px;background-color:#676767;font-size: 9px;">
					<td style="background-color:#676767;color: #fff; width: 5%; border: 1px solid #676767; padding: 5px 0 5px 0;">No.</td>
					<td style="background-color:#676767;color: #fff; width: 35%; border: 1px solid #676767; padding: 5px 0 5px 0;">Vehicle Parts</td>
					<td style="background-color:#676767;color: #fff; width: 10%; border: 1px solid #676767; padding: 5px 0 5px 0;">Status</td>
					<td style="background-color:#676767;color: #fff; width: 25%; border: 1px solid #676767; padding: 5px 0 5px 0;">Updates</td>
				</tr>
			<?php foreach($val->report_checklist as $key => $checklist) : ?>

				<tr style="width: 750px;">
					<td style="text-align: center; width: 8px;padding-left: 3px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>;"><?php echo ($key+1); ?></td>
					<td style="width: 95px; padding-right: 10px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>"><?php echo $checklist->item_name; ?></td>
					<td style="width: 208px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>">
						<?php if($checklist->checklist_ischeck == 1) : ?>
							<strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->timestamp; ?>
						<?php elseif($checklist->checklist_ischeck == 2) : ?>
							<strong  style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->timestamp; ?>
						<?php elseif($checklist->checklist_ischeck == 3) : ?>
							<strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->timestamp; ?>
						<?php else : ?>
							<strong style="color: #04B04F;"><?php echo "Good"; ?></strong> <?php echo $checklist->timestamp; ?>
						<?php endif; ?>

						<?php echo ($checklist->checklist_value != '') ? "<br><span>Note: ".$checklist->checklist_value."</span>" : ""; ?>
					</td>
					
					<td style="width: 208px; padding-right: 10px; background-color: <?php echo (($key+1) % 2 != 0) ? "#DCDBDB": "#fff;";?>">
						<?php if(isset($checklist->final_update_ischeck)) : ?>
							<?php if($checklist->final_update_ischeck == 1) : ?>
                                <strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
                            <?php elseif($checklist->final_update_ischeck == 2) : ?>
                                <strong style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
                            <?php elseif($checklist->final_update_ischeck == 3) : ?>
                                <strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
                            <?php else : ?>
                                <strong style="color: #04B04F;"><?php echo "Good "; ?></strong> <?php echo $checklist->final_update_timestamp;?><br>
                            <?php endif; ?>

                            <?php if($checklist->final_update_value != '') : ?>
                            	<br><span>Note: <?php echo $checklist->final_update_value; ?></span><br>
                        	<?php endif; ?>
						<?php endif;?>
						<?php if(isset($checklist->updated_ischeck)) : ?>
                            <?php if($checklist->updated_ischeck == 1) : ?>
                                <strong style="color: #ED2224;"><?php echo "Defect "; ?></strong> <?php echo $checklist->updated_timestamp;?>
                            <?php elseif($checklist->updated_ischeck == 2) : ?>
                                <strong style="color: #5D9BD3;"><?php echo "Recheck "; ?></strong> <?php echo $checklist->updated_timestamp;?>
                            <?php elseif($checklist->updated_ischeck == 3) : ?>
                                <strong style="color: #818181;"><?php echo "N/A "; ?></strong> <?php echo $checklist->updated_timestamp;?>
                            <?php else : ?>
                                <strong style="color: #04B04F;"><?php echo "Good "; ?></strong> <?php echo $checklist->updated_timestamp;?>
                            <?php endif; ?>

                            <?php if($checklist->updated_value != '') : ?>
                            	<br><span>Note: <?php echo $checklist->updated_value; ?></span><br>
                        	<?php endif; ?>
                        <?php else: ?>     
                            &nbsp;
                        <?php endif; ?>
					</td>
				</tr>

			<?php endforeach; ?>
			</table>
		</div>
		<?php endif; ?>

		<div class="row" style="width: 747px;background-color: #676767; color: #fff;padding-bottom: 5px;padding-top: 5px; margin-top: 30px; border: 1px solid #676767;">
			<label style="margin-left: 15px;font-size: 10px;">Assigned Checker</label>
		</div>
		<div class="row" style="width: 748px;border: 1px solid #DCDBDB; font-size: 9px;">
			<table style="padding: 10px;">
				<tr>
					<td>Reported By: <?php echo $val->display_name;?></td>
					<td style="padding-left: 130px;">Role: <?php echo $val->role;?></td>
				</tr>
				<tr>
					<td colspan="2" style="width: 350px; padding-top: 10px;">Notes: <?php echo ($val->report_notes != '') ? $val->report_notes : ""; ?></td>
					<td style="padding-top: -10px;padding-left: 220px;">
						<?php if(!empty($val->report_statuses)) : ?>
						<img src="<?php echo $val->report_statuses[0]->signature;?>" style="height:40px;"><br>
						<?php echo $val->report_statuses[0]->display_name;?><br>
						<?php else: ?>
							<?php echo $val->display_name;?><br>
						<?php endif; ?>
						<span>__________________________</span><br>
						<span>Signature over printed name</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<?php endforeach; ?>
</body>
</html>
