$('#close-modal-search-str-inf').on('click', function (event) {
    $('#modal-search-str-inf').click();
});
$('#close-modal-agree-get-str-inf, #modal-agree-get-str-inf .close').on('click', function (event) {
    $('#modal-search-str-inf').css('opacity', '');
    $('#modal-agree-get-str-inf').click();
});



$('#modal-search-str-inf #btn-search-str-inf-clear-filter').on('click', function (event) {

    $('#searchStrInfForm #id_region_str_inf').val('').trigger('change');
    $('#searchStrInfForm ').find('#id_local_str_inf').empty().trigger('change');
    $('#searchStrInfForm ').find('#id_pasp_str_inf').empty().trigger('change');
    $('#searchStrInfForm ').find('#id_cars_str_inf').empty().trigger('change');
});


var cur_pasp = [];

$('#searchStrInfForm #id_region_str_inf').on('change', function (event) {

    var ids_region = $(this).val();

    var cur_loc = $('#searchStrInfForm ').find('#id_local_str_inf').val();
    cur_pasp = $('#searchStrInfForm ').find('#id_pasp_str_inf').val();

    //setSelectedCars();

    $('#searchStrInfForm ').find('#id_local_str_inf').empty();

    if (ids_region !== null && ids_region !== '') {
        //get locals by region
        $.ajax({
            dataType: "json",
            url: '../../dones/get_grochs_by_region',
            method: 'POST',
            data: {
                ids_region: ids_region
            },
            success: function (data) {

                $(data).each(function (index, value) {

                    // $('#searchStrCarsForm ').find("#id_local_str_cars").append($("<option></option>").attr("value", value.id_loc_org).text(value.locorg_name));
                    if (cur_loc !== null && cur_loc !== '' && cur_loc.includes(value.id_loc_org)) {
                        $('#searchStrInfForm ').find("#id_local_str_inf").append($("<option selected></option>").attr("value", value.id_loc_org).text(value.locorg_name));
                    } else {
                        $('#searchStrInfForm ').find("#id_local_str_inf").append($("<option></option>").attr("value", value.id_loc_org).text(value.locorg_name));
                    }


                });

                $('#searchStrInfForm ').find('#id_local_str_inf').trigger('change');

            },
            error: function () {
                console.log('jj');
            }
        });


    } else {
        $('#searchStrInfForm ').find('#id_local_str_inf').empty().trigger('change');
        $('#searchStrInfForm ').find('#id_pasp_str_inf').empty().trigger('change');

    }

});


$('#searchStrInfForm #id_local_str_inf').on('change', function (event) {

    var ids_locorg = $(this).val();
    cur_pasp = $('#searchStrInfForm ').find('#id_pasp_str_inf').val();

    //setSelectedCars();


    $('#searchStrInfForm ').find('#id_pasp_str_inf').empty();
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
                        $('#searchStrInfForm ').find("#id_pasp_str_inf").append($("<option selected></option>").attr("value", value.id_pasp).text(value.pasp_name_spec + ' ' + value.of_locorg_name_spec));
                    } else {
                        $('#searchStrInfForm ').find("#id_pasp_str_inf").append($("<option></option>").attr("value", value.id_pasp).text(value.pasp_name_spec + ' ' + value.of_locorg_name_spec));
                    }
                    //$('#searchStrCarsForm ').find("#id_pasp_str_cars").append($("<option></option>").attr("value", value.id_pasp).text(value.pasp_name_spec + '' + value.of_locorg_name_spec));

                });

                $('#searchStrInfForm ').find('#id_pasp_str_inf').trigger('change');

            },
            error: function () {
                console.log('jj');
            }
        });


    } else {

        $('#searchStrInfForm ').find('#id_pasp_str_inf').empty().trigger('change');

    }

});




$('#searchStrInfForm #id_pasp_str_inf').on('change', function (event) {

    var ids_cars = $(this).val();
//alert(ids_cars);

    if (ids_cars !== null && ids_cars !== '') {

        $('#modal-search-str-inf').find('#btn-get-data-str-inf').attr('disabled', false);

    } else {

        $('#modal-search-str-inf').find('#btn-get-data-str-inf').attr('disabled', true);
    }

});


$('#modal-agree-get-str-inf').on('show.bs.modal', function (event) {
    $('#modal-search-str-inf').css('opacity', '0.8');
});


