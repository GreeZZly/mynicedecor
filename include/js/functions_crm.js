var lang = localization.common_frases;
var clone = function(obj) {
  var obj2 = {};
  for(var key in obj) obj2[key] = (typeof(obj[key]) == 'object') ? clone(obj[key]) : obj[key];
  return obj2;  
}
var cloneArray = function(obj) {
  var obj2 = Array();
  for(var key in obj) obj2[key] = (typeof(obj[key]) == 'object') ? clone(obj[key]) : obj[key];
  return obj2;  
}
var getKeyById = function(data, id){
    var len = data.length;
    for (var i=0; i<len; i+=1){
        if (data[i].id == id) return i;
    }
    return '';
}
var digitFormat = function(str) {
    str = str.replace(/\s+/g,'')
    return str.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
}
var makeKosherDate = function(string){
    var s = string.split('-'); 
    var t = ''; 
    t = s[0]; s[0] = s[1]; s[1] = t; 
    string = s.join('-'); 
    delete t; delete s;
    return string;
}
var round_mod = function(value, precision){
    var precision_number = Math.pow(10, precision);
    return Math.round(value * precision_number) / precision_number;
}
var getTextFrom = function(struct, key, def, round, sign) {
    var option = '';
    var def = def || '';
    try {
        option = (key in struct) ? struct[key] : def;
    } catch (e) {
        option = def
    }
    if (struct == '') option = key || def;
    if (option == 'null'        ||
        option == 'undefined'   || 
        option == ''            || 
        typeof(option)=='object'|| 
        typeof(option)=='undefined'
        ) option = def;
    if (typeof(option)=='number' && round) option = round_mod(option, 2)+sign
    return option;
}
var getUserList = function(overfunction, overurl, send, async){
    var registered = $('#currJSON').data().reg;
    var defalutfunction = function(data, wtc){
            data = data || $('#currJSON').data().reg
            var resp = sales_parser({
                'responsibility': data
            })['responsibility'];
            var html = '';
            for (var k in resp) {
                if (($.inArray(parseInt(k), managers)>-1) && show_only_managers) //to change go to htmlfragments form 740s. 
                    html += '<li id="performer_' + k + '">' + resp[k] + '</li>';
            }
            $('#clients_categories li#all_clients').after(html);
            if (wtc=='success') $('#currJSON').data('reg', data);
        }
    async = (typeof(async)!='undefined') ? async : true;
    if (!overurl && registered){
        (overfunction || defalutfunction)()
    } else $.ajax({
        url: '/index.php/' + (overurl || 'crm/getRegistered'),
        type: 'POST',
        dataType: 'json',
        data: send,
        async: async,
        success: overfunction || defalutfunction
    })
}
var main_table_header = function(){
    var html = '<table class="clients_table_head"><tr id="table_head"><td class="mark"><div id="all" class="icon-box-unchecked"></div></td>'
    var loc = clone(localization.record); delete loc.realname;
    for (var i in loc){
        html += '<td class="'+i+'">'+loc[i]+'</td>'
    }
    return html + '</tr></table>';
}
var v_h_a = function(){
    var html = '<div id="contentButs">'
    var loc = localization.cbuttonts;
    for (var i in loc){
        html += '<div class="CButs" id="show_'+i+'">'+loc[i]+'</div>'
    }
    return html+'</div>';
}
var report_table_vha = function(){
    html = '<div id="report1" class="report1_head">\
        <div id="content">\
        <table>\
                    <tr id="tablehead">'
    loc = localization.report_table;
    for (var i in loc){
        html += '<td class="'+i+'">'+loc[i]+'</td>'
    }
    return html+'</tr>\
                </table>\
                </div></div>'
}
var report_table_body = function(){    
    getUserList(rtt_emp_table)
    getUserList(rtt_set, 'crm/getReport', {date: date_string(false, true)})
    return '<div id="report1"><div id="content"><table id="rtplace"></table></div></div>';
}
var rtt_emp_table = function(data, wtc){
        data = data || $('#currJSON').data().reg
        var loc = localization.report_table;
        var len = data.length;        
        var html = ''
        if (len) for (var i=0; i<=len; i++){
            html += (i == len) ? '<tr id="total">' :'<tr>';
            for (var k in loc){
                html += '<td rprt="'+((i==len)?'total':data[i].id)+'-'+k+'" class="'+k+'">'+((k=='user')?((i == len) ? 'Итого:' : data[i].fio):'—')+'</td>'
            }
            html += '</tr>';
        }
        if (wtc=='success') $('#currJSON').data('reg', data);
        waitForUpdate('#rtplace', html)
    }
var rtt_set = function(data, wtc){
    var loc = clone(localization.report_table); delete loc.user; 
    var len = data.length;
    var html = ''
    var total = clone(loc); for (var i in total) total[i]={sum:0,count:0};
    if (len) for (var i=0; i<=len; i++){
        var c = (i==len)? total : data[i]
        for (var k in loc){
            var s = '[rprt="'+((i==len)? 'total' : c.id)+'-'+k+'"]'
            var t = (i==len)? getTextFrom('', c[k].sum/c[k].count, '0', true, (k=='efficiency' || k=='activity')? '%' : '') : getTextFrom(c, k, '—', (k=='efficiency' || k=='activity')? true : false, '%')
            if(i!=len){
                total[k].sum += ((t!='—')?((typeof(t)=='string')?parseFloat(t.split('%')[0].replace(',','.')):t):0);
                total[k].count ++;
            }
            waitForUpdate(s,t)
        }
    }
}
var report_voron_head = function(){
    return '<div id="content_title">\
            <div id="title_id">'+lang.activity_report+'</div>\
            <div class="funnel_period" id="day">'+lang.day+'</div>\
            <div class="funnel_period current_period" id="month">'+lang.month+'</div>\
            <div class="funnel_period" id="year">'+lang.year+'</div>\
            '+build_select_block(cloneArray(field_values.type_sale), 'process', 'funnel', 0)+'\
            </div>'
        }
var rfl_set = function(data, wtc){
    var l = data.length;
    var loc = phase_by_type[data[l-1].type_sale || data[0].process];
    var left = ''; var diagram = '';
    for (var i in loc){
        var plan = ('phase' in data[i])?+(data[i].count):0;
        var fact = ('phase' in data[i])?+(getTextFrom(data[i], 'cnt', '0')):0;
        var difference = ((fact - plan >= 0) ? '+' : '-') + Math.abs(fact - plan);
        left += '<li>'+loc[i]+'</li>';
        diagram += '<div class="diagram" id="phase_'+i+'">\
                <div class="diagram_plan"><div class="diagram_temp"  llength="'+plan+'" id="funnel_plan_'+i+'"></div><div class="diagram_count">'+plan+'</div></div>\
                <div class="diagram_act"><div class="diagram_temp"  llength="'+fact+'" id="funnel_fact_'+i+'"></div><div class="diagram_count">'+fact+'('+difference+')</div></div>\
            </div>'
    }
    diagram += '<div class="FSD" id="forecast">'+lang.prognosis+' = '+(data[l-1].prognosis||0)+' руб.</div>\
            <div class="FSD" id="supply">'+lang.payment+' = '+(data[l-1].payment||0)+' руб.</div>\
            <div class="FSD" id="debit">'+lang.debet+' = '+(data[l-1].debet||0)+' руб.</div>\
        </div>'
    waitForUpdate('[placefor="phases"]', left);
    waitForUpdate('[placefor="diagram"]', diagram);
    expandFunnelLines()
}
var build_funnel = function(data, process){
    //переделать в запись в элемент
    var left = '<div id="leftside"><ol placefor="phases"></ol></div>';
    var diagram = '<div id="center" placefor="diagram"></div>';
    var right = '<div id="rightside" plasefor="userlist"></div>';
    $('.clients').html('<div id="report1" trgt="funnel"><div id="content">'+left+diagram+right+'</div></div>');
    var fil_set = function(data, wtc){
        var html = '';
    var sum=0;
        for (var i in data){
        sum+=data[i].activity;
            waitForWrite('#activity_user_'+i+' .summ_percent', '   '+data[i].activity+'%');
        }
    waitForWrite('.employee#all .summ_percent', round_mod(sum/$('#currJSON').data('reg').length,2)+'%')
    }
    var build_fil = function(data, wtc){
        data = data || $('#currJSON').data().reg;
        console.log('called');
        var l = data.length; var html = '<div class="employee current_user" id="all" qty="'+l+'">'+lang.all+'<div class="summ_percent"></div></div>';
        for (var i=0; i<l;i++){
            html += '<div class="employee" id="activity_user_'+data[i].id+'">'+data[i].fio+'<div class="summ_percent">0%</div></div>';
        }
        waitForUpdate('#rightside', html);
        if (wtc=='success') $('#currJSON').data('reg', data);
    }
    getUserList(build_fil)
    getUsersForTable(rfl_set, 'crm/getFunnel', {process:0, date: date_string(false, true)})
    getUsersForTable(fil_set, 'crm/getActivity', {process:0, date: date_string(false, true)})
}
var expandFunnelLines = function(){
    var element = '.diagram_temp'
    var checkExist = setInterval(function() {
       if ($(element).length) {
        $(element).each(function(){
            var sp = $(this).attr('id').split('_');
            var val = +$(this).attr('llength');
            $(this).animate({'width': val*2}, 100);
        })
        clearInterval(checkExist);
       }
    }, 100);
}
var sale_table_head = function(stage){
    var html = '<div id="open_sale"><div id="content"><table><tr id="table_head">';    
    var wtf = (stage == 'open' || stage == 'mine_open') ? 'open_sales' : 'closed_sales';
    var loc = clone(localization[wtf]); delete loc.id; delete loc.id_sale;
    for (var i in loc){
        html+='<td class="'+i+'">'+loc[i]+'</td>'
    }
    return html+'</tr></table></div></div>';
}
var sales_hidden_row_head = function(){
    var html = '<tr class="hidden_table_head">'
    var loc = localization.sale_history;
    for (var i in loc){
        html += '<td class="'+i+'">'+loc[i]+'</td>';
    }
    return html+'</tr>'
}

var fast_message_popup = function(people){
    var men='';
    for (var i in people){
        man = people[i]
        men += '<div class="ava-small"><img width="30px" height="30px" src="include/images/userpics/'+man.img+'" title = "'+man.fio+'"><div class="hidden reseiver_id">'+man.id+'</div></div>'
    }
    return '<div id="popup_fast_message" class="hidden">\
        <div id="fast_message_title">\
            <div id="fast_message_title_name">'+lang.fast_messages+'</div>\
            <div class="icon-x"></div>\
        </div>\
        <div id="fast_message_photo_container">\
            '+lang.recepient+':\
            <div id="fast_message_photo">'+men+'</div>\
        </div>\
        <div>\
            '+lang.message+':\
            <textarea name="fast_message" id="fast_message_field" class="upper_popup"></textarea>\
        </div>\
        <div class="fast_message_button upper_popup">\
            '+lang.send+'\
        </div>\
    </div>'
}
var sale_table_generator = function(data, stage){
    var send = (stage == 'open') ? localization.open_sales : localization.closed_sales;
    var html = '<div id="open_sale"><div id="content"><div id="scroll"><table>'
    for (var key in data){
        html += sale_row_generator(data[key], 'table_row', send)
    }
    return html + '</table></div></div></div>'
}
var sale_row_generator = function(data, c, sep){
    var html = '<tr class="'+c+'">'
    for (var key in sep){
        var text = data[key] || ''//'Нет данных'
        html += '<td class="'+key+'">'+getTextFrom(data, key, lang.nodata)+'</td>'
    }
    return html + '</tr>'
}
var sale_hidden_table = function(data, id){
    var sep = localization.plan_by_sale;
    var html = '<tr id="hidden_table_'+id+'"><td colspan="9" class="movable hidden"><table class="sale_table_hidden">' + sales_hidden_row_head();
    for (var key in data){
        html += sale_row_generator(data[key], 'hidden_table_row', sep)
    }
    return html + '</table></tr>'
}
var sales_by_stage = function(stage, id){
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url : '/index.php/crm/getSalesForTable',
        data : {
            stage : stage
        },
        success: function(data){
            $('.clients').html(sale_table_generator(data, stage));
            console.debug(data)
        },
        error: function(e){
            console.debug(e)
            $('.clients').html(lang.nodata + '<br>' + e.join(' ') + '')
        }
    })
}
var build_inc_msgs = function(arr){
    var msgs = ''
    for (var i in arr){
        ms = arr[i];
        msgs += '<div id="fast_message_photo">\
            <div id="fast_message_photo">\
                <div class="ava-small"><img src="include/images/userpics/'+ms.img+'" width="30" height="30" title="'+ms.fio+'"><div class="hidden id_author">'+ms.id_author+'</div></div>\
            <div class="received_text">'+ms.text+'</div></div>\
        </div>'
    }
    return msgs
}
var fast_received = function(arr){    
    return '<div class="hidden" id="fast_received">\
        <div id="fast_message_title">\
            <div id="fast_message_title_name">'+lang.fast_message+':</div>\
            <div class="icon-x"></div>\
        </div><div class="fastMessages">'+build_inc_msgs(arr)+'</div><div>\
            <textarea name="fast_message" id="fast_message_field" class="downer_popup">'+''+'</textarea>\
        </div>\
        <div class="fast_message_button downer_popup">\
            '+lang.answer+'\
        </div>\
    </div>'
}
var extendToTwo = function(number){
    return ((number<9)?'0':'')+number;
}
var date_string = function(today, month, date) {
        var days = lang.days;
        if (date) date = makeKosherDate(date)
        var months = lang.months_icn;
        var currentTime = (typeof(date)=='string') ? new Date(date) : new Date();
        var currentDay = days[currentTime.getDay()];
        var currentDate = currentTime.getDate();
        var month = currentTime.getMonth()+1;
        var day = extendToTwo(currentDate);
        var currentMonth = (today==true||month==true) ? extendToTwo(month) : months[currentTime.getMonth()];
        var currentYear = currentTime.getFullYear();
        if (today==true) return day + '-' + currentMonth + '-' + currentYear;
        if (month==true) return currentMonth + '-' + currentYear;
        if (typeof(date)=='string') return currentDate + ' ' + currentMonth + ' ' + currentYear;
        return currentDay + ', ' + currentDate + ' ' + currentMonth + ' ' + currentYear
    }
