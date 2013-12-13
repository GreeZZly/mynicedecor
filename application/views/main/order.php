<? if ($log_on == 0){?>
<div id="order_form">
	<div class="order_radio">
		<label><input type="radio" checked="checked" name="order_auth">Я уже зарегистрирован, мне нужно только войти в систему</label>
		<div id="login_form" class="loginform">
			<form method='post' action='/auth/order_login'>
				<div class="loginform_item"><p>*Мой логин</p> <input type="text" name="identity"></div>			
				<div class="loginform_item"><p>*Мой пароль</p> <input type="password" name="password"></div>
				<div class="loginform_item"><input type="submit" value="Продолжить"></div>
			</form>
		</div>
	</div>
	<div class="order_radio">
		<label><input type="radio" name="order_auth">Я не хочу регистрироваться. Хочу купить сразу.</label>
		<div id="login_form" class="hidden loginform">
			<form method='post' action='/'>
				<div class="loginform_item"><p>*Мое имя</p> <input type="text" name="name"></div>			
				<div class="loginform_item"><p>*Моя фамилия</p> <input type="text" name="surname"></div>
				<div class="loginform_item"><p>*Мой e-mail</p> <input type="text" name="email"></div>
				<div class="loginform_item"><p>*Мой телефон</p> <input type="text" name="phone"></div>
				<div class="loginform_item"><input type="submit" value="Продолжить"></div>
			</form>
		</div>
	</div>
</div>
<?}
else {
	redirect('/main/order_pay', 'refresh');
}
?>