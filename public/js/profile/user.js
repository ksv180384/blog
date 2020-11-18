$(document).ready(function(){

    // Отслеживаем посты пользователя
    $('body').on('submit', '#formAddFollow', function(e){
        e.preventDefault();

        const btn = $('#followBtn');
        const $form = $(this);

        thisBtn.prop('disabled', true);
        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                btn.prop('disabled', false);
                toastr.success(response.data.message);
                $('#formAddFollow').replaceWith(response.data.html);
                $('#followTo').text(response.data.follow_count);

            })
            .catch(function (error) {
                btn.prop('disabled', false);
                if(error.response){
                    toastr.error(errorsToString(error.response.data));
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

                toastr.success(response.data.message);
                $('#formDestriyFollow').replaceWith(response.data.html);
                $('#followTo').text(response.data.follow_count);
            })
            .catch(function (error) {
                btn.prop('disabled', false);
                if(error.response){
                    toastr.error(errorsToString(error.response.data));
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });
    });
});