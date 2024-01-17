<?php
session_start();

include('includes/config.php');

// Si l'utilisateur n'est plus logué
// On le redirige vers la page de login
if(strlen($_SESSION['alogin']) == 0){
	// Si l'utilisateur n'est plus logué
	// On le redirige vers la page de login
	header('location:../adminlogin.php');
}else{
// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
	if(isset($_POST['change'])){
		$actualPassword = $_POST['actual-password'];
		//$actHashPwd = password_hash($actualPassword, PASSWORD_DEFAULT);
		$userName = $_SESSION['alogin'];

		error_log('Act-Pwd:'.$actualPassword);
		error_log('User:'.$userName);

		$query = "SELECT * FROM admin WHERE UserName = :userName";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		error_log(print_r($result, 1));
		error_log(password_hash("Test@123", PASSWORD_DEFAULT));
		$test =(!empty($actualPassword)
		&& strlen($actualPassword) <= 20
		&& preg_match("#^[A-Za-z0-9.+_@!?&§%]+$#", $actualPassword));

		error_log('test:'.$test);

		if($test){

			if(!password_verify($actualPassword, $result['Password'])){
				// Si le mot de passe actuel est incorrect, on affiche un message d'erreur
				
				echo "<script> window.addEventListener('load', () => {
					alert('Le mot de passe actuel est incorrect.');				
					})</script>";
				
			}else{				

				$password = valid_donnees($_POST['password']);
				$newPassword = password_hash($password, PASSWORD_DEFAULT);

				error_log('Pwd:'.$password);
				error_log('NewPwd:'.$newPassword);

				if(!empty($password)
				&& strlen($password) <= 20
				&& preg_match("#^[A-Za-z0-9.+_@!?&§%]+$#", $password)){

				$query = "UPDATE admin SET Password = :password WHERE UserName = :userName";
				$stmt = $dbh->prepare($query);
				$stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
				$stmt->bindParam(':userName', $userName, PDO::PARAM_STR);
				$stmt->execute();

				echo "<script>alert('Votre mot de passe a été changé avec succès.')</script>";

				}
			}
		}
	}
}
// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
// Si le formulaire a bien ete soumis
// On recupere le mot de passe courant
// On recupere le nouveau mot de passe
// On recupere le nom de l'utilisateur stocké dans $_SESSION

// On prepare la requete de recherche pour recuperer l'id de l'administrateur (table admin)
// dont on connait le nom et le mot de passe actuel
// On execute la requete

// Si on trouve un resultat
// On prepare la requete de mise a jour du nouveau mot de passe de cet id
// On execute la requete
// On stocke un message de succès de l'operation
// On purge le message d'erreur
// Sinon on a trouve personne	
// On stocke un message d'erreur

// Sinon le formulaire n'a pas encore ete soumis
// On initialise le message de succes et le message d'erreur (chaines vides)
?>

<!DOCTYPE html>
<html lang="FR">

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<title>Gestion bibliotheque en ligne</title>
		<!-- BOOTSTRAP CORE STYLE  -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<!-- FONT AWESOME STYLE  -->
		<link href="assets/css/font-awesome.css" rel="stylesheet" />
		<!-- CUSTOM STYLE  -->
		<link href="assets/css/style.css" rel="stylesheet" />
		<!-- Penser a mettre dans la feuille de style les classes pour afficher le message de succes ou d'erreur  -->
	</head>

	<script type="text/javascript">
		// On cree une fonction JS valid() qui renvoie
		// true si les mots de passe sont identiques
		// false sinon
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
		<!------MENU SECTION START-->
		<?php include('includes/header.php'); ?>
		<!-- MENU SECTION END-->
		<!-- On affiche le titre de la page "Changer de mot de passe"  -->
		<!-- On affiche le message de succes ou d'erreur  -->
		<div class="container">
			<div class="row">
				<div class="col">
					<h3>CHANGER MON MOT DE PASSE DE L'ADMIN</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
					<div class="container">
						<div class="card bg-primary text-black">
							<h2 class="card-header">Changer le mot de passe</h2>
						</div>
						<div class="card-body border p-4">
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
			</div>
		</div>
		<!-- On affiche le formulaire de changement de mot de passe-->
		<!-- La fonction JS valid() est appelee lors de la soumission du formulaire onSubmit="return valid();" -->

		<!-- CONTENT-WRAPPER SECTION END-->
		<?php include('includes/footer.php'); ?>
		<!-- FOOTER SECTION END-->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	</body>

</html>