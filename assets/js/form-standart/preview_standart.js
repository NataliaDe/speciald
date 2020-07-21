

//$('body').on('input change keyup', '#createStandart #middle-block-div input[name="time_msg"]', function (e) {
//    setPreviewData();
//});
//
//
//$('body').on('click', '.daterangepicker .applyBtn ', function (e) {
//    setPreviewData();
//});
//
//
//$('body').on('change', '#createStandart #middle-block-div #lat_id', function (e) {
//    setPreviewData();
//});
//
//
//$('body').on('change', '#createStandart #middle-block-div #long_id', function (e) {
//    setPreviewData();
//
//
//});
//
//
//
//$('body').on('input change keyup', '#createStandart #opening_description_id', function (e) {
//    setPreviewData();
//
//});
//
//
//$('body').on('input change keyup', '#createStandart #middle-block-div textarea[name="address"]', function (e) {
//    setPreviewData();
//
//});
//
//
//$('body').on('change', '#createStandart #middle-block-div #is_show_address', function (e) {
//    setPreviewData();
//
//
//});
//
//
//function setPreviewData() {
//
//
//    var date = $('#createStandart #middle-block-div input[name="time_msg"]').val();
//    var time = $('#createStandart #middle-block-div input[name="time_msg"]').val();
//    var descr = $("#createStandart #opening_description_id").val();
//    var address = $('#createStandart #middle-block-div textarea[name="address"]').val();
//    var lat = $('#createStandart #middle-block-div #lat_id').val();
//    var long = $('#createStandart #middle-block-div #long_id').val();
//    var is_show_address = $('#createStandart #middle-block-div #is_show_address').is(":checked");
//
//
//
//
//    var new_date = '';
//    var new_time = '';
//    var new_descr = '';
//    var new_addr = '';
//    var new_lat = '';
//    var new_long = '';
//    var new_coord = '';
//
//
//    var arr_date = date.split(' ');
//
//    if (date !== '') {
//        new_date = arr_date[0] + ' года ';
//    }
//
//    if (time !== '') {
//
//        var arr_time = arr_date[1].split(':');
//        new_time = 'в ' + arr_time[0] + ':' + arr_time[1] + ' ';
//    }
//
//
//
//    new_coord = ' (' + lat + ', ' + long + ').';
//
//    if (lat === '' && long === '') {
//        new_coord = ' (нет координат).';
//    }
//
//
////            if (address !== '') {
////            address = address.replace(/\r?\n/g, '<br />');
////
////            var arr_new_address = address.split('<br />');
////            var arr_new_address_str = arr_new_address.join('<br />');
////
////            address = arr_new_address_str;
////        }
//
//    if (address !== '' && is_show_address === true) {
//        new_coord = ' ' + address + new_coord;
//    }
//
//
//
//    if (descr !== '') {
//        new_descr = descr.replace(/\r?\n/g, '<br />');
//
//        var arr_new_descr = new_descr.split('<br />');
//        arr_new_descr[0] = arr_new_descr[0] + '' + new_coord;
//        var arr_new_descr_str = arr_new_descr.join('<br />');
//    } else {
//        var arr_new_descr_str = new_coord;
//    }
//
//    $('#preview-opening-description-standart').html('<b><u>Предпросмотр (начальный текст):</u></b><br>' + new_date + '' + new_time + '' + arr_new_descr_str);
//
//
//}


$('body').on('input change keyup', '#createStandart #middle-block-div input[name="time_msg"]', function (e) {
    setPreviewData();
});


$('#createStandart #middle-block-div input[name="time_msg"]').on('apply.daterangepicker', function (e, picker) {
    setPreviewData();
});
//$('body').on('click', '.daterangepicker .applyBtn ', function (e) {
//    setPreviewData();
//});


$('body').on('change', '#createStandart #middle-block-div #lat_id', function (e) {
    setPreviewData();
});


$('body').on('change', '#createStandart #middle-block-div #long_id', function (e) {
    setPreviewData();


});



$('body').on('input change keyup', '#createStandart #opening_description_id', function (e) {
    setPreviewData();

});


$('body').on('input change keyup', '#createStandart #middle-block-div textarea[name="address"]', function (e) {
    setPreviewData();

});


