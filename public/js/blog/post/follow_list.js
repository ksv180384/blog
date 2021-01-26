$(document).ready(function(){

    const body = $('body');

    body.on('submit', '.js-form-follow-remove', function(e){
        e.preventDefault();

        const $form = $(this);
        const $button = $form.find('button.btn');

        $button.prop('disabled', true);
        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                $button.prop('disabled', false);
                toastr.success(response.data.message);
                location.reload();
            })
            .catch(function (error) {
                $button.prop('disabled', false);
                if(error.response){
                    var error_text = '';
                    $.each(error.response.data.errors, function (index, val) {
                        error_text += val[0] + '<br>';
                    });
                    toastr.error(error.response.data.message + '<br>' + error_text);
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });
    });
});