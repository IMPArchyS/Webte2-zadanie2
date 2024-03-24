<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Rozvrh</title>
</head>
<body>
<?php 
    include_once "header.php";
?>
<div class="container impContainer text-center">
    <h1 class="impFontH">Rozvrh</h1>
    <div class="table-responsive">
        <table class="table table-dark table-striped responsive-table" id="timetableMain">
            <thead>
                <tr>
                    <?php
                    echo "<th scope=\"col\">Deň</th>";
                    for ($hour = 7; $hour <= 19; $hour++) {
                        for ($minute = 0; $minute <= 50; $minute += 100) {
                            $time = sprintf("%02d:%02d-%02d:%02d", $hour, $minute, $hour, $minute+50);
                            echo "<th scope=\"col\">$time</th>";
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tbody id="timetableBody">
                <?php
                $days = ['Po', 'Ut', 'St', 'Št', 'Pi'];
                for ($day = 0; $day < 5; $day++) {
                    echo "<tr>";
                    echo "<th scope=\"row\">$days[$day]</th>";
                    for ($hour = 7; $hour <= 19; $hour++) {
                        for ($minute = 0; $minute <= 50; $minute += 100) {
                            $time = sprintf("%02d:%02d-%02d:%02d", $hour, $minute, $hour, $minute+50);
                            echo "<td id=\"$days[$day]-$time\"></td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <button id="deleteButton" class="impRedButton my-3 btn btn-primary">Vymazať akciu</button>
    <button id="addButton" class="impGreenButton my-3 btn btn-primary">Pridať/aktualizovať akciu</button>
    <form id="deleteForm" class="d-none" method="delete">
        <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="deleteId">ID</label>
            <input type="text" class="form-control text-light impSelect" id="deleteId" name="deleteId">
        </div>
        <button id="submitDeleteButton" type="submit" class="impButton my-3 btn btn-primary">Potvrdiť</button>
    </form>
    <form id="addForm" class="d-none">
    <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="courseId">ID</label>
            <input type="number" class="form-control text-light impSelect" id="courseId" name="courseId">
        </div>
        <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="day">Deň</label>
            <input type="text" class="form-control text-light impSelect" id="day" name="day">
        </div>
        <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="timeFrom">Čas od</label>
            <input type="text" class="form-control text-light impSelect" id="timeFrom" name="timeFrom">
        </div>
        <div class="form-group
        my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="timeTo">Čas do</label>
            <input type="text" class="form-control text-light impSelect" id="timeTo" name="timeTo">
        </div>
        <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="type">Typ akcie</label>
            <input type="text" class="form-control text-light impSelect" id="type" name="type">
        </div>
        <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="name">Názov akcie</label>
            <input type="text" class="form-control text-light impSelect" id="name" name="name">
        </div>
        <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="room">Miestnosť</label>
            <input type="text" class="form-control text-light impSelect" id="room" name="room">
        </div>
        <div class="form-group my-2 col-9 mx-auto">
            <label class="font-weight-bold impFontW fs-5" for="teacher">Vyučujúci</label>
            <input type="text" class="form-control text-light impSelect" id="teacher" name="teacher">
        </div>
        <p class="font-weight-bold text-white fs-5">Ak je pole ID prázdne nastane vytvorenie novej rozvrhovej akcie</p>
        <button id="submitAddButton" type="submit" class="impButton my-3 btn btn-primary">Potvrdiť</button>
    </form>

</div>

<?php
    include_once "footer.php";
?>
<script src="../js/timetable.js"></script>
</body>
</html>