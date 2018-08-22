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
					<img src="<?php echo site_url('public/img/vehicle0checklist.png');?>">
					<h1 style="margin-bottom: 5px;">Vehicle Checklist</h1>
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
				<td colspan="2" style="text-align: center;"> <h3>USER PLAN INVOICE</h3> </td>
			</tr>

			<tr>
				<td colspan="2">&nbsp;</td>
				<td><strong>NO:</strong></td>
				<td><?php echo $invoice_no; ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td><strong>DATE:</strong></td>
				<td><?php echo date("d M Y"); ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td><strong>BILLING TYPE:</strong></td>
				<td><?php echo $billing_type; ?></td>
			</tr>
			<tr>
				<td colspan="4">
					<strong>Company:</strong><br>
					<div style="padding:5px 0px;">
						<span><?php echo $store_name; ?></span><br>
						<small><?php echo $address; ?></small>
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
				<td colspan="4"><br><br><br><br></td>
			</tr>

			<tr>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">PLAN</th>
				<th colspan="3" style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0;border-right:1px solid #e0e0e0;">VALIDITY</th>
			</tr>
			<tr>
				
			</tr>
			<tr>
				<td style="border-left:1px solid #e0e0e0;text-align: center;"><?php echo $title; ?> Plan </td>
				<td colspan="3" style="border-left:1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;">Created: <?php echo $plan_created; ?></td>
			</tr>
			<tr>
				<td style="border-left:1px solid #e0e0e0;text-align: center;"><?php echo ($no_accounts == 0)? "Unlimited":$no_accounts; ?> Account(s) (Driver / Admin)</td>
				<td colspan="3" style="border-left:1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;">Expiration: <?php echo $plan_expiration;?></td>
			</tr>			
			<tr>
				<td style="border-left:1px solid #e0e0e0;text-align: center;"><?php echo ($no_vehicle == 0)? "Unlimited":$no_vehicle; ?> Vehicle(s)</td>
				<td colspan="3" style="border-left:1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;"></td>
			</tr>		
			<tr>
				<td style="border-left:1px solid #e0e0e0;text-align: center;"><?php echo ($no_reports == 0)? "Unlimited" : $no_reports; ?> Reports / Month</td>
				<td colspan="3" style="border-left:1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;">&nbsp;</td>
			</tr>
			<tr>
				<td style="border-left:1px solid #e0e0e0;text-align: center;"><?php echo $description; ?></td>
				<td colspan="3" style="border-left:1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;">&nbsp;</td>
			</tr>

			<tr>
				<td style="border-left:1px solid #e0e0e0;text-align: center;">
					<?php  if($title == "Basic") : ?>
						No Export
					<?php  elseif($title == "Standard") : ?>
						With Export and Reporting
					<?php  else : ?>
						&nbsp;
					<?php endif; ?>
				</td>
				<td colspan="3" style="border-left:1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;">&nbsp;</td>
			</tr>
			<tr>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;text-align: center;">&nbsp;</td>
				<td colspan="3" style="border-left:1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" style="border-bottom:1px solid #e0e0e0;border-left: 1px solid #e0e0e0;padding: 5px;text-align: center;"><strong>BILLED MONTH(S)</strong></td>
				<td style="border-bottom:1px solid #e0e0e0;border-left: 1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;">
					<?php if($title == "Trial") : ?>
						0			
					<?php else : ?>
						<?php echo ($title != "Trial" && $billing_type == "MONTHLY")? 1 : 12; ?>
					<?php endif; ?>					
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" style="border-bottom:1px solid #e0e0e0;border-left: 1px solid #e0e0e0;padding: 5px;text-align: center;"><strong>PRICE</strong></td>
				<td style="border-bottom:1px solid #e0e0e0;border-left: 1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;"><?php echo $plan_price;?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;padding: 5px;text-align: center;"><strong>TOTAL AMOUNT</strong></td>
				<td style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;padding: 5px;text-align: center;border-right:1px solid #e0e0e0;"><?php echo $price;?></td>
			</tr>
			
			<tr>
				<td colspan="4"><br><br><br><br></td>
			</tr>
			<tr>
				<td colspan="4"><br><br><br><br></td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;"><small>*** If you have any comments or questions, please don't hesitate to reach us at <a href="mailto:cherlaj@trackerteer.com">cherlaj@trackerteer.com</a>. ***</small></td>
			</tr>
		</table>

	</div>

</body>
</html>
