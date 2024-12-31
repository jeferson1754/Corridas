<div class="modal fade" id="editChildresn<?php echo $row['ID']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editModalLabel">Actualizar Información</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="recib_Update.php" class="needs-validation" novalidate>
        <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">

        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label fw-semibold">Usuario</label>
            <input type="text" name="usuario" class="form-control shadow-sm"
              value="<?php echo $row['Usuario']; ?>" required>
            <div class="invalid-feedback">Campo requerido</div>
          </div>

          <div class="mb-3">
            <label for="kilometros" class="form-label fw-semibold">Kilómetros</label>
            <input
              type="number"
              class="form-control shadow-sm"
              id="kilometros"
              value="<?php echo $row['KM']; ?>"
              name="kilometros"
              required
              placeholder="Ingrese los kilómetros">
            <div class="invalid-feedback">Por favor ingrese los kilómetros</div>
          </div>


          <div class="mb-3">
            <label for="tiempo" class="form-label fw-semibold">Tiempo</label>
            <input
              type="text"
              class="form-control shadow-sm"
              id="tiempo"
              name="tiempo"
              value="<?php echo $row['Tiempo']; ?>" min="00:10:00" max="12:00:00" step="1"
              placeholder="HH:MM:SS"
              pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$"
              required>
            <div class="invalid-feedback">Por favor ingrese un tiempo válido en formato HH:MM:SS</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Ubicación</label>
            <input type="text" name="ubi" class="form-control shadow-sm"
              value="<?php echo $row['Ubicacion']; ?>" required>
            <div class="invalid-feedback">Ingrese la ubicación</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Fecha</label>
            <input type="date" name="fecha" class="form-control shadow-sm"
              value="<?php echo date('Y-m-d', strtotime($row['Fecha'])); ?>" required>
            <div class="invalid-feedback">Seleccione una fecha</div>
          </div>


          <div class="mb-3">
            <label class="form-label fw-semibold">Estado</label>
            <select name="estado" class="form-select shadow-sm" required>
              <option value="<?php echo $row['Estado']; ?>"><?php echo $row['Estado']; ?></option>
              <?php
              $query = $conexion->query("SELECT * FROM `estado_corridda`");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
              }
              ?>
            </select>
            <div class="invalid-feedback">Seleccione un estado</div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .modal-content {
    border-radius: 0.5rem;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  .shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }
</style>

<script>
  (function() {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>