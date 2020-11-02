$(document).ready(function () {
    // Сохраняет данные роли
    $('body').on('click', '#btnUserUpdate', function(e){
        e.preventDefault();

        var thisBtn = $(this);

        var $form = $('#formUserUpdate');

        thisBtn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
        .then(function (response) {
            thisBtn.prop('disabled', false);
            if(response.data.success == 'N'){
                toastr.error(response.data.message);
                return true;
            }
            toastr.success(response.data.message);
        })
        .catch(error => {
            thisBtn.prop('disabled', false);
            if(error.response){
                var error_text = '';
                $.each(error.response.data.errors, function (index, val) {
                    error_text += val[0];
                });
                toastr.error(error.response.data.message + ' ' + error_text);
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
            if(response.data.success == 'N'){
                toastr.error(response.data.message);
                return true;
            }
            $('.js-role-name-value').text(response.data.role);
            toastr.success(response.data.message);
        })
        .catch(function (error) {
            toastr.error(error.message);
        });
    });

    // Меняем пароль пользователя
    $('body').on('click', '#btnChangePassword', function(e){
        e.preventDefault();

        var thisBtn = $(this);

        var $form = $('#formChangePassword');

        thisBtn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                thisBtn.prop('disabled', false);
                $('#formChangePassword').find('input').val('');
                if(response.data.success == 'N'){
                    toastr.error(response.data.message);
                    return true;
                }
                toastr.success(response.data.message);
            })
            .catch(error => {
                thisBtn.prop('disabled', false);
                $('#formChangePassword').find('input[type="password"]').val('');
                if(error.response){
                    var error_text = '';
                    $.each(error.response.data.errors, function (index, val) {
                        error_text += val[0];
                    });
                    toastr.error(error.response.data.message + ' ' + error_text);
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });
    });
});