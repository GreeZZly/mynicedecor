<noscript>
	<div class='egor'>В вашем браузере отключен JavaScript.<br> Перенаправление...</div>
	<meta http-equiv="refresh" content="2; URL=http://www.google.ru/search?q=как+включить+javascript+в+браузере">
</noscript>
<body>
	<div id="login_form">
		<form method='post' action='/auth/login/'>
			<div id="head">
				<div id="logo"><img src="/include/images/logo_crm.png"></div>
                            
                                    <?php 
                                    if($message)        
                                        echo"<div class='block err'>". $message."</div>";
                                      ?>
                               
				<div class="block">
					<div class="caption">Логин<span class='warning hidden identity'></span></div>
                                            <?=  form_input($identity)?>
						<!--<input class="field"  type="text" name="identity">-->
					
				</div>
				<div class="block">
					<div class="caption">Пароль<span class='warning hidden password'></span></div>
                                            <?= form_password($password)?>
                                        
						<!--<input class="field" type="password" name="password">-->
					
				</div>
				<div id="checkbox">
					<input type="checkbox" name="remember" value='1' id="savecheck"><label for="savecheck"><div id="rem">запомнить</div></label>
				</div>
<!-- 				<div id="send"><span id="ololo">Войти</span>

				</div> -->
				<div id="submit_wrap"><input type="submit" id="send" value="Войти"></div>
				<div class="links"><a href="/auth/forgot_password">Забыли пароль?</a></div>
				<div class="links"><a href="/auth/registr">Зарегистрироваться</a></div>
			</div>
		</form>
		<div id="foot">
			<div id="str1">Хотите <br>увеличить продажи?</div>
			<div id="str2">Мощнейшая технология для продаж сложных и дорогих проектов, разработанная на основе качественных...</div>
			<a href="http://goodpractice.ru/buy/pauk-sales.php"><div id="detail_button">Подробнее</div></a>
		</div>
	</div>	
</body>
</html>
