<!DOCTYPE html>
<html>
<head>
</head>
<body style="width: 100%">

	<div class="container" style="background-image: url('https://www.trackerteer.com/vehiclechecklist/public/img/reset-pass.jpg'); width: 40%; height: auto;background-size: cover; margin-left: 25%;">
		<div class="row" style="padding: 30px;">
			<table style="width: 100%;">
				<tr>
					<td style="text-align: center;"><img src="https://www.trackerteer.com/vehiclechecklist/public/img/vehicle-checklist.png" style="height: 65px; margin-bottom: -5px;"><br> <span style="font-size: 10px;">Vehicle Checklist</span></td>
				</tr>
				<tr>
					<td style="text-align: center;color:#252327"><h1 style="margin-top: 5px;margin-bottom: 0;"><strong>Email Verification</strong></h1></td>
				</tr>
				<tr>
					<td style="color:#252327;">
						<p>Hello,</p>
						<p>To make your <strong>Vehicle Checklist</strong> account more secure and to receive important messages and transaction history from Vehicle Checklist, please click the link below to verify your email address. A confirmation message will appear subsequently.</p>
					</td>
				</tr>
				<tr>
					<td style="text-align: center;color:#252327;">
						<p><strong><?php echo $code; ?></strong></p>
					</td>
				</tr>
				<tr>
					<td style="color:#252327;">
						<p>*This verification email is valid for 1 hour only.</p>
					</td>
				</tr>
				<tr><td style="margin-bottom: 20px;">&nbsp;</td></tr>
				<tr>
					<td style="color:#252327;">
						<p>If you did not ask to reset your password, then you can just ignore this email; your password will not change.</p>
					</td>
				</tr>
				<tr><td style="margin-bottom: 30px;">&nbsp;</td></tr>
				
			</table>
		</div>
		<div class="row" style="background-color: #252327; color:#929193;">
			<table style="width: 100%, padding-top:20px;">
				<tr>
					<td>
						<p>By joining <strong>Vehicle Checklist</strong>, you've agreed to our Terms of Use and Privacy Policy.</p>
					</td>
				</tr>
				<tr>
					<td>
						<p>This email has been sent to you as part of your Vehicle Checklist subscription.<br>
							Please do not reply to this email, as we are unable to respond from this email address. if you need help or would like to contact us, <a href="https://www.trackerteer.com" style="color:#2196f3;">click here</a>.
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<p>This message was mailed to <a href="mailto:<?php echo $email;?>"><?php echo $email; ?></a> by Vehicle Checklist. Use of the Vehicle Checklist service and website is subject to our Terms of Use and Privacy Policy.</p>
					</td>
				</tr>
				<tr><td style="margin-bottom: 30px;">&nbsp;</td></tr>
			</table>
			<p style="text-align: center; height: 25px; padding-top: 5px; font-size: 12px;">&copy; <?php echo date("Y"); ?> Trackerteer Web Developer Inc.</p>
		</div>
	</div>
</body>
</html>