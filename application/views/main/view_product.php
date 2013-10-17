<div id="view_product">
	<div id="prod_info_wrapper">
		<div id="prod_img"><img src="/include/images/products/furniture/armchairs/armchair1.jpg"></div>
		<div id="prod_info">
			<div id="prod_name">Стул йопта!</div>
			<div id="prod_id" class="font16">ID 123456</div>
			<div id="prod_type"><b>Стул, окаянный</b></div>
			<div id="prod_brend">Бренд: <a href="/">Покровские стулья</a></div>
			<div>Дополнительные характеристики</div>
			<table>
				<tr>
					<td>Год выпуска</td>
					<td>1965</td>
				</tr>
				<tr>
					<td>Организация</td>
					<td>ООО "Покровские стулья"</td>
				</tr>
				<tr>
					<td>Название</td>
					<td>Стул йопта</td>
				</tr>
				<tr>
					<td>Тип</td>
					<td>стул</td>
				</tr>
				<tr>
					<td>Цвет</td>
					<td>разноцветный</td>
				</tr>
			</table>
			<div class="font16">Ваша цена:</div>
			<div id="prod_price">9 963 руб.</div>
			<div><input type="submit" value="Нравится"></div>
		</div>
	</div>	
	<div id="prod_underline">
		<div id="prod_review">Оставить отзыв</div>
	</div>
	<div id="producer_review">
		<div><b>От производителя:</b></div><br>
		<div>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</div>
	</div>
</div>
	<div id="another_interesting_prods">
		<div class="font16color" align="center">Вам будет интересно:</div>
		<div id="products_area">
		<?
			foreach ($int_prod as $key => $value) {
				print('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/index.php/main/insert_to_cart"><div class="pr_img"><img src="'.$value['img'].'"></div><div class="pr_name">'.$value['name'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].'р.</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Нравится</div></form></div>');
			}
		?>
		</div>
	</div>