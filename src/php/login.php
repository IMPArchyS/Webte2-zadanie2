<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <title>Aktualizácia rozvrhu</title>
</head>
<body>
    <?php 
        include_once "header.php";
    ?>

    <div class="container impContainer text-center">
        <h1 class="impFontH">Prihlásenie do AISu</h1>
        <form method="POST" action="login.php" id="loginForm">
            
            <div class="form-group my-2 col-9 mx-auto">
                <label class="font-weight-bold impFontW fs-5" for="username">Prihlasovacie meno</label>
                <input type="text" class="form-control text-light impSelect" id="username" name="username">
            </div>
            <div class="form-group my-2 col-9 mx-auto">
                <label class="font-weight-bold impFontW fs-5" for="password">Heslo</label>
                <input type="password" class="form-control text-light impSelect" id="password" name="password">
            </div>
            <h5 id="passwordError" class="text-danger"></h5>
            <input type="hidden" name="_method" value="POST" />
            <button id="submitLoginButton" type="submit" class="impGreenButton my-3 btn btn-primary">Prihlásiť sa</button>
        </form>
        <form method="POST" action="login.php">
            <input type="hidden" name="_method" value="DELETE" />
            <button id="deleteDBbutton" type="submit" class="impRedButton my-3 btn btn-primary">Vymazať údaje</button>
            <h5 id="dbResponse" class="text-danger"></h5>
        </form>
    </div>
<script src="../js/notStuba.js"></script>

