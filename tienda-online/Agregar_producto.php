<?php 
  ob_start(); 
  require_once __DIR__ . '/includes/database.php'; 
  
  $database = new Database(); 
  $conn = $database->getConnection(); 

  error_reporting(E_ALL); 
  ini_set('display_errors', 1); 

  $uploadDir = __DIR__ . '/assets/images/productos/'; 
  $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; 
  $maxFileSize = 2 * 1024 * 1024; 

  if (!file_exists($uploadDir)) { 
    mkdir($uploadDir, 0755, true); 
  } 

  if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    try { 
        $requiredFields = ['nombre', 'descripcion', 'precio', 'categoria', 'stock']; 
        foreach ($requiredFields as $field) { 
            if (empty($_POST[$field])) { 
                throw new Exception("El campo $field es requerido"); 
            } 
        } 

        $nombre = trim($_POST['nombre']); 
        $descripcion = trim($_POST['descripcion']); 
        $precio = (float)$_POST['precio']; 
        $categoria = $_POST['categoria']; 
        $stock = (int)$_POST['stock']; 
        $imagen = null; 

        if ($precio <= 0 || $stock < 0) { 
            throw new Exception("Precio y stock deben ser valores positivos"); 
        } 

        if (!empty($_FILES['imagen']['name'])) { 
            $fileTmpPath = $_FILES['imagen']['tmp_name']; 
            $fileName = $_FILES['imagen']['name']; 
            $fileSize = $_FILES['imagen']['size']; 
            $fileType = $_FILES['imagen']['type']; 
            $fileNameCmps = explode(".", $fileName); 
            $fileExtension = strtolower(end($fileNameCmps)); 

            if (!in_array($fileType, $allowedTypes)) { 
                throw new Exception("Tipo de archivo no permitido. Use JPG, PNG o GIF."); 
            } 

            if ($fileSize > $maxFileSize) { 
                throw new Exception("El archivo es demasiado grande. Máximo 2MB permitidos."); 
            } 

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension; 
            $destPath = $uploadDir . $newFileName; 

            if (!move_uploaded_file($fileTmpPath, $destPath)) { 
                throw new Exception("Hubo un error al subir la imagen."); 
            } 
            $imagen = 'assets/images/productos/' . $newFileName; 
        } 

        $query = "INSERT INTO productos (nombre, descripcion, precio, categoria, stock, imagen)  
                  VALUES (:nombre, :descripcion, :precio, :categoria, :stock, :imagen)"; 
        
        $stmt = $conn->prepare($query); 
        $stmt->bindParam(':nombre', $nombre); 
        $stmt->bindParam(':descripcion', $descripcion); 
        $stmt->bindParam(':precio', $precio); 
        $stmt->bindParam(':categoria', $categoria); 
        $stmt->bindParam(':stock', $stock); 
        $stmt->bindParam(':imagen', $imagen); 

        if ($stmt->execute()) { 
            $_SESSION['success'] = "Producto agregado correctamente"; 
            unset($_SESSION['form_data']);
            if(ob_get_length() > 0) {
                ob_end_clean();
            }
            header('Location: admin.php'); 
            exit; 
        } else { 
            throw new Exception("Error al guardar el producto"); 
        } 
    } catch (PDOException $e) { 
        $_SESSION['error'] = "Error de base de datos: " . $e->getMessage(); 
    } catch (Exception $e) { 
        $_SESSION['error'] = $e->getMessage(); 
    } 

    $_SESSION['form_data'] = $_POST; 
    if(ob_get_length() > 0) {
        ob_end_clean();
    }
    header('Location: Agregar_producto.php'); 
    exit; 
  } 

  require_once __DIR__ . '/includes/header.php'; 

  $error = $_SESSION['error'] ?? null; 
  $form_data = $_SESSION['form_data'] ?? []; 

  unset($_SESSION['error']); 
  unset($_SESSION['form_data']); 
?> 

<?php 
ob_start(); 
require_once __DIR__ . '/includes/database.php'; 
  
$database = new Database(); 
$conn = $database->getConnection(); 

error_reporting(E_ALL); 
ini_set('display_errors', 1); 

$uploadDir = __DIR__ . '/assets/images/productos/'; 
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; 
$maxFileSize = 2 * 1024 * 1024; 

