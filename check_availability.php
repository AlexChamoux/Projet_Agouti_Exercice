<?php
// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');
/*error_log("coucou");*/
// On vérifie que la méthode utilisée est bien GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // On récupère l'e-mail envoyé en GET
$email = trim($_GET['email']);
	/*error_log(print_r($_GET, 1));*/

    // On vérifie que l'e-mail est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // L'e-mail n'est pas valide
        $response = array('exists' => false, 'error' => 'L\'e-mail n\'est pas valide.');
        echo json_encode($response);
        exit();
    }

    // On prépare la requête pour vérifier l'existence de l'e-mail dans la base de données
    $query = "SELECT EmailId FROM tblreaders WHERE EmailId = :email";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // On récupère le résultat de la requête
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        // L'e-mail existe déjà dans la base de données
        $response = array('exists' => true);
        echo json_encode($response);
        exit();
    } else {
        // L'e-mail n'existe pas encore dans la base de données
        $response = array('exists' => false);
        echo json_encode($response);
        exit();
    }
}
?>