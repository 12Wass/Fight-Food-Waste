<?php
require_once("php/classes/classIncluder.php");
if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] == true){
?>



<!DOCTYPE html>
<html>
<head>
    <title>Accueil - Fight Food Waste</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/uikit.min.css" />
    <script src="js/uikit.min.js"></script>
    <script src="js/uikit-icons.min.js"></script>
</head>
<body>
<?php require_once("php/templates/Navbar.php"); ?>
<h1 class="uk-heading-divider uk-text-center"><span>Bienvenue sur Fight Food Waste!</span></h1>

    <h2 class="uk-text-center">Que voulez-vous faire, <?php echo $_SESSION['user']->getFirstName(); ?> ?</h2>
    <div class="uk-child-width-1-2@s uk-text-center" uk-grid>
     <a href="profil.php">
        <div>
            <div class="uk-background-primary uk-light uk-padding uk-panel">
                <p class="uk-h4">Profil</p>
            </div>
        </div>
    </a>
    <a href="collects.php">
        <div>
            <div class="uk-background-primary uk-light uk-padding uk-panel">
                <p class="uk-h4">Mes collectes</p>
            </div>
        </div>
    </a>
    <a href="mesdemandes.php">
        <div>
            <div class="uk-background-primary uk-light uk-padding uk-panel">
                <p class="uk-h4">Gestion des services</p>
            </div>
        </div>
    </a>
    <a href="followLists.php">
        <div>
            <div class="uk-background-primary uk-light uk-padding uk-panel">
                <p class="uk-h4">Suivi</p>
            </div>
        </div>
    </a>
    </div>
</body>
    </html>

<?php }
    else
        {
?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Accueil - Fight Food Waste</title>
                <meta charset="utf-8" />
                <link rel="stylesheet" href="css/uikit.min.css" />
                <script src="js/uikit.min.js"></script>
                <script src="js/uikit-icons.min.js"></script>
            </head>
            <body>
            <?php require_once("php/templates/Navbar.php"); ?>
            <h1 class="uk-heading-divider uk-text-center"><span>Bienvenue sur Fight Food Waste!</span></h1>
            <div class="uk-child-width-1-2@s uk-text-center" uk-grid>
                <a href="connection.php">
                    <div>
                        <div class="uk-background-primary uk-light uk-padding uk-panel">
                            <p class="uk-h4">Se connecter</p>
                        </div>
                    </div>
                </a>
                <a href="register.php">
                    <div>
                        <div class="uk-background-primary uk-light uk-padding uk-panel">
                            <p class="uk-h4">S'inscrire</p>
                        </div>
                    </div>
                </a>
            </div>

            </body>
</html>

<?php } ?>