$('body').on('change', '#createStandart  #is_show_opening_descr', function (e) {
    setPreviewData();
});
$('body').on('input change keyup', '#createStandart  input[name="official_creator_name"]', function (e) {
    setPreviewData();
});
$('body').on('input change keyup', '#createStandart  input[name="people_fio"]', function (e) {
    setPreviewData();
});
$('body').on('input change keyup', '#createStandart  input[name="people_phone"]', function (e) {
    setPreviewData();
});


function setPreviewData() {


    var date = $('#createStandart #middle-block-div input[name="time_msg"]').val();
    var time = $('#createStandart #middle-block-div input[name="time_msg"]').val();
    var descr = $("#createStandart #opening_description_id").val();
    var address = $('#createStandart #middle-block-div textarea[name="address"]').val();
    var lat = $('#createStandart #middle-block-div #lat_id').val();
    var long = $('#createStandart #middle-block-div #long_id').val();
    var is_show_opening_descr = $('#createStandart #is_show_opening_descr').is(":checked");




    if (is_show_opening_descr === true) {


        var new_date = '';
        var new_time = '';
        var new_descr = '';
        var new_addr = '';
        var new_lat = '';
        var new_long = '';
        var new_coord = '';


        var arr_date = date.split(' ');

        if (date !== '') {
            new_date = arr_date[0] + ' года ';
        }

        if (time !== '') {

            var arr_time = arr_date[1].split(':');
            new_time = 'в ' + arr_time[0] + ':' + arr_time[1];
        }



        new_coord = '(' + lat + ', ' + long + ').';

        if (lat === '' && long === '') {
            new_coord = '(нет координат).';
        }



        if (address !== '') {
            new_coord = ' ' + address + new_coord;
        }



        if ($.trim(descr) !== '') {

//        new_descr = ($.trim(descr)).replace(/\r?\n/g, '<br />');
//
//        var arr_new_descr = new_descr.split('<br />');
//        arr_new_descr[0] = arr_new_descr[0] + ' ' + new_coord;
//        var arr_new_descr_str = arr_new_descr.join('<br />');

            // new_descr = ($.trim(descr)).replace(/\r?\n/g, '<br />');

            var r = $.trim(descr);
            var arr_new_descr = r.split(/\r?\n/g);
            arr_new_descr[0] = arr_new_descr[0] + ' ' + new_coord;
            var arr_new_descr_str = arr_new_descr.join('\n');
        } else {

            var arr_new_descr_str = new_coord;
        }

        //$('#preview-opening-description-standart').html('<b><u>Предпросмотр (начальный текст):</u></b><br>' + new_date + '' + new_time + '' + arr_new_descr_str);
        $('#preview-opening-description-standart').find('textarea[name="opening_word"]').val(new_date + '' + new_time + ' ' + arr_new_descr_str);

    } else {

        var preview = '';

        var new_date = '';
        var new_time = '';
        var new_descr = '';
        var new_addr = '';
        var new_lat = '';
        var new_long = '';
        var new_coord = '';

        var creator = $('#createStandart input[name="official_creator_name"]').val();
        var people_fio = $('#createStandart input[name="people_fio"]').val();
        var people_phone = $('#createStandart input[name="people_phone"]').val();


        var arr_date = date.split(' ');

        if (date !== '') {
            new_date = arr_date[0] + ' года ';
            preview = new_date;
        }

        if (time !== '') {

            var arr_time = arr_date[1].split(':');
            new_time = 'в ' + arr_time[0] + ':' + arr_time[1];

            preview = preview + new_time;
        }


        if (creator !== '') {
            preview = preview + ' в ' + creator;
        }

        if (people_fio !== '') {
            var rn = new RussianName($.trim(people_fio));
            var f = rn.fullName(rn.gcaseRod); // from ...

            var gender = rn.getSex();

            if (gender !== '' && gender === 'm') {
                preview = preview + ' от гражданина ' + f;
            } else if (gender !== '' && gender === 'f') {
                preview = preview + ' от гражданки ' + f;
            } else {
                preview = preview + ' от гражданина ' + f;
            }


        }

        if (people_phone !== '') {
            preview = preview + ' с телефона ' + people_phone;
        }

        preview = preview + ' поступило сообщение о пожаре жилого дома';


        if (address !== '') {
            preview = preview + ' по адресу: ' + address + '.';
        } else {
            preview = preview + '.';
        }



        //new_coord = '(' + lat + ', ' + long + ').';

        if (lat === '' && long === '') {
            //preview=preview+' Нет координат.';

        } else {
            preview = preview + ' Координаты: ' + lat + ', ' + long + '.';
        }



        $('#preview-opening-description-standart').find('textarea[name="opening_word"]').val(preview);

    }


}



