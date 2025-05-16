<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agro insumos El Jarillo - <?php echo $pageTitle ?? 'Inicio'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
       
        .session-indicator {
            position: fixed;
            top: 15px;
            right: 15px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
       
        .login-btn {
            color: #333;
            padding: 8px 15px;
            border-radius: 4px;
            background: #f8f8f8;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }
        
        .login-btn:hover {
            background: #e9e9e9;
            text-decoration: none;
        }
        
        .register-btn {
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            background: #4CAF50;
            transition: all 0.3s;
            margin-left: 10px;
        }
        
        .register-btn:hover {
            background: #45a049;
            text-decoration: none;
        }
        
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #4CAF50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .logout-btn {
            color: #fff;
            padding: 8px 15px;
            border-radius: 4px;
            background: #f44336;
            transition: all 0.3s;
            margin-left: 10px;
        }
        
        .logout-btn:hover {
            background: #d32f2f;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php session_start(); ?>
    
    
    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="session-indicator" title="Sesión activa">
            <i class="fas fa-circle" style="color: #4CAF50; font-size: 10px;"></i>
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
        </div>
    <?php endif; ?>

    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="index.php">
                    <img src="assets/images/logo.png" alt="Agro insumos El Jarillo">
                    <span>Agro insumos El Jarillo</span>
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                    <li><a href="carrito.php" class="cart-link">Carrito (<span id="cart-count">0</span>)</a></li>
                    
                    <li class="auth-links">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <div class="user-menu">
                                <?php 
                                    // Verificar si el usuario es admin o usuario normal
                                    $dashboardLink = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'admin.php' : 'usuario.php';
                                ?>
                                <a href="<?php echo $dashboardLink; ?>">Mi cuenta</a>
                                <a href="logout.php" class="logout-btn">Cerrar sesión</a>
                            </div>
                        <?php else: ?>
                            <a href="login.php" class="login-btn">Ingresar</a>
                            <a href="registro.php" class="register-btn">Registrarse</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>