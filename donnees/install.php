<?php

  /**
   * Installe la base de donnée (bdd, table, peupler les tables avec Donnees.inc.php)
   */

    include "Donnees.inc.php";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cocktails";
    $tableIngredient = "Ingredient";
    $tableSousCategorie = "IngredientSousCategorie";
    $tableRecette = "Recettes";
    $tableIngredientRecette = "IngredientPourRecette";
    $categoryRacine = "Aliment";
    //$tableName = "IngredientHierarchy";
    $tableFavoris = "favoris";
    $tableUtilisateur="personne";
    
    //drop les table pour reset si elle existe déjà dans la bbd
    dropTables($servername, $username, $password, $dbname);

    //Creation de la bdd et des tables
    createDataBase($servername, $username, $password, $dbname);
    createTableIngredients($servername, $username, $password, $dbname, $tableIngredient);
    createTableSousCategorie($servername, $username, $password, $dbname, $tableSousCategorie);
    createTableRecette($servername, $username, $password, $dbname, $tableRecette);
    createTableIngredientPourRecettes($servername, $username, $password, $dbname, $tableIngredientRecette);
    createTableUtilisateur($servername,$username,$password,$dbname,$tableUtilisateur);
    createTableFavoris($servername,$username,$password,$dbname,$tableFavoris);

    //peuplement des tables avec Donnees.inc.php
    peuplerIngredients($categoryRacine, $servername, $username, $password, $dbname, $tableIngredient);

    peuplerSousCategorie($servername, $username, $password, $dbname, $tableSousCategorie);

    peuplerRecettes($servername, $username, $password, $dbname, $tableIngredient); 


    $categoryRacine = "Aliment";


        
    /**
     * Crée une base de donnée
     * mysqli
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $dbname nom de la base de donnée qu'on veut créer
     * @return void
     */
    function createDataBase(string $servername, string $username, string $password, string $dbname)
    {
        // Create connection
        $conn = new mysqli($servername, $username, $password);
        $conn->query("SET NAMES utf8"); 
        mysqli_set_charset($conn, "utf8");
    
        // Check connection
        if ($conn->connect_error) {
        die("Erreur connexion au serveur: " . $conn->connect_error);
        } else {
        }

        // Create database
        $sql = "CREATE DATABASE ".$dbname;
        if ($conn->query($sql) === TRUE) 
        {
            echo "BDD créée";
        }
        else
        {
            echo "ERREUR Création BDD " . $conn->error;
        }
        echo "<br>";
        $conn->close();
    }

    /**
     * Crée une table qui stock des ingredients 
     * 
     * int idIngredient PRIMARY KEY | varchar nom ingredient
     * 
     * mysqli
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les ingredients
     * @return void
     */
    function createTableIngredients(string $servername, string $username, string $password, string $dbname, string $tablename)
    {
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      //Creation de la table
      $sql = "CREATE TABLE ".$tablename."(
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          nomIngredient VARCHAR(128) NOT NULL 
          )";

      if ($conn->query($sql) != TRUE)
      {
        
        echo "ERREUR creation table ingredient : " . $conn->error;
      }

      $conn->close();

    }

    /**
     * Crée une table qui stock les favoris des utilisateurs
     * 
     * int id_utilisateur | int id_recette
     * 
     * mysqli
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les favoris des utilisateurs
     * @return void
     */
    function createTableFavoris(string $servername, string $username, string $password, string $dbname, string $tablename)
    {
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      //Creation de la table
      $sql = "CREATE TABLE ".$tablename."(
          id_utilisateur INT(6) UNSIGNED NOT NULL,
          id_recette INT(6) UNSIGNED NOT NULL,
          FOREIGN KEY(id_recette) REFERENCES recettes(id),
          FOREIGN KEY(id_utilisateur) REFERENCES personne(id),
          PRIMARY KEY (id_utilisateur,id_recette)
          
          )";

      if ($conn->query($sql) != TRUE)
      {
        
        echo "ERREUR creation table Favoris: " . $conn->error;
      }

      $conn->close();

    }

    /**
     * Crée une table qui stock les utilisateurs
     * 
     * int id | varchar mail | varchar mot de passe
     * 
     * mysqli
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les utilisateurs 
     * @return void
     */
    function createTableUtilisateur(string $servername, string $username, string $password, string $dbname, string $tablename)
    {
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      //Creation de la table
      $sql = "CREATE TABLE ".$tablename."(
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          mail VARCHAR(128) NOT NULL,
          pwd VARCHAR(128) NOT NULL  
          )";

      if ($conn->query($sql) != TRUE)
      {
        
        echo "ERREUR creation table Utilisateur: " . $conn->error;
      }

      $conn->close();

    }

    /**
     * Crée une table stock qui les recettes
     * 
     * int id_utilisateur | int id_recette
     * 
     * mysqli
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les ingredient qu'on veut créer
     * @return void
     */
    function createTableRecette(string $servername, string $username, string $password, string $dbname, string $tablename)
    {
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      //Creation de la table
      $sql = "CREATE TABLE ".$tablename."(
          id INT(6) UNSIGNED PRIMARY KEY,
          titre VARCHAR(512) NOT NULL,
          preparation VARCHAR(1024) NOT NULL
          )";

      if ($conn->query($sql) != TRUE)
      {
        echo "ERREUR creation table recette : " . $conn->error;
        echo "<br>";
      }

      $conn->close();

    }

    
    /**
     * Crée une table stock qui les ingredient requis pour les recettes
     * 
     * int idIngredient | int idRecette | varchar quantity
     * 
     * mysqli
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les ingredient requis pour les recettes
     * @return void
     */
    function createTableIngredientPourRecettes(string $servername, string $username, string $password, string $dbname, string $tablename)
    {
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      //Creation de la table
      $sql = "CREATE TABLE ".$tablename."(
          idIngredient INT(6) UNSIGNED,
          idRecette INT(6) UNSIGNED,
          quantity VARCHAR(128) NOT NULL,
          FOREIGN KEY(idIngredient) REFERENCES ingredient(id),
          FOREIGN KEY(idRecette) REFERENCES recettes(id)
          )";

      if ($conn->query($sql) != TRUE)
      {
        echo "ERREUR creation table recette : " . $conn->error;
        echo "<br>";
      }

      $conn->close();

    }


    /**
     * Crée une table stock qui les sous-categories pour une categorie/ingredient
     * 
     * int idProduit | int sousCategorieId
     * 
     * mysqli
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les sous-categories pour une categorie/ingredient
     * @return void
     */
    function createTableSousCategorie(string $servername, string $username, string $password, string $dbname, string $tablename)
    {
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      //Creation de la table
      $sql = "CREATE TABLE ".$tablename."(
          idProduit INT(6) UNSIGNED ,
          sousCategorieId INT(6) UNSIGNED,
          FOREIGN KEY(idProduit) REFERENCES ingredient(id),
          FOREIGN KEY(sousCategorieId) REFERENCES ingredient(id)
          )";

      if ($conn->query($sql) != TRUE)
      {
        echo "ERREUR creation table sous catégorie: " . $conn->error;
        echo "<br>";
      }

      $conn->close();

    }

        
    /**
     * Fonction qui peuple la table qui stock les ingredients à partir d'une racine
     *
     * @param  mixed $currentCategory racine
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les sous-categories pour une categorie/ingredient
     * @param  mixed $dbname nom de la base de donnée
     * @return void
     */
    function peuplerIngredients(string $currentCategory,string $servername, string $username, string $password, string $dbname, $tablename)
    {
      //echo $currentCategory."<br>";

      $id = 1;
      $subCategories = sousCategorie($currentCategory);

      //print_r($subCategories);
      //echo "<br>";

      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      
      //On verifie si on a déjà ajouté la categorie/ingredient qu'on balaye dans la bdd
      $query1 = "SELECT * FROM ".$tablename." WHERE nomIngredient = '".$conn->escape_string($currentCategory)."'";


      
      if ($result = $conn->query($query1)) {
        //Si la categorie/ingredient ne se trouve pas dans la categorie
        if($result->num_rows === 0)
        {
          //On cherche le dernier ID
          $query2 = "SELECT MAX(id) AS maxID FROM ".$tablename;
          if ($result = $conn->query($query2))
          {
            
            $row = $result->fetch_assoc();
            if($result->num_rows > 0)
            {
              $id = $row['maxID'] + 1;
              
            }

            //On insert la categorie/ingredient avec l'id qu'il faut dans la bdd
            $query3 = "INSERT INTO ".$tablename." (id, nomIngredient) VALUES (".$id.", '".$conn->escape_string($currentCategory)."')";

            //echo $query3."<br>";

            if (!$conn->query($query3))
            {
              echo " <br> Erreur: " . $query3 . "<br>" . $conn->error;
            }

          }
          
        }
        /* Libération des résultats */
        $result->free();
      }

      $conn->close();



      //Si ce qu'on balaye est une categoire ( = a au moins 1 sous categorie)
      if($subCategories!=-1)
      {        
        //Si on ne traite pas la categorie Aliment
        if(array_key_exists('super-categorie',$subCategories))
        {
          for($i=0;$i<sizeof($subCategories)-2;$i++)
          {
            if(sousCategorie($currentCategory))
            
            peuplerIngredients($subCategories[$i], $servername, $username, $password, $dbname, $tablename);
          }
        }
        //sinon
        else{
          //On fait une recursion chaque sous categorie/ingredient de la categorie qu'on balaye
          for($i=0;$i<sizeof($subCategories)-1;$i++)
          {
            if(sousCategorie($currentCategory))
            
            peuplerIngredients($subCategories[$i], $servername, $username, $password, $dbname, $tablename);
          }
        }
        
      }
      else{
        //echo "CAAAAAAAAAAAAAAATEGORIE".$currentCategory;
      }
    }


        
    /**
     * Fonction qui peuple la table qui stock des sous categories
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les sous-categories pour une categorie/ingredient
     * @param  mixed $dbname nom de la base de donnée
     * @return void
     */
    function peuplerSousCategorie(string $servername, string $username, string $password, string $dbname, string $tablename)
    {

      include "Donnees.inc.php";

      $id = 1;

      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }



      $query = "SELECT * FROM ingredient";

      $i=0;

      if ($result = $conn->query($query))
      {
        print_r($result);
        //Pour chaque ingredient/Category dans la table ingredient
        while ($row = $result->fetch_assoc()) 
        {
            $nomIngredient = $row['nomIngredient'];
            //si l'élément a au moins une sous categorie
            if(array_key_exists('sous-categorie', $Hierarchie[$nomIngredient]))
            {
              $ingredientSousCategorie_key = array_keys($Hierarchie[$nomIngredient]['sous-categorie']);
              //pour chaque sous categorie
              foreach ($ingredientSousCategorie_key as &$value) {
                $sousCategorie = $Hierarchie[$nomIngredient]['sous-categorie'][$value];
                
                //On cherche l'id de la sous categorie
                $rechercheIdSouCategorie = "SELECT id FROM ingredient WHERE nomIngredient = \"".$sousCategorie."\"";
                

                if (!$result2 = $conn->query($rechercheIdSouCategorie))
                {
                  echo " <br> ERREUR: " . $rechercheIdSouCategorie . "<br>" . $conn->error;
                }
                $ligne = $result2->fetch_assoc();
                

                
                //$ligne = $result->fetch_assoc();
                $idSousCategorie = $ligne['id'];
                //$idSousCategorie = 1;

                //On insert dans la table ingredientsouscategorie la ligne      id de l'element qu'on balaye | id qu'on vient de trouver
                $insertionSousCategorie = "INSERT INTO ingredientsouscategorie (idProduit, sousCategorieId) VALUES (".$row['id'].", ".$idSousCategorie.")";
                if (!$conn->query($insertionSousCategorie))
                {
                  echo " <br> Erreur: " . $insertionSousCategorie . "<br>" . $conn->error;
                }
              }
              
            }
            //Si l'element qu'on balaye n'a pas de sous categorie
            //alors on insert la ligne        id de l'element qu'on balaye | NULL
            else{
              $insertionSousCategorie = "INSERT INTO ingredientsouscategorie (idProduit, sousCategorieId) VALUES (".$row['id'].", NULL)";
              if (!$conn->query($insertionSousCategorie))
              {
                echo " <br> Erreur: " . $insertionSousCategorie . "<br>" . $conn->error;
              }
            }
        }
    
        /* Libération des résultats */
        $result->free();
      }
      $conn->close();
    }


        
    /**
     * Fonction qui peuple la table qui stock les recettes et la table ingredientPourRecette
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $tablename nom de la table qui stock les recettes
     * @param  mixed $dbname nom de la base de donnée
     * @return void
     */
    function peuplerRecettes(string $servername, string $username, string $password, string $dbname, string $tablename)
    {
      $idRecette = 1;
      include "Donnees.inc.php";
      
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }

      //On retrouve l'id Max des id Recettes
      $query1 = "SELECT MAX(id) AS maxID FROM recettes";
      
      //Pour chaque recettes dans Donnees.inc.php
      foreach($Recettes as &$value){

        $quantity = preg_split("/\|/",$value['ingredients']);
        if ($result = $conn->query($query1))
        {
          $row = $result->fetch_assoc();
          if($result->num_rows > 0)
          {
            $idRecette = $row['maxID'] + 1;
          }
          //Insertion de la ligne idRecette | titreRecette | preparationRecette dans la table recette
          $query2 = "INSERT INTO recettes (id, titre, preparation) VALUES (".$idRecette.", '".$conn->escape_string($value['titre'])."', '".$conn->escape_string($value['preparation'])."')";
          if (!$conn->query($query2))
          {
            echo " <br> Erreur: " . $query2 . "<br>" . $conn->error;
          }
          $i=0;
          //Pour chaque ingredient dans la recette
          foreach($value['index'] as &$nomIngredient)
          {
            //On retrouve l'id de l'ingredient
            $query3 = "SELECT id FROM ingredient WHERE nomIngredient = '".$nomIngredient."'";
            if ($result = $conn->query($query3))
            {
              $row = $result->fetch_assoc();
              $idIngredient = $row['id'];
              
              //Insertion de la ligne idIngredient | idRecette | quantity dans la table recette
              $query4 = "INSERT INTO ingredientpourrecette (idIngredient,	idRecette,	quantity) VALUES (".$idIngredient.",".$idRecette.",'".$conn->escape_string($quantity[$i])."')";
              if (!$conn->query($query4))
              {
                echo " <br> Erreur: " . $query4 . "<br>" . $conn->error;
              }
            }
            $i++;
          }
        }
      }
      $conn->close();
    }

    
    /**
     * Fonction drop les tables crée ( = drop de la BDD)
     *
     * @param  mixed $servername le nom du serveur
     * @param  mixed $username nom d'utilisateur pour se connecter au serveur
     * @param  mixed $password mot de passe pour se connecter au serveur
     * @param  mixed $dbname nom de la base de donnée
     * @return void
     */
    function dropTables(string $servername, string $username, string $password, string $dbname)
    {
      //Connection à la BDD
      $conn = new mysqli($servername, $username, $password, $dbname);
      // Check connection
      if ($conn->connect_error) 
      {
        die("Erreur connexion au serveur" . $conn->connect_error);
      }


      $dropDataBase = "DROP DATABASE IF EXISTS cocktails";
      if ($result = $conn->query($dropDataBase))
      {
        echo "DROP BDD <br>";
      }

      $conn->close();
    }
    

    function sousCategorie(string $categorie){
      include "Donnees.inc.php";
      $array_categorie=array();
      $hierarchie_keys=array_keys($Hierarchie);
      for ($i=0;$i<sizeof($Hierarchie);$i++){ //Pour chaque catégorie
          $nb_categorie=sizeof($Hierarchie[$hierarchie_keys[$i]]); // Nombre de chaque sous categorie
          if (strcmp(array_keys($Hierarchie)[$i],$categorie)==0){ // On filtre la bonne catégorie
              for ($a=0;$a<$nb_categorie;$a++){ // Pour chaque sous categorie
                  $array_categorie=$Hierarchie[$hierarchie_keys[$i]]; // Pour chaque catégorie on a l'array des sous/super categorie
                  $key_categorie = array_keys($array_categorie);
                  if (strcmp($key_categorie[$a],"sous-categorie")==0){ // Si il y a une sous categorie
                      $categorie_keys=array_keys($array_categorie[$key_categorie[$a]]); //Array des clés des sous/super categorie
                      for ($s=0;$s<sizeof($categorie_keys);$s++){ // Pour chaque élement de la sous catégorie
                          $element = $array_categorie[$key_categorie[$a]][$categorie_keys[$s]];
                          array_push($array_categorie,$element);
                      }
                      return $array_categorie;
                  }
                  
              }
          }
      }
      return -1;
    }
    
    


?>