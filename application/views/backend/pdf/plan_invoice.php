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
					<img src="<?php echo base_url("public/img/GB2.png"); ?>" style="height: 50px;"><br>
					<small><strong>Gravybaby Sdn. Bhd.</strong> (1147708-X)</small><br>
					<small>M1-05-3A Menara 8trium, Level 5, Jalan Cempaka SD12/5,</small><br>
					<small>Bandar Sri Damansara 52200 Kuala Lumpur, Malaysia.</small><br>
					<small>Tel: +6012 212 2276 | Email: contact@gravybaby.com</small><br>
					<small>GST no.: 001963732992</small><br>
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
				<td colspan="2" style="text-align: center;"> <h2>TAX INVOICE</h2> </td>
			</tr>

			<tr>
				<td colspan="2">&nbsp;</td>
				<td><strong>NO:</strong></td>
				<td><?php echo $invoice_no; ?></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>DATE:</td>
				<td><?php echo date("d.m.Y"); ?></td>
			</tr>
		
			<tr>
				<td colspan="2"></td>
				<td>P/O NO:</td>
				<td>SYSTEM</td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td>TERMS:</td>
				<td><?php echo $payment_method; ?></td>
			</tr>
			<tr>
				<td colspan="2">Attn:</td>
				<td>D/O NO:</td>
				<td><?php echo $invoice_no; ?></td>
			</tr>

			
			<tr>
				<td colspan="4" >
					<div style="padding:5px 0px">
						<strong><?php echo $display_name; ?></strong>
					</div>
				</td>
			</tr>

			<tr>
				<td colspan="4">
					Company:<br>
					<div style="padding:5px 0px;">
						<strong><?php echo $company_name; ?></strong><br>
						<?php echo $address; ?>
					</div>
				</td>
			</tr>

			<tr>
				<td colspan="4" style="">
					Hp no:<br>
					<div style="padding:5px 0px;">
						<strong><?php echo $phone_number; ?></strong>
					</div>
				</td>
			</tr>

			<tr>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">ITEMS</th>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">QTY</th>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-top:1px solid #e0e0e0">UNIT PRICE (RM)</th>
				<th style="text-align: center;padding:10px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-right:1px solid #e0e0e0;border-top:1px solid #e0e0e0">TOTAL (RM)</th>
			</tr>

			<?php foreach($items_list as $key => $i) : ?>
				<tr>
					<th style="padding: 5px;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;"><?php echo $key+1; ?> <?php echo $i->product_name;?></th>
					<th style="padding: 5px;text-align: center;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;"><?php echo $i->quantity;?></th>
					<th style="padding: 5px;text-align: right;border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;"><?php echo $i->product_price;?></th>
					<th style="padding: 5px;text-align: right;border-left:1px solid #e0e0e0;border-right:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;"><?php echo $i->total_price;?></th>
				</tr>
			<?php endforeach; ?>

			<tr>
				<td>&nbsp;</td>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;padding: 5px;text-align: center;"><?php echo $items; ?></td>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;">&nbsp;</td>
				<td style="border-left:1px solid #e0e0e0;border-bottom:1px solid #e0e0e0;border-right:1px solid #e0e0e0;padding: 5px;text-align: right;"><strong><?php echo $price; ?></strong></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;padding: 5px;text-align: center;">GST <?php echo $gst; ?></td>
				<td style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;border-right: 1px solid #e0e0e0;padding: 5px;text-align: right;"><strong><?php echo $gst_price; ?></strong></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;padding: 5px;text-align: center;">TOTAL AMOUNT</td>
				<td style="border-left: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;border-right: 1px solid #e0e0e0;padding: 5px;text-align: right;"><strong><?php echo $total_price; ?></strong></td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;">
					<p>Payment can be made by cheque payable to GRAVYBABY SDN BHD <br>or arrange for online transfer to PUBLIC BANK 3199111705</p>
				</td>
			</tr>
			
			<tr>
				<td colspan="4">
					<p>Prepared by,</p>
				</td>
			</tr>
			<tr>
				<td colspan="4"><br><br><br><br></td>
			</tr>
			<tr>
				<td style="border-top:1px solid #e0e0e0;">&nbsp;</td>
				<td>&nbsp;</td>
				<td style="text-align: center;">Amount</td>
				<td style="text-align: center;">Tax</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="text-align: center;">Tax Summary</td>
				<td style="text-align: center;">(RM)</td>
				<td style="text-align: center;">(RM)</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td style="text-align: center;"><?php echo $gst; ?> GST</td>
				<td style="text-align: center;"><?php echo $price; ?></td>
				<td style="text-align: center;"><?php echo $gst_price; ?></td>
			</tr>
		</table>

	</div>

</body>
</html>
