<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';

$auth = new Auth();
$message = '';

// Mensaje de registro exitoso
if(isset($_GET['registered'])) {
    $message = 'Registro exitoso. Ahora puedes iniciar sesión.';
}

// Procesar el formulario de login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_type = trim($_POST['user_type']); // Nuevo campo: tipo de usuario

    // Validar campos vacíos
    if(empty($email) || empty($password)) {
        $message = 'Por favor ingrese email y contraseña';
    } else {
        $user = $auth->login($email, $password, $user_type); // Modificamos la función login

        if($user) {
            session_start();
            session_regenerate_id(true);
            
            // Establecer variables de sesión
            $_SESSION = [
                'user_id' => $user['id'],
                'user_name' => $user['nombre'],
                'user_email' => $user['email'],
                'user_role' => $user['rol'],
                'logged_in' => true
            ];

            // Redirección según rol
            if(strtolower($user['rol']) === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: usuario.php');
            }
            exit();
        } else {
            $message = 'Credenciales incorrectas o no tienes permisos para este tipo de acceso';
        }
    }
}
?>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    
    <?php if($message): ?>
    <div class="alert <?= strpos($message, 'éxito') !== false ? 'alert-success' : 'alert-danger' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>
    
    <form method="post" class="login-form">
        <!-- Nuevo campo: selector de tipo de usuario -->
        <div class="form-group">
            <label for="user_type">Tipo de Usuario</label>
            <select id="user_type" name="user_type" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                <option value="user">Usuario Normal</option>
                <option value="admin">Administrador</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" id="email" name="email" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Ingresar</button>
        
        <div class="form-footer">
            <a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a>
            <span>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></span>
        </div>
    </form>
</div>

<style>
    .login-container {
        max-width: 500px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .login-form {
        margin-top: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    
    select.form-control {
        height: 46px;
    }
    
    .btn-primary {
        width: 100%;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
    }
    
    .form-footer {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        font-size: 14px;
    }
    
    .alert {
        padding: 12px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    .alert-success {
        background-color: #dff0d8;
        color: #3c763d;
    }
    
    .alert-danger {
        background-color: #f2dede;
        color: #a94442;
    }
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>