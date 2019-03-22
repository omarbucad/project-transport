<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
</head>
<body style="width: 100%">
	<table style="width: 100%;" cellpadding="0">
		<tr style="margin: 10px auto; padding: 20% auto;">
			<div style="margin-top: 100px;"></div>
			<div class="container">
				<p>Hello,</p>
				<p class="help-block">You are all set to start your vehicle checklist</p>
				<p><strong>Your account information: </strong></p>
				<p>Email<br><?php echo $email; ?></p>
				<p>Plan<br>Free plan of 5 vehicle(s)</p>
				<p>Plan price<br>$5/month</p>
				<p>Your offer<br>Free Trial (ends on <?php echo $trial_ends; ?>)</p>
				
				<?php if($payment_type == 'CARD') : ?>
					<p>Payment<br><?php echo $payment_type; ?></p>
					<p>Card<br><?php echo $card; ?></p>
				<?php else if($payment_type == 'PAYPAL') : ?>
					<p>Payment<br><?php echo $payment_type; ?></p>
					<p>Paypal Billing ID<br><?php echo $paypal_billing_id; ?></p>
				<?php else: ?>
					
				<?php endif;?>
				<p>If you’d like you can upgrade your account to <strong>premium</strong> at any time.</p>
				<p>We're here to help you if you need it. <strong>Contact us</strong> and let us know your concerns.</p>
				<p>- The team at Vehicle Checklist</p>
				<hr>
				<p>By joining Vehicle Checklist, you’ve agreed to our Terms of Use and Privacy Policy. </p>
				<p>This email has been sent to you as part of your Vehicle Checklist subscription. Please do not reply to this email, as we are unable to respond from this email address. If you need help or would like to contact us, <a href="https://www.trackerteer.com">click here</a>.</p>
				<p>This message was mailed to <a href="mailto:<?php echo $email;?>"><?php echo $customer_email; ?></a> by Vehicle Checklist. Use of the Vehicle Checklist service and website is subject to our  Terms of Use and Privacy Policy.</p>
				<p>Vehicle Checklist</p>
			</div>
		</tr>
	</table>
</body>
</html>