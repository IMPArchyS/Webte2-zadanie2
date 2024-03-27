<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../node_modules/datatables.net/js/dataTables.min.js"></script>

    <title>Záverečné práce</title>
</head>
<body>
<?php 
    include_once "header.php";
?>
<div class="container impContainer text-center">
    <h1 class="impFontH">Témy záverečných prác</h1>
    <div class="form-group my-2 col-9 mx-auto">
        <div class="row">
            <div class="col">
                <label class="font-weight-bold impFontW fs-5" for="ustav">Ústav</label>
                <select class="form-control text-light impSelect" id="ustav" name="ustav">
                    <option value="642" selected>Ústav automobilovej mechatroniky</option>
                    <option value="548">Ústav elektroenergetiky a aplikovanej elektrotechniky</option>
                    <option value="549">Ústav elektroniky a fotoniky</option>
                    <option value="550">Ústav elektrotechniky</option>
                    <option value="816">Ústav informatiky a matematiky</option>
                    <option value="817">Ústav jadrového a fyzikálneho inžinierstva </option>
                    <option value="818">Ústav multimediálnych informačných a komunikačných technológií</option>
                    <option value="356">Ústav robotiky a kybernetiky</option>
                </select>
            </div>
            <div class="col">
                <label class="font-weight-bold impFontW fs-5" for="typProjektu">Typ projektu</label>
                <select class="form-control text-light impSelect" id="typProjektu" name="typProjektu">
                    <option value="BP" selected>Bakalársky</option>
                    <option value="DP">Diplomový</option>
                    <option value="DIZP">Dizertačný</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row my-4">
        <div class="col">
            <div class="form-group">
                <label class="font-weight-bold impFontW fs-5" for="menoAbsFilter">Názov/Abstrakt</label>
                <input type="text" class="form-control bg-dark text-light" id="menoAbsFilter" name="menoAbsFilter">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label class="font-weight-bold impFontW fs-5" for="veduciFilter">Vedúci</label>
                <input type="text" class="form-control bg-dark text-light" id="veduciFilter" name="veduciFilter">
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label class="font-weight-bold impFontW fs-5" for="programFilter">Program</label>
                <input type="text" class="form-control bg-dark text-light" id="programFilter" name="programFilter">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-striped responsive-table" id="tableWorks">
            <thead>
                <tr>
                    <th scope="col">Názov témy</th>
                    <th scope="col">Vedúci práce</th>
                    <th scope="col">Program</th>
                </tr>
            </thead>
            <tbody id="tableBodyWorks">
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="abstractModal" tabindex="-1" role="dialog" aria-labelledby="abstractModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title" id="abstractModalLabel">Abstrakt</h5>
                <!-- Close button positioned to the bottom right corner -->
            </div>
            <div class="modal-body">
                <p id="AbsContent">This is the body of the modal where you can display some text.</p>
            </div>
            <div class="modal-footer">
                <button id="closeButton" class="btn btn-primary impDeleteButton" data-dismiss="modal">X</button>
            </div>
        </div>
    </div>
</div>

<?php
    include_once "footer.php";
?>
<script src="../js/works.js"></script>
</body>
</html>