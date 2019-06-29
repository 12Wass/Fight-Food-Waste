<?php
/**
 * Created by PhpStorm.
 * User: wassimdahmane
 * Date: 03/04/2019
 * Time: 14:50
 */

include_once("php/classes/classIncluder.php");
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true){
    http_response_code(403);
    exit();
}
?>

    <html>
    <head>
        <title>Connexion - Fight Food Waste</title>
        <meta charset="utf-8">
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/uikit.min.css" />
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>
        <script src="js/users.js"></script>
    </head>
    <body style="text-align:center">
    <?php require_once("php/templates/Navbar.php"); ?>
    <h1 class="title">Connexion</h1>
    <form method="POST" onsubmit="return checkConnection(this);">
        <div class="uk-margin">
            <div class="uk-inline">
                <span class="uk-form-icon" uk-icon="icon: user"></span>
                <input class="uk-input" type="email" id="con_email" name="con_email" placeholder="Adresse mail" autocomplete="email">
            </div>
        </div>

        <div class="uk-margin">
            <div class="uk-inline">
                <span class="uk-form-icon" uk-icon="icon: lock"></span>
                <input class="uk-input" type="password" id="con_password" name="con_password" placeholder="*******" autocomplete="current-password">
            </div>
            <div class="uk-margin">
              <input type="submit" value="Envoyer" class="uk-button uk-button-default">
            </div>
        <div id="getAnswer"></div>
        </div>

    </form>
    </body>
    </html>

<?php
if (isset($_POST['con_password']) && $_POST['con_email']){
    $connUser = new User(
        NULL, NULL, NULL, NULL, NULL, NULL, $_POST['con_password'], NULL, $_POST['con_email'], NULL
    );
    $connUser->connectUser();
}
