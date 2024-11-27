<?php
include 'conexion.php'; // Conexión a la base de datos

$query = "SELECT * FROM favoritos";
$result = $conn->query($query);

$favoritos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favoritos[] = $row;
    }
}

echo json_encode($favoritos);
?>
