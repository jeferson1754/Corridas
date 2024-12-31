<!--ventana para Update--->
<div class="modal fade" id="editChildresn2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #563d7c !important;">
        <h6 class="modal-title" style="color: #fff; text-align: center;">
          Nuevo Registro
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>



      <form name="form-data" action="recibCliente.php" method="POST">

        <div class="modal-body" id="cont_modal">
          <div class="form-group">
            <label for="name" class="form-label">Nombre del Usuario</label>
            <input type="text" class="form-control" name="nombre" required='true' autofocus>
          </div>
          <div class="form-group">
            <label for="kilometros" class="form-label">Kilometros</label>

            <select name="kilometros" class="form-control" required>
              <option value="">Seleccione:</option>
              <?php
              $query = $conexion->query("SELECT KM FROM `km`");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['KM'] . '">' . $valores['KM'] . ' KM</option>';
              }
              ?>
            </select>

          </div>
          <div class="form-group">
            <label for="tiempo" class="form-label">Tiempo</label>
            <input type="time" class="form-control" name="tiempo" max="05:00:00" min="00:00:00" step="1">
          </div>
          <div class="form-group">
            <label for="ubi" class="form-label">Ubicacion</label>
            <input type="text" class="form-control" name="ubi" required>
          </div>
          <div class="form-group">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" min="<?php echo $año; ?>-01-01" max="<?php echo $año; ?>-12-31" required='true'>
          </div>
          <div class="form-group">
            <label for="estado" class="form-label">Estado</label>

            <select name="estado" class="form-control" required>
              <option value="">Seleccione:</option>
              <?php
              $query = $conexion->query("SELECT * FROM `estado_corridda`");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
              }
              ?>
            </select>

          </div>
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btnEnviar">
            Registrar Cliente
          </button>
        </div>
    </div>
    </form>

  </div>
</div>
</div>
<!---fin ventana Update --->