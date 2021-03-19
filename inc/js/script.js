
//Event Listeners

// Btn ajouter un produit.
let btn_add = document.getElementById("btn_addProduct");
if(btn_add)
{
    btn_add.addEventListener("click", function(){
    let form = document.getElementsByClassName("form_addProduct");
    form[0].classList.toggle("hidden");
  });
}

// bouton confirmer l'ajout.
let btn_send_product = document.getElementById("btn_SendNewProduct")
if(btn_send_product)
  btn_send_product.addEventListener("click", addProduit);

// Boutons supprimer.
let produits_delete = document.getElementsByClassName("removeProduct");
for(let i=0; i < produits_delete.length; i++)
    produits_delete[i].addEventListener("click", function(){removeProduit(i)});

// Champs qte d'achatsView
let quantites = document.getElementsByClassName("qty");
for(let i=0; i < quantites.length; i++)
    quantites[i].addEventListener("change", function(){calculer_total(i)});

// Google auth.
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
// Google signOut 
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
  event.preventDefault();

  // get elements by id -> pour chacun de input du formulaire
  let produit = document.getElementById("produit").value;
  let categorie = document.getElementById("categorie").value; 
  let description = document.getElementById("description").value;
  let prix = document.getElementById("prix").value;

  let post_values = "action=AjoutProduit&categorie="+categorie+"&produit="+produit+"&description="+description+"&prix="+prix;
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
/**
 * Fonction qui calcule et affiche les champs Total de chacune des lignes dans achatView.
 */
function calculer_total(index)
{
  // get elements by id -> pour chacun de input du formulaire
  let qty = parseInt(quantites[index].value);

  let p = document.getElementsByClassName("prix");
  let prix = parseFloat(p[index].innerHTML);
  

  let total = qty * prix;
  total = total.toFixed(2);

  let total_row = document.getElementsByClassName("achat_total");
  total_row[index].innerHTML = total;

  // Update du grand total
  calculer_grand_total()
}

/**
 * Calcule le grand total dans achatsView.
 * @returns 
 */
function calculer_grand_total()
{
    let grand_total = document.getElementById("achat_grand_total");
    let value = 0;
    let totals = document.getElementsByClassName("achat_total");
    for(let i=0; i < totals.length; i++)
    {
      if(parseFloat(totals[i].innerHTML) >= 0)
        value = value + parseFloat(totals[i].innerHTML);
    }
    value=value.toFixed(2);
    grand_total.innerHTML = value;

    return value;
}

/**
 * Renvoit le nom d'un produit selon l'<'index.
 * @param {*} index 
 * @returns 
 */
function get_product_name(index)
{
  let names = document.getElementsByClassName("product_name");

  return names[index].innerHTML;
}

/**
 * Fonction qui renvoit le prix d<un item
 * @param {*} index 
 * @returns 
 */
function get_product_price(index)
{
  let p = document.getElementsByClassName("prix");
  let prix = parseFloat(p[index].innerHTML);

  return prix;
}

/**
 * Fonction qui renvoit le prix d'un item.
 * @param {*} index 
 * @returns 
 */
function get_product_quantity(index)
{
  let quantites = document.getElementsByClassName("qty");
  let q = quantites[index].value;  

  return q;
}

function get_product_id(index)
{
  let quantites = document.getElementsByClassName("qty");
  let id = quantites[index].getAttribute("data-id_produit");  

  return id;
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