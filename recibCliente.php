<?php
include('bd.php');
$nombre     = $_REQUEST['nombre'];
$km         = $_REQUEST['kilometros'];
$tiempo     = $_REQUEST['tiempo'];
$ubi        = $_REQUEST['ubi'];
$fecha      = $_REQUEST['fecha'];
$estado     = $_REQUEST['estado'];

try {
    $sql2 = "INSERT INTO `registros`( 
    `Usuario`,
    `KM`, 
    `Tiempo`,
    `Ubicacion`,
    `Fecha`,
    `Estado` )
    VALUES (
    '" . $nombre . "',
    '" . $km . "',
    '" . $tiempo . "',
    '" . $ubi . "',
    '" . $fecha . "',
    '" . $estado . "')";

    $consulta = mysqli_query($conexion, $sql2);

    echo $sql2;
    //echo 'ultimo usuario insertado ' . $last_id1;
    //echo 'ultimo usuario insertado ' . $last_id2;
    echo "<br>";
} catch (PDOException $e) {


}


header("location:index.php");
