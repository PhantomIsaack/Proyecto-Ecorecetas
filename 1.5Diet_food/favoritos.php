<?php
include 'encabezado.php';
include 'conexion.php';
?>
<div class="modal fade" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-3 font-bold" id="staticBackdropLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer flex justify-content-between">
                </div>
            </div>
        </div>
    </div>

    <main class="mt-5 container-lg">
      <h2 class="text-center fs-1">Favoritos</h2>
      <div id="resultado" class="favoritos row"></div>
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

    <script src="js/app.js"></script>
    <?php include 'pie.php'; ?>