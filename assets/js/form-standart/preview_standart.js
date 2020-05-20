

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


//            if (address !== '') {
//            address = address.replace(/\r?\n/g, '<br />');
//
//            var arr_new_address = address.split('<br />');
//            var arr_new_address_str = arr_new_address.join('<br />');
//
//            address = arr_new_address_str;
//        }

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

    $('#preview-opening-description-standart').html('<b><u>Предпросмотр (начальный текст):</u></b><br>' + new_date + '' + new_time + '' + arr_new_descr_str);


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





function setPreviewDataObject() {

    var object = $('#createStandart textarea[name="object"]').val();

    var object_house_val = $('#createStandart #object_house_id option:selected').val();
    var object_house = $('#createStandart  #object_house_id option:selected').text();

    var material_val = $('#createStandart #object_material_id option:selected').val();
    var material = $('#createStandart  #object_material_id option:selected').text();

    var object_floor = $('#createStandart input[name="object_floor"]').val();

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

    if ($text !== '') {
        $text = $text + 'этажный';
    }

    var roof_id = $('#createStandart #object_roof_id option:selected').val();
    var roof = $('#createStandart  #object_roof_id option:selected').text();

    var object_is_electric = $('#createStandart #object_is_electric').is(":checked");
    var object_is_api = $('#createStandart #object_is_api').is(":checked");

    var office_belong_val = $('#createStandart #object-office-belong-id option:selected').val();
    var office_belong = $('#createStandart  #object-office-belong-id option:selected').text();


    var is_show_object = $('#createStandart #is_show_object').is(":checked");



    var preview = '';

    if (is_show_object === true) {

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
                preview = preview + ', ' + material;
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

        if (preview === '')
            preview = preview + '.';

        if (office_belong_val !== '') {


            if (preview === '')
                preview = 'Ведомственная принадлежность ' + office_belong;
            else
                preview = preview + '. ' + 'Ведомственная принадлежность - ' + office_belong;
        }





    } else {
        preview = 'информация не будет выведена в СД';
    }

    $('#preview-object-standart').html('<b><u>Предпросмотр (объект):</u></b><br>' + preview);



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