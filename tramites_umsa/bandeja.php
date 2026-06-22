<?php
session_start();

// Validamos que el usuario haya iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // O tu página de login
    exit();
}

$usuario_actual = strtoupper($_SESSION['usuario']);
$rol_actual = strtoupper($_SESSION['rol']); 

// 1. Leer el archivo JSON de seguimiento
$json_data = file_get_contents('datos/seguimiento.json');
$json_seguimiento = json_decode($json_data, true);

// Si no hay datos aún, lo inicializamos vacío
if (!$json_seguimiento) {
    $json_seguimiento = [];
}

// 2. Filtrar las tareas pendientes para el usuario actual
$tareas_pendientes = [];
foreach ($json_seguimiento as $tarea) {
    // Si la fecha fin está vacía, significa que la tarea no se ha completado
    // Y verificamos que el ROL de la tarea coincida con el ROL del usuario
    if ($tarea['fechafin'] == null && strtoupper($tarea['rol']) == $rol_actual) {
        $tareas_pendientes[] = $tarea;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Bandeja - Workflow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-inbox text-primary"></i> Bandeja de Entrada</h2>
        <?php if ($rol_actual == 'ESTUDIANTE'): ?>
            <div class="mb-4">
                <a href="nuevo_tramite.php?flujo=F1" class="btn btn-success me-2">
                    <i class="fas fa-plus-circle"></i> Nueva Beca Comedor
                </a>
                <a href="nuevo_tramite.php?flujo=F2" class="btn btn-warning">
                    <i class="fas fa-plus-circle"></i> Nueva Defensa de Grado
                </a>
            </div>
        <?php endif; ?>
        <div>
            <span class="badge bg-secondary fs-6 me-3"><?php echo $_SESSION['nombre_completo']; ?> | <?php echo $rol_actual; ?></span>
            <a href="logout.php" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Salir</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (count($tareas_pendientes) > 0): ?>
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Trámite Nro</th>
                            <th>Flujo</th>
                            <th>Proceso</th>
                            <th>Fecha de Ingreso</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tareas_pendientes as $t): ?>
                            <tr>
                                <td><span class="badge bg-success fs-6">#<?php echo $t['nrotramite']; ?></span></td>
                                <td><?php echo $t['flujo'] == 'F1' ? 'Beca Comedor' : 'Defensa de Grado'; ?></td>
                                <td><span class="fw-bold"><?php echo $t['proceso']; ?></span></td>
                                <td><?php echo $t['fechaini']; ?></td>
                                <td>
                                    <!-- Este botón enviará las variables por la URL (GET) a nuestro nuevo index.php -->
                                    <a href="index.php?flujo=<?php echo $t['flujo']; ?>&proceso=<?php echo $t['proceso']; ?>&nrotramite=<?php echo $t['nrotramite']; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-folder-open"></i> Atender
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center mb-0">
                    <i class="fas fa-smile-beam fa-2x mb-2 d-block"></i>
                    <strong>¡Todo al día!</strong> No tienes trámites pendientes en tu bandeja.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>