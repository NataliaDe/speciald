$('#close-delete-sd-modal').on('click', function (event) {
    $('#delete-sd-modal').click();
});
$('#close-history-sd-modal').on('click', function (event) {
    $('#history-sd-modal').click();
});

$('#delete-sd-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    $(this).find('#delete-sd-btn').attr('data-url', '');
    $(this).find('.modal-body p b span').text('');
    $(this).find('#delete-sd-btn').attr('data-url', button.data('url'));
    $(this).find('.modal-body p b span').text(button.data('number-sd'));
});



$('#delete-sd-modal #delete-sd-btn').on('click', function (e) {
    e.preventDefault();
    var button = $(this);
    $.ajax({
        type: 'GET',
        url: button.data('url'),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                toastr.success(response.success, 'Успех:', {progressBar: true, timeOut: 2500});
                $('#delete-sd-modal').click();
                setTimeout(function () {
                    location.reload();
                }, 2500);
            }
        },
        error: function (response) {
            toastr.error(response.error, 'Ошибка:', {progressBar: true, timeOut: 2500});

        }
    });
});



$('#history-sd-modal').on('show.bs.modal', function (e) {

    var btn = $(e.relatedTarget);

    var sd_id = btn.data('id');

    $(this).find('.modal-content .header-name  span').text('');
    $(this).find('.modal-content .header-name  span').text(btn.data('number-sd'));
    $('#history-sd-modal').find('.modal-body').html('');


    if (sd_id !== '') {
        $.ajax({
            type: 'POST',
            url: btn.data('url'),

            data: {'sd_id': sd_id}
        }).done(function (response) {

            if (JSON.parse(response)['success'] === 1) {

                $('#history-sd-modal').find('.modal-body').html(JSON.parse(response)['result']);

            } else {
               $('#history-sd-modal').find('.modal-body').html(JSON.parse(response)['error']);
               toastr.warning(JSON.parse(response)['error'], '', {progressBar: true, timeOut: 2500});

            }

        });
    } else {
        toastr.warning('СД не выбрано', '', {progressBar: true, timeOut: 2500});

    }
});



$('#close-set-number-sd-modal').on('click', function (event) {
    $('#set-number-sd-modal').click();
});

$('#set-number-sd-modal').on('show.bs.modal', function (e) {

    var btn = $(e.relatedTarget);

    var sd_id = btn.data('id');
    var number = btn.data('number');

    $(this).find('.modal-body p b span').text('');
    $(this).find('.modal-body p b span').text(sd_id);
    $(this).find('#number_sd').val('');
    $(this).find('#number_sd').val(number);
    $(this).find('#sd_id').val('');
    $(this).find('#sd_id').val(sd_id);

});



$('#set-number-sd-modal #set-number-sd-btn').on('click', function (e) {

    var sd_id = $('#set-number-sd-modal').find('#sd_id').val();
    var sd_number = $('#set-number-sd-modal').find('#number_sd').val();

    var url = $(this).attr('data-url');

    if (sd_id !== '' && sd_number !== '') {
        $.ajax({
            type: 'POST',
            url: url,

            data: {'sd_id': sd_id, 'sd_number': sd_number}
        }).done(function (response) {

            if (JSON.parse(response)['success'] === 1) {

                toastr.success('Номер СД успешно обновлен', '', {progressBar: true, timeOut: 2500});
                $('#set-number-sd-modal').click();
                setTimeout(function () {
                    location.reload();
                }, 2500);
            } else {
                toastr.error(response.error, '', {progressBar: true, timeOut: 2500});
            }

        });
    } else {
        toastr.error('Укажите номер СД', '', {progressBar: true, timeOut: 2500});
    }

});


/*---------- prove sd -----------*/
$('#close-prove-sd-modal').on('click', function (event) {
    $('#prove-sd-modal').click();
});


