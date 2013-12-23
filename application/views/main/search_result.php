<div id="news_content">
<?if (!empty($query))
		{
			$q = 0;
			if (strlen($query) < 3)
			{
				echo '<p>Слишком короткий запрос!</p>';

			} elseif (strlen($query) > 128)
			 {
				echo'<p>Слишком длинный запрос!</p>';
			} else {
				$q = $this->nice->get_smth($query);
			}
		
		if ($q != 0) {
			
			if (count($q)>0) {

				echo "<div>По запросу <b>".$query."</b> найдено совпадений: ".count($q)."</div>";
				foreach ($q as $key => $value) {
					// print('<div class="product_wrapper"><div class="pr_img"><img src="http://goodcrm.ru/'.$value['img'].'"></div><div class="pr_name">'.$value['product'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['cost'].'р.</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Нравится</div></form></div>');
					// print('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/index.php/main/insert_to_cart"><div class="pr_img"><img src="'.$value['img'].'"></div><div class="pr_name">'.$value['name'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].'р.</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Нравится</div></form></div>');
					print ('<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_img" srv_id="'.$value['id'].'"><img src="http://goodcrm.ru/'.$value['img'].'"></div></a><a href="/index.php/main/viewProduct/'.$value['id'].'"><div class="pr_name" srv_id="'.$value['id'].'">'.$value['product'].'</div></a><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['cost'].' руб.</div><input type="hidden" name="product_id" value="'.$value['id'].'"><div class="buy_button" srv_id="'.$value['id'].'">Купить</div><div class="like_button" srv_id="'.$value['id'].'" like="dislike"></div></form></div>');
				}
			}
			// echo "По вашему запросу найдено совпадений: ".count($q);
			else {echo "По вашему запросу ничего не найдено.";}
		}
	}
		else {
			echo "<p>Пустой запрос.</p>";
		}
?>
</div>
</div>