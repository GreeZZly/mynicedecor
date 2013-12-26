 $(document).ready(function(){
	$("#thumbnail div").click(function(e){

		$('.arrows').addClass('hidden');
		$(this).find('.arrows').removeClass('hidden');

		var visible = $(".large img:not(:hidden)");
		var hidden = $(".large img:hidden");
		if (visible.attr('src') == $(this).attr('href')){}
		else {
			hidden.attr({"src": $(this).attr("href")});
			visible.fadeOut();
			hidden.fadeIn();
		}
	});
	var cat_h = $('#categories').height();
	$('.large img').css({'height':cat_h});
	var gallery_btn_h = cat_h/4-10;
	$('#thumbnail div:not(.arrows), .tmb_text').css({'height':gallery_btn_h});
});