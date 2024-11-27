<?php
include 'encabezado.php';

// Incluye tu archivo de conexión a la base de datos
include 'conexion.php';

// Consulta los favoritos de la base de datos
$sql = "SELECT id_receta, titulo_receta, imagen_receta FROM favoritos";
$resultado = $conn->query($sql);

$favoritos = [];
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $favoritos[] = $row;
    }
}

// Convierte los favoritos en JSON para JavaScript
echo "<script>const favoritos = " . json_encode($favoritos) . ";</script>";
?>

<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <!-- Modal contenido -->
</div>

<main class="mt-5 container-lg">
    <h2 class="text-center fs-1">Favoritos</h2>
    <div id="resultado" class="row"></div>
</main>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <strong class="me-auto text-white">App Recetas</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/app.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof favoritos !== "undefined" && favoritos.length) {
            const resultado = document.querySelector("#resultado");
            favoritos.forEach(favorito => {
                mostrarRecetaFavorita(favorito, resultado);
            });
        }
    });

    function mostrarRecetaFavorita(favorito, contenedor) {
        const { id_receta, titulo_receta, imagen_receta } = favorito;

        const recetaContenedor = document.createElement("DIV");
        recetaContenedor.classList.add("col-md-4");

        const recetaCard = document.createElement("DIV");
        recetaCard.classList.add("card", "mb-4");

        const recetaImagen = document.createElement("IMG");
        recetaImagen.classList.add("card-img-top");
        recetaImagen.alt = `Imagen de la receta ${titulo_receta}`;
        recetaImagen.src = imagen_receta;

        const recetaCardBody = document.createElement("DIV");
        recetaCardBody.classList.add("card-body");

        const recetaHeading = document.createElement("H3");
        recetaHeading.classList.add("card-title", "mb-3");
        recetaHeading.textContent = titulo_receta;

        recetaCardBody.appendChild(recetaHeading);

        recetaCard.appendChild(recetaImagen);
        recetaCard.appendChild(recetaCardBody);

        recetaContenedor.appendChild(recetaCard);

        contenedor.appendChild(recetaContenedor);
    }
</script>
</body>
</html>
