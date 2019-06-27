function calcPrice(obj, id, originalPrice)
{
	var qty = obj.value;

	var pHT = originalPrice;

	pHT = (pHT * qty);
	var pTTC =  pHT * 1.2;

	document.getElementById('PHT_'+id).innerHTML = String(pHT.toFixed(2)).replace('.', ',')+"€";
	document.getElementById('PTTC_'+id).innerHTML = String(pTTC.toFixed(2)).replace('.', ',')+"€";
	console.log(pTTC);
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