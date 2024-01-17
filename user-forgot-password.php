<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
// Après la soumission du formulaire de login ($_POST['change'] existe
if(isset($_POST['change'])){
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)

     if ($_POST['vercode'] != $_SESSION['vercode']) {
          //  Si le code est incorrect on informe l'utilisateur par une fenetre pop_up
          echo "<script>alert('Code de vérification incorrect')</script>";
     } else {         

               // Sinon on continue
               // on recupere l'email et le numero de portable saisi par l'utilisateur
               // et le nouveau mot de passe que l'on encode (fonction password_hash)
               $email = valid_donnees($_POST['email']);
               $mobile = valid_donnees($_POST['portable']);
               $password = valid_donnees($_POST['password']);
               $passwordHash = password_hash($password, PASSWORD_DEFAULT);

               if(!empty($email) && !empty($mobile) && !empty($password)
               && strlen($email) <= 40
               && strlen($mobile) <=10
               && strlen($password) <= 20
               && preg_match("#^[A-Za-z]+@{1}[A-Za-z]+\.{1}[A-Za-z]{2,}$#", $email)
               && preg_match("#^[0-9]+$#", $mobile)
               && preg_match("#^[A-Za-z0-9.+_@!?&§%]+$#", $password)){

                    // On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
                    $query = "SELECT * FROM tblreaders WHERE EmailId = :EmailId AND MobileNumber = :MobileNumber";
                    $stmt = $dbh->prepare($query);
                    $stmt->bindParam(':EmailId', $email, PDO::PARAM_STR);
                    $stmt->bindParam(':MobileNumber', $mobile, PDO::PARAM_STR);
                    $stmt->execute();

                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Si le resultat de recherche n'est pas vide
                    if($result != 0){
                         // On met a jour la table tblreaders avec le nouveau mot de passe
                         $query = "UPDATE tblreaders SET Password = :Password";
                         $stmt = $dbh->prepare($query);
                         $stmt->bindParam(':Password', $passwordHash, PDO::PARAM_STR);
                         $stmt->execute();
                         // On informe l'utilisateur par une fenetre popup de la reussite ou de l'echec de l'operation
                         echo "<script>alert('Les données ont bien été mises à jours.')</script>";
                    }else{
                         echo "<script>alert('Les données n'ont pas été mises à jours.')</script>";
                    }
               }
     }
}

?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <!-- FONT AWESOME STYLE  -->
     <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="assets/css/style.css" rel="stylesheet" />

     <script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
     </script>

</head>

<body>
     <!--On inclue ici le menu de navigation includes/header.php-->
     <?php include('includes/header.php'); ?>
     <!-- On insere le titre de la page (RECUPERATION MOT DE PASSE -->
     <div class="container">
		<div class="row">
			<div class="col">
				<h3>RÉCUPÉRATION DE MOT DE PASSE</h3>
			</div>
		</div>
          <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="user-forgot-password.php">
                         <div class="form-group">
                              <label>Email :</label>
                              <input type="text" name="email" placeholder="Entrez votre adresse email" onBlur="check_availability(this.value)" pattern="^[A-Za-z]+@{1}[A-Za-z]+\.{1}[A-Za-z]{2,}$" maxlength="40" required>
                         </div>
                         <div class="form-group">
                              <label>Tel portable :</label>
                              <input type="text" name="portable" placeholder="Entrez votre numéro de portable" pattern="^[0-9]+$" maxlength="10" required>
                         </div>
                         <div class="form-group">
                              <label>Nouveau mot de passe :</label>
                              <input type="password" name="password" class="password" placeholder="Entrez votre mot de passe" pattern="^[A-Za-z0-9.+_@!?&§%]+$" maxlength="20" required>
                         </div>
                         <div class="form-group">
                              <label>Confirmez mot de passe :</label>
                              <input type="password" name="password" class="confirm_password" placeholder="Confirmez votre mot de passe" required>
                         </div>
                         <div class="form-group">
                              <label>Code de vérification</label>
                              <input type="text" name="vercode" placeholder="Entrez le code captcha" required style="height:25px;">&nbsp;&nbsp;&nbsp;<img src="captcha.php">
                         </div>
                         <button type="submit" name="change" class="btn btn-info">Envoyer</button>
                    </form>
               </div>
          </div>
     </div>
     <!--On insere le formulaire de recuperation-->
     <!--L'appel de la fonction valid() se fait dans la balise <form> au moyen de la propriété onSubmit="return valid();"-->


     <?php include('includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>