<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<div style="margin-top: 100px;"></div>
<div class="container">

	<p>Enterprise Plan</p>

	<p class="help-block"><?php echo $message; ?></p>

	<small><?php echo $fullname; ?> </small><br>
	<small><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></small><br>
	<small><a href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a></small><br>
	<small><?php echo $company; ?></small>
</div>
</body>
</html>