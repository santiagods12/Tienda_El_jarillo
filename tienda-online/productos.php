<?php
$pageTitle = "Productos";
include 'includes/header.php';
include 'includes/database.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear instancia de la base de datos
$database = new Database();
$conn = $database->getConnection();

// Obtener productos
$categoria = $_GET['categoria'] ?? '';
$where = $categoria ? "WHERE categoria = :categoria" : "";
$sql = "SELECT * FROM productos $where ORDER BY nombre";
$stmt = $conn->prepare($sql);
if($categoria) {
    $stmt->bindParam(':categoria', $categoria);
}
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        .product-filters {
            margin-top: 20px;
        }
        .product-filters a {
            margin-right: 15px;
            padding: 5px 10px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .product-filters a.active {
            background-color: #4CAF50;
            color: white;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: 150px;
            object-fit: contain;
        }
        .price {
            font-weight: bold;
            color: #4CAF50;
            font-size: 1.2em;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        .btn-details {
            background-color: #2196F3;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .added-to-cart {
            background-color: #8BC34A;
        }
    </style>
</head>
<body>

<div class="product-filters">
    <a href="productos.php" class="<?php echo empty($categoria) ? 'active' : ''; ?>">Todos</a>
    <a href="productos.php?categoria=semillas" class="<?php echo $categoria == 'semillas' ? 'active' : ''; ?>">Semillas</a>
    <a href="productos.php?categoria=fertilizantes" class="<?php echo $categoria == 'fertilizantes' ? 'active' : ''; ?>">Fertilizantes</a>
    <a href="productos.php?categoria=alimentos" class="<?php echo $categoria == 'alimentos' ? 'active' : ''; ?>">Alimentos</a>
    <a href="productos.php?categoria=Insecticidas" class="<?php echo $categoria == 'Insecticidas' ? 'active' : ''; ?>">Insecticidas</a>
    <a href="productos.php?categoria=Herramientas" class="<?php echo $categoria == 'Herramientas' ? 'active' : ''; ?>">Herramientas</a>
    <a href="productos.php?categoria=Riego" class="<?php echo $categoria == 'Riego' ? 'active' : ''; ?>">Riego</a>
</div>

<div class="products-grid">
    <?php foreach($productos as $producto): ?>
    <div class="product-card">
        <?php
        $imagenMostrar = 'assets/img/products/default.jpg';
        if (!empty($producto['imagen'])) {
            $rutaImagen = 'assets/images/productos/' . basename($producto['imagen']);
            if (file_exists($rutaImagen)) {
                $imagenMostrar = $rutaImagen;
            } elseif (file_exists($producto['imagen'])) {
                $imagenMostrar = $producto['imagen'];
            }
        }
        ?>
        <img src="<?php echo $imagenMostrar; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
        <p class="price">$<?php echo number_format($producto['precio'], 2); ?></p>
        <p class="stock">Disponibles: <?php echo $producto['stock']; ?></p>
        <button class="btn add-to-cart" data-id="<?php echo $producto['id']; ?>">Agregar al carrito</button>
        <a href="detalle_producto.php?id=<?php echo $producto['id']; ?>" class="btn-details">Ver detalles</a>
    </div>
    <?php endforeach; ?>
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
        cartCount.textContent = totalItems || '0';
        
        // También actualizar el carrito en la sesión del servidor
        fetch('update_cart_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({cart: cartItems})
        });
    }
    
    // Inicializar contador
    updateCartCount();
    
    // Agregar al carrito
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            
            // Verificar si hay suficiente stock
            const stockElement = this.closest('.product-card').querySelector('.stock');
            const availableStock = parseInt(stockElement.textContent.replace('Disponibles: ', ''));
            
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
            this.classList.add('added-to-cart');
            
            setTimeout(() => {
                this.textContent = originalText;
                this.classList.remove('added-to-cart');
            }, 2000);
        });
    });
});
</script>
</body>
</html>