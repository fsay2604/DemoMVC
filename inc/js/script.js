
//Event Listeners
document.getElementById("btn_addProduct").addEventListener("click", function(){
  let form = document.getElementsByClassName("form_addProduct");
  form[0].classList.toggle("hidden");
});

document.getElementById("btn_SendNewProduct").addEventListener("click", addProduit);

// delete button
let produits_delete = document.getElementsByClassName("removeProduct");
for(let i=0; i < produits_delete.length; i++)
    produits_delete[i].addEventListener("click", function(){removeProduit(i)});


function onSignIn(googleUser) 
{
    var id_token = googleUser.getAuthResponse().id_token;
    var xhr = new XMLHttpRequest();

    // Permet de specifier ce que l'on veut qui s'execute lorsque la requete se termine. Important d'etre a cette position (avant la requete)
    xhttp.onreadystatechange = function()
    {
      if(this.readyState == 4 && this.status == 200)    // Si l'etat est a 4 et que son statut est a 200
        window.location.replace("http://localhost/mvc/index.php");  // Simulate an HTTP redirect:
    }

    xhr.open('POST', 'http://localhost/mvc/index.php?action=authentifier');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      console.log('Signed in as: ' + xhr.responseText);
    };
    xhr.send('idtoken=' + id_token);
}

function signOut()
{
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    console.log('User signed out.');
    });
}

// Fonction pour ajouter un produit
function addProduit(event)
{
  console.log("I am clicked");  //debugging 
  event.preventDefault();

  // get elements by id -> pour chacun de input du formulaire
  let produit = document.getElementById("produit").value;
  let categorie = document.getElementById("categorie").value; 
  let description = document.getElementById("description").value;

  let post_values = "action=AjoutProduit&categorie="+categorie+"&produit="+produit+"&description="+description;
  var xhttp = new XMLHttpRequest();

  // Si l'etat est a 4 et que son statut est a 200
  xhttp.onreadystatechange = function()
  {
    if(xhttp.readyState == 4 && xhttp.status == 200)
    {
      alert("Produit Ajouter avec succes.");                  
      let form = document.getElementsByClassName("form_addProduct");  
      form[0].classList.toggle("hidden"); // hide form
    }
  }

  xhttp.open('POST', 'http://localhost/mvc/index.php');
  xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhttp.send(post_values);
}

function removeProduit(index)
{
  console.log("Removing product...");  //debugging 

  if(confirm("Etes-vous certain de vouloir supprimer ce produit?"))
  {
    let produit = document.getElementsByClassName("removeProduct");
    console.log("Product id: " +produit[index].value);
    post_values = "action=DeleteProduit&id_produit=" + produit[index].value;
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function()
    {
      if(xhttp.readyState == 4 && xhttp.status == 200)
        alert("Produit Supprimer avec succes.");      
        window.location.replace("http://localhost/mvc/produits");  // Simulate an HTTP redirect:window.location.replace("http://localhost/mvc/index.php");  // Simulate an HTTP redirect:             
    }

    xhttp.open('POST', 'http://localhost/mvc/index.php');
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhttp.send(post_values);
  }
}




// Fonction pour tester le ajax
function ajaxVersionGet()
{
  console.log("I am clicked");
  var xhttp = new XMLHttpRequest();

  // Si l'etat est a 4 et que son statut est a 200
  xhttp.onreadystatechange = function()
  {
    if(this.readyState == 4 && this.statut == 200)
      window.location.href = "http://localhost/mvc/index.php";      // Simulate an HTTP redirect:
  }

  xhttp.open('GET', 'http://localhost/mvc/index.php?action=testAjax');
  xhttp.send();         // param avec post
}

// Fonction pour tester le ajax
function ajaxVersionPost()
{
  console.log("I am clicked");
  var xhttp = new XMLHttpRequest();

  // Si l'etat est a 4 et que son statut est a 200
  xhttp.onreadystatechange = function()
  {
    if(this.readyState == 4 && this.statut == 200)
      window.location.replace("http://localhost/mvc/index.php");      // Simulate an HTTP redirect:
  }

  xhttp.open('POST', 'http://localhost/mvc/index.php');
  xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhttp.send('action=testAjax&nom=fc');         // param avec post
}