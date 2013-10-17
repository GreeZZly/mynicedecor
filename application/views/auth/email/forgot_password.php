<html>
<body>
	<h1><?php echo  $identity;?></h1>
	<p><?php echo  anchor('/auth/reset_password/'. $forgotten_password_code,'email_forgot_password_link');?></p>
</body>
</html>