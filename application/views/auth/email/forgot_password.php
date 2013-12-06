<html>
<body>
<div id="register_form">
	<h1><?php echo  $identity;?></h1>
	<p><?php echo  anchor('/auth/reset_password/'. $forgotten_password_code,'email_forgot_password_link');?></p>
</div>
</body>
</html>