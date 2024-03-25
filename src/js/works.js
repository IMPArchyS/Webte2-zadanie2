$(function () {
    var ustavBox = $('#ustav').val();
    var typBox = $('#typProjektu').val();

    $.ajax({
        url: 'apiWorks.php/themes',
        type: 'GET',
        dataType: 'json',
        beforeSend: function (xhr) {
            xhr.overrideMimeType('text/plain; charset=utf-8');
        },
        success: function (data) {
            // TODO
        },
        error: function (error) {
            console.log(error);
        },
    });
});
