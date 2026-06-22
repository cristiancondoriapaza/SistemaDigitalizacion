<?php
// 1. Revisamos el historial para saber si este trámite ya pasó por las manos del Tutor (P3)
$historial = json_decode(file_get_contents('datos/seguimiento.json'), true);
$fue_rechazado = false;

// Buscamos en el JSON si existe alguna tarea P3 para este número de trámite
if ($historial) {
    foreach ($historial as $tarea) {
        if ($tarea['nrotramite'] == $nrotramite && $tarea['proceso'] == 'P3') {
            $fue_rechazado = true;
            break; // Si ya pasó por P3, detenemos la búsqueda
        }
    }
}
?>

<?php if ($fue_rechazado): ?>

    <div class="alert alert-danger">
        <strong>¡Atención! Documento Observado:</strong> Tu tutor ha revisado el documento y ha solicitado correcciones.
    </div>
    <p>Por favor, aplica las correcciones necesarias de fondo o formato y vuelve a subir la versión final de tu proyecto para una nueva revisión.</p>

<?php else: ?>

    <div class="alert alert-info">
        <strong>Paso 1:</strong> Solicitud de Habilitación para Defensa de Grado.
    </div>

<?php endif; ?>

<hr>

<div class="mb-3">
    <label class="form-label fw-bold">Título del Proyecto de Grado / Tesis</label>
    <input type="text" name="titulo_tesis" class="form-control" required>
</div>

<div class="mb-4">
    <label class="form-label fw-bold">Subir Documento Corregido (PDF)</label>
    <input type="file" name="doc_tesis" class="form-control" accept=".pdf" required>
    <div class="form-text">Asegúrate de que el documento cumpla con el formato APA antes de enviarlo nuevamente.</div>
</div>