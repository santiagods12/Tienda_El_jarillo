<?php 

include 'includes/header.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Agro Insumos El Jarillo</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .contact-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .contact-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .contact-header h1 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .contact-header p {
            color: #7f8c8d;
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }
        
        /* Diseño del contenedor principal */
        .contact-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .contact-form {
            flex: 1;
            min-width: 300px;
        }
        
        .contact-info {
            flex: 1;
            min-width: 300px;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
        }
        
        /* Estilos del formulario */
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
        
        /* Botón de enviar */
        .form-submit {
            text-align: center;
        }
        
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #45a049;
        }
        
        /* Información de contacto */
        .contact-info h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        
        .info-item i {
            color: #4CAF50;
            margin-right: 15px;
            font-size: 20px;
            margin-top: 3px;
        }
        
        .info-item p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }
        
        /* Alertas */
        .alert {
            padding: 15px;
            margin: 20px auto;
            border-radius: 4px;
            text-align: center;
            max-width: 800px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .contact-wrapper {
                flex-direction: column;
            }
            
            .contact-info {
                margin-top: 30px;
            }
            
            .contact-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="contact-container">
        <div class="contact-header">
            <h1>Contáctanos</h1>
            <p>¿Tienes alguna pregunta o comentario? Completa el formulario y nos pondremos en contacto contigo.</p>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">
                ¡Gracias por contactarnos! Hemos recibido tu mensaje y te responderemos pronto.
            </div>
        <?php elseif(isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                $error = $_GET['error'];
                switch($error) {
                    case 'empty':
                        echo 'Todos los campos obligatorios deben ser completados.';
                        break;
                    case 'email':
                        echo 'Por favor ingresa un correo electrónico válido.';
                        break;
                    case 'db':
                        echo 'Error al guardar tu mensaje. Por favor intenta nuevamente.';
                        break;
                    default:
                        echo 'Hubo un error al enviar tu mensaje. Por favor intenta nuevamente.';
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="contact-wrapper">
            <form action="procesar_contacto.php" method="POST" class="contact-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre">Nombre completo *</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required
                               value="<?php echo isset($_SESSION['form_data']['nombre']) ? htmlspecialchars($_SESSION['form_data']['nombre']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Correo electrónico *</label>
                        <input type="email" id="email" name="email" class="form-control" required
                               value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control"
                           value="<?php echo isset($_SESSION['form_data']['telefono']) ? htmlspecialchars($_SESSION['form_data']['telefono']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="mensaje">Mensaje *</label>
                    <textarea id="mensaje" name="mensaje" rows="6" class="form-control" required><?php echo isset($_SESSION['form_data']['mensaje']) ? htmlspecialchars($_SESSION['form_data']['mensaje']) : ''; ?></textarea>
                </div>
                
                <div class="form-submit">
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </div>
            </form>
            
            <div class="contact-info">
                <h3>Información de contacto</h3>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Dirección: El jarillo, la enea, Calle principal.</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <p>Teléfono: +58 212 1234567</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <p>Email: info@agrojarillo.com</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <p>Horario: Lunes a Viernes de 7:30 AM - a 5:00 PM</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-info-circle"></i>
                    <p>Responderemos a tu consulta en un plazo máximo de 24 horas hábiles.</p>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // Limpiar datos del formulario después de mostrarlos
    if(isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    
    include 'includes/footer.php'; 
    ?>
</body>
</html>