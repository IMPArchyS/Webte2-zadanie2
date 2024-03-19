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
        include_once "footer.php";
    ?>
</body>
<script src="../js/loginLogic.js"></script>
</html>
