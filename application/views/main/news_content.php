	<div id="news_content">
		<div id="news_content_title"><a href="/">Ценители прекрасного:</a></div>
		<?
			foreach ($rev_records as $key => $value) {
				print('<div class="reviewer"><div class="rev_photo"><img src="'.$value['photo'].'"></div><div class="rev_name">'.$value['name'].'</div><div class="rev_text">'.$value['review'].'</div></div>');
			}
		?>
	</div>
</div>