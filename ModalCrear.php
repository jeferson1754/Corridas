<!-- Modal -->
<div class="modal fade" id="editChildresn2" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalLabel">Nuevo Registro</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form name="form-data" action="recibCliente.php" method="POST" class="needs-validation" novalidate>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label for="nombre" class="form-label fw-semibold">Nombre del Usuario</label>
            <input type="text" class="form-control shadow-sm" id="nombre" name="nombre" required autofocus>
            <div class="invalid-feedback">Por favor ingrese un nombre</div>
          </div>

          <div class="mb-3">
            <label for="kilometros" class="form-label fw-semibold">Kilómetros</label>
            <input
              type="number"
              class="form-control shadow-sm"
              id="kilometros"
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
              placeholder="HH:MM:SS"
              pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$"
              required>
            <div class="invalid-feedback">Por favor ingrese un tiempo válido en formato HH:MM:SS</div>
          </div>

          <div class="mb-3">
            <label for="ubi" class="form-label fw-semibold">Ubicación</label>
            <input type="text" class="form-control shadow-sm" id="ubi" name="ubi" required>
            <div class="invalid-feedback">Por favor ingrese una ubicación</div>
          </div>
          <div class="mb-3">
            <label for="fecha" class="form-label fw-semibold">Fecha</label>
            <input
              type="date"
              class="form-control shadow-sm"
              id="fecha"
              name="fecha"
              min="<?php echo $año; ?>-01-01"
              max="<?php echo $año; ?>-12-31"
              value="<?php echo date('Y-m-d'); ?>"
              required>
            <div class="invalid-feedback">Por favor seleccione una fecha válida</div>
          </div>


          <div class="mb-3">
            <label for="estado" class="form-label fw-semibold">Estado</label>
            <select class="form-select shadow-sm" id="estado" name="estado" required>
              <option value="">Seleccione:</option>
              <?php
              $query = $conexion->query("SELECT * FROM `estado_corridda`");
              while ($valores = mysqli_fetch_array($query)) {
                echo '<option value="' . $valores['Estado'] . '">' . $valores['Estado'] . '</option>';
              }
              ?>
            </select>
            <div class="invalid-feedback">Por favor seleccione un estado</div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary" id="btnEnviar">Registrar Cliente</button>
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
  // Form validation
  (function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>