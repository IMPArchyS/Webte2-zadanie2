<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
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
            <button id="submitLoginButton" type="submit" class="impGreenButton my-3 btn btn-primary">Prihlásiť sa</button>
        </form>
    </div>
<script src="../js/notStuba.js"></script>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Create a new DOMDocument instance
    $dom = new DOMDocument;

    // Suppress warnings due to invalid HTML
    libxml_use_internal_errors(true);

    // Load the HTML
    $dom->loadHTML($response);

    // Clear the errors
    libxml_clear_errors();

    // Create a new DOMXPath instance
    $xpath = new DOMXPath($dom);

    $elements = $xpath->query("//h1[contains(text(), 'Prihlásenie do systému')]");

    if ($elements->length > 0) {
        echo "<script>badInput();</script>";
    } else {
        echo "<script>goodInput();</script>";

        // Query the first table element
        $table = $xpath->query('//table')->item(0);
    
        // Save the table HTML to a variable
        $tableHTML = $dom->saveHTML($table);
    
        $doc = new DOMDocument();
        $doc->loadHTML(mb_convert_encoding($tableHTML, 'HTML-ENTITIES', 'UTF-8'));
        
        $xpath = new DOMXPath($doc);
        
        // Array to store class timings and courses
        $classes = array();
        
        // Iterate over each row
        $rows = $xpath->query("//table/tbody/tr");
        foreach ($rows as $row) {
            $cells = $row->getElementsByTagName('td');
        
            // Extract day
            $day = $cells[0]->nodeValue;
        
            // Iterate over cells starting from the second one
            for ($i = 1; $i < $cells->length; $i++) {
                $cell = $cells[$i];
                $classInfo = trim($cell->nodeValue);
        
                // Check if cell contains class information
                if (!empty($classInfo)) {
                    // Determine course type based on td class attribute
                    $type = '';
                    $classType = trim($cell->getAttribute('class'));
                    if ($classType === 'rozvrh-pred') {
                        $type = 'Prednáška';
                    } elseif ($classType === 'rozvrh-cvic') {
                        $type = 'Cvičenie';
                    }
        
                    // Split class info into room, name, and professor
                    $classRoomElement = $cell->getElementsByTagName('a')->item(0); // Room is the first <a> element
                    $classRoom = trim($classRoomElement->nodeValue);
        
                    $classNameElement = $cell->getElementsByTagName('a')->item(1); // Name is the second <a> element
                    $className = trim($classNameElement->nodeValue);
        
                    $classProfessorElement = $cell->getElementsByTagName('i')->item(0); // Professor is the <i> element
                    $classProfessor = trim($classProfessorElement->nodeValue);
        
                    // Calculate class timing based on column index
                    $startHour = 7 + (int)(($i - 1) / 12) + ((($i - 1) % 12) * 2) / 12;
                    $endHour = $startHour + 1;
        
                    // Adjust timing for breaks
                    if ($startHour >= 9) {
                        $startHour += 1;
                        $endHour += 1;
                    }
        
                    // Format class timing
                    $startTime = sprintf("%02d:00", $startHour);
                    $endTime = sprintf("%02d:50", $endHour);
        
                    // Store class information
                    $classes[] = array(
                        'day' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'type' => $type,
                        'class_room' => $classRoom,
                        'class_name' => $className,
                        'class_professor' => $classProfessor
                    );
                }
            }
        }
        
        
        // Establish a MySQL connection using PDO
        // Fetch the database credentials from the config file/
        
        require_once '../config.php';

        $pdo = new PDO("mysql:host={$dbconfig['hostname']};dbname={$dbconfig['dbname']}", $dbconfig['username'], $dbconfig['password']);

        foreach ($classes as $class) {
            // Check if the class already exists in the database
            $stmt = $pdo->prepare("SELECT * FROM Rozvrh WHERE den = :day AND cas_od = :start_time AND cas_do = :end_time AND miestnost = :class_room AND nazov_akcie = :class_name AND vyucujuci = :class_professor AND typ_akcie = :type");
            $stmt->bindParam(':day', $class['day']);
            $stmt->bindParam(':start_time', $class['start_time']);
            $stmt->bindParam(':end_time', $class['end_time']);
            $stmt->bindParam(':class_room', $class['class_room']);
            $stmt->bindParam(':class_name', $class['class_name']);
            $stmt->bindParam(':class_professor', $class['class_professor']);
            $stmt->bindParam(':type', $class['type']);
            $stmt->execute();
    
            // If the class does not exist, insert it into the database
            if ($stmt->rowCount() == 0) {
                $insertStmt = $pdo->prepare("INSERT INTO Rozvrh (den, cas_od, cas_do, miestnost, nazov_akcie, vyucujuci, typ_akcie) VALUES (:day, :start_time, :end_time, :class_room, :class_name, :class_professor, :type)");
                $insertStmt->bindParam(':day', $class['day']);
                $insertStmt->bindParam(':start_time', $class['start_time']);
                $insertStmt->bindParam(':end_time', $class['end_time']);
                $insertStmt->bindParam(':class_room', $class['class_room']);
                $insertStmt->bindParam(':class_name', $class['class_name']);
                $insertStmt->bindParam(':class_professor', $class['class_professor']);
                $insertStmt->bindParam(':type', $class['type']);
                $insertStmt->execute();
            }
        }
        // Echo class information into a div
        /*
        foreach ($classes as $class) {
            echo "<div class='text-white'>";
            echo "Day: " . $class['day'] . "<br>";
            echo "Start Time: " . $class['start_time'] . "<br>";
            echo "End Time: " . $class['end_time'] . "<br>";
            echo "Type: " . $class['type'] . "<br>";
            echo "Class Room: " . $class['class_room'] . "<br>";
            echo "Class Name: " . $class['class_name'] . "<br>";
            echo "Class Professor: " . $class['class_professor'] . "<br>";
            echo "</div>";
        }
        */
    }
}
    include_once "footer.php";
?>
</body>
</html>
