
<?php
include('bd.php');
$idRegistros = $_REQUEST['id'];


$update = ("DELETE FROM registros 
WHERE registros.ID='" .$idRegistros. "'
");
$result_update = mysqli_query($conexion, $update);

echo $update;




echo "<script type='text/javascript'>
        window.location='index.php';
    </script>";
?>
