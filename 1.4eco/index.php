<?php
include 'encabezado.php';

?>

    <main class="mt-5 container-lg">
        <h2 class="text-center fs-1 narrable">Recetas Vegetarianas</h2>
        <div id="resultado" class="mt-5 row"></div>
    </main>

    <div class="modal fade narrable" id="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable narrable">
            <div class="modal-content narrable">
                <div class="modal-header narrable">
                    <h1 class="modal-title fs-3 font-bold narrable" id="staticBackdropLabel"></h1>
                    <button type="button" class="btn-close narrable" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body narrable"></div>
                <div class="modal-footer flex justify-content-between narrable"></div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3 narrable">
        <div id="toast" class="toast narrable" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-danger text-white narrable">
                <strong class="me-auto text-white narrable">App Recetas</strong>
                <button type="button" class="btn-close btn-close-white narrable" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body narrable"></div>
        </div>
    </div>

    <script src="js/app.js"></script>
    <?php include 'pie.php'; ?>