<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// 1. Atrapar variables de la URL (Método GET)
$flujo = $_GET['flujo'];
$proceso = $_GET['proceso'];
$nrotramite = $_GET['nrotramite'];

// 2. Leer el diccionario JSON para saber qué pantalla le toca
$json_procesos = json_decode(file_get_contents('datos/flujo_proceso.json'), true);
$pantalla = '';

foreach ($json_procesos as $p) {
    if ($p['flujo'] == $flujo && $p['proceso'] == $proceso) {
        $pantalla = $p['pantalla']; // Ej: "formulario", "recepcion_ts", etc.
        break;
    }
}

// Protegemos si alguien pone una URL incorrecta
if ($pantalla == '') {
    die("Error: El proceso no existe.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Atención de Trámite - <?php echo $pantalla; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    
    <a href="bandeja.php" class="btn btn-outline-secondary mb-3">< Volver a Bandeja</a>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Trámite #<?php echo $nrotramite; ?> | Flujo: <?php echo $flujo; ?> | Tarea: <?php echo $proceso; ?></h5>
        </div>
        <div class="card-body">
            
            <!-- Aquí abrimos el Formulario que apunta a motor.php -->
            <form action="motor.php" method="POST" enctype="multipart/form-data">
                
                <!-- Campos ocultos para el motor -->
                <input type="hidden" name="flujo" value="<?php echo $flujo; ?>">
                <input type="hidden" name="proceso" value="<?php echo $proceso; ?>">
                <input type="hidden" name="nrotramite" value="<?php echo $nrotramite; ?>">
                
                <hr>
                
                <!-- AQUÍ ES DONDE SUCEDE LA MAGIA: Incluimos el archivo que dice el JSON -->
                <?php 
                    $archivo_incluir = $pantalla . '.inc.php';
                    if(file_exists($archivo_incluir)) {
                        include($archivo_incluir); 
                    } else {
                        echo "<div class='alert alert-danger'>Falta crear el archivo visual: <strong>$archivo_incluir</strong></div>";
                    }
                ?>
                
                <hr>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-success btn-lg">
                        Guardar y Continuar Flujo >
                    </button>
                </div>
                
            </form>

        </div>
    </div>
</div>

</body>
</html>