$('body').on('change', '#createStandart  #object_material_id', function (e) {
    setPreviewDataObject();


});

$('body').on('input change keyup', '#createStandart input[name="object_floor"]', function (e) {
    setPreviewDataObject();

});

$('body').on('change', '#createStandart  #object_roof_id', function (e) {
    setPreviewDataObject();


});


$('body').on('change', '#createStandart #object_is_electric', function (e) {
    setPreviewDataObject();


});

$('body').on('change', '#createStandart #object_is_api', function (e) {
    setPreviewDataObject();


});

$('body').on('change', '#createStandart #is_show_object', function (e) {
    setPreviewDataObject();


});


$('body').on('input change keyup', '#createStandart textarea[name="object"]', function (e) {
    setPreviewDataObject();

});


$('body').on('change', '#createStandart  #object_house_id', function (e) {
    setPreviewDataObject();


});

$('body').on('change', '#createStandart  #object-office-belong-id', function (e) {
    setPreviewDataObject();


});
$('body').on('change', '#createStandart #is_api_worked', function (e) {

    var is_api_worked = $(this).is(":checked");
    var div_api_influence = $('#createStandart .for-worked-api');
    var is_api_influence = $('#createStandart #is_api_influence');

    if (is_api_worked === true) {

        div_api_influence.removeClass('hide');
        div_api_influence.removeClass('show');
        div_api_influence.addClass('show');
    } else {
        is_api_influence.prop('checked', false);
        div_api_influence.removeClass('hide');
        div_api_influence.removeClass('show');
        div_api_influence.addClass('hide');
    }


    setPreviewDataObject();
});
$('body').on('change', '#createStandart #is_api_influence', function (e) {
    setPreviewDataObject();
});

$('body').on('change', '#createStandart #is_aps', function (e) {
    setPreviewDataObject();
});
$('body').on('change', '#createStandart #is_aps_worked', function (e) {

    var is_aps_worked = $(this).is(":checked");
    var div_aps_influence = $('#createStandart .for-worked-aps');
    var is_aps_influence = $('#createStandart #is_aps_influence');
    if (is_aps_worked === true) {

        div_aps_influence.removeClass('hide');
        div_aps_influence.removeClass('show');
        div_aps_influence.addClass('show');
    } else {
        is_aps_influence.prop('checked', false);
        div_aps_influence.removeClass('hide');
        div_aps_influence.removeClass('show');
        div_aps_influence.addClass('hide');
    }


    setPreviewDataObject();
});
$('body').on('change', '#createStandart #is_aps_influence', function (e) {
    setPreviewDataObject();
});
$('body').on('input change keyup', '#createStandart textarea[name="aps_name"]', function (e) {
    setPreviewDataObject();

});
$('body').on('change', '#createStandart #id_api_source', function (e) {
    setPreviewDataObject();
});
$('body').on('input change keyup blur', '#createStandart  input[name="api_date"]', function (e) {
    setPreviewDataObject();
});

$('body').on('input change keyup', '#createStandart input[name="object_floor_flat"]', function (e) {
    setPreviewDataObject();

});

$('body').on('input change keyup', '#createStandart input[name="object_cnt_rooms"]', function (e) {
    setPreviewDataObject();

});


