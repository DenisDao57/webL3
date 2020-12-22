<html>

<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<?php
include 'bdd.php';
include 'navbar/Header_nolog.php'; // On ajoute le header

session_start();
session_destroy(); // Si on a accès à la page de login c'est qu'on est déjà deconnecté, soit qu'on a appuyé sur le bouton de deconnexion

?>

<body class="text-center">

<form class="form-signin" action="test/Login_test.php" method="post">
<img class="mb-4" src="image/cocktail.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Veuillez vous connecter</h1>
      <?php 
      if (isset($_GET["test"])) // test si login est faux ou non
        if ($_GET["test"]=="f"){
          echo "<div style='color:red;'>Identifiants incorrects !</div>";
        } 
      ?>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input name="email" type="email" id="inputEmail" class="form-control" placeholder="E-mail" required="" autofocus="">
      <label for="inputPassword" class="sr-only">Password</label>
      <input name="pwd" style="margin-top:1em" type="password" id="inputPassword" class="form-control" placeholder="Mot de passe" required="">
      <button style="margin-top:1em" class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      <a style="margin-top:1em;width:60%;"class="btn btn-info" href="Register.php" role="button">S'inscrire</a>
</form>
</body>