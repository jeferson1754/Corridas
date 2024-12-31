<div class="modal fade" id="editChildresn1<?php echo $row['ID']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow border-0">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">
          Confirmar Eliminación
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form method="POST" action="recib_Delete.php">
        <input type="hidden" name="id" value="<?php echo $row['ID']; ?>">

        <div class="modal-body text-center p-4">
          <div class="mb-4">
            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
          </div>

          <h5 class="mb-4">¿Realmente deseas eliminar este registro?</h5>

          <div class="card bg-light p-3 mb-3">
            <div class="fw-bold text-primary mb-2"><?php echo $row['Usuario']; ?></div>
            <div class="row g-2">
              <div class="col-sm-3">
                <span class="d-block text-muted">Fecha</span>
                <span class="fw-semibold"><?php echo date('d-m-Y', strtotime($row['Fecha'])); ?></span>
              </div>
              <div class="col-sm-3">
                <span class="d-block text-muted">Kilómetros</span>
                <span class="fw-semibold"><?php echo $row['KM']; ?></span>
              </div>
              <div class="col-sm-3">
                <span class="d-block text-muted">Tiempo</span>
                <span class="fw-semibold"><?php echo $row['Tiempo']; ?></span>
              </div>

              <div class="col-sm-3">
                <span class="d-block text-muted">Estado</span>
                <span class="fw-semibold"><?php echo $row['Estado']; ?></span>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash-alt me-2"></i>Eliminar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  .modal-content {
    border-radius: 0.5rem;
  }

  .card {
    border-radius: 0.5rem;
    border: 1px solid rgba(0, 0, 0, .125);
  }
</style>