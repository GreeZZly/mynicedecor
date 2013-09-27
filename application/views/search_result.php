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
			
			if (affected_rows()>0) {
				echo "По запросу".$query."найдено совпадений: ".count($q);
				foreach ($q as $key => $value) {
					echo "<div><a href='/'>".$value['name']."</a></div>";
				}
			}
			// echo "По вашему запросу найдено совпадений: ".count($q);
			else {echo "По вашему запросу ничего не найдено.";}
		}

		
?>