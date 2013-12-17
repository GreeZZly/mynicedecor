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
		<label><input type="radio" name="order_auth" id="nonreg_radio">Я не хочу регистрироваться. Хочу купить сразу.</label>
		<div id="login_form" class="hidden loginform">
			<div id="php_valid_error"><?=(isset($message)?$message:"");?></div>
			<form method='post' action='/main/order_pay' id="nonreg_form"> <!-- main/order_pay -->
				<div class="nonreg_form_item" id="name"><p>*Мое имя</p> <p class="validation_error hidden"></p><input type="text" name="name"></div>			
				<div class="nonreg_form_item" id="surname"><p>*Моя фамилия</p> <p class="validation_error hidden"></p><input type="text" name="surname"></div>
				<div class="nonreg_form_item" id="email" ><p>*Мой e-mail</p> <p class="validation_error hidden"></p><input type="text" name="email"></div>
				<div class="nonreg_form_item" id="phone" ><p>*Мой телефон</p> <p class="validation_error hidden"></p><input type="text" name="phone"></div>
				<div class="nonreg_form_item button"><input type="button" value="Продолжить"></div>
			</form>
		</div>
	</div>
</div>
<?}
else {
	redirect('/main/order_pay', 'refresh');
}
?>