function getFloorNumber(object_floor) {

    var $text = '';
    switch (parseInt(object_floor)) {
        case 1:
            $text = 'одно';
            break;
        case 2:
            $text = 'двух';
            break;
        case 3:
            $text = 'трех';
            break;

        case 4:
            $text = 'четырех';
            break;
        case 5:
            $text = 'пяти';
            break;

        case 6:
            $text = 'шести';
            break;
        case 7:
            $text = 'семи';
            break;
        case 8:
            $text = 'восьми';
            break;
        case 9:
            $text = 'девяти';
            break;
        case 10:
            $text = 'десяти';
            break;
        case 11:
            $text = 'одиннадцати';
            break;
        case 12:
            $text = 'двенадцати';
            break;
        case 13:
            $text = 'тренадцати';
            break;
        case 14:
            $text = 'четырнадцати';
            break;
        case 15:
            $text = 'пятнадцати';
            break;
        case 16:
            $text = 'шестнадцати';
            break;
        case 17:
            $text = 'семнадцати';
            break;
        case 18:
            $text = 'восемнадцати';
            break;
        case 19:
            $text = 'девятнадцати';
            break;
        case 20:
            $text = 'двадцати';
            break;
        case 21:
            $text = 'двадцатиодно';
            break;
        case 22:
            $text = 'двадцатидвух';
            break;
        case 23:
            $text = 'двадцатитрех';
            break;
        case 24:
            $text = 'двадцатичетырех';
            break;
        case 25:
            $text = 'двадцатипяти';
            break;


        default:
            $text = '';
            break;

    }
    return $text;
}


var arr_floor = ['нулевом', 'первом', 'втором', 'третьем', 'четвертом', 'пятом', 'шестом', 'седьмом', 'восьмом', 'девятом', 'десятом',
    'одинадцатом', 'двенадцатом', 'тринадцатом', 'четырнадцатом', 'пятнадцатом', 'шестнадцатом', 'семнадцатом', 'восемнадцатом', 'девятнадцатом', 'двадцатом',
    'двадцать первом', 'двадцать втором', 'двадцать третьем', 'двадцать четвертом', 'двадцать пятом'];



