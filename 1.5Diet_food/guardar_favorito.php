<?php
include 'conexion.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos enviados desde JavaScript
    $id_receta = $_POST['id_receta'] ?? '';
    $titulo_receta = $_POST['titulo_receta'] ?? '';
    $imagen_receta = $_POST['imagen_receta'] ?? '';

    // Validar que los datos no estén vacíos
    if (!empty($id_receta) && !empty($titulo_receta) && !empty($imagen_receta)) {
        // Preparar la consulta para insertar
        $query = "INSERT INTO favoritos (id_receta, titulo, imagen) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $id_receta, $titulo_receta, $imagen_receta);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar en la base de datos']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>
