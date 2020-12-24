<?php


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
    $tableName = "IngredientHierarchy";
    
    dropTables($servername, $username, $password, $dbname);

    createDataBase($servername, $username, $password, $dbname);
    createTableIngredients($servername, $username, $password, $dbname, $tableIngredient);
    createTableSousCategorie($servername, $username, $password, $dbname, $tableSousCategorie);
    createTableRecette($servername, $username, $password, $dbname, $tableRecette);
    createTableIngredientPourRecettes($servername, $username, $password, $dbname, $tableIngredientRecette);

    peuplerIngredients($categoryRacine, $servername, $username, $password, $dbname, $tableIngredient);

    peuplerSousCategorie($servername, $username, $password, $dbname, $tableIngredient);

    peuplerRecettes($servername, $username, $password, $dbname, $tableIngredient); 


    //function peuplerHierarchyIngredients(string $server, string $username, string $pswd, string $dataBaseName, string $currentCategory, int $currentId, int $parentId)
    $categoryRacine = "Aliment";
    //peuplerHierarchyIngredients($servername, $username, $password, $dbname, $categoryRacine, 2, 1);


    function createDataBase(string $servername, string $username, string $password, string $dbname)
    {
        // Create connection
        $conn = new mysqli($servername, $username, $password);
        // Check connection
        if ($conn->connect_error) {
        die("Erreur connexion au serveur: " . $conn->connect_error);
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
          ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

      if ($conn->query($sql) != TRUE)
      {
        
        echo "ERREUR creation table ingredient Hierarchy: " . $conn->error;
      }

      $conn->close();

    }

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
          titre VARCHAR(128) NOT NULL,
          preparation VARCHAR(1024) NOT NULL
          ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      if ($conn->query($sql) != TRUE)
      {
        echo "ERREUR creation table recette : " . $conn->error;
        echo "<br>";
      }

      $conn->close();

    }

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
          )DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      if ($conn->query($sql) != TRUE)
      {
        echo "ERREUR creation table recette : " . $conn->error;
        echo "<br>";
      }

      $conn->close();

    }


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
          ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

      if ($conn->query($sql) != TRUE)
      {
        echo "ERREUR creation table ingredient Hierarchy: " . $conn->error;
        echo "<br>";
      }

      $conn->close();

    }

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



      $query1 = "SELECT * FROM ".$tablename." WHERE nomIngredient = '".$currentCategory."'";


      if ($result = $conn->query($query1)) {

        if($result->num_rows === 0)
        {
          $query2 = "SELECT MAX(id) AS maxID FROM ".$tablename;
          if ($result = $conn->query($query2))
          {
            
            $row = $result->fetch_assoc();
            if($result->num_rows > 0)
            {
              $id = $row['maxID'] + 1;
              
            }

            $query3 = "INSERT INTO ".$tablename." (id, nomIngredient) VALUES (".$id.", '".$currentCategory."')";
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



      if($subCategories!=-1)
      {        
        //echo "SIZEOF ============= ".sizeof($subCategories);

        //echo "SIZEOF ===== ".sizeof($subCategories);
        //print_r($subCategories);
        if(array_key_exists('super-categorie',$subCategories))
        {
          for($i=0;$i<sizeof($subCategories)-2;$i++)
          {
            if(sousCategorie($currentCategory))
            
            peuplerIngredients($subCategories[$i], $servername, $username, $password, $dbname, $tablename);
          }
        }
        else{
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
        while ($row = $result->fetch_assoc()) 
        {
            $nomIngredient = $row['nomIngredient'];
            if(array_key_exists('sous-categorie', $Hierarchie[$nomIngredient]))
            {
              $ingredientSousCategorie_key = array_keys($Hierarchie[$nomIngredient]['sous-categorie']);
              foreach ($ingredientSousCategorie_key as &$value) {
                $sousCategorie = $Hierarchie[$nomIngredient]['sous-categorie'][$value];
                
                $rechercheIdSouCategorie = "SELECT id FROM ingredient WHERE nomIngredient = '".$sousCategorie."'";
                
                $ligne = $result->fetch_assoc();
                $idSousCategorie = $ligne['id'];

                $insertionSousCategorie = "INSERT INTO ingredientsouscategorie (idProduit, sousCategorieId) VALUES (".$row['id'].", ".$idSousCategorie.")";
                if (!$conn->query($insertionSousCategorie))
                {
                  echo " <br> Erreur: " . $insertionSousCategorie . "<br>" . $conn->error;
                }
              }
            }
        }
    
        /* Libération des résultats */
        $result->free();
      }
      $conn->close();
    }

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

      $query1 = "SELECT MAX(id) AS maxID FROM recettes";
      
      
      foreach($Recettes as &$value){
        //Insert dans la table des recettes
        $quantity = preg_split("/\|/",$value['ingredients']);
        if ($result = $conn->query($query1))
        {
          $row = $result->fetch_assoc();
          if($result->num_rows > 0)
          {
            $idRecette = $row['maxID'] + 1;
          }
          $query2 = "INSERT INTO recettes (id, titre, preparation) VALUES (".$idRecette.", '".$conn->escape_string($value['titre'])."', '".$conn->escape_string($value['preparation'])."')";
          if (!$conn->query($query2))
          {
            echo " <br> Erreur: " . $query2 . "<br>" . $conn->error;
          }
          $i=0;
          foreach($value['index'] as &$nomIngredient)
          {
            $query3 = "SELECT id FROM ingredient WHERE nomIngredient = '".$nomIngredient."'";
            if ($result = $conn->query($query3))
            {
              $row = $result->fetch_assoc();
              $idIngredient = $row['id'];
              
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


    //Fonction pour drop la base de donnée
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