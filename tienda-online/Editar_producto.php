<?php
ob_start();
require_once __DIR__ . '/includes/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$database = new Database();
$conn = $database->getConnection();

$producto = null;
if (isset($_GET['id'])) {
    try {
        $query = "SELECT * FROM productos WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            $_SESSION['error'] = "Producto no encontrado";
            ob_end_clean();
            header('Location: admin.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al obtener producto: " . $e->getMessage();
        ob_end_clean();
        header('Location: admin.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $requiredFields = ['id', 'nombre', 'descripcion', 'precio', 'categoria', 'stock'];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field])) {
                throw new Exception("El campo $field es requerido");
            }
        }

        $id = (int)$_POST['id'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $precio = (float)$_POST['precio'];
        $categoria = $_POST['categoria'];
        $stock = (int)$_POST['stock'];

        if ($precio <= 0 || $stock < 0) {
            throw new Exception("Precio y stock deben ser valores positivos");
        }

        $query = "UPDATE productos SET 
                 nombre = :nombre,
                 descripcion = :descripcion,
                 precio = :precio,
                 categoria = :categoria,
                 stock = :stock
                 WHERE id = :id";
        
        $stmt = $conn->prepare($query);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':precio', $precio);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Producto actualizado correctamente";
            ob_end_clean();
            header('Location: admin.php');
            exit;
        } else {
            throw new Exception("No se pudo actualizar el producto");
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error de base de datos: " . $e->getMessage();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    $_SESSION['form_data'] = $_POST;
    ob_end_clean();
    header('Location: Editar_producto.php?id=' . $_POST['id']);
    exit;
}

ob_end_flush();
require_once __DIR__ . '/includes/header.php';

$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
$form_data = $_SESSION['form_data'] ?? [];

unset($_SESSION['error']);
unset($_SESSION['success']);
unset($_SESSION['form_data']);

if ($producto && !empty($form_data)) {
    $producto = array_merge($producto, $form_data);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto | Agropecuaria</title>
    <style>
        :root {
            --verde-oscuro: #2E7D32;
            --verde-medio: #4CAF50;
            --gris-oscuro: #333333;
            --gris-medio: #757575;
            --gris-claro: #E0E0E0;
            --blanco: #FFFFFF;
            --sombra: rgba(0, 0, 0, 0.1);
            --sombra-hover: rgba(0, 0, 0, 0.15);
            --rojo-cancelar: #E53935;
            --rojo-cancelar-hover: #C62828;
        }
          
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .form-container {
            max-width: 700px;
            margin: 2rem auto;
            padding: 2.5rem;
            background: var(--blanco);
            border-radius: 10px;
            box-shadow: 0 5px 15px var(--sombra);
        }
        
        .page-title {
            color: var(--verde-oscuro);
            text-align: center;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
        }
        
        .page-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, var(--verde-medio), var(--verde-oscuro));
            margin: 0.8rem auto 0;
            border-radius: 2px;
        }
        
        .form-group {
            margin-bottom: 1.8rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 500;
            color: var(--verde-oscuro);
        }
        
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--gris-claro);
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--verde-medio);
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2.5rem;
        }
        
        .btn {
            padding: 0.8rem 1.8rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            box-shadow: 0 2px 5px var(--sombra);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px var(--sombra-hover);
        }
        
        .btn-primary {
            background-color: var(--verde-medio);
            color: var(--blanco);
        }
        
        .btn-primary:hover {
            background-color: var(--verde-oscuro);
        }
        
        .btn-secondary {
            background-color: var(--blanco);
            color: var(--rojo-cancelar);
            border: 1px solid var(--rojo-cancelar);
            padding: 0.7rem 1.5rem;
        }
        
        .btn-secondary:hover {
            background-color: var(--rojo-cancelar);
            color: var(--blanco);
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 6px;
        }
        
        .alert-success {
            background-color: #E8F5E9;
            color: var(--verde-oscuro);
            border-left: 4px solid var(--verde-medio);
        }
        
        .alert-error {
            background-color: #FFEBEE;
            color: #C62828;
            border-left: 4px solid #F44336;
        }
        
        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23757575' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
        }
        
        @media (max-width: 768px) {
            .form-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1 class="page-title">Editar Producto</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if ($producto): ?>
        <form method="POST" action="Editar_producto.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre" 
                       value="<?= htmlspecialchars($producto['nombre']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?= 
                    htmlspecialchars($producto['descripcion']) 
                ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="precio">Precio ($):</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0.01"
                       value="<?= htmlspecialchars($producto['precio']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <option value="semillas" <?= ($producto['categoria'] ?? '') === 'semillas' ? 'selected' : '' ?>>Semillas</option>
                    <option value="fertilizantes" <?= ($producto['categoria'] ?? '') === 'fertilizantes' ? 'selected' : '' ?>>Fertilizantes</option>
                    <option value="riego" <?= ($producto['categoria'] ?? '') === 'riego' ? 'selected' : '' ?>>Riego</option>
                    <option value="alimentos" <?= ($producto['categoria'] ?? '') === 'alimentos' ? 'selected' : '' ?>>Alimentos</option>
                    <option value="insecticida" <?= ($producto['categoria'] ?? '') === 'insecticida' ? 'selected' : '' ?>>Insecticida</option>
                    <option value="herramientas" <?= ($producto['categoria'] ?? '') === 'herramientas' ? 'selected' : '' ?>>Herramientas</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" min="0"
                       value="<?= htmlspecialchars($producto['stock']) ?>" required>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                <a href="admin.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
        <?php else: ?>
        <p>No se ha seleccionado un producto válido para editar.</p>
        <a href="admin.php" class="btn btn-secondary">Volver al panel</a>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>