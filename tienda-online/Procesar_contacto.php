<?php
session_start();

// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/includes/database.php';

// Crear instancia de la base de datos
$database = new Database();
$pdo = $database->getConnection(); // Esta es la línea clave que faltaba

// Validar y sanitizar datos
$nombre = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
$telefono = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING));
$mensaje = trim(filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING));

// Validaciones
if (empty($nombre) || empty($email) || empty($mensaje)) {
    $_SESSION['form_data'] = $_POST;
    header('Location: contacto.php?error=empty');
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['form_data'] = $_POST;
    header('Location: contacto.php?error=email');
    exit();
}

// Guardar en base de datos
try {
    $stmt = $pdo->prepare("INSERT INTO contactos (nombre, email, telefono, mensaje, fecha) 
                          VALUES (:nombre, :email, :telefono, :mensaje, NOW())");
    
    $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':telefono' => $telefono,
        ':mensaje' => $mensaje
    ]);
    
    // Enviar email de notificación (opcional)
    $to = "tudestino@empresa.com";
    $subject = "Nuevo mensaje de contacto de $nombre";
    $message = "Has recibido un nuevo mensaje de contacto:\n\n";
    $message .= "Nombre: $nombre\n";
    $message .= "Email: $email\n";
    $message .= "Teléfono: $telefono\n\n";
    $message .= "Mensaje:\n$mensaje\n";
    
    $headers = "From: $email\r\nReply-To: $email";
    
    // Descomenta para habilitar el envío de emails
    // mail($to, $subject, $message, $headers);
    
    header('Location: contacto.php?success=1');
    exit();
    
} catch (PDOException $e) {
    // Registrar el error para diagnóstico
    error_log("Error en Procesar_contacto.php: " . $e->getMessage());
    $_SESSION['form_data'] = $_POST;
    header('Location: contacto.php?error=db');
    exit();
}
?>