/*  get data by str cars and add cars to special form */
$('#modal-agree-get-str-inf #btn-fill-str-inf-form').on('click', function (event) {


    $('#preload-update-data-search-rig').css('display', 'block');
    $('body').css('opacity', 0.5);

    var ids_pasp = $('#modal-search-str-inf').find('#id_pasp_str_inf').val();
    var url = $(this).attr('data-url');
    var data = {'ids_pasp': ids_pasp};

if(ids_pasp !== '' && ids_pasp !== null){
    $.post(url, data, function (res) {


        if (parseInt(JSON.parse(res)['is_error']) === 0) {


            $(JSON.parse(res)['pasp']).each(function (index, val) {

                $(val).each(function (index_1, value) {

                    var yes = 0;
                    $('#str-block-div #div-str').find('.id_pasp').each(function (i, v) {
                        if ($(this).val() === value.id_pasp)
                            yes = yes + 1;
                    });


                    if (yes === 0) {// pasp is not in table

                        $('#str-block-div #div-str').find('#add-row-str-tr').trigger('click');
                        var $row = $('#str-block-div #div-str .table tbody').find('.str_row:last');

                            $row.find('td').find('.str_pasp_name').val(value.pasp_name_spec);
                            $row.find('td').find('.str_locorg_name').val(value.locorg_name_spec);
                            $row.find('td').find('.str_shtat').val(value.shtat);
                            $row.find('td').find('.str_vacant').val(value.vacant);
                            $row.find('td').find('.str_on_list_ch').val(value.on_list_ch);
                            $row.find('td').find('.str_vacant_ch').val(value.vacant_ch);
                            $row.find('td').find('.str_face_ch').val(value.face_ch);
                            $row.find('td').find('.str_br_ch').val(value.br_ch);
                            $row.find('td').find('.str_trip_ch').val(value.cnt_trip_man);
                            $row.find('td').find('.str_holiday_ch').val(value.cnt_holiday_man);
                            $row.find('td').find('.str_ill_ch').val(value.cnt_ill_man);
                            $row.find('td').find('.str_duty_ch').val(value.cnt_naryd);
                            $row.find('td').find('.str_other_ch').val(value.cnt_other_man);
                            $row.find('td').find('.str_gdzs_ch').val(value.gas);

                            $row.find('td').find('.id_pasp').val(value.id_pasp);

                    }

                    /* not available */
                    var yes_not = 0;
                    $('#str-block-div #div-str').find('.id_pasp_text').each(function (i, v) {
                        if ($(this).val() === value.id_pasp)
                            yes_not = yes_not + 1;
                    });

                    if (yes_not === 0) {// pasp description is not in table

                        $('#str-block-div #div-str').find('#add-str-text-row').trigger('click');
                        var $row = $('#str-block-div #div-str .table tbody').find('.str_text_row:last');

                            $row.find('td').find('.str_text_podr_name').val(value.full_name_podr);

                            var inf='';
                            var cnt=value.non_available.length;
                            if(cnt >0){
                                $(value.non_available).each(function (i_non_avail, non_avail) {
                                    if(inf === ''){
                                        if(i_non_avail === cnt){
                                            inf=non_avail+'.';
                                        }
                                        else{
                                            inf=non_avail+';';
                                        }
                                    }
                                    else{

                                        if (i_non_avail === cnt) {
                                            inf = inf+'\n'+non_avail + '.';
                                        } else {
                                            inf = inf+'\n'+non_avail + ';';
                                        }

                                    }
                                });
                            }
                            $row.find('td').find('.str_text_descr').val(inf);

                            $row.find('td').find('.id_pasp_text').val(value.id_pasp);



                    }



                });
            });


            $('#preload-update-data-search-rig').css('display', 'none');
            $('body').css('opacity', 1);


            toastr.success('Данные по подразделениям успешно получены', 'Успех!', {progressBar: true, timeOut: 2500});
            $('#modal-search-str-inf').css('opacity', '');
            $('#modal-search-str-inf').click();
            $('#modal-agree-get-str-inf').click();
        } else {

            $('body').css('opacity', 1);
            toastr.error('Данные по подразделениям не найдены', 'Ошибка!', {progressBar: true, timeOut: 2500});

        }
    });
}
else{
    toastr.error('Необходимо выбрать подразделение', 'Ошибка!', {progressBar: true, timeOut: 2500});
}

});




$('.refresh-str').on('click', function (event) {
    var url = $(this).attr('data-url');
    var action = $(this).attr('data-action');


    if (parseInt(action) === 1) {
        $('#modal-str-refresh .modal-header h4').text('Получить строевую за предыдущую смену');
        $('#modal-str-refresh .modal-body p').html('Информация по строевой записке (<u>всех подразделений, имеющихся на форме</u>) будет обновлена информацией <u>за предыдущую смену</u>.<br><b>Выполнить действие?</b>');
    } else if (parseInt(action) === 2) {
        $('#modal-str-refresh .modal-header h4').text('Получить актуальную строевую');
        $('#modal-str-refresh .modal-body p').html('Информация по строевой записке (<u>всех подразделений, имеющихся на форме</u>) будет обновлена <u>актуальной строевой</u>.<br><b>Выполнить действие?</b>');
    }
    $('#btn-refresh-str').attr('data-url',url);

});