var clock = function(current) {
        var currentTime = new Date();
        var currentHours = currentTime.getHours();
        var currentMinutes = currentTime.getMinutes();
        var currentSeconds = currentTime.getSeconds();
        currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
        currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
        if (current) return currentHours + ':' + currentMinutes;
        $('#time').text(currentHours + ':' + currentMinutes);
    }
Date.prototype.getWeek = function(){
    var day_miliseconds = 86400000,
        onejan = new Date(this.getFullYear(),0,1,0,0,0),
        onejan_day = (onejan.getDay()==0) ? 7 : onejan.getDay(),
        days_for_next_monday = (8-onejan_day),
        onejan_next_monday_time = onejan.getTime() + (days_for_next_monday * day_miliseconds),
        // If one jan is not a monday, get the first monday of the year
        first_monday_year_time = (onejan_day>1) ? onejan_next_monday_time : onejan.getTime(),
        this_date = new Date(this.getFullYear(), this.getMonth(),this.getDate(),0,0,0),// This at 00:00:00
        this_time = this_date.getTime(),
        days_from_first_monday = Math.round(((this_time - first_monday_year_time) / day_miliseconds));

    var first_monday_year = new Date(first_monday_year_time);
    return (days_from_first_monday>=0 && days_from_first_monday<364) ? Math.ceil((days_from_first_monday+1)/7) : 52;
}
var p_maker = function(text, id, attr) {
        return (attr != '') ? '<p id="foundcompany_'+id+'" class="popup-id ' + attr + '">' + text + '</p>' : '<p id="foundcompany_'+id+'"  class="popup-id">' + text + '</p>'
    }
var aed_buttons = function(whos){
        return '<div class="wrapper_table" id="'+whos+'_record_add">\
                <div class="blue_text">'+lang.add+'\
                </div>\
            </div>\
            <div class="wrapper_table" id="'+whos+'_record_edit">\
                <div class="blue_text">'+lang.edit+'\
                </div>\
            </div>\
            <div class="wrapper_table" id="'+whos+'_record_delete">\
                <div class="blue_text">'+lang.delete+'\
                </div>\
            </div>'
        }
        
var n_d_t = function(text, show_buttons) {
        return '<div class="companyinfo">\
        <div id="contentTitle">\
            <div id="title">' + text + '</div>\
            '+((show_buttons!=false)?'<div class="wrapper_user" id="record_add">\
                <div class="blue_text">'+lang.add+'\
                </div>\
            </div>\
            <div class="wrapper_user" id="record_edit">\
                <div class="blue_text">'+lang.edit+'\
                </div>\
            </div>\
            <div class="wrapper_user" id="record_delete">\
                <div class="blue_text">'+lang.delete+'\
                </div>\
            </div>\
            <div class="wrapper_user hidden" id="time_60">\
                <div class="blue_text">60\
                </div>\
            </div>\
            <div class="wrapper_user hidden" id="time_30">\
                <div class="blue_text">30\
                </div>\
            </div>\
            <div class="wrapper_user hidden" id="time_15">\
                <div class="blue_text">15\
                </div>\
            </div>\
            <div class="wrapper_user hidden" id="time_5">\
                <div class="blue_text">5\
                </div>\
            </div>\
            <div class="wrapper_user hidden" id="time_1">\
                <div class="blue_text">1\
                </div>\
            </div>\
            ':'')+'<div class="dt" id="time">\
            </div>\
            <div class="dt" id="date">\
            </div>\
        </div>';
    }

    //var header_element = "<td class=" + /*phase +*/ "><div class='caption head'><div class='text'>" +/* mase + */"</div><div class='image'>&nbsp;<div class='icon-triangle-gray'></div></div></div></td>";

var sales_row = function(data){
    var html = '<tr>'
    for (var i = 0; i < 9; i++) {
        html += '<td>'+ data[i] +'</td>'
    };
    return html + '</tr>'
}
var sales_row_hidden = function(data){
    var html = '<tr><td colspan="9" class="movable hidden"><table>' + sales_hidden_row_head()
    for (var i = 0; i < 7; i++) {
        html += '<tr>'
        for (var j = 0; j < 7; j++) {
            html += '<td>'+ data[i][j] +'</td>'
        }
        html += '</tr>'
    }
    return html + '</table></tr></tr>'
}
var editUnitHead = function(text) {
        return '<div id="fill_unit_title">\
            <div id="fill_title">' + text + '</div>\
            <div class="save_button">'+lang.save+'</div>\
        </div>';
    }
var viewUnitHead = function(text) {
        return '<div id="fill_unit_title">\
            <div id="fill_title">' + text + '</div>\
            <div class="edit_button">'+lang.edit+'</div>\
        </div>';
    }
var star = function(fillnes, number) {
        if (number) fillnes = fillnes + "' id='star_" + number
        return "<div class='icon-star-" + fillnes + "'></div>"
    }
var status_pic = function(number, marked) {
        var string = '';
        for (var i = 0; i < number; i++) {
            string += (marked) ? star('filled', i + 1) : star('filled');
        }
        for (var i = number; i < 5; i++) {
            string += (marked) ? star('empty', i + 1) : star('empty');
        }
        return string;
    };
var replace_status_num = function() {
        $('.status').each(function() {
            num = parseInt($(this).find('.caption').html() || '0');
            $(this).find('.caption').html(status_pic(num));
        })
        $('#status').each(function() {
            num = parseInt($(this).html() || '0');
            $(this).html(status_pic(num));
        })
    }
    //add a proper document file types
    //application/vnd.oasis.opendocument.text|application/vnd.oasis.opendocument.spreadsheet|application/vnd.oasis.opendocument.presentation|application/vnd.oasis.opendocument.graphics|application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|application/vnd.openxmlformats-officedocument.presentationml.presentation|application/vnd.openxmlformats-officedocument.wordprocessingml.document

var file_upl_block = function(text){
    var add = (text == lang.delete) ? 'style="margin-left:-140px;"' : '';
    return '<div id="file_del_button"><span id="deleteDocument">'+text+'</span>\
             <div class = "hidden" id="UPI"></div>\
             <form method="post" enctype="multipart/form-data" action="/index.php/crm/att_upload" target="upload_frame">\
                    <input type="file" name="attachment" id="upl_document" size="1" class = "documentInput"  accept='+accept_doc+add+'>\
                    <input type="submit" name="upl_document_submit" id="upl_document_submit" class="hidden">\
                </form></div>';
    }
var block_with_clip = function(arr, inactive) {       
        var add = '';
        arr.text = (!'text' in arr || typeof(arr.text)!='string' || arr.text == lang.nodata)? '' : arr.text
        if (arr.text!='' && inactive){
            add = '<div id="att_href"><a href = "/include/attachments/files/'+arr.text+'">'+arr.text+'</a></div>';
        } else if (arr.text=='' && inactive){
            add = '';
        } else if (arr.text=='' && !inactive){
            add = '<div id="att_href" class="hidden_ava"><a href = "/include/attachments/files/'+arr.text+'">'+arr.text+'</a></div>' + file_upl_block(lang.attach);
        } else if (arr.text!='' && !inactive){
            add = '<div id="att_href"><a href = "/include/attachments/files/'+arr.text+'">'+arr.text+'</a></div>' + file_upl_block(lang.delete);
        }
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">'+ arr.caption +'</div>\
            <div class="editAttachment">\
                 '+add+'\
                </div>\
            </div>'
    }
var block_with_passportinfo = function(arr, i){
    var add = '';
    add = (i) ?'':'<div class="passportfields_add_button">'+lang.add+'</div>';
    return '<div class="editIdSelInactive">\
            <div class="editId">'+arr.caption+'</div>\
            '+add+'\
        </div>';
}
var block_with_two_fields = function(arr){
    var tag = arr.id
    var num = arr.num || 0;
    return block_with_price({'id' : tag+'_'+num,      'text': '', 'caption' : additfields[tag]}) 
         + block_with_date ({'id' : tag+'_date_'+num, 'text': '', 'caption' : additfields[tag+'_date']});
}
//block of blocks
var upl_block = function(text){
    add = text == 'Удалить' ? 'style="margin-left:-140px;"' : '';
    return '<div id="del_button"><span id="deletePhoto">'+text+'</span>\
             <div class = "hidden" id="UPI"></div>\
             <form method="post" enctype="multipart/form-data" action="/index.php/crm/img_upload" target="upload_frame">\
                    <input type="file" name="userpic" id="logo_or_pic" size="1" class = "photoInput"  accept="image/jpeg,image/png,image/gif" '+add+'>\
                    <input type="submit" name="logo_or_pic_submit" id="logo_or_pic_submit" class="hidden">\
                </form></div>';
    }
var block_with_photo = function(arr, inactive) {        
        var add = '';
        arr.text = (!'text' in arr || typeof(arr.text)!='string' || arr.text == lang.nodata)? '' : arr.text
        if (arr.id == 'image_path') arr.text = 'include/images/userpics/'+arr.text;
        if (arr.text!='' && inactive){
            add = '<div id="fill_ava"><img id="imageHolder" src="' + arr.text + '"></div>';
        } else if (arr.text=='' && inactive){
            add = '';
        } else if (arr.text=='' && !inactive){
            add = '<div id="fill_ava" class="hidden_ava"><img id="imageHolder" src="' + arr.text + '"></div>' + upl_block(lang.add);
        } else if (arr.text!='' && !inactive){
            add = '<div id="fill_ava"><img id="imageHolder" src="' + arr.text + '"></div>' + upl_block(lang.delete);
        }
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">'+ arr.caption +'</div>\
            <div class="editPhoto">\
                 '+add+'\
                </div>\
            </div>'
    }
var block_with_href = function(arr, inactive) {
        var html = ''; //все кроме кнопки
        var button = ''; //сама кнопка
        var text = '';
        var add = inactive || '';
        pr = (arr.id.split('_')[1] == 'pr') ? 'prognosis' : 'payment';
        prl ={'add_pr':'prognosis', 'add_pa': 'payment'}[arr.id];
        text = getTextFrom(arr, text, lang.nodata);
        var count = countPr(arr.list, prl);        
        exc = {caption: lang[pr], 'text' : count, id: prl, list: arr.list};
        if (inactive){
            html = block_with_paorpr(arr.list, prl, 'disabled')//block_with_textinput(exc, 'disabled');
            button = '';
        } else {
            html = block_with_paorpr(arr.list, prl);
            button = '<div class="editIdSelInactive">\
            <div class="editId"></div>\
            <div class="fields_add_button '+add+'" id="'+arr.id+'">'+lang.add+' '+lang[pr].toLowerCase()+'</div>\
            </div>';
        }
        return html + button;
    }
