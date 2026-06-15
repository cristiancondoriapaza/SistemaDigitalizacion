<?php
session_start();
// Configuramos la zona horaria para que el fechado sea exacto
date_default_timezone_set('America/La_Paz'); 

// 1. Recibir datos del formulario (desde index.php)
$flujo = $_POST['flujo'];
$proceso = $_POST['proceso'];
$nrotramite = $_POST['nrotramite'];
$condicion = isset($_POST['condicion']) ? $_POST['condicion'] : null;

// Estandarizamos el usuario a mayúsculas para mantener la consistencia visual en los registros
$usuario_actual = strtoupper($_SESSION['usuario']); 

// 2. Leer los archivos JSON y convertirlos en Arrays de PHP
$json_procesos = json_decode(file_get_contents('datos/flujo_proceso.json'), true);
$json_condiciones = json_decode(file_get_contents('datos/flujo_condicion.json'), true);
$json_seguimiento = json_decode(file_get_contents('datos/seguimiento.json'), true);

// Si el archivo de seguimiento está vacío (primera vez), lo inicializamos como un array vacío
if (!$json_seguimiento) {
    $json_seguimiento = []; 
}

// 3. Buscar la configuración del proceso actual en el diccionario
$proceso_actual_config = null;
foreach ($json_procesos as $p) {
    if ($p['flujo'] == $flujo && $p['proceso'] == $proceso) {
        $proceso_actual_config = $p;
        break;
    }
}

$tipo = $proceso_actual_config['tipo'];
$proceso_siguiente = $proceso_actual_config['procesosiguiente'];

// 4. Lógica de Bifurcación (Tipo Q): Determinar el camino según la respuesta
if ($tipo == 'Q' && $condicion) {
    foreach ($json_condiciones as $c) {
        if ($c['flujo'] == $flujo && $c['proceso'] == $proceso && $c['condicion'] == $condicion) {
            $proceso_siguiente = $c['procesosiguiente'];
            break;
        }
    }
}

$fecha_ahora = date('Y-m-d H:i:s');

// 5. Cerrar la tarea actual (Ponerle fecha de fin y registrar el usuario)
foreach ($json_seguimiento as &$tarea) {
    // Buscamos la fila exacta que está pendiente (fechafin == null)
    if ($tarea['nrotramite'] == $nrotramite && $tarea['flujo'] == $flujo && $tarea['proceso'] == $proceso && $tarea['fechafin'] == null) {
        $tarea['fechafin'] = $fecha_ahora;
        $tarea['usuario'] = $usuario_actual;
        break; // Detenemos el ciclo al encontrarla
    }
}
unset($tarea); // Limpiamos la referencia de memoria por seguridad

// 6. Si NO es el proceso final, creamos la nueva tarea para la siguiente bandeja
if ($tipo != 'F' && $proceso_siguiente != null) {
    
    // Primero, descubrimos a qué rol le toca el siguiente proceso
    $rol_siguiente = '';
    foreach ($json_procesos as $p) {
        if ($p['flujo'] == $flujo && $p['proceso'] == $proceso_siguiente) {
            $rol_siguiente = strtoupper($p['rol']); // Aseguramos mayúsculas
            break;
        }
    }

    // Insertamos la nueva tarea en el arreglo
    $nueva_tarea = [
        "nrotramite" => (int)$nrotramite,
        "flujo" => $flujo,
        "proceso" => $proceso_siguiente,
        "usuario" => "SIN ASIGNAR",
        "rol" => $rol_siguiente,
        "fechaini" => $fecha_ahora,
        "fechafin" => null
    ];
    $json_seguimiento[] = $nueva_tarea;
}

// 7. Sobrescribir el archivo JSON con los datos actualizados
// JSON_PRETTY_PRINT hace que el archivo sea legible para humanos (con saltos de línea)
file_put_contents('datos/seguimiento.json', json_encode($json_seguimiento, JSON_PRETTY_PRINT));

// 8. Redirigir de vuelta a la bandeja
header("Location: bandeja.php");
exit();
?>