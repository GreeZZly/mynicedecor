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
				<div id="basket_href">
					<a href="/index.php/main/view_cart">Вы купили (<span id="cart_total_items"><?=$count?></span>)</a>
				</div>
			</div>
		</div>
		<div id="header_right">
			<div id="header_right_top">
				<a href="/auth/registr"><div class="header_menu_item" id="registr">Зарегистрируйтесь :-)</div></a>
				<a href="/auth/login"><div class="header_menu_item" id="log_in">Вход</div></a>
			</div>
			<div id="soc_icons">
				<div class="icon_header_vk"></div>
				<div class="icon_header_tweet"></div>
				<div class="icon_header_fb"></div>
				<div class="icon_header_odn"></div>
				<div class="icon_header_lj"></div>
				<div class="icon_header_yt"></div>
			</div>
			<div id="welcome_msg">Добро пожаловать, <span>username!</span></div>
		</div>
	<!-- </div> -->

</div>