var countPr = function(arr, id){
    var count = 0;
    if (arr){
        var len = arr.length; 
        for (var i=0; i<len; i++){
            count += parseInt(arr[i][id]);
        }
    }
    return count.toString();
}
var block_with_phase = function(arr, inactive) {
        var html = ''; //все кроме кнопки
        var button = ''; //сама кнопка
        var text = '';
        var add = inactive || '';
        pr = 'phase';
        console.log(arr)
        if (typeof(arr.list)!='Array')
        text = getTextFrom(arr, text, lang.nodata);
        //var count = countPr(arr.list, prl);        
        //exc = {caption: lang[pr], 'text' : count, id: prl, list: arr.list};
        if (inactive){
            html = accsesory_phase_block(arr.list, arr.process)//block_with_textinput(exc, 'disabled');
            button = '';
        } else {
            html = accsesory_phase_block(arr.list, arr.process);
            button = '<div class="editIdSelInactive">\
            <div class="editId"></div>\
            <div process="'+arr.process+'" class="phase_add_button '+add+'" id="'+arr.id+'">'+lang.next+' '+lang[pr].toLowerCase()+'</div>\
            </div>';
        }
        return html + button;
    }
var accsesory_phase_block = function(list, pr){
    if (!list || !pr) return '';
    var html = '';
    var l = list.length;
    for (var i=0; i<l; i++){
        var arr = list[i];
        var text = phase_by_type[pr][arr.phase];
        if (!text) continue;
        html += block_with_textarea({
            id: 'phase_'+arr.phase,
            text: phase_by_type[pr][arr.phase],
            caption : arr.date
        }, 'disabled')
    }
    return html;
}
var capsFirstLetter = function(string){
    var all = string.split('');
    var c = all[0].toUpperCase();
    delete all[0];
    return c+all.join('');
}
var block_with_select = function(arr, inactive) {
        add = inactive || '';
        var temp = $('#username .name').html().split(' ')
        var user = temp[1] + ' ' +temp[0].split('')[0] + '.'; delete temp;
        arr.list = (arr.id in field_values) ? field_values[arr.id] : arr.list; //проверка на существование в заданных полях
        if (inactive && arr.text=='country'){
            return block_with_textarea(arr);
        }
        if (arr.id == 'responsibility' || arr.id == 'performer'){
            var handy = function(data, wtc){
                data = data || $('#currJSON').data().reg;
                var temp = {'free':lang.noresponsibility}
                    for (var key in data){
                        temp[data[key].id] = data[key].fio;
                    }
                    arr.list = temp
                if (wtc=='success') $('#currJSON').data('reg',data);
            }
            getUserList(handy, undefined,undefined,false)
        }
        if (arr.id=='id_contact'){
            $.ajax({
                type: 'POST',
                url: '/index.php/crm/getContactsForPlan',
                data: {
                    'id_customer' : $('#company_id').html() || $('.editcompanyId').html()
                },
                async: false,
                success: function(data){
                    var temp = {'0':lang.nocontact}
                    for (var key in data){
                        temp[data[key].id] = data[key].fullname;
                    }
                    arr.list = temp
                },
                dataType : 'json'
            });
        }
        if (arr.id=='sale_name'){
            var target_id = arr.id;
            var target_text = arr.text;
            arr.list= new Array();
            $.ajax({
                type: 'POST',
                url: '/index.php/crm/getSalesForPlan',
                data: {
                    'id_customer' : $('#company_id').html() || $('.editcompanyId').html()
                },
                // async: false,
                success: function(data){
                    // var temp = {'0':lang.nosale}
                    // for (var key in data){
                    //     temp[data[key].id] = data[key].name_sale;
                    // }
                    // arr.list = temp
                    // var temp = {'0':lang.nosale}
                    var options = '<option value="0" '+((target_text == '0') ? 'selected="selected"' : '')+'>'+lang.nosale+'</option>' 
                    for (var key in data){
                        var k = data[key].id
                        var v = data[key].name_sale;
                        options += '<option value="' + k +'" '+((target_text == k || target_text == v) ? 'selected="selected"' : '')+'>' + v + '</option>' 
                    }
                    var select = $('.editIdSel#'+target_id+' select');
                    select.html(/*select.html()+*/options)
                },
                dataType : 'json'
            });
        }
        ret = '<div class="editIdSel" id = "' + arr.id + '"><div class="editId">' + arr.caption + ':</div>' + '<div class="select_wrapper"><select class="editSel" '+add+'>'//<option selected="selected">' + arr.text + '</option>';
        if (arr.list instanceof Array){
            for (var k in arr.list) {
                var curr = arr.list[k];
                ret += '<option value="' + curr.value +'" '+((wetherValOrKey(arr.text, curr.value, curr.label) || wetherValOrKey(user, curr.value, curr.label)) ? 'selected="selected"' : '')+'>' + curr.label + '</option>';
            }
        } else if (arr.list) {
            for (var k in arr.list) {
                ret += '<option value="' + k +'" '+((wetherValOrKey(arr.text, k, arr.list[k])|| wetherValOrKey(user, k, arr.list[k])) ? 'selected="selected"' : '')+'>' + arr.list[k] + '</option>';
            }
        }
        if (add=='disabled') ret+='</select></div></div>'//<div class="icon-slide-down"></div>
        else ret += '</select></div></div>'
        return ret;
    }
var wetherValOrKey = function(string, comp1, comp2){
    return (string == comp1 || string == comp2) ? true : false;
}
var block_with_textinput = function(arr, inactive) {
        var add = inactive || '';
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">' + arr.caption + ':</div>\
            <input type="text" class="editNum" ' + add + ' value = "' + arr.text.replace('"','&quot;') + '"><div class="hidden edit'+arr.id+'Id"></div>\
        </div>'
    }
var block_with_textarea = function(arr, inactive) {
        var add = inactive || '';
        var rows = add ? '1' : '4';
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">' + arr.caption + '</div>\
            <textarea rows="'+rows+'"  cols="8" name="text" class="editArea" ' + add + '>' + arr.text + '</textarea>\
        </div>'
    }
var block_with_split = function() {
        return '<div class="editIdSel withSP">\
            <div class="editSplit borderBottom"></div>\
            <div class="editSplit"></div>\
        </div>'
    }
var block_with_hidden = function(arr) {
        return '<div class="editIdSel hidden" id = "' + arr.id + '">\
            <div class="editId">' + arr.caption + ':</div>\
            <input type="text" class="editNum" value = "' + arr.text + '">\
        </div>'
    }
var block_with_status = function(arr, inactive) {
        var add = inactive || '';
        arr.text = arr.text || '0';
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">' + arr.caption + ':</div>\
            <div class="wrapper_stars '+ add +'">' + status_pic(parseInt(arr.text), true) + '</div>\
            <input type="text" class="hidden" value="'+ arr.text +'">\
        </div>'
    }
var block_with_price = function(arr, inactive) {
        var add = inactive || '';
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">' + arr.caption + ':</div>\
            <input type="text" class="price" value="'+arr.text+'" '+add+'>\
            <div class="rub'+add+'">руб.</div>\
        </div>'
    }
var block_with_gender = function(arr) {
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">'+ arr.caption +'</div>\
            <div class="editSel" id="gender">\
            <input type="radio" name="sex" id="sex_m" checked>\
            <label for="sex_m"><div class="sex_id">'+lang.sex_m+'</div>\
            </label>\
            <input type="radio" name="sex" id="sex_w">\
            <label for="sex_w"><div class="sex_id">'+lang.sex_w+'</div>\
            </label>\
        </div>\
        </div>'
    }
var block_with_json = function(arr, inactive){
    return '<div class="editIdSel hidden" id = "' + arr.id + '">\
            <div class="editId">' + arr.caption + ':</div>\
            <div class="editJSON" class="hidden">'+arr.text + '</div>\
        </div>'
}
var block_with_date = function(arr, inactive) {
        add = inactive || '';        
        if (arr.id == 'date_registration' && arr.text=='' && !add) {arr.text = date_string(true); add = 'disabled';}
        return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">' + arr.caption + ':</div>\
            <input type="text" class="editDate" value = "' + arr.text + '" id = "datepicker_' + arr.id + '" '+add+'>\
        </div>'
    }
var block_with_time = function(arr, inactive){
        add = inactive || '';
        ret =   '<div class="editIdSel rightTime" id = "' + arr.id + 
                '"><input class="editTime" id = "timepicker_' + arr.id + 
                '" value="'+arr.text+'"'+ add +'></div>'
        return ret;
}
var block_with_paorpr = function(arr, tag, inactive){ //list
    ret = '';
    for (var k in arr) {
        //console.log(k)
        ret += block_with_hidden({
            'id' : 'id_'+tag+'_'+k, 
            'text' : arr[k].id, 
            'caption' : ''
        }, inactive)
        ret += block_with_price({
            'id' : tag+'_'+k, 
            'text': arr[k][tag], 
            'caption' : additfields[tag]
        }, inactive)
        ret += block_with_date({
            'id' : tag+'_date_'+k, 
            'text': arr[k][tag+'_date'], 
            'caption' : additfields[tag+'_date']
        }, inactive)
    }
    return ret;
}
var block_with_order = function(arr, inactive){
    var html = '';
    var text = '';
    var add = inactive || '';
    //console.log(!arr.text|| typeof(arr.text)!='string')
    if (!arr.text|| typeof(arr.text)!='string') {
        text = lang.nodata;
    } else {
        text = arr.text;
    }
    exc = {caption: lang.order, 'text' : text, id: 'order'};
    html = block_with_textinput(exc, 'disabled')
    if (inactive){
        return html;
    } else {
        if (text==lang.nodata) html = '';
        return html + '<div class="editIdSelInactive">\
            <div class="editId"></div>\
            <div class="order_add_button '+add+'">'+lang.add+' '+lang.order.toLowerCase()+'</div>\
        </div>';
    }
}
var block_with_tree = function(arr, add, prefix){
    var tree = Array();
    var url = 'crm/getTreeNodes'
    if (prefix=='segments') {
        url = 'crm/getSectorNodes'
    }
    else prefix = 'products';
    $.ajax({
            url: '/index.php/'+url,
            type: 'post',
            dataType: 'json',
            async: false,
            success: function(data){
                tree = data;
                if ($('#st_'+prefix).length) $('#st_'+prefix).data('tree', cloneArray(tree));
            }
        })
    return '<div class="nonEditableSel"><div class="tree">' + ul(tree) + '</div></div>'
    
}
var block_with_bill = function(arr, add){
    return '<div class="editIdSelInactive">\
            <div class="editId">'+arr.caption+'</div>\
            <div class="make_order_bill">'+lang.form+'</div>\
        </div>';
}
var block_with_radio = function(arr, inactive){
    var add = inactive || '';
    if (!arr.text) arr.text = '0';
    var no = (arr.text == '0')?'checked':'';
    var yes = (arr.text == '1')?'checked':'';
    return '<div class="editIdSel" id = "' + arr.id + '">\
            <div class="editId">'+ arr.caption +'</div>\
            <div class="editSel withRadio" id="'+arr.id+'">\
            <input type="radio" name="'+arr.id+'" id="'+arr.id+'_no" value="0" '+no+' '+add+'>\
            <label for="'+arr.id+'_no"><div class="'+arr.id+'_id" col="'+add+'">'+lang.no+'</div>\
            </label>\
            <input type="radio" name="'+arr.id+'" id="'+arr.id+'_yes" value="1" '+yes+' '+add+'>\
            <label for="'+arr.id+'_yes"><div class="'+arr.id+'_id" col="'+add+'">'+lang.yes+'</div>\
            </label>\
        </div>\
        </div>'
}
var getLastPart = function(id){
    var possible = {
        'two_fields' : ['prognisis', 'payment'],
        'price' : ['debt', 'cost'],
        'textarea' : ['description', 'task', 'result', 'about'],
        'clip' : ['attachment', 'dogovor', 'schet', 'act'],
        'hidden' : ['customer_id','id_product','id_document' ,'id', 'id_customer', 'id_bank_details', 'id_contact_info', 'id_address', 'id_work_place', 'id_passport', 'id_registred_company', 'id_sale', 'price_o'],
        'select' : ['action','sale_name','id_contact','type','responsibility','group', 'product', 'event', 'role', 'route', 'gender','work_mode_c','work_mode', 'mode', 'performer', 'type_sale', /*'phase',*/ 'failure_cause', 'dinner_time_c' , 'dinner_time', 'country'] ,
        'date' : ['date_registration','start_deal', 'end_deal', 'date_shipment', 'registration_date', 'date', 'birthday', 'reception_day', 'fired_day'],
        'status' : ['status'],
        'photo' : ['photo', 'image_path'],
        'gender' : ['gender'],
        'href' : ['add_pr', 'add_pa'],
        'order' : ['order'],
        'tree' : ['tree'],
        'passportinfo' : ['passport_с'],
        'bill' : ['bill'],
        'phase' : ['phase'],
        'radio' : ['alert', 'failure'],
        'json' : ['description_o'],
        'time' : ['time','time_start', 'time_end']
    }
    for (var p in possible){
        if ($.inArray(id, possible[p]) > -1) return p
    }
    if (id.split('_')[0] == 'split') return 'split'
    else return 'textinput'
}
var architector = function(arr, add) {
        if (!arr.text) arr.text = '';
        return window['block_with_'+getLastPart(arr.id)](arr, add);
    }