<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['_method'] == 'DELETE') {
    $pdo = $conn;
    $stmt = $pdo->prepare("DELETE FROM rozvrh");
    if ($stmt->execute()) {
        echo "<script>dbSuccess();</script>";
    } else {
        echo "<script>dbError();</script>";
    }
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postValues = array(
        "destination" => "/auth/?lang=sk",
        "credential_0" => $_POST["username"],
        "credential_1" => $_POST["password"],
        "login" => "Prihlásiť sa",
        "credential_cookie" => "1"
    );

    define('USER_AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

    define("COOKIE_FILE", "cookie.txt");
    define("LOGIN_FORM_URL", "https://is.stuba.sk/system/login.pl");
    define("LOGIN_ACTION_URL", "https://is.stuba.sk/auth/");

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, LOGIN_ACTION_URL);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));

    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($curl, CURLOPT_COOKIEJAR, COOKIE_FILE);
    curl_setopt($curl, CURLOPT_USERAGENT, USER_AGENT);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($curl, CURLOPT_REFERER, LOGIN_FORM_URL);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

    curl_exec($curl);

    if (curl_errno($curl))
        throw new Exception(curl_error($curl));

    curl_setopt($curl, CURLOPT_URL, "https://is.stuba.sk/auth/katalog/rozvrhy_view.pl");

    curl_setopt($curl, CURLOPT_POSTFIELDS, "?rozvrh_student_obec=1?zobraz=1;format=html;rozvrh_student=115069;zpet=../student/moje_studium.pl?_m=3110,lang=sk,studium=167690,obdobi=630;lang=sk");

    $response = curl_exec($curl);
    curl_close($curl);

    $dom = new DOMDocument;

    libxml_use_internal_errors(true);

    $dom->loadHTML($response);

    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    $elements = $xpath->query("//h1[contains(text(), 'Prihlásenie do systému')]");

    if ($elements->length > 0) {
        echo "<script>badInput();</script>";
        
    } else {
        echo "<script>addedToDb();</script>";
        echo "<script>goodInput();</script>";

        $table = $xpath->query('//table')->item(0);
    
        $tableHTML = $dom->saveHTML($table);
    
        $doc = new DOMDocument();
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        $doc->loadHTML(mb_convert_encoding($tableHTML, 'HTML-ENTITIES', 'UTF-8'));
        
        $xpath = new DOMXPath($doc);
        
        $classes = array();
        
        $rows = $xpath->query("//table/tbody/tr");
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
        
            $day = $cells[0]->nodeValue;

            $hourCounter = 8;
        
            for ($i = 1; $i < $cells->length; $i++) {
                $cell = $cells[$i];
                $classInfo = trim($cell->nodeValue);

                if (!empty($classInfo)) {
                    $type = '';
                    $classType = trim($cell->getAttribute('class'));
                    if ($classType === 'rozvrh-pred') {
                        $type = 'Prednáška';
                    } elseif ($classType === 'rozvrh-cvic') {
                        $type = 'Cvičenie';
                    }
        
                    $classRoomElement = $cell->getElementsByTagName('a')->item(0); 
                    $classRoom = trim($classRoomElement->nodeValue);
        
                    $classNameElement = $cell->getElementsByTagName('a')->item(1); 
                    $className = trim($classNameElement->nodeValue);
        
                    $classProfessorElement = $cell->getElementsByTagName('i')->item(0); 
                    $classProfessor = trim($classProfessorElement->nodeValue);
        
                    $startHour = $hourCounter;
                    $endHour = $startHour + 1;
                    
                    $startTime = sprintf("%02d:00", $startHour);
                    $endTime = sprintf("%02d:50", $endHour);
        
                    $classes[] = array(
                        'den' => $day,
                        'cas_od' => $startTime,
                        'cas_do' => $endTime,
                        'typ_akcie' => $type,
                        'miestnost' => $classRoom,
                        'nazov_akcie' => $className,
                        'vyucujuci' => $classProfessor
                    );
                                // Increment the hour counter by 2 for a class
                    $hourCounter += 2;
                } else {
                    $hourCounter += 5 / 60; // 5 minutes in hours
                }
            }
        }
        
        $pdo = new PDO("mysql:host={$dbconfig['hostname']};dbname={$dbconfig['dbname']}", $dbconfig['username'], $dbconfig['password']);

        foreach ($classes as $class) {
            $stmt = $pdo->prepare("SELECT * FROM rozvrh WHERE den = :day AND cas_od = :start_time AND cas_do = :end_time AND miestnost = :class_room AND nazov_akcie = :class_name AND vyucujuci = :class_professor AND typ_akcie = :type");
            $stmt->bindParam(':day', $class['den']);
            $stmt->bindParam(':start_time', $class['cas_od']);
            $stmt->bindParam(':end_time', $class['cas_do']);
            $stmt->bindParam(':class_room', $class['miestnost']);
            $stmt->bindParam(':class_name', $class['nazov_akcie']);
            $stmt->bindParam(':class_professor', $class['vyucujuci']);
            $stmt->bindParam(':type', $class['typ_akcie']);
            $stmt->execute();
    
            if ($stmt->rowCount() == 0) {
                $insertStmt = $pdo->prepare("INSERT INTO rozvrh (den, cas_od, cas_do, miestnost, nazov_akcie, vyucujuci, typ_akcie) VALUES (:day, :start_time, :end_time, :class_room, :class_name, :class_professor, :type)");
                $insertStmt->bindParam(':day', $class['den']);
                $insertStmt->bindParam(':start_time', $class['cas_od']);
                $insertStmt->bindParam(':end_time', $class['cas_do']);
                $insertStmt->bindParam(':class_room', $class['miestnost']);
                $insertStmt->bindParam(':class_name', $class['nazov_akcie']);
                $insertStmt->bindParam(':class_professor', $class['vyucujuci']);
                $insertStmt->bindParam(':type', $class['typ_akcie']);
                $insertStmt->execute();
            }
        }
    }
    /*
    foreach ($classes as $class) {
        echo "<div class='text-white'>";
        echo "Day: " . $class['den'] . "<br>";
        echo "Start Time: " . $class['cas_od'] . "<br>";
        echo "End Time: " . $class['cas_do'] . "<br>";
        echo "Type: " . $class['typ_akcie'] . "<br>";
        echo "Class Room: " . $class['miestnost'] . "<br>";
        echo "Class Name: " . $class['nazov_akcie'] . "<br>";
        echo "Class Professor: " . $class['vyucujuci'] . "<br>";
        echo "-----------------------<br>";
        echo "</div>";
    }
    */
}
    include_once "footer.php";
?>
</body>
</html>
