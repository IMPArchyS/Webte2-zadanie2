function badInput() {
    $('#passwordError').text('Nie si študentom STU');
}

function goodInput() {
    $('#passwordError').text('');
}

$('#username').on('blur', function () {
    goodInput();
});

$('#password').on('blur', function () {
    goodInput();
});
