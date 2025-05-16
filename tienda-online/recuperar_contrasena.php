_<?php
require_once 'includes/header.php';
?>

<div class="password-recovery-container">
    <div class="recovery-box">
        <h2>Recuperar Contraseña</h2>
        <p>Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>
        
        <form action="procesar_recuperacion.php" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>
            
            <button type="submit" class="btn-recovery">Enviar Enlace</button>
        </form>
        
        <div class="links">
            <a href="login.php">Volver al Login</a>
        </div>
    </div>
</div>

<style>
    .password-recovery-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 70vh;
        padding: 20px;
    }
    
    .recovery-box {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        padding: 30px;
        width: 100%;
        max-width: 450px;
    }
    
    .recovery-box h2 {
        color: #4CAF50;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .recovery-box p {
        color: #666;
        margin-bottom: 25px;
        text-align: center;
    }
    
    .form-control {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    
    .btn-recovery {
        width: 100%;
        padding: 12px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .btn-recovery:hover {
        background: #45a049;
    }
    
    .links {
        margin-top: 20px;
        text-align: center;
    }
    
    .links a {
        color: #4CAF50;
        text-decoration: none;
    }
    
    .links a:hover {
        text-decoration: underline;
    }
</style>

<?php
require_once 'includes/footer.php';
?>