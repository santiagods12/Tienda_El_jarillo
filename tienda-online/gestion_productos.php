<?php
session_start();

// Verificar si es administrador
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../includes/header.php';

// Conexión a la base de datos
require_once '../includes/database.php';
$database = new Database();
$pdo = $database->getConnection();

// Procesar eliminación de producto
if(isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $mensaje = "Producto eliminado correctamente";
}

// Obtener lista de productos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY fecha_creacion DESC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-container">
    <div class="admin-sidebar">
        <h3>Panel de Administración</h3>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="active"><a href="productos.php"><i class="fas fa-boxes"></i> Productos</a></li>
            <li><a href="pedidos.php"><i class="fas fa-clipboard-list"></i> Pedidos</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
        </ul>
    </div>
    
    <div class="admin-content">
        <div class="admin-header">
            <h2>Gestión de Productos</h2>
            <a href="agregar_producto.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Agregar Producto
            </a>
        </div>
        
        <?php if(isset($mensaje)): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        
        <div class="productos-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($productos as $producto): ?>
                    <tr>
                        <td><?php echo $producto['id']; ?></td>
                        <td>
                            <?php if($producto['imagen']): ?>
                                <img src="../assets/images/productos/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" width="50">
                            <?php else: ?>
                                <div class="no-image">Sin imagen</div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td><?php echo $producto['stock']; ?></td>
                        <td><?php echo htmlspecialchars($producto['categoria']); ?></td>
                        <td class="actions">
                            <a href="editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="productos.php?eliminar=<?php echo $producto['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Mantener los estilos del admin-container y sidebar del dashboard */

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .productos-table {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    
    th {
        background: #f9f9f9;
        font-weight: 500;
    }
    
    .no-image {
        width: 50px;
        height: 50px;
        background: #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 0.8rem;
    }
    
    .actions {
        display: flex;
        gap: 5px;
    }
    
    .btn {
        padding: 5px 10px;
        border-radius: 4px;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-primary {
        background: #4CAF50;
    }
    
    .btn-edit {
        background: #2196F3;
    }
    
    .btn-danger {
        background: #f44336;
    }
    
    .alert {
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    
    .alert-success {
        background: #d4edda;
        color: #155724;
    }
</style>

<?php include '../includes/footer.php'; ?>