<?php
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/DocumentController.php';
$usuario = AuthMiddleware::getUser();
$documentController = new DocumentController();
$usuarios = $documentController->obtenerUsuarios();
$areas = $documentController->obtenerAreas();
$base_url = '/project/public';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Documento - UTTECAM</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            animation: slideDown 0.5s ease-out;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            font-style: italic;
            letter-spacing: 1px;
        }
        
        .page-title {
            font-size: 1.2rem;
            font-weight: 500;
            opacity: 0.9;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-name {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        
        .btn-logout {
            background: linear-gradient(135deg, #ff8c42 0%, #ff7b2e 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: linear-gradient(135deg, #ff7b2e 0%, #ff6a1a 100%);
            transform: translateY(-1px);
        }
        
        .nav {
            background: white;
            padding: 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-bottom: 3px solid #4a7c59;
            animation: slideDown 0.6s ease-out;
        }
        
        .nav ul {
            list-style: none;
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .nav li {
            flex: 1;
        }
        
        .nav a {
            display: block;
            text-decoration: none;
            color: #4a7c59;
            font-weight: 600;
            padding: 1rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .nav a:hover,
        .nav a.active {
            background: #f8f9fa;
            border-bottom-color: #ff8c42;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 2rem;
            animation: fadeInUp 0.7s ease-out;
        }
        
        .card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border-top: 4px solid #4a7c59;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .form-header h2 {
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .form-header p {
            color: #666;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            animation: fadeIn 0.8s ease-out;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #4a7c59;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 124, 89, 0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-file {
            width: 100%;
            padding: 0.75rem;
            border: 2px dashed #4a7c59;
            border-radius: 8px;
            background: #f8f9fa;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-file:hover {
            background: #e9ecef;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .urgencia-options {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .radio-option:hover {
            border-color: #4a7c59;
        }
        
        .radio-option input[type="radio"]:checked + label {
            color: #4a7c59;
            font-weight: 600;
        }
        
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #4a7c59 0%, #5a8c69 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-submit:hover {
            background: linear-gradient(135deg, #5a8c69 0%, #6a9c79 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 124, 89, 0.3);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .error-message,
        .success-message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            animation: slideInDown 0.5s ease-out;
        }
        
        .error-message {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }
        
        .success-message {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
            color: white;
        }
        
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 9999;
            animation: slideInRight 0.5s ease-out;
            max-width: 300px;
        }
        
        .toast-success {
            background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        }
        
        .toast-error {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        }
        
        .footer {
            background: #6c757d;
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: 3rem;
        }
        
        /* Animaciones */
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideInDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .urgencia-options {
                flex-direction: column;
            }
            
            .container {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <div class="logo">UTTECAM</div>
            <div class="page-title">Subir Documento</div>
        </div>
        <div class="user-info">
            <span class="user-name">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></span>
            <form action="<?php echo $base_url; ?>/logout" method="POST" style="display: inline;">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    
    <div class="nav">
        <ul>
            <li><a href="<?php echo $base_url; ?>/dashboard">Dashboard</a></li>
            <li><a href="<?php echo $base_url; ?>/documentos">Documentos</a></li>
            <li><a href="<?php echo $base_url; ?>/notificaciones">Notificaciones</a></li>
            <?php if ($usuario['rol'] === 'admin'): ?>
                <li><a href="<?php echo $base_url; ?>/usuarios">Usuarios</a></li>
            <?php endif; ?>
            <li><a href="<?php echo $base_url; ?>/perfil">Perfil</a></li>
        </ul>
    </div>
    
    <div class="container">
        <div class="card">
            <div class="form-header">
                <h2>Subir Nuevo Documento</h2>
                <p>Complete todos los campos para enviar el documento</p>
            </div>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    ⚠ <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">
                    ✓ <?php echo htmlspecialchars(urldecode($_GET['success'])); ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo $base_url; ?>/subir-documento" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <label for="folio">Folio *</label>
                        <input type="text" id="folio" name="folio" class="form-input" required placeholder="Ej: MEM-2025-001">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_documento">Fecha del Documento *</label>
                        <input type="date" id="fecha_documento" name="fecha_documento" class="form-input" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="entidad_productora">Entidad Productora</label>
                    <input type="text" id="entidad_productora" name="entidad_productora" class="form-input" placeholder="Ej: Dirección General">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="destinatario_id">Destinatario *</label>
                        <select id="destinatario_id" name="destinatario_id" class="form-select" required>
                            <option value="">Seleccionar destinatario</option>
                            <?php foreach ($usuarios as $user): ?>
                                <option value="<?php echo $user['id']; ?>">
                                    <?php echo htmlspecialchars($user['nombre'] . ' (' . $user['correo'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="area_id">Área de Origen *</label>
                        <select id="area_id" name="area_id" class="form-select" required>
                            <option value="">Seleccionar área</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?php echo $area['id']; ?>">
                                    <?php echo htmlspecialchars($area['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="area_destino_id">Área Destino *</label>
                    <select id="area_destino_id" name="area_destino_id" class="form-select" required>
                        <option value="">Seleccionar área destino</option>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo $area['id']; ?>">
                                <?php echo htmlspecialchars($area['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="contenido">Contenido del Documento *</label>
                    <textarea id="contenido" name="contenido" class="form-textarea" required placeholder="Escriba el contenido del memorando..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="documento">Archivo PDF *</label>
                    <input type="file" id="documento" name="documento" class="form-file" accept=".pdf" required>
                    <small style="color: #666; font-size: 0.9rem;">Máximo 10MB, solo archivos PDF</small>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="fecha_requerida_respuesta">Fecha Requerida de Respuesta</label>
                        <input type="date" id="fecha_requerida_respuesta" name="fecha_requerida_respuesta" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_limite">Fecha Límite</label>
                        <input type="date" id="fecha_limite" name="fecha_limite" class="form-input">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Urgencia *</label>
                    <div class="urgencia-options">
                        <div class="radio-option">
                            <input type="radio" id="ordinario" name="urgencia" value="ordinario" checked>
                            <label for="ordinario">📄 Ordinario</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="urgente" name="urgencia" value="urgente">
                            <label for="urgente">⭐ Urgente</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">📤 Enviar Documento</button>
            </form>
        </div>
    </div>
    
    <div class="footer">
        © 2025 UTTECAM. Todos los derechos reservados.
    </div>
    
    <script>
        // Función para mostrar notificaciones toast
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 500);
            }, 3000);
        }
        
        <?php if (isset($_GET['error'])): ?>
            showToast('<?php echo htmlspecialchars(urldecode($_GET['error'])); ?>', 'error');
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            showToast('<?php echo htmlspecialchars(urldecode($_GET['success'])); ?>', 'success');
        <?php endif; ?>
    </script>
</body>
</html>
