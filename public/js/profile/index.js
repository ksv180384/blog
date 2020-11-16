$(document).ready(function(){

    // Сохраняет данные пользователя
    $('body').on('submit', '#formAddUserData', function(e){
        e.preventDefault();

        var btn = $('#btnSaveUserData');
        var $form = $(this);

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
    $('body').on('change', '#inputUserAvatar', function(e){
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