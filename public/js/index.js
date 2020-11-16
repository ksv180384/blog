function errorsToString(data) {
    let error_text = '';
    $.each(data.errors, function (index, val) {
        error_text += val[0];
    });
    return data.message + ' ' + error_text;
}