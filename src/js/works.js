$(function () {
    // https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=642
    let ustavBox = $('#ustav');
    let typBox = $('#typProjektu');
    let tableBody = $('#tableBodyWorks');
    let thesisData;
    let prevReq = null;
    let abstractModal = $('#abstractModal');
    let absContent = $('#AbsContent');

    function getByUstavType() {
        if (prevReq !== null) {
            prevReq.abort();
        }
        tableBody.empty();
        console.log(ustavBox.val());
        console.log(typBox.val());
        console.log('=== FETCHING ===');
        prevReq = $.ajax({
            url: 'apiWorks.php/themes?ustav=' + ustavBox.val() + '&typ=' + typBox.val(),
            type: 'GET',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.overrideMimeType('text/plain; charset=utf-8');
            },
            success: function (data) {
                console.log(data);
                thesisData = data;
                fillTable(thesisData);
                window.thesisData = thesisData;
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    ustavBox.on('change', function () {
        getByUstavType();
    });

    typBox.on('change', function () {
        getByUstavType();
    });

    $('#closeButton').on('click', function () {
        abstractModal.modal('hide');
    });

    function fillTable(tableData) {
        for (let i = 0; i < tableData.length; i++) {
            let row = $('<tr>');

            let cell = $('<td>').text(tableData[i].nazov_temy);
            cell = $('<td>');
            let text = $('<span>').text(tableData[i].nazov_temy);
            text.click(function () {
                absContent.text(tableData[i].abstrakt);
                abstractModal.modal('show');
            });
            text.css('cursor', 'pointer'); // Add this line to change the cursor on hover
            cell.append(text);
            row.append(cell);

            cell = $('<td>').text(tableData[i].veduci);
            row.append(cell);

            cell = $('<td>').text(tableData[i].program);
            row.append(cell);

            // Append the row to the table body
            tableBody.append(row);
        }
    }
    getByUstavType();
});
