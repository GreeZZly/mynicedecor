<div id="header">
<!-- 	<div id="header_menu">
		
		<div class="header_menu_item"><b>+7(8352)210-801</b></div>
		<div class="header_menu_item">Вход</div>
		<div class="header_menu_item">Зарегистрируйтесь :)</div>
	</div> -->
	<!-- <div id="header_content"> -->
		<div id="logo">
			<!-- <a href="/"><img src="/include/images/nice_logo.png"></a> -->
			<div id="logo_home" onclick="return location.href = '/'"></div>
	<div id="search">
	<div class="header_menu_item">ilove@mynicedecor.com</div>
		<div id="search_str">
			<form name="search" method="post" action="/index.php/main/search">
			    <input type="search" name="query" placeholder="Поиск" id="search_field">
			    <button type="submit" id="search_btn">Найти</button> 
			</form>
		</div>

	</div>

		</div>
		<div id="header_center">
			<div id="connect_block">
				<div class="icon_skype_header"></div>
				<div id="phone_header"><b>+7(8352)210-801</b></div>
			</div>
			<div id="basket">
					<div id="cart_shop">
					<a href="/main/view_cart"><span id="cart_total_items"><?=$count?></span></a>
					</div>
					<div id="like_cart">
					<a href="/main/view_like_cart"><span id="like_total_items"><?=$count?></span></a>
					</div>
				
				
			</div>
		</div>
		<div id="header_right">
			<div id="header_right_top">
				<?if($log_on == 0) { print('<a href="/auth/login"><div class="header_menu_item" id="log_in">Вход</div></a>');} else {print('<a href="/auth/logout"><div class="header_menu_item" id="log_in">Выход</div></a>');}?>
				<?if($log_on == 0) { print('<a href="/auth/registr"><div class="header_menu_item" id="registr">Зарегистрируйтесь</div></a>');} else {print('<div class="header_menu_item" id="registr"></div>');}?>
				<!-- <a href="/auth/login"><div class="header_menu_item" id="log_in"></div></a> -->
			</div>
			<div id="soc_icons">
				<a href="http://vk.com/club58613599" target="_blank"><div class="icon_header_vk"></div></a>
				<a href="https://twitter.com/Mynicedecor" target="_blank"><div class="icon_header_tweet"></div></a>
				<a href="https://www.facebook.com/mynicedecor" target="_blank"><div class="icon_header_fb"></div></a>
				<a href="" target="_blank"><div class="icon_header_odn"></div></a>
				<a href="http://www.tatianamaneeva.ru/" target="_blank"><div class="icon_header_lj"></div></a>
				<a href="https://www.youtube.com/channel/UCdXMmUrVor-0VU07J1XgnxA" target="_blank"><div class="icon_header_yt"></div></a>
			</div>
			<div id="welcome_msg">Добро пожаловать<?if($log_on == 0) {print('<span>!</span>');} else {print('<span>, <b>'.$username->name.'</b>!</span>');}?></div>
		</div>
	<!-- </div> -->

</div>