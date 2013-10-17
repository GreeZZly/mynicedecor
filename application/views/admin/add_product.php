<div id="admin_content">
<div id="add_prod_title">Добавить продукт</div>
<select>
	<?
		foreach ($ctg_array as $key => $value) {
			echo "<option>".$value['categ']."</option>";
		}
	?>
</select>

</div>