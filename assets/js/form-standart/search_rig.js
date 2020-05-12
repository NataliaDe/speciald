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


            $("#people-rig-data").html('');
            $("#people-rig-data").html(JSON.parse(res)['people_rig_data']);


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


            $('.select2-select').trigger("change");
            $('.select2-single').trigger("change");
            $('.select2-multi').trigger("change");

            jQuery("#lat_id").mask("99.999999");//долгота
            jQuery("#long_id").mask("99.999999");//широта


            jQuery("#vid_hs_2").chained("#vid_hs_1");


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

