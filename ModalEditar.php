<!--ventana para Update--->
<div class="modal fade" id="editChildresn<?php echo $mostrar['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #563d7c !important;">
        <h6 class="modal-title" style="color: #fff; text-align: center;">
          Actualizar Información
        </h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>


      <form method="POST" action="recib_Update.php">
        <input type="hidden" name="id" value="<?php echo $mostrar['ID']; ?>">

        <div class="modal-body" id="cont_modal">

          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Usuario:</label>
            <input type="text" name="usuario" class="form-control" value="<?php echo $mostrar['Usuario']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">KM:</label>
            <select name="km" class="form-control" required>
              <option value="<?php echo $mostrar['KM']; ?>"><?php echo $mostrar['KM']; ?></option>
              <?php
              $query = $conexion->query("SELECT KM FROM `km`");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['KM'] . '">' . $valores['KM'] . ' KM </option>';
              }
              ?>
            </select>

          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Tiempo:</label>
            <input type="time" name="tiempo" class="form-control" value="<?php echo $mostrar['Tiempo']; ?>" min="00:10:00" max="12:00:00" step="1" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Ubicacion:</label>
            <input type="text" name="ubi" class="form-control" value="<?php echo $mostrar['Ubicacion']; ?>" required="true">
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Fecha:</label>
            <input type="date" name="fecha" class="form-control" value="<?php echo $mostrar['Fecha']; ?>" max="<?php echo $año; ?>-12-31" required='true'>
          </div>
          <div class="form-group">
            <label for="estado" class="form-label">Estado</label>

            <select name="estado" class="form-control" required>
              <option value="<?php echo $mostrar['Estado']; ?>"><?php echo $mostrar['Estado']; ?></option>
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
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>

    </div>
  </div>
</div>
<!---fin ventana Update --->