var span = function(text, id, level){
    return '<span id="category_tree_'+id+'">'+text+'</span>'
}
var ul = function(arr){
    if (!arr[0]) return ''
    else{
        var html = '<ul level="'+arr[0].cat_level+'">';
        //arr = cloneArray(arr);
        for (var i in arr){
            var el = arr[i];
            var childs = getChilds(el, arr);
            arr = cleanDuplicates(arr, childs)
            html += '<li level="'+el.cat_level+'" rk="'+el.cat_right+'">' + span(el.name || el.sector, el.id)
            html += ul(childs)
            html += '</li>'
        }
        return html + '</ul>'
    }
}
var cleanDuplicates = function(from, what){
    var length = from.length;
    for (var i = 0; i < length; i++)
        if ($.inArray(from[i], what) > -1)
            delete from[i]
    return from;
}
var pi = function(arr){//получить числовые значения полей cat_left, cat_right, cat_level
    temp = new Object();
    for (var i in arr)
        if (i!='name' || i!='sector') temp[i] = parseInt(arr[i])
        else temp[i] = arr[i]
    return temp
}
var getChilds = function(trgt, arr){
    var childs = Array();
    var length = arr.length;
    for (var i = 0; i < length; i++){
        var curr = pi(arr[i]);//сравнивать строки не так весело, как инт
        var under = (curr['cat_level'] > trgt['cat_level']) ;
        var l_in =  (curr['cat_left' ] > trgt['cat_left' ]);
        var r_in =  (curr['cat_right'] < trgt['cat_right']);
        if (under && l_in && r_in) {childs.push(arr[i]); delete arr[i];}
    }
    return childs;
}
var dummy = {
    'event': '',
    'plan': '',
    'company': '',
    'place': ''
}
var empty = function(obj) {
        return obj || ''
    }
var minutes_array = function(parts) {
        a = [];
        for (var i = 1; i <= parts; i++) {
            t = (60 / parts) * i
            a[i] = (t < 10) ? '0' + t : '' + t;
        }
        return a;
    }
var day_hour_line = function(time, ch, add, minute, hour){
    var html = '<tr class="minutes">';
    var loc = localization.plan_day;
    var dark = (add) ? ' darkField': '';
    if (hour) html += '<td class="time_hour" rowspan="' + 60 / time + '">' + hour + '</td>';
    html += ('<td algn = "th_1" class="time_min" hr="'+ch+'">' + minute + '</td><td class="'+dark+'" id="day_'+ch+'_'+minute+'"><table class="allcurrent">');
    return html + '</table></td></tr>';

}
var week_hour_line = function(time, ch, add, minute, hour){    
    var html = '<tr class="minutes week">';
    var loc = clone(localization.plan_week); 
    var dark = (add) ? ' darkField': '';
    if (hour) html += '<td class="time_hour" rowspan="' + 60 / time + '">' + hour + '</td>'; 
    delete(loc.hour);
    html += ('<td algn = "th_1" class="time_min" hr="'+ch+'">' + minute + '</td>');
    delete(loc.minute);
    for (var key in loc){
        if (key == 'saturday' || key == 'sunday') dark = ' darkField';
        html += '<td class="weekday '+key+dark+'" id="'+weekdays[key]+'_'+ch+'_'+minute+'"><table class="allcurrent">';
        // try{
        //     var cur = data[weekdays[key]][parseInt(ch)][parseInt(minute)]
        // } catch(e){ var cur = false;}
        // if(cur){
        //     var len = cur.length;
        //     for (var i=0;i<len;i++){
        //         html += '<tr class="inhere_'+i+'"><td class="id">'+cur[i].id+'</td><td class="customer">'+cur[i].customer+'</td></tr>';            
        //     }
        // }
        html+='</table></td>';
    }
    return html + '</tr>';
}
var plan_line = function(number, is_dark, time, period) {
        if (!time) time = 15;
        dark_class = (is_dark == 1) ? 'class="darkField"' : '';
        number = (number < 10) ? '0' + number : '' + number;
        currHour = number;
        minutes = minutes_array(60 / time);
        ret = ''
        var func = (period=='week') ? 'week_hour_line' : 'day_hour_line';
        for (i in minutes) {
            ret += window[func](time, currHour, dark_class, minutes[i], number)
            number ='';
        }
        return ret;
    }
var timetable_generate = function(time, period) {
        ret = '<table class="timetable">';
        start = parseInt($('#workday_start').html());
        end = parseInt($('#workday_end').html());
        for (var i = 0; i < 24; i++) {
            dark = (i >= start && i <= end) ? 0 : 1;
            ret += plan_line(i, dark, time, period);
        };
        return ret + '</table>';
    }
var timetable_head = function(){
        var html = '<table class="timetable"><tr id="tableHead">';
        var loc = clone(localization.plan_day); var t=1; delete loc.hour;
        for (var key in loc){
            html += '<td class="th_'+ t++ +' '+key+'">'+loc[key]+'</td>';
        }            
        return html + '</tr></table>'
    }
var weektable_head = function(){
        var html = '<table class="timetable"><tr id="tableHead">';
        var loc = clone(localization.plan_week); var t=1; delete loc.hour;
        for (var key in loc){
            html += '<td class="week_day th_'+ ((t > 6) ? 6 : t) +' '+key+'"><div class="timetable_th">'+loc[key]+'</div></td>';
            t+=5;
        }            
        return html + '</tr></table>'
    }
var av_tr = function(came, fields, tag, attr, force_edit){
    var tr = '<tr '+attr+'>';
    for (var i in came){
        if (typeof(fields[i])=='string'){
            c = (force_edit)?(i!='stored'&&i!='available')?'contenteditable':'':(i=='quantity' || i == 'discount' || i=='total_sum') ? 'contenteditable': ''
            id = ' class = "'+i+' '+((i=='product')?"first_row" : "")+'" '+c 
            tr += '<'+tag+id+'>'+came[i]+'</'+tag+'>';
        }
    }
    return tr +'</tr>'
}
var av_order = function(data,loc_name, head_class, force_edit){
    loc = localization[loc_name]
    var html = av_tr(loc, loc,'th' ,'class="'+head_class+'"');
    for (var i in data){
        html += av_tr(data[i],loc,'td', '', force_edit);
    }
    delete data
    return html
}
var diff = function(a,b){
    return (a-b >= 0) ? a-b : 0
}
var st_lmenu = function(){
    var data = localization.st_parts;
    var generatedButtons = '';
    for (var key in data) generatedButtons += '<div class="settings_menu_item" id="st_'+key+'">'+data[key]+'</div>'
    return '<div id="settings_menu">'+generatedButtons+'</div><div class="settings_common_rb"></div>'
}
var st_products = function(arr){
    $('.v_h_a').html('<div class="settings_products_block">\
    <div id="buttons_to_categories">\
            <div class="contentTitle">'+lang.categories+'</div>\
            <div class="categories_add_button">'+lang.add+'</div>\
            <div class="categories_edit_button">'+lang.edit+'</div>\
            <div class="categories_delete_button">'+lang.delete+'</div>\
    </div>\
    <div id="buttons_to_products">\
            <div class="contentTitle">'+lang.products+'</div>\
            <div class="products_delete_button">'+lang.delete+'</div>\
    </div>\
    </div>')
    return '<div id="settings_products">\
                <div class="settings_products_block" id="tree_edit_block">\
                    <div class="settings_products_tree">\
                    '+block_with_tree()+'</div>\
                </div>\
                <div class="settings_products_block" id="settings_product_block_id">\
                    <div class="settings_products_webinar">\
                        <table><tbody>'+products_dum_row_head + products_dum_row+'</tbody></table>\
                    </div>\
                </div>\
            </div>'
}
var st_segments = function(arr){
    $('.v_h_a').html('<div class="settings_products_block">\
    <div id="buttons_to_categories">\
            <div class="contentTitle">'+lang.segments+'</div>\
            <div class="categories_add_button">'+lang.add+'</div>\
            <div class="categories_edit_button">'+lang.edit+'</div>\
            <div class="categories_delete_button">'+lang.delete+'</div>\
    </div>\
    </div>')
    return '<div id="settings_segments">\
                <div class="settings_products_block" id="tree_edit_block">\
                    <div class="settings_products_tree">\
                    '+block_with_tree({}, undefined, 'segments')+'</div>\
                </div>\
            </div>'
}
var st_users = function(arr){
    $('.v_h_a').html('<div class="settings_users_block">\
    <div id="buttons_to_users">\
            <div class="users_add_button">'+lang.add+'</div>\
            <div class="users_edit_button">'+lang.edit+'</div>\
            <div class="users_delete_button">'+lang.delete+'</div>\
    </div>')
    var generatedHead = '';
    for (var key in localization.usertable) generatedHead += '<th class="'+key+'">'+localization.usertable[key]+'</th>'
    getUsersForTable();
    return '<table id="users"><tr class="usertable_head">'+generatedHead+'</tr></table>'
}
var waitForAppend = function(element, html, wtl){
    var checkExist = setInterval(function() {
       if ($(element).length) {
        if (!html && wtl) {
            a = $(element).find(wtl);
            $(element).html(a)
        } else $(element).append(html)
        clearInterval(checkExist);
       }
    }, 100);
}
var waitForUpdate = function(element, html){
    var checkExist = setInterval(function() {
       if ($(element).length) {
        $(element).html(html)
        clearInterval(checkExist);
       }
    }, 100);
}

