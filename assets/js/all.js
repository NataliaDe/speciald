$(document).ready(function () {
    $("tfoot").css("display", "table-header-group");//tfoot of table



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

    $('.select2-multi-not-clear').select2({
        placeholder: "Выберите из списка",
        allowClear: false,
        "language": {
            "noResults": function () {
                return "Ничего не найдено";
            }
        }
    });

    $('.select2-single-face-belong').select2({
        placeholder: "Не установлена",
        allowClear: true,
        "language": {
            "noResults": function () {
                return "Ничего не найдено";
            }
        }
    });


    $('.select2-filter-header').select2({
        placeholder: "все",
        allowClear: false,
        "language": {
            "noResults": function () {
                return "Ничего не найдено";
            }
        }
    });




});

function ckeditor_init(id) {
    CKEDITOR.replace(id);
    CKEDITOR.on('dialogDefinition', function (event) {
        var definition = event.data.definition;
        if (event.data.name === 'link') {
            definition.removeContents('advanced');
            definition.removeContents('target');
        }
    });
}


//$('.float-class').keypress(function (key) {
//    if (((key.charCode < 48) && (key.charCode != 46)) || (key.charCode > 57))
//        return false;
//});


function allowFloat() {

    if (((event.which < 48) && (event.which != 46)) || (event.which > 57)) {
        //alert(event.which);
        event.preventDefault();
        // return false;
    }
}



function allowFloatTrunks() {

    if (((event.which < 48) && (event.which != 46) && (event.which != 47)) || (event.which > 57)) {
        //alert(event.which);
        event.preventDefault();
        // return false;
    }
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});



$(function () {
    $('[data-toggle="popover"]').popover();
});


(function ($) {
    "use strict";
    $(document.body).delegate('[type="checkbox"][readonly="readonly"]', 'click', function (e) {
        e.preventDefault();
    });


}(window.jQuery));



// declination
function declOfNum(number, titles)
{
    cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number % 100 > 4 && number % 100 < 20) ? 2 : cases[(number % 10 < 5) ? number % 10 : 5] ];
}