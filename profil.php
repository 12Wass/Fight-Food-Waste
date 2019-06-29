<?php
/**
 * Created by PhpStorm.
 * User: wassimdahmane
 * Date: 11/04/2019
 * Time: 21:03
 */

require_once("php/classes/classIncluder.php");
if (!isset($_SESSION['isConnected']) || $_SESSION['isConnected'] === NULL || $_SESSION['isConnected'] != true){
    echo 'Vous n\'êtes pas connecté. Retournez à l\'accueil.';
    exit();
}
else {
    $userInfos = User::getUserInfos($_SESSION['user']->getEmail()); // Récupération des informations utilisateur
    $userRole = $_SESSION['role'];
}
?>
<html>
<head>
    <title>Profil - Fight Food Waste</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/uikit.min.css" />
</head>
<body>
<?php require_once("php/templates/Navbar.php");
    if ($userInfos['code'] != 'V'){
      echo '<div class="uk-alert-danger" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            Votre compte n\'est pas actif.
            </div>';
            exit();
    }
    else{
      echo '<div class="uk-alert-success" uk-alert>
            <a class="uk-alert-close" uk-close></a>
            Votre compte est actif!
            </div>';
    }
?>
<div class="uk-background-primary uk-light uk-padding uk-panel">
  <h3 class="uk-h3" name="mesinfos">Mes informations</h3>
</div>
<div class="uk-child-width-1-2@s uk-text-center" uk-grid>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: Prénom" onclick="editFirstName()" id="pr_firstName"><a><?php echo $userInfos['firstName']; ?></a></div>
  </div>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: Nom" onclick="editLastName()" id="pr_lastName"><a><?php echo $userInfos['lastName']; ?></a></div>
  </div>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: Adresse mail" onclick="editEmail()" id="pr_email"><a><?php echo $userInfos['email']; ?></a></div>
  </div>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: Adresse" onclick="editAddress()" id="pr_address"><a><?php echo $userInfos['address']; ?></a></div>
  </div>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: Code Postal" onclick="editPostalCode()" id="pr_postalCode"><a><?php echo $userInfos['postalCode']; ?></a></div>
  </div>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: Ville" onclick="editCity()" id="pr_city"><a><?php echo $userInfos['city']; ?></a></div>
  </div>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: Date de naissance" onclick="editBirthday()" id="pr_birthday"><a><?php echo $userInfos['birthday']; ?></a></div>
  </div>
  <div>
    <div class="uk-card uk-card-secondary uk-card-body" uk-tooltip="title: N° de téléphone" onclick="editPhone()" id="pr_phone"><a><?php echo $userInfos['phone']; ?></a></div>
  </div>
</div>


<div class="uk-background-primary uk-light uk-padding uk-panel">
    <h3 class="uk-h3" name="masociete">Ma société</h3>
</div>

<table class="uk-table uk-table-striped uk-table-hover uk-table-large">
    <thead>
    <tr>
        <th>Nom</th>
        <th>N° Siret</th>
        <th>Type</th>
        <th>Adresse</th>
        <th>Code Postal</th>
        <th>Ville</th>

    </tr>
    </thead>
    <?php
    $counter = 0;
    $database = Database::getDatabaseConnection();
    $getSociety = $database->prepare('SELECT * FROM society WHERE idRepresentative = ?');
    $getSociety->execute(array($userInfos['id']));

    foreach($getSociety as $s){
        echo '<tr onclick="generateEditForm(society'. $s['id'].')" id="society'.$s['id'].'">
                  <td><a href="society.php?sid='. $s['id'] . '"> ' . $s['name'] . '</a> </td>
                  <td>' . $s['sirenNumber'] . '</td>
                  <td>' . $s['type'] . '</td>
                  <td>' . $s['address'] . '</td>
                  <td>' . $s['postalCode'] . '</td>
                  <td>' . $s['city'] . '</td>
                  </tr>';
        $counter++;
    }
    ?>
</table>

<div class="uk-background-primary uk-light uk-padding uk-panel">
    <h3 class="uk-h3" name="mescompetences">Mes compétences</h3>
</div>

<table class="uk-table uk-table-striped uk-table-hover uk-table-large">
    <thead>
    <tr>
        <th>Compétence 1</th>
        <th>Compétence 2</th>
        <th>Compétence 3</th>
        <th>Compétence 4</th>
        <th>Compétence 5</th>
    </tr>
    </thead>
    <?php
    $counter = 0;
    $database = Database::getDatabaseConnection();
    $getSkill = $database->prepare('SELECT * FROM userSkill WHERE idUser = ?');
    $getSkill->execute(array($userInfos['id']));

    foreach($getSkill as $k){
        echo '<tr onclick="generateEditForm(skill'. $k['id'].')" id="skill'.$k['id'].'">
                  <td>' . $k['skill1'] . '</td>
                  <td>' . $k['skill2'] . '</td>
                  <td>' . $k['skill3'] . '</td>
                  <td>' . $k['skill4'] . '</td>
                  <td>' . $k['skill5'] . '</td>
                  </tr>';
        $counter++;
    }
    ?>
</table>

<div class="uk-background-primary uk-light uk-padding uk-panel">
    <h3 class="uk-h3" name="meslistes">Mes listes</h3>
</div>
    <table class="uk-table uk-table-striped uk-table-hover uk-table-large">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Créateur ID</th>
            <th>Description</th>
            <th>Assignation</th>
        </tr>
        </thead>
        <?php
        $counter = 0;
        $database = Database::getDatabaseConnection();
        $getList = $database->prepare('SELECT * FROM listFood WHERE idCreator = ?');
        $getList->execute(array($userInfos['id']));

        foreach($getList as $l){
            $getAssignation = $database->prepare('SELECT * FROM rideAdresses WHERE idList = ?');
            $getAssignation->execute([$l['id']]);
            $a = $getAssignation->fetch(PDO::FETCH_ASSOC);
            echo '<tr onclick="generateEditForm(list'. $l['id'].')" id="list'.$l['id'].'">
                  <td>' . $l['name'] . '</td>
                  <td>' . $l['idCreator'] . '</td>
                  <td>' . $l['description'] . '</td>
                  <td><a href="private/admin/listDetails.php?lid='. $l['id'] . '">' . $a['address'] . '</a></td>
                  </tr>';
            $counter++;
        }
        ?>
    </table>



<script src="js/uikit.min.js"></script>
<script src="js/uikit-icons.min.js"></script>
<script src="js/users.js"></script>
<script src="js/jquery.js"></script>
</body>
</html>
