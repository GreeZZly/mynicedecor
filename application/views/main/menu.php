	<div id="menu">
		<div id="categories">
		<?
			foreach ($category as $key => $value) {

				echo "<a href='/main/products/".$value['id']."'><div class='ctg_item'>".$value['name']."</div></a>";
			}
		?>
			
		</div>
		
	<!-- </div> -->
