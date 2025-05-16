<?php
session_start();

if(isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    // Destruir sesión completamente
    $_SESSION = array();
    
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    header('Location: login.php');
    exit();
}

// Mostrar confirmación si no viene del diálogo
echo "<script>
    if(confirm('¿Estás seguro que deseas cerrar sesión?')) {
        window.location.href = 'logout.php?confirm=true';
    } else {
        window.history.back();
    }
</script>";
exit();
?>