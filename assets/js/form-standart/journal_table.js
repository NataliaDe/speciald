$(".chzn-select").chosen();
$(document).ready(function () {
    $('body').on('click', '#add_teacher', function (e) {
        e.preventDefault();

        var $div = $('div[id^="klon"]:last');
        var $div_for_clon = $('.teacher_row:last');

        var id_car_block=$div_for_clon.data('loop');
        var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;

        /* max loop */
//        var max=1;
//        $('#div-silymchs').find('.loop-index').each(function () {
//            if($(this).text()>max)
//            k++;
//        });



        var num = parseInt(id_car_block) + 1;

        var is = $('div #klon' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #klon' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'klon' + num);

        $klon.insertAfter($('.teacher_row').last());

        /* new name */
        var $div_new = $('#klon'+num);
        $div_new.find('td').find('.mark').attr('name','silymchs['+num+'][mark]');
        $div_new.find('td').find('.pasp_name').attr('name','silymchs['+num+'][pasp_name]');
        $div_new.find('td').find('.locorg_name').attr('name','silymchs['+num+'][locorg_name]');
        $div_new.find('td').find('.v_ac').attr('name','silymchs['+num+'][v_ac]');
        $div_new.find('td').find('.man_per_car').attr('name','silymchs['+num+'][man_per_car]');
        $div_new.find('td').find('.time_exit').attr('name','silymchs['+num+'][time_exit]');
        $div_new.find('td').find('.time_arrival').attr('name','silymchs['+num+'][time_arrival]');
        $div_new.find('td').find('.is_return').attr('name','silymchs['+num+'][is_return]');
        $div_new.find('td').find('.time_follow').attr('name','silymchs['+num+'][time_follow]');
        $div_new.find('td').find('.time_end').attr('name','silymchs['+num+'][time_end]');
        $div_new.find('td').find('.time_return').attr('name','silymchs['+num+'][time_return]');
        $div_new.find('td').find('.distance').attr('name','silymchs['+num+'][distance]');
        $div_new.find('td').find('.sort').attr('name','silymchs['+num+'][sort]');
        $div_new.find('td').find('.id_teh').attr('name','silymchs['+num+'][id_teh]');
        $div_new.find('td').find('.id_silymchs').attr('name','silymchs['+num+'][id_silymchs]');

        $div_new.find('td').find('input').val('');
        $div_new.find('td').find('.is_return').prop('checked',false);
        $div_new.find('td').find('.is_return').data('numb',num);
        $div_new.find('td').find('.time_arrival').prop('disabled', false);
        $div_new.find('.loop-index').text(new_loop);
        $div_new.attr('data-loop',new_loop);

        $div_new.find('td').find('.sort').val(new_loop);


        return false;
    });



    $('body').on('click', '.del-teacher', function (e) {

        e.preventDefault();

        if ($(".teacher-list").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;

            $('#div-silymchs').find('.loop-index').each(function () {
                $(this).text(k);
                k++;
            });

            $('#div-silymchs').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });




    /* ----------------- add innerservice --------------- */
    $('body').on('click', '#add-row-innerservice', function (e) {
        e.preventDefault();

        var $div = $('div[id^="innerservice_id_row"]:last');
        var $div_for_clon = $('.innerservice_row:last');


        var id_car_block=$div_for_clon.data('loop');
        var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #innerservice_id_row' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #innerservice_id_row' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'innerservice_id_row' + num);

        $klon.insertAfter($('.innerservice_row').last());


        /* new name */
        var $div_new = $('#innerservice_id_row'+num);
        $div_new.find('td').find('.service_id').attr('name','innerservice['+num+'][service_id]');
        $div_new.find('td').find('.innerservice_msg_time').attr('name','innerservice['+num+'][time_msg]');
        $div_new.find('td').find('.innerservice_time_arrival').attr('name','innerservice['+num+'][time_arrival]');
        $div_new.find('td').find('.innerservice_distance').attr('name','innerservice['+num+'][distance]');
        $div_new.find('td').find('.innerservice_note').attr('name','innerservice['+num+'][note]');
        $div_new.find('td').find('.work_innerservice').attr('name','innerservice['+num+'][work_innerservice][]');
        $div_new.find('td').find('.sort').attr('name','innerservice['+num+'][sort]');
        $div_new.find('td').find('.id_innerservice').attr('name','innerservice['+num+'][id_innerservice]');


        $('#innerservice-table').find('.select2-single, .select2-multi').select2({
                placeholder: "Выберите из списка",
                allowClear: true,
                "language": {
                    "noResults": function () {
                        return "Ничего не найдено";
                    }
                }
            });//apply select2 to my element
        $('#innerservice-table').find('.select2-single').last().next().next().remove();
        $('#innerservice-table').find('.select2-multi').last().next().next().remove();

        $div_new.find('td').find('input').val('');
        $div_new.find('td').find('textarea').val('');
        $div_new.find('td').find('.select2-single, .select2-multi').val('').trigger('change');
        $div_new.find('.loop-index').text(new_loop);
        $div_new.attr('data-loop',new_loop);

        $div_new.find('td').find('.sort').val(new_loop);


        return false;
    });


    $('body').on('click', '.del-row-innerservice', function (e) {

        e.preventDefault();

        if ($(".service_id").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;

            $('#div-innerservice').find('.loop-index').each(function () {
                $(this).text(k);
                k++;
            });

            $('#div-innerservice').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });



        /* ----------------- add informing --------------- */
    $('body').on('click', '#add-row-informing', function (e) {
        e.preventDefault();

        var $div = $('div[id^="informing_id_row"]:last');
        var $div_for_clon = $('.informing_row:last');

        var id_car_block=$div_for_clon.data('loop');
        var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #informing_id_row' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #informing_id_row' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'informing_id_row' + num);

        $klon.insertAfter($('.informing_row').last());

        /* new name */
        var $div_new = $('#informing_id_row'+num);
        $div_new.find('td').find('.informing_fio').attr('name','informing['+num+'][fio]');
        $div_new.find('td').find('.informing_msg_time').attr('name','informing['+num+'][time_msg]');
        $div_new.find('td').find('.informing_time_exit').attr('name','informing['+num+'][time_exit]');
        $div_new.find('td').find('.informing_time_arrival').attr('name','informing['+num+'][time_arrival]');
        $div_new.find('td').find('.sort').attr('name','informing['+num+'][sort]');
        $div_new.find('td').find('.id_informing').attr('name','informing['+num+'][id_informing]');


        $div_new.find('td').find('input').val('');
        $div_new.find('.loop-index').text(new_loop);
        $div_new.attr('data-loop',new_loop);

        $div_new.find('td').find('.sort').val(new_loop);


        return false;
    });


    $('body').on('click', '.del-row-informing', function (e) {

        e.preventDefault();

        if ($(".informing_fio").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;

            $('#div-informing').find('.loop-index').each(function () {
                $(this).text(k);
                k++;
            });

            $('#div-informing').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });






        /* ----------------- add trunks --------------- */
    $('body').on('click', '#add-row-trunks-tr', function (e) {
        e.preventDefault();

        var $div = $('div[id^="trunks_row_id"]:last');
        var $div_for_clon = $('.trunks_row:last');

        var id_car_block=$div_for_clon.data('loop');
        var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #trunks_row_id' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #trunks_row_id' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'trunks_row_id' + num);

        $klon.insertAfter($('.trunks_row').last());

        /* new name */
        var $div_new = $('#trunks_row_id'+num);
        $div_new.find('td').find('.mark_trunks').attr('name','trunks['+num+'][mark]');
        $div_new.find('td').find('.pasp_name_trunks').attr('name','trunks['+num+'][pasp_name]');
        $div_new.find('td').find('.locorg_name_trunks').attr('name','trunks['+num+'][locorg_name]');
        $div_new.find('td').find('.v_ac_trunks').attr('name','trunks['+num+'][v_ac]');
        $div_new.find('td').find('.man_per_car_trunks').attr('name','trunks['+num+'][man_per_car]');
        $div_new.find('td').find('.time_arrival_trunks').attr('name','trunks['+num+'][time_arrival]');
        $div_new.find('td').find('.s_fire_arrival_trunks').attr('name','trunks['+num+'][s_fire_arrival]');
        $div_new.find('td').find('.time_pod_trunks').attr('name','trunks['+num+'][time_pod]');
        $div_new.find('td').find('.means_trunks').attr('name','trunks['+num+'][means_trunks]');
        $div_new.find('td').find('.water_po_out_trunks').attr('name','trunks['+num+'][water_po_out]');
        $div_new.find('td').find('.time_loc_trunks').attr('name','trunks['+num+'][time_loc]');
        $div_new.find('td').find('.s_fire_loc_trunks').attr('name','trunks['+num+'][s_fire_loc]');
        $div_new.find('td').find('.time_likv_trunks').attr('name','trunks['+num+'][time_likv]');
        $div_new.find('td').find('.id_teh').attr('name','trunks['+num+'][id_teh]');
        $div_new.find('td').find('.set_ac_pg_trunks').attr('name','trunks['+num+'][set_ac_pg]');

        $div_new.find('td').find('.sort').attr('name','trunks['+num+'][sort]');

        $div_new.find('td').find('.id_trunks').attr('name','trunks['+num+'][id_trunks]');
        $div_new.find('td').find('.vid_t').attr('name','trunks['+num+'][vid_t]');

        $div_new.find('td').find('.actions_ls_trunks').attr('name','trunks['+num+'][actions_ls]');
        $div_new.find('td').find('.actions_ls_btn_trunks').attr('id','actions_ls_btn_trunks_'+num);
        $div_new.find('td').find('.actions_ls_btn_trunks').css('color','black');

       // $div_new.find('td').find('input').val('');
        $div_new.find('td').find('.mark_trunks, .pasp_name_trunks,.locorg_name_trunks, .v_ac_trunks, .man_per_car_trunks, .time_arrival_trunks, .time_pod_trunks, .means_trunks, .water_po_out_trunks, .sort, .id_teh, .actions_ls_trunks, .id_trunks, .vid_t,set_ac_pg_trunks').val('');
        $div_new.find('.loop-index').text(new_loop);
        $div_new.attr('data-loop',new_loop);

        $div_new.find('td').find('.sort').val(new_loop);


        return false;
    });


    $('body').on('click', '.del-row-trunks', function (e) {

        e.preventDefault();

        if ($(".mark_trunks").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;
            $('#trunks-block').find('.loop-index').each(function () {
                $(this).text(k);
                k++;
            });
            $('#trunks-block').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });



    $('body').on('click', '.actions_ls_btn_trunks', function (e) {

        e.preventDefault();

        var field = $(this).parent().parent().find('.actions_ls_trunks');

        if (field.hasClass('hide')) {
            field.removeClass('hide');
        } else {
            field.addClass('hide');
        }

        return false;
    });

});

function setSFire(val){
$('.s_fire_arrival_trunks').val($(val).val());
}

function setSFireLoc(val){
$('.s_fire_loc_trunks').val($(val).val());
}
function setTimeFireLoc(val){
$('.time_loc_trunks').val($(val).val());
}
function setTimeFireLikv(val){
$('.time_likv_trunks').val($(val).val());
}



function setTimeFollow(td) {

    var firstDate = $(td).parent().parent().find('.time_exit').val();
    var secondDate = $(td).parent().parent().find('.time_arrival').val();

    if ((firstDate === '' || secondDate === '')  || (firstDate >= secondDate)) {

        $(td).parent().parent().find('.time_follow').val(0);
    } else {
        let getDate = (string) => new Date(0, 0, 0, string.split(':')[0], string.split(':')[1]);
        let different = (getDate(secondDate) - getDate(firstDate));
        let differentRes, hours, minuts;
        if (different > 0) {
            differentRes = different;
            hours = Math.floor((differentRes % 86400000) / 3600000);
            minuts = Math.round(((differentRes % 86400000) % 3600000) / 60000);
        } else {
            differentRes = Math.abs((getDate(firstDate) - getDate(secondDate)));
            hours = Math.floor(24 - (differentRes % 86400000) / 3600000);
            minuts = Math.round(60 - ((differentRes % 86400000) % 3600000) / 60000);
        }
        let result = hours + ':' + minuts;

        $(td).parent().parent().find('.time_follow').val(minuts);
        //console.log(result);
    }

}



$('body').on("click", "#div-silymchs table .up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort=1;

    $('#div-silymchs').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });

    $('#div-silymchs').find('.sort').each(function () {
        $(this).val(k_sort);
        k_sort++;
    });
  });




 $('body').on("click", "#div-innerservice table .up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort = 1;
    $('#div-innerservice').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });
            $('#div-innerservice').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });
  });

   $('body').on("click", "#div-informing table .up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort=1;
    $('#div-informing').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });
    $('#div-informing').find('.sort').each(function () {
        $(this).val(k_sort);
        k_sort++;
    });
  });


    $('body').on("click", "#div-trunks table .up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort = 1;
    $('#div-trunks').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });

    $('#div-trunks').find('.sort').each(function () {
        $(this).val(k_sort);
        k_sort++;
    });
  });

