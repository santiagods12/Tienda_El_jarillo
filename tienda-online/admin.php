<?php
// Procesar eliminación si se recibe un ID
if (isset($_GET['delete_id'])) {
    require_once __DIR__ . '/includes/database.php';
    $database = new Database();
    $conn = $database->getConnection();

    $deleteQuery = "DELETE FROM productos WHERE id = :id";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $_GET['delete_id'], PDO::PARAM_INT);

    if ($deleteStmt->execute()) {
        $_SESSION['message'] = "Producto eliminado correctamente";
        header('Location: admin.php');
        exit;
    }
}

// --- CONFIGURACIÓN DE PAGINACIÓN ---
$productosPorPagina = 10; // Define cuántos productos mostrar por página

// Obtener el número total de productos
require_once __DIR__ . '/includes/database.php';
$database = new Database();
$conn = $database->getConnection();
$totalProductosQuery = "SELECT COUNT(*) AS total FROM productos";
$totalProductosStmt = $conn->query($totalProductosQuery);
$totalProductosResult = $totalProductosStmt->fetch(PDO::FETCH_ASSOC);
$totalProductos = $totalProductosResult['total'];
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// Obtener la página actual
$paginaActual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) {
    $paginaActual = 1;
} elseif ($paginaActual > $totalPaginas && $totalPaginas > 0) {
    $paginaActual = $totalPaginas;
}

// Calcular el índice de inicio para la consulta
$indiceInicio = ($paginaActual - 1) * $productosPorPagina;

// Obtener productos para la página actual
$query = "SELECT * FROM productos ORDER BY id DESC LIMIT :inicio, :cantidad";
$stmt = $conn->prepare($query);
$stmt->bindParam(':inicio', $indiceInicio, PDO::PARAM_INT);
$stmt->bindParam(':cantidad', $productosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Incluir el header después de todo el procesamiento PHP
require_once __DIR__ . '/includes/header.php';
?>

<style>
    .admin-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        text-align: center;
        padding: 20px;
    }

    .button-group {
        display: flex;
        gap: 20px;
        margin: 30px 0;
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

    .btn-danger {
        background-color: #f44336;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
    }

    .btn-primary {
        background-color: #2196F3;
    }

    .btn-primary:hover {
        background-color: #0b7dda;
    }

    .products-table {
        width: 100%;
        max-width: 1000px;
        margin: 30px auto;
        border-collapse: collapse;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .products-table th, .products-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    .products-table th {
        background-color: #4CAF50;
        color: white;
    }

    .products-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .products-table tr:hover {
        background-color: #f1f1f1;
    }

    /* Estilos mejorados para los botones de acción */
    .products-table td:last-child {
        width: 180px; /* Ancho fijo para la columna de acciones */
        white-space: nowrap; /* Evita saltos de línea */
    }

    .action-buttons {
        display: flex;
        gap: 8px; /* Espacio entre botones */
    }

    .action-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        color: white;
        text-decoration: none;
        font-size: 14px;
        flex: 1; /* Hace que ambos botones tengan el mismo ancho */
        text-align: center;
        min-width: 70px; /* Ancho mínimo */
    }

    .success-message {
        background-color: #dff0d8;
        color: #3c763d;
        padding: 10px;
        border-radius: 4px;
        margin: 10px 0;
    }

    /* Estilos para la paginación */
    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .pagination a, .pagination span {
        padding: 8px 12px;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #333;
        border-radius: 4px;
    }

    .pagination a:hover {
        background-color: #f1f1f1;
    }

    .pagination .current {
        background-color: #4CAF50;
        color: white;
        border-color: #4CAF50;
    }

    .pagination .disabled {
        color: #777;
        pointer-events: none;
    }
</style>

<div class="admin-container">
    <h1>Panel de Administración</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Administrador)</p>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="success-message"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="button-group">
        <a href="Agregar_producto.php" class="btn-action btn-primary">➕ Agregar Producto</a>
        <a href="logout.php" class="btn-action btn-danger">🚪 Cerrar Sesión</a>
    </div>

    <h2>Lista de Productos</h2>
    <table class="products-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($productos) > 0): ?>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['id']) ?></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                        <td>$<?= number_format($producto['precio'], 2) ?></td>
                        <td><?= htmlspecialchars($producto['categoria']) ?></td>
                        <td><?= htmlspecialchars($producto['stock']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href='editar_producto.php?id=<?= $producto['id'] ?>' class='action-btn btn-primary'>Editar</a>
                                <a href='admin.php?delete_id=<?= $producto['id'] ?>' class='action-btn btn-danger'
                                   onclick='return confirm("¿Estás seguro de eliminar este producto?")'>Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">No hay productos para mostrar en esta página.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalPaginas > 1): ?>
        <div class="pagination">
            <?php if ($paginaActual > 1): ?>
                <a href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
            <?php else: ?>
                <span class="disabled">Anterior</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <?php if ($i === $paginaActual): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="?pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
            <?php else: ?>
                <span class="disabled">Siguiente</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>