$('#prove-sd-modal').on('show.bs.modal', function (e) {

    var btn = $(e.relatedTarget);

    var number = btn.data('number-sd');

    $(this).find('.modal-body p b span').text('');
    $(this).find('.modal-body p b span').text(number);

    $(this).find('#prove-sd-btn').attr('data-url', '');
    $(this).find('#prove-sd-btn').attr('data-url', btn.data('url'));


});


$('#prove-sd-modal #prove-sd-btn').on('click', function (e) {
    e.preventDefault();
    var button = $(this);
    $.ajax({
        type: 'GET',
        url: button.data('url'),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                toastr.success(response.success, 'Успех:', {progressBar: true, timeOut: 2500});
                $('#prove-sd-modal').click();
                setTimeout(function () {
                    location.reload();
                }, 2500);
            }
        },
        error: function (response) {
            toastr.error(response.error, 'Ошибка:', {progressBar: true, timeOut: 2500});

        }
    });
});


/* detail prove */


$('#detail-prove-sd-modal').on('show.bs.modal', function (e) {

    var btn = $(e.relatedTarget);

    var number = btn.data('number-sd');
    var who = btn.data('who');
    var time = btn.data('time');
    var level = btn.data('level');

    var header='';

    $(this).find('.modal-header h5 span').text('');


    if(level === 1){//rcu
        var header='СД подтверждено РЦУРЧС';

    }
    else{
        var header='СД подтверждено УМЧС';
    }

$(this).find('.modal-header h5 span').text(header);

    $(this).find('.modal-body p b span').text('');
    $(this).find('.modal-body p b span').text(number);

    $(this).find('.modal-body #time').text('');
    $(this).find('.modal-body #time').text(time);

    $(this).find('.modal-body #who').text('');
    $(this).find('.modal-body #who').text(who);



});

/*  refuse sd */

$('#refuse-sd-modal').on('show.bs.modal', function (e) {

    var btn = $(e.relatedTarget);

    var sd_id = btn.data('id');
    var number = btn.data('number');
    var description_refuse=btn.data('description-refuse');
    var is_refresh = btn.data('refresh');

    $(this).find('.modal-body p b span').text('');
    $(this).find('.modal-body p b span').text(number);

    $(this).find('#description_refuse').val('');
    $(this).find('#description_refuse').val(description_refuse);

    $(this).find('#number_sd').val('');
    $(this).find('#number_sd').val(number);

    $(this).find('#sd_id').val('');
    $(this).find('#sd_id').val(sd_id);


    $(this).find('#is_refresh').val(0);
    $(this).find('#is_refresh').val(is_refresh);


    if (is_refresh === 1) {
        $(this).find('#refuse-sd-btn').text('Обновить');
        $(this).find('.modal-title').text('Обновить замечание');
    } else {
        $(this).find('#refuse-sd-btn').text('Отправить на доработку');
        $(this).find('.modal-title').text('Отклонить СД');
    }


});

$('#refuse-sd-modal #refuse-sd-btn').on('click', function (e) {

    var sd_id = $('#refuse-sd-modal').find('#sd_id').val();
    var description_refuse = $('#refuse-sd-modal').find('#description_refuse').val();
     var is_refresh = $('#refuse-sd-modal').find('#is_refresh').val();

    var url = $(this).attr('data-url');

    if (sd_id !== '' && description_refuse !== '') {
        $.ajax({
            type: 'POST',
            url: url,

            data: {'sd_id': sd_id, 'description_refuse': description_refuse, "is_refresh": is_refresh}
        }).done(function (response) {

            if (JSON.parse(response)['success'] === 1) {

                toastr.success('СД успешно отправлено на доработку', '', {progressBar: true, timeOut: 2500});
                $('#refuse-sd-modal').click();
                setTimeout(function () {
                    location.reload();
                }, 2500);
            } else {
                toastr.error(response.error, '', {progressBar: true, timeOut: 2500});
            }

        });
    } else {
        toastr.warning('Укажите замечания', '', {progressBar: true, timeOut: 2500});
    }

});




/* detail refuse */


