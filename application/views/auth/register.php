
	<link rel="stylesheet" href="/include/styles/welcome.css" type="text/css" media="screen"/>
<div id='register_form' class='hidd4en'>
	<form method='post' action=''>
		<div class='block logoplace'>
			<div class='logo'></div>
		</div>
		<div class="errors">
			<noscript><div class="err">Яваскрипт должен быть включён, чтобы зарегистрироваться.</div></noscript>
		</div>
		<div class='block'>
			<div class='caption'>Фамилия<span class='warning hidden surname'></span></div>
			<input type="text" name="surname" class='field'> 
		</div>
		<div class='block'>
			<div class='caption'>Имя<span class='warning hidden name'></span></div>
			<input type="text" name="name" class='field'>
		</div>
		<div class='block'>
			<div class='caption'>Отчество<span class='warning hidden second_name'></span></div>
			<input type="text" name="second_name" class='field'>
		</div>
		<div class='block'>
			<div class='caption'>Эл. почта<span class='warning hidden email'></span></div>
			<input type="text" id='email' name="email" class='email field'>
		</div>
		<div class='block'>
			<div class='caption'>Логин<span class='warning hidden login'></span></div>
			<input type="text" name="login" class='field'>
		</div>
		<div class='block'>
			<div class='caption'>Пароль
			<span class='warning hidden password'>пароль должен быть не короче 8 символов</span></div>
			<input type="password" name="password" class='field' placeholder='не менее 6 символов'>
		</div>
		<div class='block'>
			<div class='caption'>Подтверждение
			<span class='warning hidden password_confirm'>не совпадает с паролем</span></div>
			<input type="password" name="password_confirm" class='field'>
		</div>
		
		<div class='block'>
			<div class='caption'>Телефон<span class='warning hidden phone'></span></div>
			<input type="text" name="phone" class='field'>
		</div>
		<div class='block foot'>
			<input type="submit" class='submit' value='Зарегистрироваться' id='send'>
		</div>	
	</form>
</div>
<!-- <div class="login-links">
	<ul class="login-links">
		<li><a href="/megaplan/register/">Регистрация</a></li>
		<li><a href="/private/remember/">Забыли пароль?</a></li>
		<li><a href="/">Вернуться на сайт</a></li>
	</ul>
</div> -->