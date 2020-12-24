
function openNav() {
    if (document.getElementById("Sidebar").offsetWidth==null || document.getElementById("Sidebar").offsetWidth==0){ // Si le sidebar est fermé
      document.getElementById("Sidebar").style.width = "25%";
      document.getElementById("main").style.marginLeft = "25%";
    }else{
      document.getElementById("Sidebar").style.width = "0";
      document.getElementById("main").style.marginLeft = "0";
    }
  }
  
  function closeNav() {
    document.getElementById("Sidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
  }
  
  function onClickAliment(nomIngredient)
  {

    console.log(nomIngredient);
    
    window.location = "Accueil.php?ingredientName="+nomIngredient;

  }


