<html>


<link rel="stylesheet" href="css/all.css" type="text/css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>

<!-- Include Twitter Bootstrap and jQuery: -->
<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
 
<!-- Include the plugin's CSS and JS: -->
<script type="text/javascript" src="js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>


<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<?php


session_start();

if (isset($_SESSION['login'])) { // Gestion du header
    if ($_SESSION['login'] = true) {
        include 'navbar/Header_logged.php';
    } else include 'navbar/Header_nolog.php';
} else include 'navbar/Header_nolog.php';

include "donnees/Donnees.inc.php";
include "util_bdd.php";


?>




<script type="text/javascript" src="js/sidebar.js"></script>
<script type="text/javascript" src="js/favoris.js"></script>
<a id="button"></a>
<body>
    <div id="Sidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

        <?php include "Arbre.php"; ?>

    </div>

    <div id="main">
        <div class=".container-fluid">
            <div  class="row">

                <div class="col-sm">

                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#alimentInclude').multiselect({
                                includeResetOption: true,
                                enableFiltering: true,
                                nSelectedText: ' - Aliments inclus',
                                nonSelectedText: 'Tout',
                                maxHeight: 200,
                                enableCaseInsensitiveFiltering: true,
                                onChange: function(options, selected)
                                {
                                    var caseUpdated = $(options).val();
                                    if(selected)
                                    {
                                        $("#alimentExclude").multiselect('deselect', caseUpdated);
                                    }                                    
                                }
                            });
                        });
                    </script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#alimentExclude').multiselect({
                                includeResetOption: true,
                                enableFiltering: true,
                                nSelectedText: ' - Aliments exclus',
                                nonSelectedText: 'Rien',
                                maxHeight: 200,
                                enableCaseInsensitiveFiltering: true,
                                onChange: function(options, selected)
                                {
                                    var caseUpdated = $(options).val();
                                    if(selected)
                                    {
                                        $("#alimentInclude").multiselect('deselect', caseUpdated);
                                    }                                    
                                }
                            });
                        });
                    </script>
                    <form id="filtre_form" action="index.php" method="post" class="p-3 m-0 mb-2 border border-primary form-inline">
                        <label class="m-2">Avec</label>
                        <select id="alimentInclude" name="include[]" multiple="multiple">
                            <?php
                                $liste_hierarchy=array();
                                getHierarchyKeys();
                                $liste_hierarchy=getHierarchyKeys();
                                for ($i=0;$i<sizeof($liste_hierarchy);$i++){
                                    echo "<option value=\"".$liste_hierarchy[$i]."\">".$liste_hierarchy[$i]."</option>";
                                }

                            ?>
                        </select>
                        <label class="m-2">Sans</label>
                        <select id="alimentExclude" name="exclude[]" multiple="multiple">
                            <?php
                                $liste_hierarchy=array();
                                getHierarchyKeys();
                                $liste_hierarchy=getHierarchyKeys();
                                for ($i=0;$i<sizeof($liste_hierarchy);$i++){
                                    echo "<option value=\"".$liste_hierarchy[$i]."\">".$liste_hierarchy[$i]."</option>";
                                }

                            ?>
                        </select>
                        
                        <button id="btn_filtre" type="submit" class="btn btn-primary">Rechercher</button>
                    </form>
                </div>
                <div class="col-sm">
                    <form id="filtre_form" action="index.php" method="post" class="p-3 m-0 mb-2 border border-primary form-inline">
                        <input list="recettes"name="filtrage_nom" class="form-control" placeholder="Nom cocktail">
                        <datalist id="recettes">
                                <?php
                                $liste_recette=array();
                                $liste_recette=getRecettes();
                                for ($i=0;$i<sizeof($liste_recette);$i++){
                                    echo "<option value='".$liste_recette[$i]."'>";
                                }

                                ?>
                        </datalist>
                        <button id="btn_filtre" type="submit" class="btn btn-primary">Rechercher</button>
                    </form>
                </div>
            </div>
        </div>   

        <div class="list-group">

            <?php




            


            $favoris=false;
            

            if(isset($_POST['include'])||isset($_POST['exclude']))
            {
                affichage_by_idRecetteListe(calculePertinenceOrderedList());
            }
            else
            {
                if (isset($_GET["favoris"]))
                {
                    if (isset($_SESSION['login']))
                    {
                        if ($_SESSION['login'] = true)
                        {
                            affichageFavoris();
                        }
                        else
                        {
                            if(isset($_SESSION["favoris"]))
                            {
                                affichage_by_idRecetteListe($_SESSION["favoris"]);
                            }
                        }
                    }
                    else
                    {
                        if(isset($_SESSION["favoris"]))
                        {
                            affichage_by_idRecetteListe($_SESSION["favoris"]);
                        }
                    }
                }
                else{
                    if (isset($_GET["ingredientName"]))
                    {
                        affichage_liste_filtre_by_ingredient($_GET["ingredientName"]);
                    }else{
                        if (isset($_POST["filtrage_nom"])) {
                            affichage_liste_filtre($_POST["filtrage_nom"],$favoris,"index");
                        } else affichage_liste_filtre("",$favoris,"index");
                    }
                }
            }

            

            ?>



        </div>

    </div>
</body>

</html>
