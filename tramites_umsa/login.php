<?php
session_start();

$error = '';

// Si el usuario envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Recibimos los datos y forzamos el usuario a MAYÚSCULAS
    $input_usuario = strtoupper(trim($_POST['usuario']));
    $input_password = $_POST['password'];
    
    // 2. Leemos nuestro directorio de usuarios JSON
    $json_usuarios = json_decode(file_get_contents('datos/usuarios.json'), true);
    $autenticado = false;
    
    // 3. Buscamos si existe coincidencia
    if ($json_usuarios) {
        foreach ($json_usuarios as $user) {
            if ($user['usuario'] === $input_usuario && $user['password'] === $input_password) {
                // ¡Credenciales correctas!
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['rol'] = $user['rol'];
                $_SESSION['nombre_completo'] = $user['nombre_completo'];
                $autenticado = true;
                break;
            }
        }
    }
    
    // 4. Redirigimos o mostramos error
    if ($autenticado) {
        header("Location: bandeja.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Workflow Universitario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light d-flex align-items-center vh-100">

<div class="container" style="max-width: 400px;">
    <div class="card shadow border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-3">
            <h4 class="mb-0"><i class="fas fa-university"></i> Portal de Trámites</h4>
        </div>
        <div class="card-body p-4">
            
            <?php if ($error != ''): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="usuario" class="form-control" required placeholder="Ingrese su usuario">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" required placeholder="******">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Iniciar Sesión</button>
            </form>
            
        </div>
    </div>
</div>

</body>
</html>