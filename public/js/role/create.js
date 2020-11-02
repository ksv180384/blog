$(document).ready(function () {

    // Сохраняет данные роли
    $('body').on('click', '#btnFormRoleStore', function(e){
        e.preventDefault();

        var thisBtn = $(this);

        var $form = $('#formRoleStore');

        thisBtn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                if(response.data.success == 'N'){
                    thisBtn.prop('disabled', false);
                    toastr.error(response.data.message);
                    return true;
                }
                location.href = response.data.redirect;
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
});