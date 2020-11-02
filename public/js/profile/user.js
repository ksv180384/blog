$(document).ready(function(){

    // Отслеживаем посты пользователя
    $('body').on('submit', '#formAddFollow', function(e){
        e.preventDefault();

        const thisBtn = $('#followBtn');
        const $form = $(this);

        thisBtn.prop('disabled', true);
        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                thisBtn.prop('disabled', false);
                if(response.data.success != 'Y'){
                    toastr.error(response.data.message);
                    return true;
                }
                toastr.success(response.data.message);
                $('#formAddFollow').replaceWith(response.data.html);
                $('#followTo').text(response.data.follow_count);

            })
            .catch(function (error) {
                thisBtn.prop('disabled', false);
                if(error.response){
                    var error_text = '';
                    $.each(error.response.data.errors, function (index, val) {
                        error_text += val[0] + '<br>';
                    });
                    toastr.error(error.response.data.message + '<br>' + error_text);
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });
    });

    // Перестаем отслеживать посты пользователя
    $('body').on('submit', '#formDestriyFollow', function(e){
        e.preventDefault();

        const $form = $(this);
        const $button = $('#followDestroyBtn');

        $button.prop('disabled', true);
        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                $button.prop('disabled', false);
                if(response.data.success != 'Y'){
                    toastr.error(response.data.message);
                    return true;
                }
                toastr.success(response.data.message);
                $('#formDestriyFollow').replaceWith(response.data.html);
                $('#followTo').text(response.data.follow_count);
            })
            .catch(function (error) {
                $button.prop('disabled', false);
                if(error.response){
                    var error_text = '';
                    $.each(error.response.data.errors, function (index, val) {
                        error_text += val[0] + '<br>';
                    });
                    toastr.error(error.response.data.message + '<br>' + error_text);
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });
    });
});