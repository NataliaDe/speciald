
/*----------------------------------- Calendar ---------------------------------------*/
$(function () {
    /* дата и время поступления сообщения */
//    $('.date-time-cal, #date-time-cal').datetimepicker({
//       // language: 'ru',
//        defaultDate: new Date(),
//        format: 'DD.MM.YYYY HH:mm:ss'
//                // autoclose: true
//    });
//
//
//        $('.date-cal, #date-time-cal').datetimepicker({
//       // language: 'ru',
//        defaultDate: new Date(),
//        format: 'DD.MM.YYYY'
//                // autoclose: true
//    });


//$('.date-cal').datepicker({
//    format: 'dd.mm.yyyy',
//    defaultDate: new Date(),
//    time:true,
//    language: 'ru'
//
//});


});





$('.date-cal-default-empty').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
//    "minYear": 2020,
//    "maxYear": parseInt(moment().add('years', 1).format('YYYY'), 10),
    "autoApply": true,
    autoUpdateInput: false,
    // "startDate": "03.03.2020",


    locale: {
        format: 'DD.MM.YYYY',
        applyLabel: 'Применить',
        cancelLabel: 'Отменить',
        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],
        "monthNames": [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        "firstDay": 1
    }
});

$('.date-cal-default-empty').on('apply.daterangepicker', function (event, picker) {
    var date = picker.startDate.format('DD.MM.YYYY');
    $(this).val(date);
});
//$('.date-cal-default-empty').on('cancel.daterangepicker', function(ev, picker) {
//  //do something, like clearing an input
//  $('.date-cal-default-empty').val('');
//});


$('.date-cal').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    "autoApply": true,
//    "minYear": 2020,
//    "maxYear": parseInt(moment().add('years', 1).format('YYYY'), 10),

    locale: {
        format: 'DD.MM.YYYY',
        applyLabel: 'Применить',
        cancelLabel: 'Отменить',
        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],
        "monthNames": [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        "firstDay": 1
    }
});

$('.date-time-sec-cal').daterangepicker({
    autoUpdateInput: false,
    singleDatePicker: true,
    showDropdowns: true,
    "timePicker": true,
    "timePicker24Hour": true,
    "timePickerSeconds": true,
    // autoUpdateInput: false,

//maxDate: moment().startOf('year').add('years', 1).format('DD/MM/YYYY')

//    "minYear": 2020,
//    "maxYear": parseInt(moment().add('years', 1).format('YYYY'), 10),
    //"autoApply": true,

    locale: {
        format: 'DD.MM.YYYY HH:mm:ss',
        applyLabel: 'Применить',
        cancelLabel: 'Отменить',

        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],
        "monthNames": [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        "firstDay": 1
    }
});

$('.date-time-sec-cal').on('apply.daterangepicker', function (event, picker) {
    var date = picker.startDate.format('DD.MM.YYYY HH:mm:ss');
    $(this).val(date);
});



/*** поле дата/время  разрешено только . и : *****/
$('.datetime, .date-time-sec-cal, .date-time-cal, .date-time-without-sec-cal').keypress(function (key) {
    if ((key.charCode < 48 && key.charCode !== 46) || (key.charCode > 57 && key.charCode !== 58))
        return false;
});


/*------------------------------------- END Calendar -----------------------------------*/


/*------------------ date + time without seconds - depend user settings ---------------------*/



$('.date-time-without-sec-cal').daterangepicker({
    autoUpdateInput: false,
    singleDatePicker: true,
    showDropdowns: true,
    "timePicker": true,
    "timePicker24Hour": true,

    // autoUpdateInput: false,

//maxDate: moment().startOf('year').add('years', 1).format('DD/MM/YYYY')

//    "minYear": 2020,
//    "maxYear": parseInt(moment().add('years', 1).format('YYYY'), 10),
    //"autoApply": true,

    locale: {
        format: 'DD.MM.YYYY HH:mm',
        applyLabel: 'Применить',
        cancelLabel: 'Отменить',

        "daysOfWeek": [
            "Вс",
            "Пн",
            "Вт",
            "Ср",
            "Чт",
            "Пт",
            "Сб"
        ],
        "monthNames": [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь"
        ],
        "firstDay": 1
    }
});

$('.date-time-without-sec-cal').on('apply.daterangepicker', function (event, picker) {
    var date = picker.startDate.format('DD.MM.YYYY HH:mm');
    $(this).val(date);
});



/*------------------ END date + time without seconds - depend user settings ---------------------*/





