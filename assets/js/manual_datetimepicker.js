
/*----------------------------------- Calendar ---------------------------------------*/
$(function () {
    /* дата и время поступления сообщения */
    $('.date-time-cal, #date-time-cal').datetimepicker({
       // language: 'ru',
        defaultDate: new Date(),
        format: 'DD.MM.YYYY HH:mm:ss'
                // autoclose: true
    });


        $('.date-cal, #date-time-cal').datetimepicker({
       // language: 'ru',
        defaultDate: new Date(),
        format: 'DD.MM.YYYY'
                // autoclose: true
    });



});

    /*** поле дата/время  разрешено только . и : *****/
$('.datetime').keypress(function (key) {
    if ((key.charCode < 48 && key.charCode !== 46) || (key.charCode > 57 && key.charCode !== 58))
        return false;
});




/*------------------------------------- END Calendar -----------------------------------*/