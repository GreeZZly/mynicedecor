$(function(){
	$(document).on('click', '.buy_button', function(e){
		var id = $(this).attr('srv_id');
		$.ajax({
			type: 'POST',
			dataType : 'json',
			url: '/index.php/main/insert_to_cart',
			data:{
				product_id : id
			},
			success: function(total_items){
				$('#cart_total_items').html(total_items);
			}
		});
	});

	var prod_height = $('#view_product').height() - 10;
	$('#another_interesting_prods').css('height',prod_height);
	
	
	// Выборка продуктов по селекту
	// var select_count = $('[id^="pp_"]').length;
	var arr = [];

	$('[id^="pp_"]').each(function(){
		arr.push($(this).val());
	})
	console.log(arr);

	$(document).on('change', '[id^="pp_"]', function(){
		var option_id = $(this).val();
		var cat_id = $('.ctg_item[current=1]').attr('cat_id');
		var arr = [];
		var pr_id_array = [];
		$('[id^="pp_"]').each(function(){
		arr.push($(this).val());
		})
		console.log(arr);
		var prop_id_array = [];
		$.ajax({
			type:'POST',
			dataType:'json',
			url: '/index.php/main/getProdBySelect',
			data:{id_array : arr, category_id: cat_id},
			success: function(datum){
				var n = datum.length;
					var text = ''
				$.each(datum, function(n,el){
					// text+=el.id+' - '
					console.log(el.id);	
					// console.log(el.pps);
					var elpps = el.pps.split(",");
					console.log(elpps);
					for (var i = 0; i < elpps.length; i++) {
						if ($.inArray(elpps[i], pr_id_array)==-1) pr_id_array.push(elpps[i]);
					};

					//prop_id_array.push(pr_id_array);

					text+='<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><a href="/index.php/main/viewProduct/'+el.id+'"><div class="pr_img"><img src="http://goodcrm.ru/'+el.path+'"></div></a><a href="/index.php/main/viewProduct/'+el.id+'"><div class="pr_name">'+el.product+'</div></a><div class="pr_type">'+el.type+'</div><div class="price">'+el.cost+' руб.</div><input type="hidden" name="product_id" value="'+el.id+'"><div class="buy_button" srv_id="'+el.id+'">Нравится</div></form></div>'
				});



					$("#prodByCategory").html(text);
					console.log(pr_id_array);
					var bool = false;
					$('[id^="pp_"]').each(function(){if($(this).val()!=0) bool= true;});
					if (bool)
						$('[id^="pp_"]').each(function() {
							var select = $(this);
							select.find('option').each(function(){
								if ($.inArray($(this).attr('value'), pr_id_array)==-1 && parseInt($(this).attr('value'))!=0 && select.val()==0) {
									$(this).addClass('hidden');
								}
								else {$(this).removeClass('hidden');}
								if(select.find('option:not(.hidden)').length == 1) {
									select.addClass('hidden');
									select.parents('.property_parent').addClass('hidden');
								}
								else
								{
									select.removeClass('hidden');
									select.parents('.property_parent').removeClass('hidden');
								}
							});
						});
					else $('[id^="pp_"], [id^="pp_"] option, .property_parent').removeClass('hidden');
					// prop_id_array_s = ppss.split(",");
					// console.log(pr_id_array);
			}
		});
		// });

	});
	
		// $("#prodByCategory").text(option_id);

});