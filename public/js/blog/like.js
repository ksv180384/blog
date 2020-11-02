$(document).ready(function(){

    $('body').on('click', '.js-like', function (e) {
        e.preventDefault();

        const thisEl = $(this);
        const url = $(this).attr('href');

        axios({
            method: 'post',
            url: url,
        })
            .then(function (response) {

                if(response.data.success != "Y"){
                    toastr.error(response.data.message);
                    return true;
                }
                thisEl.attr('href', response.data.href);
                if(response.data.type == 'add'){
                    thisEl.addClass('text-success');
                }else{
                    thisEl.removeClass('text-success');
                }
                thisEl.find('.js-like-count-el').text(response.data.count);
            })
            .catch(function (error) {

                if(error.response){
                    let error_text = '';
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