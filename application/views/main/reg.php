<div>Регистрация:</div>
<div id="reg_form">
	 <form action="/index.php/main/save_user" method="post">
	<p>
	    <label>Ваше имя:<br></label>
	    <input name="login" type="text" size="50" maxlength="20">
	</p>
	<p>
		<label>Ваш e-mail:<br></label>
	    <input name="email" type="text" size="50" maxlength="100">
	</p>
	<p>
		<label>Ваш телефон:<br></label>
		<input name="phone" type="text" size="50" maxlength="20">
	</p>
	<p>
	    <label>Ваш пароль:<br></label>
	    <input name="password" type="password" size="50" maxlength="20">
	</p>
	<p>
	    <label>Повторите Ваш пароль:<br></label>
	    <input name="repassword" type="password" size="50" maxlength="20">
	</p>

	<p>
	    <input type="submit" name="reg_submit" value="Зарегистрироваться">
	</p>
	</form>
</div>