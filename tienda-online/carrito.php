<?php 
$pageTitle = "Carrito de Compras";
include 'includes/header.php'; 
include 'includes/database.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Crear instancia de la base de datos y obtener conexión
$database = new Database();
$conn = $database->getConnection();

// Obtener productos del carrito desde la sesión
$cartItems = $_SESSION['cart'] ?? [];

// Inicializar variables
$productosCarrito = [];
$total = 0.00;

if (!empty($cartItems)) {
    $placeholders = implode(',', array_fill(0, count($cartItems), '?'));
    $ids = array_keys($cartItems);
    
    $sql = "SELECT id, nombre, precio, imagen, stock FROM productos WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($ids);
    $productosCarrito = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($productosCarrito as $producto) {
        if(isset($cartItems[$producto['id']])) {
            $precio = (float)$producto['precio'];
            $cantidad = (int)$cartItems[$producto['id']];
            $total += $precio * $cantidad;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --danger-color: #f44336;
            --light-gray: #f8f9fa;
            --dark-gray: #343a40;
            --border-color: #dee2e6;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 0;
            margin: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        h1 {
            color: var(--dark-gray);
            text-align: center;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .empty-cart {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .empty-cart p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #666;
        }
        
        .cart-items {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--light-gray);
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .cart-product-info {
            display: flex;
            align-items: center;
        }
        
        .cart-product-info img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-right: 15px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
        }
        
        .product-name {
            font-weight: 500;
            color: var(--dark-gray);
        }
        
        .price {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .quantity-input {
            width: 60px;
            padding: 8px 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }
        
        .subtotal {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #3d8b40;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #0b7dda;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 13px;
        }
        
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        
        .cart-actions {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: var(--light-gray);
            border-top: 1px solid var(--border-color);
        }
        
        .total-row {
            font-weight: 600;
            background-color: var(--light-gray);
        }
        
        .total-row td {
            padding: 15px;
        }
        
        .text-right {
            text-align: right;
        }
        
        @media (max-width: 768px) {
            .cart-product-info {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .cart-product-info img {
                margin-bottom: 10px;
                margin-right: 0;
            }
            
            th, td {
                padding: 10px 8px;
            }
            
            .cart-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Tu Carrito de Compras</h1>

    <?php if (empty($productosCarrito)): ?>
        <div class="empty-cart">
            <p>No hay productos en tu carrito</p>
            <a href="productos.php" class="btn btn-primary">Explorar Productos</a>
        </div>
    <?php else: ?>
        <div class="cart-items">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productosCarrito as $producto): 
                        $precio = (float)$producto['precio'];
                        $cantidad = (int)$cartItems[$producto['id']];
                        $subtotal = $precio * $cantidad;
                    ?>
                    <tr>
                        <td>
                            <div class="cart-product-info">
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
                                <span class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></span>
                            </div>
                        </td>
                        <td class="price">$<?php echo number_format($precio, 2); ?></td>
                        <td>
                            <input type="number" min="1" max="<?php echo (int)$producto['stock']; ?>" 
                                   value="<?php echo $cantidad; ?>" 
                                   class="quantity-input" 
                                   data-id="<?php echo $producto['id']; ?>">
                        </td>
                        <td class="subtotal">$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <button class="btn btn-danger btn-remove" data-id="<?php echo $producto['id']; ?>">Eliminar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td colspan="2" class="subtotal">$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
            
            <div class="cart-actions">
                <a href="productos.php" class="btn btn-primary">Seguir Comprando</a>
                <a href="checkout.php" class="btn btn-secondary">Finalizar Compra</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtener carrito de localStorage
    let cartItems = JSON.parse(localStorage.getItem('cart')) || {};
    
    // Actualizar cantidades
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.getAttribute('data-id');
            const newQty = parseInt(this.value);
            const maxStock = parseInt(this.getAttribute('max'));
            
            if (isNaN(newQty) || newQty < 1) {
                this.value = 1;
                return;
            }
            
            if (newQty > maxStock) {
                alert('No hay suficiente stock disponible');
                this.value = maxStock;
                return;
            }
            
            cartItems[productId] = newQty;
            localStorage.setItem('cart', JSON.stringify(cartItems));
            
            // Actualizar sesión en el servidor
            fetch('update_cart_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({cart: cartItems})
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        });
    });
    
    // Eliminar items
    document.querySelectorAll('.btn-remove').forEach(button => {
        button.addEventListener('click', function() {
            if(confirm('¿Estás seguro de eliminar este producto del carrito?')) {
                const productId = this.getAttribute('data-id');
                delete cartItems[productId];
                localStorage.setItem('cart', JSON.stringify(cartItems));
                
                fetch('update_cart_session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({cart: cartItems})
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        });
    });
});
</script>
</body>
</html>