function addToFavourite(idRecette, idUser, idButton)
{
    document.getElementById(idButton).className = "btn btn-danger";
    document.getElementById(idButton).innerHTML = "Retirer des Favoris";
    document.getElementById(idButton).setAttribute("onclick","removeFromFavourite('"+idRecette+"','"+idUser+"','"+idButton+"')");

    console.log("removeFromFavourite("+idRecette+","+idUser+","+idButton+")");

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

function removeFromFavourite(idRecette, idUser, idButton, haveToHide)
{

    document.getElementById(idButton).className = "btn btn-success";
    document.getElementById(idButton).innerHTML = "Ajouter au Favoris";
    document.getElementById(idButton).setAttribute("onclick","addToFavourite('"+idRecette+"','"+idUser+"','"+idButton+"')");

    if(haveToHide)
    {
        document.getElementById("row"+idRecette).classList.add('d-none');
    }

    console.log("addToFavourite("+idRecette+","+idUser+","+idButton+")");

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