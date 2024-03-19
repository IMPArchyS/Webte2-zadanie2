<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Prihlásenie</title>
</head>
<body>
    <?php 
        include_once "header.php";
    ?>

    <div class="container impContainer text-center">
        <h1 class="impFontH">Prihlásenie</h1>
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

    echo "<p>" . $response . "</p>";
}
    include_once "footer.php";
?>
</body>
<script src="../js/loginLogic.js"></script>
</html>
