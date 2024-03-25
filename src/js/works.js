$(function () {
    let ustavBox = $('#ustav');
    let typBox = $('#typProjektu');

    const postData = {
        ustav: ustavBox.val(),
        typ: typBox.val(),
    };

    $.ajax({
        url: 'apiWorks.php/themes',
        type: 'GET',
        contentType: 'application/json',
        data: JSON.stringify(postData),

        beforeSend: function (xhr) {
            xhr.overrideMimeType('text/plain; charset=utf-8');
        },
        success: function (data) {
            //
        },
        error: function (error) {
            console.log(error);
        },
    });

    ustavBox.on('change', function () {
        console.log('USTAV');
    });

    typBox.on('change', function () {
        console.log('TYP');
    });
});
