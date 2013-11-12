<div id="products_view">
	<div id="prod_select_field">
		<select>
			<?foreach ($prodByCategory as $key => $value) {
				print ('<option>'.$value['name'].'</option>');
			}?>	
		</select>
		
	</div>
	<div id="prodByCategory">
		<?
			foreach ($prodByCategory as $key => $value) {
				print ('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><div class="pr_img"><img src="http://goodcrm.ru/'.$value['img'].'"></div><div class="pr_name">'.$value['name'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].' '.$value['currency'].'</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Нравится</div></form></div>');
			}
		?>
	</div>
</div>

