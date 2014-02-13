<div id="content">
	<div class="block_title">Всем нравится:</div>
	<div id="products_area">
		<?
			foreach ($prod_records as $key => $value) {
				$disable = '';
				if($value['price'] == 0) {$value['price'] = 'Уточните цену'; $value['currency'] = ''; $disable = 'disabled';}
				print ('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_img" srv_id="'.$value['id'].'"><img src="http://goodcrm.ru/'.$value['img'].'"></div></a><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_name" srv_id="'.$value['id'].'">'.$value['name'].'</div></a><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].' '.$value['currency'].'</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button '.$disable.'" srv_id="'.$value['id'].'">В корзину</div><div class="like_button" srv_id="'.$value['id'].'" like="dislike"></div></form></div>');
			}
		?>
	</div>
</div>
