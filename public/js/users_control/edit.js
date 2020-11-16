$(document).ready(function () {
    // Сохраняет данные пользователя
    $('body').on('click', '#btnUserUpdate', function(e){
        e.preventDefault();

        var btn = $(this);

        var $form = $('#formUserUpdate');

        btn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
        .then(function (response) {
            btn.prop('disabled', false);
            toastr.success(response.data.message);
        })
        .catch(error => {
            btn.prop('disabled', false);
            if(error.response){
                toastr.error(errorsToString(error.response.data));
                return true;
            }

            toastr.error('Ошибка при сохранении.');
        });
    });

    // меняет рольпользователя
    $('body').on('change', '#roleUser', function(e){
        e.preventDefault();

        var $form = $('#formControlUser');

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
        .then(function (response) {
            $('.js-role-name-value').text(response.data.role);
            toastr.success(response.data.message);
        })
        .catch(error => {
            if(error.response){
                toastr.error(errorsToString(error.response.data));
                return true;
            }

            toastr.error('Ошибка при сохранении.');
        });
    });

    // Меняем пароль пользователя
    $('body').on('click', '#btnChangePassword', function(e){
        e.preventDefault();

        var btn = $(this);

        var $form = $('#formChangePassword');

        btn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                btn.prop('disabled', false);
                $('#formChangePassword').find('input:not(:hidden)').val('');
                toastr.success(response.data.message);
            })
            .catch(error => {
                btn.prop('disabled', false);
                $('#formChangePassword').find('input[type="password"]').val('');
                if(error.response){
                    toastr.error(errorsToString(error.response.data));
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });
    });
});