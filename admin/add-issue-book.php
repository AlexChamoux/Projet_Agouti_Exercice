<?php
session_start();

include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:../adminlogin.php');
}else{

    
    

    if(isset($_POST['creer'])){

        $isbn = valid_donnees($_POST['isbnnumber']);
        $readerid = valid_donnees($_POST['readerid']);

        if(!empty($isbn) && !empty($readerid)
        && strlen($isbn) <= 13
        && strlen($readerid) <= 6
        && preg_match("#^[0-9]{3,}#", $isbn)
        && preg_match("#^[A-Z0-9]+$#", $readerid)){

        
        

            $query = "SELECT * FROM tblbooks WHERE ISBNNumber = :isbn";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':isbn', $isbn, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log(print_r($result, 1));

            
                $bookid = $result['id'];
                error_log($bookid);
            

            
            $null = NULL;
            $status = 0;
            
            $query = "INSERT INTO tblissuedbookdetails ( BookId, ReaderId, ReturnDate, ReturnStatus, fine) VALUES ( :bookid, :readerid, :returndate, :returnstatus, :fine)";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':bookid', $bookid , PDO::PARAM_INT);
            $stmt->bindParam(':readerid', $readerid , PDO::PARAM_STR);
            $stmt->bindParam(':returndate', $null , PDO::PARAM_STR);
            $stmt->bindParam(':returnstatus', $status , PDO::PARAM_INT);
            $stmt->bindParam(':fine', $null , PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['message'] = "La sortie est enregistrée avec succès";
            header('location:manage-issued-books.php');
        }   

    }
}

?>
<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script>
        // On crée une fonction JS pour récuperer le nom du lecteur à partir de son identifiant
        function getReader(readerid) {
            let FullName = document.getElementById("name");
            let submitBtn = document.querySelector(".creer");

            fetch("get_reader.php?readerid="+readerid)
                .then(response => response.json())
                .then(data => {                    
                    if(!((data.name == "Le lecteur est désactivé") || (data.name == "Le lecteur est invalide"))){
                        FullName.innerHTML = data.name;                      
                        submitBtn.disabled = false;
                        console.log(submitBtn.disabled);
                    }else{
                        FullName.innerHTML = data.name;
                        submitBtn.disabled = true;
                        console.log(submitBtn.disabled);
                    }
                    
                });
        }
            
        // On crée une fonction JS pour recuperer le titre du livre a partir de son identifiant ISBN
        function getBook(isbnNumber) {
            let BookName = document.getElementById("isbn");
            let submitBtn = document.querySelector(".creer");

            fetch("get_book.php?isbnNumber="+isbnNumber)
            .then(response => response.json())
            .then(data => {
                if(!(data.name == "Le livre n'est plus dispo, voyez.")){                    
                    BookName.innerHTML = data.name;
                    submitBtn.disabled = false;
                    console.log(submitBtn.disabled);
                        
                } else {                      
                    BookName.innerHTML = "Oups, c'est tout planté. Livre invalide.";
                    submitBtn.disabled = true;
                }
                });              
            };
    </script>
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php'); ?>
    <!-- MENU SECTION END-->

    <!-- Dans le formulaire du sortie, on appelle les fonctions JS de recuperation du nom du lecteur et du titre du livre 
 sur evenement onBlur-->
<div class="container">
        <div class="row">
                <div class="col">
                <h3>SORTIE D'UN LIVRE</h3>
                </div>
        </div>
        <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8 offset-md-3">
                    <div class="container">
                            <div class="card bg-primary text-black">
                                <h2 class="card-header">Sortie d'un livre</h2>
                            </div>
                                <div class="card-body border p-4">
                                        <form method="post" action="add-issue-book.php">
                                            <div class="form-group">
                                                    <label>Identifiant lecteur</label>
                                                    <input type="text" name="readerid" onBlur="getReader(this.value)" pattern="^[A-Z0-9]{3,}" maxlength="6" required> &nbsp &nbsp<span id="name"></span>
                                            </div>                                            
                                            <div class="form-group">
                                                    <label>ISBN</label>
                                                    <input type="text" name="isbnnumber" onBlur="getBook(this.value)" pattern="^[0-9]{3,}" maxlength="13" required> &nbsp &nbsp<span id="isbn"></span>
                                            </div>                                            
                                            <button type="submit" name="creer" class="btn btn-info creer">Créer la sortie</button>
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