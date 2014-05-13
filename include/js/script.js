$(function(){
	$(document).on('click', '.buy_button', function(e){
		var id = $(this).attr('srv_id');
		if($(this).hasClass('added')) {
			window.location = '/main/view_cart';
		}
		if(!$(this).hasClass('disabled')) {
			$(this).text('Добавлено').addClass('added');
		}
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
		var nonzero = false;
		var since = $('.pagi .active').html();
		// console.log(since);
		$('[id^="pp_"]').each(function(){
		arr.push($(this).val());
		if ($(this).val()!=0) nonzero = true;
		})
		console.log(arr);
		var prop_id_array = [];
		$.ajax({
			type:'POST',
			dataType:'json',
			url: '/index.php/main/'+(nonzero?'getProdBySelect':'raw_category'),
			data:{id_array : arr, category_id: cat_id, since: since},
			success: function(datum){
				var n = datum.length;
					var text = ''
					var disable = '';
				if(!nonzero){
					pagi = datum.pagi;
					datum = datum.products;
					
				}
				$.each(datum, function(n,el){
					// text+=el.id+' - '
					console.log(el.id);	
					// console.log(el.pps);
					if (el.pps){
						var elpps = el.pps.split(",");
						console.log(elpps);
						for (var i = 0; i < elpps.length; i++) {
							if ($.inArray(elpps[i], pr_id_array)==-1) pr_id_array.push(elpps[i]);
						};
					}
					//prop_id_array.push(pr_id_array);
					if (el.cost||el.price == 0) {
						disable = 'disabled';
					}
					text+='<div class="product_wrapper"><form name="prod_to_cart" method="post" action="/main/insert_to_cart"><a href="/index.php/main/viewProduct/'+el.id+'"><div class="pr_img"><img src="http://goodcrm.ru/'+(el.path||el.img)+'"></div></a><a href="/index.php/main/viewProduct/'+el.id+'"><div class="pr_name">'+(el.product||el.name)+'</div></a><div class="pr_type">'+el.type+'</div><div class="price">'+(el.cost||el.price)+'</div><input type="hidden" name="product_id" value="'+el.id+'"><div class="buy_button '+disable+'" srv_id="'+el.id+'">В корзину</div><div class="like_button" srv_id="'+el.id+'" like="dislike"></div></form></div>'
				});
				$("#prodByCategory").html(text);

				if (nonzero){
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
			}
		});
		// });

	});
	
		// $("#prodByCategory").text(option_id);

		
	// ORDER AUTH

	$(document).on('change', '.order_radio input[type="radio"]', function(){
		$('.loginform').addClass('hidden');
		$(this).parent('label').next('.loginform').removeClass('hidden');
		
	});


	$(document).on('click', '.nonreg_form_item input[type="button"]', function(){
		var validate_field = function(id){
			var status = true;
			var error = '';
			var value = $('#'+id + ' input').val();
			if (value == ''){
				status = false;
				error = 'Поле не заполнено.';
			}
			if (id == 'email' && value.indexOf('@')==-1) {
				status = false;
				error = 'Неправильный формат e-mail.';
			};
			if (id == 'phone' && value.length < 11){
				status = false;
				error = 'Неправильный формат номера (11-значный).';
			}
			return {status:status, error:error};
		};

		var form_status = true;
		$('.nonreg_form_item:not(.button)').each(function(){
			var valid =  validate_field($(this).attr('id'));
			var err = $(this).find('.validation_error');
			if(!valid.status){
				form_status = false;
				err.html(valid.error).removeClass('hidden');
			} else{
				err.addClass('hidden');
			}
		});
		if (form_status){
			$('#nonreg_form').submit();
		}

	});

	if(!$('#php_valid_error').html() == ''){
		$('#nonreg_radio').click();
	}
		var soc_width;
		var soc_margin;
		var e;
		e = setInterval(function(){
			if ($('.b-share').length){
				soc_width = $('.b-share').innerWidth();
				soc_margin = 1024/2 - soc_width/2;
				$('.b-share').css({'margin-left':soc_margin});
				clearInterval(e);
			}
		},100);
		var like_array=($.cookie('like_array'))?JSON.parse($.cookie('like_array')):[];
		$('#like_total_items').html(like_array.length);
		var login = 1;
		console.log(login);
	$(document).on('click', '.like_button', function(){
		var lb;
		lb = $(this);
		var srv_id = lb.attr('srv_id');
		if (lb.attr('like') == 'dislike') {
			$(this).css({'background-position':'-149px -443px'});
			$(this).attr('like','like');
			like_array.push(srv_id);

		}
		else {
			$(this).attr('like','dislike');
			$(this).css({'background-position':'-188px -443px'});
			like_array.splice($.inArray(srv_id, like_array), 1);
		};
			
			like_array_json = JSON.stringify(like_array);
			$.cookie('like_array', like_array_json,{path:'/'});
			// console.log(like_array_json);
			if(login == 1) {
			$.ajax({
				type:'POST',
				dataType:'json',
				url: '/index.php/main/setLikeToBd',
				data:{id_like_array : like_array},
				success: function(datum){
					
						}
						//prop_id_array.push(pr_id_array);

						
				});
			}
			$('#like_total_items').html(like_array.length);


	});
	// var like_array_cookie = JSON.parse($.cookie('like_array'));
	$('.like_button').each(function(){
		if ($.inArray($(this).attr('srv_id'), like_array)>-1) {
			$(this).css({'background-position':'-149px -443px'});
			$(this).attr('like','like');
		};
	});

	$(document).on('click', '.incart', function(){
		var inlb;
		inlb = $(this);
		if (inlb.attr('like') == 'dislike') {
			inlb.parents('.product_wrapper').addClass('hidden');
		}
	});

	$(function () {
    $.scrollUp({
        scrollName: 'scrollUp', //  ID элемента
        topDistance: '300', // расстояние после которого появится кнопка (px)
        topSpeed: 300, // скорость переноса (миллисекунды)
        animation: 'fade', // вид анимации: fade, slide, none
        animationInSpeed: 200, // скорость разгона анимации (миллисекунды)
        animationOutSpeed: 200, // скорость торможения анимации (миллисекунды)
        scrollText: 'Scroll to top', // текст
        activeOverlay: false, // задать CSS цвет активной точке scrollUp, например: '#00FFFF'
    });
});

});