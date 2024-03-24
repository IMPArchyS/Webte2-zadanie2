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
                let td1 = document.getElementById(course.den + '-' + course.cas_od.substring(0, 5) + '-' + course.cas_od.substring(0, 2) + ':50');
                let td2 = document.getElementById(course.den + '-' + course.cas_do.substring(0, 2) + ':00' + '-' + course.cas_do.substring(0, 5));
                td1.colSpan = 2;
                td1.innerHTML = 'id: ' + course.id + '<br>' + course.miestnost + '<br>' + course.nazov_akcie + '<br>' + course.vyucujuci;
                if (course.typ_akcie === 'Prednáška') td1.className = 'impCourse';
                else td1.className = 'impLecture';
                td2.parentNode.removeChild(td2);
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

    deleteForm.submit(function (event) {
        event.preventDefault(); // Prevent default form submission
        let itemId = $('#deleteId').val();
        $.ajax({
            url: 'apiCourses.php/courses?id=' + itemId, // Replace 'your_api_endpoint' with your actual endpoint
            method: 'DELETE',
            success: function (data) {
                location.reload();
            },
            error: function (error) {},
        });
    });

    addForm.submit(function (event) {
        const postData = {
            den: $('#day').val(),
            cas_od: $('#timeFrom').val(),
            cas_do: $('#timeTo').val(),
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
            console.log('SOM TU');
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
