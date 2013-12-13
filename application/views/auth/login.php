<noscript>
	<div class='egor'>В вашем браузере отключен JavaScript.<br> Перенаправление...</div>
	<meta http-equiv="refresh" content="2; URL=http://www.google.ru/search?q=как+включить+javascript+в+браузере">
</noscript>
<body>
	<div id="login_form">
		<form method='post' <?if($this->uri->segment(2) == 'login') {echo "action='/auth/login/'>";} else {echo "action='/auth/order_login'";}?>
			<div id="head">
				<div id="logo"><img src="/include/images/logo_img.png"><img src="/include/images/logo_text.png"></div>
                            
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
				<div id="submit_line">
					<div id="submit_wrap"><input type="submit" id="send" value="Войти"></div>
					<label for="savecheck">
					<div id="checkbox">
						<input type="checkbox" name="remember" value='1' id="savecheck"><div id="rem">Запомнить</div>
					</div>
					</label>
				</div>
<!-- 				<div id="send"><span id="ololo">Войти</span>

				</div> -->
				<div class="links"><a href="/auth/forgot_password">Забыли пароль?</a></div>
				<div class="links"><a href="/auth/registr">Зарегистрироваться</a></div>
			</div>
		</form>
		
	</div>	
</body>
</html>
