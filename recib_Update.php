
<?php
include 'bd.php';
$idRegistros  = $_POST['id'];
$usuario      = $_POST['usuario'];
$km           = $_POST['km'];
$tiempo       = $_POST['tiempo'];
$ubi          = $_POST['ubi'];
$fecha        = $_POST['fecha'];
$estado       = $_POST['estado'];

$update = ("UPDATE
registros 
SET 
Usuario ='" . $usuario . "',
KM ='" . $km . "',
Tiempo ='" . $tiempo . "',
Ubicacion ='" . $ubi . "',
Fecha ='" . $fecha . "',
Estado ='" . $estado . "'
WHERE ID='" . $idRegistros . "';
");

$result_update = mysqli_query($conexion, $update);

echo $update;

/*
$local = "01:22:00";

$NuevaFecha = strtotime ( '-1 hour' , strtotime ($tiempo) ) ; 
$NuevaFecha = date ( 'H:i:s' , $NuevaFecha); 

echo "<br>";
echo $NuevaFecha;
echo "<br>";
echo $tiempo;
echo "<br>";
echo $local;
*/

echo "<script type='text/javascript'>
        window.location='index.php';
    </script>";
    

?>