var waitForWrite = function(element, html){
    var checkExist = setInterval(function() {
       if ($(element).length) {
        $(element).text(html)
        clearInterval(checkExist);
       }
    }, 100);
}
var getUsersForTable = function(overfunction, overurl, send){
    var users = $('#goodcrm_logo .caption').data().users;
    var defalutfunction = function(data, wtc){
            data = data || $('#goodcrm_logo .caption').data().users
            var len = data.length;
            var html = '';
            for (var i=0; i<len; i++){
                html += '<tr>';
                for (var key in localization.usertable){
                    html += (key=='image_path') ? '<td class="image_path"><img src="include/images/userpics/'+data[i][key]+'" ></td>' : '<td class="'+key+'">'+getTextFrom(data[i], key, "")+'</td>';
                }
                html += '</tr>';
            }
            waitForAppend('table#users', html)
            if (wtc=='success') $('#goodcrm_logo .caption').data('users', data);
        }
    if (!overurl && users){
        (overfunction || defalutfunction)()
    } else $.ajax({
        url: '/index.php/' + (overurl || 'crm/getUsersForTable'),
        type: 'POST',
        dataType: 'json',
        data: send,
        success: overfunction || defalutfunction
    })    
}
var st_paymentplan = function(arr){
    var head = '<tr>';
    var a = cloneArray(lang.months);
    var len = a.unshift(lang.employee);
    for (var i=0; i<len; i++){
        head += '<th>'+a[i]+'</th>'
    }
    head += '</tr>'
    var b = [0,1,2,3,4];
    var v = function(w){
        var q = ''; var l = w.length; var c = (new Date()).getFullYear();
        for (var i=0; i<l; i++){
            var year = c+w[i];
            q += '<div class="period'+(i==0 ? ' current_year' : '')+'" id="year_'+year+'">'+year+'</div>'
        }
        return q;
    }
    $('.v_h_a').html('<div id="content_title">\
            <div id="title_id">'+localization.st_parts.paymentplan +'</div>\
            '+v(b)+'\
        </div>')
    var handler = function(data, wtc){
        data = data || $('#currJSON').data().reg
        var len = data.length;
        var html = '';
        var year = $('.period.current_year').attr('id').split('_')[1];
        var p_1 = 13;
        for (var i=0; i<len; i++){
            html += '<tr class="payplan_tr">';
            for (var key =0; key<p_1; key++){
                html += '<td srv="'+data[i].id +'-'+(key<10 ? '0' : '')+key+'-'+year+'" '+(key==0 ? '' : 'own="payplan" contenteditable')+'>'+((key==0) ? data[i].fio : '')+'</td>';
            }
            html += '</tr>';
        }
        if (wtc=='success') $('#currJSON').data('reg', data);
        waitForAppend('.settings_paymentplan',html)
    }
    getUserList(handler)
    getUserList(ppNormaler, 'crm/getPlansOfPayment', {year : (new Date()).getFullYear()})
    return '<table class="settings_paymentplan">\
    '+head+'</table>'
}
var ppNormaler = function(data){
    var l = data.length; 
    var checkExist = setInterval(function() {
        if ($('td[srv]').length) {
            for (var i=0; i<l; i++){
                try{
                    $('td[srv="'+data[i].id+'-'+data[i].date+'"]').html(digitFormat(data[i].plan))
                } catch(e){}
            }
            clearInterval(checkExist);
        }
    }, 100);
}
var psNormaler = function(data){
    var l = data.length; 
    var checkExist = setInterval(function() {
        if ($('td[srv]').length) {
            for (var i=0; i<l; i++){
                try{
                    $('td[srv="'+data[i].id+'-'+data[i].date+'-'+data[i].process+'-'+data[i].phase+'"]').html(digitFormat(data[i].count))
                } catch(e){}
            }
            clearInterval(checkExist);
        }
    }, 100);
}
var build_select_block = function(arr, id, start, def){
    var l = arr.length;
    var html = '<select class="'+start+'_select" id="'+start+'_'+id+'">'
    for (var i=0; i<l;i++){
        html += '<option '+((i==def || arr[i] == def)?'selected="selected"':'')+' value="'+i+'">'+arr[i] + '</option>'
    }
    return html + '</select>'
}
var build_saleplan_head = function(num, head){
    var a = cloneArray(phase_by_type[num]);
    var len = a.unshift(lang.employee);
    for (var i=0; i<len; i++){
        head += '<th>'+a[i]+'</th>'
    }
    return head;
}
var st_saleplan = function(arr){
    var b = [0,1,2,3,4];
    var c = (new Date()).getFullYear();
    var m = (new Date()).getMonth();
    var v = function(w){
        var q = ''; var l = w.length; 
        for (var i=0; i<l; i++){
            w[i] = c+w[i];
        }
        q+= build_select_block(w, 'year', 'saleplan', c);
        q+= build_select_block(cloneArray(lang.months), 'month', 'saleplan', m)
        q+= build_select_block(cloneArray(field_values.type_sale), 'process', 'saleplan', 0)
        return q;
    }
    $('.v_h_a').html('<div id="content_title">\
            <div id="title_id">'+localization.st_parts.saleplan+'</div>\
            '+v(b)+'\
        </div>')
    var head = build_saleplan_head(0, '<tr id="saleplan_tablehead">') + '</tr>';
    getUserList(saleplanTableBuilder)
    getUserList(psNormaler, 'crm/getPlansOfSale', {date : ((m>9)?(m+1):'0'+(m+1))+'-'+c, process: 0})
    return '<table class="settings_saleplan">\
    '+head+'</table>'
}
var saleplanTableBuilder= function(data, wtc){
    data = data || $('#currJSON').data().reg
    var len = data.length;
    var html = '';
    var year = $('#saleplan_year option:selected').text();
    var month = parseInt($('#saleplan_month option:selected').val())+1;
    var process = $('#saleplan_process option:selected').val();
    var p_1 = cloneArray(phase_by_type[process]).length + 1;
    for (var i=0; i<len; i++){
        html += '<tr class="saleplan_tr">';
        for (var key =0; key<p_1; key++){
            html += '<td srv="'+data[i].id +'-'+(month<10 ? '0' : '')+month+'-'+year+'-'+process+'-'+(key-1)+'" '+(key==0 ? '' : 'own="saleplan" contenteditable')+'>'+
                    ((key==0) ? data[i].fio : '')+
                    '</td>';
        }
        html += '</tr>';
    }
    if (wtc=='success') $('#currJSON').data('reg',data)
    waitForAppend('.settings_saleplan',false, '#saleplan_tablehead')
    waitForAppend('.settings_saleplan',html)
}
var st_dictionaries = function(arr){
    return '<div id="settings_directory">\
    <div class="settings_directory_top_panel">\
        <div class="SD_top_panel_button">'+lang.denial+'</div>\
        <div class="SD_top_panel_button">'+lang.roles+'</div>\
        <div class="SD_top_panel_button">'+lang.segments+'</div>\
    </div>\
\
    <div class="settings_directory_select">\
        <div>\
        <select>\
            <option selected>'+lang.attraction+'</option>\
        </select>\
        </div>  \
        <div class="SD_select_edit">'+lang.edit+'</div>\
    </div>\
    <div class="directory_edit_table">'+lang.add+'</div>\
    <div class="directory_edit_table">'+lang.edit+'</div>\
    <div class="directory_edit_table">'+lang.delete+'</div>\
    <table id="settings_directory_table">\
        <tr>\
            <th>Сегмент 1</th>\
            <th>Сегмент 2</th>\
            <th>Сегмент 3</th>\
            <th>Сегмент 4</th>\
            <th>Сегмент 5</th>\
            <th>Сегмент 6</th>\
        </tr>\
        <tr>\
            <td></td>\
            <td></td>\
            <td></td>\
            <td></td>\
            <td></td>\
            <td></td>\
        </tr>\
        <tr>\
            <td></td>\
            <td></td>\
            <td></td>\
            <td></td>\
            <td></td>\
            <td></td>\
        </tr>\
    </table>\
</div>'
}
var st_account = function(arr){
    return '<div class="settings_account">\
    <div class="settings_account_item">\
        <div>Название</div> \
        <div class="settings_account_descript">Введите название вашей компании. Название выводится на всех страницах в левом верхнем углу</div> \
        <input type="text" id="width230">\
    </div>\
    <div class="settings_account_item">\
        <div>Адрес</div>\
        <div class="settings_account_descript">Изменить адрес аккаунта может только администратор. В этом поле можнно вводить только латинские буквы и цифры без специальных символов и пробелов</div>\
        <div class="inline">http://</div><div class="inline"><input type="text"></div><div class="inline">.goodcrm.ru</div>\
        <div class="settings_account_item">\
    </div>\
    <div>Часовой пояс</div>\
        <select>\
            <option selected>(GMT+04:00)Europe/Moscow</option>\
        </select>\
    </div>  \
    <div class="settings_account_item">\
        <div>Валюта</div>\
        <select>\
            <option selected>Рубли</option>\
        </select>\
    </div>\
    <div class="settings_account_item">\
        <div>Язык</div>\
        <select>\
            <option selected>Русский</option>\
        </select>\
    </div>\
    <div class="settings_account_item">\
        <div>Партнерская ссылка</div>\
        <div class="settings_account_descript link_text">Скопировать в буфер</div>\
    </div>\
</div>'
}
var st_fields = function(arr){
    return '<div id="settings_fields">\
<div>Клиенты</div>\
    <table>\
        <tr class="settings_fields_row_dark">\
            <td></td>\
        </tr>\
        <tr>\
            <td></td>\
        </tr>\
    </table>\
</div>'
}
var st_integration = function(arr){
    return '<table class="settings_integration">\
    <tr>\
        <th>Приложение</th>\
        <th>Описание</th>\
        <th>Включить</th>\
        <!-- <th>Руководитель</th> -->\
    </tr>\
    <tr>\
        <td class="link_text">Facebook</td>\
        <td>Синхронизация информации из аккаунта</td>\
        <td>\
            <div class="integration_toggle">\
                <div class="integration_toggle_yes">Да</div>\
                <div class="integration_toggle_no integration_toggle_active">Нет</div>\
            </div>\
        </td>\
        \
    </tr>\
    <tr>\
        <td class="link_text">Online PBX</td>\
        <td>Виртуальная АТС</td>\
        <td>\
            <div class="integration_toggle">\
                <div class="integration_toggle_yes">Да</div>\
                <div class="integration_toggle_no integration_toggle_active">Нет</div>\
            </div>\
        </td>\
        \
    </tr>\
    <tr>\
        <td class="link_text">Twitter</td>\
        <td>Синхронизация информации из аккаунта</td>\
        <td>\
            <div class="integration_toggle">\
                <div class="integration_toggle_yes">Да</div>\
                <div class="integration_toggle_no integration_toggle_active">Нет</div>\
            </div>\
        </td>\
        \
    </tr>\
    <tr>\
        <td class="link_text">Dropbox</td>\
        <td>Хранение и общий доступ в большим файлам</td>\
        <td>\
            <div class="integration_toggle">\
                <div class="integration_toggle_yes">Да</div>\
                <div class="integration_toggle_no integration_toggle_active">Нет</div>\
            </div>\
        </td>\
        \
    </tr>\
    \
</table>'
}
var st_notifications = function(arr){
    return '<div id="settings_notice">\
<div class="settings_notice_title"><div>Уведмоления на электронную почту</div> <div>Напомнить мне за</div></div>\
    <table >\
        <tr>\
            <td>У вас встреча</td>\
            <td><input type="checkbox"></td>\
            <td><input type="text"></td>\
            <td>\
                <select>\
                    <option selected>дня</option>\
                </select>\
            </td>\
        </tr>\
        <tr>\
            <td>Вы запланировали встречу</td>\
            <td><input type="checkbox"></td>\
            <td><input type="text"></td>\
            <td>\
                <select>\
                    <option selected>дня</option>\
                </select>\
            </td>\
        </tr>\
        <tr>\
            <td>У вас пропущен звонок</td>\
            <td><input type="checkbox"></td>\
            <td><input type="text"></td>\
            <td>\
                <select>\
                    <option selected>дня</option>\
                </select>\
            </td>\
        </tr>\
        <tr>\
            <td>Вы запланировали звонок</td>\
            <td><input type="checkbox"></td>\
            <td><input type="text"></td>\
            <td>\
                <select>\
                    <option selected>дня</option>\
                </select>\
            </td>\
        </tr>\
        <tr>\
            <td>Скоро день рождения</td>\
            <td><input type="checkbox"></td>\
            <td><input type="text"></td>\
            <td>\
                <select>\
                    <option selected>дня</option>\
                </select>\
            </td>\
        </tr>\
    </table>\
</div>'
}
var st_rights = function(arr){
    return '<table class="settings_rights">\
    <tr>\
        <th>Ф.И.О.</th>\
        <th>Работает</th>\
        <th>Администратор</th>\
        <th>Руководитель</th>\
        <th>Добавлять</th>\
        <th>Редактировать</th>\
        <th>Удалять</th>\
        <th>Видеть всех</th>\
    </tr>\
    <tr>\
        <td>Адаев Руслан Анатольевич</td>\
        <td>\
            <div>Да</div>\
            <div>Нет</div>\
        </td>\
        <td>\
            <div>Да</div>\
            <div>Нет</div>\
        </td>\
        <td>\
            <div>Да</div>\
            <div>Нет</div>\
        </td>\
        <td>\
            <div>Да</div>\
            <div>Нет</div>\
        </td>\
        <td>\
            <div>Да</div>\
            <div>Нет</div>\
        </td>\
        <td>\
            <div>Да</div>\
            <div>Нет</div>\
        </td>\
        <td>\
            <div>Да</div>\
            <div>Нет</div>\
        </td>\
    </tr>\
</table>'
}
var showPlanPopup = function(around, e, data){
    if (!$('#popupEditUnit').length) {
        $('.clients').append('<div class="popup top" id="popupEditUnit"></div>');
    }
    $('#title').data().wtf = around;
    var p = around.parents('td').attr('id').split('_');
    var day = date_string(true);
    if (p[0] == 'weekday'){
        var sec = 86400000 * (getWeekDay(day) - parseInt(p[1]));
        var temp = new Date((new Date()).getTime()-sec);
        var d = extendToTwo(temp.getUTCDate());
        var m = extendToTwo(temp.getMonth());
        var y = temp.getFullYear();
        day =  d+ '-'+ m+ '-' + y;
    }
    var popup = $('#popupEditUnit');
    var id = around.siblings('td.id').text();
    popup.data('id', id);
    var cl = $('.clients');
    var itop = '5px'
    popup.hide();
    var icv = (id) ? 'disabled' : '';
    var action = (icv) ? 'edit' : 'add';
    fill_edit_table(id, 'plan_popup', undefined, '#popupEditUnit', icv);
    var top = around.offset().top+cl.scrollTop() - cl.offset().top;
    if (top + popup.height() > totalHeight(cl)) {
        top -= popup.height();
        itop = popup.height();
    }
    var left = e.clientX-$('#categories').width();
    if (left + popup.width() > cl.width()) left -= popup.width();
    var th1 = around.parents('.minutes').find('td[algn="th_1"]')
    var period = parseInt(th1.text())-parseInt($('#curr_timesegment').text())
    var minute = (period > 9) ? period : '0' + period
    var ct = th1.attr('hr')+':'+minute;
    var date = popup.find('.editIdSel#date input');
    var time = popup.find('.editIdSel#time input');
    popup.find('.editIdSel#company input').val(around.text());
    if (!date.val()) date.val(day)
        //date.prop('disabled', 'true');
    if (!time.val()) time.val(ct)
        //time.prop('disabled', 'true');    
    popup.css({left:left, top: top}).show();
    popup.prepend('<div class="icon-handle-next-left"></div>');
    $('.save_button').hide()
    $('#popupEditUnit .icon-handle-next-left').css('top', itop)
}
//для полей с цифрами
var isNumCode = function(e){
    var k = e.keyCode;
    if ( k == 46 || k == 8 || k == 9 || k == 27 || k == 37 || k == 8 || k==190 || k==116||
         // Разрешаем Ctrl+A,X,C,V
        (k == 65 && e.ctrlKey === true) || 
        (k == 67 && e.ctrlKey === true) || 
        (k == 86 && e.ctrlKey === true) || 
        (k == 88 && e.ctrlKey === true) || 
         // Разрешаем клавиши навигации: home, end, left, right
        (k >= 35 && k <= 40)) {
             return;
    }
    else {
        // Запрещаем всё, кроме клавиш цифр на основной клавиатуре, а также Num-клавиатуре
        if ((k < 48 || k > 57) && (k < 96 || k > 105 )) {
            e.preventDefault();
            return false; 
        }
    }
}
//получение строки дд-мм-гггг или ее частей по типу периода
var getDateByWord = function(id){
    var date = '';
    switch (id){
        case 'day':
            date=date_string(true);
            break;
        case 'month':
            date=date_string(false,true);
            break;
        case 'year':
            date=(new Date()).getFullYear();
            break;
    }
    return date;
}
var replacePhase = function(data){
        $('.editIdSel[id^="phase_"]').remove()
        if (data[0].phase) $('.editIdSel#sale_name+.editIdSelInactive').replaceWith(architector({id:'phase', list:data, process: data[0].process}));
        else {
            $('.editIdSel#sale_name+.editIdSelInactive').replaceWith(architector({id:'phase'}))
        }
        console.log(data);
    }
