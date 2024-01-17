<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
      // On le redirige vers la page de login  
      header('location:../adminlogin.php');
  }else{

  $query = "SELECT * FROM tblcategory WHERE Status = 1";
  $stmt = $dbh->prepare($query);
  $stmt->execute();
  $results = $stmt->fetchAll();
  

  $query = "SELECT * FROM tblauthors WHERE Status = 1";
  $stmt = $dbh->prepare($query);
  $stmt->execute();
  $responses = $stmt->fetchAll();
     
      $id = valid_donnees($_GET['edit']);      
      error_log($id);  
      
      if (!empty($id)
      && strlen($id) <= 3
      && preg_match("#^[0-9]{1,}#",$id)){

            if(isset($_POST['update'])){
                  $BookName = valid_donnees($_POST['BookName']);
                  $CatId = valid_donnees($_POST['categorie']);
                  error_log($CatId);
                  $AuthorId = valid_donnees($_POST['auteur']);
                  error_log($AuthorId);
                  $ISBNNumber = valid_donnees($_POST['ISBNNumber']);
                  $BookPrice = valid_donnees($_POST['BookPrice']);

                  if (!empty($BookName) && !empty($ISBNNumber) && !empty($BookPrice)
                  && strlen($BookName) <= 60
                  && strlen($ISBNNumber) <= 13
                  && strlen($BookPirce) <= 4
                  && preg_match("#^[A-Za-z0-9 '-]+$#", $BookName)
                  && preg_match("#^[0-9]{3,}#",$ISBNNumber)
                  && preg_match("#^[0-9]{1,}#",$BookPrice)){

                  $query = "UPDATE tblbooks SET BookName = :BookName, CatId = :CatId, AuthorId = :AuthorId, ISBNNumber = :ISBNNumber, BookPrice = :BookPrice WHERE id = $id";
                  $stmt =$dbh->prepare($query);
                  $stmt->bindParam(':BookName', $BookName, PDO::PARAM_STR);
                  $stmt->bindParam(':CatId', $CatId, PDO::PARAM_STR);
                  $stmt->bindParam(':AuthorId', $AuthorId, PDO::PARAM_STR);
                  $stmt->bindParam(':ISBNNumber', $ISBNNumber, PDO::PARAM_STR);
                  $stmt->bindParam(':BookPrice', $BookPrice, PDO::PARAM_STR);
                  $stmt->execute();

                  $_SESSION['message'] = "Le livre a été mise à jour avec succès";
                  header('location:manage-books.php');
                  
                  }

            }else{
                  echo "<script>alert('Le formulaire n'a pas été transmis.')</script>";
            }
      }
      
}

?>

<!DOCTYPE html>
<html>

<head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

      <title>Gestion de bibliothèque en ligne | Livres</title>
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

<div class="container">
      <div class="row">
            <div class="col">
                  <h3>EDITER UN LIVRE</h3>
            </div>
      </div>
      <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                  <div class="container">
                        <div class="card bg-primary text-black">
                              <h2 class="card-header">Information livre</h2>
                        </div>
                        <div class="card-body border p-4">
                              <form method="post">
                                    <div class="form-group">
                                          <label>Titre</label>
                                          <input type="text" name="BookName" maxlength="60" pattern="^[A-Za-z0-9 '-]+$" required>
                                    </div>
                                    <div class="form-group">
                                          <label>Catégorie</label>                              
                                          <select name="categorie" required>
                                                <option value="">Choisir une catégorie</option> 
                                                <?php
                                                      foreach ($results as $result) { ?>                                 
                                                      <option value="<?= $result['id'] ?>"><?= $result['CategoryName'] ?></option>
                                                      <?php } ?>
                                          </select>                              
                                    </div>
                                    <div class="from-group">
                                          <label>Auteur</label>
                                          <select name="auteur" required>
                                                <option value="">Choisir un auteur</option>
                                                <?php
                                                foreach ($responses as $response) { ?>                                 
                                                <option value="<?= $response['id'] ?>"><?= $response['AuthorName'] ?></option>
                                                <?php } ?>
                                          </select>
                                    </div>
                                    <div class="form-group">
                                          <label>ISBN</label>
                                          <input type="number" name="ISBNNumber" pattern="^[0-9]{3,}" maxlength="13" required>
                                    </div>
                                    <div class="form-group">
                                          <label>Prix</label>
                                          <input type="number" name="BookPrice" pattern="^[0-9]{1,}" maxlength="4" required>
                                    </div>
                                    <button type="submit" name="update" class="btn btn-info">Mettre à jour</button>
                              </form>
                        </div>
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