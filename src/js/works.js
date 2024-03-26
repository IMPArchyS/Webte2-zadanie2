$(function () {
    // https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=642
    let ustavBox = $('#ustav');
    let typBox = $('#typProjektu');

    $.ajax({
        url: 'apiWorks.php/themes?ustav=' + ustavBox.val(),
        type: 'GET',
        dataType: 'json',
        beforeSend: function (xhr) {
            xhr.overrideMimeType('text/plain; charset=utf-8');
        },
        success: function (data) {
            console.log(data);
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
