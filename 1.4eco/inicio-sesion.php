<?php
session_start();
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id_usuario, nombre FROM usuarios WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $user['id_usuario'];
        $_SESSION['user_name'] = $user['nombre'];

        echo "<script>alert('Usuario ingresado.'); window.location.href='index.php'</script>";
        exit();
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos.'); window.location.href='sesion.php';</script>";
        exit();
    }
}
?>