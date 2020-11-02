$(document).ready(function () {

    // Сохраняет данные роли
    $('body').on('click', '#btnFormRoleUpdate', function(e){
        e.preventDefault();

        var thisBtn = $(this);

        var $form = $('#formRoleUpdate');

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
});