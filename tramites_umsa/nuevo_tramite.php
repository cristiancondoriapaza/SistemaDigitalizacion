<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set('America/La_Paz');

$flujo_nuevo = $_GET['flujo']; // Recibe F1 o F2
$proceso_inicial = "P1"; 
$usuario_actual = strtoupper($_SESSION['usuario']);
$rol_actual = strtoupper($_SESSION['rol']);

// Leer el archivo JSON para determinar el último número de trámite
$json_seguimiento = json_decode(file_get_contents('datos/seguimiento.json'), true);

if (!$json_seguimiento) {
    $json_seguimiento = [];
}

$max_nro = 1000; // Empezamos a contar desde el 1000
if (count($json_seguimiento) > 0) {
    foreach ($json_seguimiento as $tarea) {
        if ($tarea['nrotramite'] > $max_nro) {
            $max_nro = $tarea['nrotramite'];
        }
    }
}

$nuevo_nrotramite = $max_nro + 1;
$fecha_ahora = date('Y-m-d H:i:s');

// Creamos e insertamos el registro inicial del P1 para el estudiante
$nueva_tarea_inicial = [
    "nrotramite" => (int)$nuevo_nrotramite,
    "flujo" => $flujo_nuevo,
    "proceso" => $proceso_inicial,
    "usuario" => "SIN ASIGNAR", // Se actualizará al presionar guardar en el motor
    "rol" => $rol_actual,
    "fechaini" => $fecha_ahora,
    "fechafin" => null // Nace pendiente para que aparezca en el motor
];

$json_seguimiento[] = $nueva_tarea_inicial;

// Guardamos el archivo con la tarea P1 ya registrada
file_put_contents('datos/seguimiento.json', json_encode($json_seguimiento, JSON_PRETTY_PRINT));

// Redirigir al cascarón (index.php) para que el estudiante llene el formulario
header("Location: index.php?flujo=$flujo_nuevo&proceso=$proceso_inicial&nrotramite=$nuevo_nrotramite");
exit();
?>