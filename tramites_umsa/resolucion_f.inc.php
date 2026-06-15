<?php
// 1. Revisamos el historial para saber por qué camino llegamos a P9
$historial = json_decode(file_get_contents('datos/seguimiento.json'), true);
$fue_aprobado = false;

// La variable $nrotramite ya existe porque index.php la captura de la URL antes de incluir este archivo
foreach ($historial as $tarea) {
    if ($tarea['nrotramite'] == $nrotramite && $tarea['proceso'] == 'P8') {
        $fue_aprobado = true;
        break; // Encontramos que pasó por Finanzas, detenemos la búsqueda
    }
}
?>

<?php if ($fue_aprobado): ?>

    <div class="alert alert-success">
        <strong>Resolución Final:</strong> ¡Beca Adjudicada!
    </div>
    <p>Has sido registrado exitosamente en el sistema de Finanzas. Ya puedes hacer uso del servicio del comedor universitario.</p>

<?php else: ?>

    <div class="alert alert-danger">
        <strong>Resolución Final:</strong> Postulación Rechazada.
    </div>
    <p>Lo sentimos. La Comisión de Becas ha evaluado tu expediente y, por falta de cupos o puntaje insuficiente en el informe social, no se te ha adjudicado el beneficio en esta gestión.</p>

<?php endif; ?>

<hr>

<div class="form-check mb-4 mt-4">
    <input class="form-check-input" type="checkbox" id="finBeca" required>
    <label class="form-check-label fw-bold text-dark" for="finBeca">Entendido. Cerrar y archivar trámite en mi historial.</label>
</div>