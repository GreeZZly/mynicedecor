$(function() {
    var click = ' click tap ';
    var keyEvents = ' keydown keyup keypressed ';
    var i = 0;
    var company_info_global = {};
    var row_global = {};
    var reg_c = $('#rg_comp').html();
    var action_button = '';
    var lang = localization.common_frases;
    $('div.v_h_a').show();
    $('#modules').data('hidden', 'true')

    $(document).on(click, '[id^="st_"]:not(#admin_bar)', function(e){
        changeHashLoc($(this).attr('id').split('_')[1])
    })
    //переход на следующее поле по нажатию кнопки таб
        $(document).on(keyEvents, '.editIdSel *', function(e){
            if (e.keyCode==9){
                $(this).parent('.editIdSel').next('.editIdSel').find('input:not(.hidden) select textarea').focus()
            }
        })
    //сохранение добавленных и редактированных записей
        $(document).on(click, '.save_button', function(e) {
            var sendAlloweed = true;//изменить для валидации, пока просто пропускает
            var unit = ($(this).parents('#editUnit').length) ? '#editUnit' : '#popupEditUnit';
            if (unit=='#popupEditUnit') $(unit).slideUp();
            $(unit).scrollTop(0);
            var send = collectData(unit); var cid = $('#company_id').html(); var nm = '';
            try{
                nm = location.href.split('#').split('/')[1]
                if (!cid) cid = location.href.split('#').split('/')[2]
            }catch(e){} 
            if (!send.id_customer && cid) send.id_customer= cid;
            send.customer_id = send.id_customer;
            table = $('#table_name').html();
            act = $('#proc_action').html()
            id = (act == 'add') ? '' : (table == 'customer') ? $('#company_id').html() || send.id_customer : $('#current_id').html();
            if (!id && send.id) id = send.id;
            common = {
                'id': id,
                'table': (table=='plan_popup') ? 'plan' : table,
                'values': send
            }
            nativ = {
                'table': table,
                'values': send
            }
            send_data = (act == 'add') ? nativ : common;
            if (sendAlloweed) {
                $.ajax({
                    type: 'POST',
                    data: send_data,
                    url: '/index.php/crm/update_record',
                    success: function(data) {
                        if (!id && data!=true) {id = data; send.id = data;}
                        $(unit).html(''); 
                        if (table == 'customer') {
                            if (act == 'add') {
                                changeHashLoc('company/' + $.trim(send.name) + '/' + data.id_customer)
                            } else if (act == 'edit') {
                                changeHashLoc('company/' + $.trim(send.name) + '/' + send.id_customer)
                            } else {
                                location.reload();
                            }
                        } else if (table == 'sale' || table == 'plans' || table == 'contact' || table == 'order' || table == 'segment') {
                            show_company_info(encodeURI(nm), data.id_customer)
                            // if (data && act == 'add') load_table(''); //update_row(send, table);
                            // else if (data && act == 'edit') update_row(send, table);
                        } else if (table == 'plan_popup'){
                            // updatePlanRow(send, table);
                            show_plans_hash($('#reload').data('sub'), $('#curr_timesegment').html(), false)
                        } else {
                            //console.log(id, act, table, send)
                            // show_company_info(encodeURI(nm), data.id_customer)
                            // location.reload()
                        }
                    },
                    error: function(data, error) {
                        // show_company_info(encodeURI(nm), send.id_customer)
                        // location.reload()
                        console.log(error);
                    },
                    dataType: 'json'
                })
                $('#editUnit, #order_tables').fadeOut();
            }
        })
    //добавление и удаление фото, документов(приложений)
        $(document).on(click, '#deletePhoto', function(e) {
            if (confirm(lang.delete_confirm)) {
                if ($(this).html() == lang.delete) {
                    $(this).html(lang.add);
                    $(this).parents('.editPhoto').find('#fill_ava').addClass('hidden_ava');
                    $(this).parents('.editPhoto').find('input').css({
                        'margin-left': '0px'
                    });
                }
            } else return false
            sP(e)
        })
        $(document).on('change', '.photoInput', function(e) {
            var span_tag = $(this).parents('div#del_button').find('span')
            if (span_tag.html() == lang.add) {
                span_tag.html(lang.delete)
                $('#logo_or_pic_submit').trigger('click');
                $('#fill_ava').removeClass('hidden_ava')
                document.getElementById('upload_frame').contentWindow.document.body.innerHTML = ''
                $('#imageHolder').attr('src', 'include/images/loading.gif')
                window.setTimeout(function() {
                    write_ifc(0, e)
                }, 1);
            }
        })
        $(document).on(click, '#deleteDocument', function(e) {
            if (confirm(lang.delete_confirm)) {
                if ($(this).html() == lang.delete) {
                    $(this).html(lang.attach);
                    $(this).parents('.editAttachment').find('#att_href').addClass('hidden_ava');
                    $(this).parents('.editAttachment').find('input').css({
                        'margin-left': '0px'
                    });
                    document.getElementById('upload_frame').contentWindow.document.body.innerHTML
                }
            } else return false;
            sP(e)
        })
        $(document).on('change', '.documentInput', function(e) {
            if ($(this).parents('div#file_del_button').find('span').html() == lang.attach) {
                $('#upl_document_submit').trigger('click');
                //
                window.setTimeout(function() {
                    write_ifc(0, e)
                }, 1);
            }
        })
    //кнопки редактирования, удаления и добавления (только для клиентов, для таблиц дальше)
        $(document).on(click, '.wrapper_user#record_add', function(e) {
            var miserable = $('#reload').data().current;
            switch (miserable){
                case 'company':
                    loc = localization['legal'];
                    html = editUnitHead(lang.customer);
                    type = block_with_select({
                        'caption': lang.entity,
                        'text': $(this).find('option:selected').text(),
                        'id': 'type',
                    })
                    for (var key in loc) {
                        arr = {
                            'caption': loc[key],
                            'text': (key == 'date_registration') ? date_string(true) : '',
                            'id': key
                        }
                        if (key == 'type') {
                            html += type;
                        } else if (key == 'date_registration') {
                            html += architector(arr, 'disabled')
                        } else {
                            html += architector(arr);
                        }
                    }
                    html += '<div id="proc_action" class="hidden">add</div>';
                    html += '<div id="table_name" class="hidden">customer</div>';
                    html += editUnitHead('Клиент');
                    $('#editUnit').html(html);
                    $('#editUnit').scrollTop(0).fadeIn();
                    break;
                default:
                    console.log(miserable)
                    break;
            }
            sP(e)
        })
        $(document).on(click, '.wrapper_user#record_edit', function(e) { //Customer edit
            var miserable = $('#reload').data().current;
            switch (miserable){
                case 'company':
                    var id = $("#company_id").html();
                    var data = false //$('#title').data('company_info') || false;
                    if (!data)
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',

                            data: {
                                'value': id,
                                'field': 'id',
                                'table': 'customer'
                            },
                            url: '/index.php/crm/record',
                            async : false,
                            success : function(s){
                                data = s[0];
                            }
                        })
                    fill_edit_table(id, 'customer', data)
                    $('#table_name').html('customer');
                    $('#editUnit').append('<div id="proc_action" class="hidden">edit</div>')
                    $('#editUnit').scrollTop(0).fadeIn();
                    sP(e);
                    break;
                case 'user':
                    console.log(miserable);
                default:
                    console.log(miserable)
            }
        })
        $(document).on(click, '.wrapper_user#record_delete', function(e) {
            var miserable = $('#reload').data().current;
            switch (miserable){
                case 'company':
                    if (confirm(lang.usual_confirm)) {
                        var rem = [];
                        $(".icon-box-checked:not(#all)").each(function() {
                            rem.push(parseInt($(this).parents('tr').find('td.id .caption').html()));
                        });
                        var nums = (rem.join("t"));
                        if (nums != '') {
                            $.ajax({
                                url: '/index.php/crm/delete/' + nums
                            }).done(function() {
                                load_table('all');
                            })
                        }
                        if ($('#company_id').length) {
                            $.ajax({
                                url: '/index.php/crm/delete/' + $('#company_id').html()
                            }).done(function() {
                                load_table('all');
                            })
                        }
                    }
                    break;
                default:
                    break;
            }
        });
    //события нажатия на кнопки ред, уд, доб для таблиц(продажи, планы и т.д.)
        $(document).on(click, '[id$="_record_add"]', function(e) {
            var name = $(this).attr('id').split('_')[0]
            fill_edit_table('', name, 'new');
            $('#editUnit').append('<div id="proc_action" class="hidden">add</div>')
            $('#editUnit').scrollTop(0).fadeIn();
            sP(e);
        })
        $(document).on(click, '[id$="_record_edit"]', function(e) {
            sP(e);
            if ($('tr.active').size() != 0) {
                var id = $('tr.active').find('.id').html();
                var table = $('.cont .active').attr('class').split(' ')[0];
                console.log('id = ', id, 'table = ', table)
                fill_edit_table(id, table, '' /*'by_id'*/ );
                $('#editUnit').append('<div id="proc_action" class="hidden">edit</div>')
                $('#editUnit').scrollTop(0).fadeIn();
                sP(e);
            } /*else {
                var id = $('#company_id').html();
                var table = 'customer';
            }*/
        })
        $(document).on(click, '[id$="_record_delete"]', function(e) {
            if (confirm(lang.usual_confirm)) {
                var id = $('.cont .active').find('td.id').html();
                var table = $('.cont .active').attr('class').split(' ')[0];
                table = (table == 'plan') ? 'plans' : table;
                $.ajax({
                    dataType: 'json',
                    type: 'post',
                    data: {
                        'id': id,
                        'table': table
                    },
                    url: '/index.php/crm/delete_record',
                    success: function() {
                        location.reload();
                    }
                })
            }
            sP(e);
        })
    //предотвращение скрытия при нажатии в пределах кастомного селекта (jqueryUI)
        $(document).on(click, '.ui-autocomplete *', function(e) {
            sP(e)
        })
    //Автозаполнение полей адреса
        $(document).on('change', '.editIdSel#country select', function(e) {
            var id = $(this).find('option:selected').val()
            if (id != '0001') { /*$('.editIdSel#region, .editIdSel#subregion, .editIdSel#street').remove(); */ } else {
                $('.editIdSel#region input, .editIdSel#subregion input, .editIdSel#ppp input, .editIdSel#street input').val('');
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '/index.php/crm/getRegByCountry',
                    data: {
                        'id': id
                    },
                    success: function(data) {
                        $('.editIdSel#region input').autocomplete({
                            source: data,
                            //minLength : 2,
                            select: function(event, ui) {
                                event.preventDefault();
                                $(this).val(ui.item.label);
                                $(".editregionId").html(ui.item.value);
                            },
                            focus: function(event, ui) {
                                event.preventDefault();
                                $(this).val(ui.item.label);
                                $(".editregionId").html(ui.item.value);
                            },
                        });
                    }
                })
            }
        })
        $(document).on('blur ', '.editIdSel#region input', function(e) {
            var id = $(".editregionId").html()
            if (id) $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/index.php/crm/getRNCByRegion',
                data: {
                    'id': id
                },
                success: function(data) {
                    $('.editIdSel#subregion input').autocomplete({
                        source: data,
                        //minLength : 2,
                        select: function(event, ui) {
                            event.preventDefault();
                            $(this).val(ui.item.label);
                            $(".editsubregionId").html(ui.item.value);
                        },
                        focus: function(event, ui) {
                            event.preventDefault();
                            $(this).val(ui.item.label);
                            $(".editsubregionId").html(ui.item.value);
                        }
                    });
                }
            })
        })
        var cocaine = '';
        var identity = '';
        $(document).on('keyup', '.editIdSel#subregion input', function(e) {
            if ($(this).val().split('.')[0] == 'р-н') {
                cocaine = 'getPNCBySNR';
                caption = lang.subregion;
                identity = 'ppp';
                if ($('.editIdSel#ppp').size() == 0) $('.editIdSel#subregion').after(block_with_textinput({
                    'caption': caption,
                    'id': identity
                }))
            } else {
                cocaine = 'getStrByCNR';
                caption = lang.street;
                identity = 'street';
                $('.editIdSel#ppp').remove();
            }
            //$( '.editIdSel#'+identity+' input' ).autocomplete("destroy")
        })
        $(document).on('blur', '.editIdSel#subregion input', function(e) {
            var said = $(".editsubregionId").html()
            if (said) $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/index.php/crm/' + cocaine,
                data: {
                    'id': said
                },
                success: function(data) {
                    $('.editIdSel#' + identity + ' input').autocomplete({
                        source: data,
                        //minLength : 2,
                        select: function(event, ui) {
                            event.preventDefault();
                            $(this).val(ui.item.label);
                            $(".edit" + identity + "Id").html(ui.item.value);
                            $('.editIdSel#index input').val(ui.item.index)
                        },
                        focus: function(event, ui) {
                            event.preventDefault();
                            $(this).val(ui.item.label);
                            $(".edit" + identity + "Id").html(ui.item.value);
                            $('.editIdSel#index input').val(ui.item.index)
                        }
                    });
                }
            })
        })
        $(document).on('blur', '.editIdSel#ppp input', function(e) {
            var rid = $(".editregionId").html()
            var sid = $(".editsubregionId").html()
            var pid = $(".editpppId").html()
            /*if (rid && sid && pid) */
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '/index.php/crm/getStreetsInPPP',
                data: {
                    'pid': pid
                },
                success: function(data) {
                    $('.editIdSel#street input').autocomplete({
                        source: data,
                        //minLength : 2,
                        select: function(event, ui) {
                            event.preventDefault();
                            $(this).val(ui.item.label);
                            $(".editstreetId").html(ui.item.value);
                            $('.editIdSel#index input').val(ui.item.index)
                        },
                        focus: function(event, ui) {
                            event.preventDefault();
                            $(this).val(ui.item.label);
                            $(".editstreetId").html(ui.item.value);
                            $('.editIdSel#index input').val(ui.item.index)
                        }
                    });
                }
            })
        })
    //изменение адреса для отображения категорий и принадлежности клиентов
        $('#categories').on(click, '#clients_categories ul li', function(e) {
            var id = $(this).attr('id').split('_')[1] || $(this).attr('id').split('_')[0]
            if (id == 'clients') id = 'all'
            changeHashLoc('clients/'+id);
        })
    
    //важная функция для запрета скрытия всплывающих меню, окон редактирования и прочего
        $(document).on(click, function(event) {
            if (!$("#editUnit").is(':hidden')) {
                if (!$(event.target).closest("#editUnit").length) {
                    $("#editUnit, #order_tables").fadeOut("fast");
                    $('#editUnit').scrollTop(0);
                    return;
                }
            }
            if (!$("div.settings_popup").is(':hidden')) {
                if (!$(event.target).closest("#settings").length) {
                    $("div.settings_popup").fadeOut("fast");
                    return;
                }
            }
            if (!$("#lookup_popup").hasClass('hidden')) {
                if (!$(event.target).closest("#big_plus").length) {
                    $('#lookup_popup').addClass('hidden');
                    return;
                }
            }
        });
        $(document).on(click, '#ui-datepicker-div *, #ui-timepicker-div *, .ui-corner-all', function(e) {
            sP(e);
        })
    //переход по вариантам страницы планы (недельное планирование, суточное)
        $('#plan_categories ul li').on(click, function() {
            changeHashLoc('plans/' + $(this).attr('id'));
        })
    //редактирование статиса по клику на звезды
        $(document).on(click, '#status .wrapper_stars:not(.disabled)', function(e) {
            id = e.target.id;
            num = parseInt(id.split('_')[1])
            if (num) {
                for (var i = 1; i <= 5; i++) {
                    if (i <= num) {
                        $('.wrapper_stars #star_' + i).attr('class', 'icon-star-filled');
                    } else {
                        $('.wrapper_stars #star_' + i).attr('class', 'icon-star-empty');
                    }
                }
                element = $('#' + id).parent('.wrapper_stars').next('input').val(num + '')
            }
        })
    //обновление страницы, просто перезагрука
        $('#reload').on(click, function(e) {
            //location.href = location.href.split('#')[0] + '#clients/all';
            location.reload();
        });
    //кастомная метка строки в таблице
        $(document).on(click, "[class^='icon-box-']:not(#all)", function(e) {
            e.preventDefault();
            if ($('#company_id').size() == 0) $('.clients').append('<div class="hidden" id="company_id"></div>');
            if ($(this).attr('class') == 'icon-box-unchecked') {
                $(this).attr('class', 'icon-box-checked');
                $('.wrapper_user#record_edit, .wrapper_user#record_delete').show();
                var id = $(this).parents('tr').find('td.id .caption').html();
                $('#company_id').html(id)
                if ($('.icon-box-checked').size() != 0) {
                    if ($('.icon-box-checked').size() == 1) {
                        $('.wrapper_user#record_edit, .wrapper_user#record_delete').show();
                    } else {
                        $('.wrapper_user#record_delete').show();
                        $('.wrapper_user#record_edit').hide();
                    }
                } else {
                    $('.wrapper_user#record_edit, .wrapper_user#record_delete').hide();
                }
            } else if ($(this).attr('class') == 'icon-box-checked') {
                $(this).attr('class', 'icon-box-unchecked');
                if ($('.icon-box-checked').size() != 0) {
                    var id = $('.icon-box-checked').last().parents('tr').find('td.id .caption').html();
                    $('#company_id').html(id)
                    if ($('.icon-box-checked').size() == 1) {
                        $('.wrapper_user#record_edit, .wrapper_user#record_delete').show();
                    } else {
                        $('.wrapper_user#record_delete').show();
                        $('.wrapper_user#record_edit').hide();
                    }
                } else {
                    $('#company_id').html('')
                    $('.wrapper_user#record_edit, .wrapper_user#record_delete').hide();
                }
            }
        });
    //пометка всех видимых строк разом
        $(document).on(click, '#all', function(e) {
            e.preventDefault();
            if ($(this).attr('class') == 'icon-box-unchecked') {
                $("[class^='icon-box-'],[class*='icon-box-']").attr('class', 'icon-box-checked');
                $('.wrapper_user#record_delete').show();
                $('.wrapper_user#record_edit').hide();
            } else if ($(this).attr('class') == 'icon-box-checked') {
                $("[class^='icon-box-'],[class*='icon-box-']").attr('class', 'icon-box-unchecked');
                $('.wrapper_user#record_edit, .wrapper_user#record_delete').hide();
            }
        });
    //переключение кастомной метки Да/Нет
        $(document).on(click, '.integration_toggle', function(){
            var no = $(this).find('.integration_toggle_no');
                yes = $(this).find('.integration_toggle_yes');      

            if (no.hasClass('integration_toggle_active')) {
                no.removeClass('integration_toggle_active');
                yes.addClass('integration_toggle_active');
            }
            else
            {
                yes.removeClass('integration_toggle_active');
                no.addClass('integration_toggle_active');
            }        
        });
    //добавление/удаление/редактирование категорий

        $(document).on(click, '[id^="category_tree_"]', function(e) {
            e.preventDefault();
            var id = $(this).attr('id').split('_')[2];
            var name = $(this).html();
            $('[id^="category_tree_"]').removeClass('active_product_category');
            $(this).addClass('active_product_category');
            $.ajax({
                url: '/index.php/admin/getFolderProducts',
                type: 'post',
                data: {
                    'folder_id': id
                },
                success: function(data) {
                    if (0 in data){
                        for (var i in data) {
                            data[i]['available'] = diff(data[i]['storage'], data[i]['stored'])
                            data[i]['id_cat'] = data[i]['category_id']
                        }
                        if ($('#products_available').size() > 0) {
                            $('#products_available #table2').html('')
                            $('#products_available #table2').html(av_order(data, 'av_order', 'av_order_head'))
                            $('#products_available .ordertable_header').html(name)
                        } else if ($('.settings_products_webinar').size() > 0) {
                            $('.settings_products_webinar table').html('').html(av_order(data, 'av_order', 'av_order_head', true)+products_dum_row)
                        }
                    } else {
                        if ($('#products_available').size() > 0) {
                            $('#products_available #table2').html(products_dum_row_head+products_dum_row)
                            $('#products_available .ordertable_header').html(name)
                        } else if ($('.settings_products_webinar').size() > 0) {
                            $('.settings_products_webinar table').html('').html(products_dum_row_head+products_dum_row)
                        }
                    }
                },
                dataType: 'json'
            });
            return false;
        })
        $(document).on(click, '.categories_add_button',function(e){
            var ab = $('.active_product_category');
            if (ab.length){
                var parent_lvl = parseInt(ab.parent('li').attr('level'));
                var parent_rk = parseInt(ab.parent('li').attr('rk'));
                if (!ab.next('ul').length) {ab.after('<ul level="'+(parent_lvl+1)+'"></ul>');}//mkCollapsible(ab.parent())}
                var a = ab.next('ul');
            } else if ($('.tree ul').length==0){
                $('.tree').append('<ul level="0"><li level="0" rk="1"><span class="active_product_category"></span></li></ul>') //добавить создание категории
                var parent_rk = 1;
                var parent_lvl = 0;
                var a = $('.tree ul') 
            }
            a.show();
            a.find('.last').removeClass('last');
            //a.find('.lastCollapsable').removeClass('lastCollapsable')
            a.append('<li class="last" level="'+(parent_lvl+1)+'"><span id="category_tree_" contenteditable></span></li>');
            console.log(parent_lvl, parent_rk);
            a.find('.last span').addClass('editable_category_block').data('upl_info', {name:'',level:parent_lvl,prk:parent_rk}).focus();
            console.time('tree rebuilding took');
            rebuildTree($('.tree').children('ul'));
            $('.tree').treeview();
            console.timeEnd('tree rebuilding took');
        })
        $(document).on(click, '.categories_edit_button',function(e){
            var ab = $('.active_product_category');
            var new_level = parseInt(ab.parent('li').attr('level'));
            var parent_rk = parseInt(ab.parent('li').attr('rk'));
            console.log(new_level, parent_rk);
            ab.addClass('editable_category_block').attr('contenteditable', true).data('upl_info', {name:ab.text(),level:new_level,prk:parent_rk})
            ab.focus()
        })
        $(document).on(click, '.categories_delete_button',function(e){
            $('.active_product_category');
            return false;
        })
        $(document).on(keyEvents, '.editable_category_block', function(e){
            if (e.keyCode == 13){
                $(this).focusout();
            }
        })
        $(document).on('focusout', '.editable_category_block', function(e){
            var data  = $(this).data().upl_info;
            data.name = $.trim($(this).text());
            data.id   = $(this).attr('id').split('_')[2]       
            var tree  = $('#st_products').data().tree;
            if (data.id){
                var origText = tree[getKeyById(tree, data.id)].name;
                if (data.name)
                    if (origText!=data.name)
                        saveNode($(this), 'edit');
                    else
                        saveNode($(this), false);
                else{
                    $(this).text(origText);
                    saveNode($(this), false);
                }
            }else{
                if (data.name)
                    saveNode($(this));
                else {
                    var pa = $('.active_product_category').next('ul');                
                    if (pa.find('li').length <= 1)
                        pa.remove();
                    else {
                        $(this).parent('li').remove();
                        pa.find('li:last-of-type').addClass('last');
                    }
                }
            }
        })
    //добавление/удаление/редактирование продуктов
        $(document).on('focusout', '.settings_products_webinar table td[contenteditable]', function(e){
            collectRowData($(this).parents('tr'))
            if ($(this).hasClass('storage')){
               var table = $('.settings_products_webinar table')
                if(isLastRowEmpty(table)) {
                    table.append(products_dum_row);
                    table.find('tr:last-of-type td.product').focus();
                }  
            }
        })
        $(document).on(keyEvents, '.storage', function(e){
            if (e.type=='keypress'){
                var table = $('.settings_products_webinar table');
                var k = e.keyCode;
                if (k==9) $(this).focusout();
            }
            var stored = $(this).parent('tr').children('td.stored');
            var available = $(this).parent('tr').children('td.available');
            if (stored.text()!='0' && stored.text()!=''){
                var num = parseInt($(this).text())-parseInt(stored.text())
                available.text((num>0)? num : 0)
                if ($(this).text()=='') $(this).text('0')
            } else {
                if ($(this).text()=='') {
                    available.text('');
                    stored.text('');
                }else{
                    available.text($(this).text());
                    stored.text(0);
                }
            }
        })
        $(document).on(keyEvents, '.settings_products_webinar table td', function(e){        
            var c = $(this).hasClass('cost') || $(this).hasClass('storage');
            var table = $(this).parents('table');
            if (e.type == 'keydown'&&c){
                isNumCode(e);            
            }
            if (e.keyCode && isLastRowEmpty(table)){
                table.append(products_dum_row);
            }
        })
    //все поля с заполнением только цифрами
        $(document).on(keyEvents, '[own$="plan"], input.price, #phone input, #index input, #INN input, #KPP input, #BIK input, #payment_account input, #corr_account input, #OGRN input, #OKPO input, #OKVED input, #OKFS input, #OKOPF input, #OKATO input', function(e){
            if (e.type == 'keydown') isNumCode(e);
            //if ($(this).hasClass('price')) $(this).html(digitFormat($(this).html()))
        })
    //неизвестные !unfinished    
        $(document).on(click,'.SD_top_panel_button',function(){
            $('.SD_top_panel_button').removeClass('SD_top_panel_button_selected');
            $(this).addClass('SD_top_panel_button_selected');
        });

        $(document).on(click,'#settings_directory_table tr',function(){
            $('#settings_directory_table tr').removeClass('SD_table_row_selected');
            $(this).addClass('SD_table_row_selected');

        });    
        $(document).on('click', '.choice',function() {
            $('.choice').removeClass('sales_active_tr_choice');
            $(this).addClass('sales_active_tr_choice');
        });
        $(document).on('click','.movable tr', function() {
            $('.movable td').removeClass('sales_active_tr_hidden');
            $(this).find('td').addClass('sales_active_tr_hidden');
        });
    //отображение меню редактирования пользователя !unfinished
        $(document).on(click, '#users tr:not(.usertable_head):not(.users_row_selected)', function(e){
            $('#users tr').removeClass('users_row_selected');
            $(this).addClass('users_row_selected');
            var id = $(this).find('td.id').html()
            var users = $('#goodcrm_logo .caption').data('users')
            var data = users[getKeyById(users, id)];
            fill_edit_table(id, 'user', data, undefined,'disabled');
            $('#editUnit').scrollTop(0).show();
            sP(e)
        });
    //редактирование/удаление/добавление пользователей
        $(document).on(click, '.users_edit_button', function(e){
            var id = $('#users tr.users_row_selected td.id').html();
            var users = $('#goodcrm_logo .caption').data('users')
            var data = users[getKeyById(users, id)];
            fill_edit_table(id, 'user', data)
            sP(e)
            console.log(e);
        })
    //показ планов по продаже
        $('.clients').on('click', '.table_row', function() {
            var lex = $(this);
            var id_sale = $(this).find('td.id_sale').html()
            var id = $(this).find('td.id').html()
            if (!$('#hidden_table_' + id).length && !$('.nohistory_'+id).length) {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '/index.php/crm/getPlansBySale',
                    data: {
                        'id_sale': id_sale
                    },
                    success: function(data) {
                        if (data.length){
                            lex.after(sale_hidden_table(data, id))
                            $('#hidden_table_' + id + ' .movable').show()
                        } else {
                            lex.after('<tr class="nohistory_'+id+'" id="hidden_table_' + id + '"><td class="movable">'+lang.no_plan_by_sale+'</td></tr>')
                        }
                        $('#hidden_table_' + id + ' .movable').show()
                    },
                    error: function(data) {
                        lex.after('<tr class="hidden" id="hidden_table_' + id + '"></tr>')
                    }
                })
            }
            var td = $('#hidden_table_' + id + ' .movable');
            if (td.css('display') == 'none') td.show()
            else td.hide()
            $('.table_row td').removeClass('sales_active_tr');
            $(this).find('td').addClass('sales_active_tr');
        });
    //редактирование/добавление планов по продажам
        $(document).on('change', '#saleplan_process', function(e){
            $('#saleplan_tablehead').html(build_saleplan_head($(this).find('option:selected').val(), ''))
            getUserList(saleplanTableBuilder)
            var y = $('#saleplan_year').find('option:selected').text();
            var p = $('#saleplan_process').find('option:selected').val();
            var m = parseInt($('#saleplan_month').find('option:selected').val())+1;
            m = (m<10) ? '0'+m: m
            getUserList(psNormaler, 'crm/getPlansOfSale', {date : m+'-'+y, process: p})
        })
        $(document).on('change', '#saleplan_year, #saleplan_month', function(e){
            var y = $('#saleplan_year').find('option:selected').text();
            var p = $('#saleplan_process').find('option:selected').val();
            var m = parseInt($('#saleplan_month').find('option:selected').val())+1;
            m = (m<10)?'0'+m:m;
            $('td[srv]:not([srv$="--1"]').each(function(index, el) {
                var a = $(this).attr('srv').split('-');
                a[2] = y; a[1] = m;
                $(this).attr('srv', a.join('-')).html('');
            });
            getUserList(psNormaler, 'crm/getPlansOfSale', {date : m+'-'+y, process: p})
        })
        $(document).on('focusin', '[own="saleplan"]', function(e){
            $(this).attr('hist', $(this).text().split(' ').join(''))
        })
        $(document).on('focusout', '[own="saleplan"]', function(e){
            $(this).html(digitFormat($(this).html()))
            var s = $(this).attr('srv').split('-');
            var c = $(this).text().split(' ').join('');
            var h = parseInt($(this).attr('hist'));
            if (parseInt(c)>0 && c!=h) $.ajax({
                url: '/index.php/crm/setPlanOfSale',
                type: 'POST',
                dataType: 'json',
                data: {
                    send: {
                        id_user : s[0],
                        date : s[1]+'-'+s[2],
                        process : s[3],
                        phase:{
                            phase:s[4],
                            count:c
                        }
                    }
                },
            })
            .fail(function() {
                console.log("error");
            })            
        })
    //отображение воронки по дате, пользователю
        $(document).on('change', '#funnel_process', function(e){
            var process = $(this).val();
            var date = getDateByWord($('.funnel_period.current_period').attr('id'));
            var id = $('.employee.current_user').attr('id');
            if (id!='all') send = {process:process,date:date, user: id.split('_')[2]};
            else send = {process:process,date:date}
            getUsersForTable(rfl_set, 'crm/getFunnel', send);
        })
        $(document).on(click, '.employee:not(.current_user)', function(e){
            $('.employee.current_user').removeClass('current_user');
            $(this).addClass('current_user');
            var date=getDateByWord($('.funnel_period.current_period').attr('id'))
            var process = $('#funnel_process').val();
            var id = $(this).attr('id');
            if (id!='all') send = {process:process,date:date, user: id.split('_')[2]};
            else send = {process:process,date:date}
            getUsersForTable(rfl_set, 'crm/getFunnel', send);
        })
        $(document).on(click, '.funnel_period:not(.current_period)', function(e){
            $('.funnel_period.current_period').removeClass('current_period');
            $(this).addClass('current_period');
            var date=getDateByWord($(this).attr('id'))
            var process = $('#funnel_process').val();
            var id = $('.employee.current_user').attr('id');
            if (id!='all') send = {process:process,date:date, user: id.split('_')[2]};
            else send = {process:process,date:date}
            getUsersForTable(rfl_set, 'crm/getFunnel', send);
        })
    //отображение таблицы активности продаж по периоду и бизнес процессу
        $(document).on(click, '.table_period:not(.current_period)', function(e){
            $('.table_period.current_period').removeClass('current_period');
            $(this).addClass('current_period');
            var date=getDateByWord($(this).attr('id'))
            getUserList(rtt_set, 'crm/getReport', {date: date})
            getUserList(rtt_emp_table)
        })
    //редактирование планов по платежам
        $(document).on(click, '.period', function(e){
            $('.period.current_year').removeClass('current_year');
            $(this).addClass('current_year');
            var year = $(this).attr('id').split('_')[1]
            $('td[srv]:not([srv*="-00-"]').each(function(){
                var a = $(this).attr('srv').split('-');
                a[2] = year;
                $(this).attr('srv', a.join('-')).html('')
            })
            getUserList(ppNormaler, 'crm/getPlansOfPayment', {year : year})
        })
        $(document).on('focusin', '[own="payplan"]', function(e){
            $(this).attr('hist', $(this).text().split(' ').join(''))
        })
        $(document).on('focusout', '[own="payplan"]', function(e){
            $(this).html(digitFormat($(this).html()))
            var s = $(this).attr('srv').split('-');
            var c = $(this).text().split(' ').join('');
            var h = parseInt($(this).attr('hist'));
            if (parseInt(c)>0 && c!=h) $.ajax({
                url: '/index.php/crm/setPlanOfPayment',
                type: 'POST',
                dataType: 'json',
                data: {
                    send: {
                        id_user : s[0],
                        date : s[1]+'-'+s[2],
                        plan : c
                    }
                },
            })
            .fail(function() {
                console.log("error");
            })
            
        })
    //переходы по ссылкам ajax и навигация по истории
        var app = $.sammy(function() {
            /*var current_user = false;
            function checkLoggedIn(callback) {
                if (!current_user) {
                    $.getJSON('/index.php/crm/isLoggedIn', function(json) {
                        if (json) {
                            current_user = json;
                            callback();
                        } else {
                            current_user = false;
                            location.href = '/auth/login';
                        }
                    });
                } else {
                    callback();
                }
            };
            this.around(checkLoggedIn);*/
            this.get('/admin#:func', function(context) {
                console.log(this.params.func)
                display_settings(this.params.func)
                setCurPart('company')
            });          
        });
        $(function() {
            app.run();
        });
    //костыля для css, размещение элементов на положенном им месте
        $(window).resize(function() {
            
        });
        $(window).trigger('resize');
        $('#editUnit, .clients').on('resize', function() {
            var tw = $('.clients').width();
            var ew = $('#editUnit').width();
            var curo = tw - ew;
            $('#order_tables').css({
                'width': curo + 'px'
            })
        })
        $('#editUnit, .clients').trigger('resize');
        $('#all_sales_categories ul li').on(click, function(e) {
            changeHashLoc('sales/' + $(this).attr('id'))
        })
        $('#sales_activity').on(click, function() {
            changeHashLoc('reports/funnel')
        })
        $('#plan_realization').on(click, function() {
            changeHashLoc('reports/table')
        })
});
