<?php 
session_start();

include('includes/config.php');
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero l'identifiant du lecteur SID---*/
// On prepare la requete de recherche du lecteur correspondant
// On execute la requete
// Si un resultat est trouve
	// On affiche le nom du lecteur
	// On active le bouton de soumission du formulaire
// Sinon
	// Si le lecteur n existe pas
		// On affiche que "Le lecteur est non valide"
		// On desactive le bouton de soumission du formulaire
	// Si le lecteur est bloque
		// On affiche lecteur bloque
		// On desactive le bouton de soumission du formulaire

		$readerid = $_GET['readerid'];
  error_log($readerid);

		// exécuter la requête SQL pour récupérer les données de la table "tblreaders" avec les colonnes "ReaderId" et "FullName"
		$query = "SELECT * FROM tblreaders WHERE ReaderId = :readerid";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':readerid', $readerid, PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch();
		error_log(print_r($result, 1));
		
		if($result){
			if($result['Status'] == 0){
				echo json_encode(['name' => "Le lecteur est désactivé"]);
			}else{
				echo json_encode(['name' => "{$result['FullName']}"]);	
			}
		}else{
			echo json_encode(['name' => "Le lecteur est invalide"]);
		}
	
	?>

