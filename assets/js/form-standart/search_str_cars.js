$('#close-modal-search-str-cars').on('click', function (event) {
    $('#modal-search-str-cars').click();
});
$('#close-modal-agree-get-str-cars').on('click', function (event) {
    $('#modal-search-str-cars').css('opacity','');
    $('#modal-agree-get-str-cars').click();
});



$(document).ready(function () {
//    $('.select2-select').select2({
//         placeholder: "Выберите из списка",
//        // allowClear: true,
//        "language": {
//            "noResults": function () {
//                return "Ничего не найдено";
//            }
//        }
//    });


});

var cur_pasp = [];
var cur_cars = [];

$('#searchStrCarsForm #id_region_str_cars').on('change', function (event) {

    var ids_region = $(this).val();

    var cur_loc = $('#searchStrCarsForm ').find('#id_local_str_cars').val();
    cur_pasp = $('#searchStrCarsForm ').find('#id_pasp_str_cars').val();
    cur_cars = $('#searchStrCarsForm ').find('#id_cars_str_cars').val();

    $('#searchStrCarsForm ').find('#id_local_str_cars').empty();

    if (ids_region !== null && ids_region !== '') {
        //get locals by region
        $.ajax({
            dataType: "json",
            url: '../get_grochs_by_region',
            method: 'POST',
            data: {
                ids_region: ids_region
            },
            success: function (data) {

                $(data).each(function (index, value) {

                    // $('#searchStrCarsForm ').find("#id_local_str_cars").append($("<option></option>").attr("value", value.id_loc_org).text(value.locorg_name));
                    if (cur_loc !== null && cur_loc !== '' && cur_loc.includes(value.id_loc_org)) {
                        $('#searchStrCarsForm ').find("#id_local_str_cars").append($("<option selected></option>").attr("value", value.id_loc_org).text(value.locorg_name));
                    } else {
                        $('#searchStrCarsForm ').find("#id_local_str_cars").append($("<option></option>").attr("value", value.id_loc_org).text(value.locorg_name));
                    }


                });

                $('#searchStrCarsForm ').find('#id_local_str_cars').trigger('change');

            },
            error: function () {
                console.log('jj');
            }
        });


    } else {
        $('#searchStrCarsForm ').find('#id_local_str_cars').empty().trigger('change');
        $('#searchStrCarsForm ').find('#id_pasp_str_cars').empty().trigger('change');
        $('#searchStrCarsForm ').find('#id_cars_str_cars').empty().trigger('change');
    }

});


$('#searchStrCarsForm #id_local_str_cars').on('change', function (event) {

    var ids_locorg = $(this).val();
    cur_pasp = $('#searchStrCarsForm ').find('#id_pasp_str_cars').val();
    cur_cars = $('#searchStrCarsForm ').find('#id_cars_str_cars').val();

    $('#searchStrCarsForm ').find('#id_pasp_str_cars').empty();
    if (ids_locorg !== null && ids_locorg !== '') {
        //get locals by region
        $.ajax({
            dataType: "json",
            url: '../get_pasp_by_locorg',
            method: 'POST',
            data: {
                ids_locorg: ids_locorg
            },
            success: function (data) {

                $(data).each(function (index, value) {


                    if (cur_pasp !== null && cur_pasp !== '' && cur_pasp.includes(value.id_pasp)) {
                        $('#searchStrCarsForm ').find("#id_pasp_str_cars").append($("<option selected></option>").attr("value", value.id_pasp).text(value.pasp_name_spec + ' ' + value.of_locorg_name_spec));
                    } else {
                        $('#searchStrCarsForm ').find("#id_pasp_str_cars").append($("<option></option>").attr("value", value.id_pasp).text(value.pasp_name_spec + ' ' + value.of_locorg_name_spec));
                    }
                    //$('#searchStrCarsForm ').find("#id_pasp_str_cars").append($("<option></option>").attr("value", value.id_pasp).text(value.pasp_name_spec + '' + value.of_locorg_name_spec));

                });

                $('#searchStrCarsForm ').find('#id_pasp_str_cars').trigger('change');

            },
            error: function () {
                console.log('jj');
            }
        });


    } else {

        $('#searchStrCarsForm ').find('#id_pasp_str_cars').empty().trigger('change');
    }

});