function setPreviewDataObject() {

    var id_face_belong = $('#createStandart #id_face_belong').val();




    var object = $('#createStandart textarea[name="object"]').val();

    var object_house_val = $('#createStandart #object_house_id option:selected').val();
    var object_house = $('#createStandart  #object_house_id option:selected').text();

    var material_val = $('#createStandart #object_material_id option:selected').val();
    var material = $('#createStandart  #object_material_id option:selected').text();

    var object_floor = $('#createStandart input[name="object_floor"]').val();

    var object_floor_flat = $('#createStandart input[name="object_floor_flat"]').val();
    var object_cnt_rooms = $('#createStandart input[name="object_cnt_rooms"]').val();

    var $text = '';

    $text = getFloorNumber(parseInt(object_floor));

    if ($text !== '') {
        $text = $text + 'этажный';
    }

    var roof_id = $('#createStandart #object_roof_id option:selected').val();
    var roof = $('#createStandart  #object_roof_id option:selected').text();

    var object_is_electric = $('#createStandart #object_is_electric').is(":checked");
    var object_is_api = $('#createStandart #object_is_api').is(":checked");


    var room = '';

    room = getFloorNumber(parseInt(object_cnt_rooms));

    if (room !== '') {
        room = 'квартира ' + room + 'комнатная';
    }


    //var floor_flat=declOfNum(live_tog, ['человек', 'человека', 'человек'])

//    var office_belong_val = $('#createStandart #object-office-belong-id option:selected').val();
//    var office_belong = $('#createStandart  #object-office-belong-id option:selected').text();


    var is_show_object = $('#createStandart #is_show_object').is(":checked");

//api
    var is_api_worked = $('#createStandart #is_api_worked').is(":checked");
    var is_api_influence = $('#createStandart #is_api_influence').is(":checked");
    var api_date = $('#createStandart input[name="api_date"]').val();

    var id_api_source_val = $('#createStandart #id_api_source option:selected').val();
    var id_api_source = $('#createStandart #id_api_source option:selected').text();

//aps
    var is_aps = $('#createStandart #is_aps').is(":checked");
    var is_aps_worked = $('#createStandart #is_aps_worked').is(":checked");
    var is_aps_influence = $('#createStandart #is_aps_influence').is(":checked");
    var aps_name = $('#createStandart textarea[name="aps_name"]').val();

    var preview = '';

    if (is_show_object === true) {

        $("#panel-preview-object").show();

        if (object !== '') {
            object = object.replace(/\r?\n/g, '<br />');

            var arr_new_descr = object.split('<br />');
            var arr_new_descr_str = arr_new_descr.join('<br />');

            preview = arr_new_descr_str;
        }




        if (object_house_val !== '') {
            if (preview === '')
                preview = object_house;
            else
                preview = preview + ' (' + object_house + ')';
        }

        if (material_val !== '') {

            if (preview === '')
                preview = 'Дом ' + material;
            else
                preview = preview + ' ' + material;
        }

        if ($text !== '') {
            if (preview === '')
                preview = $text;
            else
                preview = preview + ', ' + $text;
        }

        if (roof_id !== '') {
            if (preview === '')
                preview = 'кровля' + roof;
            else
                preview = preview + ', ' + 'кровля ' + roof;
        }

        if (object_is_electric === true) {
            if (preview === '')
                preview = 'электрофицирован';
            else
                preview = preview + ', ' + 'электрофицирован';
        } else {
            if (preview === '')
                preview = 'не электрофицирован';
            else
                preview = preview + ', ' + 'не электрофицирован';
        }

        if (room !== '') {
            if (preview === '')
                preview = room;
            else
                preview = preview + ', ' + room;
        }

        if (object_floor_flat !== '' && object_floor_flat > 0 && arr_floor[object_floor_flat] !== 'undefined' && arr_floor[object_floor_flat] !== undefined) {
            if (preview === '')
                preview = 'расположена на ' + arr_floor[object_floor_flat] + ' этаже';
            else
                preview = preview + ', расположена на ' + arr_floor[object_floor_flat] + ' этаже';
        }


        if (parseInt(id_face_belong) === 1) {

            if (object_is_api === true) {
                if (preview === '')
                    preview = 'АПИ установлен';
                else
                    preview = preview + ', ' + 'АПИ установлен';


                if (api_date !== '')
                    preview = preview + ' ' + api_date;

                if (id_api_source_val !== '')
                    preview = preview + ' ' + ' (источник финансирования: ' + id_api_source + ')';

                if (is_api_worked === true) {
                    preview = preview + ' и сработал';
                    if (is_api_influence === false) {
                        preview = preview + ' (на обнаружение загорания не повлиял)';
                    } else {
                        preview = preview + ' (повлиял на обнаружение загорания)';
                    }
                } else {
                    preview = preview + ' и не сработал';
                }

            } else {
                if (preview === '')
                    preview = 'АПИ не установлен';
                else
                    preview = preview + ', ' + 'АПИ не установлен';
            }
        } else if (parseInt(id_face_belong) === 2) {//law

            if (is_aps === true) {
                if (preview === '')
                    preview = 'АПС установлена';
                else
                    preview = preview + ', ' + 'АПС установлена';


                if (aps_name !== '') {
                    preview = preview + ' (' + aps_name + ')';
                }

                if (is_aps_worked === true) {
                    preview = preview + ' и сработала';
                    if (is_aps_influence === false) {
                        preview = preview + ' (на обнаружение загорания не повлияла)';
                    } else {
                        preview = preview + ' (повлияла на обнаружение загорания)';
                    }
                } else {
                    preview = preview + ' и не сработала';
                }



            } else {
                if (preview === '')
                    preview = 'АПС отсутствует';
                else
                    preview = preview + ', ' + 'АПС отсутствует';
            }
        }


        if (preview !== '')
            preview = preview + '.';

//        if (office_belong_val !== '') {
//
//
//            if (preview === '')
//                preview = 'Ведомственная принадлежность ' + office_belong;
//            else
//                preview = preview + '. ' + 'Ведомственная принадлежность - ' + office_belong;
//        }
    } else {
        //preview = 'информация не будет выведена в СД';
        preview = '';
        $("#panel-preview-object").hide();
    }

    //$('#preview-object-standart').html('<b><u>Предпросмотр (объект):</u></b><br>' + preview);
    $('#preview-object-standart').find('textarea[name="object_word"]').val(preview);

}




