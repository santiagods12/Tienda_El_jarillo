<?php
require_once __DIR__ . '/includes/header.php';
?>

<style>
    .user-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        text-align: center;
    }
    
    .button-group {
        display: flex;
        gap: 20px;
        margin-top: 30px;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .btn-action {
        background-color: #4CAF50;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        font-size: 16px;
        transition: all 0.3s;
        min-width: 180px;
    }
    
    .btn-action:hover {
        background-color: #45a049;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .btn-logout {
        background-color: #f44336;
    }
    
    .btn-logout:hover {
        background-color: #d32f2f;
    }
</style>

<div class="user-container">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <p>Gestiona tu cuenta en nuestra tienda</p>
    
    <div class="button-group">
        <a href="../tienda-online/productos.php" class="btn-action">🛍️ Ir a la Tienda</a>
        <a href="../tienda-online/carrito.php" class="btn-action">🛒 Ver Carrito</a>
        <a href="logout.php" class="btn-action btn-logout">🚪 Cerrar Sesión</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>