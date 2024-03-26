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
            if (courses.length === 0) return;
            // loop over the courses using forEach
            courses[0].forEach(function (course) {
                let td1 = document.getElementById(course.den + '-' + course.cas_od.substring(0, 5) + '-' + course.cas_od.substring(0, 2) + ':50');
                let td2 = document.getElementById(course.den + '-' + course.cas_do.substring(0, 2) + ':00' + '-' + course.cas_do.substring(0, 5));
                td1.innerHTML = '<div>' + course.miestnost + '<br>' + course.nazov_akcie + '<br>' + course.vyucujuci + '</div>';
                td1.innerHTML +=
                    '<button id="' +
                    course.id +
                    'editID" class="impEditButton btn btn-primary mx-1 px-2 py-1">EDIT</button> <button id="' +
                    course.id + // Added quotes around course.id
                    'DelID" class="impDeleteButton btn btn-primary mx-1 px-2 py-1">X</button>';
                if (course.typ_akcie === 'Prednáška') td1.className = 'impCourse';
                else td1.className = 'impLecture';
                if (td2 !== null) {
                    td1.colSpan = 2;
                    td2.parentNode.removeChild(td2);
                }

                $('#' + course.id + 'DelID').on('click', function (event) {
                    event.preventDefault();
                    deleteCourse(course.id); // Added courseId parameter
                });
                $('#' + course.id + 'editID').on('click', function (event) {
                    event.preventDefault();
                    addForm.removeClass('d-none');
                    addButton.addClass('d-none');
                    $('#courseId').val(course.id);
                    $('#day').val(course.den);
                    $('#timeFrom').val(course.cas_od);
                    $('#timeTo').val(course.cas_do);
                    $('#type').val(course.typ_akcie);
                    $('#name').val(course.nazov_akcie);
                    $('#room').val(course.miestnost);
                    $('#teacher').val(course.vyucujuci);
                });
            });
        },
        error: function (error) {
            console.log(error);
        },
    });

    let addForm = $('#addForm');
    let addButton = $('#addButton');

    addButton.click(function () {
        if (addForm.hasClass('d-none')) {
            addForm.removeClass('d-none');
            addButton.addClass('d-none');
            $('#courseId').val('');
            $('#day').val('Po');
            $('#timeFrom').val('');
            $('#timeTo').val('');
            $('#type').val('Prednáška');
            $('#name').val('');
            $('#room').val('');
            $('#teacher').val('');
        }
    });

    function deleteCourse(courseId) {
        $.ajax({
            url: 'apiCourses.php/courses?id=' + courseId,
            method: 'DELETE',
            success: function (data) {
                location.reload();
            },
            error: function (error) {},
        });
    }

    addForm.submit(function (event) {
        let timeS = $('#timeFrom').val(); // e.g., "13:23"
        let hoursS = timeS.split(':')[0]; // split the string into hours and minutes, and take the hours
        let newTimeS = hoursS + ':00'; // append ":00" to the hours

        let timeE = $('#timeTo').val(); // e.g., "13:23"
        let hoursE = timeE.split(':')[0]; // split the string i nto hours and minutes, and take the hours
        let newTimeE = hoursE + ':50'; // append ":00" to the hours

        const postData = {
            den: $('#day').val(),
            cas_od: newTimeS,
            cas_do: newTimeE,
            typ_akcie: $('#type').val(),
            nazov_akcie: $('#name').val(),
            miestnost: $('#room').val(),
            vyucujuci: $('#teacher').val(),
        };

        event.preventDefault(); // Prevent default form submission
        let itemId = $('#courseId').val();
        if (isNaN(parseInt(itemId))) {
            $.ajax({
                url: 'apiCourses.php/courses',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(postData),
                success: function (data) {
                    location.reload();
                    // Handle success
                },
                error: function (error) {
                    // Handle error
                },
            });
        } else {
            $.ajax({
                url: 'apiCourses.php/courses?id=' + itemId,
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(postData),
                success: function (data) {
                    location.reload();
                    // Handle success
                },
                error: function (error) {
                    // Handle error
                },
            });
        }
    });
});