/* prevention */
$('body').on('input change keyup blur', '#createStandart #accordion7 input[name="prevention_time"]', function (e) {
    setPreviewDataPrevent();
});
$('body').on('input change keyup', '#createStandart #accordion7 input[name="prevention_who"]', function (e) {
    setPreviewDataPrevent();
});
$('body').on('input change keyup', '#createStandart #accordion7 textarea[name="prevention_result"]', function (e) {
    setPreviewDataPrevent();
});
$('body').on('input change keyup', '#createStandart #accordion7 textarea[name="prevention_events"]', function (e) {
    setPreviewDataPrevent();
});
$('body').on('change', '#createStandart #accordion7 #is_show_prevention', function (e) {
    setPreviewDataPrevent();
});


function setPreviewDataPrevent() {

    var date = $('#createStandart #accordion7 input[name="prevention_time"]').val();
    var who = $('#createStandart #accordion7 input[name="prevention_who"]').val();
    var result = $('#createStandart #accordion7 textarea[name="prevention_result"]').val();
    //var events = $('#createStandart #accordion7 textarea[name="prevention_events"]').val();
    var is_show_prevention = $('#createStandart #accordion7 #is_show_prevention').is(":checked");


    var preview = '';

    if (is_show_prevention === true) {
        $("#panel_preview_prevention").show();

        if (date !== '') {
            preview = date;
        }


        if (who !== '') {

            if (preview === '')
                preview = who;
            else
                preview = preview + ' ' + who;
        }

        if (result !== '') {
            result = result.replace(/\r?\n/g, '<br />');

            var arr_new_descr = result.split('<br />');
            var arr_new_descr_str = arr_new_descr.join('<br />');

            if (preview === '')
                preview = arr_new_descr_str;
            else
                preview = preview + ' проводились следующие профилактические работы: ' + arr_new_descr_str;

        }

//        if (events !== '') {
//            events = events.replace(/\r?\n/g, '<br />');
//
//            var arr_new_descr = events.split('<br />');
//            var arr_new_descr_str = arr_new_descr.join('<br />');
//
//            if (preview === '')
//                preview = arr_new_descr_str;
//            else
//                preview = preview + ' Проводимые мероприятия для формирования в обществе культуры, безопасной жизнедеятельности: ' + arr_new_descr_str;
//
//        }
    } else {
        preview = 'информация не будет выведена в СД';
        $("#panel_preview_prevention").hide();

    }
    $('#preview_prevention').html('<b><u>Предпросмотр (меры профилактики):</u></b><br>' + preview);


}





/* owner individual face */
$('body').on('change', '#createStandart #accordion6 #is_show_owner', function (e) {
    setPreviewOwner();
});
$('body').on('change', '#createStandart #accordion6 #id_owner_category', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 input[name="owner_fio"]', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 input[name="owner_year_birthday"]', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 textarea[name="owner_address"]', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 input[name="owner_position"]', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 textarea[name="owner_job"]', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 textarea[name="owner_character"]', function (e) {
    setPreviewOwner();
});
$('body').on('change', '#createStandart #accordion6 input[name="owner_is_uhet"]', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 input[name="owner_live_together"]', function (e) {
    setPreviewOwner();
});

$('body').on('input change keyup', '#createStandart #accordion6 .live_together_fio', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 .live_together_year_birth', function (e) {
    setPreviewOwner();
});
$('body').on('input change keyup', '#createStandart #accordion6 .live_together_note', function (e) {
    setPreviewOwner();
});
$('body').on('click', '#createStandart #accordion6 .del-row-live-together', function (e) {
    setTimeout(function () {
        setPreviewOwner();
    }, 150);

});

$('body').on('input change keyup', '#createStandart textarea[name="owner_multi_descr"]', function (e) {
    setPreviewOwner();
});




