<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
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
                <option value="Bakalársky" selected>Bakalársky</option>
                <option value="Diplomový">Diplomový</option>
                <option value="Dizeratčný">Dizeratčný</option>
            </select>
        </div>
    </div>
</div>
<div class="table-responsive">
        <table class="table table-dark table-striped responsive-table" id="timetableMain">
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

<?php
    include_once "footer.php";
?>
<script src="../js/works.js"></script>
</body>
</html>