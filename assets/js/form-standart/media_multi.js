
$('body').on('change', '.adapt-img-multi, .adapt-img-cloud-multi', function (e) {
    let $el = $(this),
            fileName,
            i,
            $span = $el.parent().find('.upload-info span');

    if (e.target.value !== null && e.target.value !== '') {
        if ($el.val().lastIndexOf('\\')) {
            i = $el.val().lastIndexOf('\\') + 1;
        } else {
            i = $el.val().lastIndexOf('/') + 1;
        }
        fileName = $el.val().slice(i);
        //$span.html(fileName + '<p class="upload-info-loading">' + loading + '</p>');

        let files = this.files;

        if (typeof files == 'undefined')
            return;
        let data = new FormData();

        $.each(files, function (key, value) {
            data.append('file[]', value);
        });

        data.append('my_file_upload', 1);

        let $bar = $(e.target).parent().next(".progress");
        $bar.find('.progress-bar').removeClass('error');

        function setProgress(e) {
            if (e.lengthComputable) {
                let complete = e.loaded / e.total;
                $("#text-file-completed").text("Загружено " + Math.floor(complete * 100) + "%");
                $($bar.eq(0).children().eq(0)).css("width", Math.floor(complete * 100) + "%");
            }
        }

        $bar.animate({height: 'show'});

        if ($el.hasClass('student-test-adapt-img')) {
            let deleteButton = $el.closest('.passing-test-wrapp').find('.upload-info-clear-multi');
            if (deleteButton.length > 0) {
                deleteButton.closest('p').detach();
            }
        }

        $.ajax({
            xhr: function () {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", setProgress, false);
                xhr.addEventListener("progress", setProgress, false);
                return xhr;
            },
            url: $el.data('api-url'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false,
        }).done(function (data) {
            let $inpName = $("#" + $el.data('inp-name'));



            if (data.is_ok) {
                //console.log('kk');

var arr_val=[];
                        $.each(data.images, function (key, value) {
arr_val.push(value.file_name);
                $el.parent().find('.issue-img-multi').remove();
                if ($(e.target).prop('files') !== undefined && $(e.target).prop('files') !== null) {
                    let file = $(e.target).prop('files')[0];
                    let type = file['type'].split('/')[0];
                    if (file) {
                        switch (type) {
                            case 'image':
                                console.log(value.success);
                                if (!$el.hasClass('student-test-adapt-img')) {
                                    $el.parent().parent().prepend('<img src="' + value.success + '" class="issue-img-multi" data-name="'+ value.file_name +'" ><i class="fa fa-times-circle-o fa-lg delete-img-multi" data-name="'+ value.file_name +'" aria-hidden="true"></i>');
                                }
                                break;
                            case 'video':
                                if (!$el.hasClass('student-test-adapt-img')) {
                                    $el.parent().prepend('<video controls class="issue-video-multi" data-name="'+ value.file_name +'"><source src="' + value.success + '"></video><i class="fa fa-times-circle-o fa-lg delete-icon-video-multi" data-name="'+ value.file_name +'" aria-hidden="true"></i>');
                                }
                                break;
                            case 'audio':
                                if (!$el.hasClass('student-test-adapt-img')) {
                                    $el.parent().prepend('<audio controls class="issue-audio-multi"  data-name="'+ value.file_name +'"><source src="' + value.success + '"></audio><i class="fa fa-times-circle-o fa-lg delete-icon-audio-multi" data-name="'+ value.file_name +'" aria-hidden="true"></i>');
                                }
                                break;
                        }
                    }
                }


        });

                        $inpName.val(arr_val);
                $span.removeClass('error');
                var delete_file = 'удалить файлы';
                //$span.html('<p class="upload-info-name">' + decodeURIComponent(arr_val) + '</p><p><a class="upload-info-clear-multi" href="#">' + delete_file + '</a></p>');
                $span.html('</p><p><a class="upload-info-clear-multi" href="#">' + delete_file + '</a></p>');
                $bar.animate({height: 'hide'});
                if (!$el.hasClass('student-test-adapt-img')) {
                    $el.hide();
                }

                if ($el.hasClass('student-test-adapt-img')) {
                    $el.after('<p style="margin-left: 150px;"><a class="upload-info-clear-multi student-test-upload-info-clear-multi" href="#">' + delete_file + '</a></p>');
                }


            }
//
//            if (data.origname) {
//                $inpName.val(data.success);
//                $("#" + $el.data('inp-origname')).val(data.origname);
//                $inpName.next().next().find('span').html(data.origname.replace(/\_/g, ' '));
//            }
//
//            if (data.orignamefile) {
//                $inpName.val(data.success);
//                $("#" + $el.data('inp-orignamefile')).val(data.orignamefile);
//                $inpName.next().next().find('span').html(data.orignamefile.replace(/\_/g, ' '));
//            }

            if (data.error) {
                console.log(data.error);
                //data.error = $(data.error).addClass('error-text').prop('outerHTML');

                $inpName.val('');
                $inpName.next().val('');
                //todo вывод ошибка чуть ниже поля
                if ($el.hasClass('student-test-adapt-img')) {
                    $el.after(data.error);
                } else {
                    $span.addClass('error');

                    // $inpName.next().attr('required', 'required');
                    $span.html(data.error + '<p>' + $span.data('text') + '</p>');
                    $bar.find('.progress-bar').addClass('error');
                    $bar.animate({height: 'show'});
                }

            }
        });
    }
});





// button delete img
$('body').on('click', '.upload-info-clear-multi', function (e) {

    e.preventDefault();

    if ($(this).hasClass('student-test-upload-info-clear-multi')) {
        let adaptImg = $(this).closest('.passing-test-wrapp').find('.adapt-img-multi');
        adaptImg.val('');
        $('#' + adaptImg.attr('data-inp-name')).val('');
        $(this).detach();
        $(this).closest('.passing-test-wrapp').find('.error').remove();
    } else {

        let $uploadInput = $(this).parents('#upload-input');

        if ($uploadInput.hasClass('video')) {// reset video

            let $adaptImg = $uploadInput.find('.adapt-img-cloud-multi');
            let $span = $uploadInput.find('.upload-info span');
            let upload_text;

            $uploadInput.find('.issue-video-multi').remove();
            $uploadInput.parent().find('.delete-icon-video-multi').remove();

            if ($span.data('text') !== null) {
                upload_text = $span.data('text');

            } else {
                upload_text = "загрузить файлы";
            }

            $('#' + $adaptImg.data('inp-name')).val('');
            $adaptImg.show();
            $span.html('<p>' + upload_text + '</p>');

            $.each($('.delete_video_multi'), function (key, value) {
               $(this).remove();
            });

        }
        else if ($uploadInput.hasClass('audio')) {// reset audio

            let $adaptImg = $uploadInput.find('.adapt-img-cloud-multi');
            let $span = $uploadInput.find('.upload-info span');
            let upload_text;

            $uploadInput.find('.issue-audio-multi').remove();
            $uploadInput.parent().find('.delete-icon-audio-multi').remove();

            if ($span.data('text') !== null) {
                upload_text = $span.data('text');

            } else {
                upload_text = "загрузить файлы";
            }

            $('#' + $adaptImg.data('inp-name')).val('');
            $adaptImg.show();
            $span.html('<p>' + upload_text + '</p>');

            $.each($('.delete_audio_multi'), function (key, value) {
               $(this).remove();
            });

        }
        else {
            let $adaptImg = $uploadInput.find('.adapt-img-multi');
            let $span = $uploadInput.find('.upload-info span');
            let upload_text;

            $uploadInput.parent().find('.issue-img-multi').remove();
            $uploadInput.parent().find('.delete-img-multi').remove();


            if ($span.data('text') !== null) {
                upload_text = $span.data('text');
            } else {
                upload_text = upload_file; // Берём надпись "загрузить файл" из языкового файла
            }

            $('#' + $adaptImg.data('inp-name')).val('');
            $adaptImg.show();
            $span.html('<p>' + upload_text + '</p>');

            $.each($('.delete_photo_multi'), function (key, value) {
               $(this).remove();
            });
        }
    }
});




// button delete img from preview
$('body').on('click', '.delete-img-multi', function (e) {

    e.preventDefault();

    var file = $(this).attr('data-name');


    $.each($('.issue-img-multi'), function (key, value) {
        if ($(this).attr('data-name') === file) {
            $(this).remove();

        }
    });

    $(this).parent().append('<input type="hidden" class="delete_photo_multi" name="delete_photo_multi[]" value="' + file + '">');



    if ($('.issue-img-multi').length === 0) {

        $(this).parent().find('.upload-info-clear-multi').click();
        $.each($('.delete_photo_multi'), function (key, value) {
            $(this).remove();
        });

    }
    $(this).remove();
});



// button delete video from preview
$('body').on('click', '.delete-icon-video-multi', function (e) {

    e.preventDefault();

    var file = $(this).attr('data-name');


    $.each($('.issue-video-multi'), function (key, value) {
        if ($(this).attr('data-name') === file) {
            $(this).remove();

        }
    });

    $(this).parent().append('<input type="hidden" class="delete_video_multi" name="delete_video_multi[]" value="' + file + '">');



    if ($('.issue-video-multi').length === 0) {

        $(this).parent().find('.upload-info-clear-multi').click();
        $.each($('.delete_video_multi'), function (key, value) {
            $(this).remove();
        });

    }

    $(this).remove();
});





// button delete audio from preview
$('body').on('click', '.delete-icon-audio-multi', function (e) {

    e.preventDefault();

    var file = $(this).attr('data-name');


    $.each($('.issue-audio-multi'), function (key, value) {
        if ($(this).attr('data-name') === file) {
            $(this).remove();

        }
    });

    $(this).parent().append('<input type="hidden" class="delete_audio_multi" name="delete_audio_multi[]" value="' + file + '">');



    if ($('.issue-audio-multi').length === 0) {

        $(this).parent().find('.upload-info-clear-multi').click();
        $.each($('.delete_audio_multi'), function (key, value) {
            $(this).remove();
        });

    }

    $(this).remove();
});

