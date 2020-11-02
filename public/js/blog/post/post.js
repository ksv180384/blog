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
                if(response.data.success != 'Y'){
                    toastr.error(response.data.message);
                    return true;
                }

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