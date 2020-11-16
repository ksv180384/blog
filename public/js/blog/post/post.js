$(document).ready(function(){

    // Публикуем пост
    $('body').on('click', '#published, #offPublished', function(e){
        e.preventDefault();

        const thisBtn = $(this);
        const url = thisBtn.data('url');

        thisBtn.prop('disabled', true);
        axios({
            method: 'post',
            url: url,
            data: []
        })
            .then(function (response) {
                thisBtn.prop('disabled', false);

                thisBtn.text(response.data.text)
                    .removeClass('btn-default')
                    .removeClass('btn-primary')
                    .addClass(response.data.classCss);
                $('#datePublished').html(response.data.date);
                toastr.success(response.data.message);
            })
            .catch(function (error) {
                thisBtn.prop('disabled', false);
                if(error.response){
                    toastr.error(errorsToString(error.response.data));
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });
    });
});