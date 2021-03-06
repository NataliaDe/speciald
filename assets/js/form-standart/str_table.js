
$(document).ready(function () {

        /* ----------------- add str table --------------- */
    $('body').on('click', '#add-row-str-tr', function (e) {
        e.preventDefault();

        var $div = $('div[id^="str_id_row"]:last');
        var $div_for_clon = $('.str_row:last');

        var id_car_block=$div_for_clon.data('loop');
        var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #str_id_row' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #str_id_row' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'str_id_row' + num);

        $klon.insertAfter($('.str_row').last());

        /* new name */
        var $div_new = $('#str_id_row'+num);
        $div_new.find('td').find('.str_pasp_name').attr('name','str['+num+'][pasp_name]');
        $div_new.find('td').find('.str_locorg_name').attr('name','str['+num+'][locorg_name]');
        $div_new.find('td').find('.str_shtat').attr('name','str['+num+'][shtat]');
        $div_new.find('td').find('.str_vacant').attr('name','str['+num+'][vacant]');
        $div_new.find('td').find('.str_on_list_ch').attr('name','str['+num+'][on_list_ch]');
        $div_new.find('td').find('.str_vacant_ch').attr('name','str['+num+'][vacant_ch]');
        $div_new.find('td').find('.str_face_ch').attr('name','str['+num+'][face_ch]');
        $div_new.find('td').find('.str_br_ch').attr('name','str['+num+'][br_ch]');
        $div_new.find('td').find('.str_trip_ch').attr('name','str['+num+'][cnt_trip_man]');
        $div_new.find('td').find('.str_holiday_ch').attr('name','str['+num+'][cnt_holiday_man]');
        $div_new.find('td').find('.str_ill_ch').attr('name','str['+num+'][cnt_ill_man]');
        $div_new.find('td').find('.str_duty_ch').attr('name','str['+num+'][cnt_naryd]');
        $div_new.find('td').find('.str_other_ch').attr('name','str['+num+'][cnt_other_man]');
        $div_new.find('td').find('.str_gdzs_ch').attr('name','str['+num+'][gas]');
        $div_new.find('td').find('.sort').attr('name','str['+num+'][sort]');
        $div_new.find('td').find('.id_pasp').attr('name','str['+num+'][id_pasp]');

        $div_new.find('td').find('.id_str').attr('name','str['+num+'][id_str]');


        $div_new.find('td').find('input').val('');
        $div_new.find('.loop-index').text(new_loop);
        $div_new.attr('data-loop',new_loop);

        $div_new.find('td').find('.sort').val(new_loop);


        return false;
    });


    $('body').on('click', '.del-str-row', function (e) {

        e.preventDefault();

        if ($(".str_pasp_name").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;

            $('#div-str').find('.loop-index').each(function () {
                $(this).text(k);
                k++;
            });
            $('#div-str').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });


          /* ----------------- add str text block --------------- */
    $('body').on('click', '#add-str-text-row', function (e) {
        e.preventDefault();

        var $div = $('div[id^="str_text_id_row"]:last');
        var $div_for_clon = $('.str_text_row:last');

        var id_car_block=$div_for_clon.data('loop');
        //var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;
        var new_loop=parseInt($div_for_clon.find('.sort').val())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #str_text_id_row' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #str_text_id_row' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'str_text_id_row' + num);

        $klon.insertAfter($('.str_text_row').last());

        /* new name */
        var $div_new = $('#str_text_id_row'+num);
        $div_new.find('td').find('.str_text_podr_name').attr('name','str_text['+num+'][str_text_podr_name]');
        $div_new.find('td').find('.str_text_descr').attr('name','str_text['+num+'][str_text_description]');
        $div_new.find('td').find('.sort').attr('name','str_text['+num+'][sort]');
        $div_new.find('td').find('.id_pasp_text').attr('name','str_text['+num+'][id_pasp]');

        $div_new.find('td').find('.id_str').attr('name','str_text['+num+'][id_str_text]');

        $div_new.find('td').find('input').val('');
        $div_new.find('td').find('textarea').val('');

        $div_new.find('td').find('.sort').val(new_loop);

       // $div_new.find('.loop-index').text(new_loop);
       // $div_new.attr('data-loop',new_loop);


        return false;
    });


    $('body').on('click', '.del-str-text-row', function (e) {

        e.preventDefault();

        if ($(".str_text_podr_name").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;
            $('#str-text table').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });

});

    $('body').on("click", "#div-str .table-str .up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort = 1;
    $('#div-str .table-str').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });

    $('#div-str .table-str').find('.sort').each(function () {
        $(this).val(k_sort);
        k_sort++;
    });
  });


      $('body').on("click", "#div-str #str-text table .up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort = 1;
    $('#div-str #str-text table').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });
    $('#str-text table').find('.sort').each(function () {
        $(this).val(k_sort);
        k_sort++;
    });
  });





          /* ----------------- add str vacant info block --------------- */
    $('body').on('click', '#add-str-vacant-row', function (e) {
        e.preventDefault();

        var $div = $('div[id^="str_vacant_info_id_row"]:last');
        var $div_for_clon = $('.str_vacant_info_row:last');

        var id_car_block=$div_for_clon.data('loop');
        //var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;
        var new_loop=parseInt($div_for_clon.find('.sort').val())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #str_vacant_info_id_row' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #str_vacant_info_id_row' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'str_vacant_info_id_row' + num);

        $klon.insertAfter($('.str_vacant_info_row').last());

        /* new name */
        var $div_new = $('#str_vacant_info_id_row'+num);
        $div_new.find('td').find('.str_vacant_info_podr_name').attr('name','str_vacant_info['+num+'][podr_name]');
        $div_new.find('td').find('.str_vacant_info_descr').attr('name','str_vacant_info['+num+'][description]');
        $div_new.find('td').find('.sort').attr('name','str_vacant_info['+num+'][sort]');
        $div_new.find('td').find('.id_pasp_vacant_info').attr('name','str_vacant_info['+num+'][id_pasp]');

        $div_new.find('td').find('.id_str_vacant_info').attr('name','str_vacant_info['+num+'][id_str_vacant_info]');

        $div_new.find('td').find('input').val('');
        $div_new.find('td').find('textarea').val('');

        $div_new.find('td').find('.sort').val(new_loop);

       // $div_new.find('.loop-index').text(new_loop);
       // $div_new.attr('data-loop',new_loop);


        return false;
    });



        $('body').on('click', '.del-str-vacant-info-row', function (e) {

        e.preventDefault();

        if ($(".str_vacant_info_podr_name").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;
            $('#collapse_vacant_block table').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });



      $('body').on("click", "#accordion_vacant #collapse_vacant_block table .up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort = 1;
    $('#accordion_vacant #collapse_vacant_block table').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });
    $('#accordion_vacant #collapse_vacant_block table').find('.sort').each(function () {
        $(this).val(k_sort);
        k_sort++;
    });
  });

