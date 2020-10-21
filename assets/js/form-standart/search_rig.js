$('#close-modal-search-rig').on('click', function (event) {
    $('#modal-search-rig').click();
});
$('#close-modal-agree-get-rig-data').on('click', function (event) {
    $('#modal-search-rig').css('opacity', '');
    $('#modal-agree-get-rig-data').click();
});



$(document).ready(function () {
    $('.select2-select').select2({
        placeholder: "Выберите из списка",
        // allowClear: true,
        "language": {
            "noResults": function () {
                return "Ничего не найдено";
            }
        }
    });
    jQuery("#id_local").chained("#id_region");
    jQuery("#id_organ").chained("#id_local");

});



function update(url) {

    $('#btn-get-data-rig').attr('disabled', true);
    $("#result-search-rig").html('');
    $('#preload-update-data').css('display', 'block');

    $.get(url, $("#searchRigForm").serialize(), function (res) {
        $('#preload-update-data').css('display', 'none');
        $("#result-search-rig").html(JSON.parse(res)['innerHtml']);

//        if (JSON.parse(res)['is_data'] === 1) {
//            $('#btn-get-data-rig').attr('disabled', false);
//        } else {
//            $('#btn-get-data-rig').attr('disabled', true);
//        }
    }).fail(function (data, textStatus, xhr) {
        //This shows status code eg. 403
        console.log("error", data.status);
        //This shows status message eg. Forbidden
        console.log("STATUS: " + xhr);
    });
}

$('#btn-search-rig').on('click', function (event) {

    var button = $(event.relatedTarget);
    var url = $(this).data('url');

    update(url);

});



function selectRig(r) {

    if ($(r).val() !== '' && $(r).val() !== 0) {

        $('#btn-get-data-rig').attr('data-rig', $(r).val());
        $('#btn-get-data-rig').attr('disabled', false);
    } else {
        $('#btn-get-data-rig').attr('data-rig', 0);
        $('#btn-get-data-rig').attr('disabled', true);
    }
}


$('#modal-agree-get-rig-data').on('show.bs.modal', function (event) {
    $('#modal-search-rig').css('opacity', '0.8');
});

/*  get data by rig and fill special form with this data */
$('#btn-fill-form').on('click', function (event) {


    $('#preload-update-data-search-rig').css('display', 'block');
    $('body').css('opacity', 0.5);

    var id_rig = $('#btn-get-data-rig').attr('data-rig');
    var url = $('#btn-get-data-rig').attr('data-url');


    var data = {'id_rig': id_rig};

    $.get(url, data, function (res) {

        //$('#preload-update-data').css('display', 'none');


        if (parseInt(JSON.parse(res)['is_data']) === 1) {
            $("#accordion2").html('');
            $("#accordion2").html(JSON.parse(res)['opening_block']);
            $("#middle-block-div").html('');
            $("#middle-block-div").html(JSON.parse(res)['middle_block']);

            $("#silymchs-block-div").html('');
            $("#silymchs-block-div").html(JSON.parse(res)['silymchs']);

            $("#innerservice-block-div").html('');
            $("#innerservice-block-div").html(JSON.parse(res)['innerservice']);
            $("#informing-block-div").html('');
            $("#informing-block-div").html(JSON.parse(res)['informing']);

            $("#accordion4").html('');
            $("#accordion4").html(JSON.parse(res)['final_block']);

            $("#div-str").html('');
            $("#div-str").html(JSON.parse(res)['str_block']);

            $("#trunks_data-block-div").html('');
            $("#trunks_data-block-div").html(JSON.parse(res)['trunks_block']);


            $("#object-data").html('');
            $("#object-data").html(JSON.parse(res)['object_data']);

            $("#object-floor-div").html('');
            $("#object-floor-div").html(JSON.parse(res)['object_floor']);

            $("#object-floor-flat-div").html('');
            $("#object-floor-flat-div").html(JSON.parse(res)['object_floor_flat']);


            $("#people-rig-data").html('');
            $("#people-rig-data").html(JSON.parse(res)['people_rig_data']);

            $("#law_face_office_belong").html('');
            $("#law_face_office_belong").html(JSON.parse(res)['law_face_office_belong']);

            $("#owner_from_jour").html('');
            $("#owner_from_jour").html(JSON.parse(res)['owner_from_jour']);

            setPreviewData();// set preview start text

            if (parseInt(JSON.parse(res)['id_face_belong']) === 1) {

                $('#id_face_belong').val(1);
                $('#id_face_belong').trigger("change");
            } else {

                $('#id_face_belong').val('');
                $('#id_face_belong').trigger("change");
            }

            $('.select2-select').select2({
                placeholder: "Выберите из списка",
                "language": {
                    "noResults": function () {
                        return "Ничего не найдено";
                    }
                }
            });


            $('.select2-single').select2({
                placeholder: "Выберите из списка",
                allowClear: true,
                "language": {
                    "noResults": function () {
                        return "Ничего не найдено";
                    }
                }
            });

            $('.select2-multi').select2({
                placeholder: "Выберите из списка",
                allowClear: true,
                "language": {
                    "noResults": function () {
                        return "Ничего не найдено";
                    }
                }
            });


                    $('.select2-single-theme-msg').select2({
        placeholder: "Поступило сообщение",
        allowClear: true,
        "language": {
            "noResults": function () {
                return "Ничего не найдено";
            }
        }
    });


            $('.select2-select').trigger("change");
            $('.select2-single').trigger("change");
            $('.select2-multi').trigger("change");

            jQuery("#lat_id").mask("99.999999");//долгота
            jQuery("#long_id").mask("99.999999");//широта


            jQuery("#vid_hs_2").chained("#vid_hs_1");



            /*----------------------------------- Calendar ---------------------------------------*/


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

            $('.date-cal').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                "autoApply": true,

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

            /* END calendar*/





            $('#preload-update-data-search-rig').css('display', 'none');
            $('body').css('opacity', 1);


            toastr.success('Данные по выезду с ID = ' + id_rig + ' успешно выбраны', 'Успех!', {progressBar: true, timeOut: 2500});
            $('#modal-search-rig').css('opacity', '');
            $('#modal-search-rig').click();
            $('#modal-agree-get-rig-data').click();
        } else {

            $('body').css('opacity', 1);
            toastr.error('Данные по выезду с ID = ' + id_rig + ' не найдены', 'Ошибка!', {progressBar: true, timeOut: 2500});

        }
    });

});


$('#modal-search-rig #searchRigForm #id_region').on('change', function (event) {

    var reg = $(this).val();

    if (parseInt(reg) === 3) {
        $('#modal-search-rig #searchRigForm #id_local_block').addClass('hide');
    } else {
        $('#modal-search-rig #searchRigForm #id_local_block').removeClass('hide');
    }

});

