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
    $getService = $database->prepare("SELECT * FROM userService WHERE id = ? AND dateExpiration > CURDATE()");
    $getService->execute(array($_GET['sid']));
}
else {
    $getService = $database->prepare("SELECT * FROM userService WHERE dateExpiration > CURDATE() AND idUser <> ? AND ISNULL(idWorker)");
    $getService->execute(array($_SESSION['user']->getId()));

}
if (isset($_POST['newServiceTitle']) && isset($_POST['newServiceNeed']) && isset($_POST['newServiceSkillRequired'])){
    $newService = new Service();
    $newService->setTitle($_POST['newServiceTitle']);
    $newService->setNeed($_POST['newServiceNeed']);
    $newService->setSkillRequired($_POST['newServiceSkillRequired']);
    $newService->insertService();
    header('Location: services.php');
}
if (isset($_POST['propose'])){
    $proposeService = $database->prepare("INSERT INTO propose(idUser, idService, isAccepted) VALUES (?, ?, ?)");
    $proposeService->execute(array($_SESSION['user']->getId(), $_POST['propose'], 0));

    header('Location: services.php');
}
if (isset($_POST['unpropose'])){
    $removeProposition = $database->prepare("UPDATE userService SET idWorker = NULL WHERE id = ?");
    $removeProposition->execute(array($_POST['unpropose']));
    $unproposeService = $database->prepare("DELETE FROM propose WHERE idUser = ? AND idService = ?");
    $unproposeService->execute(array($_SESSION['user']->getId(), $_POST['unpropose']));
    header('Location: services.php');
}

function isUserPositioned($service){
    $database = Database::getDatabaseConnection();
    $checkPositions = $database->prepare("SELECT * FROM propose WHERE idUser = ? AND idService = ? ");
    $checkPositions->execute(array($_SESSION['user']->getId(), $service));
    $isPositioned = $checkPositions->fetch(PDO::FETCH_ASSOC);
    if ($isPositioned !== false){
        return true;
    }
    else {
        return false;
    }
}

?>

<html>
<head>
    <title>Annonces - Fight Food Waste</title>
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
<h1 style="text-align: center;" class="uk-align-center">Annonces</h1>

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
        <th>Action</th>
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
                  <td><a href="messagerie.php?uid='. $creatorInfo['id']. '">' . $creatorInfo['firstName'] . ' ' . $creatorInfo['lastName'] . '</a></td>
                  <td>' . $currentService->getNeed() . '</td>
                  <td>' . $currentService->getSkillRequired() . '</td>
                  <td><a href="private/admin/users.php?uid='. $workerInfo['id']. '">' . $workerInfo['firstName'] . ' ' . $workerInfo['lastName'] . '</a></td>
                  <td>' . $currentService->getIsDone() . '</td>
                  <td>' . $currentService->getStars() . '</td>
                  '. (isUserPositioned($s['id']) ? '<td><button class="uk-button uk-button-danger" name="unpropose" type="submit" value="'. $s["id"] .'" form="propose">Se désister</button></td>' :  '<td><button class="uk-button uk-button-primary" name="propose" type="submit" value="'. $s["id"] .'" form="propose">Se positionner</button></td>') .'
                  </tr>';
    $counter++;
}
?>
    </tbody>
</table>

<div class="uk-child-width-1-2@s uk-text-center" uk-grid>
    <a href="mesdemandes.php">
        <div>
            <div class="uk-background-primary uk-light uk-padding uk-panel">
                <p class="uk-h4">Voir mes demandes</p>
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

<form method="POST" action="services.php" id="propose"></form>



<script src="js/lists.js"></script>

</body>
</html>

