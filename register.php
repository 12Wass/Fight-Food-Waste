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
if (isset($_GET['c']) && isset($_GET['e'])){
    $database =Database::getDatabaseConnection();
    $getAccount = $database->prepare('SELECT email, code FROM users WHERE email = ? AND code = ?');
    $getAccount->execute(array($_GET['e'], $_GET['c']));
    $account = $getAccount->fetchAll(PDO::FETCH_ASSOC);

    if ($account != NULL) {
        echo 'Compte validé!';
        $validateAccount = $database->prepare('UPDATE users SET code = "V", role = 1  WHERE email = ?');
        $validateAccount->execute(array($_GET['e']));
        exit();
    }
    else {
        echo 'L\'adresse mail envoyée n\'existe pas dans notre base de données.';
        exit();
    }
 }
?>

    <html>
    <head>
        <title>Inscription - Fight Food Waste</title>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/register.css">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>
    </head>
    <body style="text-align:center">
      <?php require_once("php/templates/Navbar.php"); ?>
    <!-- firstName lastName birthday email password phone address postalCode city regDate lastConnection-->
    <div class="uk-align-center">
           <div class="uk-background-secondary uk-light uk-padding uk-panel">
               <p class="uk-h1">Inscription</p>
           </div>
       </div>

        <form method="POST" onsubmit="return checkRegistration(this);">
          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: user"></span>
                  <input class="uk-input" type="text" id="reg_firstName" name="reg_firstName" placeholder="Prénom" autocomplete="name">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: user"></span>
                  <input class="uk-input" type="text" id="reg_lastName" name="reg_lastName" placeholder="Nom" autocomplete="name">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: home"></span>
                  <input class="uk-input" type="text" id="reg_address" name="reg_address" placeholder="Adresse" autocomplete="address-level1">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: home"></span>
                  <input class="uk-input" type="text" id="reg_city" name="reg_city" placeholder="Ville" autocomplete="address-level1">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: home"></span>
                  <input class="uk-input" type="text" id="reg_postalCode" name="reg_postalCode" placeholder="Code Postal" autocomplete="address-level1">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: mail"></span>
                  <input class="uk-input" type="email" id="reg_email" name="reg_email" placeholder="Adresse Mail" autocomplete="email">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: receiver"></span>
                  <input class="uk-input" type="text" id="reg_phone" name="reg_phone" placeholder="N° de téléphone" autocomplete="mobile">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: user"></span>
                  <input class="uk-input" type="date" id="reg_birthday" name="reg_birthday" placeholder="Date de naissance" autocomplete="birthday">
              </div>
          </div>

          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: lock"></span>
                  <input class="uk-input" type="password" id="reg_password" name="reg_password" placeholder="Mot de passe" autocomplete="password">
              </div>

          </div>
          <div class="uk-margin">
              <div class="uk-inline">
                  <span class="uk-form-icon" uk-icon="icon: lock"></span>
                  <input class="uk-input" type="password" id="reg_verifPassword" name="reg_verifPassword" placeholder="Confirmation" autocomplete="password">
              </div>
          </div>
        <input type="submit" value="Envoyer">
    </form>
    </body>

    <script src="js/users.js">
    </script>
    </html>

<?php
if (isset($_POST['reg_password']) && $_POST['reg_verifPassword'] != NULL){
    $user = new User($_POST['reg_firstName'],
        $_POST['reg_lastName'],
        $_POST['reg_address'],
        $_POST['reg_city'],
        $_POST['reg_postalCode'],
        $_POST['reg_phone'],
        $_POST['reg_password'],
        $_POST['reg_birthday'],
        $_POST['reg_email'],
        NULL
    );
    $user->registerUser();
}
