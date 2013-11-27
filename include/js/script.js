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
	$(document).on('change', '[id^="pp_"]', function(){
		var option_id = $(this).val();
		if(option_id > 0) {
		$.ajax({
			type:'POST',
			dataType:'json',
			url: '/index.php/main/getProdBySelect',
			data:{id : option_id},
			success: function(datum){
				var n = datum.length;
					var text = ''
				$.each(datum, function(n,el){
					// text+=el.id+' - '
					console.log(el.id);	

					text+='<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><div class="pr_img"><img src="http://goodcrm.ru/'+el.path+'"></div><div class="pr_name">'+el.product+'</div><div class="pr_type">'+el.type+'</div><div class="price">'+el.cost+' руб.</div><input type="hidden" name="product_id" value="'+el.id+'"><div class="buy_button" srv_id="'+el.id+'">Нравится</div></form></div>'
				});
					$("#prodByCategory").html(text);
			}
		});
		}

	});
		// $("#prodByCategory").text(option_id);

});