function setPreviewOwner() {

    var is_show_owner = $('#createStandart #accordion6 #is_show_owner').is(":checked");

    var category_val = $('#createStandart #id_owner_category option:selected').val();
    var category = $('#createStandart  #id_owner_category option:selected').text();
    var category_gender = $('#createStandart #id_owner_category option:selected').data('gender');

    var fio = $('#createStandart input[name="owner_fio"]').val();
    var birth = $('#createStandart input[name="owner_year_birthday"]').val();
    var pos = $('#createStandart input[name="owner_position"]').val();
    var job = $('#createStandart textarea[name="owner_job"]').val();
    var addr = $('#createStandart textarea[name="owner_address"]').val();
    var live_tog = $('#createStandart input[name="owner_live_together"]').val();

    var live_tog_div = $('#createStandart #div-live-together');

    var character = $('#createStandart textarea[name="owner_character"]').val();
    var owner_is_uhet = $('#createStandart #accordion6 #owner_is_uhet').is(":checked");


    var is_show_multi = $('#createStandart input[name="is_owner_multi"]').is(":checked");
    var owner_multi_descr = $('#createStandart textarea[name="owner_multi_descr"]').val();

    var preview = '';

    if (is_show_owner === true) {
        $("#panel_preview_owner").show();



        if (category_val !== '') {
            preview = category.charAt(0).toUpperCase() +
                    category.slice(1) + ': ';
        }


        if (fio !== '') {
            if (preview === '')
                preview = fio;
            else
                preview = preview + fio;
        }

        if (birth !== '') {
            if (preview === '')
                preview = birth + ' г.р.';
            else
                preview = preview + ', ' + birth + ' г.р.';
        }

        if (pos !== '') {
            if (preview === '')
                preview = pos;
            else
                preview = preview + ', ' + pos;
        }

        if (job !== '') {
            if (preview === '')
                preview = job;
            else
                preview = preview + ', ' + job;
        }

        if (addr !== '') {
            if (preview === '')
                preview = ' (проживает ' + addr + ')';
            else
                preview = preview + ' (проживает ' + addr + ')';
        }

        if (preview !== '')
            preview = preview + '.';


        var arr_live_tog = [];

        if (live_tog_div.hasClass('show') && live_tog !== '' && parseInt(live_tog) !== 0) {
            var rows = live_tog_div.find('.live_together_row');

            $(rows).each(function (index, value) {

                var f = $(this).find('.live_together_fio').val();
                var y = $(this).find('.live_together_year_birth').val();
                var n = $(this).find('.live_together_note').val();

                var res = '';

                if (f !== '') {
                    res = f;
                }
                if (y !== '') {

                    if (res === '')
                        res = y;
                    else
                        res = res + ', ' + y + 'г.р.';
                }

                if (n !== '') {

                    if (res === '')
                        res = n;
                    else
                        res = res + ', ' + n;
                }

                if (res !== '')
                    arr_live_tog.push(res);
            });


        }



        if (live_tog !== '' && parseInt(live_tog) !== 0) {
            if (preview === '')
                preview = 'Совместно проживает ' + live_tog + ' ' + declOfNum(live_tog, ['человек', 'человека', 'человек']);
            else
                preview = preview + ' Совместно проживает ' + live_tog + ' ' + declOfNum(live_tog, ['человек', 'человека', 'человек']);


            if (arr_live_tog.length > 0) {
                preview = preview + ' (' + arr_live_tog.join('; ') + ')';
            }

            preview = preview + '.';
        } else {
            if (preview === '')
                preview = 'Проживает';
            else
                preview = preview + ' Проживает';

            if (parseInt(category_gender) === 1) {
                preview = preview + ' одна.';
            } else {
                preview = preview + ' один.';
            }
        }





        if (character !== '') {
            if (preview === '')
                preview = character;
            else
                preview = preview + ' ' + character;
        }



        if (owner_is_uhet === true) {
            if (preview === '')
                preview = ' Состоит на учете профилактики.';
            else
                preview = preview + ' Состоит на учете профилактики.';
        } else {
            if (preview === '')
                preview = ' На учете профилактики не состоит.';
            else
                preview = preview + ' На учете профилактики не состоит.';
        }



        if (is_show_multi === true && owner_multi_descr !== '') {
            if (preview === '')
                preview = owner_multi_descr;
            else
                preview = preview + '\n' + owner_multi_descr;
        }



    } else {
        //preview = 'информация не будет выведена в СД';
        preview = '';
        $("#panel_preview_owner").hide();

    }
    //$('#preview_owner').html('<b><u>Предпросмотр (данные по собственнику):</u></b><br>' + preview);
    $('#preview-owner-standart').find('textarea[name="owner_word"]').val(preview);


}



/* owner law face */
$('body').on('change', '#createStandart #accordion6 #is_show_owner_law', function (e) {
    setPreviewLawFace();
});
$('body').on('change', '#createStandart #accordion6 #law-face-office-belong-id', function (e) {
    setPreviewLawFace();
});
$('body').on('input change keyup', '#createStandart #accordion6 textarea[name="law_face_name_owner"]', function (e) {
    setPreviewLawFace();
});

