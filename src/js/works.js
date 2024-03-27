$(function () {
    // https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=642
    let ustavBox = $('#ustav');
    let typBox = $('#typProjektu');
    let tableBody = $('#tableBodyWorks');
    let thesisData;
    let prevReq = null;
    let abstractModal = $('#abstractModal');
    let absContent = $('#AbsContent');
    const table = $('#tableWorks');
    tableBody.append($('<tr>').append($('<td colspan="3">').text('Údaje sa načítavajú')));

    function getByUstavType() {
        if (prevReq !== null) {
            prevReq.abort();
        }
        tableBody.empty();
        console.log(ustavBox.val());
        console.log(typBox.val());
        console.log('=== FETCHING ===');
        tableBody.append($('<tr>').append($('<td colspan="3">').text('Údaje sa načítavajú')));
        thesisData = [];
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
                tableBody.empty();
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
        table.DataTable().destroy();
        tableBody.empty(); // Clear the table body before filling with new data

        for (let i = 0; i < tableData.length; i++) {
            let row = $('<tr>');

            let cell = $('<td>').text(tableData[i].nazov_temy);
            cell = $('<td>');
            let text = $('<span>').text(tableData[i].nazov_temy);
            let hiddenAbs = $('<p class="d-none">').text(tableData[i].abstrakt);
            text.click(function () {
                absContent.text(tableData[i].abstrakt);
                abstractModal.modal('show');
            });
            text.css('cursor', 'pointer'); // Add this line to change the cursor on hover
            cell.append(text);
            cell.append(hiddenAbs);
            row.append(cell);

            cell = $('<td>').text(tableData[i].veduci);
            row.append(cell);

            cell = $('<td>').text(tableData[i].program);
            row.append(cell);

            // Append the row to the table body
            tableBody.append(row);
        }
        applyDataTables();
    }

    function applyDataTables() {
        table.DataTable({
            order: [],
            paging: false,
            lengthChange: false,
            responsive: true,
        });
        $('#menoAbsFilter').on('keyup', function () {
            table.DataTable().column(0).search(this.value).draw();
        });

        $('#veduciFilter').on('keyup', function () {
            table.DataTable().column(1).search(this.value).draw();
        });

        $('#programFilter').on('keyup', function () {
            table.DataTable().column(2).search(this.value).draw();
        });

        table.removeAttr('style');
        $('#tableWorks_wrapper').css('overflow', 'hidden');
    }
    getByUstavType();
});
