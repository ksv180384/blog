$(document).ready(function () {

    const body = $('body');

    datepicker('#inputBirthday', {
        startDay: 1,
        customDays: ['пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'],
        customMonths: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        overlayButton: 'Выбрать',
        overlayPlaceholder: 'Введите год',
        formatter: (input, date, instance) => {
            const value = date.toLocaleDateString('ru-RU');
            input.value = value // => '1/1/2099'
        }
    });

    // Сохраняет данные пользователя
    body.on('click', '#btnUserUpdate', function(e){
        e.preventDefault();

        const btn = $(this);
        const $form = $('#formUserUpdate');

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
    body.on('change', '#roleUser', function(e){
        e.preventDefault();

        const $form = $('#formControlUser');

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
    body.on('click', '#btnChangePassword', function(e){
        e.preventDefault();

        const btn = $(this);
        const $form = $('#formChangePassword');

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