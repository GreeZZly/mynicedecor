

<div class = 'loginform'>
	<form method='post' action='welcome/ajax'>
		<input type="text" name="name" class='field'>
		<input type="password" name="password" class='field'>
		<input type='submit' class='caption' value='Войти'>
		<input type="button" class='submit' value='Зарегистрироваться' id='register'>
		<input type="button" class='submit' value='Забыли пароль?' id='restore'>
	</form>
<div id='overlay' class='hidden'></div>
<div id='reg_form' class='hidden'>
	<form method='post' action='index.php/welcome/ajax'>
		<div class='logoplace'>
			<div class='image'></div>
		</div>
		<div class='line'>
			<div class='caption'>Фамилия</div>
			<input type="text" name="surname" class='field'></input> 
		</div>
		<div class='line'>
			<div class='caption'>Имя</div>
			<input type="text" name="name" class='field'></input> 
		</div>
		<div class='line'>
			<div class='caption'>Эл. почта</div>
			<input type="text" name="email" class='field'></input> 
		</div>
		<div class='line'>
			<div class='caption'>Логин</div>
			<input type="text" name="login" class='field'></input> 
		</div>
		<div class='line'>
			<div class='caption'>Пароль</div>
			<input type="password" name="password" class='field'></input> 
		</div>
		<div class='line'>
			<div class='caption'>Подтверждение</div>
			<input type="password" name="confirm" class='field'></input> 
		</div>
		<div class='hidden'>
			<input type='hidden' name='key' value='<?=$random?>'></input> 
		</div>
		<div class='line'>
			<div class='caption'>Телефон</div>
			<input type="text" name="phone" class='field'></input>
		</div>
		<div class='line s'>
			<input type="submit" class='submit' value='Зарегистрироваться' id='send'></input> 
		</div>
	</form>
</div>
<div id='lostp_form' class='hidden'>
	<form method='post' action='/welcome/email'>
		<div class='line'>
		<div class='caption'>Ваша эл.почта:</div>
			<input type="text" name="email" class='field'></input> 
		</div>
		<div class='line s'>
			<input type="submit" class='submit' value='Отправить' id='send'></input> 
		</div>
	</form>
</div>
