

$('body').on('input change keyup', '#createStandart #middle-block-div input[name="time_msg"]', function (e) {
    setPreviewData();
});


$('body').on('click', '.daterangepicker .applyBtn ', function (e) {
    setPreviewData();
});


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


$('body').on('change', '#createStandart #middle-block-div #is_show_address', function (e) {
    setPreviewData();


});


function setPreviewData() {


    var date = $('#createStandart #middle-block-div input[name="time_msg"]').val();
    var time = $('#createStandart #middle-block-div input[name="time_msg"]').val();
    var descr = $("#createStandart #opening_description_id").val();
    var address = $('#createStandart #middle-block-div textarea[name="address"]').val();
    var lat = $('#createStandart #middle-block-div #lat_id').val();
    var long = $('#createStandart #middle-block-div #long_id').val();
    var is_show_address = $('#createStandart #middle-block-div #is_show_address').is(":checked");




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
        new_time = 'в ' + arr_time[0] + ':' + arr_time[1] + ' ';
    }



    new_coord = ' (' + lat + ', ' + long + ').';

    if (lat === '' && long === '') {
        new_coord = ' (нет координат).';
    }

    if (address !== '' && is_show_address === true) {
        new_coord = ' ' + address + new_coord;
    }



    if (descr !== '') {
        new_descr = descr.replace(/\r?\n/g, '<br />');

        var arr_new_descr = new_descr.split('<br />');
        arr_new_descr[0] = arr_new_descr[0] + '' + new_coord;
        var arr_new_descr_str = arr_new_descr.join('<br />');
    } else {
        var arr_new_descr_str = new_coord;
    }

    $('#preview-opening-description-standart').html('<b><u>Предпросмотр:</u></b><br>' + new_date + '' + new_time + '' + arr_new_descr_str);


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





function setPreviewDataObject() {


    var material_val = $('#createStandart #object_material_id option:selected').val();
    var material = $('#createStandart  #object_material_id option:selected').text();

    var object_floor = $('#createStandart input[name="object_floor"]').val();

    var all_floor = '';
    switch (parseInt(object_floor)) {
        case 1:
            all_floor = 'одно';
            break;
        case 5:
            all_floor = 'пяти';
            break;

    }

    var roof_id = $('#createStandart #object_roof_id option:selected').val();
    var roof = $('#createStandart  #object_roof_id option:selected').text();

    var object_is_electric = $('#createStandart #object_is_electric').is(":checked");
    var object_is_api = $('#createStandart #object_is_api').is(":checked");


    var is_show_object = $('#createStandart #is_show_object').is(":checked");



    var preview = '';

    if (is_show_object === true) {
        if (material_val !== '') {
            preview = 'Дом ' + material;
        }

        if (all_floor !== '') {
            if (preview === '')
                preview = all_floor + 'этажный';
            else
                preview = preview + ', ' + all_floor + 'этажный';
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

        if (object_is_api === true) {
            if (preview === '')
                preview = 'АПИ установлен';
            else
                preview = preview + ', ' + 'АПИ установлен';
        } else {
            if (preview === '')
                preview = 'АПИ не установлен';
            else
                preview = preview + ', ' + 'АПИ не установлен';
        }

        preview = preview + '.';
    }


    $('#preview-object-standart').html('<b><u>Предпросмотр:</u></b><br>' + preview + '.');


}