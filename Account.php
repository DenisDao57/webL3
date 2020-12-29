<html>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<?php
session_start();
include 'navbar/Header_logged.php'; // On ajoute le header
include 'bdd.php';


$log=array();
if ($_SESSION["login"]!=true){
    header('location:index.php');
}else{
    foreach($db->query('SELECT * FROM personne WHERE id='.$_SESSION["id"]) as $row){
        array_push($log,$row["mail"]);
    }
}


?>

<body class="text-center">
<h1 style="margin-top:2%;" class="font-weight-normal">Modifier son compte</h1>
    <form action="test/Edit_account.php" method="post">
        <?php
        if (isset($_GET["mail"])){
            if ($_GET["mail"]=="f"){
                echo "<div style='color:red;'>Mail déjà utilisé!</div>";
            }
        }
        if (isset($_GET["pwd"])){
            if ($_GET["pwd"]=="f"){
                echo "<div style='color:red;'>Ancien mot de passe faux !</div>";
            }
        }
        ?>
        <div class="form-group">
            <input required name="mail" type="email" class="form-control" id="InputEmail1" aria-describedby="emailHelp" placeholder="E-mail" <?php echo "value=".$log[0]; ?>>
        </div>
        <div class="form-group">
            <input required name="pwd_old" type="password" class="form-control" id="InputPassword1" placeholder="Ancien mot de passe">
        </div>
        <div class="form-group">
            <input name="pwd" type="password" class="form-control" id="InputPassword1" placeholder="Mot de passe">
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
        </br>
        <a style="margin-top:1em;"class="btn btn-info" href="index.php" role="button">Annuler</a>
    </form>
</body>

</html>