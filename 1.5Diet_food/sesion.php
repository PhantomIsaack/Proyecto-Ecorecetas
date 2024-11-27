<?php
include 'encabezado.php';
require 'conexion.php';

if (isset($_POST['add_usuario'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password']; 

    $check_sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('Este correo ya está registrado.');</script>";
    } else {
        $add_sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($add_sql);
        $stmt->bind_param('sss', $nombre, $email, $password);

        if ($stmt->execute()) {
            echo "<script>
                alert('Usuario agregado exitosamente.');
                window.location.href = 'sesion.php';
            </script>";
            exit(); 
        } else {
            $error_msg = $stmt->error;
            echo "<script>alert('Error agregando Usuario: $error_msg');</script>";
        }
        $stmt->close();
    }
}

$usuarios_sql = "SELECT * FROM usuarios";
$usuarios_result = $conn->query($usuarios_sql);
?>


   <main class="mt-5 container-lg">
        <h2 class="text-center fs-1 narrable">Registro / Inicio de sesión</h2>
        <div id="resultado" class="mt-5 row"></div>
    </main>

<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Registro -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-primary text-white text-center narrable">
                    <h4>Registro</h4>
                </div>
                <div class="card-body">
                    <form id="register-form" action="sesion.php" method="post">
                        <div class="mb-3">
                            <label for="registroNombre" class="form-label narrable">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="registroCorreo" class="form-label narrable">Correo</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="registroPassword" class="form-label narrable">Contraseña</label>
                            <input type="password" class="form-control"id="password" name="password" placeholder="Crea una contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 narrable" name="add_usuario">Registrarse</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Inicio de sesión -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-header bg-success text-white text-center narrable">
                    <h4>Inicio de Sesión</h4>
                </div>
                <div class="card-body">
                    <form id="loginForm" action="inicio-sesion.php" method="post">
                        <div class="mb-3">
                            <label for="loginCorreo" class="form-label narrable">Correo</label>
                            <input type="email" class="form-control" id="login-email" name="email" placeholder="Ingresa tu correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label narrable">Contraseña</label>
                            <input type="password" class="form-control" id="login-password" name="password" placeholder="Ingresa tu contraseña" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100 narrable">Iniciar Sesión</button>
                        <p id="login-error" class="error-message narrable" aria-live="assertive"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'pie.php'; ?>
