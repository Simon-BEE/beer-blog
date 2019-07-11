function calcPrice(obj, id, originalPrice)
{
	var qty = obj.value;

	var pHT = originalPrice;

	pHT = (pHT * qty);
	var pTTC =  pHT * 1.2;

	document.getElementById('PHT_'+id).innerHTML = String(pHT.toFixed(2)).replace('.', ',')+"€";
	document.getElementById('PTTC_'+id).innerHTML = String(pTTC.toFixed(2)).replace('.', ',')+"€";
}

var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
	acc[i].addEventListener("click", function() {
		this.classList.toggle("active");
		var panel = this.nextElementSibling;
		if (panel.style.display === "block") {
			panel.style.display = "none";
		} else {
			panel.style.display = "block";
		}
	});
}

function chooseAddress(id, user_id){	
	$.post('/shop/choice', {id : id, user_id : user_id}, function(data){
		if (data !== 'error') {
			lines = JSON.parse(data);
			for (const [key, item] of Object.entries(lines)) {
				console.log(key + " " + item);
				document.getElementById(key).value = item;
			}
		}else{
			alert("Une erreur s'est produit!");
		}
		console.log(data);
	})


	$('div.choix').on("click",function(){
		if(!$(this).hasClass("bg-secondary"))
		{
			$('div.choix').removeClass("bg-secondary");
			$(this).addClass("bg-secondary");
		}
		else
		{
			$('div.choix').removeClass("bg-secondary");
		}
	});
	
}

function addBasket(id) {
	var beer_id = document.getElementsByName('beer_id'+id)[0].value;
	var beerQTY = document.getElementsByName('qty['+id+']')[0].value;
	
	$.post('/shop/add', {beer_id:beer_id, beerQTY : beerQTY}, function(data){
		if (data === "ok") {
			console.log("tudo bem");
			alert("Votre produit a bien été ajouté à votre panier");
		}else{
			alert('Erreur insertion panier');
		}
	})
}

function upBasket(id) {
	var beer_id = document.getElementsByName('beer_id'+id)[0].value;
	var beerQTY = document.getElementsByName('qty['+id+']')[0].value;
	var line = document.getElementById('line'+id).innerHTML;
	beerQTY = parseInt(beerQTY) + parseInt(line);
	
	$.post('/shop/update', {beer_id:beer_id, beerQTY : beerQTY}, function(data){
		if (data === "ok") {
			console.log("tudo bem");
			$('#line'+id).html(beerQTY);
			
		}else{
			alert('Erreur insertion panier');
		}
	})
}

function lessBasket(id, lineId) {
	var beerQTY = document.getElementsByName('qty['+id+']')[0].value;
	var beer_id = document.getElementsByName('beer_id'+id)[0].value;
	var line = document.getElementById('line'+id).innerHTML;
		beerQTY = parseInt(line) - parseInt(beerQTY);
		if (beerQTY > 0) {
			$.post('/shop/update', {beer_id:beer_id, beerQTY : beerQTY}, function(data){
				if (data === "ok") {
					console.log("tudo bem");
					$('#line'+id).html(beerQTY);
					
				}else{
					alert('Erreur insertion panier');
				}
			})
		}else{
			deleteLine(lineId);
		}
}

function deleteLine(id) {
	if (confirm("Etes-vous sûr de vouloir supprimer cette ligne ?")) {
		$.post('/shop/delete', {id:id}, function(data){
			//console.log(data);
			if (data === "ok") {
				console.log("tudo bem");
				alert("Votre produit a bien été retiré de votre panier");
				$('#test'+id).remove();
			}else{
				alert('Erreur suppression du panier');
			}
		})
	}
}