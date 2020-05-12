
$('body').on('change', '.adapt-img, .adapt-img-cloud', function (e) {
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
            data.append('file', value);
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
            let deleteButton = $el.closest('.passing-test-wrapp').find('.upload-info-clear');
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

            if (data.success) {
                $el.parent().find('.issue-img').remove();
                if ($(e.target).prop('files') !== undefined && $(e.target).prop('files') !== null) {
                    let file = $(e.target).prop('files')[0];
                    let type = file['type'].split('/')[0];
                    if (file) {
                        switch (type) {
                            case 'image':
                                if (!$el.hasClass('student-test-adapt-img')) {
                                    $el.parent().prepend('<img src="' + data.success + '" class="issue-img">');
                                }
                                break;
                            case 'video':
                                if (!$el.hasClass('student-test-adapt-img')) {
                                    $el.parent().prepend('<video controls class="issue-video"><source src="' + data.success + '"></video>');
                                }
                                break;
                        }
                    }
                }

                $inpName.val(data.file_name);
                $span.removeClass('error');
                var delete_file = 'удалить файл';
                $span.html('<p class="upload-info-name">' + decodeURIComponent(data.file_name) + '</p><p><a class="upload-info-clear" href="#">' + delete_file + '</a></p>');
                $bar.animate({height: 'hide'});
                if (!$el.hasClass('student-test-adapt-img')) {
                    $el.hide();
                }

                if ($el.hasClass('student-test-adapt-img')) {
                    $el.after('<p style="margin-left: 150px;"><a class="upload-info-clear student-test-upload-info-clear" href="#">' + delete_file + '</a></p>');
                }

            }

            if (data.origname) {
                $inpName.val(data.success);
                $("#" + $el.data('inp-origname')).val(data.origname);
                $inpName.next().next().find('span').html(data.origname.replace(/\_/g, ' '));
            }

            if (data.orignamefile) {
                $inpName.val(data.success);
                $("#" + $el.data('inp-orignamefile')).val(data.orignamefile);
                $inpName.next().next().find('span').html(data.orignamefile.replace(/\_/g, ' '));
            }

            if (data.error) {
                data.error = $(data.error).addClass('error-text').prop('outerHTML');

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
$('body').on('click', '.upload-info-clear', function (e) {

    e.preventDefault();

    if ($(this).hasClass('student-test-upload-info-clear')) {
        let adaptImg = $(this).closest('.passing-test-wrapp').find('.adapt-img');
        adaptImg.val('');
        $('#' + adaptImg.attr('data-inp-name')).val('');
        $(this).detach();
        $(this).closest('.passing-test-wrapp').find('.error').remove();
    } else {

        let $uploadInput = $(this).parents('#upload-input');

        if ($uploadInput.hasClass('video')) {// reset video

            let $adaptImg = $uploadInput.find('.adapt-img-cloud');
            let $span = $uploadInput.find('.upload-info span');
            let upload_text;

            $uploadInput.find('.issue-video').remove();

            if ($span.data('text') !== null) {
                upload_text = $span.data('text');

            } else {
                upload_text = "загрузить файл";
            }

            $('#' + $adaptImg.data('inp-name')).val('');
            $adaptImg.show();
            $span.html('<p>' + upload_text + '</p>');

        } else {
            let $adaptImg = $uploadInput.find('.adapt-img');
            let $span = $uploadInput.find('.upload-info span');
            let upload_text;

            $uploadInput.find('.issue-img').remove();

            if ($span.data('text') !== null) {
                upload_text = $span.data('text');
            } else {
                upload_text = upload_file; // Берём надпись "загрузить файл" из языкового файла
            }

            $('#' + $adaptImg.data('inp-name')).val('');
            $adaptImg.show();
            $span.html('<p>' + upload_text + '</p>');
        }


    }
});



//   $('body').on('click', '#save-sd-media', function (e) {
//        e.preventDefault();
//
//        var data=$('#form-sd-media').serializeArray();
//
//    });