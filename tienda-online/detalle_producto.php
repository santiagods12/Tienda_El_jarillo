<?php
$pageTitle = "Detalles del Producto";
include 'includes/header.php';
include 'includes/database.php';

if(!isset($_GET['id'])) {
    header("Location: productos.php");
    exit();
}

$producto_id = $_GET['id'];

$database = new Database();
$conn = $database->getConnection();

$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$producto = $stmt->fetch();

if(!$producto) {
    header("Location: productos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($producto['nombre']); ?> - <?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        /* Estilos específicos para la página de detalles */
        .producto-detalle-container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .producto-header {
            display: flex;
            gap: 40px;
            margin-bottom: 30px;
        }

        .producto-imagen {
            flex: 1;
            max-width: 400px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .producto-imagen img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            max-height: 400px;
        }

        .producto-info {
            flex: 1;
            padding: 20px 0;
        }

        .producto-titulo {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .producto-precio {
            font-size: 1.8rem;
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .producto-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .producto-stock {
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .producto-categoria {
            background: #e8f4fc;
            color: #2980b9;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .producto-descripcion {
            margin-top: 30px;
            line-height: 1.6;
            color: #34495e;
        }

        .producto-acciones {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        @media (max-width: 768px) {
            .producto-header {
                flex-direction: column;
                gap: 20px;
            }
            
            .producto-imagen {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="producto-detalle-container">
    <div class="producto-header">
        <div class="producto-imagen">
            <?php
            $imagenMostrar = 'assets/img/products/default.jpg';
            if (!empty($producto['imagen'])) {
                $rutaImagen = 'assets/images/productos/' . basename($producto['imagen']);
                $imagenMostrar = file_exists($rutaImagen) ? $rutaImagen : $producto['imagen'];
            }
            ?>
            <img src="<?php echo $imagenMostrar; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
        </div>
        
        <div class="producto-info">
            <h1 class="producto-titulo"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
            <div class="producto-precio">$<?php echo number_format($producto['precio'], 2); ?></div>
            
            <div class="producto-meta">
                <span class="producto-stock">Disponibles: <?php echo $producto['stock']; ?></span>
                <span class="producto-categoria"><?php echo htmlspecialchars($producto['categoria']); ?></span>
            </div>
            
            <div class="producto-acciones">
                <button class="btn btn-primary add-to-cart" data-id="<?php echo $producto['id']; ?>">Agregar al carrito</button>
                <a href="productos.php" class="btn btn-secondary">Volver a productos</a>
            </div>
        </div>
    </div>
    
    <div class="producto-descripcion">
        <h3>Descripción detallada</h3>
        <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sistema de carrito
    const cartCount = document.getElementById('cart-count');
    let cartItems = JSON.parse(localStorage.getItem('cart')) || {};
    
    // Actualizar contador del carrito
    function updateCartCount() {
        const totalItems = Object.values(cartItems).reduce((total, qty) => total + qty, 0);
        if(cartCount) {
            cartCount.textContent = totalItems || '0';
        }
        
        // Actualizar sesión en el servidor
        fetch('update_cart_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({cart: cartItems})
        });
    }
    
    // Agregar al carrito
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const availableStock = <?php echo $producto['stock']; ?>;
            
            if ((cartItems[productId] || 0) >= availableStock) {
                alert('No hay suficiente stock disponible');
                return;
            }
            
            cartItems[productId] = (cartItems[productId] || 0) + 1;
            localStorage.setItem('cart', JSON.stringify(cartItems));
            updateCartCount();
            
            // Feedback visual
            const originalText = this.textContent;
            this.textContent = '✓ Agregado';
            
            setTimeout(() => {
                this.textContent = originalText;
            }, 2000);
        });
    });
    
    // Inicializar contador
    updateCartCount();
});
</script>
</body>
</html>