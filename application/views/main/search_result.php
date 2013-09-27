<div id="news_content">
<?if (!empty($query))
		{
			if (strlen($query) < 3)
			{
				echo '<p>Слишком короткий запрос!</p>';
			} elseif (strlen($query) > 128)
			 {
				echo'<p>Слишком длинный запрос!</p>';
			} else {
				$q = $this->nice->get_smth($query);
			}

		
			if (mysql_affected_rows()>0) {

				echo "<div>По запросу <b>".$query."</b> найдено совпадений: ".count($q)."</div>";
				foreach ($q as $key => $value) {
					print('<div class="product_wrapper"><div class="pr_img"><img src="'.$value['img'].'"></div><div class="pr_name">'.$value['name'].'</div><div class="pr_type">'.$value['type'].'</div><div class="price">'.$value['price'].'р.</div><div class="buy_button">Нравится</div></div>');
				}
			}
			// echo "По вашему запросу найдено совпадений: ".count($q);
			else {echo "По вашему запросу ничего не найдено.";}
		}

		else {
			echo "<p>Пустой запрос.</p>";
		}
?>
</div>
</div>