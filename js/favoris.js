/**
 * addToFavourite
 * 
 * @param {*} idRecette id de la recette à remove des favoris de l'utilisateur
 * @param {*} idUser id de l'utilisateur 
 * @param {*} idButton id du bouton addTo/removeFrom favourite
 */

function addToFavourite(idRecette, idUser, idButton)
{
    //changement de la couleur du bouton du vert vers le rouge
    document.getElementById(idButton).className = "btn btn-danger";
    //changement du texte
    document.getElementById(idButton).innerHTML = "Retirer des Favoris";

    //changement de la fonction onclick de addToFavourite vers removeFromFavourite
    document.getElementById(idButton).setAttribute("onclick","removeFromFavourite('"+idRecette+"','"+idUser+"','"+idButton+"')");

    console.log("removeFromFavourite("+idRecette+","+idUser+","+idButton+")");


    //On supprime via XMLHttpRequest le favoris
    var url = "test/Add_favoris.php";
    var params = "personne="+idUser+"&recette="+idRecette;
    var http = new XMLHttpRequest();

    http.open("GET", url+"?"+params, true);
    http.onreadystatechange = function()
    {
        if(http.readyState == 4 && http.status == 200) {
            //alert(http.responseText);
        }
    }
    http.send(null);

}
/**
 * removeFromFavourite
 * 
 * @param {*} idRecette id de la recette à remove des favoris de l'utilisateur
 * @param {*} idUser id de l'utilisateur 
 * @param {*} idButton id du bouton addTo/removeFrom favourite
 * @param {*} haveToHide si on doit cacher l'affichage de la recette
 */
function removeFromFavourite(idRecette, idUser, idButton, haveToHide)
{

    //changement de la couleur du bouton du rouge vers le vert
    document.getElementById(idButton).className = "btn btn-success";
    //changement du texte
    document.getElementById(idButton).innerHTML = "Ajouter au Favoris";

    //changement de la fonction onclick de removeFromFavourite vers addToFavourite
    document.getElementById(idButton).setAttribute("onclick","addToFavourite('"+idRecette+"','"+idUser+"','"+idButton+"')");

    //Si on doit cacher l'affichage de la recette, alors on la cache
    if(haveToHide)
    {
        document.getElementById("row"+idRecette).classList.add('d-none');
    }

    console.log("addToFavourite("+idRecette+","+idUser+","+idButton+")");


    //On supprime via XMLHttpRequest le favoris
    var url = "test/Delete_favoris.php";
    var params = "personne="+idUser+"&recette="+idRecette;
    var http = new XMLHttpRequest();

    http.open("GET", url+"?"+params, true);
    http.onreadystatechange = function()
    {
        if(http.readyState == 4 && http.status == 200) {
            //alert(http.responseText);
        }
    }
    http.send(null);



}