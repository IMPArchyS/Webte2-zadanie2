$(function () {
    // print the url
    let courses = [];
    $.ajax({
        url: 'apiCourses.php/courses',
        type: 'GET',
        dataType: 'json',
        beforeSend: function (xhr) {
            xhr.overrideMimeType('text/plain; charset=utf-8');
        },
        success: function (data) {
            courses = data;
            // loop over the courses using forEach
            courses[0].forEach(function (course) {
                let tr1 = document.getElementById(course.den + '-' + course.cas_od.substring(0, 5) + '-' + course.cas_od.substring(0, 2) + ':50');
                let tr2 = document.getElementById(course.den + '-' + course.cas_do.substring(0, 2) + ':00' + '-' + course.cas_do.substring(0, 5));
                console.log(tr1);
                console.log(tr2);
            });
        },
        error: function (error) {
            console.log(error);
        },
    });

    let deleteForm = $('#deleteForm');
    let deleteButton = $('#deleteButton');
    let addForm = $('#addForm');
    let addButton = $('#addButton');

    deleteButton.click(function () {
        if (deleteForm.hasClass('d-none')) {
            deleteButton.addClass('d-none');
            deleteForm.removeClass('d-none');
        }
        if (!addForm.hasClass('d-none')) {
            addForm.addClass('d-none');
            addButton.removeClass('d-none');
        }
    });

    addButton.click(function () {
        if (addForm.hasClass('d-none')) {
            addForm.removeClass('d-none');
            addButton.addClass('d-none');
        }
        if (!deleteForm.hasClass('d-none')) {
            deleteForm.addClass('d-none');
            deleteButton.removeClass('d-none');
        }
    });
});