function returnTeh(t) {

var i=$(t).data('numb');
    var time_arrival = $('input[name="silymchs[' + i + '][time_arrival]"]');
    var time_follow = $('input[name="silymchs[' + i + '][time_follow]"]');
    var time_end = $('input[name="silymchs[' + i + '][time_end]"]');

    if ($(t).is(':checked') === true) {
        time_arrival.val('');
        time_arrival.prop('disabled', true);

        time_follow.val('');
        time_follow.prop('disabled', true);

        time_end.val('');
        time_end.prop('disabled', true);
    } else {
        time_arrival.prop('disabled', false);
        time_follow.prop('disabled', false);
        time_end.prop('disabled', false);
    }
}






        /* -----------------copy trunk --------------- */

        $('#modal-copy-trunk').on('show.bs.modal', function (e) {

            var btn = $(e.relatedTarget);

            var row_id= btn.parent().parent().prop('id');
           // alert(row_id);
             $('#modal-copy-trunk').find('#btn-copy-trunk').attr('data-rid',row_id);

        });

    $('body').on('click', '#btn-copy-trunk', function (e) {
        e.preventDefault();


        var row_id= $(this).attr('data-rid');
         var $row=$('#'+row_id);

        //var $div = $('div[id^="trunks_row_id"]:last');
        var $div_for_clon = $('.trunks_row:last');

        var id_car_block=$div_for_clon.data('loop');
        var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #trunks_row_id' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #trunks_row_id' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        //var $klon = $div_for_clon.clone().prop('id', 'trunks_row_id' + num);
         var $klon = $row.clone().prop('id', 'trunks_row_id' + num);

        //$klon.insertAfter($('.trunks_row').last());
        $klon.insertAfter($row);

        /* new name */
        var $div_new = $('#trunks_row_id'+num);
        $div_new.addClass('copied_row');
        $div_new.find('td').find('.mark_trunks').attr('name','trunks['+num+'][mark]');
        $div_new.find('td').find('.pasp_name_trunks').attr('name','trunks['+num+'][pasp_name]');
        $div_new.find('td').find('.locorg_name_trunks').attr('name','trunks['+num+'][locorg_name]');
        $div_new.find('td').find('.v_ac_trunks').attr('name','trunks['+num+'][v_ac]');
        $div_new.find('td').find('.man_per_car_trunks').attr('name','trunks['+num+'][man_per_car]');
        $div_new.find('td').find('.time_arrival_trunks').attr('name','trunks['+num+'][time_arrival]');
        $div_new.find('td').find('.s_fire_arrival_trunks').attr('name','trunks['+num+'][s_fire_arrival]');
        $div_new.find('td').find('.time_pod_trunks').attr('name','trunks['+num+'][time_pod]');
        $div_new.find('td').find('.means_trunks').attr('name','trunks['+num+'][means_trunks]');
        $div_new.find('td').find('.water_po_out_trunks').attr('name','trunks['+num+'][water_po_out]');
        $div_new.find('td').find('.time_loc_trunks').attr('name','trunks['+num+'][time_loc]');
        $div_new.find('td').find('.s_fire_loc_trunks').attr('name','trunks['+num+'][s_fire_loc]');
        $div_new.find('td').find('.time_likv_trunks').attr('name','trunks['+num+'][time_likv]');
        $div_new.find('td').find('.id_teh').attr('name','trunks['+num+'][id_teh]');
        $div_new.find('td').find('.set_ac_pg_trunks').attr('name','trunks['+num+'][set_ac_pg]');

        $div_new.find('td').find('.sort').attr('name','trunks['+num+'][sort]');

        $div_new.find('td').find('.id_trunks').attr('name','trunks['+num+'][id_trunks]');
        $div_new.find('td').find('.vid_t').attr('name','trunks['+num+'][vid_t]');

        $div_new.find('td').find('.actions_ls_trunks').attr('name','trunks['+num+'][actions_ls]');
        $div_new.find('td').find('.actions_ls_btn_trunks').attr('id','actions_ls_btn_trunks_'+num);
        $div_new.find('td').find('.actions_ls_btn_trunks').css('color','black');

        $div_new.find('td').find(' .means_trunks, .sort, .id_trunks').val('');

        $div_new.find('.loop-index').text(new_loop);
        $div_new.attr('data-loop',new_loop);

        $div_new.find('td').find('.sort').val(new_loop);


            var k = 1;
            var k_sort = 1;
            $('#trunks-block').find('.loop-index').each(function () {
                $(this).text(k);
                k++;
            });
            $('#trunks-block').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });


        $('#modal-copy-trunk').click();
        return false;
    });
