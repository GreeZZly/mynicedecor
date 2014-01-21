<div id="products_view">
	<div id="prod_select_field">
		
		<?
			foreach ($propParent as $key => $value) {
				print ('<div class="property_parent"><div class="property_parent_title">'.$value['name'].'</div><select id="pp_'.$value['id'].'"><option value="0" selected>Выберите свойство</option>');
				foreach ($propChild as $k => $v) {if ($v['id_property_name'] == $value['id']) print ('<option value="'.$v['id'].'">'.$v['name'].'</option>');}
				print('</select></div>');
			}
		?>
		
	</div>
	<div id="prodByCategory">
		<?
			foreach ($prodByCategory as $key => $value) {
				if ($value['price'] == 0) {
					$value['price'] = 'Уточните цену'; 
					$value['currency'] = '';					
				}

				$position =strlen('/include/images/products/2/')-1;
				$value['img'] =strpos($value['img'], '/2/')?substr_replace($value['img'], '/small',$position , 0):$value['img']; 
				print ('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_img" srv_id="'.$value['id'].'"><img src="http://goodcrm.ru'.$value['img'].'"></div></a><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_name" srv_id="'.$value['id'].'">'.$value['name'].'</div></a><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].' '.$value['currency'].'</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Купить</div><div class="like_button" srv_id="'.$value['id'].'" like="dislike"></div></form></div>');
			}
		?>
	</div>
	
</div>
</div>

