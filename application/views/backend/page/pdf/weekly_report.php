<!DOCTYPE>
<html>
<head>
	<title></title>
	<style type="text/css">
		table{
			border-collapse: collapse;
			border: 1px solid #ddd;
		}
		
		.invoice-box{
			max-width:100%;
			margin-top:5px;
		}
		td{
			padding:5px;
			font-size: 11px;

			
		}
		.td_data{
			padding:5px;
			font-size: 11px;
			border: 1px solid #ddd;	
		}
		th{
			/*background-color:#e0e0e0;*/
			padding:5px;
			font-weight: bold;
			border: 1px solid #ddd;			
			font-size: 10px;
		}
	</style>
</head>
<body>
	<div class="invoice-box">		
		<table style="width:97%;margin-left: 50px;">
			<tr>
				<td width="11%"></td>
				<td width="11%"></td>
				<td width="11%"></td>
				<td width="11%"></td>
				<td width="11%"></td>
				<td width="11%"></td>
				<td width="11%"></td>
				<td width="11%"></td>
				<td width="11%"></td>
			</tr>
			<tr>
				<td colspan="9" style="text-align: center;font-size:30; padding: 0!important;"><?php echo $data['header'][0]->store_name;?></td>
			</tr>
			<tr>
				<td colspan="9" style="text-align: center;font-size:14; padding: 0!important;"><?php echo $data['header'][0]->address;?></td>
			</tr>
			<tr>
				<td colspan="9" style="text-align: center;font-size:22; padding: 10px !important;"><?php echo $data['header'][0]->checklist_name;?></td>
			</tr>
			<tr>
				<td colspan="5" style="font-size:14; padding-top:20px; border-bottom: 1px solid #ddd;"><strong>Week Start Date:</strong> <?php echo $data['header'][0]->startrange;?></td>
				<td colspan="4" style="font-size:14; padding-top:20px; border-bottom: 1px solid #ddd;"><strong>Week End Date:</strong> <?php echo $data['header'][0]->endrange;?></td>
			</tr>
			<tr class="tr_header">
				<th colspan="2" width="11%">DRIVERS NAME</th>
				<th width="11%">VEHICLE REG</th>
				<th>VEHICLE TYPE</th>
				<th colspan="2">DATE</th>
				<th>START <br>MILEAGE</th>
				<th>FINISH <br>MILEAGE</th>
				<th>DRIVERS <br>SIGNATURE</th>
			</tr>
			<?php $headercount = 0;?>
			<?php foreach($data['header'] as $row) : ?>
				<?php $headercount++;?>
				<?php if($headercount <= 7) : ?>
				<tr>
					<td class="td_data" colspan="2"><span><?php echo $row->display_name; ?></span></td>
		            <td class="td_data"><span><?php echo $row->vehicle_registration_number; ?></span></td>
		            <td class="td_data"><span><?php echo $row->type; ?></span></td>
		            <td class="td_data" colspan="2"><span><?php echo $row->created;?></span></td>
		            <td class="td_data"><span><?php echo $row->start_mileage?></span></td>
		            <td class="td_data"><span><?php echo $row->end_mileage?></span></td>
		            <td class="td_data" style="padding-top: 0!important;"><span>
		            	 <?php if(isset($row->signature)){ ?>
		                  <img src="<?php echo $row->signature; ?>" class="img img-responsive thumbnail no-margin-bottom" style="height: 30px;">  
		                  <?php }?>                        
		                </span>
		            </td>
		        </tr>
		    	<?php endif;?>
			<?php endforeach; ?>
			<tr>
				<th colspan="2"><span>CHECKLIST ITEMS</span></th>
				<th>DAY 1</th>
				<th>DAY 2</th>
				<th>DAY 3</th>
				<th>DAY 4</th>
				<th>DAY 5</th>
				<th>DAY 6</th>
				<th>DAY 7</th>
			</tr>
			<tr>
				<td class="td_data" colspan="2">DATE</td>
				<?php for($x = 0; $x < 7; $x++) :?>
					<td class="td_data"><small><?php echo (isset($data['header'][$x]->status_created)) ? $data['header'][$x]->status_created : "&nbsp;"; ?></small></td>
				<?php endfor?>
			</tr>
			<?php $answers = 0;?>
			<?php foreach($data['checklist'] as $row => $value): ?>
			<?php $answers++;?>
			<?php if($answers < 7) : ?>
			<tr>
				<td class="td_data" colspan="2"><span><?php echo $value['item_name'];?></span></td>
				<?php if($value['day1'] != '') : ?>
				<td class="td_data"><span>
					<?php if(isset($data['header'][0]->status_created)) : ?>
						<?php if($value['day1'] == 1) : ?>
							<span>DEFECT</span>
						<?php elseif($value['day1'] == 2): ?>
							<span>RECHECKED</span>
						<?php else: ?>
							<span>GOOD</span>
						<?php endif; ?>
					<?php endif;?>
					</span>
				</td>
				<?php else : ?>
					<td class="td_data"><span>&nbsp;</span></td>
				<?php endif; ?>
				<?php if($value['day2'] != '') : ?>
				<td class="td_data"><span>
					<?php if(isset($data['header'][1]->status_created)) : ?>
						<?php if($value['day2'] == 1) : ?>
							<span>DEFECT</span>
						<?php elseif($value['day2'] == 2): ?>
							<span>RECHECKED</span>
						<?php else: ?>
							<span>GOOD</span>
						<?php endif; ?>				
					<?php endif;?>
					</span>
				</td>
				<?php else : ?>
					<td class="td_data"><span>&nbsp;</span></td>
				<?php endif; ?>
				<?php if($value['day3'] != '') : ?>
				<td class="td_data"><span>
					<?php if(isset($data['header'][2]->status_created)) : ?>
						<?php if($value['day3'] == 1) : ?>
							<span>DEFECT</span>
						<?php elseif($value['day3'] == 2): ?>
							<span>RECHECKED</span>
						<?php else: ?>
							<span>GOOD</span>
						<?php endif; ?>									
					<?php endif;?>
					</span>
				</td>
				<?php else : ?>
					<td class="td_data"><span>&nbsp;</span></td>
				<?php endif; ?>
				<?php if($value['day4'] != '') : ?>
				<td class="td_data"><span>
					<?php if(isset($data['header'][3]->status_created)) : ?>
						<?php if($value['day4'] == 1) : ?>
							<span>DEFECT</span>
						<?php elseif($value['day4'] == 2): ?>
							<span>RECHECKED</span>
						<?php else: ?>
							<span>GOOD</span>
						<?php endif; ?>
					<?php endif; ?>
					</span>
				</td>
				<?php else : ?>
					<td class="td_data"><span>&nbsp;</span></td>
				<?php endif; ?>
				<?php if($value['day5'] != '') : ?>
				<td class="td_data"><span>
					<?php if(isset($data['header'][4]->status_created)) : ?>
						<?php if($value['day5'] == 1) : ?>
							<span>DEFECT</span>
						<?php elseif($value['day5'] == 2): ?>
							<span>RECHECKED</span>
						<?php else: ?>
							<span>GOOD</span>
						<?php endif; ?>
					<?php endif; ?>
					</span>
				</td>
				<?php else : ?>
					<td class="td_data"><span>&nbsp;</span></td>
				<?php endif; ?>
				<?php if($value['day6'] != '') : ?>
				<td class="td_data"><span>
					<?php if(isset($data['header'][5]->status_created)) : ?>
						<?php if($value['day6'] == 1) : ?>
							<span>DEFECT</span>
						<?php elseif($value['day6'] == 2): ?>
							<span>RECHECKED</span>
						<?php else: ?>
							<span>GOOD</span>
						<?php endif; ?>
					<?php endif; ?>
					</span>
				</td>
				<?php else : ?>
					<td class="td_data"><span>&nbsp;</span></td>
				<?php endif; ?>
				<?php if($value['day7'] != '') : ?>
				<td class="td_data"><span>
					<?php if(isset($data['header'][6]->status_created)) : ?>
						<?php if($value['day7'] == 1) : ?>
							<span>DEFECT</span>
						<?php elseif($value['day7'] == 2): ?>
							<span>RECHECKED</span>
						<?php else: ?>
							<span>GOOD</span>
						<?php endif; ?>
					<?php endif; ?>
					</span>
				</td>
				<?php else : ?>
					<td class="td_data"><span>&nbsp;</span></td>
				<?php endif; ?>
            	
			</tr>

			<?php endif;?>
			<?php endforeach; ?>
			
		</table>

	</div>

</body>
</html>
