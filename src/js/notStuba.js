function badInput() {
    $('#passwordError').text('Nie si študentom STU');
}

function goodInput() {
    $('#passwordError').text('');
}

function addedToDb() {
    $('#dbResponse').text('Údaje pridané');
    $('#dbResponse').removeClass('text-danger');
    $('#dbResponse').addClass('text-success');
}

$('#username').on('blur', function () {
    goodInput();
    dbResponseHide();
});

$('#password').on('blur', function () {
    goodInput();
    dbResponseHide();
});

function dbError() {
    $('#dbResponse').text('Nastala chyba');
    $('#dbResponse').removeClass('text-success');
    $('#dbResponse').addClass('text-danger');
}

function dbSuccess() {
    $('#dbResponse').text('Údaje odstránené');
    $('#dbResponse').removeClass('text-danger');
    $('#dbResponse').addClass('text-success');
}
function dbResponseHide() {
    $('#dbResponse').text('');
}
