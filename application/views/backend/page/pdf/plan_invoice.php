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
	</style>
</head>
<body>

	<div class="invoice-box">
		<table style="width:97%;">

			<tr>
				<td colspan="4" style="text-align: center;">
					<img src="<?php echo site_url('public/img/favicon.png');?>">
					<h1 style="margin-bottom: 5px;">Transport Checklist</h1>
					<span>Trackerteer Web Development Corporation</span>
				</td>				
			</tr>	

			<tr>
				<td style="width:50%"></td>
				<td style="width:10%"></td>
				<td style="width:20%"></td>
				<td style="width:20%"></td>
			</tr>

			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" style="text-align: center;"> <h2>USER PLAN INVOICE</h2> </td>
			</tr>

			<tr>
				<td colspan="2">&nbsp;</td>
				<td><strong>NO:</strong></td>
				<td><?php echo $invoice_no; ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>DATE:</td>
				<td><?php echo date("d M Y"); ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>BILLING TYPE:</td>
				<td><?php echo $billing_type; ?></td>
			</tr>
			<tr>
				<td colspan="4">
					<strong>Company:</strong><br>
					<div style="padding:5px 0px;">
						<strong><?php echo $store_name; ?></strong><br>
						<span><?php echo $address; ?></span>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="4" >
					<strong>Contact Person:</strong><br>
					<div style="padding:5px 0px">
						<?php echo $display_name; ?>
					</div>
				</td>
			</tr>

			<tr>
				<td colspan="4" style="">
					<strong>Contact Number:</strong><br>
					<div style="padding:5px 0px;">
						<?php echo $phone; ?>
					</div>
				</td>
			</tr>

			<tr>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">PLAN</th>
				<th colspan="2" style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">VALIDITY</th>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">PRICE ($)</th>
			</tr>
			<tr>
				
			</tr>
			<tr>
				<td><?php echo $title; ?></td>
				<td colspan="2" style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;padding: 5px;text-align: center;"><?php echo $plan_created . " <br> " .$plan_expiration; ?></td>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;">&nbsp;</td>
			</tr>
			<tr>
				<td><?php echo $plan_created; ?></td>
				<td colspan="2" style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;padding: 5px;text-align: center;">&nbsp;</td>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;padding: 5px;text-align: center;"><?php //echo $items; ?></td>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;padding: 5px;text-align: center;">TOTAL AMOUNT</td>
				<td style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;border-right: 1px solid #e0e0e0;padding: 5px;text-align: right;"><strong><?php //echo $total_price; ?></strong></td>
			</tr>
			
			<tr>
				<td colspan="4"><br><br><br><br></td>
			</tr>
		</table>

	</div>

</body>
</html>