var rebuildTree = function(tree){
    var level = tree.attr('level');
    var larr = tree.children('li[level="'+level+'"]');
    larr.each(function(){
        var ul = $(this).children('ul')
        var div = $(this).children('div')
        var last = tree.children('li:last');
        if (ul.length && ul.children('li').length){
            if (!$(this).hasClass('collapsable')){
                $(this).addClass('collapsable');
                if (last == $(this)){
                    tree.children('li.lastCollapsable').removeClass('lastCollapsable');
                    $(this).addClass('lastCollapsable');
                }
            }
            if (!div.length)
                if($(this).hasClass('lastCollapsable'))
                    $(this).prepend('<div class="hitarea collapsable-hitarea lastCollapsable-hitarea"></div>')
                else 
                    $(this).prepend('<div class="hitarea collapsable-hitarea"></div>')
            rebuildTree(ul);
        } else {
            if (div.length) div.remove();
            if (ul.length) ul.remove();
            if ($(this).hasClass('collapsable'))
                $(this).removeClass('collapsable').removeClass('lastCollapsable');
            if (last==$(this)){
                tree.children('li.last').removeClass('last');
                    $(this).addClass('last');
            }
        }
    })
}
var collectRowData = function(row){
    if (!row.data().fields) row.data().fields = {};
    var data = row.data().fields
    row.find('td').each(function(){
        data[$(this).attr('class').split(' ')[0]] = $.trim($(this).text());
    })
    var id = $('.active_product_category').attr('id').split('_')[2];
    data.category_id = id; 
    var same_dir = (data.category_id == data.id_cat)|| data.id_cat=='';
    delete(data.available); delete(data.id_cat)
    if (data.product && data.cost && data.storage && id && same_dir){
        $.ajax({
            url: '/index.php/crm/addOrUpdProduct',
            type: 'POST',
            dataType: 'json',
            data: {
                product: data
            },
        })
        .done(function(id) {
            if (id) {
                row.children('td.id').text(id);
                row.children('td.id_cat').text(data.category_id)
            }
            console.log("success");
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
}
var isLastRowEmpty = function(table){
    row = table.find('tr:last-of-type')
    var really = false;
    row.find('td').each(function(){
        if ($.trim($(this).text())) really = true;
    })
    return really;
}
var saveNode = function(cur, upload){
    var add = true;
    if (upload == 'edit') add = false;
    if (upload!==false) upload = true;
    cur.removeClass('editable_category_block').removeAttr('contenteditable') //if success
    if (upload){
        var send = cur.data().upl_info;
        $.ajax({
            url: '/index.php/'+(($('#reload').data().sub=='segments') ? 'crm/addOrUpdSegment' : 'crm/addOrUpdCategory'),
            type: 'POST',
            dataType: 'json',
            data: {node: send},
        })
        .done(function(id) {
            cur.attr('id', 'category_tree_'+id)
            if (add){
                $("[rk]").each(function(e){
                    $(this).attr('rk', parseInt($(this).attr('rk'))+2);
                });
                cur.parent('li').attr('rk', send.prk+1);
            }
            console.log("success");
        })
        .fail(function() {
            console.log(send)
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
}
var deleteNode = function(cur){
    cur.parents('li').remove();
}
var setCurPart = function(string, option, date, year){
    $('#reload').data('current', string)
    if (option) $('#reload').data('sub', option)
    if (date) $('#reload').data('calendar_date', date)
    if (year) $('#reload').data('calendar_year', year)
    //console.log(string)
}
var getKeyById = function(data, id){
    var len = data.length;
    for (var i=0; i<len; i+=1){
        if (data[i].id == id) return i;
    }
    return '';
}
var sP = function(e) {
    e.stopPropagation ? e.stopPropagation() : (e.cancelBubble = true);
}
var CL = function(str) {
    console.log(str)
}
var collectData = function(target) {
    if (!target) target = '#editUnit';
    var send = new Object();
    var sel_val = ['country', /*'sale_type', 'phase',*/ 'failure_cause', 'role', 'route', 'dinner_time', 'mode', 'dinner_time_c', 'work_mode_c', 'work_mode'];
    var mk_ar = ['prognosis', 'payment'];
    console.groupCollapsed('#editUnit collected data')
    $(target + ' .editIdSel').each(function() {
        var key = $(this).attr('id');
        var spl = [];
        try {
            var spl = key.split('_');
        } catch (e) {}
        if ($.inArray(key, sel_val) > -1) {
            val = $(this).find('select option:selected').text()
            val = (val == lang.select_country) ? lang.nodata : val
        } else if (spl[0]=='phase'/*key.indexOf('phase')>-1*/) {
            var val = send.phase || [];
            key = 'phase'
            val.push({date:$(this).find('.editId').text(), phase: spl[1]})
        } else if ($.inArray(spl[0], mk_ar) > -1) {
            key = (2 in spl) ? spl[0] + '_' + spl[1] : spl[0];
            var val = send[key] || [];
            val.push($(this).find("input").val())
        } else {
            val = $(this).find('select').val() || $(this).find('input[type="radio"]:checked').val()||$(this).find('input:not(#logo_or_pic_submit):not(#logo_or_pic):not([type="radio"])').val() || $(this).find('textarea').val() || $(this).find('#UPI').html() || $(this).find('.editJSON').html();
        }
        if (key == 'ownership') send[key] = val || 'ООО'
        else send[key] = val;
        console.log('%s: %s', key, val);
    })
    console.debug(send)
    console.groupEnd();
    return send;
}
var write_ifc = function(t, e) {
    var io = '';
    try {
        io = document.getElementById('upload_frame').contentWindow.document.body.innerHTML
    } catch (e) {
        io = '';
    }
    if (io != '' && typeof(io) == 'string') {
        $('#currJSON').html(io)
        var data = $.parseJSON(io);
        if ('error' in data) {
            alert(data.error)
            $('#deletePhoto').trigger('click');
        } else {
            $('#UPI').html('include' + data.full_path.split('include')[1])
            $('#upload_frame').html('');
            if ($("#imageHolder").size() > 0) {
                preloadImage(e, "#imageHolder");
            } else {
                $('#deleteDocument').html(lang.delete).parents('.editPhoto').find('input').css({
                    'margin-left': '0px'
                });
                $('#att_href a').attr('href', '/include/attachments/files/' + data.file_name).html(data.file_name)
                $('#att_href').removeClass('hidden_ava')
            }
        }
    } else {
        window.setTimeout(function() {
            write_ifc(t + 1, e)
        }, 1);
    }
}
var msqgetter = function(data) {
    if (0 in data) {
        if (!$('#fast_received').length) {
            var left = $('#chat').offset().left;
            $('body').append(fast_received(data));
            var pw = $('#fast_received').width();
            $('#fast_received').css({
                'left': left - pw + 3
            })
            $('#fast_received').removeClass('hidden')
        } else if ($('#fast_received').hasClass('hidden')) {
            $('#fast_received .fastMessages').html(build_inc_msgs(data))
            $('#fast_received').removeClass('hidden')
        } else {
            var msgs = $('#fast_received .fastMessages').html();
            $('#fast_received .fastMessages').html(msgs + build_inc_msgs(data))
        }
    }
    lpstart();
}
var lpstart = function() {
    $.ajax({
        url: '/index.php/crm/get_msg_json',
        type: 'post',
        data: "",
        cache: 'false',
        success: function(data) {
            window.setTimeout(function() {
                msqgetter(data)
            }, 1000);
        },
        error: function(data) {
            window.setTimeout(function() {
                lpstart();
            }, 1000);
        },
        dataType: 'json'
    })
}
var load_table = function(whos, pattern) {
    whos = whos || 'all';
    pattern = pattern || '';
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {
            'whos': whos,
            'pattern' : pattern
        },
        url: '/index.php/crm/clients',
        success: function(data) {
            display_table(data, whos);
        },
        error: function(e) {
            console.log(e)
            changeHashLoc('');
        }
    })
};
var getKeys = function(data) {
    var temp = [];
    for (key in data) {
        temp.push(key);
    }
    return temp;
}
var getVal = function(data) {
    for (key in data) {
        return data[key];
    }
}
//check for config.php
var fill_edit_table = function(id, table, process, target, inv) {
    var temp = {};
    if (!target) target = '#editUnit';
    if (!process && id/*!='new' && process != '' && typeof(process)!='object'*/ )
        $.ajax({
            type: 'POST',
            data: {
                'table': (table == 'plan' || table == 'plan_popup') ? 'plans' : table,
                'value': id,
                'field': 'id'
            },
            url: '/index.php/crm/record',
            async: false,
            success: function(data) {
                process = data[0] || data;
                //console.dir(process)
            },
            dataType: 'json'
        });

    /*} else {*/
    if (table == 'sale') {
        temp = sales_parser(process);
        process = process['sales'] || {};
        if ('prognosis' in process) {
            loc.prognosis = '';
        }
        if ('payment' in process) {
            loc.payment = '';
        }
    }
    var html = '';
    name_t = localization.tables[table]
    table = (table == 'customer') ? process['type'] || 'legal' : table;
    loc = clone(localization[table]);
    if (table == 'order') {
        html += '<div class="hidden" id="last_order_number">' + empty(process['number']) + '</div>'
    }
    // console.groupCollapsed('blocks for editUnit')
    for (var key in loc) {
        arr = [];
        arr.caption = loc[key];
        arr.text = getTextFrom(process, key);
        arr.id = key;
        arr.list = {};
        if (key=='phase'){
            try{
                arr.process = process.type_sale;
            } catch(e){
                arr.process = 0;
            }
                
        }
        if (temp[key]) {
            arr.list = cloneArray(temp[key]);
        }
        //особые случаи, когда создание поля нужно пропустить или создать отключенное поле
        if (key == 'failure_cause' && process.failure != '1') html += architector(arr, 'disabled'); 
        else if (key == 'customer') {
            arr.text = $.trim($('#title').html());
            html += architector(arr, 'disabled');
        } else if (key=='type_sale' && id){
            html += architector(arr, 'disabled');
        } else if (key == 'number') {
            try {
                arr.text = ('id' in process) ? process.id : '';
            } catch (e) {}
            html += architector(arr, 'disabled');
        } else if (key == 'customer_id') {
            try {
                arr.text = $('#company_id').text();
            } catch (e) {arr.text = $('#company_id').text()}
            html += architector(arr, 'disabled');
        } else {
            html += architector(arr, inv);
        }
        // console.log(arr.id, ' ', arr.text, ' ', arr.caption, ' ', arr.list);
    }
    // console.groupEnd();
    if (html) {
        if (table != 'order') 
            if (inv) html = viewUnitHead(name_t) + html + viewUnitHead(name_t)
            else html = editUnitHead(name_t) + html + editUnitHead(name_t);
        html += '<div id="table_name" class="hidden">' + table + '</div>';
        if ($('#current_id').size() > 0) $('#current_id').remove();
        html += '<div id="current_id" class="hidden">' + id || process.id + '</div>';
        //console.log('id = %s, table = %s ', id, table)
        $(target).html(html);
        expandDateBlocks();
        $('.tree').treeview();
    }
}
var sales_parser = function(data) {
    var res_array = {};

    for (var key in data) {
        res_array[key] = {};
    }
    //res_array['name_sale'] = field_values['name_sale'];
    //res_array.phase = field_values.phase;
    res_array.failure_cause = field_values.failure_cause;
    if (data.phase) {
        res_array.phase = data.phase;
        console.log(data.phase)
    }
    if (data.payment) {
        res_array.add_pa = data.payment;
    }
    if (data.prognosis) {
        res_array.add_pr = data.prognosis;
    }
    if (data.responsibility) {
        var dev = data.responsibility;
        res_array.performer = {};
        for (var key in dev) {
            res_array.responsibility[dev[key].id] = dev[key].fio
            res_array.performer[dev[key].id] = dev[key].fio
        }
    }
    if (data.sales) {
        for (var key in data.sales) {
            res_array[key] = data.sales[key];
        }
    }
    // CL_rec(res_array)
    return res_array;
}
//отображение таблицы пользователей
var display_table = function(data, table_caption) {
    var captions = {};
    $('#clients_categories ul li').each(function() {
        var key = $(this).attr('id').split('_')[1] || $(this).attr('id').split('_')[0]
        var value = $(this).html();
        if (key == 'clients') {
            key = 'all';
            value = lang.all_customers
        }
        captions[key] = value;
    })
    if (!table_caption) table_caption = lang.all_customers
    else {
        table_caption = captions[table_caption]
    }
    var loc = localization.record;
    var table = '<table class="clients_table">';
    for (var i in data) {
        var content = data[i];
        if (parseInt(content.label) == 1){
            delete content.label;
            table += mark;
            for (var key in loc) {
                table += '<td class="' + key + '"><div class="caption">' + getTextFrom(content, key, '—') + '</div></td>';
            }
            table += '</tr>';
        }
    }
    table += '</table>';
    $('div.clients').html(table);
    $('div.v_h_a').html(main_table_header())
    $('div.n_d_t').html(n_d_t(table_caption))
    $('#record_edit').hide();
    $('#title').data('all_clients', data)
    show_in_head('record');
    $('.wrapper_user#record_edit, .wrapper_user#record_delete').hide();
    $('#date').html(date_string());
    replace_status_num();
    clock(); //вызываем функцию времени 
    window.setInterval(clock, 1000); //вызываем функцию clock() каждую секунду      
}
var display_sales = function(stage) {
    var name = lang[stage+'_sales']
    var head = sale_table_head(stage);
    console.log(stage);
    console.log(stage, $('#rg_comp').html())
    sales_by_stage(stage, $('#rg_comp').html())
    $('div.v_h_a').html(head)
    $('div.n_d_t').html(n_d_t(name))
    $('#record_edit').hide();
    show_in_head('record');
    //$('.wrapper_user#record_edit, .wrapper_user#record_delete').hide();
    $('#date').html(date_string());
    replace_status_num();
    clock(); //вызываем функцию времени 
    window.setInterval(clock, 1000); //вызываем функцию clock() каждую секунду
}

var display_report_voron = function(data) {
    $('div.v_h_a').html(report_voron_vha)
    build_funnel();
    $('div.n_d_t').html(report_voron_head())
    $('#record_edit').hide();
    show_in_head('record');
    $('.wrapper_user#record_edit, .wrapper_user#record_delete').hide();
    $('#date').html(date_string());
    replace_status_num();
    clock(); //вызываем функцию времени 
    window.setInterval(clock, 1000); //вызываем функцию clock() каждую секунду        
    expandFunnelLines(data);
}
var display_report_table = function(data) {
    $('div.clients').html(report_table_body());
    $('div.v_h_a').html(report_table_vha())
    $('div.n_d_t').html(report_table_head)
    $('#record_edit').hide();
    show_in_head('record');
    $('.wrapper_user#record_edit, .wrapper_user#record_delete').hide();
    $('#date').html(date_string());
    replace_status_num();
    clock(); //вызываем функцию времени 
    window.setInterval(clock, 1000); //вызываем функцию clock() каждую секунду      
}
var display_settings = function(chapter) {
    /*if ($(window).width()<1025) */slideCategories(210, 15, -1, true);
    $('div.clients').html('').append(st_lmenu());
    $('div.n_d_t').html(n_d_t(lang.settings, false))
    show_in_head('record');
    $('div.v_h_a').html('')//.hide();
    if(chapter=='users') $('div.v_h_a').html(n_d_t(''))//.hide();
    $('.settings_common_rb').html(window['st_' + chapter])
    $(window).trigger('resize');
    if ($('[id^="buttons_to_"]').length) reAlignButtons(chapter);
    $('#date').html(date_string());
    replace_status_num();
    clock(); //вызываем функцию времени
    window.setInterval(clock, 1000);
    $('.tree').treeview();
    $('span#category_tree_1').addClass('active_product_category')
    $('.settings_menu_item#st_' + chapter).addClass('settings_menu_item_selected');
}
var reAlignButtons = function(trigger){
    if(trigger=='products'){
        var tl = $('#buttons_to_categories');
        var tr = $('#buttons_to_products');
        var bl = $('.settings_products_tree').offset().left;
        var br = $('.settings_products_webinar').offset().left;
        tl.css('margin-left', bl)
        tr.css('margin-left', br-bl-tl.width())
    } else if(trigger == 'users'){
        $('#buttons_to_users').css({left:$('.settings_common_rb').offset().left, position: 'absolute'})
    }
} 
var basic_search = function(keyword, mode, is_all) {
    if (keyword) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/index.php/crm/search_basic',
            data: {
                'keyword': encodeURI(keyword),
                'mode': mode,
                'param': is_all
            },
            success: function(data) {
                temp = '';
                for (key in data) {
                    if (key < 9) {
                        temp += p_maker(data[key]['name'], data[key].id, '');
                    }
                }
                if (temp == '') {
                    temp = p_maker(lang.not_found + keyword, '', 'inactive')
                }
                $('.found_list').html(temp);
            },
            error: function(data) {
                temp = p_maker(lang.search_error + keyword, '', 'inactive')
                $('.found_list').html(temp);
            }
        })
    }
}
var getNameByType = function(data){
    if (data.type == 'legal')
        return data.ownership + ' "' + data.name + '"'
    if (data.type == 'individual')
        return data.surname + ' ' + data.name + ' ' + data.second_name;
    else 
        return '';
}
var make_company_table = function(data) {
    if (data.label == '0') {
        changeHashLoc('clients/all');
    } else {
        var loc = localization.company;
        string = '<div class="hidden" id="company_id">' + data.id + '</div>';
        string += '<div class="hidden" id="company_type">' + data.type + '</div>';
        delete data.id;
        for (var key in loc) {
            var val = getTextFrom(data,key, lang.nodata);
            string += '<div class="pData">' + loc[key] + '<span id=' + key + '>' + val + '</span></div>';
        }
        string += '</div>';
        string += make_contacts_table(data.contacts, true) //temp_tables;
        string += make_sales_table(data.sales, true)
        string += make_plans_table(data.plans, true)
        string += make_segments_table(data.segment, true)
        $('.n_d_t').html(n_d_t(getNameByType(data)));
        $('.v_h_a').html(v_h_a());
        $('#date').html(date_string());
        $('.clients').html(companyinfo_add + string);
        $('#title').data('company_info', data)
        replace_status_num();
        show_in_head('record');
        clock(); //вызываем функцию времени
        window.setInterval(clock, 1000); //вызываем функцию clock() каждую секунду
        $('.f_el:not(.lt)').hide();
        $('[id$="_record_edit"],[id$="_record_delete"]').hide();
    }
}
var make_contacts_table = function(data, only_three) {
    name = 'contact';
    data = data || {};
    th = lang.contacts;
    var loc = localization[name + '_table'];
    ai = '"company_' + name + '"';
    table = '<div class="cont" id="' + name + 's"><span class="tabName" id=' + ai + '>' + th + ':</span>' + aed_buttons(name)
    if (!data[0]) return table + '</div>';
    table += '<table class=' + ai + '><tr class="header">';
    for (var key in loc) {
        table += '<th class="' + key + '">' + loc[key] + '</th>'; //ячейки заголовков
    }
    table += '</tr>';
    for (var k in data) {
        if (k >= 3 && only_three) break;
        table += '<tr class="' + name + '">'
        for (var key in loc) {
            table += '<td class="' + key + '">' + getTextFrom(data[k],key, '—') + '</td>'; //ячейки из БД
        }
        table += '</tr>'
    }
    table += '</table></div>';
    return table;
}
var make_sales_table = function(data, only_three) {
    name = 'sale';
    data = data || {};
    th = lang.sales;
    var loc = localization[name + '_table'];
    ai = '"company_' + name + '"';
    table = '<div class="cont" id="' + name + 's"><span class="tabName" id=' + ai + '>' + th + ':</span>' + aed_buttons(name)
    if (!data[0]) return table + '</div>';
    table += '<table class=' + ai + '><tr class="header">';
    for (var key in loc) {
        table += '<th class="' + key + '">' + loc[key] + '</th>'; //ячейки заголовков
    }
    table += '</tr>';
    for (var k in data) {
        if (k >= 3 && only_three) break;
        table += '<tr class="' + name + '">'
        for (var key in loc) {
            table += '<td class="' + key + '">' + getTextFrom(data[k],key, '—') + '</td>'; //ячейки из БД
        }
        table += '</tr>'
    }
    table += '</table></div>';
    return table;
}
var make_plans_table = function(data, only_three) {
    name = 'plan';
    data = data || {};
    th = lang.plans;
    var loc = localization[name + '_table'];
    ai = '"company_' + name + '"';
    table = '<div class="cont" id="' + name + 's"><span class="tabName" id=' + ai + '>' + th + ':</span>' + aed_buttons(name)
    if (!data[0]) return table + '</div>';
    table += '<table class=' + ai + '><tr class="header">';
    for (var key in loc) {
        table += '<th class="' + key + '">' + loc[key] + '</th>'; //ячейки заголовков
    }
    table += '</tr>';
    for (var k in data) {
        if (k >= 3 && only_three) break;
        table += '<tr class="' + name + '">'
        for (var key in loc) {
            table += '<td class="' + key + '">' + ((key=='action')?field_values.action[data[k][key]]:getTextFrom(data[k],key, '—')) + '</td>'; //ячейки из БД
        }
        table += '</tr>'
    }
    table += '</table></div>';
    return table;
}
var make_segments_table = function(data, only_three) {
    name = 'segment';
    data = data || {};
    th = lang.segments;
    var max = data['max_count'] - 1;
    ai = '"company_' + name + '"';
    delete data['max_count'];
    table = '<div class="cont" id="segments"><span class="tabName" id=' + ai + '>' + th + ':</span>' + aed_buttons('segment')
    if (!data['max_count']) return table + '</div>';
    table += '<table class=' + ai + '><tr class="header"><th class="type">'+lang.type+'</th>';
    for (var i = 1; i <= max; i++) {
        table += '<th class="' + i + '">' + lang.segment+' ' + i + '</th>';
    }
    table += '<th class="id"></th></tr>';
    for (var k in data) {
        if (k >= 3 && only_three) break;
        table += '<tr class="segment"><td class="type">' + k.split('#')[0] + '<span class="hidden">' + k.split('#')[1] + '</span></td>';
        for (var i = 0; i < max; i++) {
            if (data[k][i]) {
                id = data[k][i].split('#')[1] || '';
                cell = data[k][i].split('#')[0] || '';
                table += '<td class="' + i + '">' + getTextFrom('',cell, '—') + '<span class="hidden">' + id + '</span></td>'; //ячейки из БД
            } else {
                table += '<td></td>'; //ячейки из БД
            }
        }
        table += '<td class="id">' + data[k].id + '</td></tr>';
    }
    table += '</table></div>';
    return table;
}
var make_orders_table = function(data, only_three) {
    return '<div class="cont" id="orders"><span class="tabName" id="company_order">'+lang.function_unavailable+'</span></div>';
}
var make_exchange_table = function(data, only_three) {
    return '<div class="cont" id="exchange"><span class="tabName" id="company_exchange">'+lang.function_unavailable+'</span></div>';
}
var display_all_rows = function(table) {
    var data = $('#title').data('company_info');
    var func = 'make_' + table + '_table';
    $('.clients').html(window[func](data[table] || data, false));
    $('[id$="_record_edit"],[id$="_record_delete"]').hide();
}
//запрос к серв за информацией о компании
var make_plans_hash = function(period, time) {
    if (!time) time = 15; //временной отрезок одной строки (15 по умолчанию)
    var head_text = lang[period+'_plan'];
    $('.n_d_t').html(n_d_t(head_text));
    $('.v_h_a').html((period == 'day') ? timetable_head() : weektable_head()).css({
        'border-bottom': '1px solid #CDCDCD'
    });
    $('.clients').html(timetable_generate(time, period));
    $('.clients').append('<div id="curr_period" class="hidden">' + period + '</div>')
    $('.clients').append('<div id="curr_timesegment" class="hidden">' + time + '</div>')
    $('#time_'+time).addClass('inactive_time')
    $('#date').html(date_string());
    $('.f_el').hide();
    $('.calendar').show();
    show_in_head('time');
    start = parseInt($('#workday_start').html());
    height = 20;
    scroll = start * height * (60 / time);
    $('.clients').scrollTop(scroll);
    clock();
    $(window).resize();
    window.setInterval(clock, 1000); //вызываем функцию clock() каждую секунду   console.log()
}
var show_in_head = function(id) {
    var op = (id == 'record') ? 'time' : 'record';
    $('[id^="' + op + '_"]').hide();
    $('[id^="' + id + '_"]').show();
}
var normalize = function(data, time) {
    var a = Array();
    var temp = data;  
    var len = data.length;
    for (var key=0;key<len;key++) {
        var hour = parseInt(temp[key].time.split(':')[0]);
        var minute = getCeilMinute(temp[key].time, time);
        if (!a[hour]) a[hour] = Array();
        if (!a[hour][minute]) a[hour][minute] = Array();
        a[hour][minute].push(temp[key])
        delete temp[key];
    }
    return a;
}
var getCeilMinute = function(time, period){
    var minute = parseInt(time.split(':')[1]);
    minute = period*Math.ceil(minute/period);
    return (minute<period)?period:minute;
}
var week_norm = function(data, time) {
    var len = data.length;
    if (!len) return {};
    var a = Array();
    var temp = data;    
    for (var key=0;key<len;key++) {
        var d_a = data[key]['date'].split('-');
        var day = 'weekday_' + ((new Date(d_a[2], parseInt(d_a[1]) - 1, d_a[0])).getDay()-1);
        var hour = parseInt(temp[key]['time'].split(':')[0]);
        var minute = getCeilMinute(temp[key].time, time);
        if (!a[day]) a[day] = Array();
        if (!a[day][hour]) a[day][hour] = Array();
        if (!a[day][hour][minute]) a[day][hour][minute] = Array();
        a[day][hour][minute].push(temp[key])
        delete temp[key];
    }
    return a;
}
var show_plans_hash = function(period, time_period, build_grid, date, year) {
    //if (date) date = $('#reload').data().calendar_date;
    var title = lang['by_'+period];
    var send = {date : date || encodeURI($.trim(date_string(true)))};
    var func = ''; var url = '';
    if (period == 'day') {
        func = 'normalize';
        if (date) title = date_string(false, false, date);
    } else if (period == 'week'){
        url = 'Week';
        if (date) title = lang['by_week']+' №'+date;
        send.date = date || $.datepicker.iso8601Week(new Date());
        send.year = (year) ? year : (new Date()).getFullYear();
        func = 'week_norm';
    }
    if (build_grid!=false) {
        console.time('build_plan_'+period)
        make_plans_hash(period, time_period || 15);
        console.timeEnd('build_plan_'+period)
    }
    var t = lang[period+'_plan']
    var e = $.trim(t).split(' '); 
    e[e.length-1] = title;
    $('#title').html(e.join(' '))
    getUserList(planDataInserter, 'crm/plan'+url, send);
};
var show_company_info = function(company, id) {
    $.ajax({
        type: "POST",
        url: '/index.php/crm/company',
        data: {
            'id': id,
            'name': company
        },
        success: function(data) {
            make_company_table(data[0]);
            company_info_global = data[0];
        },
        error: function(data) {
            changeHashLoc('');
        },
        dataType: 'json'
    });
};
var planDataInserter = function(data){
    var tempor = $('#reload').data('sub');
    tempor = (tempor=='day') ? 'day' : 'weekday';
    var l = data.length;
    var period = parseInt($('.inactive_time .blue_text').text());
    $('.allcurrent').html('');
    for (var i = 0; i < l; i++) {
        var e = ['#'+tempor];
        if (tempor!='day') e.push(getWeekDay(data[i].date));
        var t = data[i].time;
        e.push(t.split(':')[0])
        e.push(getCeilMinute(t, period))
        switch (tempor) {
            case 'weekday' : 
                var a = $(e.join('_')).find('.allcurrent');
                var inh = a.find('[class^="inhere_"]');
                var n = 0;
                if (inh.length){
                    n = parseInt(inh.last().attr('class').split('_')[1])+1;
                }
                a.append('<tr class="inhere_'+n+'"><td class="id">'+data[i].id+'</td><td class="customer">'+data[i].customer+'</td></tr>')
                break;
            case 'day' :
                var build = function(n, cur){
                    var loc = clone(localization.plan_day); delete loc.hour; delete loc.minute;
                    html = '<tr class="inhere_'+n+'">';
                    var t = 2;
                    for (var key in loc){
                        if (key=='performer' || key=='responsibility') cur[key] = cur[key].split(' ')[0] +' '+capsFirstLetter(cur[key].split(' ')[1]).split('')[0]+'.';
                        if (key=='action') cur[key] = field_values.action[cur[key]]
                        html += '<td algn="th_'+ (t++) +'" class="'+key+'">'+cur[key]+'</td>';            
                    }
                    return html + '</tr>';
                }
                var a = $(e.join('_')).find('.allcurrent');
                var inh = a.find('[class^="inhere_"]');
                var n = 0;
                if (inh.length){
                    n = parseInt(inh.last().attr('class').split('_')[1])+1;
                    console.log(n);
                }
                a.append(build(n, data[i])) 
                break;
            default:
                break;
        }
    };
}
var lastSlashEl = function(){
    var a = location.href.split('/');
    return a[a.length-1];
}
var getWeekDay = function(date){
    var s = date.split('-');
    var t = '';
    t = s[0]; s[0] = s[1]; s[1] = t;
    a = (new Date(s.join('-'))).getDay()-1;
    return (a>=0) ? a : 6;
}
var update_row = function(data, table) {
    if (table != 'customer') {
        sel = 'tr.' + table + ' td.id:contains("' + id + '")'
        loc = localization[table+'_table'];
        row = ''        
        console.log(data)
        for (var key in loc) {
            if (key.split('_')[0] != 'split') {
                row += '<td class="' + key + '">' + getTextFrom(data, key, '—') + '</td>'; //ячейки из БД
            }
        }
        $(sel).parents('tr').html(row)
    } else {
        show_company_info(data[0]['name'])
    }
}
var validate_field = function(tag, val){
        var email_pattern = /^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i;
        var error = '';
        var status = true;
        not_empty = ['company', 'date', 'time', 'task', 'result'];
        not_zero = ['id_contact', 'sale_name'];
        if ($.inArray(tag, not_empty) > -1 && val=='') {
            error = lang.need_to_be_filled; 
            status = false;
        }
        if ($.inArray(tag, not_zero) > -1 && parseInt(val)==0) {
            error = lang.need_to_be_filled; 
            status = false;
        }        
        if (tag == '' && !email_pattern.test(val) && val != '') {
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
        if (tag == 'span.phone' && (val.size != 11 || val.size!=6) && val != '') {
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
var validateEIS = function(selector){
    var status = true;
    $(selector + ' .editIdSel').each(function() {
        var tag = $(this).attr('id');
        var val = $(this).find('select, input, textarea').val();
        data = validate_field(tag, val);
        status = (status & data.status);
    })
    return status;
}
var changeHashLoc = function(to, hashNeeded){
    if (hashNeeded!=false) hashNeeded = true;
    to = to || '';
    location.href = location.href.split('#')[0] + ((hashNeeded)?'#':'') + to;
}
var expand = function() { //раскрыть все пункты категорий
    var ic = $('#crm').find("[class $= '-end']").attr('class');
    $("[id $= '_categories']").slideDown();
    $('#crm').find('.' + ic).attr('class', 'icon-handle-up-end');
    $(".menu_cell:not(#crm) [class ^= 'icon-handle-']").attr('class', 'icon-handle-up')
};
var collapse = function() { //свернуть все пункты категорий
    var ic = $('#crm').find("[class $= '-end']").attr('class');
    $("[id $= '_categories']").slideUp();
    $('#crm').find('.' + ic).attr('class', 'icon-handle-down-end');
    $(".menu_cell:not(#crm) [class ^= 'icon-handle-']").attr('class', 'icon-handle-down')
};
var preloadImage = function(evt, trgt) {
    var fld = $(trgt).parents('div#fill_ava')
    var txt = $(trgt).parents('.editPhoto').find('div#del_button span')
    var btn = $(trgt).parents('.editPhoto').find('div#del_button')
    var inp = $(trgt).parents('.editPhoto').find('input')
    $(trgt).attr('src', '')
    var files = evt.target.files;
    for (var i = 0, f; f = files[i]; i++) {
        var reader = new FileReader();
        reader.onload = (function(f) {
            return function(e) {
                $(trgt).attr('src', e.target.result); 
            }
        })(f);
        reader.readAsDataURL(f);
    }
    fld.removeAttr('class');
    txt.html(lang.delete);
    btn.css({
        'z-index': '100'
    });
    inp.css({
        'margin-left': '-140px'
    })
}
var expandDateBlocks = function() {
    var dates = $('.editDate');
    dates.each(function() {
        var element = $(this);
        if (!element.parents('.editIdSel').next().hasClass('rightTime')) element.css('width', '170px');
    })
}
var totalHeight = function(obj){
    return obj.prop('scrollHeight');
}
var slideCategories = function(start, end, step, hide) {
    if (hide) $(".menu_cell, [id*='_categories'] *").hide(); //исчезает текст пунктов
    w = $(window).width();
    //for (var i = start; i >= end; i+=step) {
    while (start != end) {
        $("div#categories").css({
            width: start
        });
        var curw = 100 * ((w - start - 7) / w);
        var curf = 100 * ((w - start) / w);
        $("#footer").css({
            width: curf + '%'
        });
        $("div.clients,.v_h_a,.n_d_t").css({
            width: curw + '%'
        });
        start += step;
    };
    var icon = (hide) ? 'icon-handles-right' : 'icon-handles-left';
    $("div#categories .opener").find("[class^='icon-']").attr('class', icon); //меняется иконка налево
    if (!hide) $(".menu_cell, [id*='_categories'] *").show(); //появлется текст
    $('#checker').html('' + hide); //изменяется текст триггера
    $(window).trigger('resize');
}