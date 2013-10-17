<html>
<body>
<h1>Добро пожаловать </h1>
<p>Ваш email для авторизации на сайте:<?php echo $identity ?><p>

    <br>
<p>Для активации аккаунта перейдите по ссылке:</p>
<p><?php echo anchor('auth/activate/'. $id .'/'. $activation, "Активация аккаунта");?></p>
</body>
</html>