<div id="content">
	<div class="block_title">Всем нравится:</div>
	<div id="products_area">
		<?
			foreach ($prod_records as $key => $value) {
				print('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/index.php/main/insert_to_cart"><div class="pr_img"><img src="http://goodcrm.ru/'.$value['img'].'"></div><div class="pr_name">'.$value['name'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].' '.$value['currency'].'</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Нравится</div></form></div>');
			}
		?>
	</div>
</div>