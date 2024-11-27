let narradorActivo = localStorage.getItem("narradorActivo") === "true";

function narrarTexto(texto) {
  if (narradorActivo) {
    const narrador = new SpeechSynthesisUtterance(texto);
    narrador.lang = "es-ES";
    window.speechSynthesis.speak(narrador);
  }
}

const elementosNarrables = document.querySelectorAll(".narrable");
elementosNarrables.forEach((elemento) => {
  elemento.addEventListener("mouseover", function () {
    const texto = this.innerText;
    narrarTexto(texto);
  });

  elemento.addEventListener("mouseout", function () {
    window.speechSynthesis.cancel();
  });
});

const toggleNarradorButton = document.getElementById("toggleNarrador");


if (narradorActivo) {
  toggleNarradorButton.innerText = "Apagar narrador";
} else {
  toggleNarradorButton.innerText = "Encender narrador";
}

toggleNarradorButton.addEventListener("click", function () {
  narradorActivo = !narradorActivo; 

  if (narradorActivo) {
    this.innerText = "Apagar narrador";
  } else {
    this.innerText = "Encender narrador";
    window.speechSynthesis.cancel(); 
  }

  localStorage.setItem("narradorActivo", narradorActivo);
});