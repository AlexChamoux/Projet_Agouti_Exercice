<?php
session_start();

include('includes/config.php');

if(strlen($_SESSION['alogin'] == 0)){
      // Si l'utilisateur est déconnecté
      // L'utilisateur est renvoyé vers la page de login : adminlogin.php
      header('location:../adminlogin.php');
}else{
      
      // Récupérer le message enregistré dans la variable de session
      if (!strlen($_SESSION['message']) == 0) {
            $message = $_SESSION['message'];
            error_log($message);
            echo "<script> window.addEventListener('load', () => {
                alert('" . $message . "');
                event.preventDefault();
                })</script>";
            $_SESSION['message'] = '';
        }
      
      
      // On recupere l'identifiant de la catégorie a supprimer
      $stmt = $dbh->query("SELECT * FROM tblauthors");
      $results = $stmt->fetchAll();

      if(isset($_GET['suppress'])){
            $id = valid_donnees($_GET['suppress']);

            if (!empty($id)
            && strlen($id) <= 3
            && preg_match("#^[0-9]{1,}#",$id)){

            $dbh->exec("UPDATE tblauthors SET Status = 0 WHERE id=$id");
            header('location:manage-authors.php');
            }
      };
}



?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion des auteurs</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="container">
        <div class="row">
            <div class="col">
                <h3>GESTION DES AUTEURS</h3>
            </div>
        </div>
        <div class="row">
            <div class="w-100">
                <div class="container">
                    <div class="card bg-primary text-black">
                        <h2 class="card-header">Auteurs</h2>
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
                                    <td class="fa-border"><?= $result['AuthorName'] ?></td>
                                    <td class="fa-border"><?= $result['Status'] == 0 ? "Inactif" : "Actif"; ?></td>
                                    <td class="fa-border"><?= $result['creationDate'] ?></td>
                                    <td class="fa-border"><?= $result['UpdationDate'] ?></td>
                                    <td class="fa-border"><a href="edit-author.php?edit=<?= $result['id'] ?>"><button name="edit" class="btn btn-info">Éditer</button></a>
                                    <a href="manage-authors.php?suppress=<?= $result['id'] ?>"><button type="submit" name="suppress" class="btn btn-danger">Supprimer</button></a></td>
                              </tr>
                              <?php } ?>
                              </tbody>
                        </table>
               </div>
            </div>
        </div>
</div>
     <!-- CONTENT-WRAPPER SECTION END-->
<?php include('includes/footer.php');?>
      <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
