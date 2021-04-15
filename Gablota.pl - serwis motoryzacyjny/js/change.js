const list = document.querySelector(".listatypow");
const ladownosc = document.getElementById("ladownosc");
const prawko = document.getElementById("prawko");
const typmoto = document.getElementById("typmoto");
const typdost = document.getElementById("typdost");
const miejsca = document.getElementById("miejsca");
const nadwozie = document.getElementById("nadwozie");
const naped = document.getElementById("naped");

function zmiana() {
  const opcja = list.value;
  if (opcja == "osobowe") {
    ladownosc.style.display = "none";
    prawko.style.display = "none";
    typdost.style.display = "none";
    typmoto.style.display = "none";
    miejsca.style.display = "block";
    nadwozie.style.display = "block";
    naped.style.display = "block";
  } else if (opcja == "motocykle") {
    ladownosc.style.display = "none";
    prawko.style.display = "block";
    typdost.style.display = "none";
    typmoto.style.display = "block";
    miejsca.style.display = "block";
    nadwozie.style.display = "none";
    naped.style.display = "none";
  } else if (opcja == "dostawcze") {
    ladownosc.style.display = "block";
    prawko.style.display = "none";
    typdost.style.display = "block";
    typmoto.style.display = "none";
    miejsca.style.display = "none";
    nadwozie.style.display = "none";
    naped.style.display = "block";
  }
}

list.addEventListener("click", zmiana);