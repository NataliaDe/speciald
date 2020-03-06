
$(document).ready(function () {

        /* ----------------- add water source --------------- */
    $('body').on('click', '#add-row-water-source', function (e) {
        e.preventDefault();

        var $div = $('div[id^="water_source_id_row"]:last');
        var $div_for_clon = $('.water_source_row:last');

        var id_car_block=$div_for_clon.data('loop');
        var new_loop=parseInt($div_for_clon.find('.loop-index').text())+1;

        var num = parseInt(id_car_block) + 1;

        var is = $('div #water_source_id_row' + num);
        while ((is.length > 0)) {
            var num = num + 1;
            var is = $('div #water_source_id_row' + num);
        }

        // Clone it and assign the new ID (i.e: from num 4 to ID "klon4")
        var $klon = $div_for_clon.clone().prop('id', 'water_source_id_row' + num);

        $klon.insertAfter($('.water_source_row').last());

        /* new name */
        var $div_new = $('#water_source_id_row'+num);
        $div_new.find('td').find('.water_source_type').attr('name','water_source['+num+'][water_source_type]');
        $div_new.find('td').find('.water_source_distance').attr('name','water_source['+num+'][water_source_distance]');
        $div_new.find('td').find('.water_source_use').attr('name','water_source['+num+'][water_source_use]');
        $div_new.find('td').find('.sort').attr('name','water_source['+num+'][sort]');

        $div_new.find('td').find('.id_water_source').attr('name','water_source['+num+'][id_water_source]');


        $('#water-source-table').find('.select2-single').select2({
                placeholder: "Выберите из списка",
                allowClear: true,
                "language": {
                    "noResults": function () {
                        return "Ничего не найдено";
                    }
                }
            });//apply select2 to my element
        $('#water-source-table').find('.select2-single').last().next().next().remove();


        $div_new.find('td').find('input').val('');
        $div_new.find('td').find('.select2-single').val('').trigger('change');

        $div_new.find('.loop-index').text(new_loop);
        $div_new.attr('data-loop',new_loop);

        $div_new.find('td').find('.sort').val(new_loop);


        return false;
    });


    $('body').on('click', '.del-row-water-source', function (e) {

        e.preventDefault();

        if ($(".water_source_type").length > 1) {

            $(this).parent().parent().remove();

            var k = 1;
            var k_sort = 1;

            $('#div-water-source').find('.loop-index').each(function () {
                $(this).text(k);
                k++;
            });

            $('#div-water-source').find('.sort').each(function () {
                $(this).val(k_sort);
                k_sort++;
            });

        }
        return false;
    });

});

    $("#div-water-source table").on("click", ".up, .down", function(){
     var $row = $(this).closest("tr");

     var up = $(this).hasClass("up");

     var $t = up  ?  $row.prev() : $row.next() ;
     if($t.length){
        up ? $t.insertAfter($row) : $t.insertBefore($row);
     }

    var k = 1;
    var k_sort = 1;
    $('#div-water-source').find('.loop-index').each(function () {
        $(this).text(k);
        k++;
    });
    $('#div-water-source').find('.sort').each(function () {
        $(this).val(k_sort);
        k_sort++;
    });
  });







