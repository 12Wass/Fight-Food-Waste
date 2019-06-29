<?php

require_once("php/classes/classIncluder.php");

$database = Database::getDatabaseConnection();
if (isset($_POST['changeDemand'])){
    $getService = $database->prepare("SELECT * FROM userService WHERE id = ?");
    $getService->execute([$_POST['changeList']]);
    $getPropositions = $database->prepare("SELECT * FROM propose WHERE idList = ?");
    $getPropositions->execute(array($_POST['changeList']));
    $serviceId = $_POST['changeList'];
}

if (isset($_POST['deny'])){
    $removeProposition = $database->prepare("UPDATE propose SET isAccepted = 2 WHERE id = ?");
    $removeProposition->execute(array($_POST['deny']));
    header('Location: messervices.php');
}

if (isset($_GET['sid'])) {
    $getService = $database->prepare("SELECT * FROM userService WHERE id = ?");
    $getService->execute([$_GET['sid']]);
    $getPropositions = $database->prepare("SELECT * FROM propose WHERE idService = ?");
    $getPropositions->execute(array($_GET['sid']));
    $serviceId = $_GET['sid'];
}
if (!isset($_GET['sid']) && !isset($_POST['changeDemand'])){
    echo "Veuillez spécifier une référence de demande";
    exit();
}

?>

<html>
<head>
    <title>Demande n°<?php echo $serviceId; ?></title>
    <meta charset="utf-8"/>
    <!-- Inclusion d'UIkit + Jquery -->
    <link rel="stylesheet" href="css/uikit.min.css">
    <script src="js/uikit.js"></script>
    <script src="js/jquery.js"></script>
    <style type="text/css">
        #textbox {padding:10px;border-radius:5px;border:0;box-shadow:0 0 4px 0 rgba(0,0,0,0.2)}
        .row img {width:40px;height:40px;border-radius:50%;vertical-align:middle;}
        .row {padding:10px;} .row:hover {background-color:#eee}
        .goRight {text-align: right;}
    </style>
</head>

<body>
<?php include_once("php/templates/Navbar.php");?>
<h1 style="text-align: center;" class="uk-align-center" id="h1Id">Demande n°<?php echo $serviceId; ?></h1>
<form action="listDetails.php" method="post">
    <input type="text" class="uk-input uk-align-center" placeholder="Numéro de liste" name="changeList">
</form>

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
<!-- Bouton d'ajout -->

<div id="displayFoodList"></div>
<table class="uk-table uk-table-striped uk-table-hover uk-table-large">
    <thead>
    <tr>
        <th>Bénévole</th>
        <th>Compétences</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody id="rewrite">
    <?php
    // TODO : Faire un affichage en plusieurs page (afficher 20 max par exemple)
    foreach($getPropositions as $p){
        $userInfos = User::getUserInfosById($p['idUser']);
        $userSkills = User::getUserSkills($p['idUser']);
            echo '<tr onclick="serviceEditForm(service'. $s['id'] .')" id="service'. $s['id'] .'" serviceId="'. $s["id"] .'">
                  <td><a href="messagerie.php?uid='. $creatorInfo['id']. '">' . $userInfos['firstName'] . " " . $userInfos['lastName'] . '</a></td>
                  <td>' . $userSkills['skill1']  . ", " . $userSkills['skill2'] . ", " . $userSkills['skill3'] . ", " . $userSkills["skill4"] . ", " . $userSkills['skill5'] . '</td>
                  <td><button class="uk-button uk-button-danger" name="deny" type="submit" value="'. $s["id"] .'" form="deny">Supprimer</button></td>
                  </tr>';
    }
    ?>
    </tbody>
</table>
<form action="" method="post" id="deny"></form>
<script src="js/stocks.js"></script>
<script src="js/uikit-icons.js"></script>


</body>
</html>
