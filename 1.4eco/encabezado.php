<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Recetas saludables, Recetas vegetarianas y Recetas a tu alcance">
    <title>Diet food</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger bg-gradient p-3">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center fs-4 narrable" href="index.php">
            <img src="imagenes/logeco.jpg" alt="Logo de la página" class="img-fluid me-2" style="width: 50px; height: 50px; object-fit: cover;">
            Diet food
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link fs-4 active narrable" aria-current="page" href="index.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-4 narrable" href="conocenos.php">Conócenos</a>
                </li>
                <?php if (isset($_SESSION['user_name'])): ?>
                    <li class="nav-item">
                        <a class="nav-link fs-4 narrable" href="favoritos.php">Favoritos</a>
                    </li>
                    <li><span class="nav-link fs-4 narrable">Usuario: <?php echo $_SESSION['user_name']; ?></span></li>
                    <li class="nav-item">
                        <a class="nav-link fs-4 narrable" href="logout.php">Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fs-4 narrable" href="sesion.php">Sesión</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                <button class="btn btn-danger fw-bold ms-3 narrable" id="toggleNarrador">Apagar narrador</button>
                </li>
            </ul>
        </div>
    </div>
</nav>
</header>
