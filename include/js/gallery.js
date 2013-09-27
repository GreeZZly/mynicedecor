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
});