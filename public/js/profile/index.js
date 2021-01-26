$(document).ready(function(){

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
    body.on('submit', '#formAddUserData', function(e){
        e.preventDefault();

        const btn = $('#btnSaveUserData');
        const $form = $(this);

        btn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                btn.prop('disabled', false);
                toastr.success('Профиль успешно отредактирован.');
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

    // Сохраняет аватар пользователя
    body.on('change', '#inputUserAvatar', function(e){
        e.preventDefault();

        const $form = $('#formAddUserAvatar');
        let data = new FormData();
        if(!this.files[0]){
            return true;
        }
        data.append('avatar', this.files[0]);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: data
        })
            .then(function (response) {
                $('.js-user-avatar').css({'background-image': 'url('+ response.data.url +')'});

            })
            .catch(function (error) {
                if(error.response){
                    toastr.error(errorsToString(error.response.data));
                    return true;
                }

                toastr.error('Ошибка при загрузке файла.');
            });
    });

});