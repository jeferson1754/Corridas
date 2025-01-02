<?php
// Incluir archivo de conexión a la base de datos
include('bd.php');

// Validar y recibir el ID del registro a eliminar
$idRegistros = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : null;

// Verificar que el ID sea válido
if (!$idRegistros) {
    die("Error: ID no válido.");
}

try {
    // Usar consultas preparadas para prevenir inyecciones SQL
    $query = "DELETE FROM registros WHERE ID = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $idRegistros);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página principal con JavaScript después de eliminar
        header('Location: index.php');
        exit;
    } else {
        throw new Exception("Error al eliminar el registro: " . $stmt->error);
    }
} catch (Exception $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
