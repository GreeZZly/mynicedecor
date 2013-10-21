$(function() {
	$('#register').on('click', function() {
		$('#overlay').removeClass('hidden');
		$('#reg_form').removeClass('hidden');
		$(this).hide();
		// $('#reg_form').show(300);
	})
	$('#restore').on('click', function() {
		$('#overlay').removeClass('hidden');
		$('#lostp_form').removeClass('hidden');
		$('.loginform').hide();
		// $('#reg_form').show(300);
	})
	$('#login').on('click', function() {
		$('#overlay').removeClass('hidden');
		$('#reg_form').removeClass('hidden');
		$(this).hide();
		// $('#reg_form').show(300);
	})
	$("[name='confirm'],[name='password']").on('blur', function() {
		var pass = $("[name='password']").val();
		var conf = $("[name='confirm']").val();
		if (conf != pass) {
			$('.warning.confirm').html('пароли не совпадают').removeClass('hidden');
		} else if (conf == pass) {
			$('.warning').addClass('hidden');
		}
	})
	//проверка заполнения поля (tag - идентификатор поля ошибки, val - значение поля)
	var validate_field = function(tag, val){
		var pattern = /^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i;
		var error = '';
		var pass = $("[name='password']").val();
		var conf = $("[name='password_confirm']").val();
		var status = true;
		if (tag == 'span.password_confirm' &&  conf != pass) {
			error = 'пароли не совпадают';
		}				
		if (tag == 'span.email' && !pattern.test(val) && val != '') {
			error = 'некорректный адрес почты';
			status = false;
		}
		var size = 6;
		if (tag == 'span.password' && val.size < size && val != '') {
			error = 'не менее 6 символов';
			status = false;
		}
		if (tag == 'span.identity' && val.size < 3 && val != '') {
			error = 'не менее 3 символов';
			status = false;
		}
		if (tag == 'span.phone' && val.size <11 && val != '') {
			error = 'неверный формат номера';
			status = false;
		}
		if (tag == 'span.confirm' && val.size < size && val != '') {
			error = 'не менее 6 символов';
			status = false;
		}
		if (val == ''){
		 	error = 'обязательно для заполнения';
			status = false;
		}
		return {'error': error, 'status' : status};
	}
	var validate_form = function(id) {
			var status = true;
			$(id + ' input:not(.submit)').each(function() {
				var tag = 'span.' + $(this).attr('name');
				var val = $(this).val();
				data = validate_field(tag, val);
				status = (status & data.status);
				$(tag).html(data.error).removeClass('hidden')
				//if (!data.status) console.log(tag + " : " + data.error);
			})
			return status;
		}
	$('#send').on('click', function(e) {
		if (!validate_form("[id$='_form']")) {
			e.preventDefault();
		}
	})
	
	$("[id$='_form'] input:not(.submit)").on('blur',function(){
		var tag = 'span.' + $(this).attr('name');
		var val = $(this).val();
		data = validate_field(tag, val);
		$(tag).html(data.error).removeClass('hidden');
		// if (!data.status) console.log(tag + " : " + data.error);
	})	
})
