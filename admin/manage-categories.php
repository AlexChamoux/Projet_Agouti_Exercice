<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('includes/config.php');

if(strlen($_SESSION['alogin'] == 0)){
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoyé vers la page de login : adminlogin.php
    header('location:../adminlogin.php');
}else{
    // On recupere l'identifiant de la catégorie a supprimer
    if (!strlen($_SESSION['message']) == 0) {
        $message = $_SESSION['message'];
        echo "<script> window.addEventListener('load', () => {
            alert('" . $message . "');
            event.preventDefault();
            })</script>";
        $_SESSION['message'] = '';
    }

    $stmt = $dbh->query("SELECT * FROM tblcategory");
    $results = $stmt->fetchAll();

    if(isset($_GET['suppress'])){
        $id = valid_donnees($_GET['suppress']);

        if (!empty($id)
        && strlen($id) <= 3
        && preg_match("#^[0-9]{1,}#",$id)){

        $dbh->exec("UPDATE tblcategory SET Status = 0 WHERE id=$id");
        header('location:manage-category.php');
        }
        
    };

    
}






// On prepare la requete de suppression

// On execute la requete

// On informe l'utilisateur du resultat de l operation

// On redirige l'utilisateur vers la page manage-categories.php

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <!-- On affiche le titre de la page-->
<div class="container">
    <div class="row">
        <div class="col">
            <h3>GESTION DES CATÉGORIES</h3>
        </div>
    </div>    

    <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->

    <!-- On affiche le formulaire de gestion des categories-->
    <div class="row">
            <div class="w-100">
                <div class="container">
                    <div class="card bg-primary text-black">
                        <h2 class="card-header">Information catégorie</h2>
                    </div>
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th class="fa-border">#</th>
                                    <th class="fa-border">Nom</th>
                                    <th class="fa-border">Statut</th>
                                    <th class="fa-border">Créé le :</th>
                                    <th class="fa-border">Mise à jour le :</th>
                                    <th class="fa-border">Action</th>
                                </tr>
                            </thead>
                            <tbody class="body">
                            <?php                           

                            foreach ($results as $result) { ?>
                            <tr>
                                <td class="fa-border"><?= $result['id'] ?></td>
                                <td class="fa-border"><?= $result['CategoryName'] ?></td>
                                <td class="fa-border"><?= $result['Status'] == 0 ? "Inactive" : "Active"; ?></td>
                                <td class="fa-border"><?= $result['CreationDate'] ?></td>
                                <td class="fa-border"><?= $result['UpdationDate'] ?></td>
                                <td class="fa-border"><a href="edit-category.php?edit=<?= $result['id'] ?>"><button name="edit" class="btn btn-info">Éditer</button></a>
                                <a href="manage-categories.php?suppress=<?= $result['id'] ?>"><button type="submit" name="suppress" class="btn btn-danger">Supprimer</button></a></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
    </div>
</div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>