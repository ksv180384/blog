$(document).ready(function(){

    const body = $('body');

    $('.select2').select2({
        theme: 'bootstrap4'
    });

    $('.wysiwyg').trumbowyg({
        imageWidthModalEdit: true,
        lang: 'ru',
    });

    // Подставляет катринку
    body.on('change', '#imgPost', function(e){
        e.preventDefault();

        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function(theFile) {
            const image = new Image();
            image.onload = function() {
                //console.log(this.width);
            };
            $('.js-post-img').css({'background-image': 'url('+ theFile.target.result +')'});
        };
        reader.readAsDataURL(file);

    });

    // Добавляет новый пост
    body.on('submit', '#formPostUpdate', function(e){
        e.preventDefault();

        const thisBtn = $('#btnAddPost');
        const $form = $(this);
        const formData = new FormData($form.get(0));

        thisBtn.prop('disabled', true);

        axios({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: formData
        })
            .then(function (response) {
                thisBtn.prop('disabled', false);
                toastr.success(response.data.message);
                //location.href = response.data.redirect
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