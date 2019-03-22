<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <style type="text/css">
		@media only screen and (max-width: 768px) {
		  /* For mobile phones: */
		  .container {
		    margin-left: 0;
		    width: 100% !important;
		  }
		}
	</style>
</head>
<body style="width: 100%">
	<table style="width: 100%;" align="center" cellpadding="0">
		<tr align="center" style="margin: 10px auto; padding: 20% auto;">
			<div class="container" style="background-image: url('https://www.trackerteer.com/vehiclechecklist/public/img/reset-pass.jpg'); width: 50%; height: auto;background-size: cover;">
				<div class="row" style="padding: 30px;">
					<table style="width: 100%;">
						<tr>
							<td style="text-align: center;"><img src="https://www.trackerteer.com/vehiclechecklist/public/img/vehicle-checklist.png" style="height: 65px; margin-bottom: -5px;"><br> <span style="font-size: 10px;">Vehicle Checklist</span></td>
						</tr>
						<tr>
							<td style="text-align: center;color:#252327"><h1 style="margin-top: 5px;margin-bottom: 0;"><strong>Reset your password</strong></h1></td>
						</tr>
						<tr>
							<td style="color:#252327;">
								<p>Hello,</p>
								<p>You told us you forgot your password. Let's reset your password so you can get back using the Vehicle Checklist App.</p>
							</td>
						</tr>
						<tr>
							<td style="text-align: center;">
								<a href="<?php echo $link; ?>" style="border-radius: 20px; width: 100%; height: auto; background: #2196f3;display:inline-block; line-height:40px; text-align:center; color:#fff; text-decoration: none; margin-top: 20px;font-family: sans-serif;">RESET PASSWORD</a>
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
							<td style=" padding-left: 20px; padding-right: 20px;">
								<p>By joining <strong>Vehicle Checklist</strong>, you've agreed to our Terms of Use and Privacy Policy.</p>
							</td>
						</tr>
						<tr>
							<td style=" padding-left: 20px; padding-right: 20px;">
								<p>This email has been sent to you as part of your Vehicle Checklist subscription.<br>
									Please do not reply to this email, as we are unable to respond from this email address. if you need help or would like to contact us, <a href="https://www.trackerteer.com" style="color:#2196f3;">click here</a>.
								</p>
							</td>
						</tr>
						<tr>
							<td style=" padding-left: 20px; padding-right: 20px;">
								<p>This message was mailed to <a href="mailto:<?php echo $customer_email;?>"><?php echo $customer_email; ?></a> by Vehicle Checklist. Use of the Vehicle Checklist service and website is subject to our Terms of Use and Privacy Policy.</p>
							</td>
						</tr>
						<tr><td style="margin-bottom: 30px;">&nbsp;</td></tr>
					</table>
					<p style="text-align: center; height: 25px; padding-top: 5px; font-size: 12px;">&copy; <?php echo date("Y"); ?> Trackerteer Web Developer Inc.</p>
				</div>
			</div>
		</tr>
	</table>
</body>
</html>