<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('includes/config.php');

if(!strlen($_SESSION['login'])){
    // Si l'utilisateur n'est pas connecte,
    // On le dirige vers la page de login
    header('location:index.php');
}else{
    // Sinon on peut continuer
    $ReaderId = $_SESSION['rdid'];
    $query = "SELECT tib.BookId, tib.IssuesDate, tib.ReturnDate, tib.ReturnStatus, tb.BookName, tb.ISBNNumber, tb.id
            FROM tblissuedbookdetails tib
            JOIN tblbooks tb ON tib.BookId = tb.id
            WHERE tib.ReaderId = :ReaderId";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ReaderId', $ReaderId, PDO::PARAM_STR);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log(print_r($results, 1));
};
?>
<!DOCTYPE html>
<html lang="FR">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
      <!--On insere ici le menu de navigation T-->
<?php include('includes/header.php');?>

           <!-- On affiche le titre de la page : LIVRES SORTIS -->  
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>LIVRES EMPRUNTÉS</h3>
                </div>
            </div>    
           <!-- On affiche la liste des sorties contenus dans $results sous la forme d'un tableau -->
            <table class="table">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Titre</th>
                    <th>ISBN</th>
                    <th>Date de sortie</th>
                    <th>Date de retour</th>
                    </tr>
                </thead>
                <tbody class="body">
                </tbody>
            </table>

            <script>
                const tbody = document.querySelector('.body');
                const results = <?php echo json_encode($results);?>;
                

                results.forEach((result, index) => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${result['BookId']}</td>
                    <td>${result['BookName']}</td>
                    <td>${result['ISBNNumber']}</td>
                    <td>${result['IssuesDate']}</td>
                    <td>${result['ReturnDate'] ? result['ReturnDate'] : 'non retourné'}</td>
                    `;
                    tbody.appendChild(tr);
                });
            </script>
        </div>    
           <!-- Si il n'y a pas de date de retour, on affiche non retourne --> 


  <?php include('includes/footer.php');?>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>