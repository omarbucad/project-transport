
<style type="text/css">

	#tyre_table td{
		text-align: center; 
		vertical-align: middle;
	}

</style>
<div class="row">
	<table width="100%" style="margin-bottom: 10px; text-align: left;">
		<tr>
			<th style="width: 100px;">Report #</th>
			<td><?php echo $report_number; ?></td>
			<th style="width: 100px;">Report By</th>
			<td><?php echo $display_name; ?></td>
		</tr>								
		<tr>
			<th style="width: 100px;">Start Mileage</th>
			<td><?php echo $start_mileage; ?></td>
			<th style="width: 100px;">End Mileage</th>
			<td><?php echo $end_mileage; ?></td>
		</tr>
		<tr>
			<th style="width: 100px;">Vehicle Registration No.</th>
			<td><?php echo $vehicle_registration_number; ?></td>
			<th style="width: 100px;">Trailer No.</th>
			<td><?php echo $trailer_number; ?></td>
		</tr>
		<tr>
			<th style="width: 100px;">Created</th>
			<td><?php echo $created; ?></td>
		</tr>
		<tr>
			<th style="width: 100px;">Report Notes</th>
			<td><?php echo $report_notes; ?></td>
		</tr>
	</table>

	<table width="100%" class="table table-bordered" border="1" style="border-collapse: collapse;">
		<thead>
			<tr>
				<th style="text-align: center;">Status</th>
				<th style="text-align: center;">Updated By</th>
				<th style="text-align: center;">Notes</th>
				<th style="text-align: center;">Created</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($report_statuses as $key => $value) : ?>
				<tr>
					<td style="text-align: center;width: 100px;"><?php echo $value->status; ?></td>
					<td style="padding-left: 5px;"><?php echo $value->display_name; ?></td>
					<td style="padding-left: 5px;"><?php echo $value->notes; ?></td>
					<td style="text-align: center; width: 135px;"><?php echo $value->created; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<table width="100%" class="table table-bordered" border="1" style="border-collapse: collapse;">
		<thead >
			<tr>
				<th>Item</th>
				<th style="width: 70px">Serviceable</th>
				<th>Remarks</th>
			</tr>
		</thead>
		<tbody>

			<?php foreach($report_checklist as $index => $value): ?>
			<tr>
				<td style="padding-left: 5px;"> <?php echo $value->item_name; ?></td>

				<?php if($value->checklist_ischeck == 1) : ?>
					<td style="text-align: center;"><?php echo "✖"; ?></td>							
				<?php else: ?>
					<td style="text-align: center;"><?php echo "✔"; ?></td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
