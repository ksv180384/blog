$(document).ready(function(){

    // Сохраняет данные пользователя
    $('body').on('submit', '#formAddUserData', function(e){
        e.preventDefault();

        var thisBtn = $('#btnSaveUserData');
        var $form = $(this);

        thisBtn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                thisBtn.prop('disabled', false);
                toastr.success('Профиль успешно отредактирован.');
            })
            .catch(function (error) {
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

    // Сохраняет аватар пользователя
    $('body').on('change', '#inputUserAvatar', function(e){
        e.preventDefault();

        const $form = $('#formAddUserAvatar');
        let data = new FormData();
        if(!this.files[0]){
            return true;
        }
        data.append('userAvatar', this.files[0]);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: data
        })
            .then(function (response) {
                console.log(response.data.url);
                $('.js-user-avatar').css({'background-image': 'url('+ response.data.url +')'});

            })
            .catch(function (error) {
                toastr.error('Ошибка при загрузке файла.');
            });
    });

});