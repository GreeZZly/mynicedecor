<div id="header">
	<div id="header_menu">
		<div class="header_menu_item">Как заказать?</div>
		<div class="header_menu_item">Чем платить?</div>
		<div class="header_menu_item">Кто доставит?</div>
		<div class="header_menu_item">Помогите!</div>
		<div class="header_menu_item">Войти?</div>
		<div class="header_menu_item active">Зарегистрируйтесь :)</div>
	</div>
	<div id="header_content">
		<div id="logo">
			<div id="logo_img"><a href="/"><img src="/include/images/logo_img.png"></a></div>
			<div id="logo_text"><img src="/include/images/logo_text.png"></div>
		</div>
		<div id="header_commercial">Здесь могла быть ваша реклама!</div>
		<div id="header_info">
			<div id="header_contacts">
				<div id="header_phone">+7(8352) 210-801</div>
				<div id="header_email">ilove@mynicedecor.com</div>
			</div>
			<div class="fb_icon"></div>
			<div class="vk_icon"></div>
			<div id="welcome_msg">Добро пожаловать, <span>username!</span></div>
		</div>
	</div>
	<div id="search">
		<div id="shop_menu_title">МАГАЗИН</div>
		<div id="search_str">
			<form name="search" method="post" action="/index.php/main/search">
			    <input type="search" name="query" placeholder="Поиск" id="search_field">
			    <button type="submit" id="search_btn">Найти</button> 
			</form>
		</div>
		<div id="basket"><a href="/index.php/main/view_cart">Вы купили (<span id="cart_total_items"><?=$count?></span>)</a></div>
	</div>

</div>