$('#searchStrCarsForm #btn-search-str-cars').on('click', function (e) {
    var ids_pasp = $('#searchStrCarsForm ').find('#id_pasp_str_cars').val();

cur_cars = $('#searchStrCarsForm ').find('#id_cars_str_cars').val();

$('#searchStrCarsForm ').find('#id_cars_str_cars').empty();
    if (ids_pasp !== null && ids_pasp !== '') {
        //get locals by region
        $.ajax({
            dataType: "json",
            url: '../get_str_cars_by_pasp',
            method: 'POST',
            data: {
                ids_pasp: ids_pasp
            },
            success: function (data) {

                console.log(data);

                if (data.is_error === 1)
                    toastr.error(data.msg, 'Ошибка', {timeOut: 5000});
                else {
                    $(data.cars).each(function (index, val) {

                        $(val).each(function (index_1, value) {


                            if (cur_cars !== null && cur_cars !== '' && cur_cars.includes(value.id_car)) {
                                $('#searchStrCarsForm ').find("#id_cars_str_cars").append($("<option selected></option>").attr("value", value.id_car).text(value.mark + ' ' + value.status + value.pasp_name_spec_real + ' ' + value.of_locorg_name_spec_real));
                            } else {
                                $('#searchStrCarsForm ').find("#id_cars_str_cars").append($("<option></option>").attr("value", value.id_car).text(value.mark + ' ' + value.status + value.pasp_name_spec_real + ' ' + value.of_locorg_name_spec_real));
                            }


                        });
                    });
                    $('#searchStrCarsForm ').find('#id_cars_str_cars').trigger('change');
                }

            },
            error: function () {
                toastr.error('Информация не найдена', 'Ошибка', {timeOut: 5000});

            }
        });



    } else {
        $('#searchStrCarsForm ').find('#id_cars_str_cars').empty().trigger('change');
        toastr.error('Выберите ПАСЧ', 'Ошибка', {timeOut: 5000});
    }

});





$('#searchStrCarsForm #id_cars_str_cars').on('change', function (event) {

    var ids_cars = $(this).val();
//alert(ids_cars);

    if (ids_cars !== null && ids_cars !== '') {

$('#modal-search-str-cars').find('#btn-get-data-str-cars').attr('disabled', false);

    } else {

$('#modal-search-str-cars').find('#btn-get-data-str-cars').attr('disabled', true);
    }

});






