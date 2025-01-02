<?php
// Incluir archivo de conexión a la base de datos
include 'bd.php';

// Validar y recibir los datos del formulario
$idRegistros = isset($_POST['id']) ? intval($_POST['id']) : null;
$usuario     = isset($_POST['usuario']) ? trim($_POST['usuario']) : null;
$km          = isset($_POST['kilometros']) ? floatval($_POST['kilometros']) : null;
$tiempo      = isset($_POST['tiempo']) ? trim($_POST['tiempo']) : null;
$ubi         = isset($_POST['ubi']) ? trim($_POST['ubi']) : null;
$fecha       = isset($_POST['fecha']) ? trim($_POST['fecha']) : null;
$estado      = isset($_POST['estado']) ? trim($_POST['estado']) : null;

// Verificar que todos los datos requeridos estén presentes
if (!$idRegistros || !$usuario || !$km || !$tiempo || !$ubi || !$fecha || !$estado) {
    die("Error: Todos los campos son obligatorios.");
}

try {
    // Usar consultas preparadas para prevenir inyecciones SQL
    $query = "UPDATE registros 
              SET Usuario = ?, KM = ?, Tiempo = ?, Ubicacion = ?, Fecha = ?, Estado = ? 
              WHERE ID = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('sdssssi', $usuario, $km, $tiempo, $ubi, $fecha, $estado, $idRegistros);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página principal después de la actualización
        header('Location: index.php');
        exit;
    } else {
        throw new Exception("Error al actualizar el registro: " . $stmt->error);
    }
} catch (Exception $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$stmt->close();
$conexion->close();
?>
