$('body').on('click', '.show-media-catalog', function (e) {

    e.preventDefault();

    var id = $(this).attr('data-id');
    var sd = $(this).attr('data-number-sd');

    $('#modal-catalog-media .modal-header h5').text('Медиа СД ');
    $('#modal-catalog-media .modal-header h5').text('Медиа СД: ' + sd);


    var url = $(this).attr('data-url');
    var data = {'id': id};

    $.post(url, data, function (res) {
        if (parseInt(JSON.parse(res)['success']) === 1) {

            $("#modal-catalog-media .modal-body").html('');
            $('#modal-catalog-media .modal-body').html(JSON.parse(res)['innerHtml']);

        } else {

            toastr.error(parseInt(JSON.parse(res)['error']), 'Ошибка!', {progressBar: true, timeOut: 2500});

        }
    });


});




// button delete img
//$('body').on('click', '.show-media-catalog', function (e) {
//
//    e.preventDefault();
//
//    var video = $(this).attr('data-video');
//    var sd = $(this).attr('data-number-sd');
//
//    $('#modal-catalog-media .modal-header h5').text('Медиа СД ');
//
//    $('#modal-catalog-media .modal-header h5').text('Медиа СД: '+sd);
//
//
//    var i = 1;
//    var j = [];
//    var x = 4;//cnt photo !!!!!!!!!!!!!!!!!!!
//
//    for (i = 1; i <= x; i++) {
//
//        var photo = $(this).attr('data-photo-' + i);
//
//        if (photo !== '') {
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + i).removeClass('hide');
//
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + i).removeClass('active');
//
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + i).find('.nav-link').removeClass('active');
//
//            $('#modal-catalog-media .modal-body').find('#photo_sd_' + i).removeClass('active');
//            $('#modal-catalog-media .modal-body').find('#photo_sd_' + i).removeClass('hide');
//            $('#modal-catalog-media .modal-body').find('#photo_sd_' + i).removeClass('show');
//
//            $('#modal-catalog-media .modal-body').find('#photo_sd_' + i).find('img').attr('src', photo);
//
//            j.push(i);
//
//        } else {
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + i).removeClass('hide');
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + i).addClass('hide');
//
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + i).removeClass('active');
//
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + i).find('.nav-link').removeClass('active');
//
//
//            $('#modal-catalog-media .modal-body').find('#photo_sd_' + i).removeClass('active');
//            $('#modal-catalog-media .modal-body').find('#photo_sd_' + i).removeClass('show');
//
//
//            $('#modal-catalog-media .modal-body').find('#photo_sd_' + i).find('img').attr('src', "");
//
//        }
//
//    }
//    var numb = 0;
//
//    if (j.length > 0) {
//        for (i = 0; i <= j.length; i++) {
//            numb++;
//            $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + j[i]).find('a').text('Фото ' + numb);
//
//        }
//    }
//
//    var min_of_array = Math.min.apply(Math, j);
//
//    $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + min_of_array).addClass('active');
//
//
//    $('#modal-catalog-media .modal-body').find('#li-tab-photo-' + min_of_array).find('.nav-link').addClass('active');
//
//    $('#modal-catalog-media .modal-body').find('#photo_sd_' + min_of_array).addClass('active');
//    $('#modal-catalog-media .modal-body').find('#photo_sd_' + min_of_array).addClass('show');
//
//
//
//
//
//    if (video !== '') {
//        $('#modal-catalog-media .modal-body').find('#li-tab-video').removeClass('hide');
//
//        $('#modal-catalog-media .modal-body').find('#li-tab-video').removeClass('active');
//
//        $('#modal-catalog-media .modal-body').find('#li-tab-video').find('.nav-link').removeClass('active');
//
//        $('#modal-catalog-media .modal-body').find('#video_sd').removeClass('active');
//        $('#modal-catalog-media .modal-body').find('#video_sd').removeClass('hide');
//        $('#modal-catalog-media .modal-body').find('#video_sd').removeClass('show');
//
//        $('#modal-catalog-media .modal-body').find('#video_sd').find('video').html("<source src='" + video + "'>");
//
//        if (j.length === 0) {
//
//            $('#modal-catalog-media .modal-body').find('#li-tab-video').addClass('active');
//
//
//            $('#modal-catalog-media .modal-body').find('#li-tab-video').find('.nav-link').addClass('active');
//
//            $('#modal-catalog-media .modal-body').find('#video_sd').addClass('active');
//            $('#modal-catalog-media .modal-body').find('#video_sd').addClass('show');
//        }
//
//
//    } else {
//        $('#modal-catalog-media .modal-body').find('#li-tab-video').removeClass('hide');
//        $('#modal-catalog-media .modal-body').find('#li-tab-video').addClass('hide');
//
//        $('#modal-catalog-media .modal-body').find('#li-tab-video').removeClass('active');
//
//        $('#modal-catalog-media .modal-body').find('#li-tab-video').find('.nav-link').removeClass('active');
//
//
//        $('#modal-catalog-media .modal-body').find('#video_sd').removeClass('active');
//        $('#modal-catalog-media .modal-body').find('#video_sd').removeClass('show');
//
//
//        $('#modal-catalog-media .modal-body').find('#video_sd').find('video').find('source').attr('src', "");
//
//    }
//
//
//});