

$('body').on('input change keyup', '.calendar-simple-time-msg', function (e) {
    setPreviewData();
});


$('#createSimple #accordion2 input[name="time_msg"]').on('apply.daterangepicker', function (e, picker) {
    setPreviewData();
});
//$('body').on('click', '.daterangepicker .applyBtn ', function (e) {
//    setPreviewData();
//});


$('body').on('change', '#lat_id', function (e) {
    setPreviewData();
});


$('body').on('change', '#long_id', function (e) {
    setPreviewData();


});



ck_opening_descr.on('change', function () {
    setPreviewData();
});
//$('body').on('input change keyup', '#opening_description_id', function (e) {
//    setPreviewData();
//
//});


$('body').on('change', '#is_show_coords', function (e) {
    setPreviewData();


});


function setPreviewData() {


    var date = $('.calendar-simple-time-msg').val();
    var time = $('.calendar-simple-time-msg').val();
    var lat = $('#lat_id').val();
    var long = $('#long_id').val();
    //var descr = $("#opening_description_id").val();

    var descr = CKEDITOR.instances.opening_description_id.getData();

    var is_show_coords = $('#is_show_coords').is(":checked");


    var new_date = '';
    var new_time = '';
    var new_lat = '';
    var new_long = '';
    var new_coord = '';
    var new_descr = '';


    var arr_date = date.split(' ');

    if (date !== '') {
        new_date = arr_date[0] + ' года ';
    }

    if (time !== '') {

        var arr_t = time.split(' ');
        var arr_time = arr_t[1].split(':');
        new_time = 'в ' + arr_time[0] + ':' + arr_time[1] + ' ';
    }



    if (is_show_coords === false) {
        new_coord = ' (' + lat + ', ' + long + ').';

        if (lat === '' && long === '') {
            new_coord = ' (нет координат).';
        }
    } else {
        new_coord = '';
    }
    new_coord = '';




    if (descr !== '') {
//        new_descr = descr.replace(/\r?\n/g, '<br />');
//
//        var arr_new_descr = new_descr.split('<br />');
//        arr_new_descr[0] = arr_new_descr[0] + '' + new_coord;
//        var arr_new_descr_str = arr_new_descr.join('<br />');

        var r = $.trim(descr);
        //var arr_new_descr = r.split(/\r?\n/g);
        //arr_new_descr[0] = arr_new_descr[0] + ' ' + new_coord;

        var arr_new_descr_first = r.split(/\r?\n/g);
        var arr_new_descr = [];
        for (var i = 0; i < arr_new_descr_first.length; i++) {
            var el = arr_new_descr_first[i].replace(/(<([^>]+)>)/gi, "");
            var el = el.replace(/&nbsp;/g, '');

            var varTitle = $('<textarea />').html(el).text();
            var el = varTitle;

            if (el !== '') {
                // console.log(el);
                arr_new_descr.push(el);
            } else {
                // console.log('g');
            }
        }

        var arr_new_descr_str = arr_new_descr.join('\n');
    } else {
        var arr_new_descr_str = new_coord;
    }

    //$('#preview-simple-block-inner').html('<b><u>Предпросмотр (текст специального донесения):</u></b><br>' + new_date + '' + new_time + '' + arr_new_descr_str);
    $('#preview-simple-block-inner').find('textarea[name="opening_word"]').val(new_date + '' + new_time + '' + arr_new_descr_str);


}



$("#preview-simple-block_button").click(function () {


    if ($("#preview-simple-block").hasClass('open_panel')) {
        $("#preview-simple-block").removeClass('open_panel');
        $("#preview-simple-block").addClass('close_panel');
        $("#preview-simple-block-inner").hide();
        $("#preview-simple-block_button").show();
    } else {
        $("#preview-simple-block").removeClass('close_panel');
        $("#preview-simple-block").addClass('open_panel');
        $("#preview-simple-block-inner").show();
        //$( "#theme_panel_button" ).show();
    }



});