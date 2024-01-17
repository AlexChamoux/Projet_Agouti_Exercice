<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

if(!isset($_SESSION['login'])){
	// Si l'utilisateur n'est pas logué, on le redirige vers la page de login (index.php)
	header('location:index.php');
}else{
	// sinon, on peut continuer,
	// si le formulaire a ete envoye : $_POST['change'] existe
	if(!isset($_POST['change'])){
		echo "<script>alert('Le formulaire n'a pas été soumis, un problème est survenu.)</script>";
	}else{
		
		
		$actualPassword = valid_donnees($_POST['actual-password']);
		
		$email = $_SESSION['login'];
		error_log($email);
		// On cherche en base l'utilisateur avec ce mot de passe et cet email

		if(!empty($actualPassword)
			&& strlen($actualPassword) <= 20
			&& preg_match("#^[A-Za-z0-9.+_@!?&§%]+$#", $actualPassword)){


		$query = "SELECT * FROM tblreaders WHERE EmailId = :email";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		error_log(print_r($result, 1));
		error_log(password_hash("Test@123", PASSWORD_DEFAULT));

		}


		if(!password_verify($actualPassword, $result['Password'])){
			// Si le mot de passe actuel est incorrect, on affiche un message d'erreur
			echo "<script> window.addEventListener('load', () => {
				alert('Le mot de passe actuel est incorrect.');				
				})</script>";
			
		}else{

			$password = valid_donnees($_POST['password']);
			$newPassword = password_hash($password, PASSWORD_DEFAULT); 

			if(!empty($password)
			&& strlen($password) <= 20
			&& preg_match("#^[A-Za-z0-9.+_@!?&§%]+$#", $password)){

			$query = "UPDATE tblreaders SET Password = :password WHERE EmailId = :email";
			$stmt = $dbh->prepare($query);
			$stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
			$stmt->bindParam(':email', $email, PDO::PARAM_STR);
			$stmt->execute();

			echo "<script>alert('Votre mot de passe a été changé avec succès.')</script>";
			}
		}
	}
}






// Si le resultat de recherche n'est pas vide
// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
// On stocke le message d'operation reussie
// sinon (resultat de recherche vide)
// On stocke le message "mot de passe invalide"

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<!-- FONT AWESOME STYLE  -->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="assets/css/style.css" rel="stylesheet" />

	<!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->

</head>
<script type="text/javascript">
	/* On cree une fonction JS valid() qui verifie si les deux mots de passe saisis sont identiques 
	Cette fonction retourne un booleen*/
	function valid() {
        let password = document.querySelector(".password").value;
        let confirm_password = document.querySelector(".confirm_password").value;
        if (password != confirm_password) {
        alert("Les mots de passe ne sont pas identiques !");
        return false;
        } else {
        return true;
        }
    };
</script>

<body>
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('includes/header.php'); ?>
	<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
	<div class="container">
		<div class="row">
			<div class="col">
				<h3>CHANGER MON MOT DE PASSE</h3>
			</div>
		</div>
        <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
				<form method="post" action="change-password.php" onsubmit="return valid()">
                         <div class="form-group">
                              <label>Mot de passe actuel :</label>
                              <input type="password" name="actual-password" placeholder="Mot de passe actuel" pattern="^[A-Za-z0-9.+_@!?&§%]+$" maxlength="20" required>
                         </div>
                         <div class="form-group">
                              <label>Nouveau mot de passe :</label>
                              <input type="password" name="password" class="password" placeholder="Entrez votre mot de passe" pattern="^[A-Za-z0-9.+_@!?&§%]+$" maxlength="20" required>
                         </div>
                         <div class="form-group">
                              <label>Confirmez mot de passe :</label>
                              <input type="password" name="confirm-password" class="confirm_password" placeholder="Confirmez votre mot de passe" pattern="^[A-Za-z0-9.+_@!?&§%]+$" maxlength="20" required>
                         </div>
						<button type="submit" name="change" class="btn btn-info">Changer</button>
                </form>
            </div>
        </div>
    </div>
	<!--  Si on a une erreur, on l'affiche ici -->
	<!--  Si on a un message, on l'affiche ici -->

	<!--On affiche le formulaire-->
	<!-- la fonction de validation de mot de passe est appelee dans la balise form : onSubmit="return valid();"-->


	<?php include('includes/footer.php'); ?>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>