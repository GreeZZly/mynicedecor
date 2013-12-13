<div id="order_form">
	<div class="order_radio">
		<label><input type="radio" checked="checked" name="order_auth">Я уже зарегистрирован, мне нужно только войти в систему</label>
		<div>
			<form method='post' action='/auth/order_login'>
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
	</div>
	<div class="order_radio">
		<label><input type="radio" name="order_auth">Я не хочу регистрироваться. Хочу купить сразу.</label>
	</div>
</div>