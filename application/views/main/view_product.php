<div id="view_product">
	<div id="prod_info_wrapper">
	<? #print($prod_data[0]['cid']);?>
		<div id="prod_img"><?print('<img src="http://goodcrm.ru/'.$prod_data[0]['img'].'"></img>');?></div>
		<div id="prod_info">
	
			<div id="prod_name"><?if ($prod_data[0]['type'] == 'Обои') { echo "Артикул:";}?> <?=$prod_data[0]['product']?></div>
			<!-- <div id="prod_id" class="font16">ID </div> -->
			<div id="prod_type"><b><?=$prod_data[0]['type']?></b></div>
			<!-- <div id="prod_brend">Бренд: <a href="/">Покровские стулья</a></div> -->
			<div id="prod_character">Характеристики:</div>
			<table>
			<? foreach ($prod_prop as $key => $value) {
				print('<tr><td>'.$value['property'].'</td><td>'.$value['value'].'</td></tr>');
			}?>
			</table>
			<div class="font16">Ваша цена:</div>
			<div id="prod_price"><?if ($prod_data[0]['cost'] == 0) {echo "Уточните цену";} else {echo "".$prod_data[0]['cost']." руб.";}?></div>
			<div><input type="submit" value="Купить"></div>
			<div class="like_button"></div>
		</div>
	</div>	
	<div id="prod_underline">
		<div id="prod_review">Оставить отзыв</div>
	</div>
	<div id="producer_review">
		<div><b>Описание:</b></div><br>
		<div><?=$prod_data[0]['description']?></div>
	</div>
</div>
	<div id="another_interesting_prods">
		<div class="font16color" align="center">Вам будет интересно:</div>
		<div id="products_area">
		<?
			foreach ($prodByCategory as $key => $value) {
				if ($value['id'] != $prod_data[0]['id']){
					// print('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/index.php/main/insert_to_cart"><div class="pr_img"><img src="'.$value['img'].'"></div><div class="pr_name">'.$value['name'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].'р.</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Нравится</div></form></div>');
					print ('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_img" srv_id="'.$value['id'].'"><img src="http://goodcrm.ru/'.$value['img'].'"></div></a><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_name" srv_id="'.$value['id'].'">'.$value['name'].'</div></a></div>');
				}
			}
		?>
		</div>
	</div>