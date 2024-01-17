<?php
session_start();

include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0){
      // Si l'utilisateur n'est plus logué
      // On le redirige vers la page de login
      header('location:../adminlogin.php');
}else{

      if(isset($_POST['ajouter'])){
            $AuthorName = valid_donnees($_POST['AuthorName']);

            if(!empty($AuthorName)
            && strlen($AuthorName) <= 40
            && preg_match("#^[A-Za-z '-]+$#", $AuthorName)){

            //On prépare la requete d'insertion dans la table tblauthors
            $query = "INSERT INTO tblauthors (AuthorName, Status) VALUES (:AuthorName, 1)";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':AuthorName', $AuthorName, PDO::PARAM_STR);
            $stmt->execute();
            
            // On stocke dans $_SESSION le message correspondant au resultat de l'operation
            $_SESSION['message'] = "L\'Auteur a été ajouté avec succès";
            header('location:manage-authors.php');
            }
      }

}
?>

<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
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
      <!-- On affiche le titre de la page-->
      <div class="container">
            <div class="row">
                  <div class="col">
                        <h3>AJOUTER UN AUTEUR</h3>
                  </div>
            </div>
            <div class="row">
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                        <div class="container">
                              <div class="card bg-primary text-black">
                                    <h2 class="card-header">Information Auteur</h2>
                              </div>
                                    <div class="card-body border p-4">
                                          <form method="post" action="add-author.php">
                                                <div class="form-group">
                                                      <label>Nom</label>
                                                      <input type="text" name="AuthorName" maxlength="40" pattern="^[A-Za-z '-]+$" required>
                                                </div>
                                                <button type="submit" name="ajouter" class="btn btn-info">Ajouter</button>
                                          </form>
                                    </div>
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
