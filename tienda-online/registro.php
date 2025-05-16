<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';
$auth = new Auth();
$message = '';
$message_type = 'danger'; // Por defecto es error

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if(empty($nombre) || empty($email) || empty($password)) {
        $message = 'Todos los campos son obligatorios';
    } elseif($password !== $confirm_password) {
        $message = 'Las contraseñas no coinciden';
    } elseif($auth->emailExists($email)) {
        $message = 'El email ya está registrado';
    } elseif($auth->register($nombre, $email, $password)) {
        $message = '¡Registro exitoso! Ahora puedes iniciar sesión.';
        $message_type = 'success';
        // Limpiar los campos del formulario
        $nombre = $email = '';
    } else {
        $message = 'Error al registrar. Intenta nuevamente.';
    }
}
?>

<div class="login-container">
    <h2 class="login-header">Registro de Usuario</h2>
    
    <?php if($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>
    
    <form method="post" action="registro.php" style="display: flex; flex-direction: column; gap: 15px;">
        <div>
            <label for="nombre">Nombre Completo</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre ?? ''); ?>" required>
        </div>
        
        <div>
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
        </div>
        
        <div>
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div>
            <label for="confirm_password">Confirmar Contraseña</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit">Registrarse</button>
        
        <div class="text-center" style="margin-top: 20px;">
            <span>¿Ya tienes cuenta? </span>
            <a href="login.php">Inicia Sesión</a>
        </div>
    </form>
</div>

<?php 
require_once __DIR__ . '/includes/footer.php';
?>