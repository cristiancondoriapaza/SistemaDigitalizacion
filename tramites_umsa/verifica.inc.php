<div class="alert alert-primary">
    <strong>Evaluación de Requisitos:</strong> ¿La documentación está completa y correcta?
</div>

<p>Revisa minuciosamente los respaldos adjuntos. Si falta algún documento, debes devolver el trámite al estudiante.</p>

<div class="form-check mb-3 mt-4">
    <input class="form-check-input" type="radio" name="condicion" value="SI" id="condSi" required>
    <label class="form-check-label text-success fw-bold" for="condSi">
        SÍ, la documentación está completa (Aprobar y pasar a redacción de Informe).
    </label>
</div>

<div class="form-check mb-4">
    <input class="form-check-input" type="radio" name="condicion" value="NO" id="condNo" required>
    <label class="form-check-label text-danger fw-bold" for="condNo">
        NO, faltan documentos o son ilegibles (Rechazar y devolver al Estudiante).
    </label>
</div>

<div class="mb-3">
    <label class="form-label fw-bold">Observaciones (Obligatorio si se rechaza)</label>
    <textarea name="observaciones_ts" class="form-control" rows="2" placeholder="Si rechazas el trámite, detalla aquí qué documento falta para que el estudiante lo corrija..."></textarea>
</div>