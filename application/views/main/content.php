<div id="content">
	<div id="content_title">Всем нравится:</div>
	<div id="products_area">
		<?
			foreach ($prod_records as $key => $value) {
				print('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/index.php/main/insert_to_cart"><div class="pr_img"><img src="'.$value['img'].'"></div><div class="pr_name">'.$value['name'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].'р.</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button">Нравится<button type="submit">нравится</button></div></form></div>');
			}
		?>
	</div>
</div>