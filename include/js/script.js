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
});