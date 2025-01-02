<?php

require 'bd.php';

$año = date("Y");

$mes = date("m"); 

$dia = date("d")-1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

    <title>Aplicacion Corridas
    </title>
</head>
<style>

</style>

<body>
    <div class="col-sm">
        <!--- Formulario para registrar Cliente --->
     
        <button type="button" class="btn btn-info " data-toggle="modal" data-target="#editChildresn2">
            Crear Nuevo Registro
        </button>
        <?php include('ModalCrear.php');  ?>
    </div>

    <div class="main-container">

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Kilometros</th>
                    <th>Tiempo</th>
                    <th>Ubicacion</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th colspan="2">Acciones</th>
                </tr>
            </thead>
            <?php
            $sql = "SELECT * FROM registros ORDER BY `registros`.`Fecha` DESC";
            $result = mysqli_query($conexion, $sql);
            //echo $sql;

            while ($mostrar = mysqli_fetch_array($result)) {
            ?>
                <tr>
                    <td><?php echo $mostrar['Usuario'] ?></td>
                    <td><?php echo $mostrar['KM'] ?> KM</td>
                    <td><?php echo $mostrar['Tiempo'] ?></td>
                    <td><?php echo $mostrar['Ubicacion'] ?></td>
                    <td><?php echo $mostrar['Fecha'] ?></td>
                    <td><?php echo $mostrar['Estado'] ?></td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editChildresn<?php echo $mostrar['ID']; ?>">
                            Editar
                        </button>

                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#editChildresn1<?php echo $mostrar['ID']; ?>">
                            Eliminar
                        </button>
                    </td>
                </tr>
                <!--Ventana Modal para Actualizar--->
                <?php include('ModalEditar.php'); ?>

                <!--Ventana Modal para la Alerta de Eliminar--->
                <?php include('ModalDelete.php'); ?>
            <?php
            }
            ?>
        </table>
    </div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script>
    //Funciona
    function alerta() {
        Swal.fire({
            title: 'How old are you?',
            icon: 'question',
            input: 'range',
            inputLabel: 'Your age',
            inputAttributes: {
                min: 8,
                max: 120,
                step: 1
            },
            inputValue: 25
        })

    }
</script>


</html>