if (!file_exists($uploadDir)) { 
    mkdir($uploadDir, 0755, true); 
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    try { 
        $requiredFields = ['nombre', 'descripcion', 'precio', 'categoria', 'stock']; 
        foreach ($requiredFields as $field) { 
            if (empty($_POST[$field])) { 
                throw new Exception("El campo $field es requerido"); 
            } 
        } 

        $nombre = trim($_POST['nombre']); 
        $descripcion = trim($_POST['descripcion']); 
        $precio = (float)$_POST['precio']; 
        $categoria = $_POST['categoria']; 
        $stock = (int)$_POST['stock']; 
        $imagen = null; 

        if ($precio <= 0 || $stock < 0) { 
            throw new Exception("Precio y stock deben ser valores positivos"); 
        } 

        if (!empty($_FILES['imagen']['name'])) { 
            $fileTmpPath = $_FILES['imagen']['tmp_name']; 
            $fileName = $_FILES['imagen']['name']; 
            $fileSize = $_FILES['imagen']['size']; 
            $fileType = $_FILES['imagen']['type']; 
            $fileNameCmps = explode(".", $fileName); 
            $fileExtension = strtolower(end($fileNameCmps)); 

            if (!in_array($fileType, $allowedTypes)) { 
                throw new Exception("Tipo de archivo no permitido. Use JPG, PNG o GIF."); 
            } 

            if ($fileSize > $maxFileSize) { 
                throw new Exception("El archivo es demasiado grande. Máximo 2MB permitidos."); 
            } 

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension; 
            $destPath = $uploadDir . $newFileName; 

            if (!move_uploaded_file($fileTmpPath, $destPath)) { 
                throw new Exception("Hubo un error al subir la imagen."); 
            } 
            $imagen = 'assets/images/productos/' . $newFileName; 
        } 

        $query = "INSERT INTO productos (nombre, descripcion, precio, categoria, stock, imagen)  
                  VALUES (:nombre, :descripcion, :precio, :categoria, :stock, :imagen)"; 
        
        $stmt = $conn->prepare($query); 
        $stmt->bindParam(':nombre', $nombre); 
        $stmt->bindParam(':descripcion', $descripcion); 
        $stmt->bindParam(':precio', $precio); 
        $stmt->bindParam(':categoria', $categoria); 
        $stmt->bindParam(':stock', $stock); 
        $stmt->bindParam(':imagen', $imagen); 

        if ($stmt->execute()) { 
            $_SESSION['success'] = "Producto agregado correctamente"; 
            unset($_SESSION['form_data']);
            if(ob_get_length() > 0) {
                ob_end_clean();
            }
            header('Location: admin.php'); 
            exit; 
        } else { 
            throw new Exception("Error al guardar el producto"); 
        } 
    } catch (PDOException $e) { 
        $_SESSION['error'] = "Error de base de datos: " . $e->getMessage(); 
    } catch (Exception $e) { 
        $_SESSION['error'] = $e->getMessage(); 
    } 

    $_SESSION['form_data'] = $_POST; 
    if(ob_get_length() > 0) {
        ob_end_clean();
    }
    header('Location: Agregar_producto.php'); 
    exit; 
} 

require_once __DIR__ . '/includes/header.php'; 

$error = $_SESSION['error'] ?? null; 
$form_data = $_SESSION['form_data'] ?? []; 

unset($_SESSION['error']); 
unset($_SESSION['form_data']); 
?> 

<!DOCTYPE html> 
<html lang="es"> 
<head> 
  <meta charset="UTF-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <title>Agregar Producto | Agropecuaria</title> 
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
    
    .form-group input[type="file"] {
        width: 100%;
        padding: 0.5rem;
    }
    
    .file-hint {
        font-size: 0.85rem;
        color: var(--gris-medio);
        margin-top: 0.5rem;
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
            background:rgb(255, 0, 0);
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 6px;
    }
    
    .alert-error {
        background-color: #ffebee;
        color: #c62828;
        border-left: 4px solid #f44336;
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
    <h1 class="page-title">Agregar Nuevo Producto</h1> 
    
    <?php if ($error): ?> 
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div> 
    <?php endif; ?> 
    
    <form method="POST" action="Agregar_producto.php" enctype="multipart/form-data"> 
        <div class="form-group"> 
            <label for="nombre">Nombre del Producto:</label> 
            <input type="text" id="nombre" name="nombre"  
                   value="<?= htmlspecialchars($form_data['nombre'] ?? '') ?>" required> 
        </div> 
          
        <div class="form-group"> 
            <label for="descripcion">Descripción:</label> 
            <textarea id="descripcion" name="descripcion" required><?= 
                htmlspecialchars($form_data['descripcion'] ?? '') 
            ?></textarea> 
        </div> 
          
        <div class="form-group"> 
            <label for="precio">Precio ($):</label> 
            <input type="number" id="precio" name="precio" step="0.01" min="0"  
                   value="<?= htmlspecialchars($form_data['precio'] ?? '') ?>" required> 
        </div> 
          
        <div class="form-group"> 
            <label for="categoria">Categoría:</label> 
            <select id="categoria" name="categoria" required> 
                <option value="" disabled <?= empty($form_data['categoria']) ? 'selected' : '' ?>>Seleccione una categoría...</option>
                <option value="semillas" <?= ($form_data['categoria'] ?? '') === 'semillas' ? 'selected' : '' ?>>Semillas</option> 
                <option value="fertilizantes" <?= ($form_data['categoria'] ?? '') === 'fertilizantes' ? 'selected' : '' ?>>Fertilizantes</option> 
                <option value="riego" <?= ($form_data['categoria'] ?? '') === 'riego' ? 'selected' : '' ?>>Riego</option> 
                <option value="alimentos" <?= ($form_data['categoria'] ?? '') === 'alimentos' ? 'selected' : '' ?>>Alimentos</option> 
                <option value="insecticida" <?= ($form_data['categoria'] ?? '') === 'insecticida' ? 'selected' : '' ?>>Insecticida</option> 
                <option value="herramientas" <?= ($form_data['categoria'] ?? '') === 'herramientas' ? 'selected' : '' ?>>Herramientas</option> 
            </select> 
        </div> 
          
        <div class="form-group"> 
            <label for="stock">Stock:</label> 
            <input type="number" id="stock" name="stock" min="0"  
                   value="<?= htmlspecialchars($form_data['stock'] ?? '') ?>" required> 
        </div> 
          
        <div class="form-group"> 
            <label for="imagen">Imagen del Producto:</label> 
            <input type="file" id="imagen" name="imagen" accept="image/jpeg,image/png,image/gif">
            <div class="file-hint">Formatos aceptados: JPG, PNG, GIF (Máx. 2MB)</div> 
        </div> 
          
        <div class="form-actions"> 
            <button type="submit" class="btn btn-primary">Guardar Producto</button> 
            <a href="admin.php" class="btn btn-secondary">Cancelar</a> 
        </div> 
    </form> 
</div>

<?php include __DIR__ . '/includes/footer.php'; ?> 
<?php 
    if(ob_get_level() > 0) {
        ob_end_flush(); 
    }
?>
</body> 
</html>