	<div id="menu">
		<div id="categories">
		<a href="/main/design_service"><div class="ctg_item">Услуги дизайнера</div></a>
		<?
			foreach ($category as $key => $value) {
				if (isset($cat_id) && $cat_id==$value['id']) $add = " current='1'";
				else $add = "";
				echo "<a href='/main/products/".$value['id']."'><div class='ctg_item'".$add." cat_id='".$value['id']."'>".$value['name']."</div></a>";
			}
		?>
			
		</div>
		
	<!-- </div> -->
