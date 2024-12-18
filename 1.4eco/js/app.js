function iniciarApp() {
  const resultado = document.querySelector("#resultado");

  const favoritosDiv = document.querySelector("#resultado"); // Usa el id directamente
  if (favoritosDiv) {
    obtenerFavoritos();
  }

  const modal = new bootstrap.Modal("#modal", {});

  // Llamar directamente a la categoría "Vegetarian" al iniciar
  obtenerRecetasVegetarian();

  function obtenerRecetasVegetarian() {
    const url = `https://www.themealdb.com/api/json/v1/1/filter.php?c=Vegetarian`;
    fetch(url)
      .then((respuesta) => respuesta.json())
      .then((resultado) => mostrarRecetas(resultado.meals));
  }

  function mostrarRecetas(recetas = []) {
    limpiarHTML(resultado);

    const heaging = document.createElement("H2");
    heaging.classList.add("text-center", "text-black", "my-5");
    heaging.textContent = recetas.length
      ? "Recetas Vegetarianas"
      : "No hay Recetas";
    resultado.appendChild(heaging);

    // Iterar en los resultados
    recetas.forEach((receta) => {
      const { idMeal, strMeal, strMealThumb } = receta;

      const recetaContenedor = document.createElement("DIV");
      recetaContenedor.classList.add("col-md-4");

      const recetaCard = document.createElement("DIV");
      recetaCard.classList.add("card", "mb-4");

      const recetaImagen = document.createElement("IMG");
      recetaImagen.classList.add("card-img-top");
      recetaImagen.alt = `Imagen de la receta ${strMeal}`;
      recetaImagen.src = strMealThumb;

      const recetaCardBody = document.createElement("DIV");
      recetaCardBody.classList.add("card-body");

      const recetaHeading = document.createElement("H3");
      recetaHeading.classList.add("card-title", "mb-3");
      recetaHeading.textContent = strMeal;

      const recetaButton = document.createElement("BUTTON");
      recetaButton.classList.add("btn", "btn-danger", "w-100");
      recetaButton.textContent = "Ver Receta";
      recetaButton.onclick = function () {
        seleccionarReceta(idMeal);
      };

      // Inyectar en el HTML
      recetaCardBody.appendChild(recetaHeading);
      recetaCardBody.appendChild(recetaButton);

      recetaCard.appendChild(recetaImagen);
      recetaCard.appendChild(recetaCardBody);

      recetaContenedor.appendChild(recetaCard);

      resultado.appendChild(recetaContenedor);
    });
  }

  function seleccionarReceta(id) {
    const url = `https://www.themealdb.com/api/json/v1/1/lookup.php?i=${id}`;
    fetch(url)
      .then((respuesta) => respuesta.json())
      .then((resultado) => mostrarRecetaModal(resultado.meals[0]));
  }

  function mostrarRecetaModal(receta) {
    const { idMeal, strInstructions, strMeal, strMealThumb } = receta;

    // Añadir contenido al modal
    const modalTitle = document.querySelector(".modal .modal-title");
    const modalBody = document.querySelector(".modal .modal-body");

    modalTitle.textContent = strMeal;
    modalBody.innerHTML = `
          <img class="img-fluid" src="${strMealThumb}" alt="receta ${strMeal}">
          <h3 class="my-3">Instrucciones</h3>
          <p>${strInstructions}</p>
          <h3 class="my-3">Ingredientes y Cantidades</h3>
      `;

    const listGroup = document.createElement("UL");
    listGroup.classList.add("list-group");

    // Mostrar cantidades e ingredientes
    for (let i = 1; i <= 20; i++) {
      if (receta[`strIngredient${i}`]) {
        const ingrediente = receta[`strIngredient${i}`];
        const cantidad = receta[`strMeasure${i}`];

        const ingredienteLi = document.createElement("LI");
        ingredienteLi.classList.add("list-group-item");
        ingredienteLi.textContent = `${ingrediente} - ${cantidad}`;

        listGroup.appendChild(ingredienteLi);
      }
    }

    modalBody.appendChild(listGroup);

    const modalFooter = document.querySelector(".modal-footer");
    limpiarHTML(modalFooter);

    // Botones de cerrar y favorito
    const btnFavorito = document.createElement("BUTTON");
    btnFavorito.classList.add("btn", "btn-danger", "col");
    btnFavorito.textContent = existeStorage(idMeal)
      ? "Eliminar Favorito"
      : "Guardar Favorito";

    // LocalStorage
    btnFavorito.onclick = function () {
      if (existeStorage(idMeal)) {
        eliminarFavorito(idMeal);
        btnFavorito.textContent = "Guardar Favorito";
        mostrarToast("Eliminado Correctamente");
        return;
      }

      agregarFavorito({
        id: idMeal,
        titulo: strMeal,
        img: strMealThumb,
      });
      btnFavorito.textContent = "Eliminar Favorito";
      mostrarToast("Agregado Correctamente");
    };

    const btnCerrarModal = document.createElement("BUTTON");
    btnCerrarModal.classList.add("btn", "btn-secondary", "col");
    btnCerrarModal.textContent = "Cerrar";
    btnCerrarModal.onclick = function () {
      modal.hide();
    };

    modalFooter.appendChild(btnFavorito);
    modalFooter.appendChild(btnCerrarModal);

    // Muestra el modal
    modal.show();
  }

  function agregarFavorito(receta) {
    const datos = new FormData();
    datos.append("id_receta", receta.id);
    datos.append("titulo_receta", receta.titulo);
    datos.append("imagen_receta", receta.img);

    fetch("guardar_favorito.php", {
        method: "POST",
        body: datos,
    })
    .then((respuesta) => respuesta.json())
    .then((resultado) => {
        if (resultado.status === "success") {
            mostrarToast("Agregado correctamente");
        } else {
            mostrarToast("Error al guardar favorito");
            console.error(resultado.message);
        }
    })
    .catch((error) => console.error("Error en el fetch:", error));
}



  function eliminarFavorito(id) {
    const favoritos = JSON.parse(localStorage.getItem("favoritos")) ?? [];
    const nuevosFavoritos = favoritos.filter((favorito) => favorito.id !== id);
    localStorage.setItem("favoritos", JSON.stringify(nuevosFavoritos));
  }

  function existeStorage(id) {
    const favoritos = JSON.parse(localStorage.getItem("favoritos")) ?? [];
    return favoritos.some((favorito) => favorito.id === id);
  }

  function mostrarToast(mensaje) {
    const toastDiv = document.querySelector("#toast");
    const toastBody = document.querySelector(".toast-body");
    const toast = new bootstrap.Toast(toastDiv);
    toastBody.textContent = mensaje;
    toast.show();
  }

  function obtenerFavoritos() {
    fetch("obtener_favoritos.php")
        .then((respuesta) => respuesta.json())
        .then((favoritos) => {
            console.log("Favoritos desde la base de datos:", favoritos);
            if (favoritos.length) {
                mostrarRecetas(favoritos);
            } else {
                const noFavoritos = document.createElement("P");
                noFavoritos.textContent = "No hay favoritos aún";
                noFavoritos.classList.add("fs-4", "text-center", "font-bold", "mt-5");
                favoritosDiv.appendChild(noFavoritos);
            }
        })
        .catch((error) => console.error("Error al obtener favoritos:", error));
}



  function limpiarHTML(selector) {
    while (selector.firstChild) {
      selector.removeChild(selector.firstChild);
    }
  }
}

document.addEventListener("DOMContentLoaded", iniciarApp);
