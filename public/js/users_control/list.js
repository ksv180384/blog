$(document).ready(function (e) {
    // Удаляем пользователя
    $('body').on('click', '.js-delete-user', function(e){
        e.preventDefault();

        const thisEl = $(this);
        const thisBlock = thisEl.closest('tr');

        axios({
            method: 'DELETE',
            url: thisEl.attr('href'),
            data: {id: thisEl.data('id')}
        })
            .then(function (response) {
                $('.js-role-name-value').text(response.data.role);
                thisBlock.remove();
                toastr.success(response.data.message);
            })
            .catch(function (error) {
                if(error.response){
                    toastr.error(errorsToString(error.response.data));
                    return true;
                }
                toastr.error('Ошибка при удалении.');
            });
    });
});