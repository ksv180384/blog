$(document).ready(function(){

    const body = $('body');

    body.on('click', '.js-like', function (e) {
        e.preventDefault();

        const btn = $(this);
        const url = $(this).attr('href');

        axios({
            method: 'post',
            url: url,
        })
            .then(function (response) {

                if(response.data.type == 'add'){
                    btn.closest('.btn-like').addClass('like-active');
                }else{
                    btn.closest('.btn-like').removeClass('like-active');
                }
                btn.closest('.btn-like').find('.js-like-count-el').text(response.data.count);
            })
            .catch(function (error) {
                if(error.response){
                    toastr.error(errorsToString(error.response.data));
                    return true;
                }

                toastr.error('Ошибка при сохранении.');
            });

    });
});