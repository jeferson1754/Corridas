
<form name="form-data" action="recibCliente.php" method="POST">

    <div class="row">
        <div class="col-md-12">
            <label for="name" class="form-label">Nombre del Usuario</label>
            <input type="text" class="form-control" name="nombre" required='true' autofocus>
        </div>
        <div class="col-md-12 mt-2">
            <label for="kilometros" class="form-label">Kilometros</label>

            <select name="kilometros" class="form-control">
                <option value="0">Seleccione:</option>
                <?php
                $query = $conexion->query("SELECT KM FROM `km`");
                while ($valores = mysqli_fetch_array($query)) {
                    echo '<option value="' . $valores['KM'] . '">' . $valores['KM'] . '</option>';
                }
                ?>
            </select>

        </div>
        <div class="col-md-12 mt-2">
            <label for="tiempo" class="form-label">Tiempo</label>
            <input type="time" class="form-control" name="tiempo"max="22:30:00" min="00:10:00" step="1" required='true'>
        </div>
        <div class="col-md-12 mt-2">
            <label for="ubi" class="form-label">Ubicacion</label>
            <input type="text" class="form-control" name="ubi" required='true'>
        </div>
        <div class="col-md-12 mt-2">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" required='true'>
        </div>

    </div>
    <div class="row justify-content-start text-center mt-5">
        <div class="col-12">
            <button class="btn btn-primary btn-block" id="btnEnviar">
                Registrar Cliente
            </button>
        </div>
    </div>
</form>