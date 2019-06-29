<?php
/**
 * Created by PhpStorm.
 * User: wassimdahmane
 * Date: 05/05/2019
 * Time: 02:49
 * Description : Page de gestion des stocks accessible uniquement aux administrateurs
 */

require_once("php/classes/classIncluder.php");

$database = Database::getDatabaseConnection();
if (isset($_GET['sid']) && !empty($_GET['sid'])){
    $getService = $database->prepare("SELECT * FROM userService WHERE id = ? ");
    $getService->execute(array($_GET['sid']));
}
else {
    $getService = $database->prepare("SELECT * FROM userService where idUser = ? AND dateExpiration > CURDATE() ");
    $getService->execute(array($_SESSION['user']->getId()));

}
if (isset($_POST['newServiceTitle']) && isset($_POST['newServiceNeed']) && isset($_POST['newServiceSkillRequired'])){
    if (strlen($_POST['newServiceTitle']) > 5 && strlen($_POST['newServiceNeed']) > 5&& strlen($_POST['newServiceSkillRequired']) > 3) {
        $newService = new Service();
        $newService->setTitle($_POST['newServiceTitle']);
        $newService->setNeed($_POST['newServiceNeed']);
        $newService->setSkillRequired($_POST['newServiceSkillRequired']);
        $newService->insertService();
        header('Location: mesdemandes.php');
    }
    else {
        echo 'Les valeurs entrées ne sont pas correctes. Le titre et le besoin doivent dépasser 5 caractères et la compétence 3';
        header('Refresh: 3 url=mesdemandes.php');
    }

}
if (isset($_POST['deleteService'])){
    $deleteService = $database->prepare("DELETE FROM userService WHERE id = ?");
    $deleteService->execute(array($_POST['deleteService']));
    header('Location: mesdemandes.php');
}

?>

<html>
<head>
    <title>Mes demandes de service - Fight Food Waste</title>
    <meta charset="utf-8"/>
    <!-- Inclusion d'UIkit + Jquery -->
    <link rel="stylesheet" href="css/uikit.min.css">
    <script src="js/uikit.js"></script>
    <script src="js/jquery.js"></script>
    <style type="text/css">
        #textbox {padding:10px;border-radius:5px;border:0;box-shadow:0 0 4px 0 rgba(0,0,0,0.2)}
        .row img {width:40px;height:40px;border-radius:50%;vertical-align:middle;}
        .row {padding:10px;} .row:hover {background-color:#eee}
    </style>
</head>

<body>
<?php include_once("php/templates/Navbar.php");?>
<h1 style="text-align: center;" class="uk-align-center">Gestion de mes demandes</h1>

<!-- Algorithme de recherche -->

<table class="uk-table uk-table-striped uk-table-hover uk-table-large">
    <thead>
    <tr>
        <th>Titre</th>
        <th>Créateur</th>
        <th>Description</th>
        <th>Compétences</th>
        <th>Prestataire</th>
        <th>Etat</th>
        <th>Avis</th>
    </tr>
    </thead>
    <tbody id="rewrite">
<?php
// TODO : Faire un affichage en plusieurs page (afficher 20 max par exemple)
$counter = 0;
foreach($getService as $s){
    $currentService = new Service();
    insertValuesInService($currentService, $s);
    $creatorInfo = $currentService->getUserInfos($currentService->getIdUser());
    $workerInfo = $currentService->getUserInfos($currentService->getIdWorker());
    echo '<tr onclick="serviceEditForm(service'. $s['id'] .')" id="service'. $s['id'] .'" serviceId="'. $s["id"] .'">
                  <td>' . $currentService->getTitle() . '</td>
                  <td><a href="profil.php">' . $creatorInfo['firstName'] . ' ' . $creatorInfo['lastName'] . '</a></td>
                  <td>' . $currentService->getNeed() . '</td>
                  <td>' . $currentService->getSkillRequired() . '</td>
                  <td><a href="private/admin/users.php?uid='. $workerInfo['id']. '">' . $workerInfo['firstName'] . ' ' . $workerInfo['lastName'] . '</a></td>
                  <td><a href="followDemands.php?sid='. $currentService->getId() .'">' . $currentService->getIsDone() . '</a></td>
                  <td>' . $currentService->getStars() . '</td>

                  <td><button class="uk-button uk-button-danger" name="deleteService" type="submit" value="'. $s["id"] .'" form="deleteService">Supprimer</button></td>
                  </tr>';
    $counter++;
}
?>
    </tbody>
</table>

<div class="uk-child-width-1-2@s uk-text-center" uk-grid>
    <a href="services.php">
        <div>
            <div class="uk-background-primary uk-light uk-padding uk-panel">
                <p class="uk-h4">Voir toutes les annonces</p>
            </div>
        </div>
    </a>
    <a href="messervices.php">
        <div>
            <div class="uk-background-primary uk-light uk-padding uk-panel">
                <p class="uk-h4">Voir mes positionnements</p>
            </div>
        </div>
    </a>
</div>

<form method="POST" action="mesdemandes.php" id="deleteService"></form>

<div style="text-align: center;">
<h1>Ajouter une demande</h1>
<form method="POST" action="mesdemandes.php">
    <div class="uk-margin">
        <input class="uk-input uk-form-width-medium" type="text" placeholder="Titre du service" name="newServiceTitle">
    </div>
    <div class="uk-margin">
        <input class="uk-input uk-form-width-medium" type="text" placeholder="Besoin" name="newServiceNeed">
    </div>
    <div class="uk-margin">
        <input class="uk-input uk-form-width-medium" type="text" placeholder="Compétence requise" name="newServiceSkillRequired">
    </div>
    <div class="uk-margin">
        <input class="uk-input uk-form-width-medium" type="submit">
    </div>
</form>
</div>



<script src="js/lists.js"></script>

</body>
</html>