$('body').on('input change keyup', '#createStandart textarea[name="owner_multi_descr_law"]', function (e) {
    setPreviewLawFace();
});


function setPreviewLawFace() {

    var is_show_owner_law = $('#createStandart #accordion6 #is_show_owner_law').is(":checked");

    var belong_val = $('#createStandart #law-face-office-belong-id option:selected').val();
    var belong = $('#createStandart  #law-face-office-belong-id option:selected').text();
    var name = $('#createStandart textarea[name="law_face_name_owner"]').val();

    var is_show_multi = $('#createStandart input[name="is_owner_multi_law"]').is(":checked");
    var owner_multi_descr = $('#createStandart textarea[name="owner_multi_descr_law"]').val();


    var preview = '';

    if (is_show_owner_law === true) {
        $("#panel_preview_owner").show();



        if (belong_val !== '') {
            preview = 'Ведомственная принадлежность: ' + belong + '.';
        }


        if (name !== '') {
            if (preview === '')
                preview = 'Наименование собственника: ' + name;
            else
                preview = preview + ' Наименование собственника: ' + name;
        }


        if (is_show_multi === true && owner_multi_descr !== '') {
            if (preview === '')
                preview = owner_multi_descr;
            else
                preview = preview + '\n' + owner_multi_descr;
        }
    } else {

        preview = '';
        $("#panel_preview_owner").hide();

    }

    $('#preview-owner-standart').find('textarea[name="owner_word"]').val(preview);

}

// hide owner preview if face is not select
function hidePreviewOwner() {
    $('#preview-owner-standart').find('textarea[name="owner_word"]').val('');
    $("#panel_preview_owner").hide();
}

// hide object preview if face is not select
function hidePreviewDataObject() {
    $('#preview-object-standart').find('textarea[name="object_word"]').val('');
    $("#panel-preview-object").hide();
}








$("#preview_opening_description_button").click(function () {


    if ($("#panel_preview_opening_description").hasClass('open_panel')) {
        $("#panel_preview_opening_description").removeClass('open_panel');
        $("#panel_preview_opening_description").addClass('close_panel');
        $("#preview-opening-description-standart").hide();
        $("#preview_opening_description_button").show();
    } else {
        $("#panel_preview_opening_description").removeClass('close_panel');
        $("#panel_preview_opening_description").addClass('open_panel');
        $("#preview-opening-description-standart").show();
        //$( "#theme_panel_button" ).show();
    }



});



$("#preview_object_button").click(function () {


    if ($("#panel-preview-object").hasClass('open_panel')) {
        $("#panel-preview-object").removeClass('open_panel');
        $("#panel-preview-object").addClass('close_panel');
        $("#preview-object-standart").hide();
        $("#preview_object_button").show();
    } else {
        $("#panel-preview-object").removeClass('close_panel');
        $("#panel-preview-object").addClass('open_panel');
        $("#preview-object-standart").show();
        //$( "#theme_panel_button" ).show();
    }
});

$("#preview_prevention_button").click(function () {


    if ($("#panel_preview_prevention").hasClass('open_panel')) {
        $("#panel_preview_prevention").removeClass('open_panel');
        $("#panel_preview_prevention").addClass('close_panel');
        $("#preview_prevention").hide();
        $("#preview_prevention_button").show();
    } else {
        $("#panel_preview_prevention").removeClass('close_panel');
        $("#panel_preview_prevention").addClass('open_panel');
        $("#preview_prevention").show();
    }
});



$("#preview_owner_button").click(function () {


    if ($("#panel_preview_owner").hasClass('open_panel')) {
        $("#panel_preview_owner").removeClass('open_panel');
        $("#panel_preview_owner").addClass('close_panel');
        $("#preview-owner-standart").hide();
        $("#preview_owner_button").show();
    } else {
        $("#panel_preview_owner").removeClass('close_panel');
        $("#panel_preview_owner").addClass('open_panel');
        $("#preview-owner-standart").show();
        //$( "#theme_panel_button" ).show();
    }



});