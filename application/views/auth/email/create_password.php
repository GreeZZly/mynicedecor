<html>
<body>
<h1>Добро пожаловать </h1>
<p>Ваш email для авторизации на сайте:<?php echo $identity ?><p>

    <br>
<p>Для активации аккаунта  создания пароля перейдите по ссылке:</p>
<p><?php echo anchor('/auth/reset_password/'. $activation, "Активация аккаунта");?></p>
</body>
</html>