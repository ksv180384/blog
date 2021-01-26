$(document).ready(function () {

    const body = $('body');

    // Сохраняет данные роли
    body.on('submit', '#formRoleStore', function(e){
        e.preventDefault();

        const btn = $('#btnFormRoleStore');

        const $form = $(this);

        btn.prop('disabled', true);
        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                location.href = response.data.redirect;
                toastr.success(response.data.message);
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