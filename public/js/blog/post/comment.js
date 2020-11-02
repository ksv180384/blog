$(document).ready(function(){

    // Отслеживаем посты пользователя
    $('body').on('submit', '#sendComment', function(e){
        e.preventDefault();

        const thisBtn = $('#sendCommentBtn');
        const $form = $(this);

        thisBtn.prop('disabled', true);
        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        })
            .then(function (response) {
                thisBtn.prop('disabled', false);
                if(response.data.success != 'Y'){
                    toastr.error(response.data.message);
                    return true;
                }
                $('#commentsList').append(response.data.html);
                $('#countComments').text(response.data.count_messages);
                $('.js-comment').val('');
                toastr.success(response.data.message);
            })
            .catch(function (error) {
                thisBtn.prop('disabled', false);
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