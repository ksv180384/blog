$(document).ready(function () {

    // Создаем пользователя
    $('body').on('click', '#btnUserCreate', function(e){
        e.preventDefault();

        var btn = $(this);

        var $form = $('#formUserCreate');

        btn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                btn.prop('disabled', false);
                toastr.success(response.data.message);
                document.location.href = response.data.redirect;
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
});