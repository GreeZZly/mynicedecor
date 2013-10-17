$('[src^="http://master-style.ru"]').remove()
var localization = {
    legal: {
        split_0 : '',
        id : '',
        type : 'Лицо',
        date_registration : 'Дата регистрации',
        photo : 'Логотип',
        name : 'Наименование',
        ownership : 'Форма собственности',
        birthday : 'День основания',
        work_mode_c : 'Режим работы',
        dinner_time_c : 'Обед',
        split_1 : '',
        status : 'Статус',
        responsibility : 'Ответственный',
        split_2 : '',
        phone : 'Телефон',
        fax : 'Факс',
        email_home : 'E-mail',
        site1 : 'Сайт',
        split_3 : '',
        description : 'О компании',
        split_35 : '',
        country : 'Страна',
        region : 'Регион',
        subregion : 'Район/город',
        street : 'Улица',
        house : 'Дом',
        housing : 'Корпус',
        index : 'Почтовый индекс',
        flat : 'Офис',
        split_4 : '',
        full_name : 'Полное наим.',
        legal_address : 'Юр. адрес',
        head : 'Руководитель',
        under : 'На основании',
        accountant : 'Бухгалтер',
        INN : 'ИНН',
        KPP : 'КПП',
        bank : 'Банк',
        BIK : 'БИК',
        payment_account : 'Расчетный счет',
        corr_account : 'Корр. счет',
        OGRN : 'ОГРН',
        OKPO : 'ОКПО',
        OKVED : 'ОКВЭД',
        OKFS : 'ОКФС',
        OKOPF : 'ОКОПФ',
        OKATO : 'ОКАТО',
        id_contact_info : '',
        id_address : '',
        id_bank_details : '',
        id_work_place : '',
        id_passport : '',
        split_5 : '',
    },
    individual : {
        split_0 : '',
        id : '',
        type : 'Лицо',
        date_registration : 'Дата регистрации',
        photo : 'Фотография',
        surname : 'Фамилия',
        name : 'Имя',
        second_name : 'Отчество',
        birthday : 'День рождения',
        gender : 'Пол',
        split_1 : '',
        status : 'Статус',
        responsibility : 'Ответственный',
        split_2 : '',
        company : 'Компания',
        ownership_w : 'Форма собственности',
        position : 'Должность',
        role : 'Роль',
        work_mode : 'Режим работы',
        dinner_time : 'Обед',
        split_3 : '',
        phone : 'Телефон (моб)',
        phone_work : 'Телефон (раб)',
        phone_home : 'Телефон (дом)',
        fax : 'Факс',
        email_home : 'E-mail (лич)',
        email_work : 'E-mail (раб)',
        site1 : 'Сайт (лич)',
        site2 : 'Сайт (раб)',
        split_4 : '',
        country : 'Страна',
        region : 'Регион',
        subregion : 'Район/город',
        street : 'Улица',
        house : 'Дом',
        housing : 'Корпус',
        index : 'Почтовый индекс',
        flat : 'Офис',
        split_5 : '',
        scan_passport : 'Паспорт',
        number : 'Номер паспорта',
        series : 'Серия',
        date : 'Дата выдачи',
        kod : 'Код подразделения',
        surname_p : 'Фамилия',
        name_p : 'Имя',
        second_name_p : 'Отчество',
        passport_db : 'День рождения',
        gender_p : 'Пол',
        place_birth : 'Место рождения',
        split_6 : '',
        INN_c : 'ИНН',
        SNILS : 'СНИЛС',
        split_7 : '',
        bank : 'Банк',
        BIK : 'БИК',
        INN : 'ИНН',
        payment_account : 'Расчетный счет',
        corr_account : 'Кор. счет',
        personal_account : 'Личный счет',
        card_number : 'Номер карты',
        id_contact_info : '',
        id_address : '',
        id_bank_details : '',
        id_work_place : '',
        id_passport : '',
        split_8 : ''
    },
    sale: {
        split_0 : '',
        name_sale : 'Название',
        number : 'Номер',
        split_1 : '',
        type_sale : 'Бизнес процесс',
        phase : 'Этап',
        split_2 : '',
        order : 'Заказ',
        split_3 : '',
        start_deal : 'Начало',
        time_start : '',
        end_deal : 'Конец',
        time_end : '',
        split_4 : '',
        responsibility : 'Ответственный',
        performer : 'Исполнитель',
        split_5 : '',        
        add_pr : '',
        split_6 : '',        
        add_pa : '',
        split_7 : '',
        debt : 'Дебет',
        split_8 : '',
        failure : 'Отказ',
        failure_cause : 'Причина',
        split_9 : '',
        dogovor : 'Договор',
        schet : 'Счет',
        act : 'Акт',
        split_10 : '',
        id : '',
        customer_id: '',
        id_product : '',
        id_document : ''
    },
    plan: {
        split_0 : '',
        action : 'Мероприятие',
        date : 'Начало',
        time : '',
        alert : 'Оповещение',
        id_contact : 'Контакт',
        split_1 : '',
        sale_name : 'Продажа',
        phase : 'Этап',
        split_2 : '',
        responsibility : 'Ответственный',
        performer : 'Исполнитель',
        split_3 : '',
        task : 'Задача',
        result : 'Результат',
        split_4 : '',
        attachment : 'Приложение',
        id_customer : '',
        id_registred_company : '',
        split_5 : ''
    },
    plan_popup: {
        split_0 : '',
        company : 'Компания',
        action : 'Мероприятие',
        date : 'Начало',
        time : '',
        alert : 'Оповещение',
        id_contact : 'Контакт',
        split_1 : '',
        sale_name : 'Название продажи',
        phase : 'Этап продажи',
        split_2 : '',
        responsibility : 'Ответственный',
        performer : 'Исполнитель',
        split_3 : '',
        task : 'Задача',
        result : 'Результат',
        split_4 : '',
        attachment : 'Приложение',
        id_customer : '',
        id_registred_company : '',
        split_5 : ''
    },
    plan_day : {
        hour : '',
        minute : '',
        action : 'Мероприятие',
        customer : 'Компания',
        task : 'Задача',
        result : 'Результат',
        responsibility : 'Ответственный',
        performer : 'Исполнитель',
        id: '',
    },
    plan_week : {
        hour : '',
        minute : '',
        monday : 'Понедельник',
        tuesday : 'Вторник',
        wednesday : 'Среда',
        thursday : 'Четверг',
        friday : 'Пятница',
        saturday : 'Суббота',
        sunday : 'Воскресенье',
    },
    contact: {
        split_0 : '',
        date_registration : 'Дата регистрации',
        photo : 'Фотография',
        surname: 'Фамилия',
        name: 'Имя',
        second_name: 'Отчество',
        birthday: 'День рождения',
        gender: 'Пол',
        split_1 : '',
        position: 'Должность',
        route: 'Направление',
        role: 'Роль',
        split_2 : '',
        work_mode : 'Режим работы',
        dinner_time : 'Обед',
        split_3 : '',
        phone: 'Телефон',
        email: 'E-mail',
        IM: 'В соц. сети',
        passport_с: 'Паспорт',
        INN: 'ИНН',
        description: 'Описание',
        id : '',
        id_customer: ''

    },
    user: {
        split_0 : '',
        id : '',
        image_path : 'Фотография',
        surname : 'Фамилия',
        name : 'Имя',
        second_name : 'Отчество',
        gender : 'Пол',
        birthday : 'День рождения',
        about : 'О себе',
        split_1 : '',
        login: 'Логин',
        email: 'Почта',
        language: 'Язык интерфейса',
        split_2 : '',
        route : 'Отдел',
        position : 'Должность',
        work_mode : 'Режим работы',
        dinner_time : 'Обед',
        reception_day: 'День приема',
        fired_day: 'День увольнения',
        split_3 : '',
        phone : 'Телефон (моб)',
        phone_work : 'Телефон (раб)',
        email_home : 'E-mail (дом)',
        email_work : 'E-mail (раб)',
        site1 : 'www',
        split_4 : '',
        country : 'Страна',
        city : 'Город',
        street : 'Улица',
        house : 'Дом',
        housing : 'Корпус',
        flat : 'Квартира',
        index : 'Почтовый индекс',
        split_5 : '',
        INN : 'ИНН',
        SNILS : 'СНИЛС',
        split_6 : '',
        scan_passport : 'Паспорт',
        series : 'Серия',
        kod : 'Код подразделения',
        date : 'Дата выдачи',
        number : 'Номер паспорта',
        surname_p : 'Фамилия',
        name_p : 'Имя',
        second_name_p : 'Отчество',
        gender_p : 'Пол',
        passport_db : 'День рождения',
        place_birth : 'Место рождения',
        split_7 : '',
        INN_c : 'ИНН',
        bank : 'Банк',
        BIK : 'БИК',
        payment_account : 'Расчетный счет',
        corr_account : 'Кор. счет',
        personal_account : 'Личный счет',
        card_number : 'Номер карты',
        id_contact_info : '',
        id_address : '',
        id_bank_details : '',
        id_work_place : '',
        id_passport : '',
        split_8 : ''
    },
    order:{
        split_0 : '',
        name : 'Название',
        number : 'Номер',
        date : 'Время',
        time : '',
        bill : 'Счет',
        attachment : 'Приложение',
        split_1 : '',
        description_o : '',
        price_o : '',
        id_sale : '',
        tree : '',
    },
    av_order: {
        id : '',
        id_cat : '',
        product : 'Наименование',
        cost : 'Цена',
        storage : 'Склад',
        stored : 'Отложено',
        available : 'Свободно',
        service : '',
    },
    cu_order: {
        id : '',
        id_cat : '',
        product : 'Наименование',
        cost : 'Цена',
        quantity : 'Количество',
        discount : 'Скидка',
        total_sum : 'Сумма',
        service : ''
    },
    segment : {
        split_0 : '',
        segment_1:'Привлечение',
        split_1 : '',
        segment_2:'Отрасль',
        split_2 : '',
        segment_3:'Модель',
        split_3 : ''
    },
    company: {
        date_registration: 'Дата регистрации: ',
        responsibility: 'Ответственный: ',
        status: 'Статус: ',
        address: 'Адрес: ',
        site: 'Сайт: ',
        description: 'О компании: '
    },
    sale_table: {
        name_sale: 'Название',
        product: 'Продукт',
        responsibility: 'Ответственный',
        prognosis: 'Прогноз',
        payment: 'Поступление',
        debt: 'Долг',
        phase: 'Этап',
        status: 'Состояние',
        id: '',
        customer_id: ''
    },
    plan_table: {
        date: 'Дата',
        contact: 'Контакт',
        action: 'Мероприятие',
        responsibility: 'Ответственный',
        sale: 'Продажа',
        task: 'Задача',
        result: 'Результат',
        id: ''
    },
    contact_table: {
        fullname: 'Ф.И.О.',
        position: 'Должность',
        route: 'Направление',
        role: 'Роль',
        phone: 'Телефон',
        email: 'E-mail',
        birthday: 'День рождения',
        id: '',
        id_customer: ''
    },
    cap_records : {
        company: 'Компания',
        trainer: 'Ответственный',
        phase: 'Этап',
        prediction: 'Прогноз',
        date: 'Дата'
    },
    record: { //добавить все возможные названия столбцов бд
        id : '',
        company: 'Компания',
        realname :'',
        status : 'Статус',
        trainer: 'Ответственный',
        prediction: 'Прогноз',
        date: 'Дата'
    },
    tables: {
        contact : 'Контакт',
        sale : 'Продажа',
        segment : 'Сегмент',
        plan : 'История',
        plan_popup : 'История',
        order : 'Заказ',
        customer:'Клиент',
        user : 'Пользователь'
    },
    closed_sales : {
        id : '',
        id_sale : '',
        company_name : 'Компания',
        end_deal : 'Конец',
        name_sale : 'Продажа',
        product : 'Продукт',
        prognosis : 'Прогноз',
        payment : 'Поступление',
        failure_cause : 'Причина',
        phase : 'Этап',
        responsibility : 'Ответственный'
    },
    open_sales : {
        id : '',
        id_sale : '',
        company_name : 'Компания',
        start_deal : 'Начало',
        name_sale : 'Продажа',
        product : 'Продукт',
        prognosis : 'Прогноз',
        payment : 'Поступление',
        debt : 'Дебет',
        phase : 'Этап',
        responsibility :'Ответственный'
    },
    sale_history : {
        id : '',       
        date: 'Дата',
        contact_name: 'Контакт',
        action: 'Мероприятие',
        task: 'Задача',
        result: 'Результат',
        phase: 'Этап',
        performer: 'Исполнитель'
    },
    plan_by_sale : {
        id : '',
        date : '',
        contact_name : '',
        action : '',
        task : '',
        result : '',
        phase : '',
        performer : ''
    },
    view_tab:{
        all: 'company',
        sale: 'sales',
        contact: 'contacts',
        history: 'plans',
        segment: 'segments',
        order: 'orders',
        exchange: 'exchange'
    },
    usertable:{
        id : '',
        image_path: 'Фото',
        fullname: 'Ф.И.О.',
        role: 'Должность',
        email: 'Почта',
        phone: 'Мобильный'
    },
    st_parts:{
        users : 'Пользователи',
        rights : 'Права',
        account : 'Аккаунт',
        integration : 'Интеграция',
        paymentplan : 'Продажи',
        saleplan : 'Активность',
        products : 'Продукты',
        notifications : 'Уведомления',
        dictionaries : 'Справочники',
        segments : 'Сегменты',
        fields : 'Поля'
    },
    cbuttonts:{
        all : 'Главная',
        sale : 'Продажи',
        order : 'Заказы',
        contact : 'Контакты',
        history : 'История',
        exchange : 'Обмен',
        segment : 'Сегменты',
    },
    report_table : {
        id: '',
        user : 'Сотрудники',
        activity : 'Активность',
        efficiency : 'Эффективность',
        prognosis : 'Прогноз',
        payment : 'Поступление',
        plan : 'План',
        debet : 'Дебет'
    },
    funnel_head : {
        funnel : 'Воронка продаж',
        phases : 'Этапы бизнес-процесса'
    },
    common_frases : {
        translation : 'ru',
        add : 'Добавить',
        next : 'Следующий',
        edit : 'Редактировать',
        delete : 'Удалить',
        logout: 'Выйти',
        login: 'Войти',
        send : 'Отправить',
        save : 'Сохранить',
        answer : 'Ответить',
        form : 'Сформировать',
        attach : 'Прикрепить',
        company: 'Компания',
        employees: 'Сотрудники',
        employee: 'Сотрудник',
        customer: 'Клиент',
        entity: 'Лицо',
        type: 'Тип',
        sex_m: 'М',
        sex_w: 'Ж',
        yes: 'Да',
        no: 'Нет',
        all: 'Все',
        total: 'Итого',
        nomination: 'Наименование',
        categories: 'Категории',
        settings: 'Настройки',
        title: 'Название',
        plan: 'План',
        plans: 'Планы',
        sale: 'Продажа',
        segment: 'Сегмент',
        segments: 'Сегменты',
        contact: 'Контакт',
        sale_name: 'Название продажи',
        storage: 'Склад',
        stored: 'Отложено',
        available: 'Свободно',
        denial: 'Отказ',
        roles: 'Роли',
        sales: 'Продажи',
        contacts: 'Контакты',
        products: 'Продукты',
        segments: 'Сегменты',
        attraction: 'Привлечение',
        price: 'Цена',
        man: 'Человек',
        second: 'Секунда',
        minute: 'Минута',
        hour: 'Час',
        day: 'День',
        week: 'Неделя',
        month: 'Месяц',
        quartal: 'Квартал',
        year: 'Год',
        subregion: 'Нас. пункт',
        region: 'Регион',
        city: 'Город',
        street: 'Улица',
        phase: 'Этап',
        function_unavailable: 'Данная функция не доступна в ознакомительной версии',
        no_plan_by_sale: 'Нет истории по данной продаже',
        need_to_be_filled: 'обязательно для заполнения',
        delete_confirm: 'Подтвердите удаление',
        usual_confirm: 'Вы уверены?',
        select_country: 'Выберите страну',
        not_found: 'Ничего не найдено по ',
        search_error: 'Ошибка поиска по ',
        all_customers: 'Все клиенты',
        open_sales: 'Открытые продажи',
        closed_sales: 'Закрытые продажи',
        mine_open_sales: 'Мои открытые продажи',
        mine_closed_sales: 'Мои закрытые продажи',
        day_plan: 'Все планы на сегодня',
        week_plan: 'Планы встреч на неделю',
        by_day: 'сегодня',
        by_week: 'неделю',
        plan_report: 'Отчет по выполнению планов продаж',
        activity_report: 'Отчет по активности торгового персонала',
        order : 'Заказ',
        prognosis : 'Прогноз',
        prognosis_s : 'прогноз',
        payment : 'Поступление',
        payment_s : 'поступление',
        debet : 'Дебет',
        fast_messages : 'Быстрые сообщения',
        fast_message : 'Быстрое сообщение',
        message : 'Сообщение',
        recepient : 'Кому',
        nodata : 'нет данных',
        noresponsibility : 'Нет ответственного',
        nocontact : 'Нет контакта',
        nosale : 'Нет продажи',
        days : ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
        months_icn : ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'],
        months : ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    }
}
var lang = localization.common_frases;
var weekdays = {
    monday : 'weekday_0',
    tuesday : 'weekday_1',
    wednesday : 'weekday_2',
    thursday : 'weekday_3',
    friday : 'weekday_4',
    saturday : 'weekday_5',
    sunday : 'weekday_6',
}
var additfields = {
    prognosis : 'Прогноз',
    prognosis_date : 'Не позднее',
    payment : 'Поступление',
    payment_date : 'Прошло'
}
var phase_by_type = {
    0: {
        0:'Изучение закупочного центра',
        1:'Исследование потребностей',
        2:'Презентация решения',
        3:'Оформление договора',
        4:'Оплата'
    },
    1 : {
        0:'Изучение партнерского центра',
        1:'Исследование необходимостей',
        2:'Презентация партнерства',
        3:'Оформление партнерки',
        4:'Братухи'
    },
    2: {
        0:'Изучение закупочного центра',
        1:'Исследование потребностей',
        2:'Презентация решения',
        3:'Оформление договора',
        4:'Оплата'
    }
}
var field_values = {
    type_sale : {
        0 : 'Продажа',
        1 : 'Партнерство'
    },
    action : {
        callin : 'Звонок нам',
        callout : 'Звонок им',
        meetin : 'Встреча у нас',
        meetout : 'Встреча у них',
        note : 'Заметка'
    },
    failure_cause : {
        0 : 'Нет',
        1 : 'Не решит наших проблем',
        2 : 'Стоимость решения выше стоимости проблемы',
        3 : 'Не хотим покупать у вас',
        4 : 'Недостаточно знаем о вас',
        5 : 'Нет времени'
    },
    type : {
        'legal' :'юридическое',
        'individual' : 'физическое'
    },
    gender : {
        'male' : 'мужской',
        'female' : 'женский'
    },
    role : {
        0:'Администратор',
        1:'Влияющий на ЛПР',
        2:'ЛПР',
        3:'Покупатель',
        4:'Пользователь'
    },
    route : {
        0:'Администрация',
        1:'Логистика',
        2:'Маркетинг',
        3:'Персонал',
        4:'Продажи',
        5:'Производство',
        6:'Финансы'
    },
    mode : {
        0 : 'нет данных',
        1 : 'с 07:00 до 16:00',
        2 : 'с 07:30 до 16:30',
        3 : 'с 08:00 до 17:00',
        4 : 'с 08:30 до 17:30',
        5 : 'с 09:00 до 18:00',
        6 : 'с 09:30 до 18:30',
        7 : 'с 10:00 до 19:00',
        8 : 'Свободный график',
    },
    dinner_time : {
        0 : 'нет данных',
        1 : 'с 11:00 до 12:00',
        2 : 'с 12:00 до 13:00',
        3 : 'с 13:00 до 14:00',
    },
    country : {
        '0000' : 'Выберите страну',
        '0001' : 'Россия',
        '0002' : 'Азербайджан',
        '0003' : 'Армения',
        '0004' : 'Белоруссия',
        '0005' : 'Казахстан',
        '0006' : 'Киргизия',
        '0007' : 'Молдавия',
        '0008' : 'Таджикистан',
        '0009' : 'Туркмения',
        '0010' : 'Узбекистан',
        '0011' : 'Украина'
    }
}
managers = [4,11,15]; show_only_managers = true;
field_values.dinner_time_c = field_values.dinner_time;
field_values.work_mode_c  = field_values.work_mode = field_values.mode; //чтобы не дублировать массивы, создается ссылка
var user_popup = "<div class='settings_popup hidden'><div class='popup_text' id='user_cab'>"+lang.settings+"</div><div class='popup_text' id='logout'>"+lang.logout+"</div></div>";
var lookup_pop = '<div id="lookup_popup" class="hidden">\
                    <div class="icon-x-blue"></div>\
        <div class="search_wrapper">\
            <input class="field_search" name="seachtext" id="keyword">\
            <div id="start_search" class="icon-lookup-blue"></div>\
        </div>\
        <div class="check_wrapper">\
            <span class="trig active_state" id="s_legal">'+lang.company+'</span>\
            <span class="trig" id="s_individual">'+lang.man+'</span>\
        </div>\
        <div class="found_list">\
        </div>\
    </div>';