//function update(url) {
//
//    $('#btn-get-data-rig').attr('disabled', true);
//    $("#result-search-rig").html('');
//    $('#preload-update-data').css('display', 'block');
//
//    $.get(url, $("#searchRigForm").serialize(), function (res) {
//        $('#preload-update-data').css('display', 'none');
//        $("#result-search-rig").html(JSON.parse(res)['innerHtml']);
//
////        if (JSON.parse(res)['is_data'] === 1) {
////            $('#btn-get-data-rig').attr('disabled', false);
////        } else {
////            $('#btn-get-data-rig').attr('disabled', true);
////        }
//    });
//}
//
//$('#btn-search-rig').on('click', function (event) {
//
//    var button = $(event.relatedTarget);
//    var url = $(this).data('url');
//
//    update(url);
//
//});
//
//
//
//function selectRig(r) {
//
//    if ($(r).val() !== '' && $(r).val() !== 0) {
//
//        $('#btn-get-data-rig').attr('data-rig',$(r).val());
//        $('#btn-get-data-rig').attr('disabled', false);
//    }
//    else{
//        $('#btn-get-data-rig').attr('data-rig',0);
//        $('#btn-get-data-rig').attr('disabled', true);
//    }
//}
//
//
//        $('#modal-agree-get-rig-data').on('show.bs.modal', function (event) {
//$('#modal-search-rig').css('opacity','0.8');
//        });
//
///*  get data by rig and fill special form with this data */
//$('#btn-fill-form').on('click', function (event) {
//
//
//        $('#preload-update-data-search-rig').css('display', 'block');
//        $('body').css('opacity',0.5);
//
//    var id_rig = $('#btn-get-data-rig').attr('data-rig');
//    var url = $('#btn-get-data-rig').attr('data-url');
//
//
//    var data={'id_rig':id_rig};
//
//    $.get(url, data, function (res) {
//
//        //$('#preload-update-data').css('display', 'none');
//
//
//        if (parseInt(JSON.parse(res)['is_data']) === 1) {
//            $("#accordion2").html('');
//            $("#accordion2").html(JSON.parse(res)['opening_block']);
//            $("#middle-block-div").html('');
//            $("#middle-block-div").html(JSON.parse(res)['middle_block']);
//
//            $("#silymchs-block-div").html('');
//            $("#silymchs-block-div").html(JSON.parse(res)['silymchs']);
//
//            $("#innerservice-block-div").html('');
//            $("#innerservice-block-div").html(JSON.parse(res)['innerservice']);
//            $("#informing-block-div").html('');
//            $("#informing-block-div").html(JSON.parse(res)['informing']);
//
//            $("#accordion4").html('');
//            $("#accordion4").html(JSON.parse(res)['final_block']);
//
//            $("#div-str").html('');
//            $("#div-str").html(JSON.parse(res)['str_block']);
//
//            $("#trunks_data-block-div").html('');
//            $("#trunks_data-block-div").html(JSON.parse(res)['trunks_block']);
//
//
//            $("#object-data").html('');
//            $("#object-data").html(JSON.parse(res)['object_data']);
//
//            $("#object-floor-div").html('');
//            $("#object-floor-div").html(JSON.parse(res)['object_floor']);
//
//
//            $("#people-rig-data").html('');
//            $("#people-rig-data").html(JSON.parse(res)['people_rig_data']);
//
//
//            $('.select2-select').select2({
//                placeholder: "Выберите из списка",
//                "language": {
//                    "noResults": function () {
//                        return "Ничего не найдено";
//                    }
//                }
//            });
//
//
//            $('.select2-single').select2({
//                placeholder: "Выберите из списка",
//                allowClear: true,
//                "language": {
//                    "noResults": function () {
//                        return "Ничего не найдено";
//                    }
//                }
//            });
//
//                        $('.select2-multi').select2({
//                placeholder: "Выберите из списка",
//                allowClear: true,
//                "language": {
//                    "noResults": function () {
//                        return "Ничего не найдено";
//                    }
//                }
//            });
//
//
//            $('.select2-select').trigger("change");
//            $('.select2-single').trigger("change");
//            $('.select2-multi').trigger("change");
//
//            jQuery("#lat_id").mask("99.999999");//долгота
//            jQuery("#long_id").mask("99.999999");//широта
//
//
//            jQuery("#vid_hs_2").chained("#vid_hs_1");
//
//
//            $('#preload-update-data-search-rig').css('display', 'none');
//            $('body').css('opacity',1);
//
//
//            toastr.success('Данные по выезду с ID = ' + id_rig + ' успешно выбраны', 'Успех!', {progressBar: true, timeOut: 2500});
//            $('#modal-search-rig').css('opacity','');
//            $('#modal-search-rig').click();
//            $('#modal-agree-get-rig-data').click();
//        } else {
//
//            $('body').css('opacity',1);
//            toastr.error('Данные по выезду с ID = ' + id_rig + ' не найдены', 'Ошибка!', {progressBar: true, timeOut: 2500});
//
//        }
//    });
//
//});