//$('#detail-refuse-sd-modal').on('show.bs.modal', function (e) {
//
//    var btn = $(e.relatedTarget);
//
//    var number = btn.data('number-sd');
//    var who = btn.data('who');
//    var time = btn.data('time');
//     var descr = btn.data('descr');
//
//    $(this).find('.modal-body p b span').text('');
//    $(this).find('.modal-body p b span').text(number);
//
//    $(this).find('.modal-body #time').text('');
//    $(this).find('.modal-body #time').text(time);
//
//    $(this).find('.modal-body #who').text('');
//    $(this).find('.modal-body #who').text(who);
//
//
//     $(this).find('.modal-body #descr').text('');
//    $(this).find('.modal-body #descr').text(descr);
//
//
//
//});


$('#detail-refuse-sd-modal').on('show.bs.modal', function (e) {

    var btn = $(e.relatedTarget);

    var sd_id = btn.data('id');
    var level = btn.data('level');

    var header='';

    $(this).find('.modal-header h5 span').text('');

    if(level === 1){//rcu
        var header='СД отклонено РЦУРЧС';

    }
    else{
        var header='СД отклонено УМЧС';
    }

$(this).find('.modal-header h5 span').text(header);

    $(this).find('.modal-body .number b').text('');
    $(this).find('.modal-body .number b').text(btn.data('number-sd'));


    if (sd_id !== '') {
        $.ajax({
            type: 'POST',
            url: btn.data('url'),

            data: {'sd_id': sd_id,'level':level}
        }).done(function (response) {

            if (JSON.parse(response)['success'] === 1) {

                $('#detail-refuse-sd-modal').find('.modal-body .ajax').html(JSON.parse(response)['result']);
            } else {
                toastr.warning(response.error, '', {progressBar: true, timeOut: 2500});
            }

        });
    } else {
        toastr.warning('СД не выбрано', '', {progressBar: true, timeOut: 2500});
    }
});

/* open update  */

$('#open-update-sd-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    $(this).find('#open-update-sd-btn').attr('data-url', '');
    $(this).find('.modal-body p b span').text('');
    $(this).find('#open-update-sd-btn').attr('data-url', button.data('url'));
    $(this).find('.modal-body p b span').text(button.data('number-sd'));
});



$('#open-update-sd-modal #open-update-sd-btn').on('click', function (e) {
    e.preventDefault();
    var button = $(this);
    $.ajax({
        type: 'GET',
        url: button.data('url'),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                toastr.success(response.success, 'Успех:', {progressBar: true, timeOut: 2500});
                $('#open-update-sd-modal').click();
                setTimeout(function () {
                    location.reload();
                }, 2500);
            }
        },
        error: function (response) {
            toastr.error(response.error, 'Ошибка:', {progressBar: true, timeOut: 2500});

        }
    });
});



/* open update  */

$('#close-update-sd-modal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    $(this).find('#close-update-sd-btn').attr('data-url', '');
    $(this).find('.modal-body p b span').text('');
    $(this).find('#close-update-sd-btn').attr('data-url', button.data('url'));
    $(this).find('.modal-body p b span').text(button.data('number-sd'));
});



$('#close-update-sd-modal #close-update-sd-btn').on('click', function (e) {
    e.preventDefault();

    var button = $(this);
    $.ajax({
        type: 'GET',
        url: button.data('url'),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                toastr.success(response.success, 'Успех:', {progressBar: true, timeOut: 2500});
                $('#close-update-sd-modal').click();
                setTimeout(function () {
                    location.reload();
                }, 2500);
            }
        },
        error: function (response) {
            toastr.error(response.error, 'Ошибка:', {progressBar: true, timeOut: 2500});

        }
    });
});



/* copy SD */
$('#modal-copy-sd').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);

    $(this).find('#btn-create-copy').attr('href', '#');
    $(this).find('.modal-body p b span').text('');
    $(this).find('#btn-create-copy').attr('href', button.data('url'));
    $(this).find('.modal-body p b span').text(button.data('number-sd'));
});