var mark = '<tr class="sizable">\
                <td class="mark">\
                    <div class="caption">\
                        <div class="image">\
                            <div class="icon-box-unchecked"></div>\
                        </div>\
                    </div>\
                </td>';
var report_table_head = '<div id="content_title">\
            <div id="title_id">'+lang.plan_report+'</div>\
            <div class="table_period" id="day">\
                '+lang.day+'\
            </div>\
            <div class="table_period current_period" id="month">\
                '+lang.month+'\
                </div>\
            <div class="table_period" id="year">\
                '+lang.year+'\
            </div>\
        </div>';
var report_voron_vha = '<div id="report1" class="report1_head">\
                    <div id="content">\
                    <div id="head">\
                <div class="leftside" id="left_head">'+localization.funnel_head.phases+'</div>\
                <div class="center" id="center_head">'+localization.funnel_head.funnel+'</div>\
            </div></div></div>';
var companyinfo_add = '<div id="xContent"><div class="cont" id="data"><div class="cont" id="info">';
var accept_doc = '"application/vnd.ms-excel|application/vnd.ms-powerpoint|application/msword|application/pdf"';
var lorder = '<div class="vertical_block" id="current_order">\
                <div class="vertical_block_top">\
                    <span class="ordertable_header">'+lang.order+' 1356</span>\
                    <div class="order_entry_edit">'+lang.add+'</div>\
                    <div class="order_entry_delete">'+lang.edit+'</div>\
                    <div class="order_entry_add">'+lang.delete+'</div>\
                </div>\
                <table class="order_table" id="table1"></table>\
                <div id="current_total_price">'+lang.total+' <span class="total"></span> р.</div>\
            </div>';

var rorder = '<div class="vertical_block" id="products_available">\
                <div class="vertical_block_top">\
                    <span class="ordertable_header"></span>\
                    <div class="product_add_button">'+lang.add+'</div>\
                </div>\
                <table class="order_table" id="table2"></table>\
            </div>';
var products_dum_row_head = '<tr class="av_order_head"><th class="id"></th><th class="id_cat"></th><th class="first_row product">'+lang.nomination+'</th><th class="cost">'+lang.price+'</th><th class="storage">'+lang.storage+'</th><th class="stored">'+lang.stored+'</th><th class="available">'+lang.available+'</th><th class="service"></th></tr>';
var products_dum_row = '<tr><td class="id"></td><td class="product first_row" contenteditable></td><td class="cost" contenteditable></td><td class="service" contenteditable></td><td class="storage" contenteditable></td><td class="stored"></td><td class="available"></td><td class="id_cat"></td></tr>'
