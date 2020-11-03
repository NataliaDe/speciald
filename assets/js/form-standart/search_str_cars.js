$('#close-modal-search-str-cars').on('click', function (event) {
    $('#modal-search-str-cars').click();
});
$('#close-modal-agree-get-str-cars, #modal-agree-get-str-cars .close').on('click', function (event) {
    $('#modal-search-str-cars').css('opacity', '');
    $('#modal-agree-get-str-cars').click();
});



$('#modal-search-str-cars #btn-search-str-cars-clear-filter').on('click', function (event) {

    $('#searchStrCarsForm #id_region_str_cars').val('').trigger('change');
    $('#searchStrCarsForm ').find('#id_local_str_cars').empty().trigger('change');
    $('#searchStrCarsForm ').find('#id_pasp_str_cars').empty().trigger('change');

    $('#searchStrCarsForm #id_cars_str_cars').val('').trigger('change');
    $('#searchStrCarsForm ').find('#id_cars_str_cars').empty().trigger('change');
    cur_cars = [];
});


var cur_pasp = [];
var cur_cars = [];

$('#searchStrCarsForm #id_region_str_cars').on('change', function (event) {

    var ids_region = $(this).val();

    var cur_loc = $('#searchStrCarsForm ').find('#id_local_str_cars').val();
    cur_pasp = $('#searchStrCarsForm ').find('#id_pasp_str_cars').val();

    setSelectedCars();

    $('#searchStrCarsForm ').find('#id_local_str_cars').empty();

    if (ids_region !== null && ids_region !== '') {
        //get locals by region
        $.ajax({
            dataType: "json",
            url: '../../dones/get_grochs_by_region',
            method: 'POST',
            data: {
                ids_region: ids_region
            },

            statusCode: {
                307: function () {
                    alert("Время сессии истекло");
                }
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
//                complete: function(xhr, textStatus) {
//        console.log(xhr.status);
//    }
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

    setSelectedCars();


    $('#searchStrCarsForm ').find('#id_pasp_str_cars').empty();
    if (ids_locorg !== null && ids_locorg !== '') {
        //get locals by region
        $.ajax({
            dataType: "json",
            url: '../../dones/get_pasp_by_locorg',
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
        $('#searchStrCarsForm ').find('#id_cars_str_cars').empty().trigger('change');
    }

});



$('#searchStrCarsForm #id_pasp_str_cars').on('change', function (event) {

    var ids_pasp = $(this).val();
    var t = [];


    setSelectedCars();

    //cur_cars = $('#searchStrCarsForm ').find('#id_cars_str_cars').attr('data-idcar');
    console.log('cars=' + cur_cars);
    $('#searchStrCarsForm ').find('#id_cars_str_cars').empty();
    if (ids_pasp !== null && ids_pasp !== '') {
        //get locals by region
        $.ajax({
            dataType: "json",
            url: '../../dones/get_str_cars_by_pasp',
            method: 'POST',
            data: {
                ids_pasp: ids_pasp
            },
            success: function (data) {



                if (data.is_error === 1)
                    toastr.error(data.msg, 'Ошибка', {timeOut: 5000});
                else {

                    $(data.cars).each(function (index, val) {

                        $(val).each(function (index_1, value) {


                            if (value.id_car !== null) {
                                t.push(value.id_car);
                                if (cur_cars !== null && cur_cars !== '' && cur_cars !== undefined && cur_cars !== "undefined" && cur_cars.includes(value.id_car)) {

                                    $('#searchStrCarsForm ').find("#id_cars_str_cars").append($("<option selected></option>").attr({"value": value.id_teh + '~' + value.mark + '~' + value.pasp_name_spec + '~' + value.locorg_name_spec + '~' + value.v_ac + '~' + value.man_per_car+'~'+value.vid_t, 'data-idcar': value.id_car, 'data-vid_t': value.vid_t}).text(value.mark + ' ' + value.status + ' '+ value.pasp_name_spec_real + ' ' + value.of_locorg_name_spec_real));

                                } else {

                                    $('#searchStrCarsForm ').find("#id_cars_str_cars").append($("<option></option>").attr({"value": value.id_teh + '~' + value.mark + '~' + value.pasp_name_spec + '~' + value.locorg_name_spec + '~' + value.v_ac + '~' + value.man_per_car+'~'+value.vid_t, 'data-idcar': value.id_car, 'data-vid_t': value.vid_t}).text(value.mark + ' ' + value.status + ' '+ value.pasp_name_spec_real + ' ' + value.of_locorg_name_spec_real));
                                }


                            }


                        });
                    });


                    $('#searchStrCarsForm ').find('#id_cars_str_cars').trigger('change');
                }



                if (cur_cars !== null && cur_cars !== '' && cur_cars !== undefined && cur_cars !== "undefined") {

                    $(cur_cars).each(function (index, value) {

                        if (t.includes(value)) {

                        } else {

                            cur_cars.splice(index, value);

                        }


                    });
                }


            },
            error: function () {
                toastr.error('Информация не найдена', 'Ошибка', {timeOut: 5000});

            }
        });


    } else {
        cur_cars = [];
        $('#searchStrCarsForm ').find('#id_cars_str_cars').empty().trigger('change');
    }

});



function setSelectedCars() {
    $('#searchStrCarsForm ').find('#id_cars_str_cars option:selected').each(function () {
        var idcar = $(this).attr('data-idcar');
        console.log(idcar);
        if (idcar !== null && idcar !== "null" && idcar !== '' && idcar !== undefined && idcar !== "undefined") {
            cur_cars = cur_cars || [];
            if (cur_cars.includes(idcar)) {

            } else {
                cur_cars.push(idcar); // ids.push(this.id) would work as well.
            }
        }
    });
}


$('#searchStrCarsForm #id_cars_str_cars').on('change', function (event) {

    var ids_cars = $(this).val();
//alert(ids_cars);

    if (ids_cars !== null && ids_cars !== '') {

        $('#modal-search-str-cars').find('#btn-get-data-str-cars').attr('disabled', false);

    } else {

        $('#modal-search-str-cars').find('#btn-get-data-str-cars').attr('disabled', true);
    }

});


$('#modal-agree-get-str-cars').on('show.bs.modal', function (event) {
    $('#modal-search-str-cars').css('opacity', '0.8');
});


/*  get data by str cars and add cars to special form */
$('#modal-agree-get-str-cars #btn-fill-str-cars-form').on('click', function (event) {


    $('#preload-update-data-search-rig').css('display', 'block');
    $('body').css('opacity', 0.5);

    var cars = $('#modal-search-str-cars').find('#id_cars_str_cars').val();
    var url = $(this).attr('data-url');
    var data = {'cars': cars};

    if (cars !== '' && cars !== null) {
        $.post(url, data, function (res) {


            if (parseInt(JSON.parse(res)['is_error']) === 0) {


                $(JSON.parse(res)['cars']).each(function (index, val) {

                    $(val).each(function (index_1, value) {

                        var yes = 0;

                        $('#silymchs-block-div').find('.id_teh').each(function (i, v) {
                            if ($(this).val() === value.id_teh)
                                yes = yes + 1;
                        });


                        if (yes === 0) {// car is not in table

                            $('#silymchs-block-div').find('#add_teacher').trigger('click');
                            var $row = $('#silymchs-block-div #div-silymchs .table tbody').find('.teacher_row:last');
                            $row.find('td').find('.mark').val(value.mark);
                            $row.find('td').find('.pasp_name').val(value.pasp_name);
                            $row.find('td').find('.locorg_name').val(value.locorg_name);
                            //$row.find('td').find('.v_ac').val(Number(value.v_ac / 1000).toFixed(1));
                             $row.find('td').find('.v_ac').val(value.v_ac);
                            $row.find('td').find('.man_per_car').val(value.man_per_car);

                            $row.find('td').find('.id_teh').val(value.id_teh);

                        }

                        /* add teh to trunks table */
                        var yes_trunks = 0;
                        $('#trunks_data-block-div #trunks-block').find('.id_teh').each(function (i, v) {
                            if ($(this).val() === value.id_teh)
                                yes_trunks = yes_trunks + 1;
                        });

                        if (yes_trunks === 0) {// car is not in table trunks

                            $('#trunks_data-block-div #trunks-block').find('#add-row-trunks-tr').trigger('click');
                            var $row = $('#trunks_data-block-div #trunks-block .table tbody').find('.trunks_row:last');
                            $row.find('td').find('.mark_trunks').val(value.mark);
                            $row.find('td').find('.pasp_name_trunks').val(value.pasp_name);
                            $row.find('td').find('.locorg_name_trunks').val(value.locorg_name);
                            $row.find('td').find('.v_ac_trunks').val(Number(value.v_ac / 1000).toFixed(1));
                            $row.find('td').find('.man_per_car_trunks').val(value.man_per_car);
                            $row.find('td').find('.vid_t').val(value.vid_t);
                            $row.find('td').find('.id_teh').val(value.id_teh);

                        }


                    });
                });

                /*  delete empty first row*/
                $('#silymchs-block-div').find('.id_teh').each(function (i, v) {
                    var id_row = ($(this).closest('.teacher_row')).attr('id');
                    var $row_check = $('#silymchs-block-div #div-silymchs .table tbody').find('#' + id_row);
                    if ($row_check.find('td').find('.mark').val() === '' &&
                            $row_check.find('td').find('.pasp_name').val() === '' &&
                            $row_check.find('td').find('.locorg_name').val() === ''){
                        $row_check.remove();
                    }
                });

                 /*  delete empty first row - trunks */
                $('#trunks_data-block-div #trunks-block').find('.id_teh').each(function (i, v) {
                    var id_row = ($(this).closest('.trunks_row')).attr('id');
                    var $row_check = $('#trunks_data-block-div #trunks-block .table tbody').find('#' + id_row);
                    if ($row_check.find('td').find('.mark_trunks').val() === '' &&
                            $row_check.find('td').find('.pasp_name_trunks').val() === '' &&
                            $row_check.find('td').find('.locorg_name_trunks').val() === ''){
                        $row_check.remove();
                    }
                });


                $('#preload-update-data-search-rig').css('display', 'none');
                $('body').css('opacity', 1);


                toastr.success('Данные по технике успешно получены', 'Успех!', {progressBar: true, timeOut: 2500});
                $('#modal-search-str-cars').css('opacity', '');
                $('#modal-search-str-cars').click();
                $('#modal-agree-get-str-cars').click();
            } else {

                $('body').css('opacity', 1);
                toastr.error('Данные по технике не найдены', 'Ошибка!', {progressBar: true, timeOut: 2500});

            }
        });

    } else {
        toastr.error('Необходимо выбрать технику', 'Ошибка!', {progressBar: true, timeOut: 2500});
    }

});






//$('#searchStrCarsForm #btn-search-str-cars').on('click', function (e) {
//    var ids_pasp = $('#searchStrCarsForm ').find('#id_pasp_str_cars').val();
//
//cur_cars = $('#searchStrCarsForm ').find('#id_cars_str_cars').val();
//
//$('#searchStrCarsForm ').find('#id_cars_str_cars').empty();
//    if (ids_pasp !== null && ids_pasp !== '') {
//        //get locals by region
//        $.ajax({
//            dataType: "json",
//            url: '../get_str_cars_by_pasp',
//            method: 'POST',
//            data: {
//                ids_pasp: ids_pasp
//            },
//            success: function (data) {
//
//                console.log(data);
//
//                if (data.is_error === 1)
//                    toastr.error(data.msg, 'Ошибка', {timeOut: 5000});
//                else {
//                    $(data.cars).each(function (index, val) {
//
//                        $(val).each(function (index_1, value) {
//
//
//                            if (cur_cars !== null && cur_cars !== '' && cur_cars.includes(value.id_car)) {
//                                $('#searchStrCarsForm ').find("#id_cars_str_cars").append($("<option selected></option>").attr("value", value.id_car).text(value.mark + ' ' + value.status + value.pasp_name_spec_real + ' ' + value.of_locorg_name_spec_real));
//                            } else {
//                                $('#searchStrCarsForm ').find("#id_cars_str_cars").append($("<option></option>").attr("value", value.id_car).text(value.mark + ' ' + value.status + value.pasp_name_spec_real + ' ' + value.of_locorg_name_spec_real));
//                            }
//
//
//                        });
//                    });
//                    $('#searchStrCarsForm ').find('#id_cars_str_cars').trigger('change');
//                }
//
//            },
//            error: function () {
//                toastr.error('Информация не найдена', 'Ошибка', {timeOut: 5000});
//
//            }
//        });
//
//
//
//    } else {
//        $('#searchStrCarsForm ').find('#id_cars_str_cars').empty().trigger('change');
//        toastr.error('Выберите ПАСЧ', 'Ошибка', {timeOut: 5000});
//    }
//
//});