/*  refresh str inf */
$('#modal-str-refresh #btn-refresh-str').on('click', function (event) {




    var pasp = $('input:hidden.id_pasp');


var ids_pasp=[];

        $(pasp).each(function (index, value) {


if($(this).val() !== '' ){
    ids_pasp.push($(this).val());
    }


});



    var url = $(this).attr('data-url');
    var data = {'ids_pasp': ids_pasp};

if(ids_pasp !== '' && ids_pasp !== null && ids_pasp.length > 0){

    $('#preload-update-data-search-rig').css('display', 'block');
    $('body').css('opacity', 0.5);

    $.post(url, data, function (res) {


        if (parseInt(JSON.parse(res)['is_error']) === 0) {


            $(JSON.parse(res)['pasp']).each(function (index, val) {

                $(val).each(function (index_1, value) {

                    var yes = 0;
                    var id_row='';
                    $('#str-block-div #div-str').find('.id_pasp').each(function (i, v) {
                        if ($(this).val() === value.id_pasp){
                            yes = yes + 1;
                            id_row=($(this).closest('.str_row')).attr('id');
                          //console.log(id_row);
                        }
                    });


                    if (yes === 1 && id_row !== '') {// pasp is  in table

                        var $row = $('#str-block-div #div-str .table tbody').find('#'+id_row);

                            $row.find('td').find('.str_pasp_name').val(value.pasp_name_spec);
                            $row.find('td').find('.str_locorg_name').val(value.locorg_name_spec);
                            $row.find('td').find('.str_shtat').val(value.shtat);
                            $row.find('td').find('.str_vacant').val(value.vacant);
                            $row.find('td').find('.str_on_list_ch').val(value.on_list_ch);
                            $row.find('td').find('.str_vacant_ch').val(value.vacant_ch);
                            $row.find('td').find('.str_face_ch').val(value.face_ch);
                            $row.find('td').find('.str_br_ch').val(value.br_ch);
                            $row.find('td').find('.str_trip_ch').val(value.cnt_trip_man);
                            $row.find('td').find('.str_holiday_ch').val(value.cnt_holiday_man);
                            $row.find('td').find('.str_ill_ch').val(value.cnt_ill_man);
                            $row.find('td').find('.str_duty_ch').val(value.cnt_naryd);
                            $row.find('td').find('.str_other_ch').val(value.cnt_other_man);
                            $row.find('td').find('.str_gdzs_ch').val(value.gas);

                            $row.find('td').find('.id_pasp').val(value.id_pasp);

                    }

                    /* not available */
                    var yes_not = 0;
                    var id_row_text='';
                    $('#str-block-div #div-str').find('.id_pasp_text').each(function (i, v) {
                        if ($(this).val() === value.id_pasp){
                            yes_not = yes_not + 1;
                            id_row_text=($(this).closest('.str_text_row')).attr('id');

                        }
                    });

                    if (yes_not === 1 && id_row_text !== '') {// pasp description is  in table

                        var $row = $('#str-block-div #div-str .table tbody').find('#'+id_row_text);

                            $row.find('td').find('.str_text_podr_name').val(value.full_name_podr);

                            var inf='';
                            var cnt=value.non_available.length;
                            if(cnt >0){
                                $(value.non_available).each(function (i_non_avail, non_avail) {
                                    if(inf === ''){
                                        if(i_non_avail === cnt){
                                            inf=non_avail+'.';
                                        }
                                        else{
                                            inf=non_avail+';';
                                        }
                                    }
                                    else{

                                        if (i_non_avail === cnt) {
                                            inf = inf+'\n'+non_avail + '.';
                                        } else {
                                            inf = inf+'\n'+non_avail + ';';
                                        }

                                    }
                                });
                            }
                            $row.find('td').find('.str_text_descr').val(inf);

                            $row.find('td').find('.id_pasp_text').val(value.id_pasp);



                    }



                });
            });


            $('#preload-update-data-search-rig').css('display', 'none');
            $('body').css('opacity', 1);


            toastr.success('Данные по подразделениям успешно обновлены', 'Успех!', {progressBar: true, timeOut: 2500});
            $('#modal-str-refresh').css('opacity', '');
            $('#modal-str-refresh').click();
        } else {

            $('body').css('opacity', 1);
            toastr.error('Данные по подразделениям не найдены', 'Ошибка!', {progressBar: true, timeOut: 2500});

        }
    });
}
else{
    toastr.error('На форме нет ни одного подразделения', 'Ошибка!', {progressBar: true, timeOut: 2500});
}

});