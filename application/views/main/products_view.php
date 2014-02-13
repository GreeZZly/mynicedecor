<div id="products_view">
	<div id="prod_select_field">
		
		<?php
			foreach ($propParent as $key => $value) {
				print ('<div class="property_parent"><div class="property_parent_title">'.$value['name'].'</div><select id="pp_'.$value['id'].'"><option value="0" selected>Выберите свойство</option>');
				foreach ($propChild as $k => $v) {if ($v['id_property_name'] == $value['id']) print ('<option value="'.$v['id'].'">'.$v['name'].'</option>');}
				print('</select></div>');
			}
		?>
		
	</div>
	<div id="prodByCategory">
		<?php
			foreach ($prodByCategory as $key => $value) {
                $disable = '';
				if ($value['price'] == 0) {
					$value['price'] = 'Уточните цену'; 
					$value['currency'] = '';					
					$disable = 'disabled';
				}

				$position =strlen('/include/images/products/2/')-1;
				$point_position = strrpos($value['img'],'.');
				if(strpos($value['img'], '/2/')){
                                
                                    $img ="/include/images/products/2/small".substr($value['img'],$position,$point_position-$position).".jpg";
                                   // echo file_get_contents("http://goodcrm.ru.$img") ."<br>";
                                   $headers = @get_headers("http://goodcrm.ru.$img");
                                   
                                    if(strpos($headers[0],'200'))
                                        $value['img'] = $img;
                                }
				//$value['img'] =(strpos($value['img'], '/2/')?"/include/images/products/2/small".substr($value['img'],$position,$point_position-$position).".jpg":$value['img']; 
				print ('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_img" srv_id="'.$value['id'].'"><img src="http://goodcrm.ru'.$value['img'].'"></div></a><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_name" srv_id="'.$value['id'].'">'.$value['name'].'</div></a><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].' '.$value['currency'].'</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button '.$disable.'" srv_id="'.$value['id'].'">В корзину</div><div class="like_button" srv_id="'.$value['id'].'" like="dislike"></div></form></div>');
			}
		?>
	</div>
	
</div>
</div>

